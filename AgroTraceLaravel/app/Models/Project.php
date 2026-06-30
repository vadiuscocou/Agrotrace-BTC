<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];
    protected $appends = ['formatted_id', 'remaining_amount'];

    public function getFormattedIdAttribute() {
        return 'PRJ-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    public function getRemainingAmountAttribute() {
        $invested = $this->investments()->where('status', 'paid')->sum('amount_fcfa');
        return max(0, $this->target_amount_fcfa - $invested);
    }

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
