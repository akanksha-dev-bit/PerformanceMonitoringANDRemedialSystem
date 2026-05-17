<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\Mark;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class MarkController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Mark::with(['student', 'subject'])->latest();

        // If user is a student, only show their own marks
        if ($user->isStudent()) {
            $student = $user->studentProfile;
            if (!$student) {
                return redirect()->route('complete-profile');
            }
            $query->where('student_id', $student->id);
            
            $marks = $query->paginate(20);
            return view('marks.index', compact('marks'));
        }

        // Admins and Teachers can filter
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $marks    = $query->paginate(20)->withQueryString();
        $students = Student::orderedByName()->with('user')->get();
        $subjects = Subject::orderBy('name')->get(['id', 'name', 'code']);

        return view('marks.index', compact('marks', 'students', 'subjects'));
    }

    public function create()
    {
        if (auth()->user()->isStudent()) {
            abort(403, 'Students cannot add marks.');
        }

        $students = Student::active()->orderedByName()->with('user')->get();
        $subjects = Subject::orderBy('name')->get();
        return view('marks.create', compact('students', 'subjects'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->isStudent()) {
            abort(403, 'Students cannot add marks.');
        }

        $validated = $request->validate([
            'student_id'     => 'required|exists:students,id',
            'subject_id'     => 'required|exists:subjects,id',
            'marks_obtained' => 'required|integer|min:0',
            'max_marks'      => 'required|integer|min:1',
            'exam_type'      => 'required|in:unit_test,midterm,final,practical',
            'academic_year'  => 'required|string|max:10',
            'remarks'        => 'nullable|string',
        ]);

        $validated['marks_obtained'] = min($validated['marks_obtained'], $validated['max_marks']);

        Mark::create($validated);

        return redirect()->route('marks.index')
            ->with('success', 'Marks recorded successfully!');
    }

    public function destroy(Mark $mark)
    {
        if (auth()->user()->isStudent()) {
            abort(403, 'Students cannot delete marks.');
        }

        $mark->delete();
        return back()->with('success', 'Mark entry deleted.');
    }
}
