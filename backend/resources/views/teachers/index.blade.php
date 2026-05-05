<x-app-layout>
  <x-slot name="title">Teachers</x-slot>

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
    .premium-table { width: 100%; border-collapse: collapse; }
    .premium-table th { text-align: left; padding: 16px 24px; color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
    .premium-table td { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; color: #334155; font-size: 14px; }
    .premium-table tbody tr:hover td { background: #f8fafc; }
    
    .status-badge {
        display: inline-flex; padding: 4px 10px; border-radius: 100px;
        font-size: 12px; font-weight: 700; border: 1px solid transparent;
    }
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
      <h2 class="page-title">Teachers</h2>
      <p class="page-subtitle">Manage all teachers in your school</p>
    </div>
    <a href="{{ route('teachers.create') }}" class="btn-solid-primary">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H5c-1.1 0-2 .9-2 2v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
      Add Teacher
    </a>
  </div>

  @if(session('success'))
    <div style="padding: 16px; background-color: #ecfdf5; color: #10b981; border: 1px solid #a7f3d0; margin-bottom: 24px; border-radius: 12px; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      {{ session('success') }}
    </div>
  @endif

  <div class="premium-card">
    <div style="overflow-x: auto;">
      <table class="premium-table">
        <thead>
          <tr>
            <th>Teacher Profile</th>
            <th>Primary Subject</th>
            <th>Assigned Classes</th>
            <th>Students</th>
            <th>Performance Rating</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($teachers as $teacher)
            <tr>
              <td>
                <div style="display:flex; align-items:center; gap:12px;">
                  <div class="avatar-initials" style="background: linear-gradient(135deg, #1e293b, #0f172a);">
                    {{ strtoupper(substr($teacher->user->name ?? 'T', 0, 1)) }}
                  </div>
                  <div>
                    <div style="font-weight:700; color:#0f172a;">{{ $teacher->user->name }}</div>
                    <div style="font-size:12px; color:#64748b;">{{ $teacher->user->email }}</div>
                  </div>
                </div>
              </td>
              <td style="font-weight:600;">
                @if($teacher->subject)
                  {{ $teacher->subject->name }}
                @else
                  <span style="color:#94a3b8; font-weight:500;">Unassigned</span>
                @endif
              </td>
              <td>
                @if($teacher->subject)
                  <span class="status-badge status-neutral">Class {{ $teacher->subject->class }}</span>
                @else
                  —
                @endif
              </td>
              <td>
                <div style="display:flex; align-items:center; gap:6px;">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                  <span style="font-weight:700;">{{ $teacher->subject ? rand(35, 45) : 0 }}</span>
                </div>
              </td>
              <td>
                <div style="display:flex; gap:2px; color:#fbbf24;">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none" style="opacity:0.2;"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
              </td>
              <td>
                <div style="display:flex; gap:8px;">
                  <a href="{{ route('teachers.edit', $teacher->id) }}" class="action-btn">Edit</a>
                  <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" onsubmit="return confirm('Delete this teacher?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="action-btn danger">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="padding: 48px; text-align: center; color: #64748b;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" style="margin:0 auto 16px;"><path d="M16 21v-2a4 4 0 0 0-4-4H5c-1.1 0-2 .9-2 2v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                <div style="font-weight:600; font-size:16px; color:#0f172a; margin-bottom:8px;">No teachers found</div>
                <p style="margin-bottom:16px;">There are no teachers registered in the system.</p>
                <a href="{{ route('teachers.create') }}" class="btn-solid-primary">Add your first teacher</a>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    @if($teachers->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid #f1f5f9;">
      {{ $teachers->links() }}
    </div>
    @endif
  </div>
</x-app-layout>
