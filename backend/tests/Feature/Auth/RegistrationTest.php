<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        \Illuminate\Support\Facades\Event::fake([\Illuminate\Auth\Events\Registered::class]);
        $response = $this->post('/register', [
            'school_name' => 'Test School',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'plan' => 'professional',
        ]);

        $response->assertRedirect(route('verification.notice', absolute: false));
        $this->assertDatabaseHas('school_subscriptions', ['plan_slug' => 'professional', 'status' => 'provisional']);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com', 'role' => 'admin']);
    }

    public function test_each_plan_can_be_selected_but_invalid_plan_is_rejected(): void
    {
        \Illuminate\Support\Facades\Event::fake([\Illuminate\Auth\Events\Registered::class]);
        foreach (['starter', 'professional', 'enterprise'] as $index => $plan) {
            $this->post('/register', [
                'school_name' => 'School ' . $index,
                'name' => 'Admin ' . $index,
                'email' => "admin{$index}@example.com",
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'plan' => $plan,
            ])->assertRedirect(route('verification.notice'));
            $this->assertDatabaseHas('school_subscriptions', ['plan_slug' => $plan, 'status' => 'provisional']);
        }

        $this->post('/register', [
            'school_name' => 'Invalid School', 'name' => 'Invalid', 'email' => 'invalid@example.com',
            'password' => 'Password123!', 'password_confirmation' => 'Password123!',
            'plan' => 'enterprise-free',
        ])->assertSessionHasErrors('plan');
        $this->assertDatabaseMissing('users', ['email' => 'invalid@example.com']);
    }

    public function test_registration_access_policy_is_explicit_for_all_rollout_flag_combinations(): void
    {
        \Illuminate\Support\Facades\Event::fake([\Illuminate\Auth\Events\Registered::class]);
        $combinations = [
            [false, false, 'provisional'],
            [false, true, 'provisional'],
            [true, false, 'provisional'],
            [true, true, 'pending'],
        ];

        foreach ($combinations as $index => [$checkout, $enforcement, $expected]) {
            config()->set('plans.checkout_enabled', $checkout);
            config()->set('plans.enforcement_enabled', $enforcement);
            $this->post('/register', [
                'school_name' => "Rollout School {$index}",
                'name' => "Admin {$index}",
                'email' => "rollout{$index}@example.com",
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'plan' => 'enterprise',
            ])->assertRedirect(route('verification.notice'));

            $school = \App\Models\User::where('email', "rollout{$index}@example.com")->firstOrFail()->school;
            $this->assertSame($expected, $school->subscription->status);
            if ($expected === 'provisional' && $enforcement) {
                $state = app(\App\Services\PlanCatalog::class)->state($school);
                $this->assertSame(200, $state['student_limit']);
                $this->assertNotContains('allocations', $state['features']);
                $this->assertNotContains('attendance_analytics', $state['features']);
            }
        }
    }

    public function test_provisional_school_keeps_setup_access_without_premium_entitlements(): void
    {
        \Illuminate\Support\Facades\Event::fake([\Illuminate\Auth\Events\Registered::class]);
        config()->set('plans.checkout_enabled', false);
        config()->set('plans.enforcement_enabled', true);
        $this->post('/register', [
            'school_name' => 'Provisional School', 'name' => 'Admin',
            'email' => 'provisional@example.com', 'password' => 'Password123!',
            'password_confirmation' => 'Password123!', 'plan' => 'enterprise',
        ])->assertRedirect(route('verification.notice'));

        $admin = \App\Models\User::where('email', 'provisional@example.com')->firstOrFail();
        $admin->forceFill(['email_verified_at' => now()])->save();
        $this->actingAs($admin)->getJson('/api/user')->assertOk()
            ->assertJsonPath('subscription.status', 'provisional')
            ->assertJsonPath('subscription.student_limit', 200);
        $this->getJson('/api/academic-years')->assertOk();
        $this->getJson('/api/allocations')->assertForbidden()->assertJsonPath('code', 'PLAN_FEATURE_REQUIRED');
    }
}
