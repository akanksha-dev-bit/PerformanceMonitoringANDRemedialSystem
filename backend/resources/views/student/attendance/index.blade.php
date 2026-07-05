<x-app-layout>
  <x-slot name="title">My Attendance Analytics</x-slot>

  @push('styles')
  <style>
    .page-header {
        margin-bottom: 32px;
    }
    .page-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
        margin: 0 0 4px 0;
    }
    .page-subtitle {
        font-size: 15px;
        color: #64748b;
        margin: 0;
    }
    .premium-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }
    .premium-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 20px -2px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.02);
        padding: 24px;
    }
    .card-title {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 16px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .stat-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .stat-label {
        font-weight: 600;
        color: #475569;
        font-size: 14px;
    }
    .stat-value {
        font-weight: 700;
        font-size: 15px;
        color: #0f172a;
    }
    .progress-bar-container {
        width: 100%;
        background: #e2e8f0;
        height: 8px;
        border-radius: 100px;
        overflow: hidden;
        margin-top: 6px;
    }
    .progress-bar-fill {
        height: 100%;
        border-radius: 100px;
        transition: width 0.3s;
    }
    .trend-bar-chart {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        height: 150px;
        margin-top: 24px;
        padding-bottom: 8px;
        border-bottom: 1px solid #cbd5e1;
    }
    .trend-bar-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 45px;
    }
    .trend-bar {
        width: 24px;
        background: linear-gradient(to top, #6C5CE7, #8072F1);
        border-radius: 6px 6px 0 0;
        transition: height 0.3s ease;
    }
    .trend-label {
        font-size: 10px;
        color: #64748b;
        font-weight: 700;
        margin-top: 6px;
        text-align: center;
        white-space: nowrap;
    }
  </style>
  @endpush

  <div class="page-header">
    <h2 class="page-title">My Attendance Analytics</h2>
    <p class="page-subtitle">Track your presence, view subject attendance rates, and analyze trends</p>
  </div>

  @if($alertMessage)
    <div style="padding: 16px; background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a; margin-bottom: 24px; border-radius: 12px; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      {{ $alertMessage }}
    </div>
  @endif

  <!-- Overall Stat Banner -->
  <div class="premium-card" style="display:flex; align-items:center; gap:24px; margin-bottom:32px; background: linear-gradient(135deg, #6C5CE7, #5A4BD6); color: #fff;">
    <div style="background: rgba(255,255,255,0.1); border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; font-size: 32px;">
      📅
    </div>
    <div>
      <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; opacity:0.8;">Overall Attendance</div>
      <h3 style="font-size:40px; font-weight:800; margin:4px 0 0 0; line-height:1;">{{ $overallAttendance }}%</h3>
      <p style="margin:8px 0 0 0; font-size:13px; opacity:0.9;">
        @if($overallAttendance >= 75)
          Keep it up! Your attendance is in the safe zone.
        @else
          Warning: Your attendance is below the required 75% limit.
        @endif
      </p>
    </div>
  </div>

  <div class="premium-grid">
    <!-- Subject breakdown -->
    <div class="premium-card">
      <h3 class="card-title">Subject-wise Analytics</h3>
      <div class="stat-list">
        @foreach($subjectBreakdown as $breakdown)
          <div>
            <div class="stat-item">
              <span class="stat-label">{{ $breakdown['subject'] }}</span>
              <span class="stat-value" style="color: {{ $breakdown['color'] }};">
                {{ $breakdown['percentage'] }}% ({{ $breakdown['present'] }}/{{ $breakdown['total'] }})
              </span>
            </div>
            <div class="progress-bar-container">
              <div class="progress-bar-fill" style="width: {{ $breakdown['percentage'] }}%; background: {{ $breakdown['color'] }};"></div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <!-- Monthly trend bar chart -->
    <div class="premium-card">
      <h3 class="card-title">Monthly Trends</h3>
      <p style="color:#64748b; font-size:13px; margin: 0 0 16px 0;">Average presence rate for the past 5 months</p>
      
      <div class="trend-bar-chart">
        @foreach($monthlyTrends as $trend)
          <div class="trend-bar-wrapper">
            <span style="font-size:10px; font-weight:700; color:#475569; margin-bottom:4px;">{{ $trend['pct'] }}%</span>
            <div class="trend-bar" style="height: {{ max(10, $trend['pct'] * 1.2) }}px;"></div>
            <div class="trend-label">{{ $trend['month'] }}</div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- Absent logs history -->
  <div class="premium-card">
    <h3 class="card-title">Absence Log History</h3>
    <p style="color:#64748b; font-size:13px; margin:0 0 20px 0;">Lists all sessions recorded as absent. Keeping track helps in submitting excuses.</p>

    <div style="overflow-x:auto;">
      <table style="width:100%; border-collapse:collapse; text-align:left;">
        <thead>
          <tr style="border-bottom:1px solid #cbd5e1; color:#64748b; font-size:12px; font-weight:700; text-transform:uppercase;">
            <th style="padding:12px 16px;">Date</th>
            <th style="padding:12px 16px;">Subject</th>
            <th style="padding:12px 16px;">Period</th>
            <th style="padding:12px 16px;">Teacher</th>
          </tr>
        </thead>
        <tbody>
          @forelse($absentHistory as $absent)
            <tr style="border-bottom:1px solid #f1f5f9; font-size:14px; color:#475569;">
              <td style="padding:12px 16px; font-weight:700;">{{ \Carbon\Carbon::parse($absent->session->date)->format('M d, Y') }}</td>
              <td style="padding:12px 16px; color:#ef4444; font-weight:600;">{{ $absent->session->subject->name ?? 'N/A' }}</td>
              <td style="padding:12px 16px;">Period {{ $absent->session->period_number }}</td>
              <td style="padding:12px 16px;">{{ $absent->session->teacher->name ?? 'N/A' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" style="padding:32px; text-align:center; color:#94a3b8;">🎉 Perfect attendance record! You haven't been absent.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</x-app-layout>
