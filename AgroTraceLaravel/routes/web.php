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

    Route::post('/invest/{project_id}', function ($project_id) {
        $amountFcfa = request('amount_fcfa', 50000);
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

        $path = request()->file('proof_image') ? request()->file('proof_image')->store('proofs', 'public') : null;

        $milestone->update([
            'status' => 'submitted',
            'proof_image' => $path,
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

    Route::post('/projects/{id}/repay', function ($id) {
        if (Auth::user()->role !== 'project_owner') abort(403);
        
        $project = Project::findOrFail($id);
        if ($project->user_id !== Auth::id()) abort(403);

        $bolt11 = request('bolt11');

        if (!$bolt11) {
            return back()->with('error', 'Veuillez fournir une facture Lightning.');
        }

        try {
            $lnbits = new \App\Services\LNbitsService();
            $lnbits->payInvoice($bolt11);
            
            $project->update(['status' => 'completed']);

            return redirect('/dashboard')->with('success', 'Paiement effectué ! Fonds envoyés sur votre portefeuille avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur de paiement LNbits: ' . $e->getMessage());
        }
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
