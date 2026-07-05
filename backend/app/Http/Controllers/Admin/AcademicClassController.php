<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use Illuminate\Http\Request;

class AcademicClassController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;
        $classes = AcademicClass::where('school_id', $schoolId)->withCount('classrooms')->get();
        return view('admin.classes.index', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $schoolId = auth()->user()->school_id;

        AcademicClass::create([
            'school_id' => $schoolId,
            'name' => $request->name,
        ]);

        return redirect()->route('classes.index')->with('success', 'Academic class created successfully!');
    }

    public function update(Request $request, AcademicClass $class)
    {
        if ($class->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $class->update([
            'name' => $request->name,
        ]);

        return redirect()->route('classes.index')->with('success', 'Academic class updated successfully!');
    }

    public function destroy(AcademicClass $class)
    {
        if ($class->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $class->delete();

        return redirect()->route('classes.index')->with('success', 'Academic class deleted successfully!');
    }
}
