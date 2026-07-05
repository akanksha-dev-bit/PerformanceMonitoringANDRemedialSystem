<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Timetable;
use App\Models\Classroom;
use App\Models\TeacherSubjectAssignment;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TimetableController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $classrooms = Classroom::where('school_id', $schoolId)->get();
        
        $selectedClassroomId = $request->input('classroom_id', $classrooms->first()?->id);
        
        $timetable = [];
        if ($selectedClassroomId) {
            $timetable = Timetable::where('school_id', $schoolId)
                ->where('classroom_id', $selectedClassroomId)
                ->with(['subject', 'teacher'])
                ->orderBy('period_number')
                ->get();
        }

        // Get allocations so we can populate dropdowns reactively (Teacher + Subject + Classroom combinations)
        $allocations = TeacherSubjectAssignment::where('school_id', $schoolId)
            ->with(['teacher', 'subject', 'classroom'])
            ->get();

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        return view('admin.timetable.index', compact('classrooms', 'selectedClassroomId', 'timetable', 'allocations', 'days'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'allocation_id' => 'required|exists:teacher_subject_assignments,id',
            'day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'period_number' => 'required|integer|min:1|max:10',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $schoolId = auth()->user()->school_id;

        // Fetch the allocation details
        $allocation = TeacherSubjectAssignment::where('id', $request->allocation_id)
            ->where('school_id', $schoolId)
            ->firstOrFail();

        try {
            Timetable::create([
                'school_id' => $schoolId,
                'classroom_id' => $request->classroom_id,
                'subject_id' => $allocation->subject_id,
                'teacher_id' => $allocation->teacher_id,
                'day_of_week' => $request->day_of_week,
                'period_number' => $request->period_number,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('timetables.index', ['classroom_id' => $request->classroom_id])
            ->with('success', 'Timetable slot scheduled successfully!');
    }

    public function destroy(Timetable $timetable)
    {
        if ($timetable->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $classroomId = $timetable->classroom_id;
        $timetable->delete();

        return redirect()->route('timetables.index', ['classroom_id' => $classroomId])
            ->with('success', 'Timetable slot deleted successfully.');
    }
}
