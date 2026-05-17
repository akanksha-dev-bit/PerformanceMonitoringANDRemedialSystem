<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Student;
use App\Models\Mark;
use App\Models\TeacherAssignment;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user->isTeacher()) abort(403);

        $assignments = TeacherAssignment::where('teacher_id', $user->id)->get();

        $recentStudents = Student::where('school_id', $user->school_id)
            ->where(function ($q) use ($assignments) {
                foreach ($assignments as $a) {
                    $q->orWhere(function ($sub) use ($a) {
                        $sub->where('class', $a->class)
                            ->where('section', $a->section);
                    });
                }
            })->with('marks')->take(10)->get();

        $assignedClassesCount = $assignments->unique('class')->count();
        $assignedStudentsCount = $recentStudents->count();

        return view('dashboard.teacher', compact(
            'recentStudents', 'assignments', 'assignedClassesCount', 'assignedStudentsCount'
        ));
    }
}
