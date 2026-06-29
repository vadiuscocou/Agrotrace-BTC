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

// Public Project Routes
Route::get('/projects', function () {
    return view('projects', ['projects' => Project::with('user')->get()]);
});
Route::get('/verification', function () {
    return view('verification', ['milestones' => Milestone::with('project')->where('status', 'validated')->get()]);
});
Route::get('/impact-map', function () {
    return view('impact-map', ['projects' => Project::whereIn('status', ['active', 'verified'])->get()]);
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
        $amount = request('amount_fcfa', 50000);
        Investment::create([
            'project_id' => $project_id,
            'user_id' => Auth::id(),
            'amount_fcfa' => $amount,
            'amount_sats' => $amount * 6, // fake rate
            'fee_sats' => ($amount * 6) * 0.02,
            'payment_hash' => 'fake_hash_' . time(),
            'status' => 'paid'
        ]);
        return redirect('/dashboard')->with('success', 'Investment confirmed via Lightning!');
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

    Route::post('/projects/{id}/repay', function ($id) {
        if (Auth::user()->role !== 'project_owner') abort(403);
        
        $project = Project::findOrFail($id);
        if ($project->user_id !== Auth::id()) abort(403);

        $project->update(['status' => 'completed']);

        return redirect('/dashboard')->with('success', 'Fonds distribués aux investisseurs avec succès via Lightning !');
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
        $milestone->update(['status' => 'validated']);

        return redirect('/dashboard')->with('success', 'Milestone validated and recorded to blockchain!');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
