<?php

/**
 * ============================================================================
 * StudentController — CRUD Management of Student Records
 * ============================================================================
 *
 * PURPOSE:
 *   Full resource controller for managing student records. Used by Admins
 *   and Teachers to add, view, edit, and remove students. Students themselves
 *   can only view their own data through the dashboard (not this controller).
 *
 * HOW IT WORKS:
 *   - Admin/Teacher navigates to "Students" in the navbar.
 *   - Index page shows a paginated, searchable list of all students in
 *     their school with filters by class and a keyword search (name/roll/class).
 *   - "Add Student" form allows creating a student record with optional
 *     linked User account (if email is provided, a User with role='student'
 *     is auto-created so the student can log in).
 *   - Show page displays full student profile with marks history and
 *     remedial actions assigned to them.
 *   - Edit/Update allows modifying student details and linking/creating
 *     a login account retroactively.
 *
 * ROUTES (Resource):
 *   GET    /students              → index()   — Paginated list with search
 *   GET    /students/create       → create()  — Add student form
 *   POST   /students              → store()   — Save new student
 *   GET    /students/{student}    → show()    — Student detail page
 *   GET    /students/{student}/edit → edit()  — Edit form
 *   PUT    /students/{student}    → update()  — Save changes
 *   DELETE /students/{student}    → destroy() — Remove student
 *
 * SECURITY:
 *   - Protected by 'auth', 'verified', and EnsureProfileCompleted middleware.
 *   - School-scoped: students belong to the authenticated user's school.
 *
 * RELATED FILES:
 *   - Views:  resources/views/students/ (index, create, show, edit)
 *   - Model:  App\Models\Student, App\Models\User
 *   - Routes: routes/web.php → 'students.*'
 * ============================================================================
 */
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $query = Student::with(['user', 'marks'])
            ->where('students.school_id', $schoolId)
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select('students.*')
            ->withCount('marks');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function($qb) use ($q) {
                $qb->where('users.name', 'like', "%$q%")
                   ->orWhere('users.email', 'like', "%$q%")
                   ->orWhere('students.roll_no', 'like', "%$q%")
                   ->orWhere('students.class', 'like', "%$q%");
            });
        }

        if ($request->filled('class')) { 
            $query->where('students.class', $request->class); 
        }

        // Default order by user name
        $query->orderBy('users.name', 'asc');

        $students = $query->paginate(15)->withQueryString();

        if ($request->expectsJson()) {
            $students->getCollection()->transform(function($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'roll_no' => $student->roll_no,
                    'class' => $student->class,
                    'section' => $student->section,
                    'gender' => $student->gender,
                    'is_active' => $student->is_active,
                    'marks_count' => $student->marks_count,
                    'average_percentage' => $student->average_percentage,
                    'is_slow_learner' => $student->is_slow_learner,
                ];
            });
            return response()->json($students);
        }

        $classes  = Student::distinct()->where('school_id', $schoolId)->pluck('class');
        return view('students.index', compact('students', 'classes'));
    }

    public function create() { return view('students.create'); }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'password' => ['nullable', 'string', \Illuminate\Validation\Rules\Password::defaults()],
            'roll_no' => 'required|string|unique:students,roll_no',
            'class' => 'required|string|max:50',
            'section' => 'nullable|string|max:10',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'phone' => 'nullable|string|max:20',
            'guardian_name' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $email = $validated['email'];
        if (empty($email)) {
            $email = strtolower(str_replace(' ', '', $validated['roll_no'])) . '_' . auth()->user()->school_id . '@example.com';
        }

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $email,
            'email_verified_at' => now(),
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password'] ?? 'password123'),
            'role' => 'student',
            'school_id' => auth()->user()->school_id,
            'profile_completed' => true,
            'is_active' => filter_var($validated['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN),
        ]);

        $student = Student::create([
            'user_id' => $user->id,
            'school_id' => auth()->user()->school_id,
            'roll_no' => $validated['roll_no'],
            'class' => $validated['class'],
            'section' => $validated['section'],
            'dob' => $validated['dob'],
            'gender' => $validated['gender'],
            'phone' => $validated['phone'],
            'guardian_name' => $validated['guardian_name'],
            'is_active' => filter_var($validated['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Student added successfully!',
                'student' => $student
            ], 201);
        }

        return redirect()->route('students.index')->with('success', 'Student added successfully!');
    }

    public function show(Student $student)
    {
        $student->load(['marks.subject', 'remedialActions', 'attendanceRecords.session.subject']);
        
        if (request()->expectsJson()) {
            return response()->json([
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'roll_no' => $student->roll_no,
                'class' => $student->class,
                'section' => $student->section,
                'dob' => $student->dob ? $student->dob->format('Y-m-d') : null,
                'gender' => $student->gender,
                'phone' => $student->phone,
                'guardian_name' => $student->guardian_name,
                'is_active' => $student->is_active,
                'xp_points' => $student->xp_points,
                'study_streak' => $student->study_streak,
                'has_marks' => $student->has_marks,
                'average_percentage' => $student->average_percentage,
                'is_slow_learner' => $student->is_slow_learner,
                'performance_label' => $student->performance_label,
                'performance_status' => $student->performance_status,
                'performance_color' => $student->performance_color,
                'overall_attendance' => $student->overall_attendance,
                'marks' => $student->marks->map(function($mark) {
                    return [
                        'id' => $mark->id,
                        'exam_type' => $mark->exam_type,
                        'marks_obtained' => $mark->marks_obtained,
                        'max_marks' => $mark->max_marks,
                        'percentage' => $mark->percentage,
                        'is_pass' => $mark->is_pass,
                        'subject' => $mark->subject ? [
                            'id' => $mark->subject->id,
                            'name' => $mark->subject->name,
                            'code' => $mark->subject->code,
                        ] : null,
                    ];
                }),
                'remedial_actions' => $student->remedialActions->map(function($action) {
                    return [
                        'id' => $action->id,
                        'title' => $action->title,
                        'action_type' => $action->action_type,
                        'scheduled_date' => $action->scheduled_date ? $action->scheduled_date->format('Y-m-d') : null,
                        'status' => $action->status,
                        'status_badge_color' => $action->status_badge_color,
                    ];
                }),
                'attendance_records' => $student->attendanceRecords->map(function($record) {
                    return [
                        'id' => $record->id,
                        'status' => $record->status,
                        'session' => $record->session ? [
                            'id' => $record->session->id,
                            'subject' => $record->session->subject ? [
                                'id' => $record->session->subject->id,
                                'name' => $record->session->subject->name,
                            ] : null,
                        ] : null,
                    ];
                }),
            ]);
        }

        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        if (request()->expectsJson()) {
            $student->load('user');
            return response()->json([
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->user ? ($student->user->email ?? '') : '',
                'roll_no' => $student->roll_no,
                'class' => $student->class,
                'section' => $student->section,
                'dob' => $student->dob ? $student->dob->format('Y-m-d') : null,
                'gender' => $student->gender,
                'phone' => $student->phone,
                'guardian_name' => $student->guardian_name,
                'is_active' => $student->is_active,
            ]);
        }
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        if ($request->expectsJson() && $request->has('is_active') && count($request->all()) <= 2) {
            $validated = $request->validate([
                'is_active' => 'required|boolean',
            ]);
            $student->update([
                'is_active' => $validated['is_active']
            ]);
            if ($student->user) {
                $student->user->update([
                    'is_active' => $validated['is_active']
                ]);
            }
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!',
                'student' => $student
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . ($student->user_id ?? 'NULL'),
            'password' => ['nullable', 'string', \Illuminate\Validation\Rules\Password::defaults()],
            'roll_no' => 'required|string|unique:students,roll_no,' . $student->id,
            'class' => 'required|string|max:50',
            'section' => 'nullable|string|max:10',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'phone' => 'nullable|string|max:20',
            'guardian_name' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive',
            'is_active' => 'nullable|boolean',
        ]);

        $isActive = true;
        if ($request->has('is_active')) {
            $isActive = filter_var($validated['is_active'], FILTER_VALIDATE_BOOLEAN);
        } elseif ($request->has('status')) {
            $isActive = $validated['status'] === 'active';
        }

        $email = $validated['email'];
        if (empty($email)) {
            $email = strtolower(str_replace(' ', '', $validated['roll_no'])) . '_' . auth()->user()->school_id . '@example.com';
        }

        if ($student->user_id) {
            $userData = [
                'name' => $validated['name'],
                'email' => $email,
                'is_active' => $isActive,
            ];
            if (!empty($validated['password'])) {
                $userData['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
            }
            $student->user->update($userData);
        } else {
            $user = \App\Models\User::create([
                'name' => $validated['name'],
                'email' => $email,
                'password' => \Illuminate\Support\Facades\Hash::make($validated['password'] ?? 'password123'),
                'role' => 'student',
                'school_id' => $student->school_id,
                'profile_completed' => true,
                'is_active' => $isActive,
            ]);
            $student->user_id = $user->id;
        }

        $student->update([
            'roll_no' => $validated['roll_no'],
            'class' => $validated['class'],
            'section' => $validated['section'],
            'dob' => $validated['dob'],
            'gender' => $validated['gender'],
            'phone' => $validated['phone'],
            'guardian_name' => $validated['guardian_name'],
            'is_active' => $isActive,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully!',
                'student' => $student
            ]);
        }

        return redirect()->route('students.show', $student)->with('success', 'Student updated successfully!');
    }

    public function destroy(Student $student)
    {
        if ($student->user) {
            $student->user->delete();
        } else {
            $student->delete();
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Student removed.'
            ]);
        }

        return redirect()->route('students.index')->with('success', 'Student removed.');
    }
}
