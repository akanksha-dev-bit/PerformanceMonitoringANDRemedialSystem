<?php

/**
 * ============================================================================
 * QuizAssignmentController — Assign Quizzes to Students & View Analytics
 * ============================================================================
 *
 * PURPOSE:
 *   After a teacher creates a quiz, they use this controller to assign it
 *   to individual students with a start date and repeat count (how many
 *   daily practice attempts the student gets). Also provides per-assignment
 *   analytics showing attempt history and score progression.
 *
 * HOW IT WORKS:
 *   - Teacher clicks "Assign" on a quiz → sees student picker form.
 *   - Selects a student, start date, and number of practice days.
 *   - System creates a StudentQuizAssignment record with calculated end_date.
 *   - Student sees the assignment on their "My Tasks" dashboard page.
 *   - Analytics page shows all completed attempts with scores over time.
 *
 * ROUTES:
 *   GET  /quizzes/{quiz}/assign                     → create()    — Assignment form
 *   POST /quizzes/{quiz}/assign                     → store()     — Save assignment
 *   GET  /quiz-assignments/{assignment}/analytics   → analytics() — Score analytics
 *
 * RELATED FILES:
 *   - Views:  resources/views/quizzes/ (assign, analytics)
 *   - Model:  App\Models\StudentQuizAssignment, App\Models\Quiz, App\Models\Student
 *   - Routes: 'quizzes.assign', 'quizzes.assign.store', 'quizzes.analytics'
 * ============================================================================
 */
namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Student;
use App\Models\StudentQuizAssignment;
use Illuminate\Http\Request;

class QuizAssignmentController extends Controller
{
    public function create(Quiz $quiz)
    {
        $students = Student::active()->orderedByName()->with('user')->get();
        return view('quizzes.assign', compact('quiz', 'students'));
    }

    public function store(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'student_id'  => 'required|exists:students,id',
            'start_date'  => 'required|date|after_or_equal:today',
            'repeat_days' => 'required|integer|min:1|max:30',
        ]);

        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate   = $startDate->copy()->addDays($validated['repeat_days'] - 1);

        StudentQuizAssignment::create([
            'quiz_id'     => $quiz->id,
            'student_id'  => $validated['student_id'],
            'assigned_by' => auth()->id(),
            'start_date'  => $startDate,
            'end_date'    => $endDate,
            'repeat_days' => $validated['repeat_days'],
            'status'      => 'active',
        ]);

        return redirect()->route('quizzes.show', $quiz)
            ->with('success', 'Quiz assigned successfully! The student will receive ' . $validated['repeat_days'] . ' practice attempts.');
    }

    public function analytics(StudentQuizAssignment $assignment)
    {
        $assignment->load([
            'quiz.subject',
            'quiz.questions',
            'student.user',
            'attempts' => fn($q) => $q->whereNotNull('completed_at')->orderBy('created_at'),
            'assignedBy',
        ]);

        return view('quizzes.analytics', compact('assignment'));
    }
}
