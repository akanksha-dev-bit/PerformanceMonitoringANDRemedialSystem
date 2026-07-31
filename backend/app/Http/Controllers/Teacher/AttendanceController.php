<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use App\Models\Student;
use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use App\Models\AttendanceActivityLog;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user->isTeacher()) {
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['message' => 'Only teachers can access attendance.'], 403);
            }
            abort(403, 'Only teachers can access attendance.');
        }

        $today = now()->format('l');

        // Fetch today's schedule for this teacher
        $todaySlots = Timetable::where('school_id', $user->school_id)
            ->where('teacher_id', $user->id)
            ->where('day_of_week', $today)
            ->with(['classroom.academicClass', 'subject'])
            ->orderBy('period_number')
            ->get();

        // Also get existing attendance sessions completed today to display completion state
        $completedSessionKeys = AttendanceSession::where('school_id', $user->school_id)
            ->where('teacher_id', $user->id)
            ->where('date', now()->toDateString())
            ->pluck('period_number')
            ->toArray();

        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'todaySlots' => $todaySlots,
                'completedSessionKeys' => $completedSessionKeys,
                'today' => $today,
            ]);
        }

        return view('teacher.attendance.index', compact('todaySlots', 'completedSessionKeys', 'today'));
    }

    public function mark(Request $request, Timetable $timetable)
    {
        $user = auth()->user();
        if (!$user->isTeacher() || $timetable->teacher_id !== $user->id) {
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }
            abort(403);
        }

        $timetable->load(['classroom.academicClass', 'subject']);
        $classroom = $timetable->classroom;
        $students = Student::where('classroom_id', $classroom->id)
            ->where('school_id', $user->school_id)
            ->orderBy('roll_no')
            ->with('user')
            ->get();

        // Check if session was already marked for today
        $session = AttendanceSession::where('classroom_id', $timetable->classroom_id)
            ->where('subject_id', $timetable->subject_id)
            ->where('date', now()->toDateString())
            ->where('period_number', $timetable->period_number)
            ->with('records')
            ->first();

        $savedRecords = [];
        if ($session) {
            $savedRecords = $session->records->pluck('status', 'student_id')->toArray();
        }

        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'timetable' => $timetable,
                'students' => $students,
                'session' => $session,
                'savedRecords' => $savedRecords,
            ]);
        }

        return view('teacher.attendance.mark', compact('timetable', 'students', 'session', 'savedRecords'));
    }

    public function store(Request $request, Timetable $timetable)
    {
        $user = auth()->user();
        if (!$user->isTeacher() || $timetable->teacher_id !== $user->id) {
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }
            abort(403);
        }

        $request->validate([
            'headcount' => 'required|integer|min:0',
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,absent',
        ]);

        $classroom = $timetable->classroom;
        $students = Student::where('classroom_id', $classroom->id)->get();

        $attendanceData = $request->input('attendance', []);
        $presentCount = collect($attendanceData)->filter(fn($val) => $val === 'present')->count();
        $headcount = (int)$request->input('headcount');

        // Strict headcount check
        if ($presentCount !== $headcount) {
            $errorMsg = "Attendance mismatch detected. Present Count: {$presentCount}, Physical Headcount entered: {$headcount}. Please recheck before final submission.";
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'message' => $errorMsg,
                    'errors' => [
                        'headcount' => [$errorMsg]
                    ]
                ], 422);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', $errorMsg);
        }

        // Get or Create Session
        $existingSession = AttendanceSession::where('classroom_id', $timetable->classroom_id)
            ->where('subject_id', $timetable->subject_id)
            ->where('date', now()->toDateString())
            ->where('period_number', $timetable->period_number)
            ->first();

        $isNew = !$existingSession;

        $session = AttendanceSession::updateOrCreate(
            [
                'school_id' => $user->school_id,
                'classroom_id' => $timetable->classroom_id,
                'subject_id' => $timetable->subject_id,
                'date' => now()->toDateString(),
                'period_number' => $timetable->period_number,
            ],
            [
                'teacher_id' => $user->id,
                'timetable_id' => $timetable->id,
                'headcount' => $headcount,
                'created_by' => $existingSession ? $existingSession->created_by : $user->id,
                'updated_by' => $existingSession ? $user->id : null,
            ]
        );

        // Update student records
        foreach ($students as $student) {
            $status = isset($attendanceData[$student->id]) ? $attendanceData[$student->id] : 'present';
            
            AttendanceRecord::updateOrCreate(
                [
                    'school_id' => $user->school_id,
                    'attendance_session_id' => $session->id,
                    'student_id' => $student->id,
                ],
                [
                    'status' => $status,
                ]
            );
        }

        // Audit Trail Activity Log
        AttendanceActivityLog::create([
            'school_id' => $user->school_id,
            'attendance_session_id' => $session->id,
            'user_id' => $user->id,
            'action' => $isNew ? 'created' : 'updated_records',
            'details' => [
                'headcount' => $headcount,
                'absentees' => collect($attendanceData)->filter(fn($val) => $val === 'absent')->keys()->toArray(),
            ]
        ]);

        $successMsg = 'Attendance marked successfully and synced to academic audit records!';
        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'message' => $successMsg,
                'session' => $session
            ]);
        }

        return redirect()->route('attendance.index')
            ->with('success', $successMsg);
    }
}
