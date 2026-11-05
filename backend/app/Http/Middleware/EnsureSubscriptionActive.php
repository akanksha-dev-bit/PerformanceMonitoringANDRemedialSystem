<?php

namespace App\Http\Middleware;

use App\Services\PlanCatalog;
use Closure;
use Illuminate\Http\Request;

class EnsureSubscriptionActive
{
    public function handle(Request $request, Closure $next)
    {
        $school = $request->user()?->school;
        if (!$school) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['code' => 'SCHOOL_REQUIRED', 'message' => 'A school account is required.'], 403);
            }
            abort(403, 'A school account is required.');
        }
        if (!config('plans.enforcement_enabled')) {
            return $next($request);
        }

        $state = app(PlanCatalog::class)->state($school);
        if (in_array($state['status'], ['active', 'legacy', 'provisional'], true)) {
            return $next($request);
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'code' => 'SUBSCRIPTION_REQUIRED',
                'message' => 'Your school needs an active subscription to access this module.',
                'subscription' => $state,
            ], 403);
        }

        return redirect()->route('billing')->with('status', 'Review your school subscription to continue.');
    }
}
