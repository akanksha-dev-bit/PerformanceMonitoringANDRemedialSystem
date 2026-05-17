<?php

/**
 * ============================================================================
 * ReportController — School-Wide Academic Reports
 * ============================================================================
 *
 * PURPOSE:
 *   Generates high-level academic reports for Admins. Shows overall slow
 *   learner statistics plus a class-wise breakdown of how many students
 *   are performing well vs. struggling in each class.
 *
 * DATA PROVIDED TO THE VIEW:
 *   - summary:        Overall slow learner stats (count, percentage)
 *   - slowLearners:   Collection of all students flagged as slow learners
 *   - classBreakdown: Per-class stats showing:
 *       • class name
 *       • total evaluated students
 *       • slow learner count
 *       • good performer count
 *
 * ROUTES:
 *   GET /reports → index() — Main reports page
 *
 * RELATED FILES:
 *   - View:    resources/views/reports/index.blade.php
 *   - Service: App\Services\SlowLearnerService
 *   - Routes:  routes/web.php → 'reports.index'
 * ============================================================================
 */
namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\Mark;
use App\Models\Student;
use App\Services\SlowLearnerService;

class ReportController extends Controller
{
    public function __construct(protected SlowLearnerService $slowLearnerService) {}

    public function index()
    {
        $summary      = $this->slowLearnerService->getSummary();
        $slowLearners = $this->slowLearnerService->detect();

        // Class-wise breakdown
        $classBreakdown = Student::with('marks')
            ->get()
            ->groupBy('class')
            ->map(function ($students, $class) {
                $evaluated = $students->filter(fn($s) => $s->has_marks);
                $slow = $evaluated->filter(fn($s) => $s->is_slow_learner)->count();
                return [
                    'class'   => $class,
                    'total'   => $evaluated->count(),
                    'slow'    => $slow,
                    'good'    => $evaluated->count() - $slow,
                ];
            })
            ->filter(fn($row) => $row['total'] > 0)
            ->values();

        return view('reports.index', compact('summary', 'slowLearners', 'classBreakdown'));
    }
}
