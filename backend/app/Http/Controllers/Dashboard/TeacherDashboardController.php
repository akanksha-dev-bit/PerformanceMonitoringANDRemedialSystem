<?php

/**
 * ============================================================================
 * TeacherDashboardController — Teacher's Class Overview Panel
 * ============================================================================
 *
 * PURPOSE:
 *   Renders the Teacher's dashboard showing only the classes and students
 *   they are assigned to. Uses TeacherAssignment records to determine
 *   which class+section combinations belong to this teacher.
 *
 * DATA PROVIDED TO THE VIEW:
 *   - recentStudents:        Up to 10 students from the teacher's assigned classes
 *   - assignments:           All TeacherAssignment records for this teacher
 *   - assignedClassesCount:  Number of unique classes the teacher handles
 *   - assignedStudentsCount: Total students across assigned classes
 *
 * ROUTES:
 *   GET /dashboard/teacher → index() — Teacher dashboard page
 *
 * SECURITY:
 *   - Aborts 403 if the user is not a teacher.
 *   - Queries are scoped to the teacher's school and assigned classes.
 *
 * RELATED FILES:
 *   - View:   resources/views/dashboard/teacher.blade.php
 *   - Model:  App\Models\TeacherAssignment
 *   - Routes: routes/web.php → 'dashboard.teacher'
 * ============================================================================
 */
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
