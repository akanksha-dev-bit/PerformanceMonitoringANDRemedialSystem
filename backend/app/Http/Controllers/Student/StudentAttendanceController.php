<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\AttendanceRecord;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentAttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user->isStudent()) {
            abort(403);
        }

        $student = Student::where('user_id', $user->id)
            ->where('school_id', $user->school_id)
            ->firstOrFail();

        $overallAttendance = $student->overall_attendance;

        // Fetch all subjects taught in this school
        $subjects = Subject::where('school_id', $user->school_id)->get();

        $subjectBreakdown = [];
        foreach ($subjects as $subject) {
            $percentage = $student->getSubjectAttendance($subject->id);
            
            // Count total and present sessions
            $total = $student->attendanceRecords()
                ->whereHas('session', function($q) use ($subject) {
                    $q->where('subject_id', $subject->id);
                })->count();

            if ($total > 0) {
                $present = $student->attendanceRecords()
                    ->whereHas('session', function($q) use ($subject) {
                        $q->where('subject_id', $subject->id);
                    })
                    ->where('status', 'present')
                    ->count();

                $subjectBreakdown[] = [
                    'subject' => $subject->name,
                    'percentage' => $percentage,
                    'present' => $present,
                    'total' => $total,
                    'color' => $percentage >= 75 ? '#22c55e' : '#ef4444',
                ];
            }
        }

        // Fetch absent history
        $absentHistory = AttendanceRecord::where('student_id', $student->id)
            ->where('status', 'absent')
            ->with(['session.subject', 'session.teacher'])
            ->orderByDesc('created_at')
            ->get();

        // Calculate Monthly Trends (Presence percentage for the last 5 months)
        $isPgsql = DB::getDriverName() === 'pgsql';
        $monthExpr = $isPgsql 
            ? "to_char(attendance_sessions.date, 'FMMonth YYYY')"
            : "DATE_FORMAT(attendance_sessions.date, '%M %Y')";

        $monthlyTrends = DB::table('attendance_records')
            ->join('attendance_sessions', 'attendance_records.attendance_session_id', '=', 'attendance_sessions.id')
            ->select(
                DB::raw("{$monthExpr} as month"),
                DB::raw("COUNT(attendance_records.id) as total"),
                DB::raw("SUM(CASE WHEN attendance_records.status = 'present' THEN 1 ELSE 0 END) as present")
            )
            ->where('attendance_records.student_id', $student->id)
            ->groupBy(DB::raw($monthExpr))
            ->orderBy(DB::raw("MIN(attendance_sessions.date)"))
            ->take(5)
            ->get()
            ->map(function($row) {
                return [
                    'month' => $row->month,
                    'pct' => $row->total > 0 ? round(($row->present / $row->total) * 100, 1) : 100.0
                ];
            });

        // Trigger warning if overall is low
        $alertMessage = null;
        if ($overallAttendance < 75) {
            $alertMessage = "⚠️ Low Attendance Alert: Your overall attendance is currently at {$overallAttendance}%. You need at least 75% to be compliant with school regulations.";
        }

        return view('student.attendance.index', compact('student', 'overallAttendance', 'subjectBreakdown', 'absentHistory', 'monthlyTrends', 'alertMessage'));
    }
}
