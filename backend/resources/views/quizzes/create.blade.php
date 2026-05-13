<x-app-layout>
  <x-slot name="title">Create Quiz</x-slot>

  @push('styles')
  <style>
    .form-page-header { text-align: center; margin-bottom: 32px; }
    .form-page-header h2 { font-family:'Poppins',sans-serif; font-size: 28px; font-weight: 700; color: #111827; letter-spacing: -0.02em; }
    .form-page-header p { font-size: 15px; color: #64748b; margin-top: 8px; }

    .premium-form-card {
      background: #fff; border: 1px solid rgba(0,0,0,0.06);
      border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.03);
      max-width: 860px; margin: 0 auto; overflow: hidden;
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
    textarea.premium-input { resize: vertical; min-height: 80px; }

    .premium-row { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .premium-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px; }
    @media(max-width: 640px) { .premium-row, .premium-row-3 { grid-template-columns: 1fr; gap: 0; } }

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

    /* Question Builder */
    .question-section { margin-top: 32px; border-top: 1px solid rgba(0,0,0,0.06); padding-top: 32px; }
    .section-title { font-family:'Poppins',sans-serif; font-size: 18px; font-weight: 700; color: #0f172a; margin: 0 0 6px; }
    .section-desc { font-size: 13px; color: #64748b; margin: 0 0 24px; }

    .question-block {
      background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px;
      padding: 24px; margin-bottom: 16px; position: relative;
      transition: all 0.2s;
    }
    .question-block:hover { border-color: #c7d2fe; }
    .qb-number {
      display: inline-flex; align-items: center; justify-content: center;
      width: 28px; height: 28px; border-radius: 8px;
      background: linear-gradient(135deg, #6C5CE7, #8B5CF6); color: #fff;
      font-size: 12px; font-weight: 800; margin-bottom: 12px;
    }
    .qb-remove {
      position: absolute; top: 16px; right: 16px;
      width: 28px; height: 28px; border-radius: 8px;
      background: #fef2f2; color: #ef4444; border: none;
      cursor: pointer; display: flex; align-items: center; justify-content: center;
      transition: all 0.2s;
    }
    .qb-remove:hover { background: #ef4444; color: #fff; }

    .options-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 12px; }
    @media(max-width: 640px) { .options-grid { grid-template-columns: 1fr; } }
    .option-label { font-size: 12px; font-weight: 700; color: #6C5CE7; margin-bottom: 4px; }

    .btn-add-question {
      display: flex; align-items: center; justify-content: center; gap: 8px;
      width: 100%; padding: 14px; border-radius: 14px;
      border: 2px dashed #c7d2fe; background: #f8faff; color: #6C5CE7;
      font-weight: 700; font-size: 14px; cursor: pointer;
      transition: all 0.2s;
    }
    .btn-add-question:hover { background: #eef2ff; border-color: #6C5CE7; }
  </style>
  @endpush

  <div class="form-page-header">
    <h2>Create New Quiz</h2>
    <p>Build a quiz with questions to assign to students for remedial practice</p>
  </div>

  <div class="premium-form-card">
    <div class="pfc-header">
      <div style="font-weight:700; color:#111827; font-size:16px; display:flex; align-items:center; gap:8px;">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#6C5CE7;">
          <path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
        </svg>
        Quiz Details
      </div>
      <a href="{{ route('quizzes.index') }}" class="btn-outline-form" style="padding:6px 14px; font-size:13px;">
        ← Back to Quizzes
      </a>
    </div>

    <form method="POST" action="{{ route('quizzes.store') }}" id="quiz-form">
      @csrf

      <div class="pfc-body">
        <div class="premium-form-group">
          <label class="premium-label" for="title">Quiz Title *</label>
          <input type="text" id="title" name="title" class="premium-input" value="{{ old('title') }}" placeholder="e.g. Algebra Basics Quiz" required>
          @error('title')<div style="color:#ef4444;font-size:12px;margin-top:6px;">{{ $message }}</div>@enderror
        </div>

        <div class="premium-form-group">
          <label class="premium-label" for="description">Description</label>
          <textarea id="description" name="description" class="premium-input" placeholder="Brief description of what this quiz covers...">{{ old('description') }}</textarea>
        </div>

        <div class="premium-row-3">
          <div class="premium-form-group">
            <label class="premium-label" for="subject_id">Subject *</label>
            <select name="subject_id" id="subject_id" class="premium-input" required>
              <option value="">Choose subject…</option>
              @foreach($subjects as $s)
                <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
              @endforeach
            </select>
            @error('subject_id')<div style="color:#ef4444;font-size:12px;margin-top:6px;">{{ $message }}</div>@enderror
          </div>
          <div class="premium-form-group">
            <label class="premium-label" for="difficulty_level">Difficulty *</label>
            <select name="difficulty_level" id="difficulty_level" class="premium-input" required>
              <option value="easy" {{ old('difficulty_level') == 'easy' ? 'selected' : '' }}>Easy</option>
              <option value="medium" {{ old('difficulty_level', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
              <option value="hard" {{ old('difficulty_level') == 'hard' ? 'selected' : '' }}>Hard</option>
            </select>
          </div>
          <div class="premium-form-group">
            <label class="premium-label" for="duration_minutes">Duration (min) *</label>
            <input type="number" id="duration_minutes" name="duration_minutes" class="premium-input" value="{{ old('duration_minutes', 15) }}" min="1" max="180" required>
          </div>
        </div>

        <!-- Question Builder Section -->
        <div class="question-section">
          <h3 class="section-title">Questions</h3>
          <p class="section-desc">Add MCQ questions below. Each question must have 4 options and one correct answer.</p>

          <div id="questions-container">
            <!-- Question blocks injected by JS -->
          </div>

          <button type="button" class="btn-add-question" onclick="addQuestion()">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Question
          </button>
        </div>
      </div>

      <div class="pfc-footer">
        <a href="{{ route('quizzes.index') }}" class="btn-outline-form">Cancel</a>
        <button type="submit" class="btn-primary-form">Create Quiz</button>
      </div>
    </form>
  </div>

  @push('scripts')
  <script>
    let questionCount = 0;

    function addQuestion() {
      questionCount++;
      const container = document.getElementById('questions-container');
      const html = `
        <div class="question-block" id="q-block-${questionCount}">
          <div class="qb-number">${questionCount}</div>
          <button type="button" class="qb-remove" onclick="removeQuestion(${questionCount})" title="Remove Question">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
          </button>

          <div class="premium-form-group" style="margin-bottom:12px;">
            <label class="premium-label">Question Text *</label>
            <textarea name="questions[${questionCount}][question]" class="premium-input" placeholder="Enter the question..." required style="min-height:60px;"></textarea>
          </div>

          <div class="options-grid">
            <div>
              <div class="option-label">Option A *</div>
              <input type="text" name="questions[${questionCount}][option_a]" class="premium-input" placeholder="Option A" required>
            </div>
            <div>
              <div class="option-label">Option B *</div>
              <input type="text" name="questions[${questionCount}][option_b]" class="premium-input" placeholder="Option B" required>
            </div>
            <div>
              <div class="option-label">Option C *</div>
              <input type="text" name="questions[${questionCount}][option_c]" class="premium-input" placeholder="Option C" required>
            </div>
            <div>
              <div class="option-label">Option D *</div>
              <input type="text" name="questions[${questionCount}][option_d]" class="premium-input" placeholder="Option D" required>
            </div>
          </div>

          <div class="premium-row" style="margin-top:12px;">
            <div>
              <label class="premium-label">Correct Answer *</label>
              <select name="questions[${questionCount}][correct_answer]" class="premium-input" required>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
              </select>
            </div>
            <div>
              <label class="premium-label">Marks *</label>
              <input type="number" name="questions[${questionCount}][marks]" class="premium-input" value="1" min="1" required>
            </div>
          </div>

          <div class="premium-form-group" style="margin-top:12px;margin-bottom:0;">
            <label class="premium-label">Explanation (shown after quiz)</label>
            <textarea name="questions[${questionCount}][explanation]" class="premium-input" placeholder="Explain why this is the correct answer..." style="min-height:50px;"></textarea>
          </div>
        </div>
      `;
      container.insertAdjacentHTML('beforeend', html);
    }

    function removeQuestion(id) {
      const block = document.getElementById('q-block-' + id);
      if (block) {
        block.style.opacity = '0';
        block.style.transform = 'translateY(-10px)';
        block.style.transition = 'all 0.3s ease';
        setTimeout(() => block.remove(), 300);
      }
    }

    // Start with one question
    addQuestion();
  </script>
  @endpush

</x-app-layout>
