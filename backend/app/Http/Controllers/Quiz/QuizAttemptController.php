<?php

/**
 * ============================================================================
 * QuizAttemptController — Student Quiz Taking & Auto-Grading Engine
 * ============================================================================
 *
 * PURPOSE:
 *   Handles the entire student quiz-taking experience: starting a new
 *   attempt, rendering the fullscreen quiz interface, auto-grading on
 *   submission, and showing detailed results with per-question breakdown.
 *
 * QUIZ ATTEMPT LIFECYCLE:
 *   1. Student clicks "Start Quiz" on their tasks page.
 *   2. start() verifies: is it the right student? Is an attempt available today?
 *   3. If an in-progress attempt exists, resume it; otherwise create new one.
 *   4. show() renders the fullscreen quiz UI with timer and question navigation.
 *   5. submit() receives answers, auto-grades by comparing with correct_answer,
 *      calculates score + percentage, marks attempt as completed.
 *   6. If all daily attempts are used up, the assignment status → 'completed'.
 *   7. results() shows per-question breakdown: correct/incorrect, explanations,
 *      and XP earned (50 XP if ≥40%, else 25 XP).
 *
 * ROUTES:
 *   GET  /quiz/{assignment}/start         → start()   — Begin new attempt
 *   GET  /quiz/attempt/{attempt}          → show()    — Fullscreen quiz UI
 *   POST /quiz/attempt/{attempt}/submit   → submit()  — Submit & auto-grade
 *   GET  /quiz/attempt/{attempt}/results  → results() — Results breakdown
 *
 * SECURITY:
 *   - Only students can take quizzes (abort 403 for others).
 *   - Ownership check: attempt.student_id must match logged-in student.
 *   - Completed attempts cannot be re-submitted.
 *   - Daily attempt limits enforced via today_attempt_available attribute.
 *
 * RELATED FILES:
 *   - Views:  resources/views/quiz/ (attempt, results)
 *   - Model:  App\Models\QuizAttempt, App\Models\StudentQuizAssignment
 *   - Routes: 'quiz.start', 'quiz.attempt', 'quiz.submit', 'quiz.results'
 * ============================================================================
 */
namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempt;
use App\Models\StudentQuizAssignment;
use App\Models\Student;
use Illuminate\Http\Request;

class QuizAttemptController extends Controller
{
    /**
     * Start a new quiz attempt for the logged-in student.
     */
    public function start(StudentQuizAssignment $assignment)
    {
        $user = auth()->user();
        if (!$user->isStudent()) abort(403);

        $student = Student::where('user_id', $user->id)->firstOrFail();

        // Verify this assignment belongs to this student
        if ($assignment->student_id !== $student->id) abort(403);

        // Verify an attempt is available today
        if (!$assignment->today_attempt_available) {
            return back()->with('error', 'No attempt available today. Please try again tomorrow.');
        }

        // Check if there's an in-progress attempt (not yet submitted)
        $inProgress = $assignment->attempts()
            ->where('student_id', $student->id)
            ->whereNull('completed_at')
            ->first();

        if ($inProgress) {
            return redirect()->route('quiz.attempt', $inProgress);
        }

        // Create a new attempt
        $attempt = QuizAttempt::create([
            'assignment_id' => $assignment->id,
            'student_id'    => $student->id,
            'score'         => 0,
            'total_marks'   => $assignment->quiz->total_marks,
            'percentage'    => 0,
            'answers'       => [],
            'duration_taken' => 0,
        ]);

        return redirect()->route('quiz.attempt', $attempt);
    }

    /**
     * Render the fullscreen quiz interface.
     */
    public function show(QuizAttempt $attempt)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        if ($attempt->student_id !== $student->id) abort(403);

        // If already completed, redirect to results
        if ($attempt->completed_at) {
            return redirect()->route('quiz.results', $attempt);
        }

        $attempt->load(['assignment.quiz.questions', 'assignment.quiz.subject']);
        $quiz = $attempt->assignment->quiz;
        $questions = $quiz->questions;

        return view('quiz.attempt', compact('attempt', 'quiz', 'questions'));
    }

    /**
     * Submit quiz answers — auto-grade and save.
     */
    public function submit(Request $request, QuizAttempt $attempt)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        if ($attempt->student_id !== $student->id) abort(403);
        if ($attempt->completed_at) {
            return redirect()->route('quiz.results', $attempt);
        }

        $answers = $request->input('answers', []);
        $duration = (int) $request->input('duration_taken', 0);

        $quiz = $attempt->assignment->quiz;
        $questions = $quiz->questions;

        $score = 0;
        $totalMarks = 0;

        foreach ($questions as $q) {
            $totalMarks += $q->marks;
            $studentAnswer = $answers[$q->id] ?? null;
            if ($studentAnswer && strtoupper($studentAnswer) === $q->correct_answer) {
                $score += $q->marks;
            }
        }

        $percentage = $totalMarks > 0 ? round(($score / $totalMarks) * 100, 2) : 0;

        $attempt->update([
            'answers'       => $answers,
            'score'         => $score,
            'total_marks'   => $totalMarks,
            'percentage'    => $percentage,
            'completed_at'  => now(),
            'duration_taken' => $duration,
        ]);

        // Check if all attempts used — mark assignment as completed
        $assignment = $attempt->assignment;
        if ($assignment->attempts_used >= $assignment->repeat_days) {
            $assignment->update(['status' => 'completed']);
        }

        return redirect()->route('quiz.results', $attempt)
            ->with('success', 'Quiz submitted! You scored ' . $percentage . '%');
    }

    /**
     * Show results page with per-question breakdown.
     */
    public function results(QuizAttempt $attempt)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        if ($attempt->student_id !== $student->id) abort(403);

        $attempt->load(['assignment.quiz.questions', 'assignment.quiz.subject', 'assignment']);

        $quiz = $attempt->assignment->quiz;
        $questions = $quiz->questions;
        $answers = $attempt->answers ?? [];
        $assignment = $attempt->assignment;

        // Build per-question results
        $results = $questions->map(function ($q) use ($answers) {
            $studentAnswer = $answers[$q->id] ?? null;
            return [
                'question'       => $q->question,
                'options'        => ['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d],
                'correct_answer' => $q->correct_answer,
                'student_answer' => $studentAnswer ? strtoupper($studentAnswer) : null,
                'is_correct'     => $studentAnswer && strtoupper($studentAnswer) === $q->correct_answer,
                'explanation'    => $q->explanation,
                'marks'          => $q->marks,
            ];
        });

        $xpEarned = $attempt->percentage >= 40 ? 50 : 25;

        return view('quiz.results', compact('attempt', 'quiz', 'results', 'assignment', 'xpEarned'));
    }
}
