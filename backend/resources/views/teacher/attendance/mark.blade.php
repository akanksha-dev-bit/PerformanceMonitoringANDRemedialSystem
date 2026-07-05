<x-app-layout>
  <x-slot name="title">Mark Attendance: {{ $timetable->classroom->display_name }}</x-slot>

  @push('styles')
  <style>
    .page-header {
        margin-bottom: 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .page-title {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
        margin: 0;
    }
    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 16px;
        margin-top: 24px;
    }
    .student-card {
        background: #fff;
        border-radius: 16px;
        border: 2px solid #e2e8f0;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s;
        cursor: pointer;
        user-select: none;
    }
    .student-card.present {
        border-color: #22c55e;
        background: rgba(34, 197, 94, 0.02);
    }
    .student-card.absent {
        border-color: #ef4444;
        background: rgba(239, 68, 68, 0.02);
    }
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #fff;
    }
    .avatar.present { background: #22c55e; }
    .avatar.absent { background: #ef4444; }

    /* Switch Design */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }
    .switch input { 
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ef4444;
        transition: .3s;
        border-radius: 34px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #22c55e;
    }
    input:checked + .slider:before {
        transform: translateX(24px);
    }

    /* Modal Styling */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        pointer-events: none;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    .modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
        visibility: visible !important;
    }
    .modal-container {
        background: #fff;
        border-radius: 24px;
        padding: 32px;
        width: 480px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }
    .modal-overlay.active .modal-container {
        transform: scale(1);
    }
    .modal-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 15px;
        outline: none;
        transition: border-color 0.2s;
        margin-top: 8px;
    }
    .modal-input:focus {
        border-color: #6C5CE7;
    }
    .modal-title {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 12px 0;
    }
    .modal-btn {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }
    .modal-btn.primary {
        background: linear-gradient(135deg, #6C5CE7, #5A4BD6);
        color: #fff;
        box-shadow: 0 4px 12px rgba(108, 92, 231, 0.3);
    }
    .modal-btn.primary:disabled {
        background: #cbd5e1;
        color: #94a3b8;
        box-shadow: none;
        cursor: not-allowed;
    }
    .modal-btn.secondary {
        background: #f1f5f9;
        color: #475569;
    }
  </style>
  @endpush

  <div class="page-header">
    <div>
      <a href="{{ route('attendance.index') }}" style="color:#6C5CE7; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:6px; font-size:14px; margin-bottom:8px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Workspace
      </a>
      <h2 class="page-title">{{ $timetable->classroom->display_name }} — {{ $timetable->subject->name }}</h2>
      <p style="color:#64748b; font-size:14px; margin:4px 0 0 0;">Period {{ $timetable->period_number }} ({{ \Carbon\Carbon::parse($timetable->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($timetable->end_time)->format('h:i A') }})</p>
    </div>
  </div>

  @if(session('error'))
    <div style="padding: 16px; background-color: #fef2f2; color: #ef4444; border: 1px solid #fca5a5; margin-bottom: 24px; border-radius: 12px; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      {{ session('error') }}
    </div>
  @endif

  <!-- Action Bar -->
  <div style="display:flex; justify-content:space-between; align-items:center; background:#fff; padding:16px 24px; border-radius:16px; box-shadow:0 4px 20px -2px rgba(0,0,0,0.02); margin-bottom:24px;">
    <div>
      <span style="font-size:13px; color:#64748b; font-weight:600;">Attendance Helper:</span>
      <span style="font-weight:700; margin-left:8px;" id="present-count-badge">0 Present</span>
      <span style="color:#cbd5e1; margin: 0 8px;">|</span>
      <span style="font-weight:700; color:#ef4444;" id="absent-count-badge">0 Absent</span>
    </div>

    <div style="display:flex; gap:12px;">
      <button type="button" class="modal-btn secondary" style="padding: 8px 16px; font-size:13px;" onclick="setAllAttendance(true)">Mark All Present</button>
      <button type="button" class="modal-btn secondary" style="padding: 8px 16px; font-size:13px;" onclick="setAllAttendance(false)">Mark All Absent</button>
    </div>
  </div>

  <!-- Attendance Marking Form -->
  <form id="attendance-form" action="{{ route('attendance.store', $timetable->id) }}" method="POST">
    @csrf

    <div class="grid-container">
      @forelse($students as $student)
        @php
          // If editing, use saved record. If new, default to true (present)
          $isPresent = true;
          if (isset($savedRecords[$student->id])) {
              $isPresent = $savedRecords[$student->id] === 'present';
          }
        @endphp
        <div class="student-card {{ $isPresent ? 'present' : 'absent' }}" id="student-card-{{ $student->id }}" onclick="toggleStudent('{{ $student->id }}')">
          <div style="display:flex; align-items:center; gap:12px;">
            <div class="avatar {{ $isPresent ? 'present' : 'absent' }}" id="avatar-{{ $student->id }}">
              {{ strtoupper(substr($student->name, 0, 1)) }}
            </div>
            <div>
              <div style="font-weight:700; color:#0f172a; font-size:14px;">{{ $student->name }}</div>
              <div style="font-size:11px; color:#64748b; margin-top:2px;">Roll No: {{ $student->roll_no ?? 'N/A' }}</div>
            </div>
          </div>

          <label class="switch" onclick="event.stopPropagation()">
            <input type="checkbox" 
                   name="attendance[{{ $student->id }}]" 
                   id="check-{{ $student->id }}" 
                   value="present" 
                   {{ $isPresent ? 'checked' : '' }}
                   onchange="handleToggleChange('{{ $student->id }}')" />
            <span class="slider"></span>
          </label>
        </div>
      @empty
        <div style="grid-column:1/-1; padding:48px; text-align:center; color:#64748b;">
          No students enrolled in this classroom.
        </div>
      @endforelse
    </div>

    <!-- Hidden Input for Headcount -->
    <input type="hidden" name="headcount" id="final-headcount" />

    @if($students->isNotEmpty())
      <div style="margin-top:32px; display:flex; justify-content:flex-end;">
        <button type="button" class="modal-btn primary" style="padding:14px 32px; font-size:15px;" onclick="openHeadcountModal()">
          Submit Attendance
        </button>
      </div>
    @endif
  </form>

  <!-- Physical Headcount Modal -->
  <div class="modal-overlay" id="headcount-modal">
    <div class="modal-container">
      <h3 class="modal-title">Verify Physical Headcount</h3>
      <p style="color:#64748b; font-size:14px; margin: 0 0 20px 0; line-height: 1.5;">
        PMRS requires verification of actual student presence to maintain sensitive academic records.
      </p>

      <div style="margin-bottom:20px; background:#f8fafc; padding:16px; border-radius:14px;">
        <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:13px; font-weight:600; color:#475569;">
          <span>Students Checked Present:</span>
          <span style="color:#22c55e;" id="modal-present-count">0</span>
        </div>
        <div style="display:flex; justify-content:space-between; font-size:13px; font-weight:600; color:#475569;">
          <span>Students Checked Absent:</span>
          <span style="color:#ef4444;" id="modal-absent-count">0</span>
        </div>
      </div>

      <div style="margin-bottom: 24px;">
        <label style="font-size:12px; font-weight:700; color:#64748b; text-transform:uppercase;">Physical headcount in room</label>
        <input type="number" id="physical-headcount-input" class="modal-input" placeholder="e.g. 35" min="0" oninput="validateHeadcount()" />
        
        <div id="mismatch-warning" style="display:none; color:#ef4444; font-size:12px; font-weight:600; margin-top:8px; display:flex; align-items:center; gap:6px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          Mismatch: Headcount does not equal Present Count.
        </div>
      </div>

      <div style="display:flex; justify-content:flex-end; gap:12px;">
        <button type="button" class="modal-btn secondary" onclick="closeHeadcountModal()">Back to Edit</button>
        <button type="button" class="modal-btn primary" id="confirm-save-btn" onclick="submitAttendanceForm()" disabled>Confirm & Save</button>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
    // Initial Counts
    updateCounts();

    function toggleStudent(studentId) {
        const checkbox = document.getElementById('check-' + studentId);
        checkbox.checked = !checkbox.checked;
        handleToggleChange(studentId);
    }

    function handleToggleChange(studentId) {
        const checkbox = document.getElementById('check-' + studentId);
        const card = document.getElementById('student-card-' + studentId);
        const avatar = document.getElementById('avatar-' + studentId);

        if (checkbox.checked) {
            card.classList.remove('absent');
            card.classList.add('present');
            avatar.classList.remove('absent');
            avatar.classList.add('present');
        } else {
            card.classList.remove('present');
            card.classList.add('absent');
            avatar.classList.remove('present');
            avatar.classList.add('absent');
        }
        updateCounts();
    }

    function setAllAttendance(isPresent) {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(chk => {
            chk.checked = isPresent;
            const studentId = chk.id.replace('check-', '');
            handleToggleChange(studentId);
        });
    }

    function updateCounts() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        let present = 0;
        let absent = 0;

        checkboxes.forEach(chk => {
            if (chk.checked) present++;
            else absent++;
        });

        document.getElementById('present-count-badge').textContent = present + ' Present';
        document.getElementById('absent-count-badge').textContent = absent + ' Absent';

        document.getElementById('modal-present-count').textContent = present;
        document.getElementById('modal-absent-count').textContent = absent;
    }

    function openHeadcountModal() {
        updateCounts();
        document.getElementById('physical-headcount-input').value = '';
        document.getElementById('mismatch-warning').style.display = 'none';
        document.getElementById('confirm-save-btn').disabled = true;
        document.getElementById('headcount-modal').classList.add('active');
    }

    function closeHeadcountModal() {
        document.getElementById('headcount-modal').classList.remove('active');
    }

    function validateHeadcount() {
        const inputVal = parseInt(document.getElementById('physical-headcount-input').value);
        const presentCount = parseInt(document.getElementById('modal-present-count').textContent);
        const warning = document.getElementById('mismatch-warning');
        const confirmBtn = document.getElementById('confirm-save-btn');

        if (isNaN(inputVal)) {
            warning.style.display = 'none';
            confirmBtn.disabled = true;
            return;
        }

        if (inputVal === presentCount) {
            warning.style.display = 'none';
            confirmBtn.disabled = false;
        } else {
            warning.style.display = 'flex';
            confirmBtn.disabled = true; // Blocks save on headcount discrepancy
        }
    }

    function submitAttendanceForm() {
        const inputVal = document.getElementById('physical-headcount-input').value;
        document.getElementById('final-headcount').value = inputVal;
        
        // Disable buttons to prevent double submission
        document.getElementById('confirm-save-btn').disabled = true;
        document.getElementById('attendance-form').submit();
    }
  </script>
  @endpush
</x-app-layout>
