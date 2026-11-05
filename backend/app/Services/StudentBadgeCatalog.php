<?php

namespace App\Services;

use App\Models\Student;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class StudentBadgeCatalog
{
    /**
     * Build the dashboard's current achievement state from recorded activity.
     * Badge XP and historical awards are not stored, so neither is invented.
     *
     * @return array<int, array<string, mixed>>
     */
    public function forDashboard(
        Student $student,
        Collection $subjectBreakdown,
        float $averagePercentage,
        ?int $rank,
        int $classSize,
        Collection $tasks
    ): array {
        $marks = $student->marks;
        $firstMark = $marks->sortBy('created_at')->first();
        $highScore = $marks->filter(fn ($mark) => $mark->percentage >= 95)->sortBy('created_at')->first();
        $subjectCount = $subjectBreakdown->count();
        $passingSubjects = $subjectBreakdown->filter(fn ($subject) => $subject['pct'] >= 40)->count();

        $streakIsCurrent = $student->last_activity_date
            && $student->last_activity_date->gte(now()->startOfDay()->subDay());
        $currentStreak = $streakIsCurrent ? (int) $student->study_streak : 0;

        $earlySubmission = $tasks->filter(function ($task) {
            if (!in_array($task->action_type, ['assignment', 'written_assignment', 'essay', 'file_upload'], true)) {
                return false;
            }

            return $task->due_date
                && $task->submission?->submitted_at
                && $task->submission->submitted_at->lte($task->due_date->copy()->endOfDay()->subDay());
        })->sortBy(fn ($task) => $task->submission->submitted_at)->first();

        // A retake of one assignment is still one quiz, not another step in a streak.
        $quizzes = $student->quizAttempts
            ->filter(fn ($attempt) => $attempt->completed_at)
            ->sortBy('completed_at')
            ->groupBy('assignment_id')
            ->map(fn ($attempts) => $attempts->last())
            ->sortBy('completed_at');
        $perfectRun = 0;
        $perfectRunEarnedAt = null;
        foreach ($quizzes as $quiz) {
            $perfectRun = (float) $quiz->percentage >= 100 ? $perfectRun + 1 : 0;
            if ($perfectRun >= 5 && $perfectRunEarnedAt === null) {
                $perfectRunEarnedAt = $quiz->completed_at;
            }
        }

        return [
            $this->badge('first-exam', 'First Exam', 'common', 'Complete your first recorded exam.',
                $marks->isNotEmpty(), min($marks->count(), 1), 1, 'Complete 1 exam', $firstMark?->created_at),
            $this->badge('top-scorer', 'Top Scorer', 'epic', 'Score 95% or higher on an exam.',
                $highScore !== null, $highScore ? 95 : min(95, (int) $marks->max(fn ($mark) => $mark->percentage)), 95,
                'Best exam score: '.round((float) $marks->max(fn ($mark) => $mark->percentage)).'%', $highScore?->created_at),
            $this->badge('passing-grade', 'Passing Grade', 'common', 'Keep your overall recorded average at 60% or higher.',
                $marks->isNotEmpty() && $averagePercentage >= 60, min(60, (int) $averagePercentage), 60,
                'Overall average: '.round($averagePercentage).'%', null),
            $this->badge('all-subjects', 'All Subjects Passing', 'rare', 'Pass every subject with recorded marks at 40% or higher.',
                $subjectCount > 0 && $passingSubjects === $subjectCount, $passingSubjects, max(1, $subjectCount),
                $passingSubjects.'/'.$subjectCount.' subjects passing', null),
            $this->badge('class-topper', 'Class Topper', 'legendary', 'Reach rank #1 in a class with other students.',
                $marks->isNotEmpty() && $student->classroom_id !== null && $classSize > 1 && $rank === 1,
                $marks->isNotEmpty() && $student->classroom_id !== null && $classSize > 1 && $rank === 1 ? 1 : 0, 1,
                $rank && $student->classroom_id !== null && $classSize > 1
                    ? 'Rank #'.$rank.' of '.$classSize : 'Join a class with a ranking', null),
            $this->badge('study-streak', '10-Day Study Streak', 'epic', 'Be active on ten consecutive days.',
                $currentStreak >= 10, min($currentStreak, 10), 10,
                $currentStreak.'/10 active days', null),
            $this->badge('early-bird', 'Early Bird Submission', 'rare', 'Submit an assignment at least a day before its due date.',
                $earlySubmission !== null, $earlySubmission ? 1 : 0, 1,
                $earlySubmission ? 'Submitted ahead of time' : 'Submit 1 assignment early',
                $earlySubmission?->submission?->submitted_at),
            $this->badge('quiz-whiz', 'Quiz Whiz', 'legendary', 'Score 100% on five consecutive distinct quizzes.',
                $perfectRunEarnedAt !== null, $perfectRunEarnedAt ? 5 : min($perfectRun, 5), 5,
                $perfectRunEarnedAt ? 'Five perfect quizzes in a row' : $perfectRun.'/5 perfect quizzes in a row',
                $perfectRunEarnedAt),
        ];
    }

    private function badge(
        string $key,
        string $label,
        string $tier,
        string $description,
        bool $unlocked,
        int $current,
        int $target,
        string $progress,
        ?CarbonInterface $earnedAt
    ): array {
        $glyph = match ($key) {
            'first-exam' => '✦',
            'top-scorer' => '★',
            'passing-grade' => '✓',
            'all-subjects' => '✚',
            'class-topper' => '♛',
            'study-streak' => '↗',
            'early-bird' => '⚡',
            'quiz-whiz' => '◆',
        };

        return compact('key', 'label', 'tier', 'description', 'unlocked', 'current', 'target', 'progress') + [
            'glyph' => $glyph,
            'progress_percent' => min(100, (int) round($current / $target * 100)),
            'earned_at' => $unlocked ? $earnedAt?->format('M j, Y') : null,
            'xp_awarded' => null,
        ];
    }
}
