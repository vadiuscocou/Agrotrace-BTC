<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function milestones() {
        return $this->hasMany(Milestone::class);
    }

    public function investments() {
        return $this->hasMany(Investment::class);
    }
}
