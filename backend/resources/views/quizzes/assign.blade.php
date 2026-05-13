<x-app-layout>
  <x-slot name="title">Assign Quiz: {{ $quiz->title }}</x-slot>

  @push('styles')
  <style>
    .form-page-header { text-align: center; margin-bottom: 32px; }
    .form-page-header h2 { font-family:'Poppins',sans-serif; font-size: 28px; font-weight: 700; color: #111827; letter-spacing: -0.02em; }
    .form-page-header p { font-size: 15px; color: #64748b; margin-top: 8px; }
    .premium-form-card {
      background: #fff; border: 1px solid rgba(0,0,0,0.06);
      border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.03);
      max-width: 680px; margin: 0 auto; overflow: hidden;
    }
    .pfc-header {
      background: linear-gradient(135deg, #f8faff, #fff);
      padding: 24px 32px; border-bottom: 1px solid rgba(0,0,0,0.06);
      display: flex; align-items: center; justify-content: space-between;
    }
    .pfc-body { padding: 32px; }
    .pfc-footer {
      padding: 24px 32px; background: #f9fafb; border-top: 1px solid rgba(0,0,0,0.06);
      display: flex; justify-content: flex-end; gap: 16px;
    }
    .premium-form-group { margin-bottom: 24px; }
    .premium-label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 8px; }
    .premium-input {
      width: 100%; padding: 12px 16px; border-radius: 10px;
      border: 1.5px solid #e2e8f0; background: #fdfdfd;
      font-size: 14px; font-family:'Inter',sans-serif; color: #111827;
      transition: all 0.2s; box-sizing: border-box;
    }
    .premium-input:focus { outline: none; border-color: #6C5CE7; background: #fff; box-shadow: 0 0 0 4px rgba(108,92,231,0.1); }
    .premium-row { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    @media(max-width:640px) { .premium-row { grid-template-columns: 1fr; gap: 0; } }

    .btn-primary-form {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 12px 24px; background: linear-gradient(135deg, #6C5CE7, #8B5CF6);
      color: #fff; border-radius: 12px; font-size: 14px; font-weight: 700;
      border: none; cursor: pointer; box-shadow: 0 4px 14px rgba(108,92,231,0.28);
      transition: all 0.22s;
    }
    .btn-primary-form:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(108,92,231,0.35); }
    .btn-outline-form {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 12px 24px; background: transparent; color: #64748b;
      border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 14px; font-weight: 600;
      text-decoration: none; cursor: pointer; transition: all 0.2s;
    }
    .btn-outline-form:hover { border-color: #94a3b8; color: #334155; }

    .quiz-preview-card {
      background: linear-gradient(135deg, #0f172a, #1e293b);
      border-radius: 16px; padding: 20px 24px; color: #fff; margin-bottom: 24px;
    }
    .qpc-title { font-family:'Poppins',sans-serif; font-size: 18px; font-weight: 700; margin: 0 0 8px; }
    .qpc-meta { display: flex; gap: 16px; font-size: 13px; color: rgba(255,255,255,0.7); }
    .qpc-meta strong { color: #fff; }

    .repeat-preview {
      background: #eef2ff; border: 1px solid #c7d2fe; border-radius: 12px;
      padding: 16px 20px; margin-top: 8px;
      font-size: 13px; color: #4338ca; font-weight: 600;
    }
  </style>
  @endpush

  <div class="form-page-header">
    <h2>Assign Quiz</h2>
    <p>Schedule this quiz for a student to practice over multiple days</p>
  </div>

  <div class="premium-form-card">
    <div class="pfc-header">
      <div style="font-weight:700; color:#111827; font-size:16px; display:flex; align-items:center; gap:8px;">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#6C5CE7;">
          <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
        </svg>
        Assignment Details
      </div>
      <a href="{{ route('quizzes.show', $quiz) }}" class="btn-outline-form" style="padding:6px 14px;font-size:13px;">← Back</a>
    </div>

    <form method="POST" action="{{ route('quizzes.assign.store', $quiz) }}">
      @csrf

      <div class="pfc-body">
        <!-- Quiz Preview -->
        <div class="quiz-preview-card">
          <div class="qpc-title">{{ $quiz->title }}</div>
          <div class="qpc-meta">
            <span>📚 <strong>{{ $quiz->subject->name ?? 'General' }}</strong></span>
            <span>⏱ <strong>{{ $quiz->duration_minutes }} min</strong></span>
            <span>📝 <strong>{{ $quiz->questions->count() }}</strong> questions</span>
          </div>
        </div>

        <div class="premium-form-group">
          <label class="premium-label" for="student_id">Select Student *</label>
          <select name="student_id" id="student_id" class="premium-input" required>
            <option value="">Choose a student…</option>
            @foreach($students as $s)
              <option value="{{ $s->id }}" {{ old('student_id') == $s->id ? 'selected' : '' }}>
                {{ $s->name }} — Roll {{ $s->roll_no }} ({{ $s->class }} {{ $s->section }})
              </option>
            @endforeach
          </select>
          @error('student_id')<div style="color:#ef4444;font-size:12px;margin-top:6px;">{{ $message }}</div>@enderror
        </div>

        <div class="premium-row">
          <div class="premium-form-group">
            <label class="premium-label" for="start_date">Start Date *</label>
            <input type="date" id="start_date" name="start_date" class="premium-input" value="{{ old('start_date', now()->format('Y-m-d')) }}" required>
            @error('start_date')<div style="color:#ef4444;font-size:12px;margin-top:6px;">{{ $message }}</div>@enderror
          </div>
          <div class="premium-form-group">
            <label class="premium-label" for="repeat_days">Repeat Days *</label>
            <input type="number" id="repeat_days" name="repeat_days" class="premium-input" value="{{ old('repeat_days', 4) }}" min="1" max="30" required>
            <div class="repeat-preview" id="repeat-preview">
              The student will receive <strong>4</strong> practice attempts over <strong>4 days</strong>.
            </div>
            @error('repeat_days')<div style="color:#ef4444;font-size:12px;margin-top:6px;">{{ $message }}</div>@enderror
          </div>
        </div>
      </div>

      <div class="pfc-footer">
        <a href="{{ route('quizzes.show', $quiz) }}" class="btn-outline-form">Cancel</a>
        <button type="submit" class="btn-primary-form">Assign Quiz</button>
      </div>
    </form>
  </div>

  @push('scripts')
  <script>
    document.getElementById('repeat_days').addEventListener('input', function() {
      const days = this.value;
      document.getElementById('repeat-preview').innerHTML =
        `The student will receive <strong>${days}</strong> practice attempt${days > 1 ? 's' : ''} over <strong>${days} day${days > 1 ? 's' : ''}</strong>.`;
    });
  </script>
  @endpush

</x-app-layout>
