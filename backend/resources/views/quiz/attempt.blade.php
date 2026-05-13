<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $quiz->title }} — Interactive Assessment</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: linear-gradient(135deg, #090d16 0%, #111827 50%, #1a153b 100%);
      color: #f8fafc; min-height: 100vh; display: flex; flex-direction: column;
      overflow-x: hidden;
    }

    /* ── Ambient Background Glows ── */
    .bg-glow { position: fixed; filter: blur(140px); opacity: 0.25; pointer-events: none; z-index: 0; }
    .bg-glow-1 { top: -100px; left: -100px; width: 500px; height: 500px; background: #6C5CE7; }
    .bg-glow-2 { bottom: -100px; right: -100px; width: 600px; height: 600px; background: #ec4899; }

    /* ── Top Navigation Bar ── */
    .quiz-topbar {
      background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      padding: 16px 40px; display: flex; align-items: center;
      justify-content: space-between; flex-wrap: wrap; gap: 16px;
      position: sticky; top: 0; z-index: 100;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .qt-left { display: flex; align-items: center; gap: 16px; }
    .qt-title { font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 700; background: linear-gradient(90deg, #fff, #cbd5e1); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .qt-badge { display: inline-flex; padding: 4px 12px; border-radius: 100px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; background: rgba(108,92,231,0.25); color: #c084fc; border: 1px solid rgba(167,139,250,0.3); }
    .qt-center { display: flex; align-items: center; gap: 12px; }
    .qt-timer {
      display: flex; align-items: center; gap: 8px;
      font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 700;
      color: #10b981; padding: 6px 18px; border-radius: 14px;
      background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.25);
      box-shadow: 0 0 20px rgba(16,185,129,0.1);
      transition: all 0.3s ease;
    }
    .qt-timer.urgent { color: #ef4444; background: rgba(239,68,68,0.15); border-color: rgba(239,68,68,0.3); box-shadow: 0 0 20px rgba(239,68,68,0.2); animation: pulse 2s infinite; }
    .qt-progress { font-size: 14px; font-weight: 600; color: #94a3b8; display: flex; align-items: center; gap: 6px; }
    .qt-progress span { color: #fff; font-weight: 700; }

    /* ── Main Content Area ── */
    .quiz-content {
      flex: 1; display: flex; justify-content: center; align-items: flex-start;
      padding: 48px 24px; position: relative; z-index: 10;
    }
    .quiz-center { width: 100%; max-width: 760px; }

    /* ── Question Navigator ── */
    .q-navigator {
      display: flex; flex-wrap: wrap; gap: 10px;
      justify-content: center; margin-bottom: 36px;
      background: rgba(255, 255, 255, 0.03); padding: 16px;
      border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.06);
      backdrop-filter: blur(12px);
    }
    .q-dot {
      width: 40px; height: 40px; border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 14px; font-weight: 700;
      background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);
      color: #94a3b8; cursor: pointer; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .q-dot:hover { background: rgba(108, 92, 231, 0.2); color: #fff; transform: translateY(-2px); }
    .q-dot.active { background: linear-gradient(135deg, #6C5CE7, #8B5CF6); color: #fff; border-color: #a78bfa; box-shadow: 0 4px 15px rgba(108,92,231,0.4); transform: scale(1.05); }
    .q-dot.answered { background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.4); color: #10b981; }
    .q-dot.answered.active { background: linear-gradient(135deg, #10b981, #059669); color: #fff; border-color: #34d399; box-shadow: 0 4px 15px rgba(16,185,129,0.4); }

    /* ── Question Card ── */
    .question-card {
      background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 28px; padding: 48px; margin-bottom: 28px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.05) inset;
      animation: fadeIn 0.4s ease forwards;
    }
    .qc-number {
      display: inline-flex; padding: 6px 16px; border-radius: 10px;
      background: linear-gradient(135deg, rgba(108,92,231,0.3), rgba(139,92,246,0.3));
      border: 1px solid rgba(167,139,250,0.3);
      color: #e2e8f0; font-size: 13px; font-weight: 700; margin-bottom: 20px;
      letter-spacing: 0.05em; text-transform: uppercase;
    }
    .qc-text { font-size: 22px; font-weight: 700; line-height: 1.6; margin-bottom: 36px; color: #f8fafc; }

    /* ── Options ── */
    .options-list { display: grid; gap: 16px; }
    .option-card {
      display: flex; align-items: center; gap: 18px;
      padding: 20px 24px; border-radius: 18px;
      background: rgba(255, 255, 255, 0.03);
      border: 2px solid rgba(255, 255, 255, 0.08);
      cursor: pointer; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .option-card:hover { background: rgba(108, 92, 231, 0.1); border-color: rgba(167, 139, 250, 0.4); transform: translateX(4px); }
    .option-card.selected { background: linear-gradient(135deg, rgba(108,92,231,0.2), rgba(139,92,246,0.2)); border-color: #8B5CF6; box-shadow: 0 8px 25px rgba(108,92,231,0.25); transform: translateX(8px); }
    .opt-letter {
      width: 40px; height: 40px; border-radius: 12px;
      background: rgba(255, 255, 255, 0.08); display: flex; align-items: center;
      justify-content: center; font-weight: 700; font-size: 15px;
      color: #94a3b8; flex-shrink: 0; transition: all 0.25s;
    }
    .option-card:hover .opt-letter { background: rgba(167,139,250,0.2); color: #c084fc; }
    .option-card.selected .opt-letter { background: linear-gradient(135deg, #6C5CE7, #8B5CF6); color: #fff; box-shadow: 0 4px 12px rgba(108,92,231,0.4); }
    .opt-text { font-size: 16px; font-weight: 500; color: #e2e8f0; line-height: 1.5; }

    /* ── Navigation Buttons ── */
    .quiz-nav {
      display: flex; justify-content: space-between; align-items: center;
      margin-top: 12px; flex-wrap: wrap; gap: 16px;
    }
    .btn-nav {
      display: inline-flex; align-items: center; gap: 10px;
      padding: 14px 28px; border-radius: 16px;
      font-size: 15px; font-weight: 700; font-family: 'Poppins', sans-serif;
      border: none; cursor: pointer; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-prev { background: rgba(255, 255, 255, 0.08); color: #e2e8f0; border: 1px solid rgba(255,255,255,0.1); }
    .btn-prev:hover { background: rgba(255, 255, 255, 0.15); transform: translateY(-2px); }
    .btn-next { background: linear-gradient(135deg, #6C5CE7, #8B5CF6); color: #fff; box-shadow: 0 8px 25px rgba(108,92,231,0.35); }
    .btn-next:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(108,92,231,0.5); }
    .btn-submit { background: linear-gradient(135deg, #10b981, #059669); color: #fff; box-shadow: 0 8px 25px rgba(16,185,129,0.35); }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(16,185,129,0.5); }

    /* ── Submit Modal ── */
    .submit-overlay {
      position: fixed; inset: 0; background: rgba(15, 23, 42, 0.85);
      backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
      display: flex; align-items: center; justify-content: center; z-index: 999;
      opacity: 0; visibility: hidden; transition: all 0.3s ease;
    }
    .submit-overlay.active { opacity: 1; visibility: visible; }
    .submit-modal {
      background: linear-gradient(135deg, #1e293b, #0f172a);
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 32px; padding: 48px 40px; max-width: 460px; width: 100%;
      text-align: center; transform: scale(0.95); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 25px 50px rgba(0,0,0,0.5);
    }
    .submit-overlay.active .submit-modal { transform: scale(1); }
    .sm-icon { font-size: 56px; margin-bottom: 20px; animation: bounce 2s infinite ease-in-out; }
    .sm-title { font-family: 'Poppins', sans-serif; font-size: 24px; font-weight: 800; margin-bottom: 12px; color: #fff; }
    .sm-desc { font-size: 15px; color: #94a3b8; margin-bottom: 32px; line-height: 1.6; }
    .sm-actions { display: flex; gap: 16px; justify-content: center; }

    /* ── Animations ── */
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.6; } }
    @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }

    @media(max-width: 640px) {
      .quiz-topbar { padding: 14px 20px; }
      .quiz-content { padding: 32px 16px; }
      .question-card { padding: 32px 24px; }
      .qc-text { font-size: 19px; }
      .option-card { padding: 16px 20px; }
    }
  </style>
</head>
<body>

  <!-- Ambient Glows -->
  <div class="bg-glow bg-glow-1"></div>
  <div class="bg-glow bg-glow-2"></div>

  <!-- Top Navigation Bar -->
  <div class="quiz-topbar">
    <div class="qt-left">
      <span class="qt-title">{{ $quiz->title }}</span>
      <span class="qt-badge">{{ $quiz->subject->name ?? 'General' }}</span>
    </div>
    <div class="qt-center">
      <div class="qt-timer" id="timer">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <span id="timer-display">{{ $quiz->duration_minutes }}:00</span>
      </div>
    </div>
    <div class="qt-progress">
      Question <span id="current-q">1</span> of {{ $questions->count() }}
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
          <button type="button" class="btn-nav btn-next" id="btn-next" onclick="nextQuestion()">Next Question →</button>
          <button type="button" class="btn-nav btn-submit" id="btn-finish" onclick="showSubmitModal()" style="display:none;">Submit Quiz ✓</button>
        </div>
      </form>

    </div>
  </div>

  <!-- Submit Confirmation Modal -->
  <div class="submit-overlay" id="submitModal">
    <div class="submit-modal">
      <div class="sm-icon">🚀</div>
      <div class="sm-title">Ready to Submit?</div>
      <div class="sm-desc" id="sm-summary">You have answered 0 out of {{ $questions->count() }} questions.</div>
      <div class="sm-actions">
        <button type="button" class="btn-nav btn-prev" onclick="closeSubmitModal()">Review</button>
        <button type="button" class="btn-nav btn-submit" onclick="document.getElementById('quizForm').submit();">Submit Assessment</button>
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
