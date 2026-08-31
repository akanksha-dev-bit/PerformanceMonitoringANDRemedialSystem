<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\TeacherSubjectAssignment;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * TimetableSlotController — JSON API for managing timetable entries.
 *
 * A timetable slot schedules an approved allocation (teacher + subject + section)
 * into a specific period on a specific day. Conflict validation is enforced at
 * both the PHP model level (human-readable errors) and the DB unique index.
 *
 * Routes (admin-only):
 *   GET    /api/timetable-slots                → index  (filter by ?section_id=, ?year_id=, ?day=)
 *   POST   /api/timetable-slots                → store
 *   DELETE /api/timetable-slots/{slot}         → destroy
 */
class TimetableSlotController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $schoolId = auth()->user()->school_id;

        $query = Timetable::where('school_id', $schoolId)
            ->with(['classroom.academicClass', 'subject', 'teacher', 'academicYear']);

        if ($request->filled('section_id')) {
            $query->where('classroom_id', $request->section_id);
        }

        if ($request->filled('year_id')) {
            $query->where('academic_year_id', $request->year_id);
        }

        if ($request->filled('day')) {
            $query->where('day_of_week', $request->day);
        }

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $slots = $query->orderBy('day_of_week')->orderBy('period_number')->get()
            ->map(fn(Timetable $t) => [
                'id'            => $t->id,
                'section'       => ['id' => $t->classroom?->id, 'display_name' => $t->classroom?->display_name],
                'subject'       => ['id' => $t->subject?->id, 'name' => $t->subject?->name],
                'teacher'       => ['id' => $t->teacher?->id, 'name' => $t->teacher?->name],
                'day_of_week'   => $t->day_of_week,
                'period_number' => $t->period_number,
                'start_time'    => $t->start_time,
                'end_time'      => $t->end_time,
                'academic_year' => ['id' => $t->academicYear?->id, 'label' => $t->academicYear?->label],
            ]);

        return response()->json($slots);
    }

    public function store(Request $request): JsonResponse
    {
        $schoolId = auth()->user()->school_id;

        $request->validate([
            'classroom_id'     => 'required|exists:classrooms,id',
            'allocation_id'    => 'required|exists:teacher_subject_assignments,id',
            'day_of_week'      => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'period_number'    => 'required|integer|min:1|max:12',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'academic_year_id' => 'nullable|exists:academic_years,id',
        ]);

        $classroom = Classroom::where('id', $request->classroom_id)
            ->where('school_id', $schoolId)
            ->firstOrFail();

        $allocation = TeacherSubjectAssignment::where('id', $request->allocation_id)
            ->where('school_id', $schoolId)
            ->firstOrFail();

        // Ensure the allocation is for this section
        if ($allocation->classroom_id !== $classroom->id) {
            return response()->json([
                'message' => 'The selected allocation does not belong to this section.',
            ], 422);
        }

        // Resolve academic year
        $yearId = $request->academic_year_id;
        if (empty($yearId)) {
            $yearId = AcademicYear::currentFor($schoolId)?->id;
        } else {
            AcademicYear::where('id', $yearId)->where('school_id', $schoolId)->firstOrFail();
        }

        try {
            $slot = Timetable::create([
                'school_id'        => $schoolId,
                'academic_year_id' => $yearId,
                'classroom_id'     => $classroom->id,
                'subject_id'       => $allocation->subject_id,
                'teacher_id'       => $allocation->teacher_id,
                'day_of_week'      => $request->day_of_week,
                'period_number'    => $request->period_number,
                'start_time'       => $request->start_time,
                'end_time'         => $request->end_time,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Scheduling conflict detected.',
                'errors'  => $e->errors(),
            ], 422);
        }

        return response()->json([
            'message' => 'Timetable slot created.',
            'data'    => $slot->load(['classroom.academicClass', 'subject', 'teacher', 'academicYear']),
        ], 201);
    }

    public function destroy(Timetable $timetableSlot): JsonResponse
    {
        if ($timetableSlot->school_id !== auth()->user()->school_id) {
            abort(403, 'Access denied.');
        }

        $timetableSlot->delete();

        return response()->json(['message' => 'Timetable slot deleted.']);
    }
}
