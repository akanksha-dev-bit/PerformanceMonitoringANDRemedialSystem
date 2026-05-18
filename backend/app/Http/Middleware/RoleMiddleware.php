<?php

/**
 * ============================================================================
 * RoleMiddleware — Enforce Role-Based Access Control
 * ============================================================================
 *
 * PURPOSE:
 *   Simple but critical middleware that ensures only users with the
 *   expected role(s) can access a route.
 *
 * HOW IT WORKS:
 *   1. Gets the logged-in user from the request.
 *   2. Checks if the user's 'role' attribute matches any of the
 *      roles passed as arguments to the middleware.
 *   3. If no match found → aborts with 403 Unauthorized.
 *   4. Otherwise → allows the request to proceed.
 *
 * USAGE EXAMPLE (in routes/web.php):
 *   Route::middleware(['auth', 'role:teacher|admin'])->group(function () {
 *       Route::get('/dashboard/teacher', [TeacherController::class, 'index']);
 *   });
 *
 * RELATED FILES:
 *   - Model:  App\Models\User (role attribute)
 *   - Config: app/Http/Kernel.php (registering the middleware)
 * ============================================================================
 */
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
