<?php

namespace App\Http\Controllers;

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
