<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $quiz->title }} — Quiz</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: #0f172a; color: #f8fafc; min-height: 100vh;
      display: flex; flex-direction: column;
    }

    /* ── Top Bar ── */
    .quiz-topbar {
      background: rgba(15,23,42,0.95); backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(255,255,255,0.08);
      padding: 14px 32px; display: flex; align-items: center;
      justify-content: space-between; flex-wrap: wrap; gap: 12px;
      position: sticky; top: 0; z-index: 100;
    }
    .qt-left { display: flex; align-items: center; gap: 16px; }
    .qt-title { font-family:'Poppins',sans-serif; font-size: 16px; font-weight: 700; }
    .qt-badge { display: inline-flex; padding: 3px 10px; border-radius: 100px; font-size: 11px; font-weight: 700; background: rgba(108,92,231,0.2); color: #a78bfa; }
    .qt-timer {
      display: flex; align-items: center; gap: 8px;
      font-family:'Poppins',sans-serif; font-size: 18px; font-weight: 800;
      color: #10b981; padding: 6px 16px; border-radius: 12px;
      background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2);
    }
    .qt-timer.urgent { color: #ef4444; background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.2); }
    .qt-progress { font-size: 13px; font-weight: 600; color: #94a3b8; }

    /* ── Main Content ── */
    .quiz-content {
      flex: 1; display: flex; justify-content: center; align-items: flex-start;
      padding: 40px 24px;
    }
    .quiz-center { width: 100%; max-width: 720px; }

    /* ── Question Card ── */
    .question-card {
      background: rgba(255,255,255,0.06); backdrop-filter: blur(10px);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 24px; padding: 40px; margin-bottom: 24px;
      transition: all 0.4s ease;
    }
    .qc-number {
      display: inline-flex; padding: 4px 12px; border-radius: 8px;
      background: linear-gradient(135deg, #6C5CE7, #8B5CF6);
      color: #fff; font-size: 12px; font-weight: 800; margin-bottom: 16px;
    }
    .qc-text { font-size: 20px; font-weight: 700; line-height: 1.5; margin-bottom: 28px; color: #f1f5f9; }

    /* ── Options ── */
    .options-list { display: grid; gap: 12px; }
    .option-card {
      display: flex; align-items: center; gap: 14px;
      padding: 16px 20px; border-radius: 14px;
      background: rgba(255,255,255,0.04);
      border: 2px solid rgba(255,255,255,0.08);
      cursor: pointer; transition: all 0.22s cubic-bezier(.4,0,.2,1);
    }
    .option-card:hover { background: rgba(108,92,231,0.08); border-color: rgba(108,92,231,0.3); }
    .option-card.selected { background: rgba(108,92,231,0.15); border-color: #6C5CE7; }
    .opt-letter {
      width: 36px; height: 36px; border-radius: 10px;
      background: rgba(255,255,255,0.08); display: flex; align-items: center;
      justify-content: center; font-weight: 800; font-size: 14px;
      color: #94a3b8; flex-shrink: 0; transition: all 0.2s;
    }
    .option-card.selected .opt-letter { background: #6C5CE7; color: #fff; }
    .opt-text { font-size: 15px; font-weight: 500; color: #e2e8f0; }

    /* ── Navigation ── */
    .quiz-nav {
      display: flex; justify-content: space-between; align-items: center;
      margin-top: 8px; flex-wrap: wrap; gap: 12px;
    }
    .btn-nav {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 12px 24px; border-radius: 12px;
      font-size: 14px; font-weight: 700;
      border: none; cursor: pointer; transition: all 0.22s;
    }
    .btn-prev { background: rgba(255,255,255,0.08); color: #e2e8f0; }
    .btn-prev:hover { background: rgba(255,255,255,0.15); }
    .btn-next { background: linear-gradient(135deg, #6C5CE7, #8B5CF6); color: #fff; box-shadow: 0 4px 14px rgba(108,92,231,0.3); }
    .btn-next:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(108,92,231,0.4); }
    .btn-submit { background: linear-gradient(135deg, #10b981, #059669); color: #fff; box-shadow: 0 4px 14px rgba(16,185,129,0.3); }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(16,185,129,0.4); }

    /* ── Question Navigator ── */
    .q-navigator {
      display: flex; flex-wrap: wrap; gap: 8px;
      justify-content: center; margin-bottom: 32px;
    }
    .q-dot {
      width: 36px; height: 36px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 13px; font-weight: 700;
      background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
      color: #94a3b8; cursor: pointer; transition: all 0.2s;
    }
    .q-dot:hover { background: rgba(108,92,231,0.15); }
    .q-dot.active { background: #6C5CE7; color: #fff; border-color: #6C5CE7; }
    .q-dot.answered { background: rgba(16,185,129,0.15); border-color: rgba(16,185,129,0.3); color: #10b981; }

    /* ── Submit Modal ── */
    .submit-overlay {
      position: fixed; inset: 0; background: rgba(0,0,0,0.7);
      backdrop-filter: blur(6px); display: flex; align-items: center;
      justify-content: center; z-index: 999; opacity: 0; visibility: hidden;
      transition: all 0.3s;
    }
    .submit-overlay.active { opacity: 1; visibility: visible; }
    .submit-modal {
      background: #1e293b; border: 1px solid rgba(255,255,255,0.1);
      border-radius: 24px; padding: 32px; max-width: 420px; width: 100%;
      text-align: center; transform: translateY(20px); transition: all 0.3s;
    }
    .submit-overlay.active .submit-modal { transform: translateY(0); }
    .sm-title { font-family:'Poppins',sans-serif; font-size: 20px; font-weight: 800; margin-bottom: 8px; }
    .sm-desc { font-size: 14px; color: #94a3b8; margin-bottom: 24px; }
    .sm-actions { display: flex; gap: 12px; justify-content: center; }

    @media(max-width:640px) {
      .quiz-topbar { padding: 12px 16px; }
      .question-card { padding: 24px; }
      .qc-text { font-size: 17px; }
    }
  </style>
</head>
<body>

  <div class="quiz-topbar">
    <div class="qt-left">
      <span class="qt-title">{{ $quiz->title }}</span>
      <span class="qt-badge">{{ $quiz->subject->name ?? 'General' }}</span>
    </div>
    <div class="qt-timer" id="timer">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      <span id="timer-display">{{ $quiz->duration_minutes }}:00</span>
    </div>
    <div class="qt-progress">
      <span id="current-q">1</span> / {{ $questions->count() }}
    </div>
  </div>

  <div class="quiz-content">
    <div class="quiz-center">

      <!-- Question Navigator -->
      <div class="q-navigator">
        @foreach($questions as $idx => $q)
        <div class="q-dot {{ $idx === 0 ? 'active' : '' }}" id="dot-{{ $idx }}" onclick="goToQuestion({{ $idx }})">{{ $idx + 1 }}</div>
        @endforeach
      </div>

      <form id="quizForm" method="POST" action="{{ route('quiz.submit', $attempt) }}">
        @csrf
        <input type="hidden" name="duration_taken" id="duration_taken" value="0">

        @foreach($questions as $idx => $q)
        <div class="question-card" id="question-{{ $idx }}" style="{{ $idx > 0 ? 'display:none;' : '' }}">
          <span class="qc-number">Question {{ $idx + 1 }} · {{ $q->marks }} mark{{ $q->marks > 1 ? 's' : '' }}</span>
          <div class="qc-text">{{ $q->question }}</div>
          <div class="options-list">
            @foreach(['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d] as $letter => $text)
            <div class="option-card" onclick="selectOption({{ $q->id }}, '{{ $letter }}', this)" id="opt-{{ $q->id }}-{{ $letter }}">
              <div class="opt-letter">{{ $letter }}</div>
              <div class="opt-text">{{ $text }}</div>
            </div>
            @endforeach
          </div>
          <input type="hidden" name="answers[{{ $q->id }}]" id="answer-{{ $q->id }}" value="">
        </div>
        @endforeach

        <div class="quiz-nav">
          <button type="button" class="btn-nav btn-prev" id="btn-prev" onclick="prevQuestion()" style="visibility:hidden;">← Previous</button>
          <button type="button" class="btn-nav btn-next" id="btn-next" onclick="nextQuestion()">Next →</button>
          <button type="button" class="btn-nav btn-submit" id="btn-finish" onclick="showSubmitModal()" style="display:none;">Submit Quiz ✓</button>
        </div>
      </form>

    </div>
  </div>

  <!-- Submit Confirmation Modal -->
  <div class="submit-overlay" id="submitModal">
    <div class="submit-modal">
      <div style="font-size:48px;margin-bottom:12px;">✅</div>
      <div class="sm-title">Ready to Submit?</div>
      <div class="sm-desc" id="sm-summary">You have answered 0 out of {{ $questions->count() }} questions.</div>
      <div class="sm-actions">
        <button class="btn-nav btn-prev" onclick="closeSubmitModal()">Review Answers</button>
        <button class="btn-nav btn-submit" onclick="document.getElementById('quizForm').submit();">Submit Quiz</button>
      </div>
    </div>
  </div>

  <script>
    const totalQuestions = {{ $questions->count() }};
    let currentIndex = 0;
    let answers = {};
    let totalDuration = {{ $quiz->duration_minutes }} * 60; // in seconds
    let elapsed = 0;

    // Timer
    const timerEl = document.getElementById('timer-display');
    const timerContainer = document.getElementById('timer');

    const timerInterval = setInterval(() => {
      elapsed++;
      const remaining = totalDuration - elapsed;

      if (remaining <= 0) {
        clearInterval(timerInterval);
        document.getElementById('duration_taken').value = elapsed;
        document.getElementById('quizForm').submit();
        return;
      }

      const mins = Math.floor(remaining / 60);
      const secs = remaining % 60;
      timerEl.textContent = `${mins}:${secs.toString().padStart(2, '0')}`;

      if (remaining <= 60) {
        timerContainer.classList.add('urgent');
      }
    }, 1000);

    function selectOption(questionId, letter, el) {
      // Clear previous selection
      document.querySelectorAll(`[id^="opt-${questionId}-"]`).forEach(o => o.classList.remove('selected'));
      el.classList.add('selected');
      document.getElementById('answer-' + questionId).value = letter;
      answers[questionId] = letter;
      updateDots();
    }

    function goToQuestion(idx) {
      document.querySelectorAll('.question-card').forEach(c => c.style.display = 'none');
      document.getElementById('question-' + idx).style.display = '';
      document.querySelectorAll('.q-dot').forEach(d => d.classList.remove('active'));
      document.getElementById('dot-' + idx).classList.add('active');
      currentIndex = idx;
      document.getElementById('current-q').textContent = idx + 1;
      updateNav();
    }

    function nextQuestion() {
      if (currentIndex < totalQuestions - 1) goToQuestion(currentIndex + 1);
    }

    function prevQuestion() {
      if (currentIndex > 0) goToQuestion(currentIndex - 1);
    }

    function updateNav() {
      document.getElementById('btn-prev').style.visibility = currentIndex === 0 ? 'hidden' : 'visible';
      if (currentIndex === totalQuestions - 1) {
        document.getElementById('btn-next').style.display = 'none';
        document.getElementById('btn-finish').style.display = '';
      } else {
        document.getElementById('btn-next').style.display = '';
        document.getElementById('btn-finish').style.display = 'none';
      }
    }

    function updateDots() {
      for (let i = 0; i < totalQuestions; i++) {
        const dot = document.getElementById('dot-' + i);
        const qCard = document.getElementById('question-' + i);
        if (qCard) {
          const hiddenInput = qCard.querySelector('input[name^="answers["]');
          if (hiddenInput && hiddenInput.value) {
            dot.classList.add('answered');
          }
        }
      }
    }

    function showSubmitModal() {
      const answeredCount = Object.keys(answers).length;
      document.getElementById('sm-summary').textContent =
        `You have answered ${answeredCount} out of ${totalQuestions} questions.`;
      document.getElementById('duration_taken').value = elapsed;
      document.getElementById('submitModal').classList.add('active');
    }

    function closeSubmitModal() {
      document.getElementById('submitModal').classList.remove('active');
    }

    // Initialize navigation state on first load
    updateNav();
  </script>

</body>
</html>
