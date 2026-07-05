<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;
        $classrooms = Classroom::where('school_id', $schoolId)->with('academicClass')->withCount('students')->get();
        $classes = AcademicClass::where('school_id', $schoolId)->get();
        return view('admin.classrooms.index', compact('classrooms', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_class_id' => 'required|exists:academic_classes,id',
            'name' => 'required|string|max:255',
        ]);

        $schoolId = auth()->user()->school_id;

        Classroom::create([
            'school_id' => $schoolId,
            'academic_class_id' => $request->academic_class_id,
            'name' => $request->name,
        ]);

        return redirect()->route('classrooms.index')->with('success', 'Classroom/Section created successfully!');
    }

    public function update(Request $request, Classroom $classroom)
    {
        if ($classroom->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $request->validate([
            'academic_class_id' => 'required|exists:academic_classes,id',
            'name' => 'required|string|max:255',
        ]);

        $classroom->update([
            'academic_class_id' => $request->academic_class_id,
            'name' => $request->name,
        ]);

        return redirect()->route('classrooms.index')->with('success', 'Classroom/Section updated successfully!');
    }

    public function destroy(Classroom $classroom)
    {
        if ($classroom->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $classroom->delete();

        return redirect()->route('classrooms.index')->with('success', 'Classroom/Section deleted successfully!');
    }
}
