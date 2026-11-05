<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSubscription extends Model
{
    protected $fillable = ['school_id', 'plan_slug', 'status', 'starts_at', 'ends_at'];

    protected $casts = ['starts_at' => 'datetime', 'ends_at' => 'datetime'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function payments()
    {
        return $this->hasMany(SubscriptionPayment::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active'
            && $this->starts_at?->lte(now())
            && $this->ends_at?->gt(now());
    }
}
