<?php

namespace Tests\Unit;

use App\Models\Mark;
use App\Models\QuizAttempt;
use App\Models\RemedialAction;
use App\Models\RemedialSubmission;
use App\Models\Student;
use App\Services\StudentBadgeCatalog;
use Carbon\Carbon;
use Tests\TestCase;

class StudentBadgeCatalogTest extends TestCase
{
    private function badges(Student $student, $subjects = null, float $average = 0, ?int $rank = null, int $classSize = 1, $tasks = null)
    {
        return collect(app(StudentBadgeCatalog::class)->forDashboard(
            $student, $subjects ?? collect(), $average, $rank, $classSize, $tasks ?? collect()
        ))->keyBy('key');
    }

    private function student($marks = null, $quizzes = null): Student
    {
        $student = new Student();
        $student->setRelation('marks', $marks ?? collect());
        $student->setRelation('quizAttempts', $quizzes ?? collect());

        return $student;
    }

    public function test_all_eight_badges_start_locked_without_recorded_activity(): void
    {
        $badges = $this->badges($this->student());

        $this->assertCount(8, $badges);
        $this->assertTrue($badges->every(fn ($badge) => $badge['unlocked'] === false));
        $this->assertTrue($badges->every(fn ($badge) => $badge['xp_awarded'] === null));
    }

    public function test_exam_badges_use_real_thresholds_and_do_not_award_topper_in_a_one_student_class(): void
    {
        $mark = (new Mark())->forceFill(['marks_obtained' => 94, 'max_marks' => 100, 'created_at' => '2026-10-01 12:00:00']);
        $student = $this->student(collect([$mark]));
        $subjects = collect([['pct' => 94]]);

        $badges = $this->badges($student, $subjects, 94, 1, 1);
        $this->assertTrue($badges['first-exam']['unlocked']);
        $this->assertFalse($badges['top-scorer']['unlocked']);
        $this->assertSame(94, $badges['top-scorer']['current']);
        $this->assertTrue($badges['passing-grade']['unlocked']);
        $this->assertTrue($badges['all-subjects']['unlocked']);
        $this->assertFalse($badges['class-topper']['unlocked']);

        $mark->marks_obtained = 95;
        $student->classroom_id = 1;
        $this->assertTrue($this->badges($student, $subjects, 95, 1, 2)['top-scorer']['unlocked']);
        $this->assertTrue($this->badges($student, $subjects, 95, 1, 2)['class-topper']['unlocked']);
    }

    public function test_streak_requires_recent_activity_and_early_submission_requires_a_full_day(): void
    {
        Carbon::setTestNow('2026-10-27 12:00:00');
        try {
            $student = $this->student();
            $student->study_streak = 10;
            $student->last_activity_date = '2026-10-26';
            $task = (new RemedialAction())->forceFill(['action_type' => 'assignment', 'due_date' => '2026-10-30']);
            $task->setRelation('submission', (new RemedialSubmission())->forceFill(['submitted_at' => '2026-10-29 20:00:00']));

            $badges = $this->badges($student, tasks: collect([$task]));
            $this->assertTrue($badges['study-streak']['unlocked']);
            $this->assertTrue($badges['early-bird']['unlocked']);

            $student->last_activity_date = '2026-10-24';
            $task->submission->submitted_at = '2026-10-30 08:00:00';
            $badges = $this->badges($student, tasks: collect([$task]));
            $this->assertFalse($badges['study-streak']['unlocked']);
            $this->assertFalse($badges['early-bird']['unlocked']);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_quiz_whiz_requires_five_consecutive_distinct_perfect_quizzes(): void
    {
        $attempts = collect();
        for ($i = 1; $i <= 4; $i++) {
            $attempts->push((new QuizAttempt())->forceFill([
                'assignment_id' => $i, 'percentage' => 100, 'completed_at' => "2026-10-0{$i} 10:00:00",
            ]));
        }
        $attempts->push((new QuizAttempt())->forceFill([
            'assignment_id' => 4, 'percentage' => 100, 'completed_at' => '2026-10-05 10:00:00',
        ]));
        $student = $this->student(quizzes: $attempts);
        $this->assertFalse($this->badges($student)['quiz-whiz']['unlocked']);

        $attempts->push((new QuizAttempt())->forceFill([
            'assignment_id' => 5, 'percentage' => 100, 'completed_at' => '2026-10-06 10:00:00',
        ]));
        $this->assertTrue($this->badges($student)['quiz-whiz']['unlocked']);
    }
}
