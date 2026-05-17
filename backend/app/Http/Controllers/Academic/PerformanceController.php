<?php

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
