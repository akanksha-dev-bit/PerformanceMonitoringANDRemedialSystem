<x-app-layout>
  <x-slot name="title">Quiz Results</x-slot>

  @push('styles')
  <style>
    .results-hero {
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #312e81 100%);
      border-radius: 24px; padding: 40px; margin-bottom: 28px;
      text-align: center; color: #fff; position: relative; overflow: hidden;
    }
    .results-hero::before {
      content: ''; position: absolute; top: -50%; right: -10%;
      width: 300px; height: 300px; border-radius: 50%;
      background: radial-gradient(circle, rgba(108,92,231,0.4), transparent 70%);
    }
    .rh-emoji { font-size: 56px; margin-bottom: 12px; position: relative; z-index: 2; }
    .rh-score { font-family:'Poppins',sans-serif; font-size: 48px; font-weight: 800; position: relative; z-index: 2; }
    .rh-label { font-size: 16px; color: rgba(255,255,255,0.7); margin-top: 6px; position: relative; z-index: 2; }
    .rh-meta { display: flex; justify-content: center; gap: 24px; margin-top: 20px; font-size: 14px; position: relative; z-index: 2; }
    .rh-meta strong { color: #fff; }

    .xp-badge {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 8px 20px; border-radius: 100px;
      background: rgba(108,92,231,0.2); border: 1px solid rgba(108,92,231,0.3);
      color: #a78bfa; font-weight: 700; font-size: 14px;
      margin-top: 16px; position: relative; z-index: 2;
    }

    .results-grid { display: grid; grid-template-columns: 1fr; gap: 16px; max-width: 800px; margin: 0 auto; }

    .result-card {
      background: #fff; border: 1px solid rgba(0,0,0,0.05); border-radius: 18px;
      padding: 24px; box-shadow: 0 4px 16px rgba(0,0,0,0.03);
    }
    .rc-header { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 14px; }
    .rc-status {
      width: 32px; height: 32px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 16px; flex-shrink: 0;
    }
    .rc-correct { background: #ecfdf5; }
    .rc-incorrect { background: #fef2f2; }
    .rc-num { font-size: 12px; font-weight: 800; color: #94a3b8; }
    .rc-question { font-size: 15px; font-weight: 600; color: #0f172a; margin-top: 2px; }

    .rc-options { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 12px; }
    @media(max-width:640px) { .rc-options { grid-template-columns: 1fr; } }
    .rc-opt {
      padding: 10px 14px; border-radius: 10px; font-size: 13px;
      background: #f8fafc; border: 1px solid #e2e8f0; color: #475569;
    }
    .rc-opt.correct { background: #ecfdf5; border-color: #a7f3d0; color: #059669; font-weight: 700; }
    .rc-opt.wrong { background: #fef2f2; border-color: #fecaca; color: #dc2626; font-weight: 700; text-decoration: line-through; }

    .rc-explanation {
      margin-top: 12px; padding: 12px 14px;
      background: #fffbeb; border-radius: 10px;
      font-size: 13px; color: #92400e; line-height: 1.5;
    }

    .results-footer {
      text-align: center; margin-top: 28px;
      display: flex; justify-content: center; gap: 12px;
    }
    .btn-back-ws {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 12px 24px; background: linear-gradient(135deg,#6C5CE7,#8B5CF6);
      color: #fff; border-radius: 12px; font-size: 14px; font-weight: 700;
      text-decoration: none; transition: all 0.22s;
      box-shadow: 0 4px 14px rgba(108,92,231,0.28);
    }
    .btn-back-ws:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(108,92,231,0.35); color:#fff; }
    .btn-outline-ws {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 12px 24px; background: #fff; color: #334155;
      border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 14px; font-weight: 600;
      text-decoration: none; transition: all 0.2s;
    }
    .btn-outline-ws:hover { border-color: #94a3b8; color: #0f172a; }

    .remaining-msg {
      background: #eef2ff; border: 1px solid #c7d2fe; border-radius: 12px;
      padding: 14px 20px; text-align: center; font-size: 14px; color: #4338ca;
      font-weight: 600; margin-top: 20px; max-width: 800px; margin-left: auto; margin-right: auto;
    }
  </style>
  @endpush

  @php
    $emoji = $attempt->percentage >= 80 ? '🏆' : ($attempt->percentage >= 60 ? '🎉' : ($attempt->percentage >= 40 ? '👍' : '💪'));
    $message = $attempt->percentage >= 80 ? 'Excellent work!' : ($attempt->percentage >= 60 ? 'Good job!' : ($attempt->percentage >= 40 ? 'Not bad, keep practicing!' : 'Keep going, you can improve!'));
  @endphp

  <div class="results-hero">
    <div class="rh-emoji">{{ $emoji }}</div>
    <div class="rh-score">{{ $attempt->percentage }}%</div>
    <div class="rh-label">{{ $message }}</div>
    <div class="rh-meta">
      <span>Score: <strong>{{ $attempt->score }}/{{ $attempt->total_marks }}</strong></span>
      <span>Time: <strong>{{ floor($attempt->duration_taken / 60) }}m {{ $attempt->duration_taken % 60 }}s</strong></span>
      <span>Quiz: <strong>{{ $quiz->title }}</strong></span>
    </div>
    <div class="xp-badge">⚡ +{{ $xpEarned }} XP Earned</div>
  </div>

  @php $remaining = $assignment->attempts_remaining; @endphp
  @if($remaining > 0)
  <div class="remaining-msg">
    🔄 You have <strong>{{ $remaining }}</strong> more attempt{{ $remaining > 1 ? 's' : '' }} remaining. A new attempt unlocks daily.
  </div>
  @else
  <div class="remaining-msg" style="background:#ecfdf5;border-color:#a7f3d0;color:#059669;">
    ✅ All attempts completed! Great effort on this quiz series.
  </div>
  @endif

  <div style="margin-top:28px;">
    <h3 style="font-family:'Poppins',sans-serif;font-size:18px;font-weight:800;color:#0f172a;text-align:center;margin-bottom:20px;">Question Breakdown</h3>
    <div class="results-grid">
      @foreach($results as $idx => $r)
      <div class="result-card">
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
            if ($letter === $r['correct_answer']) $class = 'correct';
            elseif ($letter === $r['student_answer'] && !$r['is_correct']) $class = 'wrong';
          @endphp
          <div class="rc-opt {{ $class }}">{{ $letter }}) {{ $text }}</div>
          @endforeach
        </div>
        @if($r['explanation'])
        <div class="rc-explanation">💡 {{ $r['explanation'] }}</div>
        @endif
      </div>
      @endforeach
    </div>
  </div>

  <div class="results-footer">
    <a href="{{ route('student.tasks') }}" class="btn-back-ws">← Back to Workspace</a>
  </div>

</x-app-layout>
