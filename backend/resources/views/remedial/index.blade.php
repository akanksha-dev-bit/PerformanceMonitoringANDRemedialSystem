<x-app-layout>
  <x-slot name="title">Remedial Actions</x-slot>

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
        padding: 10px 20px;
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
        transition: all 0.2s;
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
        display: inline-flex; padding: 6px 12px; border-radius: 100px;
        font-size: 12px; font-weight: 700; border: 1px solid transparent;
    }
    
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
        cursor: pointer;
    }
    .action-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .action-btn.danger { background: #fef2f2; color: #ef4444; }
    .action-btn.danger:hover { background: #ef4444; color: #fff; }
  </style>
  @endpush

  <div class="page-header">
    <div>
      <h2 class="page-title">Remedial Actions</h2>
      <p class="page-subtitle">Track improvement interventions for struggling students</p>
    </div>
    <a href="{{ route('remedial.create') }}" class="btn-solid-primary" id="create-remedial-btn">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      New Action
    </a>
  </div>

  @if(session('success'))
    <div style="padding: 16px; background-color: #ecfdf5; color: #10b981; border: 1px solid #a7f3d0; margin-bottom: 24px; border-radius: 12px; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      {{ session('success') }}
    </div>
  @endif

  <div class="premium-card">
    <form method="GET" class="filter-bar">
      <div style="min-width:180px;">
        <label style="display:block; font-size:12px; font-weight:700; color:#64748b; margin-bottom:8px; text-transform:uppercase;">Status</label>
        <select name="status" class="filter-input" id="remedial-status-filter">
          <option value="">All Statuses</option>
          @foreach(['pending'=>'Pending','in_progress'=>'In Progress','completed'=>'Completed','cancelled'=>'Cancelled'] as $val => $label)
            <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <div style="min-width:220px; flex:1; max-width: 400px;">
        <label style="display:block; font-size:12px; font-weight:700; color:#64748b; margin-bottom:8px; text-transform:uppercase;">Student</label>
        <select name="student_id" class="filter-input" id="remedial-student-filter">
          <option value="">All Students</option>
          @foreach($students as $s)
            <option value="{{ $s->id }}" {{ request('student_id') == $s->id ? 'selected' : '' }}>{{ $s->name }} (Roll: {{ $s->roll_no }})</option>
          @endforeach
        </select>
      </div>
      <div style="display:flex; gap:8px;">
        <button type="submit" class="btn-solid-dark" id="remedial-filter-btn">Filter</button>
        <a href="{{ route('remedial.index') }}" class="action-btn" style="padding: 10px 20px; font-size: 14px;">Reset</a>
      </div>
    </form>

    <div style="overflow-x: auto;">
      <table class="premium-table">
        <thead>
          <tr>
            <th>Action Title</th>
            <th>Student Profile</th>
            <th>Intervention Type</th>
            <th>Scheduled For</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($actions as $action)
          <tr>
            <td style="font-weight:700; color:#0f172a;">{{ $action->title }}</td>
            <td>
              <div style="display:flex; align-items:center; gap:12px;">
                <div class="avatar-initials">{{ strtoupper(substr($action->student?->name ?? 'NA', 0, 2)) }}</div>
                <div>
                  <div style="font-weight:700; color:#1e293b;">{{ $action->student?->name ?? 'Unknown Student' }}</div>
                  <div style="font-size:12px; color:#64748b;">Roll: {{ $action->student?->roll_no ?? 'N/A' }}</div>
                </div>
              </div>
            </td>
            <td>
              <span style="font-weight:600; font-size:13px; color:#475569; background:#f1f5f9; padding:4px 10px; border-radius:6px; border:1px solid #cbd5e1;">
                {{ $action->action_type_label }}
              </span>
            </td>
            <td style="font-weight: 600;">
              @if($action->scheduled_date)
                <div style="display:flex; align-items:center; gap:6px;">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                  {{ $action->scheduled_date->format('d M Y') }}
                </div>
              @else
                <span style="color:#94a3b8;">Not scheduled</span>
              @endif
            </td>
            <td>
              <span class="status-badge" style="background:{{ $action->status_badge_color }}15; color:{{ $action->status_badge_color }}; border-color:{{ $action->status_badge_color }}40;">
                {{ ucfirst(str_replace('_', ' ', $action->status)) }}
              </span>
            </td>
            <td>
              <div style="display:flex; gap:6px; flex-wrap:wrap;">
                @if($action->is_interactive)
                  <a href="{{ route('remedial.submissions', $action) }}"
                    style="display:inline-flex;align-items:center;gap:4px;padding:5px 10px;background:#eef2ff;color:#6C5CE7;border-radius:6px;font-size:12px;font-weight:700;text-decoration:none;"
                    id="submissions-remedial-{{ $action->id }}">
                    📥 Submissions
                    @if($action->submission)
                      <span style="background:#6C5CE7;color:#fff;border-radius:100px;padding:0 6px;font-size:10px;">1</span>
                    @endif
                  </a>
                @endif
                <a href="{{ route('remedial.edit', $action) }}" class="action-btn" id="edit-remedial-{{ $action->id }}">Edit</a>
                <form method="POST" action="{{ route('remedial.destroy', $action) }}" onsubmit="return confirm('Delete this action?')">
                  @csrf @method('DELETE')
                  <button class="action-btn danger" id="delete-remedial-{{ $action->id }}">Delete</button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" style="text-align:center; padding:48px; color:#64748b;">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" style="margin:0 auto 16px;"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
              <div style="font-weight:600; font-size:16px; color:#0f172a; margin-bottom:8px;">No remedial actions found</div>
              <p style="margin-bottom:16px;">There are currently no interventions scheduled.</p>
              <a href="{{ route('remedial.create') }}" class="btn-solid-primary">Assign New Action</a>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    @if($actions->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid #f1f5f9;">
      {{ $actions->links() }}
    </div>
    @endif
  </div>

</x-app-layout>
