<x-app-layout>
  <x-slot name="title">Slow Learners</x-slot>

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
    
    .kpi-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 24px;
      margin-bottom: 32px;
    }
    .premium-kpi {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 32px;
      display: flex;
      align-items: center;
      gap: 24px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.03);
      position: relative;
      overflow: hidden;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .premium-kpi:hover {
      transform: translateY(-4px);
      box-shadow: 0 15px 50px rgba(0,0,0,0.06);
    }
    .premium-kpi::after {
      content: ''; position: absolute; right: 0; top: 0; bottom: 0; width: 4px;
      background: var(--kpi-color);
    }
    .pkpi-icon {
      width: 72px; height: 72px; border-radius: 20px;
      display: flex; align-items: center; justify-content: center;
      background: var(--kpi-bg); color: var(--kpi-color);
    }
    .pkpi-val { font-family: 'Poppins', sans-serif; font-size: 40px; font-weight: 700; color: #111827; line-height: 1; margin-bottom: 4px; }
    .pkpi-lbl { font-size: 14px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }

    .premium-data-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      box-shadow: 0 10px 40px rgba(0,0,0,0.03);
      overflow: hidden;
      margin-bottom: 32px;
    }
    .pdc-header {
      padding: 24px 32px;
      border-bottom: 1px solid rgba(0,0,0,0.04);
      display: flex; align-items: center; gap: 16px;
    }
    .pdc-title { font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 700; color: #111827; }
    .pdc-subtitle { font-size: 13px; color: var(--text-muted); }
    
    .premium-table { width: 100%; border-collapse: collapse; }
    .premium-table th { text-align: left; padding: 16px 32px; font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; background: #fdfdfd; border-bottom: 1px solid var(--border); }
    .premium-table td { padding: 20px 32px; font-size: 14px; border-bottom: 1px solid rgba(0,0,0,0.03); vertical-align: middle; }
    .premium-table tr:last-child td { border-bottom: none; }
    .premium-table tr:hover { background: rgba(108,92,231,0.02); }

    .st-cell { display: flex; align-items: center; gap: 16px; }
    .st-avatar { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; }
    .st-name { font-weight: 600; color: #111827; font-size: 15px; }
    .st-roll { font-size: 13px; color: var(--text-muted); margin-top: 2px; }
  </style>

  <div class="premium-page-header">
    <div>
      <h2 class="pph-title">Slow Learners Detection</h2>
      <p class="pph-subtitle">Automated identification of students requiring immediate academic support.</p>
    </div>
    <a href="{{ route('remedial.create') }}" class="btn btn-primary" id="assign-remedial-all-btn">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
      Assign Remedial
    </a>
  </div>

  {{-- Summary Cards --}}
  <div class="kpi-grid">
    <div class="premium-kpi" style="--kpi-color: var(--error); --kpi-bg: rgba(255,82,82,0.1);">
      <div class="pkpi-icon">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      </div>
      <div>
        <div class="pkpi-val">{{ $slowLearners->count() }}</div>
        <div class="pkpi-lbl">Critical Support</div>
      </div>
    </div>
    
    <div class="premium-kpi" style="--kpi-color: #D97706; --kpi-bg: rgba(245,158,11,0.1);">
      <div class="pkpi-icon">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      </div>
      <div>
        <div class="pkpi-val">{{ $atRisk->count() }}</div>
        <div class="pkpi-lbl">At Risk Watchlist</div>
      </div>
    </div>
  </div>

  {{-- Slow Learners Table --}}
  <div class="premium-data-card">
    <div class="pdc-header">
      <div style="width:12px; height:12px; border-radius:50%; background:var(--error); box-shadow: 0 0 10px var(--error);"></div>
      <div>
        <div class="pdc-title">Critical Support Required</div>
        <div class="pdc-subtitle">Students with an average score below 40% or failing in multiple subjects.</div>
      </div>
    </div>
    
    <div style="overflow-x:auto;">
      <table class="premium-table">
        <thead><tr><th>Student Info</th><th>Class</th><th>Overall Score</th><th>Total Marks</th><th>Interventions</th><th>Action</th></tr></thead>
        <tbody>
          @forelse($slowLearners as $student)
          <tr>
            <td>
              <div class="st-cell">
                <div class="st-avatar" style="background:rgba(255,82,82,0.1); color:var(--error);">{{ strtoupper(substr($student->name, 0, 2)) }}</div>
                <div>
                  <div class="st-name">{{ $student->name }}</div>
                  <div class="st-roll">{{ $student->roll_no }}</div>
                </div>
              </div>
            </td>
            <td><span class="badge badge-muted">{{ $student->class }}{{ $student->section ? '-'.$student->section : '' }}</span></td>
            <td>
              <div style="font-family:'Poppins',sans-serif; font-size:20px; font-weight:700; color:var(--error); line-height:1;">{{ $student->average_percentage }}%</div>
            </td>
            <td style="color:var(--text-muted);">{{ $student->marks->sum('marks_obtained') }} / {{ $student->marks->sum('max_marks') }}</td>
            <td>
              <span class="badge badge-{{ $student->remedialActions->count() > 0 ? 'success' : 'error' }}" style="padding:6px 12px;">
                {{ $student->remedialActions->count() }} assigned
              </span>
            </td>
            <td>
              <div style="display:flex; gap:8px;">
                <a href="{{ route('students.show', $student) }}" class="btn btn-outline" style="padding:6px 12px; font-size:12px;" id="slow-view-{{ $student->id }}">Profile</a>
                <a href="{{ route('remedial.create', ['student_id' => $student->id]) }}" class="btn btn-primary" style="padding:6px 12px; font-size:12px;" id="slow-remedial-{{ $student->id }}">Assign</a>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" style="text-align:center; padding:64px;">
              <div style="display:flex; flex-direction:column; align-items:center;">
                <div style="width:80px; height:80px; border-radius:50%; background:rgba(0,196,140,0.1); color:var(--success); display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
                  <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 style="font-size:18px; font-weight:600; color:#111827;">All Clear!</h3>
                <p style="color:var(--text-muted); margin-top:4px;">No students are currently in the critical support list. Keep up the excellent work.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- At Risk Table --}}
  <div class="premium-data-card">
    <div class="pdc-header">
      <div style="width:12px; height:12px; border-radius:50%; background:#D97706; box-shadow: 0 0 10px #D97706;"></div>
      <div>
        <div class="pdc-title">At Risk Watchlist</div>
        <div class="pdc-subtitle">Students scoring between 40% and 50% requiring close monitoring.</div>
      </div>
    </div>
    
    <div style="overflow-x:auto;">
      <table class="premium-table">
        <thead><tr><th>Student Info</th><th>Class</th><th>Overall Score</th><th>Action</th></tr></thead>
        <tbody>
          @forelse($atRisk as $student)
          <tr>
            <td>
              <div class="st-cell">
                <div class="st-avatar" style="background:rgba(245,158,11,0.1); color:#D97706;">{{ strtoupper(substr($student->name, 0, 2)) }}</div>
                <div>
                  <div class="st-name">{{ $student->name }}</div>
                  <div class="st-roll">{{ $student->roll_no }}</div>
                </div>
              </div>
            </td>
            <td><span class="badge badge-muted">{{ $student->class }}{{ $student->section ? '-'.$student->section : '' }}</span></td>
            <td>
              <div style="font-family:'Poppins',sans-serif; font-size:20px; font-weight:700; color:#D97706; line-height:1;">{{ $student->average_percentage }}%</div>
            </td>
            <td><a href="{{ route('performance.show', $student) }}" class="btn btn-outline" style="padding:6px 12px; font-size:12px;">View Report</a></td>
          </tr>
          @empty
          <tr>
            <td colspan="4" style="text-align:center; padding:48px; color:var(--text-muted);">
              No at-risk students found on the watchlist.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</x-app-layout>
