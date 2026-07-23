<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function projects() {
        return $this->hasMany(Project::class);
    }
    
    public function investments() {
        return $this->hasMany(Investment::class);
    }

    public function getTrustScoreAttribute() {
        if ($this->role !== 'project_owner') return null;
        
        $baseScore = 50;
        
        $completedProjects = $this->projects()->where('status', 'completed')->count();
        $baseScore += ($completedProjects * 15);
        
        $validatedMilestones = Milestone::whereIn('project_id', $this->projects()->pluck('id'))->where('status', 'validated')->count();
        $baseScore += ($validatedMilestones * 5);
        
        return min(100, max(0, $baseScore));
    }
}
