<x-app-layout>
  <x-slot name="title">Attendance & PMRS Intelligence</x-slot>

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
    .badge {
        padding: 4px 10px;
        border-radius: 100px;
        font-size: 12px;
        font-weight: 700;
    }
    .badge-danger { background: rgba(239, 68, 68, 0.08); color: #ef4444; }
    .badge-success { background: rgba(34, 197, 94, 0.08); color: #22c55e; }
    .badge-warning { background: rgba(245, 158, 11, 0.08); color: #f59e0b; }
    
    .danger-alert-box {
        background: #fef2f2;
        border: 1px solid #fca5a5;
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 16px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }
  </style>
  @endpush

  <div class="page-header">
    <h2 class="page-title">Attendance & PMRS Intelligence</h2>
    <p class="page-subtitle">School-wide attendance metrics, risk analytics, and automated intervention highlights</p>
  </div>

  <!-- Row 1: High Risk & Alert Lists (PMRS Killer Feature) -->
  <div class="premium-card" style="margin-bottom: 32px; border-left: 6px solid #ef4444;">
    <h3 class="card-title" style="color: #ef4444;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      PMRS High-Risk Student Interventions Recommended
    </h3>
    <p style="color:#64748b; font-size:14px; margin:-8px 0 20px 0;">
      Students identified automatically with <strong>Low Attendance (&lt;75%)</strong> AND <strong>Low Academic Marks (&lt;40%)</strong>. Immediate remedial actions are advised.
    </p>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
      @forelse($highRiskStudents as $student)
        <div class="danger-alert-box">
          <div style="background: rgba(239, 68, 68, 0.1); padding: 8px; border-radius: 8px; color: #ef4444; flex-shrink:0;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
          </div>
          <div>
            <div style="font-weight:700; color:#0f172a; font-size:14px;">{{ $student->name }}</div>
            <div style="font-size:12px; color:#64748b; margin-top:2px;">{{ $student->classroom->display_name ?? 'Unassigned Section' }}</div>
            
            <div style="display:flex; gap:12px; margin-top:10px;">
              <div>
                <span style="font-size:11px; color:#64748b; font-weight:700; text-transform:uppercase;">Attendance</span>
                <div style="font-weight:800; color:#ef4444; font-size:15px;">{{ $student->overall_attendance }}%</div>
              </div>
              <div>
                <span style="font-size:11px; color:#64748b; font-weight:700; text-transform:uppercase;">Average Marks</span>
                <div style="font-weight:800; color:#ef4444; font-size:15px;">{{ $student->average_percentage }}%</div>
              </div>
            </div>

            <a href="{{ route('remedial.create', ['student_id' => $student->id]) }}" class="btn-solid-primary" style="padding: 6px 12px; font-size: 11px; border-radius: 8px; margin-top: 12px; box-shadow:none;">
              Trigger Remedial Plan
            </a>
          </div>
        </div>
      @empty
        <div style="grid-column: 1 / -1; padding: 24px; text-align: center; color: #64748b; background: #f8fafc; border-radius: 12px;">
          🎉 No high-risk student warnings at this time. All students meeting basic requirements.
        </div>
      @endforelse
    </div>
  </div>

  <div class="premium-grid">
    <!-- Classroom Attendance Trends -->
    <div class="premium-card">
      <h3 class="card-title">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/></svg>
        Classroom Attendance Rates
      </h3>
      <div class="stat-list">
        @foreach($classroomStats as $stat)
          <div>
            <div class="stat-item">
              <span class="stat-label">{{ $stat['name'] }}</span>
              <span class="stat-value {{ $stat['pct'] < 75 ? 'text-red-500' : 'text-green-500' }}" style="color: {{ $stat['pct'] < 75 ? '#ef4444' : '#22c55e' }};">
                {{ $stat['pct'] }}%
              </span>
            </div>
            <div class="progress-bar-container">
              <div class="progress-bar-fill" style="width: {{ $stat['pct'] }}%; background: {{ $stat['pct'] < 75 ? '#ef4444' : '#22c55e' }};"></div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <!-- Subject Absentee Rates -->
    <div class="premium-card">
      <h3 class="card-title">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        Subject Absentee Percentage
      </h3>
      <div class="stat-list">
        @foreach($subjectStats as $stat)
          <div>
            <div class="stat-item">
              <span class="stat-label">{{ $stat['name'] }}</span>
              <span class="stat-value" style="color: #475569;">{{ $stat['absentee_pct'] }}% Absences</span>
            </div>
            <div class="progress-bar-container">
              <div class="progress-bar-fill" style="width: {{ $stat['absentee_pct'] }}%; background: #64748b;"></div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <div class="premium-grid">
    <!-- Low Attendance Students (<75%) -->
    <div class="premium-card">
      <h3 class="card-title" style="color: #f59e0b;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        Attendance Alert List (&lt; 75%)
      </h3>
      <div style="display:flex; flex-direction:column; gap:12px;">
        @forelse($lowAttendanceStudents as $student)
          <div style="display:flex; justify-content:space-between; align-items:center; background:#f8fafc; padding:12px; border-radius:10px;">
            <div>
              <div style="font-weight:700; color:#0f172a; font-size:13px;">{{ $student->name }}</div>
              <div style="font-size:11px; color:#64748b; margin-top:2px;">{{ $student->classroom->display_name ?? 'N/A' }}</div>
            </div>
            <span class="badge badge-warning">{{ $student->overall_attendance }}%</span>
          </div>
        @empty
          <div style="padding: 16px; text-align:center; color:#94a3b8; font-size:13px;">No students below 75% attendance! Keep up the good work.</div>
        @endforelse
      </div>
    </div>

    <!-- Teacher Completion Rate -->
    <div class="premium-card">
      <h3 class="card-title">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        Teacher Attendance Record Submissions
      </h3>
      <div style="display:flex; flex-direction:column; gap:12px;">
        @foreach($teacherReports as $report)
          <div style="display:flex; justify-content:space-between; align-items:center; background:#f8fafc; padding:12px; border-radius:10px;">
            <div>
              <div style="font-weight:700; color:#0f172a; font-size:13px;">{{ $report['name'] }}</div>
              <div style="font-size:11px; color:#64748b; margin-top:2px;">{{ $report['marked'] }} / {{ $report['slots'] }} periods completed</div>
            </div>
            <span class="badge {{ $report['completion_rate'] >= 90 ? 'badge-success' : 'badge-warning' }}">{{ $report['completion_rate'] }}%</span>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</x-app-layout>
