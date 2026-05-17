<?php

/**
 * ============================================================================
 * PerformanceController — Student Performance Analysis & Slow Learner Detection
 * ============================================================================
 *
 * PURPOSE:
 *   Provides Teachers and Admins with tools to analyze student academic
 *   performance. Shows paginated student lists with marks, individual
 *   student deep-dives, and a dedicated slow learner identification page
 *   that flags students performing below threshold.
 *
 * METHODS:
 *   index()        — Paginated list of all students with their marks loaded.
 *                    Teachers use this as a starting point to drill into
 *                    individual performance.
 *
 *   show($student) — Deep-dive into a single student's performance using
 *                    PerformanceService to calculate subject-wise averages,
 *                    trends, and comparison against class averages.
 *
 *   slowLearners() — Uses SlowLearnerService to detect students below the
 *                    performance threshold AND students "at risk" (borderline).
 *                    This is the key page for identifying who needs remedial help.
 *
 * ROUTES:
 *   GET /performance                  → index()        — Student list
 *   GET /performance/student/{student} → show()        — Individual analysis
 *   GET /performance/slow-learners    → slowLearners() — Flagged students
 *
 * RELATED FILES:
 *   - Views:    resources/views/performance/ (index, show, slow-learners)
 *   - Services: App\Services\PerformanceService, App\Services\SlowLearnerService
 *   - Routes:   routes/web.php → 'performance.*'
 * ============================================================================
 */
namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\PerformanceService;
use App\Services\SlowLearnerService;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    public function __construct(
        protected PerformanceService $performanceService,
        protected SlowLearnerService $slowLearnerService
    ) {}

    public function index(Request $request)
    {
        $students = Student::with('marks')->latest()->paginate(20)->withQueryString();

        return view('performance.index', compact('students'));
    }

    public function show(Student $student)
    {
        $summary = $this->performanceService->getStudentSummary($student);
        return view('performance.show', compact('summary'));
    }

    public function slowLearners(Request $request)
    {
        $slowLearners = $this->slowLearnerService->detect();
        $atRisk       = $this->slowLearnerService->detectAtRisk();

        return view('performance.slow-learners', compact('slowLearners', 'atRisk'));
    }
}
