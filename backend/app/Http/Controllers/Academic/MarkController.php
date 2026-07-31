<?php

/**
 * ============================================================================
 * MarkController — Exam Marks Entry & Management
 * ============================================================================
 *
 * PURPOSE:
 *   Handles the recording, viewing, editing, and deletion of student exam marks.
 *   Teachers and Admins can add/edit marks for any student; Students can only
 *   view their own marks (read-only).
 *
 * HOW IT WORKS:
 *   - Admin/Teacher: sees a filterable list of all marks with dropdowns
 *     to filter by student and subject. Can add new marks via a form
 *     (selecting student, subject, exam type, marks obtained/max, etc.)
 *   - Student: sees only their own marks. If they have no student profile,
 *     they are redirected to /complete-profile.
 *   - Marks are capped: marks_obtained can never exceed max_marks.
 *   - Exam types supported: unit_test, midterm, final, practical.
 *
 * ROUTES (Resource):
 *   GET    /marks          → index()   — View all marks (filtered by role)
 *   GET    /marks/create   → create()  — Add marks form (Admin/Teacher only)
 *   POST   /marks          → store()   — Save new mark entry
 *   GET    /marks/{mark}/edit → edit() — Edit mark form / JSON
 *   PUT    /marks/{mark}   → update()  — Update mark entry
 *   DELETE /marks/{mark}   → destroy() — Delete a mark entry
 *
 * SECURITY:
 *   - Students cannot create, edit, or delete marks (abort 403 / HTTP 403 JSON).
 *   - Protected by 'auth', 'verified', and EnsureProfileCompleted.
 *
 * RELATED FILES:
 *   - Views:  resources/views/marks/ (index, create, edit)
 *   - Model:  App\Models\Mark, App\Models\Student, App\Models\Subject
 *   - Routes: routes/web.php → 'marks.*'
 * ============================================================================
 */
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
        $query = Mark::with([
            'student:id,user_id,roll_no,class',
            'student.user:id,name',
            'subject:id,name,code'
        ])->latest();

        // If user is a student, only show their own marks
        if ($user->isStudent()) {
            $student = $user->studentProfile;
            if (!$student) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Profile incomplete.'], 403);
                }
                return redirect()->route('complete-profile');
            }
            $query->where('student_id', $student->id);
            
            $marks = $query->paginate(20);

            if ($request->expectsJson()) {
                $marks->getCollection()->transform(function ($m) {
                    $m->append(['percentage', 'is_pass']);
                    return $m;
                });
                return response()->json($marks);
            }

            return view('marks.index', compact('marks'));
        }

        // Admins and Teachers can filter
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $marks = $query->paginate(20)->withQueryString();

        if ($request->expectsJson()) {
            $marks->getCollection()->transform(function ($m) {
                $m->append(['percentage', 'is_pass']);
                return $m;
            });

            $students = Student::active()
                ->orderedByName()
                ->with('user:id,name')
                ->select(['students.id', 'students.user_id', 'students.roll_no', 'students.class'])
                ->get();

            $subjects = Subject::orderBy('name')->get(['id', 'name', 'code', 'class']);

            return response()->json([
                'marks' => $marks,
                'students' => $students->map(fn($s) => [
                    'id' => $s->id,
                    'name' => $s->name,
                    'roll_no' => $s->roll_no,
                    'class' => $s->class,
                ]),
                'subjects' => $subjects,
            ]);
        }

        $students = Student::orderedByName()->with('user:id,name')->get();
        $subjects = Subject::orderBy('name')->get(['id', 'name', 'code']);

        return view('marks.index', compact('marks', 'students', 'subjects'));
    }

    public function create(Request $request)
    {
        if (auth()->user()->isStudent()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Students cannot add marks.'], 403);
            }
            abort(403, 'Students cannot add marks.');
        }

        $students = Student::active()
            ->orderedByName()
            ->with('user:id,name')
            ->select(['students.id', 'students.user_id', 'students.roll_no', 'students.class'])
            ->get();

        $subjects = Subject::orderBy('name')->get(['id', 'name', 'code', 'class']);

        if ($request->expectsJson()) {
            return response()->json([
                'students' => $students->map(fn($s) => [
                    'id' => $s->id,
                    'name' => $s->name,
                    'roll_no' => $s->roll_no,
                    'class' => $s->class,
                ]),
                'subjects' => $subjects,
            ]);
        }

        return view('marks.create', compact('students', 'subjects'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->isStudent()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Students cannot add marks.'], 403);
            }
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

        $mark = Mark::create($validated);

        if ($request->expectsJson()) {
            $mark->load([
                'student:id,user_id,roll_no,class',
                'student.user:id,name',
                'subject:id,name,code'
            ]);
            $mark->append(['percentage', 'is_pass']);
            return response()->json([
                'success' => true,
                'message' => 'Marks recorded successfully!',
                'data' => $mark
            ], 201);
        }

        return redirect()->route('marks.index')
            ->with('success', 'Marks recorded successfully!');
    }

    public function edit(Mark $mark, Request $request)
    {
        if (auth()->user()->isStudent()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Students cannot edit marks.'], 403);
            }
            abort(403, 'Students cannot edit marks.');
        }

        $mark->load([
            'student:id,user_id,roll_no,class',
            'student.user:id,name',
            'subject:id,name,code'
        ]);
        $mark->append(['percentage', 'is_pass']);

        $students = Student::active()
            ->orderedByName()
            ->with('user:id,name')
            ->select(['students.id', 'students.user_id', 'students.roll_no', 'students.class'])
            ->get();

        $subjects = Subject::orderBy('name')->get(['id', 'name', 'code', 'class']);

        if ($request->expectsJson()) {
            return response()->json([
                'mark' => $mark,
                'students' => $students->map(fn($s) => [
                    'id' => $s->id,
                    'name' => $s->name,
                    'roll_no' => $s->roll_no,
                    'class' => $s->class,
                ]),
                'subjects' => $subjects,
            ]);
        }

        if (view()->exists('marks.edit')) {
            return view('marks.edit', compact('mark', 'students', 'subjects'));
        }

        return view('marks.create', compact('students', 'subjects'));
    }

    public function update(Request $request, Mark $mark)
    {
        if (auth()->user()->isStudent()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Students cannot update marks.'], 403);
            }
            abort(403, 'Students cannot update marks.');
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

        $mark->update($validated);

        if ($request->expectsJson()) {
            $mark->load([
                'student:id,user_id,roll_no,class',
                'student.user:id,name',
                'subject:id,name,code'
            ]);
            $mark->append(['percentage', 'is_pass']);
            return response()->json([
                'success' => true,
                'message' => 'Mark entry updated successfully.',
                'data' => $mark
            ]);
        }

        return redirect()->route('marks.index')
            ->with('success', 'Mark entry updated successfully.');
    }

    public function destroy(Mark $mark, Request $request = null)
    {
        $req = $request ?? request();
        if (auth()->user()->isStudent()) {
            if ($req->expectsJson()) {
                return response()->json(['message' => 'Students cannot delete marks.'], 403);
            }
            abort(403, 'Students cannot delete marks.');
        }

        $mark->delete();

        if ($req->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Mark entry deleted.'
            ]);
        }

        return back()->with('success', 'Mark entry deleted.');
    }
}
