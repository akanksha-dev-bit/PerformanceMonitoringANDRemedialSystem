<x-app-layout>
  <x-slot name="title">Review Submission — {{ $submission->student->name }}</x-slot>

  @push('styles')
  <style>
    .review-header {
      background: linear-gradient(135deg, #ffffff, #f8faff);
      border: 1px solid #e2e8f0; border-radius: 20px;
      padding: 28px 32px; margin-bottom: 28px;
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: 16px;
    }
    .review-grid { display: grid; grid-template-columns: 1fr 380px; gap: 24px; }
    @media (max-width: 1024px) { .review-grid { grid-template-columns: 1fr; } }
    .card { background: #fff; border-radius: 20px; padding: 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.03); margin-bottom: 24px; }
    .card-title { font-size: 15px; font-weight: 800; color: #0f172a; margin: 0 0 20px; display: flex; align-items: center; gap: 8px; }
    .answer-text {
      white-space: pre-wrap; font-size: 15px; line-height: 1.8; color: #1e293b;
      font-family: 'Georgia', serif; padding: 20px; background: #f8fafc;
      border-radius: 12px; border: 1px solid #e2e8f0; min-height: 200px;
    }
    .meta-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
    .meta-row:last-child { border-bottom: none; }
    .meta-label { color: #64748b; font-weight: 600; }
    .meta-val { color: #0f172a; font-weight: 700; }
    .status-pill { display: inline-flex; padding: 4px 12px; border-radius: 100px; font-size: 12px; font-weight: 700; }
    .score-input {
      width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 12px;
      font-size: 18px; font-weight: 800; text-align: center; color: #0f172a;
      transition: border-color 0.2s;
    }
    .score-input:focus { outline: none; border-color: #6C5CE7; box-shadow: 0 0 0 4px rgba(108,92,231,0.1); }
    .feedback-textarea {
      width: 100%; min-height: 120px; padding: 14px 16px; border: 1.5px solid #e2e8f0;
      border-radius: 12px; font-size: 14px; line-height: 1.6; resize: vertical;
      font-family: 'Inter', sans-serif; transition: border-color 0.2s; box-sizing: border-box;
    }
    .feedback-textarea:focus { outline: none; border-color: #6C5CE7; box-shadow: 0 0 0 4px rgba(108,92,231,0.1); }
    .btn-review   { display: flex; align-items: center; gap: 8px; padding: 12px 20px; width: 100%; justify-content: center; border-radius: 12px; font-size: 14px; font-weight: 700; border: none; cursor: pointer; margin-bottom: 10px; transition: all 0.2s; }
    .btn-approve  { background: linear-gradient(135deg, #10b981, #059669); color: #fff; box-shadow: 0 4px 12px rgba(16,185,129,0.3); }
    .btn-approve:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(16,185,129,0.4); }
    .btn-reopen   { background: #fff; color: #f59e0b; border: 1.5px solid #fde68a; }
    .btn-reopen:hover { background: #fffbeb; }
  </style>
  @endpush

  <div class="review-header">
    <div>
      <div style="font-size:12px; font-weight:700; color:#6C5CE7; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:6px;">
        {{ $submission->remedialAction->action_type_label }}
      </div>
      <h2 style="font-size:22px; font-weight:800; color:#0f172a; margin:0 0 4px;">{{ $submission->remedialAction->title }}</h2>
      <p style="font-size:13px; color:#64748b; margin:0;">Reviewing submission by {{ $submission->student->name }}</p>
    </div>
    <a href="{{ route('remedial.submissions', $submission->remedial_action_id) }}"
      style="padding:10px 18px; background:#f1f5f9; border:1px solid #cbd5e1; border-radius:10px; font-size:13px; font-weight:700; color:#475569; text-decoration:none;">
      ← All Submissions
    </a>
  </div>

  <div class="review-grid">

    {{-- Left: Content --}}
    <div>
      {{-- Student Info --}}
      <div class="card">
        <div class="card-title">👤 Student Profile</div>
        <div class="meta-row"><span class="meta-label">Name</span><span class="meta-val">{{ $submission->student->name }}</span></div>
        <div class="meta-row"><span class="meta-label">Roll No</span><span class="meta-val">{{ $submission->student->roll_no }}</span></div>
        <div class="meta-row"><span class="meta-label">Class</span><span class="meta-val">{{ $submission->student->class }}{{ $submission->student->section ? '-'.$submission->student->section : '' }}</span></div>
        <div class="meta-row">
          <span class="meta-label">Submission Status</span>
          <span class="status-pill" style="background:{{ $submission->status_color }}20; color:{{ $submission->status_color }};">
            {{ $submission->status_label }}
          </span>
        </div>
        <div class="meta-row"><span class="meta-label">Submitted At</span><span class="meta-val">{{ $submission->submitted_at?->format('d M Y, H:i') ?? '—' }}</span></div>
        @if($submission->word_count)
          <div class="meta-row"><span class="meta-label">Word Count</span><span class="meta-val">{{ number_format($submission->word_count) }} words</span></div>
        @endif
      </div>

      {{-- Written Content --}}
      @if($submission->content)
        <div class="card">
          <div class="card-title">✍️ Student's Written Answer</div>
          <div class="answer-text">{{ $submission->content }}</div>
        </div>
      @endif

      {{-- File Submission --}}
      @if($submission->file_path)
        <div class="card">
          <div class="card-title">📎 Uploaded File</div>
          <div style="display:flex; align-items:center; gap:16px; padding:16px; background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0;">
            <span style="font-size:32px;">📄</span>
            <div style="flex:1;">
              <div style="font-weight:700; color:#0f172a;">{{ $submission->file_original_name }}</div>
              <div style="font-size:12px; color:#64748b;">{{ strtoupper($submission->file_mime_type ?? '') }}</div>
            </div>
            <a href="{{ Storage::url($submission->file_path) }}" target="_blank" download
              style="padding:8px 16px; background:linear-gradient(135deg,#6C5CE7,#5A4BD6); color:#fff; border-radius:8px; font-size:13px; font-weight:700; text-decoration:none;">
              ⬇ Download
            </a>
          </div>
        </div>
      @endif

      {{-- Previous Feedback (if already reviewed before) --}}
      @if($submission->reviewer)
        <div class="card" style="border-left:4px solid #6C5CE7;">
          <div class="card-title">📝 Previous Review</div>
          <div style="font-size:13px; color:#475569;">Reviewed by <strong>{{ $submission->reviewer->name }}</strong> on {{ $submission->reviewed_at?->format('d M Y') }}</div>
          @if($submission->teacher_feedback)
            <div style="margin-top:12px; padding:14px; background:#f8fafc; border-radius:10px; font-size:14px; color:#334155; line-height:1.7;">
              {{ $submission->teacher_feedback }}
            </div>
          @endif
        </div>
      @endif
    </div>

    {{-- Right: Grading Panel --}}
    <div>
      {{-- Grade Form --}}
      <div class="card" style="position:sticky; top:24px;">
        <div class="card-title">⭐ Grade This Submission</div>

        @if($submission->remedialAction->max_score)
          <div style="margin-bottom:20px;">
            <label style="display:block; font-size:12px; font-weight:700; color:#64748b; margin-bottom:8px; text-transform:uppercase; letter-spacing:0.05em;">
              Score (out of {{ $submission->remedialAction->max_score }})
            </label>
            <form action="{{ route('remedial.grade', $submission) }}" method="POST" id="gradeForm">
              @csrf
              <input type="number" name="teacher_score" class="score-input"
                min="0" max="{{ $submission->remedialAction->max_score }}"
                value="{{ $submission->teacher_score }}"
                placeholder="0 — {{ $submission->remedialAction->max_score }}">
              <label style="display:block; font-size:12px; font-weight:700; color:#64748b; margin:16px 0 8px; text-transform:uppercase; letter-spacing:0.05em;">Feedback</label>
              <textarea name="teacher_feedback" class="feedback-textarea"
                placeholder="Write your feedback, comments, or improvement suggestions…">{{ $submission->teacher_feedback }}</textarea>

              <button type="submit" class="btn-review btn-approve" style="margin-top:16px;">
                ✅ Mark as Reviewed & Complete
              </button>
            </form>
          </div>
        @else
          <form action="{{ route('remedial.grade', $submission) }}" method="POST" id="gradeForm">
            @csrf
            <label style="display:block; font-size:12px; font-weight:700; color:#64748b; margin-bottom:8px; text-transform:uppercase; letter-spacing:0.05em;">Feedback</label>
            <textarea name="teacher_feedback" class="feedback-textarea"
              placeholder="Write your feedback…">{{ $submission->teacher_feedback }}</textarea>
            <button type="submit" class="btn-review btn-approve" style="margin-top:16px;">
              ✅ Mark as Reviewed & Complete
            </button>
          </form>
        @endif

        <hr style="border:none; border-top:1px solid #f1f5f9; margin:16px 0;">

        {{-- Reopen --}}
        <form action="{{ route('remedial.reopen', $submission) }}" method="POST" id="reopenForm">
          @csrf
          <label style="display:block; font-size:12px; font-weight:700; color:#64748b; margin-bottom:8px; text-transform:uppercase; letter-spacing:0.05em;">
            Reason for Resubmission *
          </label>
          <textarea name="teacher_feedback" class="feedback-textarea" style="min-height:80px;" required
            placeholder="Explain what needs to be improved…"></textarea>
          <button type="submit" class="btn-review btn-reopen" style="margin-top:12px;">
            🔄 Request Resubmission
          </button>
        </form>
      </div>
    </div>
  </div>

</x-app-layout>
