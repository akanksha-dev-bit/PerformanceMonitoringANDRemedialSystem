<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    protected $fillable = [
        'school_id', 'school_subscription_id', 'plan_slug', 'annual_amount_paise',
        'setup_amount_paise', 'total_amount_paise', 'currency', 'status', 'receipt',
        'razorpay_order_id', 'razorpay_payment_id', 'verified_at',
    ];

    protected $casts = ['verified_at' => 'datetime'];

    public function subscription()
    {
        return $this->belongsTo(SchoolSubscription::class, 'school_subscription_id');
    }
}
