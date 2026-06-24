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
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
