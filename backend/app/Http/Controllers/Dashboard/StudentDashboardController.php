<?php

/**
 * ============================================================================
 * StudentDashboardController — Student's Personal Learning Hub
 * ============================================================================
 *
 * PURPOSE:
 *   The most feature-rich dashboard in the system. Provides students with
 *   a comprehensive view of their academic performance, assigned tasks,
 *   quiz assignments, progress tracking, gamification badges, and
 *   personalized recommendations. Powers THREE separate student pages.
 *
 * METHODS & WHAT THEY DO:
 *
 *   index() — Main Student Dashboard (/dashboard/student)
 *     - Subject-wise performance breakdown with color-coded percentages
 *     - Weak subjects detection (below 60%)
 *     - Personalized study recommendations based on weak areas
 *     - Class rank calculation among same class+section peers
 *     - Chart data (labels, percentages, colors) for radar/bar charts
 *     - Gamification: badges (First Exam, Top Scorer, Class Topper, etc.)
 *     - Study streak tracking (days since last exam result)
 *     - Assigned remedial tasks overview
 *
 *   progress() — My Progress Page (/my-progress)
 *     - Detailed subject-wise breakdown with obtained vs max marks
 *     - Historical marks data sorted chronologically
 *     - Performance trend visualization data
 *
 *   tasks() — My Tasks Page (/my-tasks)
 *     - All remedial actions assigned to the student
 *     - Overdue/due-soon detection (computed, never stored)
 *     - Priority levels based on action_type
 *     - Task statistics (total, completed, pending, overdue, due_soon)
 *     - XP points and study streak from the student profile
 *     - Smart quiz recommendations for weak subjects
 *     - Activity timeline (recent task + exam events)
 *     - Quiz assignment cards with attempt tracking
 *
 * ROUTES:
 *   GET /dashboard/student → index()    — Main dashboard
 *   GET /my-progress       → progress() — Progress tracking page
 *   GET /my-tasks          → tasks()    — Task management page
 *
 * SECURITY:
 *   - Aborts 403 if user is not a student.
 *   - Redirects to /complete-profile if no Student record exists.
 *
 * RELATED FILES:
 *   - Views:  resources/views/dashboard/student.blade.php
 *             resources/views/dashboard/student-progress.blade.php
 *             resources/views/dashboard/student-tasks.blade.php
 *   - Model:  App\Models\Student, App\Models\Mark, App\Models\Quiz
 *   - Routes: 'dashboard.student', 'student.progress', 'student.tasks'
 * ============================================================================
 */
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Mark;
use App\Models\Student;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user->isStudent()) abort(403);

        $studentProfile = Student::where('user_id', $user->id)
            ->with(['marks.subject', 'user'])
            ->first();

        if (!$studentProfile) {
            return redirect()->route('complete-profile');
        }

        $marks             = $studentProfile->marks;
        $averagePercentage = $studentProfile->average_percentage;
        $performanceLabel  = $studentProfile->performance_label;

        // ── Subject-wise breakdown ──────────────────────────────────────
        $subjectBreakdown = $marks->groupBy('subject_id')->map(function ($subjectMarks) {
            $subject       = $subjectMarks->first()->subject;
            $totalObtained = $subjectMarks->sum('marks_obtained');
            $totalMax      = $subjectMarks->sum('max_marks');
            $pct           = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 1) : 0;

            return [
                'name'      => $subject->name ?? 'Unknown',
                'pct'       => $pct,
                'obtained'  => $totalObtained,
                'max'       => $totalMax,
                'status'    => $pct >= 60 ? 'good' : ($pct >= 40 ? 'at_risk' : 'failing'),
                'color'     => $pct >= 60 ? '#22c55e' : ($pct >= 40 ? '#f59e0b' : '#ef4444'),
            ];
        })->values();

        // ── Weak subjects (below 60%) ────────────────────────────────────
        $weakSubjects = $subjectBreakdown->filter(fn($s) => $s['pct'] < 60)->values();

        // ── Recommended Actions ──────────────────────────────────────────
        $recommendations = $weakSubjects->map(fn($s) => [
            'subject' => $s['name'],
            'pct'     => $s['pct'],
            'tip'     => $s['pct'] < 40
                ? 'Urgent: Seek teacher help and revise all chapters.'
                : 'Review notes and practice past papers for this subject.',
        ])->take(4);

        // ── Student rank within the same class ──────────────────────────
        $classmates = Student::where('class', $studentProfile->class)
            ->where('section', $studentProfile->section)
            ->where('school_id', $studentProfile->school_id)
            ->with('marks')
            ->get();

        $ranked = $classmates->sortByDesc(fn($s) => $s->average_percentage)->values();
        $rank   = $ranked->search(fn($s) => $s->id === $studentProfile->id);
        $rank   = $rank !== false ? $rank + 1 : null;
        $totalInClass = $classmates->count();

  
        $chartLabels = $subjectBreakdown->pluck('name');
        $chartData   = $subjectBreakdown->pluck('pct');
        $chartColors = $subjectBreakdown->pluck('color');

        $badges = [];
        if ($marks->count() >= 1)  $badges[] = ['icon' => '📝', 'label' => 'First Exam', 'theme' => 'badge-silver'];
        if ($marks->count() >= 5)  $badges[] = ['icon' => '🔥', 'label' => '5 Exams Done', 'theme' => 'badge-fire'];
        if ($averagePercentage >= 80) $badges[] = ['icon' => '🏆', 'label' => 'Top Scorer', 'theme' => 'badge-gold'];
        if ($averagePercentage >= 60) $badges[] = ['icon' => '⭐', 'label' => 'Passing Grade', 'theme' => 'badge-blue'];
        if ($weakSubjects->isEmpty() && $marks->count() > 0) $badges[] = ['icon' => '✅', 'label' => 'All Subjects Passing', 'theme' => 'badge-purple'];
        if ($rank === 1) $badges[] = ['icon' => '🥇', 'label' => 'Class Topper', 'theme' => 'badge-gold'];

        $lastMarkDate = $marks->sortByDesc('created_at')->first()?->created_at;
        $streak       = $lastMarkDate ? max(0, 7 - (int) now()->diffInDays($lastMarkDate)) : 0;

        $assignedTasks = $studentProfile->remedialActions()->orderBy('scheduled_date', 'asc')->get();

        return view('dashboard.student', compact(
            'studentProfile', 'marks', 'averagePercentage', 'performanceLabel',
            'subjectBreakdown', 'weakSubjects', 'recommendations',
            'rank', 'totalInClass',
            'chartLabels', 'chartData', 'chartColors',
            'badges', 'streak', 'assignedTasks'
        ));
    }

    public function progress()
    {
        $user           = auth()->user();
        if (!$user->isStudent()) abort(403);

        $studentProfile = Student::where('user_id', $user->id)
            ->with(['marks.subject'])
            ->first();

        if (!$studentProfile) return redirect()->route('complete-profile');

        $marks = $studentProfile->marks->sortBy('created_at');

        $subjectBreakdown = $marks->groupBy('subject_id')->map(function ($subjectMarks) {
            $subject       = $subjectMarks->first()->subject;
            $totalObtained = $subjectMarks->sum('marks_obtained');
            $totalMax      = $subjectMarks->sum('max_marks');
            $pct           = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 1) : 0;
            return ['name' => $subject->name ?? 'Unknown', 'pct' => $pct,
                    'obtained' => $totalObtained, 'max' => $totalMax,
                    'color' => $pct >= 60 ? '#22c55e' : ($pct >= 40 ? '#f59e0b' : '#ef4444')];
        })->values();

        return view('dashboard.student-progress', compact('studentProfile', 'marks', 'subjectBreakdown'));
    }

    public function tasks()
    {
        $user = auth()->user();
        if (!$user->isStudent()) abort(403);

        $studentProfile = Student::where('user_id', $user->id)
            ->with(['marks.subject'])
            ->first();

        if (!$studentProfile) return redirect()->route('complete-profile');

        $now = now()->startOfDay();

        $assignedTasks = $studentProfile->remedialActions()
            ->with(['assignedByUser', 'submission'])
            ->orderBy('due_date', 'asc')
            ->orderBy('scheduled_date', 'asc')
            ->get()
            ->map(function ($t) use ($now) {
                // Overdue is computed, never stored
                $t->is_overdue  = $t->status !== 'completed'
                    && $t->status !== 'cancelled'
                    && $t->due_date
                    && $t->due_date->lt($now);

                $t->is_due_soon = !$t->is_overdue
                    && $t->status !== 'completed'
                    && $t->due_date
                    && $t->due_date->gte($now)
                    && $t->due_date->lte($now->copy()->addDays(3));

                $t->priority = match($t->action_type) {
                    'counseling', 'parent_meeting'                    => 'Critical',
                    'extra_class', 'peer_tutoring', 'quiz_test'       => 'High',
                    'written_assignment', 'essay', 'file_upload'      => 'Medium',
                    default                                           => 'Low',
                };
                return $t;
            });

        $stats = [
            'total'     => $assignedTasks->count(),
            'completed' => $assignedTasks->where('status', 'completed')->count(),
            'pending'   => $assignedTasks->whereIn('status', ['pending', 'in_progress'])->count(),
            'overdue'   => $assignedTasks->where('is_overdue', true)->count(),
            'due_soon'  => $assignedTasks->where('is_due_soon', true)->count(),
        ];

        // Use persisted XP/streak from DB
        $xpEarned = $studentProfile->xp_points;
        $streak   = $studentProfile->study_streak;

        $classmates = \App\Models\Student::where('class', $studentProfile->class)
            ->where('section', $studentProfile->section)
            ->with('marks')->get();
        $ranked = $classmates->sortByDesc(fn($s) => $s->average_percentage)->values();
        $rank   = $ranked->search(fn($s) => $s->id === $studentProfile->id);
        $rank   = $rank !== false ? $rank + 1 : '-';

        // Weak subjects for recommendations
        $marks = $studentProfile->marks;
        $subjectBreakdown = $marks->groupBy('subject_id')->map(function ($subjectMarks) {
            $totalObtained = $subjectMarks->sum('marks_obtained');
            $totalMax      = $subjectMarks->sum('max_marks');
            return [
                'name'       => $subjectMarks->first()->subject->name ?? 'Unknown',
                'subject_id' => $subjectMarks->first()->subject_id,
                'pct'        => $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 1) : 0,
            ];
        })->values();

        $weakSubjects = $subjectBreakdown->filter(fn($s) => $s['pct'] < 60)->sortBy('pct')->take(3);

        // Smart quiz recommendations: find quizzes in weak subject areas not yet fully attempted
        $weakSubjectIds = $weakSubjects->pluck('subject_id')->filter()->toArray();
        $recommendedQuizzes = collect();
        if (!empty($weakSubjectIds)) {
            $assignedQuizIds = $studentProfile->quizAssignments()->pluck('quiz_id')->toArray();
            $recommendedQuizzes = \App\Models\Quiz::with('subject')
                ->where('school_id', $user->school_id)
                ->whereIn('subject_id', $weakSubjectIds)
                ->whereNotIn('id', $assignedQuizIds)
                ->latest()
                ->take(3)
                ->get();
        }

        // Activity timeline
        $activities = collect();
        foreach ($assignedTasks->sortByDesc('updated_at')->take(4) as $t) {
            $activities->push([
                'title' => $t->status === 'completed' ? 'Completed: ' . $t->title : 'Task: ' . $t->title,
                'date'  => $t->updated_at,
                'icon'  => $t->status === 'completed' ? '✅' : '📌',
                'color' => $t->status === 'completed' ? '#10b981' : '#6366f1',
            ]);
        }
        foreach ($marks->sortByDesc('created_at')->take(2) as $m) {
            $activities->push([
                'title' => 'Exam result: ' . ($m->subject->name ?? 'Subject'),
                'date'  => $m->created_at,
                'icon'  => '📝',
                'color' => '#f59e0b',
            ]);
        }
        $timeline = $activities->sortByDesc('date')->take(5)->values();

        // Quiz assignments
        $quizAssignments = $studentProfile->quizAssignments()
            ->with(['quiz.subject', 'quiz.questions', 'attempts', 'assignedBy'])
            ->whereIn('status', ['active', 'completed'])
            ->orderByRaw("FIELD(status, 'active', 'completed')")
            ->orderBy('start_date', 'asc')
            ->get();

        return view('dashboard.student-tasks', compact(
            'studentProfile', 'assignedTasks', 'stats', 'xpEarned',
            'streak', 'rank', 'weakSubjects', 'timeline',
            'quizAssignments', 'recommendedQuizzes'
        ));
    }
}
