<x-app-layout>
  <x-slot name="title">{{ $student->name }}</x-slot>

  <style>
    .student-header-card {
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
    .student-header-card::before {
      content: '';
      position: absolute;
      top: 0; right: 0; width: 300px; height: 100%;
      background: radial-gradient(circle at top right, rgba(108,92,231,0.05), transparent 70%);
      pointer-events: none;
    }
    .sh-title { font-family: 'Poppins', sans-serif; font-size: 32px; font-weight: 700; color: #111827; letter-spacing: -0.02em; line-height: 1.2; }
    .sh-subtitle { font-size: 15px; color: var(--text-muted); margin-top: 4px; display: flex; align-items: center; gap: 8px; }
    .sh-actions { display: flex; gap: 12px; z-index: 1; }

    .grid-container {
      display: grid;
      grid-template-columns: 320px 1fr;
      gap: 32px;
      margin-bottom: 32px;
    }
    
    .profile-card {
      background: #fff;
      border-radius: var(--radius-lg);
      border: 1px solid var(--border);
      box-shadow: var(--shadow-sm);
      overflow: hidden;
    }
    .profile-card-top {
      background: var(--gradient-primary);
      height: 100px;
      position: relative;
    }
    .profile-card-content {
      padding: 0 24px 24px;
      text-align: center;
    }
    .profile-avatar-lg {
      width: 88px; height: 88px;
      border-radius: 50%;
      background: #fff;
      color: var(--primary);
      font-size: 28px; font-weight: 700;
      display: flex; align-items: center; justify-content: center;
      margin: -44px auto 16px;
      border: 4px solid #fff;
      box-shadow: 0 8px 24px rgba(108,92,231,0.15);
      position: relative;
    }
    .profile-name { font-size: 20px; font-weight: 700; color: #111827; }
    .profile-email { font-size: 14px; color: var(--text-muted); margin-top: 2px; }
    
    .info-list { margin-top: 24px; text-align: left; border-top: 1px solid rgba(0,0,0,0.04); padding-top: 20px; }
    .info-item { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed rgba(0,0,0,0.05); font-size: 14px; }
    .info-item:last-child { border-bottom: none; }
    .info-label { color: var(--text-muted); font-weight: 500; }
    .info-val { font-weight: 600; color: #111827; }

    .perf-card {
      background: #fff;
      border-radius: var(--radius-lg);
      border: 1px solid var(--border);
      padding: 32px;
      box-shadow: var(--shadow-sm);
      display: flex; align-items: center; gap: 32px;
    }
    .perf-score-wrap {
      width: 120px; height: 120px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      position: relative;
      background: conic-gradient(var(--score-color) var(--score-deg), #f1f5f9 0deg);
    }
    .perf-score-inner {
      width: 104px; height: 104px;
      background: #fff; border-radius: 50%;
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
    .perf-score-inner .val { font-family: 'Poppins', sans-serif; font-size: 28px; font-weight: 700; line-height: 1; color: var(--score-color); }
    .perf-score-inner .lbl { font-size: 11px; color: var(--text-muted); font-weight: 600; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
    .perf-details { flex: 1; }
    
    .premium-table { width: 100%; border-collapse: collapse; }
    .premium-table th { text-align: left; padding: 16px; font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border); }
    .premium-table td { padding: 16px; font-size: 14px; border-bottom: 1px solid rgba(0,0,0,0.03); vertical-align: middle; }
    .premium-table tr:last-child td { border-bottom: none; }
    .premium-table tr:hover { background: rgba(108,92,231,0.02); }
    
    .subject-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: var(--primary-light); color: var(--primary); margin-right: 12px; }

    @media (max-width: 992px) {
      .grid-container { grid-template-columns: 1fr; }
      .student-header-card { flex-direction: column; align-items: flex-start; gap: 20px; }
    }
  </style>

  <div class="student-header-card">
    <div>
      <h2 class="sh-title">{{ $student->name }}</h2>
      <p class="sh-subtitle">
        <span class="badge badge-primary">Class {{ $student->class }}{{ $student->section ? '-'.$student->section : '' }}</span>
        &bull; Roll No: <strong style="color:#111827">{{ $student->roll_no }}</strong>
      </p>
    </div>
    <div class="sh-actions">
      <a href="{{ route('students.edit', $student) }}" class="btn btn-outline" id="edit-student-btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
        Edit Profile
      </a>
      <a href="{{ route('marks.create', ['student_id' => $student->id]) }}" class="btn btn-outline" id="add-marks-btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Add Marks
      </a>
      <a href="{{ route('remedial.create', ['student_id' => $student->id]) }}" class="btn btn-primary" id="assign-remedial-btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        Assign Remedial
      </a>
    </div>
  </div>

  <div class="grid-container">
    {{-- Left: Profile Card --}}
    <div class="profile-card">
      <div class="profile-card-top"></div>
      <div class="profile-card-content">
        <div class="profile-avatar-lg">{{ strtoupper(substr($student->name, 0, 2)) }}</div>
        <div class="profile-name">{{ $student->name }}</div>
        <div class="profile-email">{{ $student->email ?? 'No email provided' }}</div>
        
        <div style="margin-top:16px;">
          @if(!$student->has_marks)
            <span class="badge badge-muted">Not Evaluated</span>
          @elseif($student->is_slow_learner)
            <span class="badge badge-error">⚠ Slow Learner</span>
          @elseif($student->average_percentage >= 60)
            <span class="badge badge-success">✓ Good Performance</span>
          @else
            <span class="badge badge-warning">~ At Risk</span>
          @endif
        </div>

        <div class="info-list">
          @foreach([['Roll No', $student->roll_no], ['Gender', ucfirst($student->gender ?? '—')], ['DOB', $student->dob ? $student->dob->format('d M Y') : '—'], ['Phone', $student->phone ?? '—'], ['Guardian', $student->guardian_name ?? '—']] as [$label, $val])
          <div class="info-item">
            <span class="info-label">{{ $label }}</span>
            <span class="info-val">{{ $val }}</span>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- Right: Content --}}
    <div style="display:flex; flex-direction:column; gap:32px;">
      
      {{-- Overall Performance --}}
      <div class="perf-card">
        @if($student->has_marks)
            @php 
              $avg = $student->average_percentage; 
              $color = $student->performance_color; 
              $deg = ($avg / 100) * 360;
            @endphp
            <div class="perf-score-wrap" style="--score-color: {{ $color }}; --score-deg: {{ $deg }}deg;">
              <div class="perf-score-inner">
                <span class="val">{{ $avg }}%</span>
                <span class="lbl">Overall</span>
              </div>
            </div>
            <div class="perf-details">
              <h3 style="font-size:18px; font-weight:700; margin-bottom:8px; color:#111827;">Academic Performance</h3>
              <p style="font-size:14px; color:var(--text-muted); line-height:1.5;">
                {{ $student->name }} is currently showing <strong>{{ strtolower($student->performance_label) }}</strong> results. 
                Based on {{ $student->marks->count() }} total examination records.
              </p>
            </div>
        @else
            <div style="width:100%; text-align:center; padding:20px;">
                <div style="width:64px; height:64px; background:#f1f5f9; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; color:var(--text-muted);">
                  <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 style="font-size:16px; font-weight:600; color:#111827; margin-bottom:4px;">No Performance Data</h3>
                <p style="font-size:14px; color:var(--text-muted); margin-bottom:16px;">Add the first marks entry to generate performance insights.</p>
                <a href="{{ route('marks.create', ['student_id' => $student->id]) }}" class="btn btn-primary">+ Add First Mark</a>
            </div>
        @endif
      </div>

      {{-- Subject marks table --}}
      <div class="card" style="padding:0; overflow:hidden;">
        <div style="padding: 24px; border-bottom: 1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
          <h3 class="card-title" style="margin:0;">Subject Records</h3>
        </div>
        <div class="table-wrapper" style="border:none; border-radius:0;">
          <table class="premium-table">
            <thead>
              <tr><th>Subject</th><th>Examination</th><th>Score</th><th>Result</th></tr>
            </thead>
            <tbody>
              @forelse($student->marks as $mark)
              <tr>
                <td>
                  <div style="display:flex; align-items:center;">
                    <div class="subject-icon">
                      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    </div>
                    <div>
                      <div style="font-weight:600; color:#111827;">{{ $mark->subject->name ?? '—' }}</div>
                    </div>
                  </div>
                </td>
                <td><span class="badge badge-muted">{{ str_replace('_', ' ', ucfirst($mark->exam_type)) }}</span></td>
                <td>
                  <div style="display:flex; flex-direction:column; gap:4px;">
                    <span style="font-weight:700; color:#111827;">{{ $mark->percentage }}%</span>
                    <span style="font-size:12px; color:var(--text-muted);">{{ $mark->marks_obtained }} / {{ $mark->max_marks }}</span>
                  </div>
                </td>
                <td>
                  @if($mark->is_pass)
                    <span class="badge badge-success">Pass</span>
                  @else
                    <span class="badge badge-error">Fail</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr><td colspan="4" style="text-align:center; padding:32px; color:var(--text-muted);">No subjects evaluated yet.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      {{-- Remedial Actions --}}
      <div class="card" style="padding:0; overflow:hidden;">
        <div style="padding: 24px; border-bottom: 1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
          <h3 class="card-title" style="margin:0;">Intervention Plans</h3>
          <a href="{{ route('remedial.create', ['student_id' => $student->id]) }}" class="btn btn-outline" style="padding:6px 14px; font-size:13px;">+ Add Plan</a>
        </div>
        <div class="table-wrapper" style="border:none; border-radius:0;">
          <table class="premium-table">
            <thead><tr><th>Title</th><th>Type</th><th>Scheduled</th><th>Status</th></tr></thead>
            <tbody>
              @forelse($student->remedialActions as $action)
              <tr>
                <td style="font-weight:600; color:#111827;">{{ $action->title }}</td>
                <td><span class="badge badge-primary">{{ str_replace('_', ' ', ucfirst($action->action_type)) }}</span></td>
                <td>{{ $action->scheduled_date ? $action->scheduled_date->format('d M Y') : '—' }}</td>
                <td>
                  <span class="badge" style="background:{{ $action->status_badge_color }}15; color:{{ $action->status_badge_color }};">
                    {{ ucfirst(str_replace('_', ' ', $action->status)) }}
                  </span>
                </td>
              </tr>
              @empty
              <tr><td colspan="4" style="text-align:center; padding:32px; color:var(--text-muted);">No active interventions.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

</x-app-layout>
