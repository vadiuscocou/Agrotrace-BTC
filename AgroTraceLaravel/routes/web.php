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

// Dashboard Route (Dynamic)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return view('admin.dashboard', ['projects' => Project::all(), 'milestones' => Milestone::with('project')->get()]);
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
        
        $project = Project::create([
            'user_id' => Auth::id(),
            'title' => request('title'),
            'description' => request('description'),
            'region' => request('location'), // mapped from form input 'location'
            'target_amount_fcfa' => request('budget_fcfa'), // mapped from form input 'budget_fcfa'
            'status' => 'pending'
        ]);

        // Create default milestones for the new project
        $milestones = [
            ['title' => 'Initial Assessment & Seeds', 'description' => 'Purchase of seeds and initial field preparation.', 'project_id' => $project->id],
            ['title' => 'Irrigation System Setup', 'description' => 'Installation of water pumps and drip irrigation.', 'project_id' => $project->id],
            ['title' => 'Harvesting & Storage', 'description' => 'Final harvest and secure storage of the crop.', 'project_id' => $project->id],
        ];

        foreach ($milestones as $m) {
            Milestone::create(array_merge($m, ['status' => 'pending']));
        }

        return redirect('/dashboard')->with('success', 'Project created and pending validation.');
    });

    Route::post('/milestones/{id}/proof', function ($id) {
        if (Auth::user()->role !== 'project_owner') abort(403);
        
        $milestone = Milestone::findOrFail($id);
        if ($milestone->project->user_id !== Auth::id()) abort(403);

        $milestone->update([
            'status' => 'submitted',
            // fake proof saving logic could go here
        ]);

        return redirect('/dashboard')->with('success', 'Proof submitted successfully and is awaiting validation.');
    });

    // Admin Actions
    Route::post('/projects/{id}/approve', function ($id) {
        if (Auth::user()->role !== 'admin') abort(403);
        
        $project = Project::findOrFail($id);
        $project->update(['status' => 'active']);

        return redirect('/dashboard')->with('success', 'Project approved and is now live!');
    });

    Route::post('/projects/{id}/reject', function ($id) {
        if (Auth::user()->role !== 'admin') abort(403);
        
        $project = Project::findOrFail($id);
        // For hackathon, just delete or mark rejected
        $project->delete();

        return redirect('/dashboard')->with('success', 'Project rejected.');
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
