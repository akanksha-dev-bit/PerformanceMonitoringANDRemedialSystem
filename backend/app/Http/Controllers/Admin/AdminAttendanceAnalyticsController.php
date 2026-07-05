<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Student;
use App\Models\User;
use App\Models\Timetable;
use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;

class AdminAttendanceAnalyticsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user->isAdmin()) {
            abort(403);
        }
        $schoolId = $user->school_id;

        // 1. Classroom / Section Attendance Trends
        $classrooms = Classroom::where('school_id', $schoolId)->with('academicClass')->get();
        $classroomStats = [];
        foreach ($classrooms as $room) {
            $total = AttendanceRecord::where('school_id', $schoolId)
                ->whereHas('session', function($q) use ($room) {
                    $q->where('classroom_id', $room->id);
                })->count();

            $present = AttendanceRecord::where('school_id', $schoolId)
                ->whereHas('session', function($q) use ($room) {
                    $q->where('classroom_id', $room->id);
                })->where('status', 'present')->count();

            $classroomStats[] = [
                'name' => $room->display_name,
                'pct' => $total > 0 ? round(($present / $total) * 100, 1) : 100.0,
                'total_sessions' => AttendanceSession::where('classroom_id', $room->id)->count()
            ];
        }

        // 2. Subject-wise Absentee Percentage
        $subjects = Subject::where('school_id', $schoolId)->get();
        $subjectStats = [];
        foreach ($subjects as $sub) {
            $total = AttendanceRecord::where('school_id', $schoolId)
                ->whereHas('session', function($q) use ($sub) {
                    $q->where('subject_id', $sub->id);
                })->count();

            $absent = AttendanceRecord::where('school_id', $schoolId)
                ->whereHas('session', function($q) use ($sub) {
                    $q->where('subject_id', $sub->id);
                })->where('status', 'absent')->count();

            $subjectStats[] = [
                'name' => $sub->name,
                'absentee_pct' => $total > 0 ? round(($absent / $total) * 100, 1) : 0.0
            ];
        }

        // 3. Low Attendance Students List (< 75%)
        $allStudents = Student::where('school_id', $schoolId)->with(['user', 'classroom.academicClass'])->get();
        $lowAttendanceStudents = $allStudents->filter(fn($s) => $s->overall_attendance < 75.0)->values();

        // 4. PMRS Intelligent Integration: High Risk & Learning Difficulty
        $highRiskStudents = $allStudents->filter(fn($s) => $s->is_high_risk)->values();
        $learningDifficultyStudents = $allStudents->filter(fn($s) => $s->is_learning_difficulty)->values();

        // 5. Teacher Attendance Completion Reports
        $teachers = User::where('school_id', $schoolId)->where('role', 'teacher')->get();
        $teacherReports = [];
        foreach ($teachers as $teacher) {
            $slotsCount = Timetable::where('teacher_id', $teacher->id)->count();
            $markedSessionsCount = AttendanceSession::where('teacher_id', $teacher->id)->count();

            $teacherReports[] = [
                'name' => $teacher->name,
                'slots' => $slotsCount,
                'marked' => $markedSessionsCount,
                'completion_rate' => $slotsCount > 0 ? round(($markedSessionsCount / $slotsCount) * 100, 1) : 100.0
            ];
        }

        return view('admin.analytics.attendance', compact(
            'classroomStats', 'subjectStats', 'lowAttendanceStudents', 
            'highRiskStudents', 'learningDifficultyStudents', 'teacherReports'
        ));
    }
}
