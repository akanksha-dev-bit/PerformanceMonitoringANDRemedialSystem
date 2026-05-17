<?php

namespace App\Http\Controllers\Remedial;

use App\Http\Controllers\Controller;
use App\Models\RemedialAction;
use App\Models\RemedialSubmission;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RemedialSubmissionController extends Controller
{
    private function authorizeSchool(RemedialSubmission $submission): void
    {
        if ($submission->school_id !== auth()->user()->school_id) {
            abort(403, 'Cross-school access denied.');
        }
    }

    private function authorizeActionSchool(RemedialAction $action): void
    {
        if ($action->school_id !== auth()->user()->school_id) {
            abort(403, 'Cross-school access denied.');
        }
    }

    public function show(RemedialAction $remedial)
    {
        $user = auth()->user();
        if (!$user->isStudent()) abort(403);
        $this->authorizeActionSchool($remedial);
        $student = Student::where('user_id', $user->id)->firstOrFail();
        if ($remedial->student_id !== $student->id) abort(403);
        $submission = $remedial->submission()->firstOrCreate(
            ['student_id' => $student->id],
            ['school_id' => $user->school_id, 'submission_status' => 'draft']
        );
        return view('remedial.submission', compact('remedial', 'submission', 'student'));
    }

    public function saveDraft(Request $request, RemedialAction $remedial)
    {
        $user = auth()->user();
        if (!$user->isStudent()) abort(403);
        $this->authorizeActionSchool($remedial);
        $student = Student::where('user_id', $user->id)->firstOrFail();
        if ($remedial->student_id !== $student->id) abort(403);
        $submission = RemedialSubmission::where('remedial_action_id', $remedial->id)
            ->where('student_id', $student->id)->where('school_id', $user->school_id)->firstOrFail();
        if (!$submission->can_edit) {
            return response()->json(['ok' => false, 'message' => 'Submission is already reviewed.'], 422);
        }
        $content = $request->input('content', '');
        $wordCount = str_word_count(strip_tags($content));
        $submission->update(['content' => $content, 'word_count' => $wordCount]);
        return response()->json(['ok' => true, 'word_count' => $wordCount, 'saved_at' => now()->format('H:i:s')]);
    }

    public function submit(Request $request, RemedialAction $remedial)
    {
        $user = auth()->user();
        if (!$user->isStudent()) abort(403);
        $this->authorizeActionSchool($remedial);
        $student = Student::where('user_id', $user->id)->firstOrFail();
        if ($remedial->student_id !== $student->id) abort(403);
        $request->validate(['content' => 'nullable|string']);
        $submission = RemedialSubmission::where('remedial_action_id', $remedial->id)
            ->where('student_id', $student->id)->where('school_id', $user->school_id)->firstOrFail();
        if (!$submission->can_edit) { return back()->with('error', 'Your submission has already been reviewed.'); }
        $content = $request->input('content', $submission->content ?? '');
        $wordCount = str_word_count(strip_tags($content));
        if ($remedial->min_words && $wordCount < $remedial->min_words) {
            return back()->with('error', "Minimum {$remedial->min_words} words required. You wrote {$wordCount}.");
        }
        if ($remedial->max_words && $wordCount > $remedial->max_words) {
            return back()->with('error', "Maximum {$remedial->max_words} words allowed. You wrote {$wordCount}.");
        }
        $submission->update(['content' => $content, 'word_count' => $wordCount, 'submitted_at' => now(), 'submission_status' => 'submitted']);
        $remedial->update(['status' => 'in_progress']);
        $student->addXP(30);
        return redirect()->route('student.tasks')->with('success', 'Assignment submitted successfully! +30 XP earned 🎉');
    }

    public function uploadFile(Request $request, RemedialAction $remedial)
    {
        $user = auth()->user();
        if (!$user->isStudent()) abort(403);
        $this->authorizeActionSchool($remedial);
        $student = Student::where('user_id', $user->id)->firstOrFail();
        if ($remedial->student_id !== $student->id) abort(403);
        $request->validate(['file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip|max:10240']);
        $submission = RemedialSubmission::where('remedial_action_id', $remedial->id)
            ->where('student_id', $student->id)->where('school_id', $user->school_id)->firstOrFail();
        if (!$submission->can_edit) { return back()->with('error', 'Your submission has already been reviewed.'); }
        if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
            Storage::disk('public')->delete($submission->file_path);
        }
        $path = "schools/{$user->school_id}/submissions";
        $file = $request->file('file');
        $storedPath = $file->store($path, 'public');
        $submission->update([
            'file_path' => $storedPath, 'file_original_name' => $file->getClientOriginalName(),
            'file_mime_type' => $file->getMimeType(), 'submitted_at' => now(), 'submission_status' => 'submitted',
        ]);
        $remedial->update(['status' => 'in_progress']);
        $student->addXP(30);
        return back()->with('success', 'File uploaded and submitted! +30 XP earned 🎉');
    }

    public function teacherShow(RemedialSubmission $submission)
    {
        $user = auth()->user();
        if (!$user->isTeacher() && !$user->isAdmin()) abort(403);
        $this->authorizeSchool($submission);
        $submission->load(['remedialAction.student.user', 'student.user', 'reviewer']);
        return view('remedial.teacher-review', compact('submission'));
    }

    public function grade(Request $request, RemedialSubmission $submission)
    {
        $user = auth()->user();
        if (!$user->isTeacher() && !$user->isAdmin()) abort(403);
        $this->authorizeSchool($submission);
        $maxScore = $submission->remedialAction->max_score ?? 100;
        $validated = $request->validate([
            'teacher_feedback' => 'nullable|string|max:2000',
            'teacher_score' => "nullable|integer|min:0|max:{$maxScore}",
        ]);
        $submission->update([
            'teacher_feedback' => $validated['teacher_feedback'], 'teacher_score' => $validated['teacher_score'],
            'reviewed_by' => $user->id, 'reviewed_at' => now(), 'submission_status' => 'reviewed',
        ]);
        $submission->remedialAction->update(['status' => 'completed']);
        $submission->student->addXP(20);
        return redirect()->route('remedial.submissions', $submission->remedial_action_id)
            ->with('success', 'Submission reviewed and marked as completed.');
    }

    public function reopen(Request $request, RemedialSubmission $submission)
    {
        $user = auth()->user();
        if (!$user->isTeacher() && !$user->isAdmin()) abort(403);
        $this->authorizeSchool($submission);
        $request->validate(['teacher_feedback' => 'required|string|max:1000']);
        $submission->update([
            'submission_status' => 'needs_improvement', 'teacher_feedback' => $request->teacher_feedback,
            'reviewed_by' => $user->id, 'reviewed_at' => now(),
        ]);
        $submission->remedialAction->update(['status' => 'pending']);
        return redirect()->route('remedial.submissions', $submission->remedial_action_id)
            ->with('success', 'Resubmission requested. Student has been notified via task status.');
    }
}
