<x-app-layout>
  <x-slot name="title">Classrooms & Sections</x-slot>

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
      <h2 class="page-title">Classrooms & Sections</h2>
      <p class="page-subtitle">Configure specific sections/rooms (e.g. 10A, 10B) mapped to academic classes</p>
    </div>
  </div>

  @if(session('success'))
    <div style="padding: 16px; background-color: #ecfdf5; color: #10b981; border: 1px solid #a7f3d0; margin-bottom: 24px; border-radius: 12px; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      {{ session('success') }}
    </div>
  @endif

  <div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">
    <div class="premium-card">
      <div style="overflow-x: auto;">
        <table class="premium-table">
          <thead>
            <tr>
              <th>Classroom / Section Name</th>
              <th>Parent Class</th>
              <th>Enrolled Students</th>
              <th style="width: 200px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($classrooms as $room)
              <tr>
                <td style="font-weight: 700; color: #0f172a;">{{ $room->name }}</td>
                <td>
                  <span style="font-weight: 600; color: #475569; background: #e2e8f0; padding: 4px 10px; border-radius: 100px; font-size: 12px;">
                    {{ $room->academicClass->name ?? 'None' }}
                  </span>
                </td>
                <td>
                  <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="font-weight: 700; color: #0f172a;">{{ $room->students_count }}</span>
                    <span style="color:#64748b; font-size: 12px;">Students</span>
                  </div>
                </td>
                <td>
                  <form action="{{ route('classrooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Deleting this classroom will remove all timetable schedules and student enrollments inside it! Proceed?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="action-btn danger">Delete</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" style="padding: 48px; text-align: center; color: #64748b;">
                  <div style="font-weight:600; font-size:16px; color:#0f172a; margin-bottom:8px;">No classrooms or sections found</div>
                  <p>Create a classroom in the sidebar to get started.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create Classroom Card -->
    <div class="premium-card" style="padding: 24px;">
      <h3 style="font-size: 18px; font-weight: 700; margin: 0 0 16px 0;">Create New Classroom</h3>
      <form action="{{ route('classrooms.store') }}" method="POST">
        @csrf
        <div style="margin-bottom: 16px;">
          <label style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Parent Class</label>
          <select name="academic_class_id" class="modal-input" required>
            <option value="">Select a class...</option>
            @foreach($classes as $class)
              <option value="{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
          </select>
          @error('academic_class_id')
            <p style="color: #ef4444; font-size: 12px; margin: 4px 0 0 0;">{{ $message }}</p>
          @enderror
        </div>

        <div style="margin-bottom: 20px;">
          <label style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Classroom / Section Name</label>
          <input type="text" name="name" class="modal-input" placeholder="e.g. 10A or A" required />
          @error('name')
            <p style="color: #ef4444; font-size: 12px; margin: 4px 0 0 0;">{{ $message }}</p>
          @enderror
        </div>
        
        <button type="submit" class="btn-solid-primary" style="width: 100%; justify-content: center;">
          Create Classroom
        </button>
      </form>
    </div>
  </div>
</x-app-layout>
