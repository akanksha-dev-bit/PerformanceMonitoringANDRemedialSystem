<x-app-layout>
  <x-slot name="title">Students</x-slot>

  @push('styles')
  <style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 16px;
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
    .btn-solid-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #6C5CE7, #5A4BD6);
        color: #ffffff;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(108, 92, 231, 0.3);
        border: none;
        cursor: pointer;
    }
    .btn-solid-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(108, 92, 231, 0.4);
        color: #ffffff;
    }
    .btn-solid-dark {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: #0f172a;
        color: #ffffff;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
        border: none;
        cursor: pointer;
    }
    .btn-solid-dark:hover {
        background: #1e293b;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.3);
        color: #ffffff;
    }
    .premium-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 20px -2px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.02);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .filter-bar {
        padding: 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        gap: 16px;
        align-items: flex-end;
        flex-wrap: wrap;
    }
    .filter-input {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 16px;
        font-size: 14px;
        width: 100%;
        color: #1e293b;
    }
    .filter-input:focus {
        background: #fff;
        border-color: #6C5CE7;
        box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1);
        outline: none;
    }
    .premium-table { width: 100%; border-collapse: collapse; }
    .premium-table th { text-align: left; padding: 16px 24px; color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
    .premium-table td { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; color: #334155; font-size: 14px; }
    .premium-table tbody tr:hover td { background: #f8fafc; }
    .status-badge {
        display: inline-flex; padding: 4px 10px; border-radius: 100px;
        font-size: 12px; font-weight: 700; border: 1px solid transparent;
    }
    .status-success { background: #ecfdf5; color: #10b981; border-color: #a7f3d0; }
    .status-danger { background: #fef2f2; color: #ef4444; border-color: #fecaca; }
    .status-warning { background: #fffbeb; color: #f59e0b; border-color: #fde68a; }
    .status-neutral { background: #f1f5f9; color: #64748b; border-color: #cbd5e1; }
    .avatar-initials {
        width: 36px; height: 36px; border-radius: 50%;
        background: linear-gradient(135deg, #6C5CE7, #5A4BD6);
        color: white; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 13px; flex-shrink: 0;
    }
    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        background: #f1f5f9;
        border: 1px solid transparent;
        text-decoration: none;
        transition: all 0.2s;
    }
    .action-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .action-btn.primary:hover {
        background: #6C5CE7;
        color: #fff;
    }
  </style>
  @endpush

  <div class="page-header">
    <div>
      <h2 class="page-title">Students</h2>
      <p class="page-subtitle">Manage all enrolled students</p>
    </div>
    <a href="{{ route('students.create') }}" class="btn-solid-primary" id="create-student-btn">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Add Student
    </a>
  </div>

  {{-- Filters --}}
  <div class="premium-card">
    <form method="GET" action="{{ route('students.index') }}" class="filter-bar">
      <div style="flex:1; min-width:200px;">
        <label style="display:block; font-size:12px; font-weight:700; color:#64748b; margin-bottom:8px; text-transform:uppercase;">Search</label>
        <input type="text" name="search" value="{{ request('search') }}" class="filter-input" placeholder="Name, Roll No, Class…" id="students-search" />
      </div>
      <div style="min-width:180px;">
        <label style="display:block; font-size:12px; font-weight:700; color:#64748b; margin-bottom:8px; text-transform:uppercase;">Class</label>
        <select name="class" class="filter-input" id="students-class-filter">
          <option value="">All Classes</option>
          @foreach($classes as $class)
            <option value="{{ $class }}" {{ request('class') == $class ? 'selected' : '' }}>Class {{ $class }}</option>
          @endforeach
        </select>
      </div>
      <div style="display:flex; gap:8px;">
        <button type="submit" class="btn-solid-dark" style="padding: 10px 20px;">Filter</button>
        <a href="{{ route('students.index') }}" class="action-btn" style="padding: 10px 20px; font-size: 14px;">Reset</a>
      </div>
    </form>
    
    {{-- Table --}}
    <div style="overflow-x: auto;">
      <table class="premium-table">
        <thead>
          <tr>
            <th>Student Profile</th>
            <th>Roll No</th>
            <th>Class</th>
            <th>Gender</th>
            <th>Assessments</th>
            <th>Average Score</th>
            <th>Academic Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($students as $student)
          <tr>
            <td>
              <div style="display:flex; align-items:center; gap:12px;">
                <div class="avatar-initials">{{ strtoupper(substr($student->name, 0, 2)) }}</div>
                <div>
                  <div style="font-weight:700; color:#0f172a;">{{ $student->name }}</div>
                  <div style="font-size:12px; color:#64748b;">{{ $student->email ?? 'No email' }}</div>
                </div>
              </div>
            </td>
            <td><span class="status-badge status-neutral" style="background:#f1f5f9;">{{ $student->roll_no }}</span></td>
            <td style="font-weight:600;">{{ $student->class }}{{ $student->section ? '-'.$student->section : '' }}</td>
            <td>{{ ucfirst($student->gender ?? '—') }}</td>
            <td><span class="status-badge status-neutral" style="background:#eef2ff; color:#6366f1; border-color:#e0e7ff;">{{ $student->marks_count }}</span></td>
            <td>
              @php $avg = $student->average_percentage; @endphp
              <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-weight:700; color:{{ $avg >= 75 ? '#10b981' : ($avg >= 40 ? '#f59e0b' : '#ef4444') }}">{{ $avg }}%</span>
                <div style="width:60px; height:6px; background:#f1f5f9; border-radius:3px; overflow:hidden;">
                  <div style="height:100%; width:{{ $avg }}%; background:{{ $avg >= 75 ? '#10b981' : ($avg >= 40 ? '#f59e0b' : '#ef4444') }};"></div>
                </div>
              </div>
            </td>
            <td>
              @if($student->is_slow_learner)
                <span class="status-badge status-danger">Slow Learner</span>
              @elseif($avg >= 75)
                <span class="status-badge status-success">Excellent</span>
              @else
                <span class="status-badge status-warning">Average</span>
              @endif
            </td>
            <td>
              <div style="display:flex; gap:6px;">
                <button type="button" class="action-btn" onclick="openStudentModal('{{ $student->name }}', '{{ $student->roll_no }}', '{{ $student->class }}', '{{ $student->section }}', '{{ $avg }}')">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
                <a href="{{ route('students.show', $student) }}" class="action-btn primary" id="view-student-{{ $student->id }}">Profile</a>
                <a href="{{ route('students.edit', $student) }}" class="action-btn" id="edit-student-{{ $student->id }}">Edit</a>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" style="text-align:center; padding:48px; color:#64748b;">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" style="margin:0 auto 16px;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
              <div style="font-weight:600; font-size:16px; color:#0f172a; margin-bottom:8px;">No students found</div>
              <p style="margin-bottom:16px;">There are no students matching your criteria.</p>
              <a href="{{ route('students.create') }}" class="btn-solid-primary">Add your first student</a>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($students->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid #f1f5f9;">
      {{ $students->links() }}
    </div>
    @endif
  </div>

  {{-- Quick View Modal --}}
  <div class="modal-overlay" id="quickViewModal" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); display:flex; align-items:center; justify-content:center; z-index:1000; opacity:0; visibility:hidden; transition:all 0.3s;">
      <div class="modal-content" style="background:#fff; border-radius:24px; width:100%; max-width:400px; padding:32px; box-shadow:0 20px 40px rgba(0,0,0,0.1); transform:translateY(20px); transition:all 0.3s;">
          <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:24px;">
              <div style="display:flex; align-items:center; gap:16px;">
                  <div class="avatar-initials" id="qv-avatar" style="width:56px; height:56px; font-size:20px;">U</div>
                  <div>
                      <h3 style="font-weight:800; font-size:20px; color:#0f172a;" id="qv-name">Student Name</h3>
                      <p style="font-size:13px; color:#64748b; margin-top:4px;" id="qv-roll">Roll No: --</p>
                  </div>
              </div>
              <button onclick="closeStudentModal()" style="background:none; border:none; cursor:pointer; color:#94a3b8; transition:0.2s;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#94a3b8'">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
              </button>
          </div>
          <div style="display:flex; flex-direction:column; gap:16px;">
              <div style="display:flex; justify-content:space-between; items-center; padding:16px; background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0;">
                  <span style="font-size:13px; font-weight:600; color:#64748b;">Class & Section</span>
                  <span style="font-weight:800; color:#0f172a;" id="qv-class">--</span>
              </div>
              <div style="display:flex; justify-content:space-between; items-center; padding:16px; background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0;">
                  <span style="font-size:13px; font-weight:600; color:#64748b;">Overall Average</span>
                  <span style="font-weight:800; color:#6C5CE7; font-size:20px;" id="qv-avg">--%</span>
              </div>
          </div>
          <div style="margin-top:24px;">
              <button type="button" class="btn-solid-dark" style="width:100%; justify-content:center;" onclick="closeStudentModal()">Close Profile</button>
          </div>
      </div>
  </div>

  @push('scripts')
  <script>
      function openStudentModal(name, roll, cls, section, avg) {
          document.getElementById('qv-avatar').innerText = name.substring(0, 2).toUpperCase();
          document.getElementById('qv-name').innerText = name;
          document.getElementById('qv-roll').innerText = 'Roll No: ' + roll;
          document.getElementById('qv-class').innerText = cls + (section ? ' - ' + section : '');
          document.getElementById('qv-avg').innerText = avg + '%';
          
          let modal = document.getElementById('quickViewModal');
          modal.style.opacity = "1";
          modal.style.visibility = "visible";
          modal.querySelector('.modal-content').style.transform = "translateY(0)";
      }

      function closeStudentModal() {
          let modal = document.getElementById('quickViewModal');
          modal.style.opacity = "0";
          modal.style.visibility = "hidden";
          modal.querySelector('.modal-content').style.transform = "translateY(20px)";
      }
  </script>
  @endpush

</x-app-layout>
