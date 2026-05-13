<?php

namespace App\Http\Controllers;

use App\Models\RemedialAction;
use App\Models\Student;
use Illuminate\Http\Request;

class RemedialController extends Controller
{
    public function index(Request $request)
    {
        $query = RemedialAction::with(['student', 'assignedByUser'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $actions  = $query->paginate(15)->withQueryString();
        $students = Student::active()->orderedByName()->with('user')->get();

        return view('remedial.index', compact('actions', 'students'));
    }

    public function create(Request $request)
    {
        $students = Student::active()->orderedByName()->with('user')->get();
        $selectedStudent = $request->filled('student_id')
            ? Student::find($request->student_id)
            : null;

        return view('remedial.create', compact('students', 'selectedStudent'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'     => 'required|exists:students,id',
            'action_type'    => 'required|in:extra_class,counseling,peer_tutoring,assignment,parent_meeting,other,quiz_test,practice_session',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'status'         => 'required|in:pending,in_progress,completed,cancelled',
            'scheduled_date' => 'nullable|date',
            'outcome'        => 'nullable|string',
        ]);

        $validated['assigned_by'] = auth()->id();
        RemedialAction::create($validated);

        return redirect()->route('remedial.index')
            ->with('success', 'Remedial action assigned successfully!');
    }

    public function edit(RemedialAction $remedial)
    {
        $students = Student::active()->orderedByName()->with('user')->get();
        return view('remedial.edit', compact('remedial', 'students'));
    }

    public function update(Request $request, RemedialAction $remedial)
    {
        $validated = $request->validate([
            'action_type'     => 'required|in:extra_class,counseling,peer_tutoring,assignment,parent_meeting,other,quiz_test,practice_session',
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'status'          => 'required|in:pending,in_progress,completed,cancelled',
            'scheduled_date'  => 'nullable|date',
            'completed_date'  => 'nullable|date|after_or_equal:scheduled_date',
            'outcome'         => 'nullable|string',
        ]);

        $remedial->update($validated);

        return redirect()->route('remedial.index')
            ->with('success', 'Remedial action updated!');
    }

    public function destroy(RemedialAction $remedial)
    {
        $remedial->delete();
        return back()->with('success', 'Remedial action removed.');
    }
}
