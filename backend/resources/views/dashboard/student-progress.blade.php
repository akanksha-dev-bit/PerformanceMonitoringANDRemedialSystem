<x-app-layout>
  <x-slot name="title">My Progress</x-slot>

  @push('styles')
    <style>
      .sd-wrap {
        max-width: 1400px;
        margin: 0 auto;
        padding: 14px 18px 18px;
        display: flex;
        flex-direction: column;
        gap: 14px;
      }

      .sd-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid rgba(0, 0, 0, 0.06);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
      }

      .sd-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 18px;
      }

      .sd-subject-item {
        margin-bottom: 16px;
      }

      .sd-subject-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
      }

      .sd-subject-name {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
      }

      .sd-pct-badge {
        font-size: 12px;
        font-weight: 700;
        padding: 2px 10px;
        border-radius: 100px;
      }

      .sd-bar-bg {
        height: 10px;
        background: #f1f5f9;
        border-radius: 100px;
        overflow: hidden;
      }

      .sd-bar-fill {
        height: 100%;
        border-radius: 100px;
        transition: width 1s ease;
      }

      .sd-detail {
        display: flex;
        gap: 12px;
        margin-top: 6px;
        font-size: 12px;
        color: #94a3b8;
      }
    </style>
  @endpush

  <div class="sd-wrap">


    <div class="sd-card">
      <div class="sd-card-title"><span>📊</span> Full Progress — {{ $studentProfile->name }}</div>
      <canvas id="progressChart" style="max-height:300px;"></canvas>
    </div>

    <div class="sd-card">
      <div class="sd-card-title"><span>📚</span> Subject-wise Breakdown</div>
      @forelse($subjectBreakdown as $s)
        <div class="sd-subject-item">
          <div class="sd-subject-row">
            <span class="sd-subject-name">{{ $s['name'] }}</span>
            <span class="sd-pct-badge"
              style="background:{{ $s['color'] }}20;color:{{ $s['color'] }};">{{ $s['pct'] }}%</span>
          </div>
          <div class="sd-bar-bg">
            <div class="sd-bar-fill" style="width:{{ $s['pct'] }}%;background:{{ $s['color'] }};"></div>
          </div>
          <div class="sd-detail">
            <span>{{ $s['obtained'] }} / {{ $s['max'] }} marks</span>
          </div>
        </div>
      @empty
        <p style="color:#94a3b8;text-align:center;padding:24px;">No subject data available.</p>
      @endforelse
    </div>

    <div class="sd-card">
      <div class="sd-card-title"><span>📝</span> All Exam Records</div>
      <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
          <thead>
            <tr style="background:#f8fafc;">
              <th style="padding:10px 14px;text-align:left;color:#94a3b8;font-size:11px;text-transform:uppercase;">
                Subject</th>
              <th style="padding:10px 14px;text-align:left;color:#94a3b8;font-size:11px;text-transform:uppercase;">Term
              </th>
              <th style="padding:10px 14px;text-align:left;color:#94a3b8;font-size:11px;text-transform:uppercase;">Exam
              </th>
              <th style="padding:10px 14px;text-align:left;color:#94a3b8;font-size:11px;text-transform:uppercase;">Score
              </th>
              <th style="padding:10px 14px;text-align:left;color:#94a3b8;font-size:11px;text-transform:uppercase;">%
              </th>
            </tr>
          </thead>
          <tbody>
            @forelse($marks as $m)
              @php $pct = $m->max_marks > 0 ? round(($m->marks_obtained / $m->max_marks) * 100, 1) : 0;
              $c = $pct >= 60 ? '#22c55e' : ($pct >= 40 ? '#f59e0b' : '#ef4444'); @endphp
              <tr style="border-bottom:1px solid #f1f5f9;">
                <td style="padding:12px 14px;font-weight:600;">{{ $m->subject->name ?? '—' }}</td>
                <td style="padding:12px 14px;color:#64748b;">{{ $m->term }}</td>
                <td style="padding:12px 14px;color:#64748b;">{{ $m->exam_type }}</td>
                <td style="padding:12px 14px;">{{ $m->marks_obtained }} / {{ $m->max_marks }}</td>
                <td style="padding:12px 14px;"><span style="font-weight:700;color:{{ $c }};">{{ $pct }}%</span></td>
              </tr>
            @empty
              <tr>
                <td colspan="5" style="padding:32px;text-align:center;color:#94a3b8;">No exam records found.</td>
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
                borderWidth: 2, borderRadius: 8, borderSkipped: false
              }]
            },
            options: {
              responsive: true, maintainAspectRatio: true,
              plugins: { legend: { display: false } },
              scales: {
                y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' }, grid: { color: 'rgba(0,0,0,0.04)' } },
                x: { grid: { display: false } }
              }
            }
          });
        })();
      @endif
    </script>
  @endpush
</x-app-layout>