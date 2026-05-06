<x-app-layout>
  <x-slot name="title">Add Marks</x-slot>

  <style>
    .form-page-header {
      text-align: center;
      margin-bottom: 32px;
    }
    .form-page-header h2 { font-family: 'Poppins', sans-serif; font-size: 28px; font-weight: 700; color: #111827; letter-spacing: -0.02em; }
    .form-page-header p { font-size: 15px; color: var(--text-muted); margin-top: 8px; }

    .premium-form-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      box-shadow: 0 10px 40px rgba(0,0,0,0.03);
      max-width: 760px;
      margin: 0 auto;
      overflow: hidden;
    }
    
    .pfc-header {
      background: linear-gradient(135deg, #f8faff 0%, #ffffff 100%);
      padding: 24px 32px;
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
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
      box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);
    }
    .premium-input:focus {
      outline: none;
      border-color: var(--primary);
      background: #fff;
      box-shadow: 0 0 0 4px rgba(108,92,231,0.1);
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
      .premium-row { grid-template-columns: 1fr; gap: 0; }
    }
  </style>

  <div class="form-page-header">
    <h2>Record Marks</h2>
    <p>Enter examination marks for a student</p>
  </div>

  <div class="premium-form-card">
    <div class="pfc-header">
      <div style="font-weight:600; color:#111827; font-size:16px; display:flex; align-items:center; gap:8px;">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--primary);"><path d="M12 20h9M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
        Academic Record
      </div>
      <a href="{{ route('marks.index') }}" class="btn btn-outline" style="padding:6px 14px; font-size:13px;" id="back-marks-btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Marks
      </a>
    </div>

    <form method="POST" action="{{ route('marks.store') }}" id="create-marks-form">
      @csrf

      <div class="pfc-body">
        <div class="premium-row">
          <div class="premium-form-group">
            <label class="premium-label" for="student_id">Select Student *</label>
            <select name="student_id" id="student_id" class="premium-input" required onchange="filterSubjects()">
              <option value="">Choose a student…</option>
              @foreach($students as $s)
                <option value="{{ $s->id }}" data-class="{{ $s->class }}" {{ old('student_id') == $s->id ? 'selected' : '' }}>{{ $s->name }} — {{ $s->roll_no }} (Class {{ $s->class }})</option>
              @endforeach
            </select>
            @error('student_id')<div class="form-error" style="color:var(--error); font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
          </div>

          <div class="premium-form-group">
            <label class="premium-label" for="subject_id">Select Subject *</label>
            <select name="subject_id" id="subject_id" class="premium-input" required>
              <option value="">Choose a subject…</option>
              @foreach($subjects as $sub)
                <option value="{{ $sub->id }}" data-class="{{ $sub->class }}" {{ old('subject_id') == $sub->id ? 'selected' : '' }}>{{ $sub->name }} ({{ $sub->code }})</option>
              @endforeach
            </select>
            @error('subject_id')<div class="form-error" style="color:var(--error); font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="premium-row">
          <div class="premium-form-group">
            <label class="premium-label" for="marks_obtained">Marks Obtained *</label>
            <input type="number" id="marks_obtained" name="marks_obtained" value="{{ old('marks_obtained') }}" min="0" class="premium-input" placeholder="e.g. 78" required />
            @error('marks_obtained')<div class="form-error" style="color:var(--error); font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
          </div>
          <div class="premium-form-group">
            <label class="premium-label" for="max_marks">Maximum Marks *</label>
            <input type="number" id="max_marks" name="max_marks" value="{{ old('max_marks', 100) }}" min="1" class="premium-input" required />
            @error('max_marks')<div class="form-error" style="color:var(--error); font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="premium-row">
          <div class="premium-form-group">
            <label class="premium-label" for="exam_type">Exam Type *</label>
            <select name="exam_type" id="exam_type" class="premium-input" required>
              @foreach(['unit_test'=>'Unit Test','midterm'=>'Midterm','final'=>'Final','practical'=>'Practical'] as $val => $label)
                <option value="{{ $val }}" {{ old('exam_type') == $val ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
            @error('exam_type')<div class="form-error" style="color:var(--error); font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
          </div>
          <div class="premium-form-group">
            <label class="premium-label" for="academic_year">Academic Year *</label>
            <input type="text" id="academic_year" name="academic_year" value="{{ old('academic_year', '2024-25') }}" class="premium-input" placeholder="2024-25" required />
            @error('academic_year')<div class="form-error" style="color:var(--error); font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="premium-form-group" style="margin-bottom:0;">
          <label class="premium-label" for="remarks">Teacher Remarks</label>
          <textarea id="remarks" name="remarks" class="premium-input" placeholder="Optional notes about the student's performance…">{{ old('remarks') }}</textarea>
        </div>
      </div>

      <div class="pfc-footer">
        <a href="{{ route('marks.index') }}" class="btn btn-outline">Cancel</a>
        <button type="submit" class="btn btn-primary" id="submit-marks-btn">Save Marks</button>
      </div>
    </form>
  </div>

</x-app-layout>

<script>
  function filterSubjects() {
      const studentSelect = document.getElementById('student_id');
      const subjectSelect = document.getElementById('subject_id');
      
      if (!studentSelect || !subjectSelect) return;
      
      const selectedStudent = studentSelect.options[studentSelect.selectedIndex];
      
      if (!selectedStudent || !selectedStudent.value) {
          // If no student selected, hide all subjects
          for (let i = 1; i < subjectSelect.options.length; i++) {
              subjectSelect.options[i].style.display = 'none';
          }
          return;
      }

      const studentClass = selectedStudent.getAttribute('data-class');

      for (let i = 1; i < subjectSelect.options.length; i++) {
          const option = subjectSelect.options[i];
          const subjectClass = option.getAttribute('data-class');
          
          if (subjectClass === String(studentClass) || subjectClass === 'All' || !subjectClass) {
              option.style.display = '';
          } else {
              option.style.display = 'none';
          }
      }

      // Reset subject selection if current selection is hidden
      if (subjectSelect.selectedIndex > 0) {
          const currentOption = subjectSelect.options[subjectSelect.selectedIndex];
          if (currentOption.style.display === 'none') {
              subjectSelect.value = '';
          }
      }
  }

  // Run on load in case a student is already selected
  document.addEventListener('DOMContentLoaded', filterSubjects);
</script>
