<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * ClassController — JSON API for managing academic classes (grade levels).
 *
 * Routes (admin-only):
 *   GET    /api/classes             → index  (all classes for this school)
 *   POST   /api/classes             → store
 *   PUT    /api/classes/{class}     → update
 *   DELETE /api/classes/{class}     → destroy
 */
class ClassController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $schoolId = auth()->user()->school_id;

        $classes = AcademicClass::where('school_id', $schoolId)
            ->withCount('classrooms')
            ->orderBy('name')
            ->get();

        return response()->json($classes);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $schoolId = auth()->user()->school_id;

        $exists = AcademicClass::where('school_id', $schoolId)
            ->where('name', $validated['name'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'A class with this name already exists.',
            ], 422);
        }

        $class = AcademicClass::create([
            'school_id' => $schoolId,
            'name'      => $validated['name'],
        ]);

        return response()->json([
            'message' => 'Class created.',
            'data'    => $class,
        ], 201);
    }

    public function update(Request $request, AcademicClass $class): JsonResponse
    {
        $this->authorizeSchool($class);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $class->update($validated);

        return response()->json([
            'message' => 'Class updated.',
            'data'    => $class,
        ]);
    }

    public function destroy(AcademicClass $class): JsonResponse
    {
        $this->authorizeSchool($class);

        // Prevent deletion if sections exist
        if ($class->classrooms()->exists()) {
            return response()->json([
                'message' => 'Cannot delete a class that has sections. Remove all sections first.',
            ], 422);
        }

        $class->delete();

        return response()->json(['message' => 'Class deleted.']);
    }

    private function authorizeSchool(AcademicClass $class): void
    {
        if ($class->school_id !== auth()->user()->school_id) {
            abort(403, 'Access denied.');
        }
    }
}
