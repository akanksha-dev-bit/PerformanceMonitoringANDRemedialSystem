<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * SectionController — JSON API for managing classrooms (sections).
 *
 * A "section" (stored in the classrooms table) is a year-specific grouping
 * of students within a grade level. E.g. "Class 10 - A" for 2025-26.
 *
 * Routes (admin-only):
 *   GET    /api/sections              → index  (filter by ?year_id=, ?class_id=)
 *   POST   /api/sections              → store
 *   GET    /api/sections/{section}    → show
 *   PUT    /api/sections/{section}    → update
 *   DELETE /api/sections/{section}    → destroy
 */
class SectionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $schoolId = auth()->user()->school_id;

        $query = Classroom::where('school_id', $schoolId)
            ->with(['academicClass', 'academicYear'])
            ->withCount('students');

        if ($request->filled('year_id')) {
            $query->where('academic_year_id', $request->year_id);
        }

        if ($request->filled('class_id')) {
            $query->where('academic_class_id', $request->class_id);
        }

        $sections = $query->orderBy('name')->get()->map(function (Classroom $c) {
            return [
                'id'              => $c->id,
                'name'            => $c->name,
                'display_name'    => $c->display_name,
                'capacity'        => $c->capacity,
                'students_count'  => $c->students_count,
                'academic_class'  => $c->academicClass?->only(['id', 'name']),
                'academic_year'   => $c->academicYear?->only(['id', 'label']),
                'is_full'         => $c->is_full,
            ];
        });

        return response()->json($sections);
    }

    public function store(Request $request): JsonResponse
    {
        $schoolId = auth()->user()->school_id;

        $validated = $request->validate([
            'name'              => 'required|string|max:50',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'academic_year_id'  => 'nullable|exists:academic_years,id',
            'capacity'          => 'nullable|integer|min:1|max:500',
        ]);

        // Verify class belongs to this school
        $class = AcademicClass::where('id', $validated['academic_class_id'])
            ->where('school_id', $schoolId)
            ->firstOrFail();

        // If no year specified, fall back to the current year
        if (empty($validated['academic_year_id'])) {
            $currentYear = AcademicYear::currentFor($schoolId);
            if ($currentYear) {
                $validated['academic_year_id'] = $currentYear->id;
            }
        } else {
            // Verify year belongs to this school
            AcademicYear::where('id', $validated['academic_year_id'])
                ->where('school_id', $schoolId)
                ->firstOrFail();
        }

        // Duplicate check: (school, class, year, name)
        $exists = Classroom::where('school_id', $schoolId)
            ->where('academic_class_id', $validated['academic_class_id'])
            ->where('academic_year_id', $validated['academic_year_id'])
            ->where('name', $validated['name'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A section with this name already exists for this class and year.',
            ], 422);
        }

        $section = Classroom::create(array_merge($validated, ['school_id' => $schoolId]));

        return response()->json([
            'message' => 'Section created.',
            'data'    => $section->load(['academicClass', 'academicYear']),
        ], 201);
    }

    public function show(Classroom $section): JsonResponse
    {
        $this->authorizeSchool($section);

        return response()->json(
            $section->load(['academicClass', 'academicYear', 'teacherSubjectAssignments.teacher', 'teacherSubjectAssignments.subject'])
                    ->append(['display_name', 'is_full'])
        );
    }

    public function update(Request $request, Classroom $section): JsonResponse
    {
        $this->authorizeSchool($section);

        $validated = $request->validate([
            'name'              => 'sometimes|required|string|max:50',
            'academic_class_id' => 'sometimes|required|exists:academic_classes,id',
            'academic_year_id'  => 'sometimes|nullable|exists:academic_years,id',
            'capacity'          => 'sometimes|nullable|integer|min:1|max:500',
        ]);

        $section->update($validated);

        return response()->json([
            'message' => 'Section updated.',
            'data'    => $section->fresh(['academicClass', 'academicYear']),
        ]);
    }

    public function destroy(Classroom $section): JsonResponse
    {
        $this->authorizeSchool($section);

        if ($section->students()->exists()) {
            return response()->json([
                'message' => 'Cannot delete a section that has enrolled students. Reassign them first.',
            ], 422);
        }

        if ($section->timetables()->exists()) {
            return response()->json([
                'message' => 'Cannot delete a section that has timetable entries. Clear the timetable first.',
            ], 422);
        }

        $section->delete();

        return response()->json(['message' => 'Section deleted.']);
    }

    private function authorizeSchool(Classroom $section): void
    {
        if ($section->school_id !== auth()->user()->school_id) {
            abort(403, 'Access denied.');
        }
    }
}
