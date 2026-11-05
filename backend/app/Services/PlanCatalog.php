<?php

namespace App\Services;

use App\Models\School;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PlanCatalog
{
    public function find(string $slug): ?array
    {
        $plan = config("plans.plans.{$slug}");
        return is_array($plan) ? ['slug' => $slug, ...$plan] : null;
    }

    public function require(string $slug): array
    {
        return $this->find($slug) ?? throw ValidationException::withMessages([
            'plan' => 'Choose a valid PMRS plan.',
        ]);
    }

    public function publicPlans(): array
    {
        return collect(config('plans.plans', []))
            ->map(fn (array $plan, string $slug) => [
                'slug' => $slug,
                'name' => $plan['name'],
                'annual_price_inr' => $plan['annual_price_inr'],
                'setup_fee_inr' => $plan['setup_fee_inr'],
                'student_limit' => $plan['student_limit'],
                'features' => $plan['features'],
            ])->values()->all();
    }

    public function state(School $school): array
    {
        $subscription = $school->subscription;
        $status = $subscription === null ? 'legacy' : ($subscription->isActive() ? 'active' : ($subscription->status === 'active' ? 'expired' : $subscription->status));
        $plan = $subscription ? $this->find($subscription->plan_slug) : null;
        $enabled = (bool) config('plans.enforcement_enabled');
        $accessible = !$enabled || in_array($status, ['legacy', 'active', 'provisional'], true);
        $provisional = $status === 'provisional';

        return [
            'status' => $status,
            'plan_slug' => $subscription?->plan_slug,
            'plan_name' => $provisional ? 'Starter setup access (unpaid)' : ($plan['name'] ?? null),
            'starts_at' => $subscription?->starts_at?->toIso8601String(),
            'ends_at' => $subscription?->ends_at?->toIso8601String(),
            'student_limit' => $status === 'legacy' ? null : ($provisional ? config('plans.plans.starter.student_limit') : ($plan['student_limit'] ?? null)),
            'student_usage' => Student::withoutGlobalScopes()->where('school_id', $school->id)->where('is_active', true)->count(),
            'features' => $accessible ? ($status === 'legacy' || !$enabled ? $this->implementedFeatures() : ($provisional ? config('plans.plans.starter.features') : ($plan['features'] ?? []))) : [],
            'requires_payment' => $enabled && in_array($status, ['pending', 'expired', 'cancelled'], true),
            'enforcement_enabled' => $enabled,
            'checkout_enabled' => (bool) config('plans.checkout_enabled'),
        ];
    }

    public function allows(School $school, string $feature): bool
    {
        return in_array($feature, $this->state($school)['features'], true);
    }

    public function implementedFeatures(): array
    {
        return array_values(array_unique(array_merge(...array_map(
            fn (array $plan) => $plan['features'],
            array_values(config('plans.plans', []))
        ))));
    }

    /** Lock the school row so concurrent creates or reactivations share one count. */
    public function assertStudentCapacity(int $schoolId): void
    {
        $school = School::whereKey($schoolId)->lockForUpdate()->firstOrFail();
        $state = $this->state($school);
        $limit = config('plans.enforcement_enabled') && in_array($state['status'], ['active', 'provisional'], true)
            ? $state['student_limit'] : null;

        if ($limit !== null && $state['student_usage'] >= $limit) {
            throw ValidationException::withMessages([
                'student_limit' => "This school's {$state['plan_name']} plan includes {$limit} active students. Upgrade to add another student.",
            ]);
        }
    }
}
