<?php

namespace App\Http\Controllers;

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

        // ── Chart data (marks per subject for Chart.js) ─────────────────
        $chartLabels = $subjectBreakdown->pluck('name');
        $chartData   = $subjectBreakdown->pluck('pct');
        $chartColors = $subjectBreakdown->pluck('color');

        // ── Achievements / Badges ────────────────────────────────────────
        $badges = [];
        if ($marks->count() >= 1)  $badges[] = ['icon' => '📝', 'label' => 'First Exam', 'theme' => 'badge-silver'];
        if ($marks->count() >= 5)  $badges[] = ['icon' => '🔥', 'label' => '5 Exams Done', 'theme' => 'badge-fire'];
        if ($averagePercentage >= 80) $badges[] = ['icon' => '🏆', 'label' => 'Top Scorer', 'theme' => 'badge-gold'];
        if ($averagePercentage >= 60) $badges[] = ['icon' => '⭐', 'label' => 'Passing Grade', 'theme' => 'badge-blue'];
        if ($weakSubjects->isEmpty() && $marks->count() > 0) $badges[] = ['icon' => '✅', 'label' => 'All Subjects Passing', 'theme' => 'badge-purple'];
        if ($rank === 1) $badges[] = ['icon' => '🥇', 'label' => 'Class Topper', 'theme' => 'badge-gold'];

        // ── Study Streak (days since last mark was recorded) ─────────────
        $lastMarkDate = $marks->sortByDesc('created_at')->first()?->created_at;
        $streak       = $lastMarkDate ? max(0, 7 - (int) now()->diffInDays($lastMarkDate)) : 0;

        // ── Assigned Remedial Tasks ──────────────────────────────────────
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
}
