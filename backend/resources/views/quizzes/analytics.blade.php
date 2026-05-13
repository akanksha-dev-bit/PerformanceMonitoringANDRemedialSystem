<x-app-layout>
  <x-slot name="title">Quiz Analytics</x-slot>

  @push('styles')
  <style>
    .analytics-header {
      background: linear-gradient(135deg,#fff,#f8faff);
      border:1px solid rgba(0,0,0,0.06); border-radius:20px;
      padding:28px 32px; margin-bottom:28px;
      box-shadow:0 4px 20px rgba(0,0,0,0.03);
      display:flex; justify-content:space-between; align-items:flex-start;
      flex-wrap:wrap; gap:16px;
    }
    .ah-title { font-family:'Poppins',sans-serif; font-size:24px; font-weight:800; color:#0f172a; margin:0 0 6px; }
    .ah-meta { font-size:13px; color:#64748b; display:flex; gap:16px; flex-wrap:wrap; }
    .ah-meta strong { color:#334155; }

    .kpi-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:16px; margin-bottom:28px; }
    .kpi-box {
      background:#fff; border:1px solid rgba(0,0,0,0.04); border-radius:16px;
      padding:20px; text-align:center; box-shadow:0 4px 16px rgba(0,0,0,0.03);
    }
    .kpi-value { font-family:'Poppins',sans-serif; font-size:28px; font-weight:800; color:#0f172a; }
    .kpi-label { font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-top:4px; }

    .p-card { background:#fff; border:1px solid rgba(0,0,0,0.04); border-radius:20px; padding:24px; box-shadow:0 4px 16px rgba(0,0,0,0.03); }
    .p-card-title { font-family:'Poppins',sans-serif; font-size:16px; font-weight:800; color:#0f172a; margin:0 0 16px; }

    .attempt-row {
      display:flex; align-items:center; justify-content:space-between;
      padding:14px 0; border-bottom:1px solid #f1f5f9;
    }
    .attempt-row:last-child { border-bottom:none; }
    .at-num { display:inline-flex; padding:3px 10px; border-radius:8px; background:#eef2ff; color:#6366f1; font-size:12px; font-weight:800; margin-right:12px; }
    .at-score { font-size:14px; font-weight:700; }
    .at-date { font-size:12px; color:#94a3b8; }

    .btn-back {
      display:inline-flex; align-items:center; gap:6px;
      padding:8px 16px; background:#f1f5f9; color:#334155;
      border-radius:10px; font-size:12px; font-weight:700;
      text-decoration:none; transition:all 0.2s;
    }
    .btn-back:hover { background:#0f172a; color:#fff; }
  </style>
  @endpush

  <div class="analytics-header">
    <div>
      <h1 class="ah-title">📊 {{ $assignment->quiz->title }} — Analytics</h1>
      <div class="ah-meta">
        <span>👤 <strong>{{ $assignment->student->user->name ?? 'Unknown' }}</strong></span>
        <span>📚 <strong>{{ $assignment->quiz->subject->name ?? 'General' }}</strong></span>
        <span>📅 {{ $assignment->start_date->format('M d') }} → {{ $assignment->end_date->format('M d, Y') }}</span>
        <span>🔄 <strong>{{ $assignment->repeat_days }}</strong> repeat days</span>
      </div>
    </div>
    <a href="{{ route('quizzes.show', $assignment->quiz) }}" class="btn-back">← Back to Quiz</a>
  </div>

  @php
    $attempts = $assignment->attempts;
    $completedAttempts = $attempts->whereNotNull('completed_at');
    $avgScore = $completedAttempts->count() > 0 ? round($completedAttempts->avg('percentage'), 1) : 0;
    $bestScore = $completedAttempts->count() > 0 ? round($completedAttempts->max('percentage'), 1) : 0;
    $completionRate = $assignment->repeat_days > 0 ? round(($completedAttempts->count() / $assignment->repeat_days) * 100) : 0;
    $improvement = 0;
    if ($completedAttempts->count() >= 2) {
      $first = $completedAttempts->first()->percentage;
      $last = $completedAttempts->last()->percentage;
      $improvement = round($last - $first, 1);
    }
  @endphp

  <div class="kpi-row">
    <div class="kpi-box">
      <div class="kpi-value">{{ $completedAttempts->count() }}/{{ $assignment->repeat_days }}</div>
      <div class="kpi-label">Attempts Done</div>
    </div>
    <div class="kpi-box">
      <div class="kpi-value" style="color:#6C5CE7;">{{ $avgScore }}%</div>
      <div class="kpi-label">Avg Score</div>
    </div>
    <div class="kpi-box">
      <div class="kpi-value" style="color:#10b981;">{{ $bestScore }}%</div>
      <div class="kpi-label">Best Score</div>
    </div>
    <div class="kpi-box">
      <div class="kpi-value">{{ $completionRate }}%</div>
      <div class="kpi-label">Completion</div>
    </div>
    <div class="kpi-box">
      <div class="kpi-value" style="color:{{ $improvement >= 0 ? '#10b981' : '#ef4444' }};">
        {{ $improvement >= 0 ? '+' : '' }}{{ $improvement }}%
      </div>
      <div class="kpi-label">Improvement</div>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
    @if($completedAttempts->count() > 0)
    <div class="p-card">
      <h3 class="p-card-title">Score Trend</h3>
      <div style="height:250px;position:relative;">
        <canvas id="trendChart"></canvas>
      </div>
    </div>
    @endif

    <div class="p-card">
      <h3 class="p-card-title">Attempt History</h3>
      @forelse($completedAttempts as $idx => $att)
      <div class="attempt-row">
        <div style="display:flex;align-items:center;">
          <span class="at-num">Day {{ $idx + 1 }}</span>
          <span class="at-score" style="color:{{ $att->percentage >= 60 ? '#10b981' : ($att->percentage >= 40 ? '#f59e0b' : '#ef4444') }};">
            {{ $att->score }}/{{ $att->total_marks }} ({{ $att->percentage }}%)
          </span>
        </div>
        <span class="at-date">{{ $att->completed_at->format('M d, H:i') }}</span>
      </div>
      @empty
      <div style="padding:24px;text-align:center;background:#f8fafc;border-radius:12px;border:1px dashed #d1d5db;">
        <p style="color:#64748b;font-size:13px;margin:0;">No attempts completed yet.</p>
      </div>
      @endforelse
    </div>
  </div>

  @if($completedAttempts->count() > 0)
  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    window.addEventListener('DOMContentLoaded', function() {
      const ctx = document.getElementById('trendChart');
      if (!ctx) return;

      const attempts = @json($completedAttempts->values()->map(fn($a, $i) => ['day' => 'Day '.($i+1), 'score' => $a->percentage]));

      Chart.defaults.font.family = "'Inter','Segoe UI',Roboto,sans-serif";
      Chart.defaults.color = '#64748b';

      new Chart(ctx, {
        type: 'line',
        data: {
          labels: attempts.map(a => a.day),
          datasets: [{
            label: 'Score (%)',
            data: attempts.map(a => a.score),
            borderColor: '#6C5CE7',
            backgroundColor: 'rgba(108,92,231,0.1)',
            borderWidth: 3, tension: 0.4, fill: true,
            pointBackgroundColor: '#fff', pointBorderColor: '#6C5CE7',
            pointBorderWidth: 2, pointRadius: 5, pointHoverRadius: 7
          }]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            y: { beginAtZero: true, max: 100, grid: { color: '#f1f5f9', drawBorder: false }, border: { display: false } },
            x: { grid: { display: false } }
          }
        }
      });
    });
  </script>
  @endpush
  @endif

</x-app-layout>
