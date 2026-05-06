<x-app-layout>
  <x-slot name="title">Edit Remedial Action</x-slot>

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

    .premium-input:disabled {
      background: #f3f4f6;
      color: #6b7280;
      cursor: not-allowed;
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
    <h2>Edit Remedial Action</h2>
    <p>Update: {{ $remedial->title }}</p>
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

    <form method="POST" action="{{ route('remedial.update', $remedial) }}" id="edit-remedial-form">
      @csrf @method('PATCH')

      <div class="pfc-body">
        <div class="premium-form-group">
          <label class="premium-label">Student</label>
          <input class="premium-input" value="{{ $remedial->student->name }} — {{ $remedial->student->roll_no }}" disabled />
          <div style="color:var(--text-muted); font-size:12px; margin-top:6px;">Student cannot be changed after creation.</div>
        </div>

        <div class="premium-row">
          <div class="premium-form-group">
            <label class="premium-label" for="action_type">Action Type *</label>
            <select name="action_type" id="action_type" class="premium-input" required>
              @foreach(['extra_class'=>'Extra Class','counseling'=>'Counseling','peer_tutoring'=>'Peer Tutoring','assignment'=>'Assignment','parent_meeting'=>'Parent Meeting','other'=>'Other'] as $val => $label)
                <option value="{{ $val }}" {{ old('action_type',$remedial->action_type) == $val ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <div class="premium-form-group">
            <label class="premium-label" for="status">Status *</label>
            <select name="status" id="status" class="premium-input" required>
              @foreach(['pending'=>'Pending','in_progress'=>'In Progress','completed'=>'Completed','cancelled'=>'Cancelled'] as $val => $label)
                <option value="{{ $val }}" {{ old('status',$remedial->status) == $val ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="premium-form-group">
          <label class="premium-label" for="title">Title *</label>
          <input type="text" id="title" name="title" value="{{ old('title', $remedial->title) }}" class="premium-input" required />
          @error('title')<div class="form-error" style="color:var(--error); font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
        </div>

        <div class="premium-form-group">
          <label class="premium-label" for="description">Description</label>
          <textarea id="description" name="description" class="premium-input">{{ old('description', $remedial->description) }}</textarea>
        </div>

        <div class="premium-row">
          <div class="premium-form-group">
            <label class="premium-label" for="scheduled_date">Scheduled Date</label>
            <input type="date" id="scheduled_date" name="scheduled_date" value="{{ old('scheduled_date', $remedial->scheduled_date?->format('Y-m-d')) }}" class="premium-input" />
          </div>
          <div class="premium-form-group">
            <label class="premium-label" for="completed_date">Completed Date</label>
            <input type="date" id="completed_date" name="completed_date" value="{{ old('completed_date', $remedial->completed_date?->format('Y-m-d')) }}" class="premium-input" />
          </div>
        </div>

        <div class="premium-form-group" style="margin-bottom:0;">
          <label class="premium-label" for="outcome">Outcome</label>
          <textarea id="outcome" name="outcome" class="premium-input" placeholder="Result / outcome of this action…">{{ old('outcome', $remedial->outcome) }}</textarea>
        </div>
      </div>

      <div class="pfc-footer">
        <a href="{{ route('remedial.index') }}" class="btn btn-outline">Cancel</a>
        <button type="submit" class="btn btn-primary" id="update-remedial-btn">Save Changes</button>
      </div>
    </form>
  </div>

</x-app-layout>
