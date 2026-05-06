<x-app-layout>
  <x-slot name="title">Performance</x-slot>

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
    }
    .pph-title { font-family: 'Poppins', sans-serif; font-size: 28px; font-weight: 700; color: #111827; letter-spacing: -0.02em; }
    .pph-subtitle { font-size: 15px; color: var(--text-muted); margin-top: 4px; }
    
    .premium-data-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      box-shadow: 0 10px 40px rgba(0,0,0,0.03);
      overflow: hidden;
      margin-bottom: 32px;
    }
    
    .premium-table { width: 100%; border-collapse: collapse; }
    .premium-table th { text-align: left; padding: 16px 32px; font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; background: #fdfdfd; border-bottom: 1px solid var(--border); }
    .premium-table td { padding: 20px 32px; font-size: 14px; border-bottom: 1px solid rgba(0,0,0,0.03); vertical-align: middle; }
    .premium-table tr:last-child td { border-bottom: none; }
    .premium-table tr:hover { background: rgba(108,92,231,0.02); }

    .st-cell { display: flex; align-items: center; gap: 16px; }
    .st-avatar { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; background: var(--primary-light); color: var(--primary); }
    .st-name { font-weight: 600; color: #111827; font-size: 15px; }
    .st-roll { font-size: 13px; color: var(--text-muted); margin-top: 2px; }
  </style>

  <div class="premium-page-header">
    <div>
      <h2 class="pph-title">Performance Analysis</h2>
      <p class="pph-subtitle">Overview of all student academic performance</p>
    </div>
    <a href="{{ route('performance.slow-learners') }}" class="btn btn-primary" id="slow-learners-link">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
      View Slow Learners
    </a>
  </div>

  <div class="premium-data-card">
    <div style="overflow-x:auto;">
      <table class="premium-table">
        <thead>
          <tr><th>Student Info</th><th>Class</th><th>Total Marks</th><th>Average %</th><th>Performance</th><th>Action</th></tr>
        </thead>
        <tbody>
          @forelse($students as $student)
          @php
            $avg   = $student->average_percentage;
            $color = $avg >= 75 ? 'var(--success)' : ($avg >= 40 ? 'var(--warning)' : 'var(--error)');
            $bg_color = $avg >= 75 ? 'rgba(0,196,140,0.1)' : ($avg >= 40 ? 'rgba(245,158,11,0.1)' : 'rgba(255,82,82,0.1)');
          @endphp
          <tr>
            <td>
              <div class="st-cell">
                <div class="st-avatar" style="background:{{ $bg_color }}; color:{{ $color }};">{{ strtoupper(substr($student->name, 0, 2)) }}</div>
                <div>
                  <div class="st-name">{{ $student->name }}</div>
                  <div class="st-roll">{{ $student->roll_no }}</div>
                </div>
              </div>
            </td>
            <td><span class="badge badge-muted">{{ $student->class }}{{ $student->section ? '-'.$student->section : '' }}</span></td>
            <td style="color:var(--text-muted);">{{ $student->marks->sum('marks_obtained') }} / {{ $student->marks->sum('max_marks') }}</td>
            <td>
              <div style="display:flex; align-items:center; gap:12px;">
                <span style="font-family:'Poppins',sans-serif; font-size:16px; font-weight:700; color:{{ $color }}; min-width:44px;">{{ $avg }}%</span>
                <div class="progress-bar" style="width:80px; height:8px; border-radius:4px; background:{{ $bg_color }};">
                  <div class="progress-fill" style="width:{{ $avg }}%; background:{{ $color }}; border-radius:4px; box-shadow: 0 0 8px {{ $bg_color }};"></div>
                </div>
              </div>
            </td>
            <td>
              @if($student->is_slow_learner)
                <span class="badge badge-error" style="padding:6px 12px;">⚠ Slow Learner</span>
              @elseif($avg >= 75)
                <span class="badge badge-success" style="padding:6px 12px;">Excellent</span>
              @else
                <span class="badge badge-warning" style="padding:6px 12px;">Average</span>
              @endif
            </td>
            <td>
              <a href="{{ route('performance.show', $student) }}" class="btn btn-outline" style="padding:6px 12px; font-size:12px;" id="perf-view-{{ $student->id }}">Details</a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" style="text-align:center; padding:64px;">
              <div style="display:flex; flex-direction:column; align-items:center;">
                <div style="width:80px; height:80px; border-radius:50%; background:#f1f5f9; color:var(--text-muted); display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
                  <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 style="font-size:18px; font-weight:600; color:#111827;">No Performance Data</h3>
                <p style="color:var(--text-muted); margin-top:4px;">There are no students with recorded marks yet.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($students->hasPages())
    <div style="padding: 16px 32px; border-top: 1px solid var(--border);">
      {{ $students->links() }}
    </div>
    @endif
  </div>

</x-app-layout>
