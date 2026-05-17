<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Student;
use App\Models\User;
use App\Services\PerformanceService;
use App\Services\SlowLearnerService;
use App\Models\Mark;
use App\Models\RemedialAction;

class AdminDashboardController extends Controller
{
    public function __construct(
        protected PerformanceService $performanceService,
        protected SlowLearnerService $slowLearnerService
    ) {}

    public function index()
    {
        $user = auth()->user();
        if (!$user->isAdmin()) abort(403);

        $summary = $this->slowLearnerService->getSummary();
        $slowLearners = $this->slowLearnerService->detect()->take(5);
        $trendData = $this->performanceService->getTrendData();

        $activeRemedials = RemedialAction::whereIn('status', ['pending', 'in_progress'])->count();

        $subjectAvgs = Mark::with('subject')
            ->selectRaw('subject_id, ROUND(SUM(marks_obtained)/SUM(max_marks)*100, 2) as avg_pct')
            ->groupBy('subject_id')
            ->with('subject')
            ->get()
            ->map(fn($m) => [
                'subject' => $m->subject->name ?? 'Unknown',
                'avg'     => $m->avg_pct,
            ]);

        $recentStudents = Student::where('school_id', $user->school_id)
            ->with('marks')->latest()->take(8)->get();

        $teacherCount = User::where('school_id', $user->school_id)->where('role', 'teacher')->count();
        $studentCount = User::where('school_id', $user->school_id)->where('role', 'student')->count();

        // Admin might need school_code for Invite Links
        $schoolCode = $user->school->school_code ?? 'PMRS-ERROR';
        $inviteLink = url('/join/' . $schoolCode);

        return view('dashboard.admin', compact(
            'summary', 'slowLearners', 'trendData', 'activeRemedials',
            'subjectAvgs', 'recentStudents', 'teacherCount', 'studentCount', 'schoolCode', 'inviteLink'
        ));
    }
}
