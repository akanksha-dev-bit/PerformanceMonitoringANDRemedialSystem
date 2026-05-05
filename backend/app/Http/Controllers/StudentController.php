<?php

namespace App\Http\Controllers;

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

        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        $students = $query->paginate(15)->withQueryString();
        $classes  = Student::distinct()->pluck('class');

        return view('students.index', compact('students', 'classes'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|unique:users,email',
            'password'      => 'nullable|string|min:8',
            'roll_no'       => 'required|string|unique:students,roll_no',
            'class'         => 'required|string|max:50',
            'section'       => 'nullable|string|max:10',
            'dob'           => 'nullable|date',
            'gender'        => 'nullable|in:male,female,other',
            'phone'         => 'nullable|string|max:20',
            'guardian_name' => 'nullable|string|max:255',
        ]);

        $userId = null;
        if (!empty($validated['email'])) {
            $user = \App\Models\User::create([
                'name'              => $validated['name'],
                'email'             => $validated['email'],
                'password'          => \Illuminate\Support\Facades\Hash::make($validated['password'] ?? 'password123'),
                'role'              => 'student',
                'school_id'         => auth()->user()->school_id,
                'profile_completed' => true,
                'is_active'         => true,
            ]);
            $userId = $user->id;
        }

        Student::create([
            'user_id'       => $userId,
            'school_id'     => auth()->user()->school_id,
            'roll_no'       => $validated['roll_no'],
            'class'         => $validated['class'],
            'section'       => $validated['section'],
            'dob'           => $validated['dob'],
            'gender'        => $validated['gender'],
            'phone'         => $validated['phone'],
            'guardian_name' => $validated['guardian_name'],
            'is_active'     => true,
        ]);

        return redirect()->route('students.index')
            ->with('success', 'Student added successfully!');
    }

    public function show(Student $student)
    {
        $student->load(['marks.subject', 'remedialActions']);
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|unique:users,email,' . ($student->user_id ?? 'NULL'),
            'password'      => 'nullable|string|min:8',
            'roll_no'       => 'required|string|unique:students,roll_no,' . $student->id,
            'class'         => 'required|string|max:50',
            'section'       => 'nullable|string|max:10',
            'dob'           => 'nullable|date',
            'gender'        => 'nullable|in:male,female,other',
            'phone'         => 'nullable|string|max:20',
            'guardian_name' => 'nullable|string|max:255',
            'status'        => 'in:active,inactive',
        ]);

        if ($student->user_id) {
            $userData = [
                'name'  => $validated['name'],
                'email' => $validated['email'],
            ];
            if (!empty($validated['password'])) {
                $userData['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
            }
            $student->user->update($userData);
        } elseif (!empty($validated['email'])) {
            $user = \App\Models\User::create([
                'name'              => $validated['name'],
                'email'             => $validated['email'],
                'password'          => \Illuminate\Support\Facades\Hash::make($validated['password'] ?? 'password123'),
                'role'              => 'student',
                'school_id'         => $student->school_id,
                'profile_completed' => true,
                'is_active'         => true,
            ]);
            $student->user_id = $user->id;
        }

        $student->update([
            'roll_no'       => $validated['roll_no'],
            'class'         => $validated['class'],
            'section'       => $validated['section'],
            'dob'           => $validated['dob'],
            'gender'        => $validated['gender'],
            'phone'         => $validated['phone'],
            'guardian_name' => $validated['guardian_name'],
            'is_active'     => ($validated['status'] ?? 'active') === 'active',
        ]);

        return redirect()->route('students.show', $student)
            ->with('success', 'Student updated successfully!');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')
            ->with('success', 'Student removed.');
    }
}
