<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherSubjectAssignment;
use App\Models\User;
use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Http\Request;

class TeacherAllocationController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;
        
        $allocations = TeacherSubjectAssignment::where('school_id', $schoolId)
            ->with(['teacher', 'subject', 'classroom'])
            ->get();
            
        $teachers = User::where('role', 'teacher')->where('school_id', $schoolId)->orderBy('name')->get();
        $subjects = Subject::where('school_id', $schoolId)->orderBy('name')->get();
        $classrooms = Classroom::where('school_id', $schoolId)->get();

        return view('admin.allocations.index', compact('allocations', 'teachers', 'subjects', 'classrooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        $schoolId = auth()->user()->school_id;

        // Verify the user is a teacher and matches the school
        $teacher = User::where('id', $request->teacher_id)->where('role', 'teacher')->where('school_id', $schoolId)->firstOrFail();
        $subject = Subject::where('id', $request->subject_id)->where('school_id', $schoolId)->firstOrFail();
        $classroom = Classroom::where('id', $request->classroom_id)->where('school_id', $schoolId)->firstOrFail();

        // Check if allocation already exists
        $exists = TeacherSubjectAssignment::where('school_id', $schoolId)
            ->where('teacher_id', $request->teacher_id)
            ->where('subject_id', $request->subject_id)
            ->where('classroom_id', $request->classroom_id)
            ->exists();

        if ($exists) {
            return redirect()->route('teacher-allocations.index')->with('error', 'This teacher is already assigned to this subject in this classroom.');
        }

        TeacherSubjectAssignment::create([
            'school_id' => $schoolId,
            'teacher_id' => $request->teacher_id,
            'subject_id' => $request->subject_id,
            'classroom_id' => $request->classroom_id,
        ]);

        return redirect()->route('teacher-allocations.index')->with('success', 'Teacher allocated successfully!');
    }

    public function destroy(TeacherSubjectAssignment $teacherAllocation)
    {
        if ($teacherAllocation->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $teacherAllocation->delete();

        return redirect()->route('teacher-allocations.index')->with('success', 'Teacher allocation removed successfully.');
    }
}
