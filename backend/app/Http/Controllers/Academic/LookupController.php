<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Http\Request;

class LookupController extends Controller
{
    /**
     * Get all classrooms (sections) for selection in student enrollment forms.
     *
     * Returns sections scoped to the current school, enriched with the parent
     * AcademicClass name and the year label so dropdowns show the full context.
     *
     * Optionally filter by ?year_id=N to show only sections for a given year.
     */
    public function classes(Request $request)
    {
        $schoolId = auth()->user()->school_id;

        $query = Classroom::with('academicClass', 'academicYear')
            ->where('school_id', $schoolId);

        if ($request->filled('year_id')) {
            $query->where('academic_year_id', $request->year_id);
        }

        $classrooms = $query->get()->map(function ($c) {
            return [
                'id'               => $c->id,
                'display_name'     => $c->display_name,
                'name'             => $c->name,
                'class'            => $c->academicClass ? $c->academicClass->name : '',
                'academic_year_id' => $c->academic_year_id,
                'year_label'       => $c->academicYear ? $c->academicYear->label : null,
            ];
        });

        return response()->json($classrooms);
    }

    /**
     * Get all teachers for selection.
     */
    public function teachers()
    {
        $schoolId = auth()->user()->school_id;
        $teachers = Teacher::with('user')
            ->where('school_id', $schoolId)
            ->get()
            ->map(function($t) {
                return [
                    'id' => $t->id,
                    'name' => $t->user ? $t->user->name : 'Unknown',
                    'email' => $t->user ? $t->user->email : '',
                ];
            });
        return response()->json($teachers);
    }

    /**
     * Get all subjects for selection.
     */
    public function subjects()
    {
        $schoolId = auth()->user()->school_id;
        $subjects = Subject::where('school_id', $schoolId)
            ->orderBy('name')
            ->get();
        return response()->json($subjects);
    }
}
