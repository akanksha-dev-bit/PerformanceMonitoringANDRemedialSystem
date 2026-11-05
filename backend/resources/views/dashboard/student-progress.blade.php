<x-app-layout>
  <x-slot name="title">My Progress</x-slot>

  @php
    $totalMarks = $marks->sum('marks_obtained');
    $possibleMarks = $marks->sum('max_marks');
    $overallScore = $possibleMarks > 0 ? round($totalMarks / $possibleMarks * 100, 1) : null;
    $bestSubject = $subjectBreakdown->sortByDesc('pct')->first();
  @endphp

  @push('styles')
    <style>
      .sp-page { max-width:1440px; margin:0 auto; color:#17213a; }
      .sp-header { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin-bottom:24px; }
      .sp-eyebrow { display:block; margin-bottom:7px; color:#6b5de4; font-size:11px; font-weight:800; letter-spacing:.14em; text-transform:uppercase; }
      .sp-header h1 { margin:0; color:#14203b; font-size:clamp(26px,3vw,34px); line-height:1.15; letter-spacing:-.04em; }
      .sp-header p { margin:8px 0 0; color:#718096; font-size:14px; }
      .sp-back { display:inline-flex; align-items:center; justify-content:center; gap:8px; flex:none; min-height:40px; padding:9px 13px; border:1px solid #e2e7f2; border-radius:11px; background:#fff; color:#4c5972; text-decoration:none; font-size:12px; font-weight:700; }
      .sp-back svg { width:16px; height:16px; flex:none; stroke:currentColor; }
      .sp-back:hover { border-color:#bdb6fa; color:#5c4fd6; }
      .sp-summary { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:14px; margin-bottom:20px; }
      .sp-stat { position:relative; min-width:0; overflow:hidden; padding:20px; border:1px solid #e8eaf3; border-radius:18px; background:#fff; box-shadow:0 7px 25px rgba(30,42,79,.045); }
      .sp-stat::after { content:""; position:absolute; width:94px; height:94px; top:-48px; right:-35px; border-radius:50%; background:var(--stat-tint,#edeaff); filter:blur(12px); }
      .sp-stat:nth-child(2) { --stat-tint:#e1f3ff; }
      .sp-stat:nth-child(3) { --stat-tint:#dcf7f1; }
      .sp-stat:nth-child(4) { --stat-tint:#fff0db; }
      .sp-stat-label { display:block; position:relative; z-index:1; color:#7a879b; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; }
      .sp-stat-value { display:block; position:relative; z-index:1; margin-top:10px; color:#17223d; font-size:clamp(22px,2.4vw,30px); font-weight:800; letter-spacing:-.04em; line-height:1.12; overflow-wrap:anywhere; }
      .sp-stat-value.is-name { font-size:19px; }
      .sp-stat-note { display:block; position:relative; z-index:1; margin-top:6px; color:#7d899a; font-size:11px; }
      .sp-grid { display:grid; grid-template-columns:minmax(0,.94fr) minmax(0,1.06fr); gap:20px; align-items:start; }
      .sp-panel { min-width:0; border:1px solid #e8eaf3; border-radius:21px; background:#fff; box-shadow:0 9px 30px rgba(30,42,79,.05); overflow:hidden; }
      .sp-panel-head { padding:21px 23px 17px; display:flex; justify-content:space-between; align-items:flex-start; gap:12px; }
      .sp-panel-head h2 { margin:0; font-size:17px; letter-spacing:-.02em; }
      .sp-panel-head p { margin:5px 0 0; color:#8290a4; font-size:12px; line-height:1.45; }
      .sp-count { flex:none; padding:6px 9px; border-radius:999px; background:#f1efff; color:#6659da; font-size:10px; font-weight:800; }
      .sp-chart { height:246px; padding:0 18px 12px; }
      .sp-chart canvas { width:100% !important; height:100% !important; }
      .sp-subjects { padding:4px 23px 21px; border-top:1px solid #eef0f6; }
      .sp-subject { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:13px 0; border-bottom:1px solid #f0f2f7; }
      .sp-subject:last-child { border-bottom:0; padding-bottom:0; }
      .sp-subject strong { display:block; font-size:13px; }
      .sp-subject small { display:block; margin-top:3px; color:#8a96a8; font-size:11px; }
      .sp-score-pill { flex:none; display:inline-flex; align-items:center; justify-content:center; min-width:58px; padding:7px 9px; border-radius:9px; background:#f0eeff; color:#6355d6; font-size:12px; font-weight:800; }
      .sp-score-pill.is-low { background:#fff0ed; color:#cf6656; }
      .sp-score-pill.is-mid { background:#fff5e7; color:#b67720; }
      .sp-empty { padding:45px 22px; color:#8190a4; text-align:center; font-size:13px; }
      .sp-table-wrap { overflow-x:auto; }
      .sp-table { width:100%; border-collapse:collapse; text-align:left; }
      .sp-table th { padding:12px 17px; background:#fafbfe; border-top:1px solid #eef0f6; border-bottom:1px solid #eef0f6; color:#8c98aa; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; white-space:nowrap; }
      .sp-table td { padding:16px 17px; border-bottom:1px solid #eff1f6; vertical-align:middle; font-size:12px; }
      .sp-table tbody tr:last-child td { border-bottom:0; }
      .sp-table tbody tr:hover { background:#f8f9ff; }
      .sp-table-subject { font-size:12px; font-weight:750; color:#26324a; }
      .sp-table-date { display:block; margin-top:4px; font-size:10px; color:#929eae; }
      .sp-term { display:inline-block; padding:5px 8px; border-radius:8px; background:#eeecff; color:#6557d4; font-size:10px; font-weight:800; text-transform:capitalize; white-space:nowrap; }
      .sp-exam-type { display:block; margin-top:4px; color:#8895a7; font-size:10px; text-transform:capitalize; }
      .sp-result { min-width:104px; }
      .sp-result strong { font-size:12px; color:#27324a; }
      .sp-result small { color:#8290a4; font-size:10px; }
      .sp-mini-track { height:5px; margin-top:8px; border-radius:99px; background:#edf0f6; overflow:hidden; }
      .sp-mini-track span { display:block; width:var(--score); height:100%; border-radius:inherit; background:linear-gradient(90deg,#6154de,#9c8df8); }
      @media(max-width:1100px) { .sp-grid { grid-template-columns:1fr; } }
      @media(max-width:720px) { .sp-summary { grid-template-columns:repeat(2,minmax(0,1fr)); } .sp-header { align-items:flex-start; } }
      @media(max-width:480px) { .sp-header { flex-direction:column; } .sp-summary { gap:9px; } .sp-stat { padding:15px; } .sp-panel-head { padding:18px; } .sp-subjects { padding:4px 18px 17px; } .sp-table { min-width:480px; } }
    </style>
  @endpush

  <main class="sp-page">
    <header class="sp-header">
      <div>
        <span class="sp-eyebrow">Learning analytics</span>
        <h1>My Progress</h1>
        <p>See how your exams and subjects are coming together.</p>
      </div>
      <a class="sp-back" href="{{ route('dashboard.student') }}" aria-label="Back to dashboard">
        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        <span>Dashboard</span>
      </a>
    </header>

    <section class="sp-summary" aria-label="Performance summary">
      <div class="sp-stat">
        <span class="sp-stat-label">Overall average</span>
        <strong class="sp-stat-value">{{ $overallScore !== null ? $overallScore.'%' : '—' }}</strong>
        <span class="sp-stat-note">Across recorded exams</span>
      </div>
      <div class="sp-stat">
        <span class="sp-stat-label">Exams recorded</span>
        <strong class="sp-stat-value">{{ $marks->count() }}</strong>
        <span class="sp-stat-note">Results available</span>
      </div>
      <div class="sp-stat">
        <span class="sp-stat-label">Subjects tracked</span>
        <strong class="sp-stat-value">{{ $subjectBreakdown->count() }}</strong>
        <span class="sp-stat-note">With graded results</span>
      </div>
      <div class="sp-stat">
        <span class="sp-stat-label">Strongest subject</span>
        <strong class="sp-stat-value is-name">{{ $bestSubject['name'] ?? '—' }}</strong>
        <span class="sp-stat-note">{{ isset($bestSubject['pct']) ? $bestSubject['pct'].'% average' : 'Awaiting results' }}</span>
      </div>
    </section>

    <div class="sp-grid">
      <section class="sp-panel" aria-labelledby="sp-subject-heading">
        <div class="sp-panel-head">
          <div>
            <h2 id="sp-subject-heading">Subject-wise Breakdown</h2>
            <p>Average percentage for each subject · hover the chart for grade tiers</p>
          </div>
          <span class="sp-count">{{ $subjectBreakdown->count() }} {{ \Illuminate\Support\Str::plural('subject', $subjectBreakdown->count()) }}</span>
        </div>
        @if($subjectBreakdown->isNotEmpty())
          <div class="sp-chart">
            <canvas id="progressChart" role="img" aria-label="Subject scores: {{ $subjectBreakdown->map(fn($s) => $s['name'].': '.$s['pct'].'%')->implode(', ') }}"></canvas>
          </div>
          <div class="sp-subjects">
            @foreach($subjectBreakdown as $subject)
              <div class="sp-subject">
                <div>
                  <strong>{{ $subject['name'] }}</strong>
                  <small>{{ $subject['obtained'] }} of {{ $subject['max'] }} marks</small>
                </div>
                <span class="sp-score-pill {{ $subject['pct'] < 40 ? 'is-low' : ($subject['pct'] < 60 ? 'is-mid' : '') }}">{{ $subject['pct'] }}%</span>
              </div>
            @endforeach
          </div>
        @else
          <div class="sp-empty">Your subject chart will appear after your first exam result.</div>
        @endif
      </section>

      <section class="sp-panel" aria-labelledby="sp-exam-heading">
        <div class="sp-panel-head">
          <div>
            <h2 id="sp-exam-heading">Exam History</h2>
            <p>Every recorded result, in one place.</p>
          </div>
          <span class="sp-count">{{ $marks->count() }} {{ \Illuminate\Support\Str::plural('result', $marks->count()) }}</span>
        </div>
        <div class="sp-table-wrap">
          <table class="sp-table">
            <thead>
              <tr><th scope="col">Subject</th><th scope="col">Exam</th><th scope="col">Score</th></tr>
            </thead>
            <tbody>
              @forelse($marks->sortByDesc('created_at') as $mark)
                @php $score = $mark->max_marks > 0 ? round($mark->marks_obtained / $mark->max_marks * 100, 1) : 0; @endphp
                <tr>
                  <td>
                    <span class="sp-table-subject">{{ $mark->subject->name ?? 'Unknown subject' }}</span>
                    <span class="sp-table-date">{{ $mark->created_at?->format('M j, Y') ?? 'Date unavailable' }}</span>
                  </td>
                  <td>
                    <span class="sp-term">{{ $mark->term ?: ($mark->exam_type ?: 'Exam') }}</span>
                    @if($mark->term && $mark->exam_type)<span class="sp-exam-type">{{ $mark->exam_type }}</span>@endif
                  </td>
                  <td>
                    <div class="sp-result">
                      <strong>{{ $score }}%</strong> <small>· {{ $mark->marks_obtained }}/{{ $mark->max_marks }}</small>
                      <div class="sp-mini-track" role="progressbar" aria-label="{{ $mark->subject->name ?? 'Subject' }} exam score" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $score }}" style="--score:{{ min(100, max(0, $score)) }}%"><span></span></div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr><td colspan="3" class="sp-empty">No exam results have been recorded yet.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </main>

  @push('scripts')
    <script src="{{ asset('js/student-performance-chart.js') }}"></script>
    @if($subjectBreakdown->isNotEmpty())
      <script>
        window.createStudentPerformanceChart(
          document.getElementById('progressChart'),
          {{ Illuminate\Support\Js::from($subjectBreakdown->pluck('name')) }},
          {{ Illuminate\Support\Js::from($subjectBreakdown->pluck('pct')) }}
        );
      </script>
    @endif
  @endpush
</x-app-layout>
