<x-app-layout>
  <x-slot name="title">Subjects</x-slot>

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
    .premium-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 20px -2px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.02);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .filter-bar {
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
    }
    .filter-input {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 16px;
        padding-left: 40px;
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
    .input-icon-wrapper {
        position: relative;
        width: 100%;
        max-width: 400px;
    }
    .input-icon-wrapper svg {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
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

    /* Custom Toggle Switch */
    .toggle-switch {
        width: 36px;
        height: 20px;
        border-radius: 20px;
        position: relative;
        cursor: pointer;
        transition: background 0.3s;
    }
    .toggle-switch.active { background: #10b981; } /* Emerald */
    .toggle-switch.inactive { background: #cbd5e1; } /* Slate 300 */
    .toggle-switch-handle {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #fff;
        position: absolute;
        top: 2px;
        transition: left 0.3s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .toggle-switch.active .toggle-switch-handle { left: 18px; }
    .toggle-switch.inactive .toggle-switch-handle { left: 2px; }
  </style>
  @endpush

  <div class="page-header">
    <div>
      <h2 class="page-title">Subjects</h2>
      <p class="page-subtitle">Manage all subjects in the system</p>
    </div>
    <a href="{{ route('subjects.create') }}" class="btn-solid-primary">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Add Subject
    </a>
  </div>

  @if(session('success'))
    <div style="padding: 16px; background-color: #ecfdf5; color: #10b981; border: 1px solid #a7f3d0; margin-bottom: 24px; border-radius: 12px; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      {{ session('success') }}
    </div>
  @endif

  <div class="premium-card">
    <div class="filter-bar">
      <div class="input-icon-wrapper">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <input type="text" id="subjectFilter" class="filter-input" placeholder="Search subjects by name or code..." onkeyup="filterSubjects()">
      </div>
    </div>

    <div style="overflow-x: auto;">
      <table class="premium-table" id="subjectsTable">
        <thead>
          <tr>
            <th>Subject Name</th>
            <th>Code</th>
            <th>Class Level</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($subjects as $subject)
            <tr class="subject-row">
              <td>
                <div style="font-weight: 700; color: #0f172a; font-size: 15px;">{{ $subject->name }}</div>
                @if($subject->type)
                  <span style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase;">{{ $subject->type }}</span>
                @endif
              </td>
              <td><span class="status-badge status-neutral" style="font-family: monospace; font-size: 13px;">{{ $subject->code }}</span></td>
              <td style="font-weight:600;">Class {{ $subject->class }}</td>
              <td>
                <div style="display:flex; align-items:center; gap:8px;">
                  <div class="toggle-switch {{ $subject->is_active ? 'active' : 'inactive' }}" title="Toggle Status">
                    <div class="toggle-switch-handle"></div>
                  </div>
                  <span style="font-size:13px; font-weight:700; color: {{ $subject->is_active ? '#10b981' : '#64748b' }};">
                    {{ $subject->is_active ? 'Active' : 'Inactive' }}
                  </span>
                </div>
              </td>
              <td>
                <div style="display:flex; gap:8px;">
                  <a href="{{ route('subjects.edit', $subject->id) }}" class="action-btn">Edit</a>
                  <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this subject?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="action-btn danger">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="padding: 48px; text-align: center; color: #64748b;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" style="margin:0 auto 16px;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                <div style="font-weight:600; font-size:16px; color:#0f172a; margin-bottom:8px;">No subjects found</div>
                <p style="margin-bottom:16px;">There are no subjects registered in the system.</p>
                <a href="{{ route('subjects.create') }}" class="btn-solid-primary">Add your first subject</a>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    @if($subjects->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid #f1f5f9;">
      {{ $subjects->links() }}
    </div>
    @endif
  </div>

  @push('scripts')
  <script>
    function filterSubjects() {
      let input = document.getElementById('subjectFilter').value.toLowerCase();
      let rows = document.querySelectorAll('.subject-row');
      
      rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? '' : 'none';
      });
    }
  </script>
  @endpush
</x-app-layout>
