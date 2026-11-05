<x-app-layout>
  <x-slot name="title">My Attendance</x-slot>

  @push('styles')
    <style>
      .sa-page { max-width:1440px; margin:0 auto; color:#18233d; }
      .sa-header { margin-bottom:22px; }
      .sa-eyebrow { display:block; margin-bottom:7px; color:#6b5de4; font-size:11px; font-weight:800; letter-spacing:.14em; text-transform:uppercase; }
      .sa-header h1 { margin:0; color:#14203b; font-size:clamp(27px,3vw,34px); line-height:1.15; letter-spacing:-.04em; }
      .sa-header p { margin:8px 0 0; color:#758197; font-size:14px; }
      .sa-banner { position:relative; overflow:hidden; display:flex; align-items:center; justify-content:space-between; gap:28px; min-height:218px; margin-bottom:20px; padding:29px 35px; border-radius:24px; color:#fff; background:linear-gradient(120deg,#332986 0%,#6151d6 54%,#8974f0 100%); box-shadow:0 18px 35px rgba(81,65,179,.2); }
      .sa-banner::before { content:""; position:absolute; width:340px; height:340px; top:-225px; right:10%; border:1px solid rgba(255,255,255,.19); border-radius:50%; box-shadow:0 0 0 46px rgba(255,255,255,.035),0 0 0 95px rgba(255,255,255,.025); pointer-events:none; }
      .sa-banner::after { content:""; position:absolute; inset:0; background:radial-gradient(circle at 75% 70%,rgba(255,255,255,.12),transparent 31%); pointer-events:none; }
      .sa-banner-copy,.sa-ring-wrap { position:relative; z-index:1; }
      .sa-banner-label { display:inline-flex; align-items:center; gap:7px; padding:7px 10px; border:1px solid rgba(255,255,255,.28); border-radius:999px; background:rgba(255,255,255,.12); font-size:10px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; backdrop-filter:blur(12px); }
      .sa-banner h2 { margin:15px 0 6px; font-size:clamp(24px,3vw,32px); line-height:1.15; letter-spacing:-.035em; }
      .sa-banner p { max-width:560px; margin:0; color:rgba(255,255,255,.84); font-size:13px; line-height:1.55; }
      .sa-banner-meta { display:flex; flex-wrap:wrap; gap:8px; margin-top:19px; }
      .sa-banner-meta span { padding:7px 10px; border:1px solid rgba(255,255,255,.21); border-radius:9px; background:rgba(255,255,255,.11); font-size:11px; font-weight:700; }
      .sa-ring-wrap { flex:none; width:154px; height:154px; padding:10px; border:1px solid rgba(255,255,255,.35); border-radius:50%; background:rgba(255,255,255,.1); box-shadow:0 8px 30px rgba(24,18,86,.15); backdrop-filter:blur(10px); }
      .sa-ring { width:100%; height:100%; display:grid; place-items:center; border-radius:50%; background:conic-gradient(#baf6e5 var(--attendance),rgba(255,255,255,.22) 0); }
      .sa-ring-inner { width:102px; height:102px; display:flex; flex-direction:column; align-items:center; justify-content:center; border-radius:50%; background:#5144b8; box-shadow:inset 0 0 12px rgba(0,0,0,.1); }
      .sa-ring-inner strong { font-size:31px; line-height:1; letter-spacing:-.05em; }
      .sa-ring-inner small { margin-top:5px; font-size:10px; color:rgba(255,255,255,.75); text-transform:uppercase; letter-spacing:.07em; }
      .sa-grid { display:grid; grid-template-columns:minmax(0,1fr) minmax(0,1fr); gap:20px; margin-bottom:20px; align-items:start; }
      .sa-panel { min-width:0; border:1px solid #e8eaf3; border-radius:21px; background:#fff; box-shadow:0 9px 30px rgba(30,42,79,.05); overflow:hidden; }
      .sa-panel-head { display:flex; justify-content:space-between; gap:12px; align-items:flex-start; padding:21px 23px 17px; }
      .sa-panel-head h2 { margin:0; font-size:17px; letter-spacing:-.02em; }
      .sa-panel-head p { margin:5px 0 0; color:#8290a4; font-size:12px; line-height:1.45; }
      .sa-panel-count { flex:none; padding:6px 9px; border-radius:999px; background:#f1efff; color:#6659da; font-size:10px; font-weight:800; }
      .sa-subject-list { padding:0 23px 14px; }
      .sa-subject { display:flex; align-items:center; gap:13px; padding:12px 0; border-top:1px solid #f0f2f7; }
      .sa-subject-ring { --ring-color:#6a5ce1; flex:none; display:grid; place-items:center; width:48px; height:48px; border-radius:50%; background:conic-gradient(var(--ring-color) var(--ring-pct),#ebedf5 0); }
      .sa-subject-ring.is-low { --ring-color:#e77870; }
      .sa-subject-ring::after { content:""; grid-area:1/1; width:34px; height:34px; border-radius:50%; background:#fff; }
      .sa-subject-ring span { grid-area:1/1; z-index:1; font-size:10px; font-weight:800; }
      .sa-subject-name { min-width:0; flex:1; }
      .sa-subject-name strong { display:block; font-size:13px; overflow-wrap:anywhere; }
      .sa-subject-name small { display:block; margin-top:3px; color:#8b97a8; font-size:11px; }
      .sa-percentage { flex:none; padding:6px 8px; border-radius:8px; background:#f0eeff; color:#6255d6; font-size:11px; font-weight:800; }
      .sa-percentage.is-low { background:#fff0ed; color:#cb655c; }
      .sa-empty { padding:35px 20px; color:#8996a9; text-align:center; font-size:13px; }
      .sa-trend { display:flex; gap:11px; height:228px; padding:5px 23px 20px; }
      .sa-axis { display:flex; flex-direction:column; justify-content:space-between; height:183px; color:#9aa5b4; font-size:10px; text-align:right; }
      .sa-plot { flex:1; min-width:0; position:relative; height:183px; border-bottom:1px solid #dce2ee; background:repeating-linear-gradient(to top,#edf0f7 0,#edf0f7 1px,transparent 1px,transparent 25%); }
      .sa-bars { position:relative; z-index:1; display:grid; grid-template-columns:repeat(5,minmax(0,1fr)); gap:8px; height:100%; align-items:end; }
      .sa-month { height:100%; display:flex; flex-direction:column; justify-content:flex-end; align-items:center; position:relative; }
      .sa-month-bar { width:min(36px,80%); height:var(--height); min-height:4px; border-radius:9px 9px 4px 4px; background:linear-gradient(180deg,#9888fa,#6658e6); box-shadow:0 8px 14px rgba(96,82,213,.2); transition:transform .25s ease,box-shadow .25s ease; }
      .sa-month:hover .sa-month-bar { transform:translateY(-3px); box-shadow:0 10px 20px rgba(96,82,213,.32); }
      .sa-month.is-empty .sa-month-bar { background:#dce1ec; min-height:3px; box-shadow:none; }
      .sa-month-value { position:absolute; bottom:calc(var(--height) + 7px); color:#6255d6; font-size:10px; font-weight:800; white-space:nowrap; }
      .sa-month.is-empty .sa-month-value { bottom:8px; color:#9aa5b4; }
      .sa-month-label { position:absolute; top:191px; color:#8491a4; font-size:10px; font-weight:700; white-space:nowrap; }
      .sa-table-wrap { overflow-x:auto; }
      .sa-table { width:100%; border-collapse:collapse; text-align:left; }
      .sa-table th { padding:12px 20px; background:#fafbfe; border-top:1px solid #eef0f6; border-bottom:1px solid #eef0f6; color:#8c98aa; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; white-space:nowrap; }
      .sa-table td { padding:15px 20px; border-bottom:1px solid #eff1f6; color:#526078; font-size:12px; }
      .sa-table tbody tr:last-child td { border-bottom:0; }
      .sa-table tbody tr:hover { background:#f8f9ff; }
      .sa-table td strong { color:#26334b; font-weight:750; }
      .sa-absent-tag { display:inline-block; padding:5px 8px; border-radius:8px; background:#fff0ed; color:#c65f57; font-size:10px; font-weight:800; }
      .sa-excuse-note { display:block; margin-top:5px; color:#96a1b1; font-size:10px; }
      @media(max-width:900px) { .sa-grid { grid-template-columns:1fr; } }
      @media(max-width:580px) { .sa-banner { flex-direction:column-reverse; align-items:flex-start; padding:23px; gap:17px; } .sa-ring-wrap { width:116px; height:116px; padding:7px; } .sa-ring-inner { width:82px; height:82px; } .sa-ring-inner strong { font-size:25px; } .sa-panel-head { padding:18px; } .sa-subject-list { padding:0 18px 12px; } .sa-trend { padding-left:15px; padding-right:15px; } .sa-table { min-width:650px; } }
      @media(prefers-reduced-motion:reduce) { .sa-month-bar { transition:none; } .sa-month:hover .sa-month-bar { transform:none; } }
    </style>
  @endpush

  <main class="sa-page">
    <header class="sa-header">
      <span class="sa-eyebrow">Attendance insights</span>
      <h1>My Attendance</h1>
      <p>Your presence, subject by subject and month by month.</p>
    </header>

    <section class="sa-banner" aria-label="Overall attendance">
      <div class="sa-banner-copy">
        <span class="sa-banner-label">{{ $attendanceTotal === 0 ? 'Awaiting records' : ($overallAttendance >= 75 ? 'On track' : 'Needs attention') }}</span>
        <h2>{{ $attendanceTotal === 0 ? 'Your attendance journey starts here.' : ($overallAttendance >= 75 ? 'Showing up looks good on you.' : 'Let’s get your attendance back on track.') }}</h2>
        <p>
          @if($attendanceTotal === 0)
            Once your teachers record attendance, your progress will appear here.
          @elseif($overallAttendance >= 75)
            You’re meeting the 75% attendance target. Keep your momentum going.
          @else
            You’re below the 75% attendance target. Attend upcoming sessions to improve your rate.
          @endif
        </p>
        <div class="sa-banner-meta">
          <span>Target · 75%</span>
          <span>{{ $attendanceTotal }} {{ \Illuminate\Support\Str::plural('session', $attendanceTotal) }} recorded</span>
        </div>
      </div>
      <div class="sa-ring-wrap">
        <div class="sa-ring" style="--attendance:{{ min(100, max(0, $overallAttendance)) }}%">
          <div class="sa-ring-inner">
            <strong>{{ $attendanceTotal > 0 ? $overallAttendance.'%' : '—' }}</strong>
            <small>Overall</small>
          </div>
        </div>
      </div>
    </section>

    <div class="sa-grid">
      <section class="sa-panel" aria-labelledby="sa-subject-heading">
        <div class="sa-panel-head">
          <div>
            <h2 id="sa-subject-heading">Subject-wise attendance</h2>
            <p>Present sessions out of those recorded in each subject.</p>
          </div>
          <span class="sa-panel-count">{{ count($subjectBreakdown) }} {{ \Illuminate\Support\Str::plural('subject', count($subjectBreakdown)) }}</span>
        </div>
        <div class="sa-subject-list">
          @forelse($subjectBreakdown as $subject)
            <div class="sa-subject">
              <div class="sa-subject-ring {{ $subject['percentage'] < 75 ? 'is-low' : '' }}" style="--ring-pct:{{ min(100, max(0, $subject['percentage'])) }}%" role="img" aria-label="{{ $subject['subject'] }} attendance: {{ $subject['percentage'] }} percent">
                <span>{{ round($subject['percentage']) }}</span>
              </div>
              <div class="sa-subject-name">
                <strong>{{ $subject['subject'] }}</strong>
                <small>{{ $subject['present'] }} of {{ $subject['total'] }} sessions present</small>
              </div>
              <span class="sa-percentage {{ $subject['percentage'] < 75 ? 'is-low' : '' }}">{{ $subject['percentage'] }}%</span>
            </div>
          @empty
            <div class="sa-empty">No subject attendance has been recorded yet.</div>
          @endforelse
        </div>
      </section>

      <section class="sa-panel" aria-labelledby="sa-trend-heading">
        <div class="sa-panel-head">
          <div>
            <h2 id="sa-trend-heading">Monthly trends</h2>
            <p>Presence rate over the last five calendar months.</p>
          </div>
          <span class="sa-panel-count">0–100%</span>
        </div>
        <div class="sa-trend" role="img" aria-label="Monthly attendance: {{ $monthlyTrends->map(fn($month) => $month['label'].': '.($month['pct'] === null ? 'no sessions' : $month['pct'].'%'))->implode(', ') }}">
          <div class="sa-axis" aria-hidden="true"><span>100</span><span>75</span><span>50</span><span>25</span><span>0</span></div>
          <div class="sa-plot">
            <div class="sa-bars">
              @foreach($monthlyTrends as $month)
                <div class="sa-month {{ $month['pct'] === null ? 'is-empty' : '' }}" style="--height:{{ $month['pct'] === null ? 0 : min(100, max(0, $month['pct'])) }}%" title="{{ $month['label'] }}: {{ $month['pct'] === null ? 'No sessions' : $month['pct'].'% present from '.$month['total'].' sessions' }}">
                  <span class="sa-month-value">{{ $month['pct'] === null ? '—' : $month['pct'].'%' }}</span>
                  <span class="sa-month-bar"></span>
                  <span class="sa-month-label">{{ $month['month'] }}</span>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </section>
    </div>

    <section class="sa-panel" aria-labelledby="sa-absence-heading">
      <div class="sa-panel-head">
        <div>
          <h2 id="sa-absence-heading">Absence log</h2>
          <p>Sessions marked absent. Excuse status is not recorded by the school yet.</p>
        </div>
        <span class="sa-panel-count">{{ $absentHistory->count() }} {{ \Illuminate\Support\Str::plural('absence', $absentHistory->count()) }}</span>
      </div>
      <div class="sa-table-wrap">
        <table class="sa-table">
          <thead><tr><th scope="col">Date</th><th scope="col">Subject</th><th scope="col">Period</th><th scope="col">Teacher</th><th scope="col">Status</th></tr></thead>
          <tbody>
            @forelse($absentHistory as $absent)
              <tr>
                <td><strong>{{ $absent->session?->date ? \Carbon\Carbon::parse($absent->session->date)->format('M j, Y') : 'Date unavailable' }}</strong></td>
                <td>{{ $absent->session?->subject?->name ?? 'Unknown subject' }}</td>
                <td>Period {{ $absent->session?->period_number ?? '—' }}</td>
                <td>{{ $absent->session?->teacher?->name ?? 'Unknown teacher' }}</td>
                <td><span class="sa-absent-tag">Absent</span><span class="sa-excuse-note">Excuse not recorded</span></td>
              </tr>
            @empty
              <tr><td colspan="5" class="sa-empty">No absences recorded. Keep it going!</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </main>
</x-app-layout>
