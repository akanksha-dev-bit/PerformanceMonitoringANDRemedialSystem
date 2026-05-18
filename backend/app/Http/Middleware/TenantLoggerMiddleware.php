<?php

/**
 * ============================================================================
 * TenantLoggerMiddleware — School Tenant Activity Logger
 * ============================================================================
 *
 * PURPOSE:
 *   Automatically logs every authenticated request with school context:
 *   - Which school is accessing?
 *   - Who is accessing (user + role)?
 *   - What action are they performing (method + URL)?
 *
 * HOW IT WORKS:
 *   1. Fires on EVERY authenticated request (registered in Kernel.php).
 *   2. Retrieves logged-in user.
 *   3. Gets school name from user's school relationship.
 *   4. Logs a single line with format:
 *      [School: {name}, User: {user}, Role: {role}] {method} {url}
 *
 * USAGE:
 *   No manual configuration needed. Just add users to schools via:
 *   - School admin (addStudent, enrollStudent, etc.)
 *   - Super admin (createSchool + createSchoolAdmin)
 *
 * LOG LOCATION:
 *   storage/logs/laravel.log
 *   Search for:
 *   - [School: ...] - to see all school-related requests
 *   - [School: <school-name>] - to filter by specific school
 *
 * EXAMPLE LOG ENTRIES:
 *   [School: DPS International School, User: Teacher Name, Role: teacher] GET /dashboard
 *   [School: St. Mary's, User: Admin User, Role: admin] POST /students
 *   [School: DPS International School, User: Student User, Role: student] GET /quizzes/1/start
 *
 * RELATED FILES:
 *   - Kernel:     app/Http/Kernel.php (middleware registration)
 *   - Models:     App\Models\School, App\Models\User
 *   - Controllers: All controllers (teacher, admin, student actions)
 * ============================================================================
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TenantLoggerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $schoolName = $user->school ? $user->school->name : 'No School';
            
            Log::info(sprintf(
                '[School: %s, User: %s, Role: %s] %s %s',
                $schoolName,
                $user->name,
                $user->role,
                $request->method(),
                $request->fullUrl()
            ));
        }

        return $next($request);
    }
}
