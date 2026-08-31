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

 * 
 */
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Student;
use App\Models\Mark;
use App\Models\TeacherSubjectAssignment;
use App\Models\AcademicYear;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user->isTeacher()) abort(403);

        $currentYear = AcademicYear::currentFor($user->school_id);
        
        $assignments = collect();
        if ($currentYear) {
            $assignments = TeacherSubjectAssignment::with('classroom.academicClass')
                ->where('teacher_id', $user->id)
                ->where('academic_year_id', $currentYear->id)
                ->get()
                ->pluck('classroom')
                ->unique('id')
                ->filter()
                ->values();
        }

        $classroomIds = $assignments->pluck('id')->toArray();

        $recentStudents = Student::where('school_id', $user->school_id)
            ->whereIn('classroom_id', $classroomIds)
            ->with(['marks', 'classroom.academicClass'])
            ->take(10)
            ->get();

        $assignedClassesCount = $assignments->pluck('academic_class_id')->unique()->count();
        $assignedStudentsCount = Student::where('school_id', $user->school_id)
            ->whereIn('classroom_id', $classroomIds)
            ->count();

        $todayClassesCount = \App\Models\Timetable::where('teacher_id', $user->id)
            ->where('day_of_week', now()->format('l'))
            ->count();

        $todayMarkedCount = \App\Models\AttendanceSession::where('teacher_id', $user->id)
            ->whereDate('date', now()->toDateString())
            ->count();

        return view('dashboard.teacher', compact(
            'recentStudents', 'assignments', 'assignedClassesCount', 'assignedStudentsCount',
            'todayClassesCount', 'todayMarkedCount'
        ));
    }
}
