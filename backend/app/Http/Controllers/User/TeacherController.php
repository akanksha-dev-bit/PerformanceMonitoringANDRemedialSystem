<?php

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
    public function index()
    {
        $admin = auth()->user();
        $teachers = Teacher::with(['user', 'subject'])
            ->where('school_id', $admin->school_id)
            ->paginate(15);
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
            'password' => ['required', 'min:6'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
        ]);
        $admin = auth()->user();
        $user = User::create([
            'name' => $request->name, 'email' => $request->email, 'email_verified_at' => now(),
            'password' => Hash::make($request->password), 'role' => 'teacher',
            'school_id' => $admin->school_id, 'profile_completed' => true, 'is_active' => true,
        ]);
        Teacher::create(['user_id' => $user->id, 'school_id' => $admin->school_id, 'subject_id' => $request->subject_id]);
        return redirect()->route('teachers.index')->with('success', 'Teacher added successfully.');
    }

    public function edit(Teacher $teacher)
    {
        if ($teacher->school_id !== auth()->user()->school_id) { abort(403); }
        $subjects = Subject::orderBy('name')->get();
        return view('teachers.edit', compact('teacher', 'subjects'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        if ($teacher->school_id !== auth()->user()->school_id) { abort(403); }
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $teacher->user_id],
            'password' => ['nullable', 'min:6'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
        ]);
        $teacher->user->update(['name' => $request->name, 'email' => $request->email]);
        if ($request->filled('password')) { $teacher->user->update(['password' => Hash::make($request->password)]); }
        $teacher->update(['subject_id' => $request->subject_id]);
        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        if ($teacher->school_id !== auth()->user()->school_id) { abort(403); }
        $teacher->user->delete();
        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }
}
