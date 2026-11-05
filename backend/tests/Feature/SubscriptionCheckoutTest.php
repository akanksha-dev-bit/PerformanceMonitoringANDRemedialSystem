<?php

namespace Tests\Feature;

use App\Models\School;
use App\Models\SchoolSubscription;
use App\Models\Student;
use App\Models\SubscriptionPayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SubscriptionCheckoutTest extends TestCase
{
    use RefreshDatabase;

    private School $school;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('plans.checkout_enabled', true);
        config()->set('plans.enforcement_enabled', true);
        config()->set('services.razorpay', [
            'mode' => 'test',
            'key_id' => 'rzp_test_example',
            'key_secret' => 'test_secret',
            'webhook_secret' => 'webhook_secret',
        ]);
        $this->school = School::create(['name' => 'Example School', 'school_code' => 'PMRS-EXAMPLE']);
        $this->admin = User::factory()->create([
            'role' => 'admin', 'school_id' => $this->school->id, 'profile_completed' => true,
        ]);
        SchoolSubscription::create([
            'school_id' => $this->school->id, 'plan_slug' => 'starter', 'status' => 'pending',
        ]);
        $this->actingAs($this->admin);
    }

    private function fakeRazorpay(bool $captured = true, int $capturedAmount = 5900000): void
    {
        Http::fake(function (ClientRequest $request) use ($captured, $capturedAmount) {
            if ($request->method() === 'POST' && str_ends_with($request->url(), '/orders')) {
                return Http::response([
                    'id' => 'order_test123', 'amount' => $request['amount'],
                    'currency' => 'INR', 'receipt' => $request['receipt'], 'status' => 'created',
                ]);
            }
            if (str_ends_with($request->url(), '/payments/pay_test123')) {
                return Http::response([
                    'id' => 'pay_test123', 'order_id' => 'order_test123',
                    'amount' => $capturedAmount, 'currency' => 'INR',
                    'status' => $captured ? 'captured' : 'authorized',
                ]);
            }
            if (str_ends_with($request->url(), '/orders/order_test123')) {
                $payment = SubscriptionPayment::where('razorpay_order_id', 'order_test123')->firstOrFail();
                return Http::response([
                    'id' => 'order_test123', 'amount' => 5900000,
                    'currency' => 'INR', 'receipt' => $payment->receipt,
                    'status' => $captured ? 'paid' : 'attempted',
                    'notes' => ['school_id' => (string) $this->school->id],
                ]);
            }
            return Http::response([], 404);
        });
    }

    private function createOrder(): void
    {
        $this->postJson('/api/payments/order', ['plan' => 'starter'])
            ->assertOk()->assertJsonPath('amount', 5900000)
            ->assertJsonPath('setup_amount_paise', 0)
            ->assertJsonPath('key_id', 'rzp_test_example');
    }

    private function sendWebhook(string $event, string $eventId)
    {
        $body = json_encode(['event' => $event, 'payload' => ['payment' => ['entity' => [
            'id' => 'pay_test123', 'order_id' => 'order_test123',
        ]]]]);
        return $this->call('POST', '/api/payments/webhook', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => hash_hmac('sha256', $body, 'webhook_secret'),
            'HTTP_X_RAZORPAY_EVENT_ID' => $eventId,
        ], $body);
    }

    private function fakeMultipleOrders(): void
    {
        $next = 0;
        $orders = [];
        Http::fake(function (ClientRequest $request) use (&$next, &$orders) {
            if ($request->method() === 'POST' && str_ends_with($request->url(), '/orders')) {
                $next++;
                $orders["order_multi{$next}"] = [
                    'id' => "order_multi{$next}", 'amount' => $request['amount'],
                    'currency' => 'INR', 'receipt' => $request['receipt'],
                    'notes' => ['school_id' => (string) $this->school->id],
                ];
                return Http::response([...$orders["order_multi{$next}"], 'status' => 'created']);
            }
            if (preg_match('~/payments/pay_multi(\d+)$~', $request->url(), $matches)) {
                $orderId = 'order_multi' . $matches[1];
                return Http::response([
                    'id' => 'pay_multi' . $matches[1], 'order_id' => $orderId,
                    'amount' => $orders[$orderId]['amount'], 'currency' => 'INR', 'status' => 'captured',
                ]);
            }
            if (preg_match('~/orders/(order_multi\d+)$~', $request->url(), $matches)) {
                return Http::response([...$orders[$matches[1]], 'status' => 'paid']);
            }
            return Http::response([], 404);
        });
    }

    private function sendMultiCapture(int $number, string $eventId)
    {
        $body = json_encode(['event' => 'payment.captured', 'payload' => ['payment' => ['entity' => [
            'id' => "pay_multi{$number}", 'order_id' => "order_multi{$number}",
        ]]]]);
        return $this->call('POST', '/api/payments/webhook', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => hash_hmac('sha256', $body, 'webhook_secret'),
            'HTTP_X_RAZORPAY_EVENT_ID' => $eventId,
        ], $body);
    }

    public function test_server_calculates_test_order_and_rejects_client_amount(): void
    {
        $this->fakeRazorpay();
        $this->postJson('/api/payments/order', ['plan' => 'starter', 'amount' => 1])->assertUnprocessable();
        $this->createOrder();
        $this->createOrder();
        $this->assertSame(1, SubscriptionPayment::where('school_id', $this->school->id)->count());
        $this->assertDatabaseHas('subscription_payments', [
            'school_id' => $this->school->id, 'annual_amount_paise' => 5900000,
            'setup_amount_paise' => 0, 'status' => 'pending',
        ]);
        $this->assertDatabaseHas('school_subscriptions', ['school_id' => $this->school->id, 'status' => 'pending']);
    }

    public function test_stale_creating_attempt_is_superseded_before_a_retry(): void
    {
        $this->fakeMultipleOrders();
        $stale = SubscriptionPayment::create([
            'school_id' => $this->school->id, 'plan_slug' => 'starter',
            'annual_amount_paise' => 5900000, 'setup_amount_paise' => 0,
            'total_amount_paise' => 5900000, 'currency' => 'INR',
            'status' => 'creating', 'receipt' => 'pmrs_stale_attempt',
        ]);
        $this->postJson('/api/payments/order', ['plan' => 'starter'])->assertStatus(409);
        Http::assertNothingSent();
        SubscriptionPayment::whereKey($stale->id)->update(['created_at' => now()->subMinutes(2)]);

        $this->postJson('/api/payments/order', ['plan' => 'starter'])
            ->assertOk()->assertJsonPath('order_id', 'order_multi1');
        $this->assertSame('superseded', $stale->fresh()->status);
    }

    public function test_midterm_change_to_another_plan_is_rejected_before_creating_an_order(): void
    {
        $this->school->subscription()->update([
            'status' => 'active', 'starts_at' => now()->subDay(), 'ends_at' => now()->addYear(),
        ]);
        Http::fake();
        $this->postJson('/api/payments/order', ['plan' => 'professional'])->assertStatus(409);
        Http::assertNothingSent();
    }

    public function test_signature_and_captured_payment_activate_once(): void
    {
        $this->fakeRazorpay();
        $this->createOrder();
        $payload = [
            'razorpay_order_id' => 'order_test123',
            'razorpay_payment_id' => 'pay_test123',
            'razorpay_signature' => str_repeat('0', 64),
        ];
        $this->postJson('/api/payments/verify', $payload)->assertUnprocessable();
        $this->assertDatabaseHas('school_subscriptions', ['school_id' => $this->school->id, 'status' => 'pending']);

        $payload['razorpay_signature'] = hash_hmac('sha256', 'order_test123|pay_test123', 'test_secret');
        $this->postJson('/api/payments/verify', $payload)->assertOk()->assertJsonPath('subscription.status', 'active');
        $firstEnd = $this->school->subscription->fresh()->ends_at;
        $this->postJson('/api/payments/verify', $payload)->assertOk();
        $this->assertEquals($firstEnd, $this->school->subscription->fresh()->ends_at);
        $this->assertDatabaseHas('subscription_payments', ['razorpay_payment_id' => 'pay_test123', 'status' => 'paid']);
    }

    public function test_uncaptured_payment_and_bad_webhook_never_activate(): void
    {
        $this->fakeRazorpay(false);
        $this->createOrder();
        $signature = hash_hmac('sha256', 'order_test123|pay_test123', 'test_secret');
        $this->postJson('/api/payments/verify', [
            'razorpay_order_id' => 'order_test123', 'razorpay_payment_id' => 'pay_test123',
            'razorpay_signature' => $signature,
        ])->assertStatus(409);
        $this->postJson('/api/payments/webhook', ['event' => 'payment.captured'])->assertUnauthorized();
        $this->assertDatabaseHas('school_subscriptions', ['school_id' => $this->school->id, 'status' => 'pending']);
    }

    public function test_mismatched_provider_amount_cannot_activate(): void
    {
        $this->fakeRazorpay(true, 1);
        $this->createOrder();
        $this->postJson('/api/payments/verify', [
            'razorpay_order_id' => 'order_test123',
            'razorpay_payment_id' => 'pay_test123',
            'razorpay_signature' => hash_hmac('sha256', 'order_test123|pay_test123', 'test_secret'),
        ])->assertStatus(409);
        $this->assertDatabaseHas('school_subscriptions', ['school_id' => $this->school->id, 'status' => 'pending']);
    }

    public function test_signed_webhook_activates_and_replay_is_idempotent(): void
    {
        $this->fakeRazorpay();
        $this->createOrder();
        $this->sendWebhook('payment.captured', 'evt_test123')->assertOk();
        $firstEnd = $this->school->subscription->fresh()->ends_at;
        $this->sendWebhook('payment.captured', 'evt_test123')->assertOk()->assertJsonPath('status', 'already_processed');
        $this->assertEquals($firstEnd, $this->school->subscription->fresh()->ends_at);
    }

    public function test_failed_then_captured_webhooks_only_activate_on_capture(): void
    {
        $this->fakeRazorpay();
        $this->createOrder();
        $this->sendWebhook('payment.failed', 'evt_failed')->assertOk();
        $this->assertDatabaseHas('subscription_payments', ['razorpay_order_id' => 'order_test123', 'status' => 'failed']);
        $this->assertDatabaseHas('school_subscriptions', ['school_id' => $this->school->id, 'status' => 'pending']);
        $this->sendWebhook('payment.captured', 'evt_captured')->assertOk();
        $this->assertDatabaseHas('school_subscriptions', ['school_id' => $this->school->id, 'status' => 'active']);
        $this->sendWebhook('payment.failed', 'evt_late_failed')->assertOk();
        $this->assertDatabaseHas('subscription_payments', ['razorpay_order_id' => 'order_test123', 'status' => 'paid']);
    }

    public function test_early_capture_webhook_retries_until_order_exists(): void
    {
        $this->fakeRazorpay();
        $this->sendWebhook('payment.captured', 'evt_early')->assertStatus(503);
        $this->assertDatabaseHas('razorpay_webhook_events', ['event_id' => 'evt_early', 'processed_at' => null]);
        $this->createOrder();
        $this->sendWebhook('payment.captured', 'evt_early')->assertOk();
        $this->assertDatabaseHas('school_subscriptions', ['school_id' => $this->school->id, 'status' => 'active']);
    }

    public function test_plan_feature_and_student_limit_are_enforced(): void
    {
        SchoolSubscription::where('school_id', $this->school->id)->update([
            'status' => 'active', 'starts_at' => now()->subDay(), 'ends_at' => now()->addYear(),
        ]);
        $this->getJson('/api/allocations')->assertForbidden()->assertJsonPath('code', 'PLAN_FEATURE_REQUIRED');
        config()->set('plans.plans.starter.student_limit', 1);
        $studentUser = User::factory()->create([
            'role' => 'student', 'school_id' => $this->school->id, 'profile_completed' => true,
        ]);
        Student::create([
            'user_id' => $studentUser->id, 'school_id' => $this->school->id,
            'roll_no' => '001', 'is_active' => true,
        ]);
        $this->postJson('/students', [
            'name' => 'Second Student', 'email' => 'second@example.com',
            'password' => 'Password123!',
            'roll_no' => '002', 'dob' => null, 'gender' => null,
            'phone' => null, 'guardian_name' => null,
        ])->assertUnprocessable()->assertJsonValidationErrors('student_limit');
    }

    public function test_professional_and_enterprise_can_use_premium_allocations(): void
    {
        foreach (['professional', 'enterprise'] as $plan) {
            SchoolSubscription::where('school_id', $this->school->id)->update([
                'plan_slug' => $plan, 'status' => 'active',
                'starts_at' => now()->subDay(), 'ends_at' => now()->addYear(),
            ]);
            $this->admin->unsetRelation('school');
            $this->getJson('/api/allocations')->assertOk();
        }
    }

    public function test_pending_school_cannot_enroll_through_invite(): void
    {
        $this->app['auth']->logout();
        $this->post('/join/' . $this->school->school_code, [
            'name' => 'Invited Student', 'email' => 'invited@example.com',
            'password' => 'Password123!', 'password_confirmation' => 'Password123!',
        ])->assertForbidden();
        $this->assertDatabaseMissing('users', ['email' => 'invited@example.com']);
    }

    public function test_pending_and_expired_schools_cannot_access_academics_but_legacy_schools_can(): void
    {
        $this->getJson('/api/allocations')->assertForbidden()->assertJsonPath('code', 'SUBSCRIPTION_REQUIRED');
        $this->get('/dashboard')->assertRedirect(route('billing'));
        $this->getJson('/api/subscription')->assertOk()->assertJsonPath('status', 'pending');

        SchoolSubscription::where('school_id', $this->school->id)->update([
            'status' => 'active', 'starts_at' => now()->subYear(), 'ends_at' => now()->subDay(),
        ]);
        $this->admin->unsetRelation('school');
        $this->getJson('/api/allocations')->assertForbidden()->assertJsonPath('subscription.status', 'expired');

        SchoolSubscription::where('school_id', $this->school->id)->delete();
        $this->admin->unsetRelation('school');
        $this->getJson('/api/allocations')->assertOk();
    }

    public function test_payment_history_is_school_scoped_and_non_admin_cannot_order(): void
    {
        $this->fakeRazorpay();
        $this->createOrder();
        $otherSchool = School::create(['name' => 'Other School', 'school_code' => 'PMRS-OTHER']);
        $otherAdmin = User::factory()->create([
            'role' => 'admin', 'school_id' => $otherSchool->id, 'profile_completed' => true,
        ]);
        $this->actingAs($otherAdmin)->getJson('/api/payments')->assertOk()->assertExactJson([]);
        $this->postJson('/api/payments/verify', [
            'razorpay_order_id' => 'order_test123', 'razorpay_payment_id' => 'pay_test123',
            'razorpay_signature' => hash_hmac('sha256', 'order_test123|pay_test123', 'test_secret'),
        ])->assertNotFound();

        $teacher = User::factory()->create([
            'role' => 'teacher', 'school_id' => $this->school->id, 'profile_completed' => true,
        ]);
        $this->actingAs($teacher)->postJson('/api/payments/order', ['plan' => 'starter'])->assertForbidden();
    }

    public function test_live_credentials_can_never_create_an_order(): void
    {
        config()->set('services.razorpay.mode', 'live');
        config()->set('services.razorpay.key_id', 'rzp_live_example');
        Http::fake();
        $this->postJson('/api/payments/order', ['plan' => 'starter'])->assertStatus(409)->assertJsonPath('code', 'TEST_MODE_REQUIRED');
        Http::assertNothingSent();
    }

    private function assertPlanOrderSequence(array $sequence): void
    {
        $this->fakeMultipleOrders();
        $this->postJson('/api/payments/order', ['plan' => 'starter'])->assertOk();
        $this->postJson('/api/payments/order', ['plan' => 'professional'])->assertOk();
        $this->assertDatabaseHas('subscription_payments', [
            'razorpay_order_id' => 'order_multi1', 'status' => 'superseded',
        ]);

        foreach ($sequence as $step) {
            if ($step === 'old') {
                $this->sendMultiCapture(1, 'evt_old_' . implode('_', $sequence))->assertOk();
                $this->assertDatabaseHas('subscription_payments', [
                    'razorpay_order_id' => 'order_multi1', 'status' => 'paid_review',
                ]);
            } else {
                $this->sendMultiCapture(2, 'evt_new_' . implode('_', $sequence))->assertOk();
            }
        }

        $subscription = $this->school->subscription()->firstOrFail();
        $this->assertSame('professional', $subscription->plan_slug);
        $this->assertTrue($subscription->isActive());
        $this->assertEqualsWithDelta(365, now()->diffInDays($subscription->ends_at), 2);
    }

    public function test_older_payment_first_cannot_replace_the_newer_plan(): void
    {
        $this->assertPlanOrderSequence(['old', 'new']);
    }

    public function test_newer_payment_first_cannot_be_replaced_by_the_older_plan(): void
    {
        $this->assertPlanOrderSequence(['new', 'old']);
    }

    public function test_superseded_payment_verification_requires_review_without_changing_entitlements(): void
    {
        $this->fakeMultipleOrders();
        $this->postJson('/api/payments/order', ['plan' => 'starter'])->assertOk();
        $this->postJson('/api/payments/order', ['plan' => 'professional'])->assertOk();
        $this->postJson('/api/payments/verify', [
            'razorpay_order_id' => 'order_multi1', 'razorpay_payment_id' => 'pay_multi1',
            'razorpay_signature' => hash_hmac('sha256', 'order_multi1|pay_multi1', 'test_secret'),
        ])->assertStatus(409)->assertJsonPath('code', 'PAYMENT_REVIEW_REQUIRED');
        $this->assertDatabaseHas('school_subscriptions', [
            'school_id' => $this->school->id, 'status' => 'pending',
        ]);
        $this->assertDatabaseHas('subscription_payments', [
            'razorpay_order_id' => 'order_multi1', 'status' => 'paid_review',
        ]);
    }

    public function test_a_rolled_back_order_link_can_be_recovered_from_the_provider_receipt(): void
    {
        $this->fakeMultipleOrders();
        $failOnce = true;
        SubscriptionPayment::updating(function (SubscriptionPayment $payment) use (&$failOnce) {
            if ($failOnce && $payment->razorpay_order_id) {
                $failOnce = false;
                throw new \RuntimeException('Simulated local persistence failure');
            }
        });

        $this->postJson('/api/payments/order', ['plan' => 'starter'])
            ->assertStatus(503)->assertJsonPath('code', 'ORDER_RECONCILIATION_REQUIRED');
        $this->assertDatabaseHas('subscription_payments', [
            'plan_slug' => 'starter', 'status' => 'creating', 'razorpay_order_id' => null,
        ]);
        $this->sendMultiCapture(1, 'evt_recovered')->assertOk();
        $this->assertDatabaseHas('subscription_payments', [
            'razorpay_order_id' => 'order_multi1', 'status' => 'paid',
        ]);
        $this->assertSame('active', $this->school->subscription()->firstOrFail()->status);
    }

    public function test_old_paid_verification_reports_current_expired_state(): void
    {
        $this->fakeRazorpay();
        $this->createOrder();
        $payload = [
            'razorpay_order_id' => 'order_test123', 'razorpay_payment_id' => 'pay_test123',
            'razorpay_signature' => hash_hmac('sha256', 'order_test123|pay_test123', 'test_secret'),
        ];
        $this->postJson('/api/payments/verify', $payload)->assertJsonPath('status', 'active');
        $this->school->subscription()->update(['ends_at' => now()->subDay()]);
        $this->postJson('/api/payments/verify', $payload)->assertJsonPath('status', 'expired');
    }

    public function test_blade_and_json_premium_routes_follow_backend_enforcement_flag(): void
    {
        $this->school->subscription()->update([
            'status' => 'active', 'starts_at' => now()->subDay(), 'ends_at' => now()->addYear(),
        ]);
        $this->getJson('/api/allocations')->assertForbidden()->assertJsonPath('code', 'PLAN_FEATURE_REQUIRED');
        $this->get('/teacher-allocations')->assertRedirect(route('billing'));
        $this->get('/analytics/attendance')->assertRedirect(route('billing'));

        config()->set('plans.enforcement_enabled', false);
        $this->getJson('/api/allocations')->assertOk();
        $this->get('/teacher-allocations')->assertOk();
        $this->get('/analytics/attendance')->assertOk();
    }

    public function test_different_webhook_ids_and_checkout_verification_do_not_extend_twice(): void
    {
        $this->fakeRazorpay();
        $this->createOrder();
        $this->sendWebhook('payment.captured', 'evt_first')->assertOk();
        $end = $this->school->subscription()->firstOrFail()->ends_at;
        $this->sendWebhook('payment.captured', 'evt_second')->assertOk();
        $this->postJson('/api/payments/verify', [
            'razorpay_order_id' => 'order_test123', 'razorpay_payment_id' => 'pay_test123',
            'razorpay_signature' => hash_hmac('sha256', 'order_test123|pay_test123', 'test_secret'),
        ])->assertJsonPath('status', 'active');
        $this->assertEquals($end, $this->school->subscription()->firstOrFail()->ends_at);
    }

    public function test_an_authenticated_account_without_a_school_cannot_use_academic_or_premium_routes(): void
    {
        $this->app['auth']->logout();
        $orphan = User::factory()->create([
            'role' => 'admin', 'school_id' => null, 'profile_completed' => true,
        ]);
        $this->actingAs($orphan)->getJson('/api/allocations')
            ->assertForbidden()->assertJsonPath('code', 'SCHOOL_REQUIRED');
        $this->getJson('/api/subscription')->assertForbidden();
    }
}
