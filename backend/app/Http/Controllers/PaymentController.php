<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolSubscription;
use App\Models\SubscriptionPayment;
use App\Services\PlanCatalog;
use App\Services\RazorpayGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use RuntimeException;

class PaymentController extends Controller
{
    public function history(Request $request)
    {
        return response()->json(SubscriptionPayment::where('school_id', $request->user()->school_id)
            ->latest()->get([
                'id', 'plan_slug', 'annual_amount_paise', 'setup_amount_paise',
                'total_amount_paise', 'currency', 'status', 'verified_at', 'created_at',
            ]));
    }

    public function order(Request $request, PlanCatalog $catalog, RazorpayGateway $gateway)
    {
        $validated = $request->validate([
            'plan' => ['required', 'string', Rule::in(array_keys(config('plans.plans', [])))],
            'amount' => ['prohibited'],
            'currency' => ['prohibited'],
            'setup_fee' => ['prohibited'],
        ]);

        if (!config('plans.checkout_enabled')) {
            return response()->json(['code' => 'CHECKOUT_DISABLED', 'message' => 'Test checkout is not enabled.'], 409);
        }

        try {
            $gateway->assertTestMode();
        } catch (RuntimeException $e) {
            return response()->json(['code' => 'TEST_MODE_REQUIRED', 'message' => $e->getMessage()], 409);
        }
        $schoolId = $request->user()->school_id;
        $plan = $catalog->require($validated['plan']);

        $payment = DB::transaction(function () use ($schoolId, $plan) {
                $school = School::whereKey($schoolId)->lockForUpdate()->firstOrFail();
                $subscription = $school->subscription()->first();

                if ($subscription?->isActive() && $subscription->plan_slug !== $plan['slug']) {
                    abort(409, 'Contact PMRS to review a mid-term plan change before payment.');
                }

                $annualPaise = $plan['annual_price_inr'] * 100;
                $hasPaidBefore = SubscriptionPayment::where('school_id', $schoolId)->where('status', 'paid')->exists();
                $setupPaise = $hasPaidBefore ? 0 : $plan['setup_fee_inr'] * 100;
                $totalPaise = $annualPaise + $setupPaise;

                $outstanding = SubscriptionPayment::where('school_id', $schoolId)
                    ->whereIn('status', ['creating', 'pending'])->orderByDesc('id')->get();
                $existing = $outstanding->first();
                if ($existing && $existing->plan_slug === $plan['slug'] && $existing->total_amount_paise === $totalPaise) {
                    if ($existing->status === 'pending') {
                        return $existing;
                    }
                    if ($existing->created_at->gt(now()->subMinute())) {
                        abort(409, 'A test order is being prepared. Please retry shortly.');
                    }
                }

                // Only the newest checkout is eligible to activate a plan.
                SubscriptionPayment::where('school_id', $schoolId)
                    ->whereIn('status', ['creating', 'pending', 'failed'])->update(['status' => 'superseded']);

                $payment = SubscriptionPayment::create([
                    'school_id' => $schoolId,
                    'school_subscription_id' => $subscription?->id,
                    'plan_slug' => $plan['slug'],
                    'annual_amount_paise' => $annualPaise,
                    'setup_amount_paise' => $setupPaise,
                    'total_amount_paise' => $totalPaise,
                    'currency' => 'INR',
                    'status' => 'creating',
                    'receipt' => 'pmrs_' . Str::lower(Str::random(28)),
                ]);

                return $payment;
        });

        if ($payment->status === 'creating') {
            try {
                // The local receipt is committed first. A signed webhook can
                // reconcile it if Razorpay succeeds but this write fails.
                $order = $gateway->createOrder($payment->total_amount_paise, $payment->receipt, $schoolId);
                if (!isset($order['id']) || !str_starts_with($order['id'], 'order_')
                    || (int) ($order['amount'] ?? -1) !== $payment->total_amount_paise
                    || ($order['currency'] ?? null) !== $payment->currency
                    || ($order['receipt'] ?? null) !== $payment->receipt
                    || ($order['status'] ?? null) !== 'created') {
                    throw new RuntimeException('Razorpay returned an inconsistent test order.');
                }

                $payment = DB::transaction(function () use ($payment, $order) {
                    School::whereKey($payment->school_id)->lockForUpdate()->firstOrFail();
                    $local = SubscriptionPayment::whereKey($payment->id)->lockForUpdate()->firstOrFail();
                    if ($local->razorpay_order_id && $local->razorpay_order_id !== $order['id']) {
                        throw new RuntimeException('A different Razorpay order is already linked to this receipt.');
                    }
                    $local->update([
                        'razorpay_order_id' => $order['id'],
                        'status' => $local->status === 'creating' ? 'pending' : $local->status,
                    ]);
                    return $local;
                });
            } catch (\Throwable $e) {
                return response()->json(['code' => 'ORDER_RECONCILIATION_REQUIRED',
                    'message' => 'The test order could not be completed locally. Please retry later or contact PMRS.'], 503);
            }
        }

        if ($payment->status !== 'pending') {
            return response()->json(['code' => 'ORDER_SUPERSEDED', 'message' => 'This test order was replaced. Please review the current plan.'], 409);
        }

        return response()->json([
            'order_id' => $payment->razorpay_order_id,
            'key_id' => $gateway->publicKey(),
            'amount' => $payment->total_amount_paise,
            'currency' => $payment->currency,
            'plan_slug' => $payment->plan_slug,
            'annual_amount_paise' => $payment->annual_amount_paise,
            'setup_amount_paise' => $payment->setup_amount_paise,
        ]);
    }

    public function verify(Request $request, RazorpayGateway $gateway, PlanCatalog $catalog)
    {
        if (!config('plans.checkout_enabled')) {
            return response()->json(['code' => 'CHECKOUT_DISABLED'], 409);
        }
        try {
            $gateway->assertTestMode();
        } catch (RuntimeException $e) {
            return response()->json(['code' => 'TEST_MODE_REQUIRED', 'message' => $e->getMessage()], 409);
        }

        $validated = $request->validate([
            'razorpay_order_id' => ['required', 'string', 'max:100'],
            'razorpay_payment_id' => ['required', 'string', 'max:100'],
            'razorpay_signature' => ['required', 'string', 'size:64'],
        ]);

        $payment = $this->resolvePaymentForOrder($validated['razorpay_order_id'], $gateway);
        abort_unless($payment && $payment->school_id === $request->user()->school_id, 404);

        if (!$gateway->validCheckoutSignature($payment->razorpay_order_id, $validated['razorpay_payment_id'], $validated['razorpay_signature'])) {
            return response()->json(['code' => 'INVALID_PAYMENT_SIGNATURE', 'message' => 'Payment signature verification failed.'], 422);
        }

        try {
            $result = $this->finalizeCapturedPayment($payment, $validated['razorpay_payment_id'], $gateway);
        } catch (RuntimeException $e) {
            return response()->json(['code' => 'PAYMENT_NOT_VERIFIED', 'message' => $e->getMessage()], 409);
        }

        $state = $catalog->state($request->user()->school->fresh());
        if ($result === 'review_required') {
            return response()->json(['code' => 'PAYMENT_REVIEW_REQUIRED', 'subscription' => $state], 409);
        }
        return response()->json(['status' => $state['status'], 'subscription' => $state]);
    }

    public function webhook(Request $request, RazorpayGateway $gateway)
    {
        if (!config('plans.checkout_enabled') || !$gateway->validWebhookSignature($request->getContent(), (string) $request->header('X-Razorpay-Signature'))) {
            return response()->json(['code' => 'INVALID_WEBHOOK_SIGNATURE'], 401);
        }

        $eventId = (string) $request->header('X-Razorpay-Event-Id');
        $payload = $request->json()->all();
        $type = $payload['event'] ?? null;
        if ($eventId === '' || !is_string($type)) {
            return response()->json(['code' => 'INVALID_WEBHOOK'], 400);
        }

        DB::table('razorpay_webhook_events')->insertOrIgnore([
            'event_id' => $eventId,
            'event_type' => $type,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (DB::table('razorpay_webhook_events')->where('event_id', $eventId)->whereNotNull('processed_at')->exists()) {
            return response()->json(['status' => 'already_processed']);
        }

        $entity = $payload['payload']['payment']['entity'] ?? [];
        $orderId = $entity['order_id'] ?? null;
        $paymentId = $entity['id'] ?? null;
        try {
            $payment = is_string($orderId) ? $this->resolvePaymentForOrder($orderId, $gateway) : null;
        } catch (RuntimeException $e) {
            return response()->json(['code' => 'WEBHOOK_RETRY', 'message' => $e->getMessage()], 503);
        }

        if ($type === 'payment.captured' && (!$payment || !is_string($paymentId))) {
            return response()->json(['code' => 'WEBHOOK_RETRY', 'message' => 'The matching order is not ready yet.'], 503);
        }

        try {
            if ($type === 'payment.captured') {
                $this->finalizeCapturedPayment($payment, $paymentId, $gateway);
            } elseif ($type === 'payment.failed' && $payment) {
                SubscriptionPayment::whereKey($payment->id)->whereIn('status', ['creating', 'pending', 'failed'])->update(['status' => 'failed']);
            }
        } catch (RuntimeException $e) {
            return response()->json(['code' => 'WEBHOOK_RETRY', 'message' => $e->getMessage()], 503);
        }

        DB::table('razorpay_webhook_events')->where('event_id', $eventId)->update(['processed_at' => now(), 'updated_at' => now()]);
        return response()->json(['status' => 'processed']);
    }

    /** Reattach an external order using the committed, unique local receipt. */
    private function resolvePaymentForOrder(string $orderId, RazorpayGateway $gateway): ?SubscriptionPayment
    {
        $local = SubscriptionPayment::where('razorpay_order_id', $orderId)->first();
        if ($local) {
            return $local;
        }

        $remote = $gateway->fetchOrder($orderId);
        $receipt = $remote['receipt'] ?? null;
        $schoolId = $remote['notes']['school_id'] ?? null;
        if (($remote['id'] ?? null) !== $orderId || !is_string($receipt) || !is_numeric($schoolId)) {
            return null;
        }

        return DB::transaction(function () use ($orderId, $remote, $receipt, $schoolId) {
            $payment = SubscriptionPayment::where('receipt', $receipt)->lockForUpdate()->first();
            if (!$payment || (string) $payment->school_id !== (string) $schoolId
                || $payment->total_amount_paise !== (int) ($remote['amount'] ?? -1)
                || $payment->currency !== ($remote['currency'] ?? null)
                || ($payment->razorpay_order_id && $payment->razorpay_order_id !== $orderId)) {
                return null;
            }
            if (!$payment->razorpay_order_id) {
                $payment->update(['razorpay_order_id' => $orderId]);
            }
            return $payment;
        });
    }

    private function finalizeCapturedPayment(SubscriptionPayment $local, string $paymentId, RazorpayGateway $gateway): string
    {
        $remotePayment = $gateway->fetchPayment($paymentId);
        $remoteOrder = $gateway->fetchOrder($local->razorpay_order_id);

        if (($remotePayment['id'] ?? null) !== $paymentId
            || ($remotePayment['order_id'] ?? null) !== $local->razorpay_order_id
            || ($remotePayment['status'] ?? null) !== 'captured'
            || (int) ($remotePayment['amount'] ?? -1) !== $local->total_amount_paise
            || ($remotePayment['currency'] ?? null) !== $local->currency
            || ($remoteOrder['id'] ?? null) !== $local->razorpay_order_id
            || ($remoteOrder['status'] ?? null) !== 'paid'
            || (int) ($remoteOrder['amount'] ?? -1) !== $local->total_amount_paise
            || ($remoteOrder['currency'] ?? null) !== $local->currency
            || ($remoteOrder['receipt'] ?? null) !== $local->receipt
            || (string) ($remoteOrder['notes']['school_id'] ?? '') !== (string) $local->school_id) {
            throw new RuntimeException('Razorpay has not confirmed a matching captured test payment.');
        }

        return DB::transaction(function () use ($local, $paymentId) {
            $school = School::whereKey($local->school_id)->lockForUpdate()->firstOrFail();
            $payment = SubscriptionPayment::whereKey($local->id)->lockForUpdate()->firstOrFail();
            if (in_array($payment->status, ['paid', 'paid_review'], true)) {
                if ($payment->razorpay_payment_id !== $paymentId) {
                    throw new RuntimeException('This order has already been paid with a different payment.');
                }
                return $payment->status === 'paid_review' ? 'review_required' : 'already_paid';
            }

            $subscription = $school->subscription()->first();
            if (in_array($payment->status, ['superseded', 'abandoned'], true)
                || ($subscription?->isActive() && $subscription->plan_slug !== $payment->plan_slug)) {
                $payment->update(['status' => 'paid_review', 'razorpay_payment_id' => $paymentId, 'verified_at' => now()]);
                return 'review_required';
            }
            $startsAt = $subscription?->isActive() ? $subscription->starts_at : now();
            $base = $subscription?->isActive() ? $subscription->ends_at : now();
            $endsAt = $base->copy()->addYear();

            if ($subscription) {
                $subscription->update([
                    'plan_slug' => $payment->plan_slug,
                    'status' => 'active',
                    'starts_at' => $startsAt,
                    'ends_at' => $endsAt,
                ]);
            } else {
                $subscription = SchoolSubscription::create([
                    'school_id' => $school->id,
                    'plan_slug' => $payment->plan_slug,
                    'status' => 'active',
                    'starts_at' => $startsAt,
                    'ends_at' => $endsAt,
                ]);
            }

            $payment->update([
                'school_subscription_id' => $subscription->id,
                'status' => 'paid',
                'razorpay_payment_id' => $paymentId,
                'verified_at' => now(),
            ]);
            SubscriptionPayment::where('school_id', $school->id)->where('id', '!=', $payment->id)
                ->whereIn('status', ['creating', 'pending'])->update(['status' => 'superseded']);
            return 'activated';
        });
    }
}
