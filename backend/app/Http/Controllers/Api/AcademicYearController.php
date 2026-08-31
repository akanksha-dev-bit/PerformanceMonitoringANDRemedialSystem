<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * AcademicYearController — JSON API for managing academic years.
 *
 * Routes (all admin-only):
 *   GET    /api/academic-years              → index
 *   POST   /api/academic-years              → store
 *   GET    /api/academic-years/{year}       → show
 *   PUT    /api/academic-years/{year}       → update
 *   DELETE /api/academic-years/{year}       → destroy
 *   POST   /api/academic-years/{year}/set-current → setCurrent
 */
class AcademicYearController extends Controller
{
    public function index(): JsonResponse
    {
        $schoolId = auth()->user()->school_id;

        $years = AcademicYear::where('school_id', $schoolId)
            ->orderByDesc('starts_on')
            ->get();

        return response()->json($years);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'label'      => 'required|string|max:20',
            'starts_on'  => 'required|date',
            'ends_on'    => 'required|date|after:starts_on',
            'is_current' => 'boolean',
        ]);

        $schoolId = auth()->user()->school_id;
        $validated['school_id'] = $schoolId;

        // Prevent duplicate labels per school
        $exists = AcademicYear::where('school_id', $schoolId)
            ->where('label', $validated['label'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'An academic year with this label already exists.',
            ], 422);
        }

        $year = AcademicYear::create($validated);

        if (!empty($validated['is_current'])) {
            $year->setAsCurrent();
        }

        return response()->json([
            'message' => 'Academic year created.',
            'data'    => $year->fresh(),
        ], 201);
    }

    public function show(AcademicYear $academicYear): JsonResponse
    {
        $this->authorizeSchool($academicYear);

        return response()->json($academicYear->load(['classrooms.academicClass']));
    }

    public function update(Request $request, AcademicYear $academicYear): JsonResponse
    {
        $this->authorizeSchool($academicYear);

        $validated = $request->validate([
            'label'     => 'sometimes|required|string|max:20',
            'starts_on' => 'sometimes|required|date',
            'ends_on'   => 'sometimes|required|date|after:starts_on',
        ]);

        $academicYear->update($validated);

        return response()->json([
            'message' => 'Academic year updated.',
            'data'    => $academicYear,
        ]);
    }

    public function destroy(AcademicYear $academicYear): JsonResponse
    {
        $this->authorizeSchool($academicYear);

        if ($academicYear->is_current) {
            return response()->json([
                'message' => 'Cannot delete the currently active academic year. Set another year as current first.',
            ], 422);
        }

        $academicYear->delete();

        return response()->json(['message' => 'Academic year deleted.']);
    }

    public function setCurrent(AcademicYear $academicYear): JsonResponse
    {
        $this->authorizeSchool($academicYear);

        $academicYear->setAsCurrent();

        return response()->json([
            'message' => "'{$academicYear->label}' is now the active academic year.",
            'data'    => $academicYear->fresh(),
        ]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function authorizeSchool(AcademicYear $year): void
    {
        if ($year->school_id !== auth()->user()->school_id) {
            abort(403, 'Access denied.');
        }
    }
}
