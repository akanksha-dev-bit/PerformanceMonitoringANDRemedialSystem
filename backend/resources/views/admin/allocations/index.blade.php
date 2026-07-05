<x-app-layout>
  <x-slot name="title">Teacher Allocations</x-slot>

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
    .premium-table { width: 100%; border-collapse: collapse; }
    .premium-table th { text-align: left; padding: 16px 24px; color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
    .premium-table td { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; color: #334155; font-size: 14px; }
    .premium-table tbody tr:hover td { background: #f8fafc; }
    
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

    .modal-input {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        outline: none;
        margin-top: 6px;
        font-family: inherit;
        background: #fff;
    }
    .modal-input:focus {
        border-color: #6C5CE7;
        box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.15);
    }
  </style>
  @endpush

  <div class="page-header">
    <div>
      <h2 class="page-title">Teacher Subject Allocations</h2>
      <p class="page-subtitle">Allocate subjects and classrooms to specific teaching staff members</p>
    </div>
  </div>

  @if(session('success'))
    <div style="padding: 16px; background-color: #ecfdf5; color: #10b981; border: 1px solid #a7f3d0; margin-bottom: 24px; border-radius: 12px; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div style="padding: 16px; background-color: #fef2f2; color: #ef4444; border: 1px solid #fca5a5; margin-bottom: 24px; border-radius: 12px; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      {{ session('error') }}
    </div>
  @endif

  <div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">
    <div class="premium-card">
      <div style="overflow-x: auto;">
        <table class="premium-table">
          <thead>
            <tr>
              <th>Teacher</th>
              <th>Subject</th>
              <th>Classroom / Section</th>
              <th style="width: 150px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($allocations as $allocation)
              <tr>
                <td style="font-weight: 700; color: #0f172a;">{{ $allocation->teacher->name ?? 'Unknown' }}</td>
                <td style="font-weight: 600; color: #6C5CE7;">{{ $allocation->subject->name ?? 'None' }}</td>
                <td>
                  <span style="font-weight: 600; color: #475569; background: #e2e8f0; padding: 4px 10px; border-radius: 100px; font-size: 12px;">
                    {{ $allocation->classroom->display_name ?? 'None' }}
                  </span>
                </td>
                <td>
                  <form action="{{ route('teacher-allocations.destroy', $allocation->id) }}" method="POST" onsubmit="return confirm('Remove this subject allocation? This will delete any associated timetable schedules.');">
                    @csrf @method('DELETE')
                    <button type="submit" class="action-btn danger">Remove</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" style="padding: 48px; text-align: center; color: #64748b;">
                  <div style="font-weight:600; font-size:16px; color:#0f172a; margin-bottom:8px;">No allocations found</div>
                  <p>Allocate a teacher to a subject and classroom to enable timetable scheduling.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create Allocation Card -->
    <div class="premium-card" style="padding: 24px;">
      <h3 style="font-size: 18px; font-weight: 700; margin: 0 0 16px 0;">New Allocation</h3>
      <form action="{{ route('teacher-allocations.store') }}" method="POST">
        @csrf
        
        <div style="margin-bottom: 16px;">
          <label style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Teacher</label>
          <select name="teacher_id" class="modal-input" required>
            <option value="">Select a teacher...</option>
            @foreach($teachers as $teacher)
              <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
            @endforeach
          </select>
          @error('teacher_id')
            <p style="color: #ef4444; font-size: 12px; margin: 4px 0 0 0;">{{ $message }}</p>
          @enderror
        </div>

        <div style="margin-bottom: 16px;">
          <label style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Subject</label>
          <select name="subject_id" class="modal-input" required>
            <option value="">Select a subject...</option>
            @foreach($subjects as $subject)
              <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
            @endforeach
          </select>
          @error('subject_id')
            <p style="color: #ef4444; font-size: 12px; margin: 4px 0 0 0;">{{ $message }}</p>
          @enderror
        </div>

        <div style="margin-bottom: 20px;">
          <label style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Classroom / Section</label>
          <select name="classroom_id" class="modal-input" required>
            <option value="">Select a classroom...</option>
            @foreach($classrooms as $classroom)
              <option value="{{ $classroom->id }}">{{ $classroom->display_name }}</option>
            @endforeach
          </select>
          @error('classroom_id')
            <p style="color: #ef4444; font-size: 12px; margin: 4px 0 0 0;">{{ $message }}</p>
          @enderror
        </div>
        
        <button type="submit" class="btn-solid-primary" style="width: 100%; justify-content: center;">
          Allocate Subject
        </button>
      </form>
    </div>
  </div>
</x-app-layout>
