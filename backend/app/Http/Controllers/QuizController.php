<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = Quiz::with(['subject', 'creator', 'questions', 'assignments'])
            ->where('school_id', $user->school_id)
            ->latest();

        // Teachers see only their own quizzes; admins see all
        if ($user->isTeacher()) {
            $query->where('created_by', $user->id);
        }

        $quizzes = $query->paginate(12);

        return view('quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('quizzes.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'subject_id'       => 'required|exists:subjects,id',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'duration_minutes' => 'required|integer|min:1|max:180',
            'questions'        => 'required|array|min:1',
            'questions.*.question'       => 'required|string',
            'questions.*.option_a'       => 'required|string',
            'questions.*.option_b'       => 'required|string',
            'questions.*.option_c'       => 'required|string',
            'questions.*.option_d'       => 'required|string',
            'questions.*.correct_answer' => 'required|in:A,B,C,D',
            'questions.*.explanation'    => 'nullable|string',
            'questions.*.marks'          => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $quiz = Quiz::create([
                'title'            => $validated['title'],
                'description'      => $validated['description'],
                'subject_id'       => $validated['subject_id'],
                'difficulty_level' => $validated['difficulty_level'],
                'duration_minutes' => $validated['duration_minutes'],
                'created_by'       => auth()->id(),
                'school_id'        => auth()->user()->school_id,
            ]);

            foreach ($validated['questions'] as $q) {
                $quiz->questions()->create($q);
            }
        });

        return redirect()->route('quizzes.index')
            ->with('success', 'Quiz created successfully with ' . count($validated['questions']) . ' questions!');
    }

    public function show(Quiz $quiz)
    {
        $quiz->load(['subject', 'creator', 'questions', 'assignments.student.user', 'assignments.attempts']);
        return view('quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        $quiz->load('questions');
        $subjects = Subject::orderBy('name')->get();
        return view('quizzes.edit', compact('quiz', 'subjects'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'subject_id'       => 'required|exists:subjects,id',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'duration_minutes' => 'required|integer|min:1|max:180',
            'questions'        => 'required|array|min:1',
            'questions.*.id'             => 'nullable|integer',
            'questions.*.question'       => 'required|string',
            'questions.*.option_a'       => 'required|string',
            'questions.*.option_b'       => 'required|string',
            'questions.*.option_c'       => 'required|string',
            'questions.*.option_d'       => 'required|string',
            'questions.*.correct_answer' => 'required|in:A,B,C,D',
            'questions.*.explanation'    => 'nullable|string',
            'questions.*.marks'          => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated, $quiz) {
            $quiz->update([
                'title'            => $validated['title'],
                'description'      => $validated['description'],
                'subject_id'       => $validated['subject_id'],
                'difficulty_level' => $validated['difficulty_level'],
                'duration_minutes' => $validated['duration_minutes'],
            ]);

            // Delete old questions and re-create
            $quiz->questions()->delete();
            foreach ($validated['questions'] as $q) {
                unset($q['id']);
                $quiz->questions()->create($q);
            }
        });

        return redirect()->route('quizzes.show', $quiz)
            ->with('success', 'Quiz updated successfully!');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('quizzes.index')
            ->with('success', 'Quiz deleted successfully.');
    }
}
