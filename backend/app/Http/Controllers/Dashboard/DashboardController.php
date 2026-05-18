<?php

/**
 * ============================================================================
 * DashboardController — Role-Based Dashboard Router
 * ============================================================================
 *
 * PURPOSE:
 *   Acts as the central entry point for the /dashboard URL. Instead of
 *   showing a generic page, it inspects the logged-in user's role and
 *   redirects them to their role-specific dashboard:
 *     - Admin   → /dashboard/admin   (AdminDashboardController)
 *     - Teacher → /dashboard/teacher (TeacherDashboardController)
 *     - Student → /dashboard/student (StudentDashboardController)
 *
 * HOW IT WORKS:
 *   1. User logs in → Laravel redirects to /dashboard (the default).
 *   2. This controller checks auth()->user()->isAdmin/isTeacher/isStudent.
 *   3. Immediately redirects to the correct role-specific dashboard.
 *   4. No view is rendered by this controller — it's purely a dispatcher.
 *
 * ROUTES:
 *   GET /dashboard → index() — Redirect based on user role
 *
 * RELATED FILES:
 *   - Dashboard\AdminDashboardController   → /dashboard/admin
 *   - Dashboard\TeacherDashboardController → /dashboard/teacher
 *   - Dashboard\StudentDashboardController → /dashboard/student
 *   - Routes: routes/web.php → 'dashboard'
 * ============================================================================
 */
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Mark;
use App\Models\RemedialAction;
use App\Models\Student;
use App\Services\PerformanceService;
use App\Services\SlowLearnerService;

class DashboardController extends Controller
{
    public function __construct(
        protected PerformanceService $performanceService,
        protected SlowLearnerService $slowLearnerService
    ) {}

    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('dashboard.admin');
        } elseif ($user->isTeacher()) {
            return redirect()->route('dashboard.teacher');
        } else {
            return redirect()->route('dashboard.student');
        }
    }
}
