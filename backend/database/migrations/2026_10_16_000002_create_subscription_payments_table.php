<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_subscription_id')->nullable()->constrained()->nullOnDelete();
            $table->string('plan_slug', 32);
            $table->unsignedInteger('annual_amount_paise');
            $table->unsignedInteger('setup_amount_paise');
            $table->unsignedInteger('total_amount_paise');
            $table->string('currency', 3)->default('INR');
            $table->string('status', 24)->default('creating');
            $table->string('receipt', 40)->unique();
            $table->string('razorpay_order_id')->nullable()->unique();
            $table->string('razorpay_payment_id')->nullable()->unique();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->index(['school_id', 'status']);
        });

        Schema::create('razorpay_webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_id')->unique();
            $table->string('event_type', 80);
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('razorpay_webhook_events');
        Schema::dropIfExists('subscription_payments');
    }
};
