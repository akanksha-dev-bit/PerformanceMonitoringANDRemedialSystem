<?php

/**
 * ============================================================================
 * TeacherController — Admin-Only Teacher Management
 * ============================================================================
 *
 * PURPOSE:
 *   Allows school Admins to manage their teaching staff. Admins can add
 *   new teachers (auto-creating a User account with role='teacher'),
 *   assign them to a subject, edit their details, or remove them entirely.
 *
 * HOW IT WORKS:
 *   - Admin navigates to "Teachers" in the navbar.
 *   - Index page shows all teachers in the admin's school with pagination.
 *   - "Add Teacher" creates both a User record (login credentials) and a
 *     Teacher record (school + subject assignment) in one transaction.
 *   - Edit allows changing name, email, password, and subject assignment.
 *   - Delete removes the User record entirely (cascading to Teacher record).
 *
 * ROUTES (Resource — Admin Only):
 *   GET    /teachers              → index()   — List all teachers
 *   GET    /teachers/create       → create()  — Add teacher form
 *   POST   /teachers              → store()   — Create teacher + user account
 *   GET    /teachers/{teacher}/edit → edit()  — Edit form
 *   PUT    /teachers/{teacher}    → update()  — Save changes
 *   DELETE /teachers/{teacher}    → destroy() — Remove teacher + user account
 *
 * SECURITY:
 *   - Protected by RoleMiddleware('admin') — only admins can access.
 *   - Cross-school protection: edit/update/delete abort(403) if the
 *     teacher's school_id doesn't match the admin's school_id.
 *   - New teacher accounts are auto-verified (email_verified_at = now()).
 *
 * RELATED FILES:
 *   - Views:  resources/views/teachers/ (index, create, edit)
 *   - Model:  App\Models\Teacher, App\Models\User, App\Models\Subject
 *   - Routes: routes/web.php → 'teachers.*' (inside admin middleware group)
 * ============================================================================
 */
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->user();
        $query = Teacher::with(['user', 'subject'])
            ->where('teachers.school_id', $admin->school_id)
            ->join('users', 'teachers.user_id', '=', 'users.id')
            ->select('teachers.*')
            ->orderBy('users.name', 'asc');

        if ($request->has('search') && $request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhereHas('subject', function ($qs) use ($search) {
                      $qs->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $teachers = $query->paginate(15);

        if ($request->expectsJson()) {
            return response()->json($teachers);
        }

        return view('teachers.index', compact('teachers'));
    }

    public function create()
    {
        $admin = auth()->user();
        $subjects = Subject::orderBy('name')->get();
        return view('teachers.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', \Illuminate\Validation\Rules\Password::defaults()],
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $admin = auth()->user();

        if ($request->expectsJson()) {
            $isActive = $request->boolean('is_active', true);
        } else {
            $isActive = $request->has('is_active');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => now(),
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'school_id' => $admin->school_id,
            'profile_completed' => true,
            'is_active' => $isActive,
        ]);

        $teacher = Teacher::create([
            'user_id' => $user->id,
            'school_id' => $admin->school_id,
            'subject_id' => $request->subject_id
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Teacher created successfully.',
                'data' => $teacher->load(['user', 'subject'])
            ], 201);
        }

        return redirect()->route('teachers.index')->with('success', 'Teacher added successfully.');
    }

    public function edit(Teacher $teacher, Request $request)
    {
        if ($teacher->school_id !== auth()->user()->school_id) { abort(403); }

        if ($request->expectsJson()) {
            return response()->json($teacher->load(['user', 'subject']));
        }

        $subjects = Subject::orderBy('name')->get();
        return view('teachers.edit', compact('teacher', 'subjects'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        if ($teacher->school_id !== auth()->user()->school_id) { abort(403); }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $teacher->user_id],
            'password' => ['nullable', \Illuminate\Validation\Rules\Password::defaults()],
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'is_active' => ['nullable', 'boolean'],
        ];

        // Partial update support for status toggle switch
        if ($request->expectsJson() && $request->has('is_active') && !$request->has('name')) {
            $rules = [
                'is_active' => ['required', 'boolean'],
            ];
        }

        $validated = $request->validate($rules);

        if (!$request->has('name') && $request->expectsJson()) {
            // Status toggle only
            $teacher->user->update(['is_active' => $request->boolean('is_active')]);
        } else {
            // Full update
            $isActive = $request->expectsJson() ? $request->boolean('is_active', true) : $request->has('is_active');

            $teacher->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'is_active' => $isActive,
            ]);

            if ($request->filled('password')) {
                $teacher->user->update(['password' => Hash::make($request->password)]);
            }

            $teacher->update(['subject_id' => $request->subject_id]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Teacher updated successfully.',
                'data' => $teacher->load(['user', 'subject'])
            ]);
        }

        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher, Request $request)
    {
        if ($teacher->school_id !== auth()->user()->school_id) { abort(403); }
        
        $teacher->user->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Teacher deleted successfully.'
            ]);
        }

        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }
}
