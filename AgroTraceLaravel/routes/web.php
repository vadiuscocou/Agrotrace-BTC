<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Project;
use App\Models\Milestone;
use App\Models\Investment;
use Illuminate\Http\Request;
use App\Http\Controllers\InvestmentController;

Route::post('/invest/{project_id}', [InvestmentController::class, 'store'])->name('investments.store');
/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

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

// Page de notification de succès (Inscription, Projet créé, Paiement reçu)
Route::get('/auth/success', function () {
    if (!session('type')) return redirect('/');
    return view('auth.success');
})->name('auth.success');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard dynamique selon le rôle
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
            return view('dashboard', [
                'investments' => $user->investments()->with('project')->get()
            ]);
        }
    })->name('dashboard');

    /* --- Actions Investisseur --- */

    Route::post('/invest/{project_id}', function ($project_id) {
        $amountFcfa = request('amount_fcfa', 50000);
        $amountSats = $amountFcfa * 6; // Taux 1 FCFA ≈ 6 SATS
        $feeSats = $amountSats * 0.02;

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
            return back()->with('error', 'Erreur API LNbits: ' . $e . getMessage());
        }
    });

    // Vérification du statut du paiement (Polling)
    Route::get('/invest/{hash}/status', function ($hash) {
        $investment = Investment::where('payment_hash', $hash)->firstOrFail();
        if ($investment->status === 'paid') return response()->json(['paid' => true]);

        try {
            $lnbits = new \App\Services\LNbitsService();
            if ($lnbits->checkPaymentStatus($hash)) {
                $investment->update(['status' => 'paid']);

                $project = $investment->project;
                $totalPaid = $project->investments()->where('status', 'paid')->sum('amount_fcfa');
                if ($totalPaid >= $project->target_amount_fcfa) {
                    $project->update(['status' => 'funded']);
                }
                return response()->json(['paid' => true]);
            }
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        });

    /* --- Actions Porteur de Projet (Coopérative) --- */

    // Afficher le formulaire de création
    Route::get('/projects/create', function () {
        if (Auth::user()->role !== 'project_owner') abort(403);
        return view('projects.create');
    })->name('projects.create');

    // Sauvegarder le projet
    Route::post('/projects', function (Request $request) {
        if (Auth::user()->role !== 'project_owner') abort(403);

        $docPath = $request->file('document') ? $request->file('document')->store('documents', 'public') : null;

        $project = Project::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'region' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'target_amount_fcfa' => $request->budget_fcfa,
            'supporting_documents' => $docPath,
            'status' => 'submitted'
        ]);

        $milestones = $request->milestones ?? [];
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

        // Redirection vers la page de succès
        return redirect()->route('auth.success')->with([
            'type' => 'project_created',
            'title' => 'Projet Soumis !',
            'message' => 'Votre projet a été enregistré et est en cours d\'étude pour ancrage blockchain.',
            'role' => 'project_owner'
        ]);
    });

    // Soumission de preuve
    Route::post('/milestones/{id}/proof', function ($id) {
        if (Auth::user()->role !== 'project_owner') abort(403);
        $milestone = Milestone::findOrFail($id);

        $path = request()->file('proof_image') ? request()->file('proof_image')->store('proofs', 'public') : null;
        $milestone->update([
            'status' => 'submitted',
            'proof_image' => $path,
            'proof_notes' => request('proof_notes'),
        ]);

        return redirect('/dashboard')->with('success', 'Preuve soumise avec succès.');
    });

    /* --- Actions Administrateur --- */

    Route::post('/projects/{id}/status', function ($id) {
        if (Auth::user()->role !== 'admin') abort(403);
        $project = Project::findOrFail($id);
        $project->update(['status' => request('status')]);
        return redirect('/dashboard')->with('success', 'Statut mis à jour.');
    });

    Route::post('/milestones/{id}/validate', function ($id) {
        if (Auth::user()->role !== 'admin') abort(403);
        Milestone::findOrFail($id)->update(['status' => 'validated']);
        return redirect('/dashboard')->with('success', 'Jalon validé on-chain !');
    });
});
/* --- Projet Contract */
Route::get('/projects/{id}/contract', function ($id) {
    $project = Project::findOrFail($id);
    if (Auth::user()->role !== 'admin' && $project->user_id !== Auth::id()) {
        abort(403, 'Accès non autorisé au contrat.');
    }
    return view('owner.contract', ['project' => $project]);
})->name('projects.contract'); // <--- Vérifie bien cette ligne

Route::get('/investments/{investment}/contract', [App\Http\Controllers\InvestmentController::class, 'contract'])
    ->name('investments.contract')
    ->middleware(['auth', 'verified']);
/* --- Profil Utilisateur --- */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
