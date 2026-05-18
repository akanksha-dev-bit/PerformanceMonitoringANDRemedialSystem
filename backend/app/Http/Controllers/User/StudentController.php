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
        $query = Student::withCount('marks')->latest();
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($qb) => $qb->where('name', 'like', "%$q%")
                ->orWhere('roll_no', 'like', "%$q%")
                ->orWhere('class', 'like', "%$q%"));
        }
        if ($request->filled('class')) { $query->where('class', $request->class); }
        $students = $query->paginate(15)->withQueryString();
        $classes  = Student::distinct()->pluck('class');
        return view('students.index', compact('students', 'classes'));
    }

    public function create() { return view('students.create'); }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255', 'email' => 'nullable|email|unique:users,email',
            'password' => ['nullable', 'string', \Illuminate\Validation\Rules\Password::defaults()], 'roll_no' => 'required|string|unique:students,roll_no',
            'class' => 'required|string|max:50', 'section' => 'nullable|string|max:10',
            'dob' => 'nullable|date', 'gender' => 'nullable|in:male,female,other',
            'phone' => 'nullable|string|max:20', 'guardian_name' => 'nullable|string|max:255',
        ]);
        $userId = null;
        if (!empty($validated['email'])) {
            $user = \App\Models\User::create([
                'name' => $validated['name'], 'email' => $validated['email'], 'email_verified_at' => now(),
                'password' => \Illuminate\Support\Facades\Hash::make($validated['password'] ?? 'password123'),
                'role' => 'student', 'school_id' => auth()->user()->school_id,
                'profile_completed' => true, 'is_active' => true,
            ]);
            $userId = $user->id;
        }
        Student::create([
            'user_id' => $userId, 'school_id' => auth()->user()->school_id,
            'roll_no' => $validated['roll_no'], 'class' => $validated['class'],
            'section' => $validated['section'], 'dob' => $validated['dob'],
            'gender' => $validated['gender'], 'phone' => $validated['phone'],
            'guardian_name' => $validated['guardian_name'], 'is_active' => true,
        ]);
        return redirect()->route('students.index')->with('success', 'Student added successfully!');
    }

    public function show(Student $student)
    {
        $student->load(['marks.subject', 'remedialActions']);
        return view('students.show', compact('student'));
    }

    public function edit(Student $student) { return view('students.edit', compact('student')); }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . ($student->user_id ?? 'NULL'),
            'password' => ['nullable', 'string', \Illuminate\Validation\Rules\Password::defaults()],
            'roll_no' => 'required|string|unique:students,roll_no,' . $student->id,
            'class' => 'required|string|max:50', 'section' => 'nullable|string|max:10',
            'dob' => 'nullable|date', 'gender' => 'nullable|in:male,female,other',
            'phone' => 'nullable|string|max:20', 'guardian_name' => 'nullable|string|max:255',
            'status' => 'in:active,inactive',
        ]);
        if ($student->user_id) {
            $userData = ['name' => $validated['name'], 'email' => $validated['email']];
            if (!empty($validated['password'])) {
                $userData['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
            }
            $student->user->update($userData);
        } elseif (!empty($validated['email'])) {
            $user = \App\Models\User::create([
                'name' => $validated['name'], 'email' => $validated['email'],
                'password' => \Illuminate\Support\Facades\Hash::make($validated['password'] ?? 'password123'),
                'role' => 'student', 'school_id' => $student->school_id,
                'profile_completed' => true, 'is_active' => true,
            ]);
            $student->user_id = $user->id;
        }
        $student->update([
            'roll_no' => $validated['roll_no'], 'class' => $validated['class'],
            'section' => $validated['section'], 'dob' => $validated['dob'],
            'gender' => $validated['gender'], 'phone' => $validated['phone'],
            'guardian_name' => $validated['guardian_name'],
            'is_active' => ($validated['status'] ?? 'active') === 'active',
        ]);
        return redirect()->route('students.show', $student)->with('success', 'Student updated successfully!');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student removed.');
    }
}
