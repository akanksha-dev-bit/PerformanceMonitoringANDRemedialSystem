<x-app-layout>
  <x-slot name="title">Timetable Scheduling</x-slot>

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
    .timetable-grid {
        display: grid;
        grid-template-columns: 120px repeat(6, 1fr);
        gap: 12px;
        margin-top: 24px;
    }
    .grid-header {
        background: #f8fafc;
        padding: 12px;
        border-radius: 8px;
        font-weight: 700;
        text-align: center;
        color: #475569;
        font-size: 12px;
        text-transform: uppercase;
    }
    .grid-cell-label {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        font-weight: 700;
        font-size: 13px;
        color: #0f172a;
        border-radius: 8px;
        padding: 12px;
    }
    .grid-cell {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
        padding: 12px;
        min-height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        transition: all 0.2s;
    }
    .grid-cell.active {
        background: #fff;
        border: 1px solid rgba(108, 92, 231, 0.15);
        box-shadow: 0 4px 12px -2px rgba(108, 92, 231, 0.05);
    }
    .grid-cell.active:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px -2px rgba(108, 92, 231, 0.1);
    }
    .delete-btn {
        position: absolute;
        top: 6px;
        right: 6px;
        background: rgba(239, 68, 68, 0.08);
        border: none;
        color: #ef4444;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .delete-btn:hover {
        background: #ef4444;
        color: #fff;
    }
  </style>
  @endpush

  <div class="page-header">
    <div>
      <h2 class="page-title">Timetable & Scheduling</h2>
      <p class="page-subtitle">Build weekly schedules, assign periods, and prevent scheduling conflicts</p>
    </div>
  </div>

  @if(session('success'))
    <div style="padding: 16px; background-color: #ecfdf5; color: #10b981; border: 1px solid #a7f3d0; margin-bottom: 24px; border-radius: 12px; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      {{ session('success') }}
    </div>
  @endif

  @if($errors->any())
    <div style="padding: 16px; background-color: #fef2f2; color: #ef4444; border: 1px solid #fca5a5; margin-bottom: 24px; border-radius: 12px; font-weight: 600; font-size: 14px;">
      <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px; font-weight: 700;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        Timetable Conflict Mismatch Detected
      </div>
      <ul style="margin: 0; padding-left: 20px;">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; background:#fff; padding: 20px; border-radius: 16px; box-shadow: 0 4px 20px -2px rgba(0,0,0,0.02);">
    <form action="{{ route('timetables.index') }}" method="GET" style="display:flex; align-items:center; gap:12px;">
      <label style="font-weight:700; color:#475569; font-size: 14px;">Select Classroom View:</label>
      <select name="classroom_id" class="modal-input" style="width: 220px; margin-top:0;" onchange="this.form.submit()">
        @foreach($classrooms as $room)
          <option value="{{ $room->id }}" {{ $selectedClassroomId == $room->id ? 'selected' : '' }}>
            {{ $room->display_name }}
          </option>
        @endforeach
      </select>
    </form>

    <p style="margin:0; font-size: 13px; color: #64748b; font-weight:600;">
      Showing timetable for <strong style="color:#6C5CE7;">{{ $classrooms->firstWhere('id', $selectedClassroomId)?->display_name ?? 'N/A' }}</strong>
    </p>
  </div>

  <div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">
    <div class="premium-card" style="padding: 24px;">
      <h3 style="font-size: 18px; font-weight: 700; margin: 0 0 8px 0;">Weekly Grid</h3>
      <p style="color:#64748b; font-size: 13px; margin: 0 0 20px 0;">Overview of active scheduled class slots</p>

      <div class="timetable-grid">
        <!-- Grid Headers -->
        <div class="grid-header">Period</div>
        @foreach($days as $day)
          <div class="grid-header">{{ $day }}</div>
        @endforeach

        <!-- Build rows for Periods 1 to 8 -->
        @for($p = 1; $p <= 8; $p++)
          <div class="grid-cell-label">Period {{ $p }}</div>
          @foreach($days as $day)
            @php
              $slot = $timetable->firstWhere(fn($item) => $item->day_of_week === $day && $item->period_number == $p);
            @endphp
            @if($slot)
              <div class="grid-cell active">
                <form action="{{ route('timetables.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Remove this class period?');">
                  @csrf @method('DELETE')
                  <button type="submit" class="delete-btn" title="Delete slot">×</button>
                </form>
                <div>
                  <div style="font-weight: 700; color: #6C5CE7; font-size: 13px;">{{ $slot->subject->name }}</div>
                  <div style="font-size: 11px; color: #64748b; margin-top: 2px;">{{ $slot->teacher->name }}</div>
                </div>
                <div style="font-size: 10px; font-weight: 700; color: #94a3b8; margin-top: 8px; border-top: 1px solid #f1f5f9; padding-top: 4px;">
                  {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                </div>
              </div>
            @else
              <div class="grid-cell" style="display: flex; align-items:center; justify-content:center; color:#cbd5e1; font-size: 12px; font-weight:500;">
                Empty
              </div>
            @endif
          @endforeach
        @endfor
      </div>
    </div>

    <!-- Create Slot Card -->
    <div class="premium-card" style="padding: 24px;">
      <h3 style="font-size: 18px; font-weight: 700; margin: 0 0 16px 0;">Schedule Class Period</h3>
      <form action="{{ route('timetables.store') }}" method="POST">
        @csrf
        <input type="hidden" name="classroom_id" value="{{ $selectedClassroomId }}" />

        <div style="margin-bottom: 16px;">
          <label style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Teacher & Subject Allocation</label>
          <select name="allocation_id" class="modal-input" required>
            <option value="">Select allocation...</option>
            @foreach($allocations->where('classroom_id', $selectedClassroomId) as $alloc)
              <option value="{{ $alloc->id }}" {{ old('allocation_id') == $alloc->id ? 'selected' : '' }}>
                {{ $alloc->teacher->name }} — {{ $alloc->subject->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div style="margin-bottom: 16px;">
          <label style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Day of Week</label>
          <select name="day_of_week" class="modal-input" required>
            <option value="">Select day...</option>
            @foreach($days as $day)
              <option value="{{ $day }}" {{ old('day_of_week') == $day ? 'selected' : '' }}>{{ $day }}</option>
            @endforeach
          </select>
        </div>

        <div style="margin-bottom: 16px;">
          <label style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Period Number</label>
          <select name="period_number" class="modal-input" required>
            <option value="">Select period...</option>
            @for($p=1; $p<=8; $p++)
              <option value="{{ $p }}" {{ old('period_number') == $p ? 'selected' : '' }}>Period {{ $p }}</option>
            @endfor
          </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px;">
          <div>
            <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Start Time</label>
            <input type="time" name="start_time" class="modal-input" value="{{ old('start_time') }}" required />
          </div>
          <div>
            <label style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">End Time</label>
            <input type="time" name="end_time" class="modal-input" value="{{ old('end_time') }}" required />
          </div>
        </div>

        <button type="submit" class="btn-solid-primary" style="width: 100%; justify-content: center;">
          Add to Timetable
        </button>
      </form>
    </div>
  </div>

  @push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const periodSelect = document.querySelector('select[name="period_number"]');
        const startTimeInput = document.querySelector('input[name="start_time"]');
        const endTimeInput = document.querySelector('input[name="end_time"]');
        
        const periodTimes = {
            1: { start: '08:30', end: '09:30' },
            2: { start: '09:30', end: '10:30' },
            3: { start: '10:30', end: '11:30' },
            4: { start: '11:30', end: '12:30' },
            5: { start: '12:30', end: '13:30' },
            6: { start: '13:30', end: '14:30' },
            7: { start: '14:30', end: '15:30' },
            8: { start: '15:30', end: '16:30' }
        };
        
        if (periodSelect && startTimeInput && endTimeInput) {
            periodSelect.addEventListener('change', function() {
                const period = this.value;
                if (periodTimes[period]) {
                    startTimeInput.value = periodTimes[period].start;
                    endTimeInput.value = periodTimes[period].end;
                } else {
                    startTimeInput.value = '';
                    endTimeInput.value = '';
                }
            });
        }
    });
  </script>
  @endpush
</x-app-layout>
