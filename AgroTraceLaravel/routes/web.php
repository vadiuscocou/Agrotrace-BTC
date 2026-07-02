<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Project;
use App\Models\Milestone;
use App\Models\Investment;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

// Public Project Routes
Route::get('/projects', function () {
    return view('projects', ['projects' => Project::with('user')->get()]);
});
Route::get('/verification', function () {
    return view('verification', ['milestones' => Milestone::with('project')->where('status', 'validated')->get()]);
});
Route::get('/impact-map', function () {
    return view('impact-map', [
        'projects' => Project::with(['user', 'investments'])
            ->whereIn('status', ['funded', 'in_progress', 'completed'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
    ]);
});
Route::get('/terms', function () {
    return view('terms');
})->name('terms');

// Dashboard Route (Dynamic)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return view('admin.dashboard', [
                'projects' => Project::all(), 
                'milestones' => Milestone::with('project')->get(),
                'totalInvested' => Investment::sum('amount_fcfa'),
                'totalFeesSats' => Investment::sum('fee_sats')
            ]);
        } elseif ($user->role === 'project_owner') {
            return view('owner.dashboard', ['projects' => $user->projects()->with('milestones')->get()]);
        } else {
            // Investor
            return view('dashboard', [
                'investments' => $user->investments()->with('project')->get()
            ]);
        }
    })->name('dashboard');

    Route::get('/invoices', function () {
        if (Auth::user()->role === 'project_owner') abort(403);
        $investments = Auth::user()->investments()->with('project')->where('status', 'paid')->orderByDesc('created_at')->get();
        return view('investor.invoices', ['investments' => $investments]);
    })->name('invoices.index');

    Route::post('/invest/{project_id}', function ($project_id) {
        $amountFcfa = (int) request('amount_fcfa');
        
        $project = Project::findOrFail($project_id);
        
        // Règles d'investissement strictes
        $remaining = $project->remaining_amount;
        $minInvestment = max(1, intval($project->target_amount_fcfa / 4));
        
        // Si le montant restant est inférieur au minimum théorique (25%), 
        // alors le minimum autorisé devient le montant restant (pour clôturer le projet).
        if ($remaining < $minInvestment) {
            $minInvestment = $remaining;
        }

        if ($amountFcfa < $minInvestment) {
            return back()->with('error', 'Le montant minimum pour investir dans ce projet est de ' . number_format($minInvestment) . ' FCFA.');
        }

        if ($amountFcfa > $remaining) {
            return back()->with('error', 'Vous ne pouvez pas investir plus que le montant restant (' . number_format($remaining) . ' FCFA).');
        }

        $amountSats = $amountFcfa * 6; // Taux fictif: 1 FCFA ≈ 6 SATS
        $feeSats = $amountSats * 0.02; // Frais de 2%

        try {
            $lnbits = new \App\Services\LNbitsService();
            $invoice = $lnbits->createInvoice($amountSats + $feeSats, "AgroTrace Inv. Projet #{$project_id}");
            
            $investment = Investment::create([
                'project_id' => $project_id,
                'user_id' => Auth::id(),
                'amount_fcfa' => $amountFcfa,
                'amount_sats' => $amountSats,
                'fee_sats' => $feeSats,
                'payment_request' => $invoice['payment_request'],
                'payment_hash' => $invoice['payment_hash'],
                'status' => 'pending'
            ]);

            return view('invest.pay', ['investment' => $investment]);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur API LNbits: ' . $e->getMessage());
        }
    });

    Route::get('/invest/{hash}/status', function ($hash) {
        $investment = Investment::where('payment_hash', $hash)->firstOrFail();
        
        if ($investment->status === 'paid') {
            return response()->json(['paid' => true]);
        }

        try {
            $lnbits = new \App\Services\LNbitsService();
            $isPaid = $lnbits->checkPaymentStatus($hash);
            
            if ($isPaid) {
                $investment->update(['status' => 'paid']);
                
                // Mettre à jour le statut du projet si nécessaire (ex: financé)
                $project = $investment->project;
                $totalInvested = $project->investments()->where('status', 'paid')->sum('amount_fcfa');
                if ($totalInvested >= $project->target_amount_fcfa) {
                    $project->update(['status' => 'funded']);
                }
                
                // Send Email to the investor
                try {
                    \Illuminate\Support\Facades\Mail::to($investment->user->email)->send(new \App\Mail\InvestmentConfirmed($investment));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Mail sending failed: " . $e->getMessage());
                }

                return response()->json(['paid' => true]);
            }
        } catch (\Exception $e) {
            // Fail silently during polling
        }

        return response()->json(['paid' => false]);
    });

    // Project Owner Actions
    Route::post('/projects', function () {
        if (Auth::user()->role !== 'project_owner') abort(403);
        
        $docPath = request()->file('document') ? request()->file('document')->store('documents', 'public') : null;

        $project = Project::create([
            'user_id' => Auth::id(),
            'title' => request('title'),
            'description' => request('description'),
            'region' => request('location'),
            'latitude' => request('latitude'),
            'longitude' => request('longitude'),
            'target_amount_fcfa' => request('budget_fcfa'),
            'supporting_documents' => $docPath,
            'status' => 'submitted'
        ]);

        $milestones = request('milestones', []);
        
        foreach ($milestones as $m) {
            if (!empty($m['title'])) {
                Milestone::create([
                    'project_id' => $project->id,
                    'title' => $m['title'],
                    'description' => $m['desc'] ?? '',
                    'amount_fcfa' => $m['amount'] ?? 0,
                    'status' => 'pending'
                ]);
            }
        }

        return redirect('/dashboard')->with('success', 'Projet créé avec succès et soumis pour étude.');
    });

    Route::post('/milestones/{id}/proof', function ($id) {
        if (Auth::user()->role !== 'project_owner') abort(403);
        
        $milestone = Milestone::findOrFail($id);
        if ($milestone->project->user_id !== Auth::id()) abort(403);

        $paths = [];
        if (request()->hasFile('proof_images')) {
            foreach (request()->file('proof_images') as $file) {
                $paths[] = $file->store('proofs', 'public');
            }
        }

        // Backward compatibility with single upload
        $singlePath = request()->file('proof_image') ? request()->file('proof_image')->store('proofs', 'public') : null;

        if ($singlePath && count($paths) === 0) {
            $paths[] = $singlePath;
        }

        $milestone->update([
            'status' => 'submitted',
            'proof_image' => $singlePath ?? ($paths[0] ?? null), // keep first image in old column for fallback
            'proof_images' => count($paths) > 0 ? $paths : null,
            'proof_notes' => request('proof_notes'),
        ]);

        return redirect('/dashboard')->with('success', 'Preuve soumise avec succès.');
    });

    Route::get('/projects/{id}/contract', function ($id) {
        $project = Project::findOrFail($id);
        
        $hasInvested = false;
        if (Auth::user()->role === 'investor') {
            $hasInvested = Auth::user()->investments()->where('project_id', $project->id)->exists();
        }

        if (Auth::user()->role !== 'admin' && $project->user_id !== Auth::id() && !$hasInvested) {
            abort(403, 'Accès non autorisé au contrat.');
        }
        return view('owner.contract', ['project' => $project]);
    })->name('projects.contract');

    Route::get('/investments/{id}/contract', function ($id) {
        $investment = Investment::with(['project.user', 'user'])->findOrFail($id);
        
        if (Auth::user()->role !== 'admin' && $investment->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à ce contrat nominatif.');
        }
        
        return view('investor.contract', ['investment' => $investment]);
    })->name('investments.contract');

    Route::get('/investments/{id}/invoice', function ($id) {
        $investment = Investment::with(['project.user', 'user'])->findOrFail($id);
        
        if (Auth::user()->role !== 'admin' && $investment->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette facture.');
        }
        
        if ($investment->status !== 'paid') {
            abort(403, 'Facture non disponible car le paiement n\'est pas confirmé.');
        }
        
        return view('investor.invoice', ['investment' => $investment]);
    })->name('investments.invoice');

    Route::post('/projects/{id}/generate-tranches', function ($id) {
        $project = Project::findOrFail($id);
        if ($project->user_id !== Auth::id()) abort(403);
        
        $totalToRepay = $project->target_amount_fcfa * 1.08;
        $trancheAmount = intval($totalToRepay / 3);
        
        for ($i = 1; $i <= 3; $i++) {
            App\Models\Repayment::create([
                'project_id' => $project->id,
                'amount_fcfa' => $trancheAmount,
                'amount_sats' => 0, // Mock for hackathon
                'due_date' => now()->addMonths($i),
                'status' => 'pending'
            ]);
        }
        
        return back()->with('status', 'Échéancier généré avec succès !');
    });

    Route::post('/repayments/{id}/pay', function (Request $request, $id) {
        $repayment = App\Models\Repayment::findOrFail($id);
        if ($repayment->project->user_id !== Auth::id()) abort(403);
        
        $bolt11 = $request->input('bolt11');
        // Hackathon Mock: We assume the lightning payment is processed successfully.
        
        $repayment->update(['status' => 'paid']);
        
        // If all 3 are paid, mark project as completed
        if ($repayment->project->repayments()->where('status', 'pending')->count() == 0) {
            $repayment->project->update(['status' => 'completed']);
        }

        return back()->with('status', 'Tranche remboursée et distribuée avec succès via Lightning !');
    });

    // Admin Actions
    Route::post('/projects/{id}/status', function ($id) {
        if (Auth::user()->role !== 'admin') abort(403);
        
        $project = Project::findOrFail($id);
        $status = request('status');
        $validStatuses = ['submitted', 'under_review', 'validated', 'awaiting_funding', 'funded', 'in_progress', 'completed'];
        
        if (in_array($status, $validStatuses)) {
            $project->update(['status' => $status]);
        }

        return redirect('/dashboard')->with('success', 'Statut du projet mis à jour avec succès.');
    });

    Route::post('/milestones/{id}/validate', function ($id) {
        if (Auth::user()->role !== 'admin') abort(403);
        
        $milestone = Milestone::findOrFail($id);
        
        // Simuler un enregistrement immuable sur la blockchain
        $txId = hash('sha256', $milestone->id . $milestone->status . time() . 'AgroTraceBTC');

        $milestone->update([
            'status' => 'validated',
            'blockchain_tx_id' => $txId
        ]);

        return redirect('/dashboard')->with('success', 'Jalon validé et ancré sur la blockchain avec succès ! (TX ID: ' . substr($txId, 0, 16) . '...)');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
