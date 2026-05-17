<x-app-layout>
  <x-slot name="title">Assign Remedial Action</x-slot>

  <style>
    .form-page-header {
      text-align: center;
      margin-bottom: 32px;
    }

    .form-page-header h2 {
      font-family: 'Poppins', sans-serif;
      font-size: 28px;
      font-weight: 700;
      color: #111827;
      letter-spacing: -0.02em;
    }

    .form-page-header p {
      font-size: 15px;
      color: var(--text-muted);
      margin-top: 8px;
    }

    .premium-form-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
      max-width: 760px;
      margin: 0 auto;
      overflow: hidden;
    }

    .pfc-header {
      background: linear-gradient(135deg, #f8faff 0%, #ffffff 100%);
      padding: 24px 32px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .pfc-body {
      padding: 32px;
    }

    .premium-form-group {
      margin-bottom: 24px;
    }

    .premium-label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: #374151;
      margin-bottom: 8px;
    }

    .premium-input {
      width: 100%;
      padding: 12px 16px;
      border-radius: 10px;
      border: 1px solid #d1d5db;
      background: #fdfdfd;
      font-size: 14px;
      font-family: 'Inter', sans-serif;
      color: #111827;
      transition: all 0.2s ease;
      box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02);
    }

    .premium-input:focus {
      outline: none;
      border-color: var(--primary);
      background: #fff;
      box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1);
    }

    textarea.premium-input {
      resize: vertical;
      min-height: 100px;
    }

    .premium-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
    }

    .pfc-footer {
      padding: 24px 32px;
      background: #f9fafb;
      border-top: 1px solid var(--border);
      display: flex;
      justify-content: flex-end;
      gap: 16px;
    }

    @media (max-width: 640px) {
      .premium-row {
        grid-template-columns: 1fr;
        gap: 0;
      }
    }
  </style>

  <div class="form-page-header">
    <h2>Assign Remedial Action</h2>
    <p>Create an intervention plan to support student learning</p>
  </div>

  <div class="premium-form-card">
    <div class="pfc-header">
      <div style="font-weight:600; color:#111827; font-size:16px; display:flex; align-items:center; gap:8px;">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
          style="color:var(--primary);">
          <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
        </svg>
        Action Details
      </div>
      <a href="{{ route('remedial.index') }}" class="btn btn-outline" style="padding:6px 14px; font-size:13px;"
        id="back-remedial-btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:4px;">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to List
      </a>
    </div>

    <form method="POST" action="{{ route('remedial.store') }}" id="create-remedial-form">
      @csrf

      <div class="pfc-body">
        <div class="premium-form-group">
          <label class="premium-label" for="student_id">Select Student *</label>
          <select name="student_id" id="student_id" class="premium-input" required>
            <option value="">Choose a student from the list…</option>
            @foreach($students as $s)
              <option value="{{ $s->id }}" {{ (old('student_id', $selectedStudent?->id) == $s->id) ? 'selected' : '' }}>
                {{ $s->name }} — {{ $s->roll_no }}
              </option>
            @endforeach
          </select>
          @error('student_id')<div class="form-error" style="color:var(--error); font-size:12px; margin-top:6px;">
          {{ $message }}</div>@enderror
        </div>

        <div class="premium-row">
          <div class="premium-form-group">
            <label class="premium-label" for="action_type">Intervention Type *</label>
            <select name="action_type" id="action_type" class="premium-input" required onchange="toggleInteractiveFields()">
              <optgroup label="Attendance-based">
                <option value="extra_class"    {{ old('action_type') == 'extra_class'    ? 'selected' : '' }}>📚 Extra Class</option>
                <option value="counseling"     {{ old('action_type') == 'counseling'     ? 'selected' : '' }}>🤝 Counseling</option>
                <option value="peer_tutoring"  {{ old('action_type') == 'peer_tutoring'  ? 'selected' : '' }}>👥 Peer Tutoring</option>
                <option value="parent_meeting" {{ old('action_type') == 'parent_meeting' ? 'selected' : '' }}>👪 Parent Meeting</option>
              </optgroup>
              <optgroup label="In-App Interactive">
                <option value="assignment"         {{ old('action_type') == 'assignment'         ? 'selected' : '' }}>📝 Assignment</option>
                <option value="written_assignment"  {{ old('action_type') == 'written_assignment'  ? 'selected' : '' }}>✍️ Written Assignment</option>
                <option value="essay"               {{ old('action_type') == 'essay'               ? 'selected' : '' }}>📄 Essay</option>
                <option value="file_upload"         {{ old('action_type') == 'file_upload'         ? 'selected' : '' }}>📎 File Upload</option>
                <option value="quiz_test"           {{ old('action_type') == 'quiz_test'           ? 'selected' : '' }}>🧩 Quiz / Test</option>
                <option value="practice_session"    {{ old('action_type') == 'practice_session'    ? 'selected' : '' }}>🔁 Practice Session</option>
              </optgroup>
              <option value="other" {{ old('action_type') == 'other' ? 'selected' : '' }}>📌 Other</option>
            </select>
            @error('action_type')<div class="form-error" style="color:var(--error); font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
          </div>

          <div class="premium-form-group">
            <label class="premium-label" for="status">Initial Status *</label>
            <select name="status" id="status" class="premium-input" required>
              @foreach(['pending' => 'Pending', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $val => $label)
                <option value="{{ $val }}" {{ old('status', 'pending') == $val ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="premium-form-group">
          <label class="premium-label" for="title">Action Title *</label>
          <input type="text" id="title" name="title" value="{{ old('title') }}" class="premium-input"
            placeholder="e.g. Weekly Math Extra Class" required />
          @error('title')<div class="form-error" style="color:var(--error); font-size:12px; margin-top:6px;">
          {{ $message }}</div>@enderror
        </div>

        <div class="premium-form-group">
          <label class="premium-label" for="description">Detailed Description</label>
          <textarea id="description" name="description" class="premium-input"
            placeholder="Provide context or specific areas of focus for this remedial plan…">{{ old('description') }}</textarea>
        </div>

        <div class="premium-row">
          <div class="premium-form-group" style="margin-bottom:0;">
            <label class="premium-label" for="scheduled_date">Scheduled Date</label>
            <input type="date" id="scheduled_date" name="scheduled_date" value="{{ old('scheduled_date') }}" class="premium-input" />
          </div>
          <div class="premium-form-group" style="margin-bottom:0;">
            <label class="premium-label" for="due_date">Due Date (Deadline)</label>
            <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}" class="premium-input" />
          </div>
        </div>

        {{-- Interactive-only fields --}}
        <div id="interactiveFields" style="display:none; margin-top:24px; padding-top:20px; border-top:1px solid #f1f5f9;">
          <div style="font-size:12px; font-weight:700; color:#6C5CE7; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:16px;">📋 Submission Settings</div>
          <div class="premium-row" style="margin-bottom:16px;">
            <div class="premium-form-group" style="margin-bottom:0;">
              <label class="premium-label" for="max_score">Max Score (optional)</label>
              <input type="number" id="max_score" name="max_score" value="{{ old('max_score') }}" class="premium-input" min="1" max="1000" placeholder="e.g. 100" />
            </div>
            <div class="premium-form-group" style="margin-bottom:0;" id="wordLimitFields">
              <label class="premium-label">Word Limits (optional)</label>
              <div style="display:flex; gap:8px;">
                <input type="number" name="min_words" value="{{ old('min_words') }}" class="premium-input" placeholder="Min words" min="1" />
                <input type="number" name="max_words" value="{{ old('max_words') }}" class="premium-input" placeholder="Max words" min="1" />
              </div>
            </div>
          </div>
        </div>

        <div class="premium-form-group">
          <label class="premium-label" for="outcome">Expected Outcome</label>
          <input type="text" id="outcome" name="outcome" value="{{ old('outcome') }}" class="premium-input" placeholder="e.g. Improve to 60%+ in next test" />
        </div>
      </div>

      <div class="pfc-footer">
        <a href="{{ route('remedial.index') }}" class="btn btn-outline">Cancel</a>
        <button type="submit" class="btn btn-primary" id="submit-remedial-btn">Assign Action</button>
      </div>
    </form>
  </div>

  @push('scripts')
  <script>
    const INTERACTIVE_TYPES = ['assignment','written_assignment','essay','file_upload','quiz_test','practice_session'];
    const WRITTEN_TYPES = ['assignment','written_assignment','essay'];

    function toggleInteractiveFields() {
      const type = document.getElementById('action_type').value;
      const ifEl = document.getElementById('interactiveFields');
      const wlEl = document.getElementById('wordLimitFields');
      if (ifEl) ifEl.style.display = INTERACTIVE_TYPES.includes(type) ? 'block' : 'none';
      if (wlEl) wlEl.style.display = WRITTEN_TYPES.includes(type) ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', toggleInteractiveFields);
  </script>
  @endpush

</x-app-layout>