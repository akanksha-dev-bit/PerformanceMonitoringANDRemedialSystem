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
        $attendanceTotal = $student->attendanceRecords()->count();

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

        // Show the last five calendar months, including months without sessions.
        $monthlyRecords = DB::table('attendance_records')
            ->join('attendance_sessions', 'attendance_records.attendance_session_id', '=', 'attendance_sessions.id')
            ->select('attendance_sessions.date', 'attendance_records.status')
            ->where('attendance_records.student_id', $student->id)
            ->where('attendance_sessions.date', '>=', now()->startOfMonth()->subMonths(4)->toDateString())
            ->get()
            ->groupBy(fn ($record) => substr((string) $record->date, 0, 7));

        $monthlyTrends = collect(range(4, 0))->map(function ($monthsAgo) use ($monthlyRecords) {
                $month = now()->startOfMonth()->subMonths($monthsAgo);
                $records = $monthlyRecords->get($month->format('Y-m'), collect());
                $total = $records->count();

                return [
                    'month' => $month->format('M'),
                    'label' => $month->format('F Y'),
                    'pct' => $total > 0 ? round($records->where('status', 'present')->count() / $total * 100, 1) : null,
                    'total' => $total,
                ];
            });

        // Trigger warning if overall is low
        $alertMessage = null;
        if ($attendanceTotal > 0 && $overallAttendance < 75) {
            $alertMessage = "⚠️ Low Attendance Alert: Your overall attendance is currently at {$overallAttendance}%. You need at least 75% to be compliant with school regulations.";
        }

        return view('student.attendance.index', compact('student', 'overallAttendance', 'attendanceTotal', 'subjectBreakdown', 'absentHistory', 'monthlyTrends', 'alertMessage'));
    }
}
