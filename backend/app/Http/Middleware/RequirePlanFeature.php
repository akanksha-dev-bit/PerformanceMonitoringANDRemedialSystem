<?php

namespace App\Http\Middleware;

use App\Services\PlanCatalog;
use Closure;
use Illuminate\Http\Request;

class RequirePlanFeature
{
    public function handle(Request $request, Closure $next, string $feature)
    {
        $school = $request->user()?->school;
        if (!$school) {
            abort(403, 'A school account is required.');
        }
        if (!config('plans.enforcement_enabled') || app(PlanCatalog::class)->allows($school, $feature)) {
            return $next($request);
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'code' => 'PLAN_FEATURE_REQUIRED',
                'feature' => $feature,
                'message' => 'This feature is not included in your school plan.',
            ], 403);
        }

        return redirect()->route('billing')->with('status', 'This feature is available on a higher school plan.');
    }
}
