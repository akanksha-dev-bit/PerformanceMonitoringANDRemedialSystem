<?php

/**
 * ============================================================================
 * EnsureProfileCompleted Middleware — Student Profile Validation
 * ============================================================================
 *
 * PURPOSE:
 *   Ensures that only students with a completed profile can access restricted
 *   pages. When a new student registers, their profile_completed flag is false.
 *   This middleware intercepts attempts to access pages like the dashboard,
 *   quizzes, or performance reports and redirects them to the profile
 *   completion form until the profile is finalized.
 *
 * TRIGGERS:
 *   - New student registration (profile_completed = false)
 *   - Student tries to access /dashboard, /quizzes, /performance, /reports
 *
 * BEHAVIOR:
 *   - If user role is 'student' AND profile_completed is false → redirect to
 *     profile completion page.
 *   - If profile is complete OR user is not a student → allow access.
 *
 * ROUTES AFFECTED:
 *   All authenticated student routes, including:
 *   - /dashboard
 *   - /quizzes/*
 *   - /performance
 *   - /reports
 *   - /remedial/*
 *
 * RELATED FILES:
 *   - Model:      App\Models\User
 *   - Route:      web.php → /profile/complete
 *   - Controller: app/Http/Controllers/Auth/CompleteProfileController.php
 * ============================================================================
 */
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'student' && !$user->profile_completed) {
            return redirect()->route('complete-profile');
        }

        return $next($request);
    }
}
