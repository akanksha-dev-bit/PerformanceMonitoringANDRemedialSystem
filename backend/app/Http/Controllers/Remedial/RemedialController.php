<?php

/**
 * ============================================================================
 * RemedialController — Remedial Action Assignment & Management
 * ============================================================================
 *
 * PURPOSE:
 *   The core of PMRS's remedial system. When a student is identified as a
 *   slow learner, Teachers/Admins use this controller to assign targeted
 *   remedial actions (extra classes, assignments, essays, file uploads,
 *   counseling sessions, parent meetings, etc.)
 *
 * ACTION TYPES SUPPORTED:
 *   extra_class, counseling, peer_tutoring, assignment, parent_meeting,
 *   quiz_test, practice_session, written_assignment, essay, file_upload, other
 *
 * HOW IT WORKS:
 *   - Teacher identifies slow learners via Performance/Reports pages.
 *   - Clicks "Create Remedial" → selects student, action type, title,
 *     description, due date, word limits (for essays), max score, etc.
 *   - Each action is tracked with status: pending → in_progress → completed.
 *   - Teacher can view all submissions for a specific remedial action.
 *   - Actions are school-scoped (school_id enforced on create + queries).
 *
 * ROUTES (Full Resource + Submissions):
 *   GET    /remedial                          → index()           — List all actions
 *   GET    /remedial/create                   → create()          — Create form
 *   POST   /remedial                          → store()           — Save new action
 *   GET    /remedial/{remedial}               → show()            — Action details
 *   GET    /remedial/{remedial}/edit          → edit()            — Edit form
 *   PUT    /remedial/{remedial}               → update()          — Save changes
 *   DELETE /remedial/{remedial}               → destroy()         — Remove action
 *   GET    /remedial/{remedial}/submissions   → showSubmissions() — View submissions
 *
 * SECURITY:
 *   - Cross-school check on showSubmissions (abort 403 if mismatch).
 *   - Only Teachers/Admins can view submissions.
 *
 * RELATED FILES:
 *   - Views:  resources/views/remedial/ (index, create, edit, submissions)
 *   - Model:  App\Models\RemedialAction, App\Models\RemedialSubmission
 *   - Routes: routes/web.php → 'remedial.*'
 * ============================================================================
 */
namespace App\Http\Controllers\Remedial;

use App\Http\Controllers\Controller;
use App\Models\RemedialAction;
use App\Models\RemedialSubmission;
use App\Models\Student;
use Illuminate\Http\Request;

class RemedialController extends Controller
{
    private const ACTION_TYPES = [
        'extra_class', 'counseling', 'peer_tutoring', 'assignment',
        'parent_meeting', 'other', 'quiz_test', 'practice_session',
        'written_assignment', 'essay', 'file_upload',
    ];

    private const STATUSES = ['pending', 'in_progress', 'completed', 'cancelled'];

    public function index(Request $request)
    {
        $query = RemedialAction::with(['student', 'assignedByUser', 'submission'])->latest();
        if ($request->filled('status')) { $query->where('status', $request->status); }
        if ($request->filled('student_id')) { $query->where('student_id', $request->student_id); }
        if ($request->filled('action_type')) { $query->where('action_type', $request->action_type); }
        $actions  = $query->paginate(15)->withQueryString();
        $students = Student::active()->orderedByName()->with('user')->get();
        return view('remedial.index', compact('actions', 'students'));
    }

    public function create(Request $request)
    {
        $students = Student::active()->orderedByName()->with('user')->get();
        $selectedStudent = $request->filled('student_id') ? Student::find($request->student_id) : null;
        return view('remedial.create', compact('students', 'selectedStudent'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id', 'action_type' => 'required|in:' . implode(',', self::ACTION_TYPES),
            'title' => 'required|string|max:255', 'description' => 'nullable|string|max:2000',
            'status' => 'required|in:' . implode(',', self::STATUSES), 'scheduled_date' => 'nullable|date',
            'due_date' => 'nullable|date', 'max_score' => 'nullable|integer|min:1|max:1000',
            'min_words' => 'nullable|integer|min:1|max:10000', 'max_words' => 'nullable|integer|min:1|max:50000',
            'outcome' => 'nullable|string|max:2000',
        ]);
        $validated['assigned_by'] = auth()->id();
        $validated['school_id']   = auth()->user()->school_id;
        RemedialAction::create($validated);
        return redirect()->route('remedial.index')->with('success', 'Remedial action assigned successfully!');
    }

    public function edit(RemedialAction $remedial)
    {
        $students = Student::active()->orderedByName()->with('user')->get();
        return view('remedial.edit', compact('remedial', 'students'));
    }

    public function update(Request $request, RemedialAction $remedial)
    {
        $validated = $request->validate([
            'action_type' => 'required|in:' . implode(',', self::ACTION_TYPES),
            'title' => 'required|string|max:255', 'description' => 'nullable|string|max:2000',
            'status' => 'required|in:' . implode(',', self::STATUSES), 'scheduled_date' => 'nullable|date',
            'due_date' => 'nullable|date', 'completed_date' => 'nullable|date',
            'max_score' => 'nullable|integer|min:1|max:1000', 'min_words' => 'nullable|integer|min:1|max:10000',
            'max_words' => 'nullable|integer|min:1|max:50000', 'outcome' => 'nullable|string|max:2000',
        ]);
        $remedial->update($validated);
        return redirect()->route('remedial.index')->with('success', 'Remedial action updated!');
    }

    public function destroy(RemedialAction $remedial)
    {
        $remedial->delete();
        return back()->with('success', 'Remedial action removed.');
    }

    public function showSubmissions(RemedialAction $remedial)
    {
        $user = auth()->user();
        if (!$user->isTeacher() && !$user->isAdmin()) abort(403);
        if ($remedial->school_id !== $user->school_id) abort(403);
        $submissions = RemedialSubmission::where('remedial_action_id', $remedial->id)
            ->where('school_id', $user->school_id)->with(['student.user', 'reviewer'])->orderByDesc('submitted_at')->get();
        return view('remedial.submissions', compact('remedial', 'submissions'));
    }
}
