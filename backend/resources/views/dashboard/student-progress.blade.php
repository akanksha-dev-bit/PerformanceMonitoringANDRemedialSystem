<x-app-layout>
  <x-slot name="title">My Progress</x-slot>

  <style>
    .premium-page-header {
      background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 32px;
      box-shadow: var(--shadow-sm);
      position: relative;
      overflow: hidden;
    }
    .premium-page-header::after {
      content: '';
      position: absolute;
      right: 0;
      top: 0;
      width: 400px;
      height: 100%;
      background: radial-gradient(circle at top right, rgba(108,92,231,0.06), transparent 70%);
      pointer-events: none;
    }
    .pph-title { font-family: 'Poppins', sans-serif; font-size: 28px; font-weight: 700; color: #111827; letter-spacing: -0.02em; }
    .pph-subtitle { font-size: 15px; color: var(--text-muted); margin-top: 4px; }
    
    .premium-data-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      box-shadow: 0 10px 40px rgba(0,0,0,0.03);
      overflow: hidden;
      margin-bottom: 24px;
      display: flex;
      flex-direction: column;
    }
    .pdc-header {
      padding: 24px 32px;
      border-bottom: 1px solid rgba(0,0,0,0.04);
    }
    .pdc-title { font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 700; color: #111827; display:flex; align-items:center; gap:8px; }
    
    .premium-table { width: 100%; border-collapse: collapse; }
    .premium-table th { text-align: left; padding: 16px 32px; font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; background: #fdfdfd; border-bottom: 1px solid var(--border); }
    .premium-table td { padding: 20px 32px; font-size: 14px; border-bottom: 1px solid rgba(0,0,0,0.03); vertical-align: middle; }
    .premium-table tr:last-child td { border-bottom: none; }
    .premium-table tr:hover { background: rgba(108,92,231,0.01); }

    .sd-subject-item { margin-bottom: 20px; padding: 0 32px; }
    .sd-subject-item:last-child { margin-bottom: 32px; }
    .sd-subject-row { display: flex; justify-content: space-between; margin-bottom: 8px; align-items: center; }
    .sd-subject-name { font-size: 15px; font-weight: 600; color: #111827; }
    .sd-pct-badge { font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 700; }
    .sd-bar-bg { height: 10px; background: #f1f5f9; border-radius: 100px; overflow: hidden; }
    .sd-bar-fill { height: 100%; border-radius: 100px; transition: width 1s cubic-bezier(0.4, 0, 0.2, 1); position: relative; }
    .sd-bar-fill::after { content:''; position:absolute; top:0; left:0; right:0; bottom:0; background:linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%); }
    .sd-detail { display: flex; gap: 12px; margin-top: 6px; font-size: 13px; color: var(--text-muted); }
  </style>

  <div class="premium-page-header">
    <div>
      <h2 class="pph-title">My Progress Overview</h2>
      <p class="pph-subtitle">In-depth breakdown of your academic performance</p>
    </div>
    <a href="{{ route('dashboard.student') }}" class="btn btn-outline" style="padding:10px 16px; background:#fff;">← Back to Dashboard</a>
  </div>

  <div class="premium-data-card" style="padding:0;">
    <div class="pdc-header">
      <h3 class="pdc-title"><span>📊</span> Performance Analytics</h3>
    </div>
    <div style="padding: 24px 32px 32px;">
      <canvas id="progressChart" style="max-height:300px;"></canvas>
    </div>
  </div>

  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap:32px; margin-bottom:32px;">
    <div class="premium-data-card" style="margin-bottom:0; padding:0;">
      <div class="pdc-header" style="margin-bottom: 24px;">
        <h3 class="pdc-title"><span>📚</span> Subject-wise Breakdown</h3>
      </div>
      @forelse($subjectBreakdown as $s)
        <div class="sd-subject-item">
          <div class="sd-subject-row">
            <span class="sd-subject-name">{{ $s['name'] }}</span>
            <span class="sd-pct-badge" style="color:{{ $s['color'] }};">{{ $s['pct'] }}%</span>
          </div>
          <div class="sd-bar-bg">
            <div class="sd-bar-fill" style="width:{{ $s['pct'] }}%;background:{{ $s['color'] }}; box-shadow: 0 0 10px {{ $s['color'] }}40;"></div>
          </div>
          <div class="sd-detail">
            <span>{{ $s['obtained'] }} / {{ $s['max'] }} total marks</span>
          </div>
        </div>
      @empty
        <div style="padding:48px 32px; text-align:center;">
          <div style="width:64px; height:64px; border-radius:50%; background:#f1f5f9; color:var(--text-muted); display:flex; align-items:center; justify-content:center; margin: 0 auto 16px;">
            <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
          </div>
          <p style="color:var(--text-muted); font-size:15px;">No subject data available.</p>
        </div>
      @endforelse
    </div>

    <div class="premium-data-card" style="margin-bottom:0; padding:0;">
      <div class="pdc-header">
        <h3 class="pdc-title"><span>📝</span> Exam History</h3>
      </div>
      <div style="overflow-x:auto;">
        <table class="premium-table">
          <thead>
            <tr>
              <th>Subject</th>
              <th>Term & Exam</th>
              <th>Score</th>
              <th>Progress</th>
            </tr>
          </thead>
          <tbody>
            @forelse($marks as $m)
              @php 
                $pct = $m->max_marks > 0 ? round(($m->marks_obtained / $m->max_marks) * 100, 1) : 0;
                $color = $pct >= 75 ? 'var(--success)' : ($pct >= 40 ? 'var(--warning)' : 'var(--error)');
                $bg_color = $pct >= 75 ? 'rgba(0,196,140,0.1)' : ($pct >= 40 ? 'rgba(245,158,11,0.1)' : 'rgba(255,82,82,0.1)');
              @endphp
              <tr>
                <td>
                  <div style="font-weight:600; color:#111827; font-size:15px;">{{ $m->subject->name ?? '—' }}</div>
                </td>
                <td>
                  <div style="color:#111827; font-weight:500;">{{ $m->term }}</div>
                  <div style="font-size:12px; color:var(--text-muted); margin-top:2px;">{{ $m->exam_type }}</div>
                </td>
                <td style="color:var(--text-muted);">
                  <strong style="color:#111827;">{{ $m->marks_obtained }}</strong> / {{ $m->max_marks }}
                </td>
                <td>
                  <div style="display:flex; align-items:center; gap:12px;">
                    <span style="font-family:'Poppins',sans-serif; font-size:15px; font-weight:700; color:{{ $color }}; min-width:44px;">{{ $pct }}%</span>
                    <div style="width:70px; height:6px; border-radius:100px; background:{{ $bg_color }}; overflow:hidden;">
                      <div style="width:{{ $pct }}%; height:100%; background:{{ $color }}; border-radius:100px;"></div>
                    </div>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" style="padding:64px 32px; text-align:center;">
                  <div style="display:flex; flex-direction:column; align-items:center;">
                    <div style="width:64px; height:64px; border-radius:50%; background:#f1f5f9; color:var(--text-muted); display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
                      <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <p style="color:var(--text-muted);">No exam records found.</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      @if($subjectBreakdown->count() > 0)
        (function () {
          const ctx = document.getElementById('progressChart');
          if (!ctx) return;
          new Chart(ctx, {
            type: 'bar',
            data: {
              labels: {!! json_encode($subjectBreakdown->pluck('name')) !!},
              datasets: [{
                label: 'Score (%)',
                data: {!! json_encode($subjectBreakdown->pluck('pct')) !!},
                backgroundColor: {!! json_encode($subjectBreakdown->pluck('color')->map(fn($c) => $c . '33')->values()) !!},
                borderColor: {!! json_encode($subjectBreakdown->pluck('color')) !!},
                borderWidth: 2, 
                borderRadius: { topLeft: 8, topRight: 8, bottomLeft: 0, bottomRight: 0 },
                borderSkipped: false
              }]
            },
            options: {
              responsive: true, 
              maintainAspectRatio: false,
              plugins: { 
                legend: { display: false },
                tooltip: {
                  backgroundColor: '#0f172a',
                  padding: 12,
                  titleFont: { size: 14, weight: 'bold', family: "'Inter', sans-serif" },
                  bodyFont: { size: 13, family: "'Inter', sans-serif" },
                  callbacks: { label: (ctx) => ` Score: ${ctx.raw}%` }
                }
              },
              scales: {
                y: { 
                  beginAtZero: true, 
                  max: 100, 
                  ticks: { callback: v => v + '%', font: { family: "'Inter', sans-serif" } }, 
                  grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false } 
                },
                x: { 
                  grid: { display: false, drawBorder: false },
                  ticks: { font: { family: "'Inter', sans-serif", weight: '600' } }
                }
              },
              animation: { duration: 1500, easing: 'easeOutQuart' }
            }
          });
        })();
      @endif
    </script>
  @endpush
</x-app-layout>