<x-app-layout>
  <x-slot name="title">{{ $remedial->title }} — Interactive Workspace</x-slot>

  @push('styles')
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap');

    .ws-body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      color: #0f172a;
      background-color: #f8fafc;
      min-height: 100vh;
    }

    /* Premium Header Banner */
    .ws-header-banner {
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #312e81 100%);
      border-radius: 24px;
      padding: 32px 40px;
      color: #ffffff;
      margin-bottom: 32px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.3);
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 24px;
    }
    .ws-header-banner::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -10%;
      width: 360px;
      height: 360px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(99, 102, 241, 0.25), transparent 70%);
      pointer-events: none;
    }
    .ws-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 100px;
      padding: 6px 14px;
      font-size: 12px;
      font-weight: 700;
      letter-spacing: 0.03em;
      margin-bottom: 14px;
      backdrop-filter: blur(4px);
    }
    .ws-title {
      font-size: 28px;
      font-weight: 800;
      margin: 0 0 10px;
      letter-spacing: -0.02em;
      line-height: 1.2;
    }
    .ws-meta {
      font-size: 13px;
      color: rgba(255, 255, 255, 0.7);
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      font-weight: 500;
    }
    .ws-meta span {
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .btn-back-workspace {
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: #ffffff;
      padding: 12px 22px;
      border-radius: 14px;
      font-size: 14px;
      font-weight: 700;
      text-decoration: none;
      transition: all 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      backdrop-filter: blur(4px);
      position: relative;
      z-index: 2;
    }
    .btn-back-workspace:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    /* Dual Column Workspace Grid */
    .ws-grid {
      display: grid;
      grid-template-columns: 1fr 380px;
      gap: 32px;
      align-items: start;
    }
    @media (max-width: 1024px) {
      .ws-grid {
        grid-template-columns: 1fr;
      }
    }

    /* Gorgeous Cards */
    .premium-card {
      background: #ffffff;
      border-radius: 24px;
      padding: 32px;
      box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
      border: 1px solid rgba(15, 23, 42, 0.05);
      margin-bottom: 28px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .premium-card:hover {
      box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.06);
    }
    .card-title-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
    }
    .card-title {
      font-size: 18px;
      font-weight: 800;
      color: #0f172a;
      display: flex;
      align-items: center;
      gap: 10px;
      letter-spacing: -0.01em;
    }

    /* Elegant Text Editor Layout */
    .submission-textarea {
      width: 100%;
      min-height: 380px;
      padding: 24px;
      border: 1.5px solid #e2e8f0;
      border-radius: 18px;
      font-size: 16px;
      line-height: 1.8;
      font-family: 'Inter', sans-serif;
      color: #1e293b;
      resize: vertical;
      transition: all 0.3s ease;
      box-sizing: border-box;
      background-color: #fafbfd;
    }
    .submission-textarea:focus {
      outline: none;
      border-color: #6366f1;
      background-color: #ffffff;
      box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12), 0 10px 20px -10px rgba(99, 102, 241, 0.05);
    }

    /* Word Counter Progress Bar */
    .word-counter-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 18px;
      font-size: 13px;
      font-weight: 700;
      color: #64748b;
      background: #f8fafc;
      padding: 12px 20px;
      border-radius: 12px;
      border: 1px solid #f1f5f9;
    }
    .wc-progress {
      flex: 1;
      margin: 0 20px;
      height: 8px;
      background: #e2e8f0;
      border-radius: 100px;
      overflow: hidden;
      position: relative;
    }
    .wc-fill {
      height: 100%;
      border-radius: 100px;
      transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1), background 0.3s ease;
    }

    /* Pulsing Autosave Status Indicator */
    .autosave-indicator {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 12px;
      color: #64748b;
      font-weight: 700;
      background: #f1f5f9;
      padding: 6px 14px;
      border-radius: 100px;
      transition: all 0.3s ease;
    }
    .autosave-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #94a3b8;
      display: inline-block;
      transition: all 0.3s ease;
    }
    .autosave-indicator.saving {
      color: #4f46e5;
      background: rgba(79, 70, 229, 0.08);
    }
    .autosave-indicator.saving .autosave-dot {
      background: #4f46e5;
      animation: pulse 1.2s infinite ease-in-out;
    }
    .autosave-indicator.saved {
      color: #059669;
      background: rgba(5, 150, 105, 0.08);
    }
    .autosave-indicator.saved .autosave-dot {
      background: #10b981;
    }

    @keyframes pulse {
      0% { transform: scale(0.85); opacity: 0.5; }
      50% { transform: scale(1.25); opacity: 1; }
      100% { transform: scale(0.85); opacity: 0.5; }
    }

    /* Drag-and-drop File Upload Zone */
    .file-drop-zone {
      border: 2px dashed #cbd5e1;
      border-radius: 20px;
      padding: 56px 32px;
      text-align: center;
      cursor: pointer;
      transition: all 0.2s ease-in-out;
      background: #f8fafc;
      position: relative;
    }
    .file-drop-zone:hover, .file-drop-zone.drag-over {
      border-color: #6366f1;
      background: rgba(99, 102, 241, 0.04);
      transform: scale(0.995);
    }
    .file-drop-zone input[type="file"] {
      display: none;
    }
    .file-icon-wrapper {
      width: 72px;
      height: 72px;
      background: #ffffff;
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      box-shadow: 0 10px 20px -5px rgba(0,0,0,0.05);
      font-size: 32px;
      transition: transform 0.2s;
    }
    .file-drop-zone:hover .file-icon-wrapper {
      transform: translateY(-4px);
    }

    /* Premium Button Style */
    .btn-submit {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 16px 36px;
      background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
      color: #ffffff;
      border-radius: 16px;
      font-size: 15px;
      font-weight: 700;
      border: none;
      cursor: pointer;
      box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.4);
      transition: all 0.2s ease;
      width: 100%;
      justify-content: center;
      margin-top: 28px;
    }
    .btn-submit:hover:not(:disabled) {
      transform: translateY(-2px);
      box-shadow: 0 15px 30px -5px rgba(79, 70, 229, 0.5);
    }
    .btn-submit:active:not(:disabled) {
      transform: translateY(0);
    }
    .btn-submit:disabled {
      opacity: 0.5;
      cursor: not-allowed;
      box-shadow: none;
    }

    /* Status Notifications & Alerts */
    .premium-alert {
      border-radius: 20px;
      padding: 24px;
      margin-bottom: 28px;
      display: flex;
      gap: 16px;
      align-items: flex-start;
      border: 1px solid transparent;
      line-height: 1.6;
    }
    .alert-success {
      background: #ecfdf5;
      border-color: #a7f3d0;
      color: #065f46;
    }
    .alert-error {
      background: #fef2f2;
      border-color: #fecaca;
      color: #991b1b;
    }
    .alert-feedback {
      background: #fffbeb;
      border-color: #fde68a;
      color: #92400e;
      border-left: 5px solid #f59e0b;
    }
    .alert-reviewed {
      background: #f0fdf4;
      border-color: #bbf7d0;
      color: #166534;
      border-left: 5px solid #10b981;
    }

    /* Right Sidebar Profile Details */
    .sidebar-block {
      background: #ffffff;
      border-radius: 24px;
      padding: 28px;
      border: 1px solid rgba(15, 23, 42, 0.05);
      box-shadow: 0 10px 25px -10px rgba(0, 0, 0, 0.03);
      margin-bottom: 24px;
    }
    .sidebar-title {
      font-size: 15px;
      font-weight: 800;
      color: #475569;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 18px;
    }
    .detail-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 0;
      border-bottom: 1px solid #f1f5f9;
      font-size: 14px;
    }
    .detail-row:last-child {
      border-bottom: none;
    }
    .detail-label {
      color: #64748b;
      font-weight: 600;
    }
    .detail-value {
      color: #0f172a;
      font-weight: 700;
    }

    /* Attendance Form Styles */
    .attendance-option {
      background: #f8fafc;
      border: 1.5px solid #e2e8f0;
      border-radius: 16px;
      padding: 20px;
      display: flex;
      gap: 16px;
      align-items: flex-start;
      cursor: pointer;
      transition: all 0.2s;
    }
    .attendance-option:hover {
      border-color: #6366f1;
      background: rgba(99, 102, 241, 0.02);
    }
    .attendance-checkbox {
      width: 22px;
      height: 22px;
      margin-top: 3px;
      accent-color: #4f46e5;
      border-radius: 6px;
      cursor: pointer;
    }
  </style>
  @endpush

  <div class="ws-body">
    {{-- Top Premium Header Banner --}}
    <div class="ws-header-banner">
      <div style="position:relative; z-index:2;">
        <div class="ws-badge">
          {{ $remedial->action_type_label }}
        </div>
        <h2 class="ws-title">{{ $remedial->title }}</h2>
        <div class="ws-meta">
          <span>
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm10-2h4m-2-2v4"></path></svg>
            Assigned by {{ $remedial->assignedByUser?->name ?? 'Teacher' }}
          </span>
          @if($remedial->due_date)
            <span @if($remedial->is_overdue) style="color:#fca5a5; font-weight:700;" @endif>
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
              Due: {{ $remedial->due_date->format('d M Y') }}
              @if($remedial->is_overdue) (Overdue) @endif
            </span>
          @endif
          @if($remedial->max_score)
            <span>
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
              Max Score: {{ $remedial->max_score }}
            </span>
          @endif
        </div>
      </div>
      <a href="{{ route('student.tasks') }}" class="btn-back-workspace">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
        Back to Dashboard
      </a>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
      <div class="premium-alert alert-success">
        <span style="font-size: 20px;">✨</span>
        <div><strong>Success!</strong> {{ session('success') }}</div>
      </div>
    @endif

    @if(session('error'))
      <div class="premium-alert alert-error">
        <span style="font-size: 20px;">⚠️</span>
        <div><strong>Error!</strong> {{ session('error') }}</div>
      </div>
    @endif

    {{-- Teacher Feedback Alert --}}
    @if($submission->submission_status === 'needs_improvement')
      <div class="premium-alert alert-feedback">
        <span style="font-size: 22px;">🔄</span>
        <div>
          <div style="font-weight: 800; font-size: 15px; margin-bottom: 4px;">Resubmission Requested by Teacher</div>
          <div style="font-size: 14px;">"{{ $submission->teacher_feedback ?? 'Please revise and resubmit your work.' }}"</div>
        </div>
      </div>
    @endif

    {{-- Reviewed/Graded Card --}}
    @if($submission->submission_status === 'reviewed')
      <div class="premium-alert alert-reviewed">
        <span style="font-size: 22px;">🏆</span>
        <div style="flex: 1;">
          <div style="font-weight: 800; font-size: 16px; margin-bottom: 6px;">Evaluation Completed</div>
          @if($submission->teacher_score !== null)
            <div style="font-size: 32px; font-weight: 900; color: #059669; margin: 4px 0 8px;">
              {{ $submission->teacher_score }} <span style="font-size: 16px; color:#64748b; font-weight:600;">/ {{ $remedial->max_score ?? '100' }}</span>
            </div>
          @endif
          @if($submission->teacher_feedback)
            <div style="font-size: 14px; background: rgba(255,255,255,0.6); padding: 12px 16px; border-radius: 10px; border: 1px solid rgba(16,185,129,0.2);">
              <strong>Feedback:</strong> {{ $submission->teacher_feedback }}
            </div>
          @endif
        </div>
      </div>
    @endif

    {{-- Dual Column Layout --}}
    <div class="ws-grid">
      
      {{-- LEFT COLUMN: Primary Workspace --}}
      <div>
        {{-- Task Details description --}}
        @if($remedial->description)
          <div class="premium-card">
            <div class="card-title">
              <svg width="20" height="20" fill="none" stroke="#4f46e5" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
              Task Assignment Description
            </div>
            <div style="color: #475569; font-size: 15px; line-height: 1.8; white-space: pre-line;">
              {{ $remedial->description }}
            </div>
          </div>
        @endif

        {{-- 1. Written/Essay submission area --}}
        @if(in_array($remedial->action_type, ['written_assignment', 'essay', 'assignment']) && $submission->can_edit)
          <div class="premium-card">
            <div class="card-title-bar">
              <div class="card-title">
                <svg width="20" height="20" fill="none" stroke="#4f46e5" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Editor Workspace
              </div>
              <span class="autosave-indicator" id="autosaveStatus">
                <span class="autosave-dot" id="autosaveDot"></span>
                Draft synced
              </span>
            </div>

            @if($remedial->min_words || $remedial->max_words)
              <div style="background:#f0fdfa; border:1px solid #ccfbf1; border-radius:14px; padding:12px 18px; margin-bottom:20px; font-size:13px; color:#0f766e; font-weight:700; display:flex; align-items:center; gap:8px;">
                <span>📏</span>
                <span>
                  Requirements: 
                  @if($remedial->min_words) Min <strong>{{ $remedial->min_words }}</strong> words @endif
                  @if($remedial->min_words && $remedial->max_words) · @endif
                  @if($remedial->max_words) Max <strong>{{ $remedial->max_words }}</strong> words @endif
                </span>
              </div>
            @endif

            <form id="writtenForm" action="{{ route('remedial.submit.store', $remedial) }}" method="POST">
              @csrf
              <textarea
                id="writtenContent"
                name="content"
                class="submission-textarea"
                placeholder="Write your beautiful solution here…"
                oninput="updateWordCount()"
              >{{ $submission->content }}</textarea>

              <div class="word-counter-bar">
                <span>Words: <strong id="wordCount" style="color:#0f172a; font-size:15px;">{{ $submission->word_count ?? 0 }}</strong></span>
                <div class="wc-progress">
                  <div class="wc-fill" id="wcFill" style="width:0%; background:#4f46e5;"></div>
                </div>
                @if($remedial->max_words)
                  <span>Limit: {{ $remedial->max_words }}</span>
                @endif
              </div>

              <button type="submit" class="btn-submit" id="submitBtn">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                Finalize & Submit Assignment
              </button>
            </form>
          </div>

        {{-- 2. File Upload submission area --}}
        @elseif($remedial->action_type === 'file_upload' && $submission->can_edit)
          <div class="premium-card">
            <div class="card-title" style="margin-bottom:24px;">
              <svg width="20" height="20" fill="none" stroke="#4f46e5" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
              Upload Submission File
            </div>

            @if($submission->file_path)
              <div style="padding:16px 20px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:16px; margin-bottom:24px; display:flex; align-items:center; gap:12px;">
                <span style="font-size:24px;">📄</span>
                <div style="flex:1;">
                  <div style="font-weight:700; color:#166534; font-size:14px;">{{ $submission->file_original_name }}</div>
                  <div style="font-size:12px; color:#15803d;">Active draft submission. Upload new file below to replace it.</div>
                </div>
              </div>
            @endif

            <form action="{{ route('remedial.submit.upload', $remedial) }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="file-drop-zone" id="dropZone" onclick="document.getElementById('fileInput').click()">
                <input type="file" id="fileInput" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip" onchange="handleFileSelect(this)">
                <div class="file-icon-wrapper">📁</div>
                <div style="font-size:16px; font-weight:800; color:#1e293b; margin-bottom:8px;">Drag & Drop your file here</div>
                <div style="font-size:13px; color:#64748b; margin-bottom:4px;">or click to browse local directory</div>
                <div style="font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.04em;">PDF, DOC, DOCX, JPG, PNG, ZIP — Max 10MB</div>
                <div id="fileSelectedName" style="margin-top:16px; font-size:14px; font-weight:800; color:#4f46e5; display:none;"></div>
              </div>

              <button type="submit" class="btn-submit" id="uploadBtn" disabled>
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Confirm Upload & Submit
              </button>
            </form>
          </div>

        {{-- 3. Quiz/Test redirect card --}}
        @elseif(in_array($remedial->action_type, ['quiz_test', 'practice_session']))
          <div class="premium-card" style="text-align:center; padding:56px 40px;">
            <div style="width: 80px; height: 80px; background: rgba(99, 102, 241, 0.08); border-radius: 24px; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; font-size: 36px;">🧩</div>
            <h3 style="font-size:22px; font-weight:800; color:#0f172a; margin-bottom:12px;">Interactive Quiz Challenge</h3>
            <p style="color:#64748b; font-size:14px; margin-bottom:32px; max-width:440px; margin-inline:auto; line-height:1.7;">
              This task contains an active interactive quiz. Check your assignments to initiate and complete this attempt.
            </p>
            <a href="{{ route('student.tasks') }}" class="btn-submit" style="text-decoration:none; max-width: 260px; margin: 0 auto; display: inline-flex;">
              🚀 Go to My Quizzes
            </a>
          </div>

        {{-- 4. Attendance confirmation area --}}
        @elseif($submission->can_edit)
          <div class="premium-card">
            <div class="card-title" style="margin-bottom:24px;">
              <svg width="20" height="20" fill="none" stroke="#4f46e5" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              Confirm Completion
            </div>
            <form action="{{ route('remedial.submit.store', $remedial) }}" method="POST">
              @csrf
              <div style="margin-bottom:24px;">
                <label class="attendance-option">
                  <input type="checkbox" name="attended" value="1" required class="attendance-checkbox">
                  <div style="flex:1;">
                    <div style="font-weight:700; color:#0f172a; font-size:14px; margin-bottom:4px;">I confirm attendance & completion</div>
                    <div style="font-size:12px; color:#64748b;">Marking this indicates you have fully attended this {{ str_replace('_',' ', $remedial->action_type) }} session.</div>
                  </div>
                </label>
              </div>

              <textarea name="content" class="submission-textarea" style="min-height:160px;" placeholder="Optional: Leave notes or key takeaways here…">{{ $submission->content }}</textarea>

              <button type="submit" class="btn-submit">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Confirm Completion
              </button>
            </form>
          </div>

        {{-- 5. Already submitted status area --}}
        @else
          <div class="premium-card" style="text-align:center; padding:56px 40px;">
            @php
              $sLabel = $submission->status_label;
              $sColor = $submission->status_color;
            @endphp
            <div style="font-size:56px; margin-bottom:20px;">{{ $submission->submission_status === 'reviewed' ? '🏆' : '⏳' }}</div>
            <span class="ws-badge" style="background:{{ $sColor }}15; color:{{ $sColor }}; border-color:{{ $sColor }}30; margin-bottom:16px;">
              {{ $sLabel }}
            </span>
            <p style="font-size:14px; color:#64748b; max-width: 320px; margin: 0 auto; line-height: 1.6;">
              @if($submission->submission_status === 'submitted')
                Your response was successfully uploaded. It is currently being reviewed by your assigned teacher.
              @elseif($submission->submission_status === 'reviewed')
                Your evaluation results have been finalized. Review evaluation parameters above.
              @endif
            </p>
          </div>
        @endif
      </div>

      {{-- RIGHT COLUMN: Sidebar Metadata & Stats --}}
      <div>
        <div class="sidebar-block">
          <h3 class="sidebar-title">Student Profile</h3>
          <div class="detail-row">
            <span class="detail-label">Name</span>
            <span class="detail-value">{{ $student->name }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Roll Number</span>
            <span class="detail-value">{{ $student->roll_no }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Class</span>
            <span class="detail-value">{{ $student->class }} - {{ $student->section }}</span>
          </div>
        </div>

        <div class="sidebar-block">
          <h3 class="sidebar-title">Gamification Status</h3>
          <div class="detail-row">
            <span class="detail-label">🏆 Current XP</span>
            <span class="detail-value" style="color:#4f46e5;">{{ $student->xp_points }} XP</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">🔥 Day Streak</span>
            <span class="detail-value" style="color:#ea580c;">{{ $student->study_streak }} Days</span>
          </div>
        </div>

        <div class="sidebar-block" style="background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%); border-color: #bbf7d0;">
          <h3 class="sidebar-title" style="color: #15803d;">Learning Benefits</h3>
          <div style="font-size: 13px; color:#166534; line-height: 1.7; font-weight: 500;">
            Participating in this remedial workout expands your cognitive retention and awards up to <strong>+50 XP</strong> for successful completion!
          </div>
        </div>
      </div>

    </div>
  </div>

  @push('scripts')
  <script>
    const minWords = {{ $remedial->min_words ?? 0 }};
    const maxWords = {{ $remedial->max_words ?? 0 }};

    function updateWordCount() {
      const text = document.getElementById('writtenContent')?.value || '';
      const words = text.trim() === '' ? 0 : text.trim().split(/\s+/).length;
      const wc = document.getElementById('wordCount');
      const fill = document.getElementById('wcFill');
      const btn = document.getElementById('submitBtn');

      if (wc) wc.textContent = words;

      if (fill && maxWords > 0) {
        const pct = Math.min(100, Math.round((words / maxWords) * 100));
        fill.style.width = pct + '%';
        
        if (words > maxWords) {
          fill.style.background = '#ef4444';
        } else if (words >= minWords) {
          fill.style.background = '#10b981';
        } else {
          fill.style.background = '#4f46e5';
        }
      }

      if (btn) {
        const tooFew = minWords > 0 && words < minWords;
        const tooMany = maxWords > 0 && words > maxWords;
        btn.disabled = tooFew || tooMany;
        btn.title = tooFew ? `Requires at least ${minWords} words` : (tooMany ? `Exceeds maximum ${maxWords} words` : '');
      }
    }

    // 30-Second Draft Autosave
    @if(in_array($remedial->action_type, ['written_assignment','essay','assignment']))
    let autosaveTimer = null;
    const DRAFT_URL = '{{ route('remedial.submit.draft', $remedial) }}';
    const CSRF = '{{ csrf_token() }}';

    function triggerAutosave() {
      const content = document.getElementById('writtenContent')?.value || '';
      const status = document.getElementById('autosaveStatus');

      if (status) {
        status.className = 'autosave-indicator saving';
        status.innerHTML = '<span class="autosave-dot"></span> Autosaving…';
      }

      fetch(DRAFT_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ content })
      })
      .then(r => r.json())
      .then(data => {
        if (status) {
          status.className = 'autosave-indicator saved';
          status.innerHTML = `<span class="autosave-dot"></span> Synced ${data.saved_at}`;
        }
      })
      .catch(() => {
        if (status) {
          status.className = 'autosave-indicator';
          status.innerHTML = '<span class="autosave-dot" style="background:#ef4444;"></span> Retry failed';
        }
      });
    }

    document.getElementById('writtenContent')?.addEventListener('input', () => {
      clearTimeout(autosaveTimer);
      autosaveTimer = setTimeout(triggerAutosave, 30000);
    });

    updateWordCount();
    @endif

    // File Upload Zone Selector
    function handleFileSelect(input) {
      const file = input.files[0];
      const nameEl = document.getElementById('fileSelectedName');
      const btn = document.getElementById('uploadBtn');
      if (file && nameEl && btn) {
        nameEl.textContent = 'Selected: ' + file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
        nameEl.style.display = 'block';
        btn.disabled = false;
      }
    }

    // Interactive drag-and-drop
    const dropZone = document.getElementById('dropZone');
    if (dropZone) {
      dropZone.addEventListener('dragover', e => {
        e.preventDefault();
        dropZone.classList.add('drag-over');
      });
      dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
      dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file) {
          const input = document.getElementById('fileInput');
          const dt = new DataTransfer();
          dt.items.add(file);
          input.files = dt.files;
          handleFileSelect(input);
        }
      });
    }
  </script>
  @endpush

</x-app-layout>
