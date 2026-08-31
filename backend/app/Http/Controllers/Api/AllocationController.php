<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\TeacherSubjectAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * AllocationController — JSON API for teacher-subject-section allocations.
 *
 * An allocation links a teacher to a subject in a specific section for
 * a given academic year. Once an allocation exists, timetable slots can
 * reference it to schedule the teacher.
 *
 * Routes (admin-only):
 *   GET    /api/allocations                  → index  (filter by ?year_id=, ?section_id=)
 *   POST   /api/allocations                  → store
 *   DELETE /api/allocations/{allocation}     → destroy
 */
class AllocationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $schoolId = auth()->user()->school_id;

        $query = TeacherSubjectAssignment::where('school_id', $schoolId)
            ->with(['teacher', 'subject', 'classroom.academicClass', 'academicYear']);

        if ($request->filled('year_id')) {
            $query->where('academic_year_id', $request->year_id);
        }

        if ($request->filled('section_id')) {
            $query->where('classroom_id', $request->section_id);
        }

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $allocations = $query->get()->map(fn(TeacherSubjectAssignment $a) => [
            'id'            => $a->id,
            'teacher'       => ['id' => $a->teacher?->id, 'name' => $a->teacher?->name],
            'subject'       => ['id' => $a->subject?->id, 'name' => $a->subject?->name, 'code' => $a->subject?->code],
            'section'       => ['id' => $a->classroom?->id, 'display_name' => $a->classroom?->display_name],
            'academic_year' => ['id' => $a->academicYear?->id, 'label' => $a->academicYear?->label],
        ]);

        return response()->json($allocations);
    }

    public function store(Request $request): JsonResponse
    {
        $schoolId = auth()->user()->school_id;

        $validated = $request->validate([
            'teacher_id'       => 'required|exists:users,id',
            'subject_id'       => 'required|exists:subjects,id',
            'classroom_id'     => 'required|exists:classrooms,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
        ]);

        // Verify each entity belongs to this school
        $teacher = User::where('id', $validated['teacher_id'])
            ->where('role', 'teacher')
            ->where('school_id', $schoolId)
            ->firstOrFail();

        $subject = Subject::where('id', $validated['subject_id'])
            ->where('school_id', $schoolId)
            ->firstOrFail();

        $classroom = Classroom::where('id', $validated['classroom_id'])
            ->where('school_id', $schoolId)
            ->firstOrFail();

        // Default to current year if not specified
        if (empty($validated['academic_year_id'])) {
            $currentYear = AcademicYear::currentFor($schoolId);
            $validated['academic_year_id'] = $currentYear?->id;
        } else {
            AcademicYear::where('id', $validated['academic_year_id'])
                ->where('school_id', $schoolId)
                ->firstOrFail();
        }

        // Duplicate check (same teacher, subject, section, year)
        $exists = TeacherSubjectAssignment::where('school_id', $schoolId)
            ->where('teacher_id', $validated['teacher_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('classroom_id', $validated['classroom_id'])
            ->where('academic_year_id', $validated['academic_year_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'This allocation already exists for the given teacher, subject, section, and year.',
            ], 422);
        }

        $allocation = TeacherSubjectAssignment::create(
            array_merge($validated, ['school_id' => $schoolId])
        );

        return response()->json([
            'message' => 'Allocation created.',
            'data'    => $allocation->load(['teacher', 'subject', 'classroom.academicClass', 'academicYear']),
        ], 201);
    }

    public function destroy(TeacherSubjectAssignment $allocation): JsonResponse
    {
        if ($allocation->school_id !== auth()->user()->school_id) {
            abort(403, 'Access denied.');
        }

        // Guard: if this allocation has live timetable entries, warn the user
        if ($allocation->timetables()->exists()) {
            return response()->json([
                'message' => 'Cannot remove an allocation that has timetable slots. Delete the timetable entries first.',
            ], 422);
        }

        $allocation->delete();

        return response()->json(['message' => 'Allocation removed.']);
    }
}
