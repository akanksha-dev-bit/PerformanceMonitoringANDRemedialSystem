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
     * Get all classrooms for selection.
     */
    public function classes()
    {
        $schoolId = auth()->user()->school_id;
        $classrooms = Classroom::with('academicClass')
            ->where('school_id', $schoolId)
            ->get()
            ->map(function($c) {
                return [
                    'id' => $c->id,
                    'display_name' => $c->display_name,
                    'class' => $c->academicClass ? $c->academicClass->name : '',
                    'name' => $c->name,
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
