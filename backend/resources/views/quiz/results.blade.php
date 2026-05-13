<x-app-layout>
  <x-slot name="title">Quiz Results & Analytics</x-slot>

  @push('styles')
  <style>
    /* ── Results Hero Section ── */
    .results-hero {
      background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #2563eb 100%);
      border-radius: 32px; padding: 56px 40px; margin-bottom: 36px;
      text-align: center; color: #fff; position: relative; overflow: hidden;
      box-shadow: 0 20px 40px rgba(79, 70, 229, 0.25);
    }
    .results-hero::before {
      content: ''; position: absolute; top: -50%; right: -10%;
      width: 400px; height: 400px; border-radius: 50%;
      background: radial-gradient(circle, rgba(255,255,255,0.15), transparent 70%);
      pointer-events: none;
    }
    .results-hero::after {
      content: ''; position: absolute; bottom: -50%; left: -10%;
      width: 400px; height: 400px; border-radius: 50%;
      background: radial-gradient(circle, rgba(255,255,255,0.1), transparent 70%);
      pointer-events: none;
    }

    .rh-emoji { font-size: 64px; margin-bottom: 16px; position: relative; z-index: 2; animation: bounce 2s infinite ease-in-out; }
    .rh-score { font-family: 'Poppins', sans-serif; font-size: 56px; font-weight: 800; position: relative; z-index: 2; line-height: 1.1; letter-spacing: -0.02em; }
    .rh-label { font-size: 18px; font-weight: 500; color: rgba(255,255,255,0.9); margin-top: 8px; position: relative; z-index: 2; }
    
    .rh-meta { display: flex; justify-content: center; flex-wrap: wrap; gap: 32px; margin-top: 28px; font-size: 15px; position: relative; z-index: 2; background: rgba(255,255,255,0.1); backdrop-filter: blur(12px); padding: 14px 28px; border-radius: 100px; border: 1px solid rgba(255,255,255,0.2); max-width: 600px; margin-left: auto; margin-right: auto; }
    .rh-meta span { color: rgba(255,255,255,0.8); }
    .rh-meta strong { color: #fff; font-weight: 700; }

    .xp-badge {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 10px 24px; border-radius: 100px;
      background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      color: #fff; font-weight: 700; font-size: 15px;
      margin-top: 24px; position: relative; z-index: 2;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    /* ── Remaining Attempts Banner ── */
    .remaining-msg {
      background: #eef2ff; border: 1px solid #c7d2fe; border-radius: 20px;
      padding: 18px 24px; text-align: center; font-size: 15px; color: #4338ca;
      font-weight: 600; margin-bottom: 36px; max-width: 860px; margin-left: auto; margin-right: auto;
      box-shadow: 0 4px 15px rgba(0,0,0,0.02); display: flex; align-items: center; justify-content: center; gap: 10px;
    }
    .remaining-msg.success { background: #ecfdf5; border-color: #a7f3d0; color: #059669; }

    /* ── Results Grid ── */
    .results-grid { display: grid; grid-template-columns: 1fr; gap: 20px; max-width: 860px; margin: 0 auto; }

    .result-card {
      background: #fff; border: 1px solid rgba(0,0,0,0.06); border-radius: 24px;
      padding: 32px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);
      position: relative; overflow: hidden; transition: all 0.3s ease;
    }
    .result-card:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(0,0,0,0.06); }
    .rc-side-strip { position: absolute; top: 0; left: 0; bottom: 0; width: 6px; }
    .rc-side-strip.correct { background: #10b981; }
    .rc-side-strip.incorrect { background: #ef4444; }

    .rc-header { display: flex; align-items: flex-start; gap: 16px; margin-bottom: 20px; }
    .rc-status {
      width: 40px; height: 40px; border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 20px; flex-shrink: 0;
    }
    .rc-correct { background: #ecfdf5; color: #10b981; }
    .rc-incorrect { background: #fef2f2; color: #ef4444; }
    .rc-num { font-size: 13px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; }
    .rc-question { font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 700; color: #0f172a; margin-top: 4px; line-height: 1.4; }

    .rc-options { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 16px; }
    @media(max-width: 640px) { .rc-options { grid-template-columns: 1fr; } }
    .rc-opt {
      padding: 14px 18px; border-radius: 14px; font-size: 14px; font-weight: 500;
      background: #f8fafc; border: 1px solid #e2e8f0; color: #475569; display: flex; align-items: center; gap: 10px;
    }
    .rc-opt.correct { background: #ecfdf5; border-color: #a7f3d0; color: #059669; font-weight: 700; box-shadow: 0 2px 8px rgba(16,185,129,0.1); }
    .rc-opt.wrong { background: #fef2f2; border-color: #fecaca; color: #dc2626; font-weight: 700; text-decoration: line-through; box-shadow: 0 2px 8px rgba(239,68,68,0.1); }

    .rc-explanation {
      margin-top: 20px; padding: 16px 20px;
      background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-radius: 16px;
      border: 1px solid #fde68a; font-size: 14px; color: #92400e; line-height: 1.6; display: flex; gap: 12px;
    }
    .rc-explanation span { font-size: 18px; flex-shrink: 0; }

    /* ── Action Footer ── */
    .results-footer {
      text-align: center; margin-top: 40px; margin-bottom: 40px;
      display: flex; justify-content: center; gap: 16px;
    }
    .btn-back-ws {
      display: inline-flex; align-items: center; gap: 10px;
      padding: 16px 36px; background: linear-gradient(135deg, #6C5CE7, #8B5CF6);
      color: #fff; border-radius: 18px; font-size: 16px; font-weight: 700; font-family: 'Poppins', sans-serif;
      text-decoration: none; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 8px 25px rgba(108,92,231,0.35);
    }
    .btn-back-ws:hover { transform: translateY(-3px); box-shadow: 0 12px 35px rgba(108,92,231,0.5); color: #fff; }

    @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
  </style>
  @endpush

  @php
    $emoji = $attempt->percentage >= 80 ? '🏆' : ($attempt->percentage >= 60 ? '🎉' : ($attempt->percentage >= 40 ? '👍' : '💪'));
    $message = $attempt->percentage >= 80 ? 'Exceptional Performance!' : ($attempt->percentage >= 60 ? 'Great Job!' : ($attempt->percentage >= 40 ? 'Solid Effort, Keep Practicing!' : 'Keep Going, You Can Master This!'));
  @endphp

  <div class="results-hero">
    <div class="rh-emoji">{{ $emoji }}</div>
    <div class="rh-score">{{ $attempt->percentage }}%</div>
    <div class="rh-label">{{ $message }}</div>
    <div class="rh-meta">
      <span>Score: <strong>{{ $attempt->score }}/{{ $attempt->total_marks }}</strong></span>
      <span>Time Taken: <strong>{{ floor($attempt->duration_taken / 60) }}m {{ $attempt->duration_taken % 60 }}s</strong></span>
      <span>Topic: <strong>{{ $quiz->title }}</strong></span>
    </div>
    <div class="xp-badge">⚡ +{{ $xpEarned }} Activity XP Earned</div>
  </div>

  @php $remaining = $assignment->attempts_remaining; @endphp
  @if($remaining > 0)
  <div class="remaining-msg">
    🔄 <span>You have <strong>{{ $remaining }}</strong> more attempt{{ $remaining > 1 ? 's' : '' }} remaining in this series. A new attempt unlocks daily.</span>
  </div>
  @else
  <div class="remaining-msg success">
    ✅ <span>All attempts completed! Incredible consistency and effort.</span>
  </div>
  @endif

  <div>
    <h3 style="font-family: 'Poppins', sans-serif; font-size: 22px; font-weight: 800; color: #0f172a; text-align: center; margin-bottom: 24px;">Detailed Question Analytics</h3>
    <div class="results-grid">
      @foreach($results as $idx => $r)
      <div class="result-card">
        <div class="rc-side-strip {{ $r['is_correct'] ? 'correct' : 'incorrect' }}"></div>
        <div class="rc-header">
          <div class="rc-status {{ $r['is_correct'] ? 'rc-correct' : 'rc-incorrect' }}">
            {{ $r['is_correct'] ? '✅' : '❌' }}
          </div>
          <div>
            <div class="rc-num">Question {{ $idx + 1 }} · {{ $r['marks'] }} mark{{ $r['marks'] > 1 ? 's' : '' }}</div>
            <div class="rc-question">{{ $r['question'] }}</div>
          </div>
        </div>
        <div class="rc-options">
          @foreach($r['options'] as $letter => $text)
          @php
            $class = '';
            $icon = '';
            if ($letter === $r['correct_answer']) { $class = 'correct'; $icon = '✓ '; }
            elseif ($letter === $r['student_answer'] && !$r['is_correct']) { $class = 'wrong'; $icon = '✕ '; }
          @endphp
          <div class="rc-opt {{ $class }}">{{ $icon }}{{ $letter }}) {{ $text }}</div>
          @endforeach
        </div>
        @if($r['explanation'])
        <div class="rc-explanation">
          <span>💡</span>
          <div><strong>Expert Insight:</strong> {{ $r['explanation'] }}</div>
        </div>
        @endif
      </div>
      @endforeach
    </div>
  </div>

  <div class="results-footer">
    <a href="{{ route('student.tasks') }}" class="btn-back-ws">← Return to Learning Workspace</a>
  </div>

</x-app-layout>
