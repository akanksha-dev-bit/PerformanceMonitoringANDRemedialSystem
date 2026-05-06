<x-app-layout>
  <x-slot name="title">Marks</x-slot>

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
    
    .premium-toolbar {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 20px 24px;
      margin-bottom: 24px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.02);
      display: flex;
      gap: 16px;
      align-items: flex-end;
      flex-wrap: wrap;
    }
    .toolbar-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }
    .toolbar-label {
      font-size: 12px;
      font-weight: 600;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    .toolbar-select {
      padding: 10px 36px 10px 16px;
      border-radius: 8px;
      border: 1px solid #d1d5db;
      background: #fdfdfd;
      font-size: 14px;
      color: #111827;
      min-width: 200px;
      outline: none;
      transition: all 0.2s;
    }
    .toolbar-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(108,92,231,0.1); }

    .premium-data-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      box-shadow: 0 10px 40px rgba(0,0,0,0.03);
      overflow: hidden;
    }
    
    .premium-table { width: 100%; border-collapse: collapse; }
    .premium-table th { text-align: left; padding: 16px 24px; font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; background: #fdfdfd; border-bottom: 1px solid var(--border); }
    .premium-table td { padding: 16px 24px; font-size: 14px; border-bottom: 1px solid rgba(0,0,0,0.03); vertical-align: middle; }
    .premium-table tr:last-child td { border-bottom: none; }
    .premium-table tr:hover { background: rgba(108,92,231,0.02); }

    .st-cell { display: flex; align-items: center; gap: 12px; }
    .st-avatar { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; background: var(--primary-light); color: var(--primary); }
    .st-name { font-weight: 600; color: #111827; font-size: 14px; }
    .st-roll { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
  </style>

  <div class="premium-page-header">
    <div>
      <h2 class="pph-title">{{ auth()->user()->isStudent() ? 'My Marks' : 'Marks Management' }}</h2>
      <p class="pph-subtitle">{{ auth()->user()->isStudent() ? 'View all your recorded academic performance' : 'View and manage all recorded marks' }}</p>
    </div>
    @if(!auth()->user()->isStudent())
    <a href="{{ route('marks.create') }}" class="btn btn-primary" id="add-marks-btn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18" style="margin-right:6px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Record Marks
    </a>
    @endif
  </div>

  {{-- Filters (Hide for students) --}}
  @if(!auth()->user()->isStudent())
  <form method="GET" class="premium-toolbar">
    <div class="toolbar-group">
      <label class="toolbar-label">Student</label>
      <select name="student_id" class="toolbar-select" id="marks-student-filter">
        <option value="">All Students</option>
        @foreach($students as $s)
          <option value="{{ $s->id }}" {{ request('student_id') == $s->id ? 'selected' : '' }}>{{ $s->name }} ({{ $s->roll_no }})</option>
        @endforeach
      </select>
    </div>
    <div class="toolbar-group">
      <label class="toolbar-label">Subject</label>
      <select name="subject_id" class="toolbar-select" id="marks-subject-filter">
        <option value="">All Subjects</option>
        @foreach($subjects as $sub)
          <option value="{{ $sub->id }}" {{ request('subject_id') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
        @endforeach
      </select>
    </div>
    <div style="display:flex; gap:12px; margin-left:auto;">
      <a href="{{ route('marks.index') }}" class="btn btn-outline" id="marks-reset-btn" style="padding:10px 16px;">Reset</a>
      <button type="submit" class="btn btn-primary" id="marks-filter-btn" style="padding:10px 20px;">Apply Filters</button>
    </div>
  </form>
  @endif

  <div class="premium-data-card">
    <div style="overflow-x:auto;">
      <table class="premium-table">
        <thead>
          <tr>
            @if(!auth()->user()->isStudent())
              <th>Student Info</th>
            @endif
            <th>Subject</th><th>Term / Exam</th><th>Score</th><th>Status</th><th>Year</th>
            @if(!auth()->user()->isStudent())
              <th>Action</th>
            @endif
          </tr>
        </thead>
        <tbody>
          @forelse($marks as $mark)
          <tr>
            @if(!auth()->user()->isStudent())
            <td>
              <div class="st-cell">
                <div class="st-avatar">{{ strtoupper(substr($mark->student->name, 0, 2)) }}</div>
                <div>
                  <div class="st-name">{{ $mark->student->name }}</div>
                  <div class="st-roll">{{ $mark->student->roll_no }}</div>
                </div>
              </div>
            </td>
            @endif
            <td style="font-weight:600; color:#111827;">{{ $mark->subject->name ?? '—' }}</td>
            <td>
              <div style="display:flex; flex-direction:column;">
                <span style="font-weight:600; color:#374151;">{{ $mark->term }}</span>
                <span class="badge badge-primary" style="width:fit-content; margin-top:4px; font-size:11px; padding:2px 8px;">{{ str_replace('_', ' ', ucfirst($mark->exam_type)) }}</span>
              </div>
            </td>
            <td>
              <div style="display:flex; flex-direction:column; gap:2px;">
                <span style="font-family:'Poppins',sans-serif; font-size:16px; font-weight:700; color:{{ $mark->percentage >= 75 ? 'var(--success)' : ($mark->percentage >= 40 ? 'var(--warning)' : 'var(--error)') }}">{{ $mark->percentage }}%</span>
                <span style="font-size:12px; color:var(--text-muted);">{{ $mark->marks_obtained }} / {{ $mark->max_marks }}</span>
              </div>
            </td>
            <td>
              @if($mark->is_pass)
                <span class="badge badge-success" style="padding:6px 12px;">Pass</span>
              @else
                <span class="badge badge-error" style="padding:6px 12px;">Fail</span>
              @endif
            </td>
            <td style="color:var(--text-muted);">{{ $mark->academic_year }}</td>
            @if(!auth()->user()->isStudent())
            <td>
              <form method="POST" action="{{ route('marks.destroy', $mark) }}" onsubmit="return confirm('Are you sure you want to delete this mark entry?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger" style="padding:6px 12px; font-size:12px;" id="delete-mark-{{ $mark->id }}">Delete</button>
              </form>
            </td>
            @endif
          </tr>
          @empty
          <tr>
            <td colspan="{{ auth()->user()->isStudent() ? 6 : 7 }}" style="text-align:center; padding:64px;">
              <div style="display:flex; flex-direction:column; align-items:center;">
                <div style="width:80px; height:80px; border-radius:50%; background:#f1f5f9; color:var(--text-muted); display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
                  <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 style="font-size:18px; font-weight:600; color:#111827;">No Marks Recorded</h3>
                <p style="color:var(--text-muted); margin-top:4px; margin-bottom:16px;">There are no academic records matching your criteria.</p>
                @if(!auth()->user()->isStudent())
                  <a href="{{ route('marks.create') }}" class="btn btn-primary">Record Marks Now</a>
                @endif
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($marks->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid var(--border);">
      {{ $marks->links() }}
    </div>
    @endif
  </div>

</x-app-layout>
