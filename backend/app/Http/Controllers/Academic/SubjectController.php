<?php

/**
 * ============================================================================
 * SubjectController — Admin-Only Subject Management
 * ============================================================================
 *
 * PURPOSE:
 *   Allows Admins to manage the list of academic subjects in the school.
 *   Subjects are used throughout the system: marks are recorded per subject,
 *   teachers are assigned to subjects, quizzes belong to subjects, and
 *   performance analysis is broken down by subject.
 *
 * HOW IT WORKS:
 *   - Admin navigates to "Subjects" in the navbar.
 *   - Index shows a paginated list of all subjects with their codes.
 *   - Create/Edit forms allow setting: name, code (unique), class, type
 *     (theory/practical/both), max marks, and active/inactive status.
 *   - Default max_marks is 100 if not specified.
 *
 * ROUTES (Resource — No show):
 *   GET    /subjects              → index()   — List all subjects
 *   GET    /subjects/create       → create()  — Add subject form
 *   POST   /subjects              → store()   — Create subject
 *   GET    /subjects/{subject}/edit → edit()  — Edit form
 *   PUT    /subjects/{subject}    → update()  — Save changes
 *   DELETE /subjects/{subject}    → destroy() — Remove subject
 *
 * SECURITY:
 *   - Protected by RoleMiddleware('admin') — only admins can access.
 *
 * RELATED FILES:
 *   - Views:  resources/views/subjects/ (index, create, edit)
 *   - Model:  App\Models\Subject
 *   - Routes: routes/web.php → 'subjects.*' (inside admin middleware group)
 * ============================================================================
 */
namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('name')->paginate(15);
        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:10|unique:subjects,code',
            'class' => 'required|string|max:50',
            'type' => 'nullable|in:theory,practical,both',
            'max_marks' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        if (empty($validated['max_marks'])) {
            $validated['max_marks'] = 100;
        }

        Subject::create($validated);

        return redirect()->route('subjects.index')->with('success', 'Subject created successfully.');
    }

    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:10|unique:subjects,code,' . $subject->id,
            'class' => 'required|string|max:50',
            'type' => 'nullable|in:theory,practical,both',
            'max_marks' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        if (empty($validated['max_marks'])) {
            $validated['max_marks'] = 100;
        }

        $subject->update($validated);

        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully.');
    }
}
