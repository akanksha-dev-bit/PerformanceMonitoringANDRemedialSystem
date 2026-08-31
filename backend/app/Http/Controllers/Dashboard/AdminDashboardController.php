<?php

/**
 * ============================================================================
 * AdminDashboardController — School Administrator Overview Panel
 * ============================================================================
 *
 * PURPOSE:
 *   Renders the Admin's main dashboard with a bird's-eye view of the
 *   entire school's academic performance. Aggregates data from marks,
 *   students, teachers, remedial actions, and slow learner detection
 *   into a single overview payload or Blade view.
 *
 * ============================================================================
 */
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

    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized access.'], 403);
            }
            abort(403);
        }

        $summary = $this->slowLearnerService->getSummary();
        $slowLearners = $this->slowLearnerService->detect()->take(5);
        $trendData = $this->performanceService->getTrendData();

        $activeRemedials = RemedialAction::whereIn('status', ['pending', 'in_progress'])->count();

        $subjectAvgs = Mark::with('subject')
            ->selectRaw('subject_id, ROUND(SUM(marks_obtained) * 100.0 / SUM(max_marks), 2) as avg_pct')
            ->groupBy('subject_id')
            ->with('subject')
            ->get()
            ->map(fn($m) => [
                'subject' => $m->subject->name ?? 'Unknown',
                'avg'     => (float) $m->avg_pct,
            ]);

        $recentStudents = Student::where('school_id', $user->school_id)
            ->with(['user', 'marks'])->latest()->take(8)->get();

        $teacherCount = User::where('school_id', $user->school_id)->where('role', 'teacher')->count();
        $studentCount = User::where('school_id', $user->school_id)->where('role', 'student')->count();

        $schoolCode = $user->school->school_code ?? 'PMRS-ERROR';
        $inviteLink = url('/join/' . $schoolCode);

        if ($request->expectsJson()) {
            return response()->json([
                'user' => [
                    'name' => $user->name,
                    'school_name' => $user->school->name ?? 'School',
                ],
                'school' => [
                    'name' => $user->school->name ?? 'School',
                    'school_code' => $schoolCode,
                    'invite_link' => $inviteLink,
                ],
                'stats' => [
                    'students' => $studentCount,
                    'teachers' => $teacherCount,
                    'slow_learners' => $summary['slow_learners'] ?? 0,
                    'not_evaluated' => $summary['not_evaluated'] ?? 0,
                    'active_remedials' => $activeRemedials,
                ],
                'recent_students' => $recentStudents->map(fn($s) => [
                    'id' => $s->id,
                    'name' => $s->user->name ?? 'Student',
                    'roll_no' => $s->roll_number ?? $s->roll_no ?? '-',
                    'class' => $s->class ?? '-',
                    'section' => $s->section ?? '-',
                    'performance_label' => $s->performance_label ?? 'No Data',
                ])->values(),
                'alerts' => $slowLearners->map(fn($l) => [
                    'id' => $l->id,
                    'name' => $l->user->name ?? 'Unknown Student',
                    'avg_pct' => round($l->average_percentage ?? 0, 1),
                ])->values(),
                'subject_rankings' => $subjectAvgs->sortByDesc('avg')->values(),
                'trend_data' => $trendData ?? [],
            ]);
        }

        return view('dashboard.admin', compact(
            'summary', 'slowLearners', 'trendData', 'activeRemedials',
            'subjectAvgs', 'recentStudents', 'teacherCount', 'studentCount', 'schoolCode', 'inviteLink'
        ));
    }
}
