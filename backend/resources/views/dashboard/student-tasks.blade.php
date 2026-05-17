<x-app-layout>
  <x-slot name="title">My Learning Workspace</x-slot>

  <style>
    /* ── Typography & Globals ── */
    .font-poppins { font-family: 'Poppins', sans-serif; }
    .text-primary { color: var(--primary); }
    .bg-primary { background: var(--primary); }
    .text-muted { color: var(--text-muted); }

    /* ── Page Header ── */
    .workspace-header {
      background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 32px;
      box-shadow: var(--shadow-sm);
      position: relative;
      overflow: hidden;
    }
    .workspace-header::after {
      content: ''; position: absolute; right: 0; top: 0; width: 400px; height: 100%;
      background: radial-gradient(circle at top right, rgba(108,92,231,0.06), transparent 70%);
      pointer-events: none;
    }

    /* ── Top Stats Grid ── */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 24px;
      margin-bottom: 32px;
    }
    .stat-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 24px;
      display: flex;
      align-items: center;
      gap: 20px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.02);
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    .stat-icon {
      width: 56px; height: 56px; border-radius: 16px;
      display: flex; align-items: center; justify-content: center; font-size: 24px;
    }

    /* ── Layout ── */
    .workspace-layout {
      display: grid;
      grid-template-columns: 1fr 340px;
      gap: 32px;
    }
    @media (max-width: 1024px) {
      .workspace-layout { grid-template-columns: 1fr; }
    }

    /* ── Filter Tabs ── */
    .filter-tabs {
      display: flex; gap: 12px; margin-bottom: 24px; overflow-x: auto; padding-bottom: 8px;
    }
    .filter-tab {
      background: #fff; border: 1px solid var(--border); padding: 8px 16px; border-radius: 100px;
      font-size: 14px; font-weight: 600; color: #475569; cursor: pointer; transition: all 0.2s;
      white-space: nowrap; box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    .filter-tab:hover { background: #f8fafc; color: #0f172a; }
    .filter-tab.active { background: var(--primary); color: #fff; border-color: var(--primary); box-shadow: 0 4px 12px rgba(108,92,231,0.2); }

    /* ── Tasks Grid ── */
    .tasks-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 24px;
    }
    .task-card {
      background: #fff; border: 1px solid var(--border); border-radius: 16px;
      display: flex; flex-direction: column; position: relative; overflow: hidden;
      box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .task-card:hover {
      transform: translateY(-4px) scale(1.01);
      box-shadow: 0 20px 40px rgba(108,92,231,0.1);
      border-color: rgba(108,92,231,0.3);
    }
    .tc-priority-strip { position: absolute; top: 0; left: 0; right: 0; height: 4px; }
    .p-critical { background: var(--error); }
    .p-high { background: var(--warning); }
    .p-medium { background: var(--primary); }
    .p-low { background: #cbd5e1; }

    .tc-header { padding: 20px; border-bottom: 1px solid rgba(0,0,0,0.03); }
    .tc-subject { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--primary); margin-bottom: 6px; display: flex; align-items: center; gap: 6px; }
    .tc-title { font-family: 'Poppins', sans-serif; font-size: 16px; font-weight: 700; color: #111827; line-height: 1.3; margin-bottom: 8px; }
    .tc-teacher { font-size: 12px; color: #64748b; display: flex; align-items: center; gap: 4px; }

    .tc-body { padding: 20px; flex-grow: 1; }
    .tc-progress-bg { height: 6px; background: #f1f5f9; border-radius: 100px; overflow: hidden; margin-bottom: 6px; }
    .tc-progress-fill { height: 100%; border-radius: 100px; }
    .tc-meta { display: flex; justify-content: space-between; font-size: 12px; color: #64748b; font-weight: 600; margin-bottom: 16px; }
    
    .tc-urgency { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; }
    .u-overdue { background: #fee2e2; color: #dc2626; }
    .u-soon { background: #fef3c7; color: #d97706; }
    .u-normal { background: #f1f5f9; color: #64748b; }
    .u-done { background: #dcfce7; color: #16a34a; }

    .tc-footer { padding: 16px 20px; background: #fdfdfd; border-top: 1px solid rgba(0,0,0,0.03); display: flex; gap: 8px; }
    .tc-btn { flex: 1; text-align: center; padding: 8px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s; border: none; }
    .tc-btn-primary { background: var(--primary); color: #fff; box-shadow: 0 4px 10px rgba(108,92,231,0.2); }
    .tc-btn-primary:hover { background: #5a4bcf; box-shadow: 0 6px 15px rgba(108,92,231,0.3); }
    .tc-btn-outline { background: #fff; color: #475569; border: 1px solid #cbd5e1; }
    .tc-btn-outline:hover { background: #f8fafc; color: #0f172a; border-color: #94a3b8; }

    /* ── Sidebar Panels ── */
    .sb-panel { background: #fff; border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
    .sb-title { font-family: 'Poppins', sans-serif; font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }

    .gami-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid rgba(0,0,0,0.04); }
    .gami-row:last-child { border-bottom: none; padding-bottom: 0; }
    .gami-label { font-size: 13px; color: #64748b; font-weight: 600; }
    .gami-val { font-size: 15px; font-weight: 800; color: #111827; }

    .rec-card { background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border: 1px solid #fde68a; border-radius: 12px; padding: 16px; text-align: center; }
    
    .tl-item { display: flex; gap: 12px; margin-bottom: 16px; }
    .tl-item:last-child { margin-bottom: 0; }
    .tl-icon { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
    .tl-content { flex: 1; }
    .tl-title { font-size: 13px; font-weight: 600; color: #1e293b; line-height: 1.4; }
    .tl-date { font-size: 11px; color: #94a3b8; margin-top: 2px; }

    /* ── Modal ── */
    .modal-overlay { position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 50; opacity: 0; transition: opacity 0.3s; }
    .modal-overlay.active { display: flex; opacity: 1; }
    .modal-content { background: #fff; width: 90%; max-width: 500px; border-radius: var(--radius-lg); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); transform: scale(0.95); transition: transform 0.3s; padding: 32px; }
    .modal-overlay.active .modal-content { transform: scale(1); }
  </style>

  {{-- ── Workspace Header ── --}}
  <div class="workspace-header">
    <div>
      <h2 class="font-poppins text-2xl font-bold text-gray-900" style="font-size:28px;">My Workspace</h2>
      <p class="text-muted mt-1" style="font-size:15px;">Your central hub for tasks, achievements, and active learning.</p>
    </div>
    <a href="{{ route('dashboard.student') }}" class="btn btn-outline" style="background:#fff; padding:10px 16px;">← Back to Dashboard</a>
  </div>

  {{-- ── KPI Statistics ── --}}
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon" style="background: rgba(108,92,231,0.1); color: var(--primary);">⚡</div>
      <div>
        <div class="font-poppins font-bold text-2xl text-gray-900">{{ $xpEarned }}</div>
        <div class="text-xs font-bold text-muted uppercase tracking-wider">Activity XP</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background: rgba(245,158,11,0.1); color: var(--warning);">⏳</div>
      <div>
        <div class="font-poppins font-bold text-2xl text-gray-900">{{ $stats['pending'] }}</div>
        <div class="text-xs font-bold text-muted uppercase tracking-wider">Pending Tasks</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background: rgba(0,196,140,0.1); color: var(--success);">✅</div>
      <div>
        <div class="font-poppins font-bold text-2xl text-gray-900">{{ $stats['completed'] }}</div>
        <div class="text-xs font-bold text-muted uppercase tracking-wider">Completed</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background: rgba(239,68,68,0.1); color: var(--error);">🚨</div>
      <div>
        <div class="font-poppins font-bold text-2xl text-gray-900">{{ $stats['overdue'] }}</div>
        <div class="text-xs font-bold text-muted uppercase tracking-wider">Overdue</div>
      </div>
    </div>
  </div>

  <div class="workspace-layout">
    {{-- ── Main Content Area ── --}}
    <div>
      {{-- Filter Tabs --}}
      <div class="filter-tabs" id="task-filters">
        <div class="filter-tab active" data-filter="all">All Tasks ({{ $stats['total'] }})</div>
        <div class="filter-tab" data-filter="pending">Pending ({{ $stats['pending'] }})</div>
        <div class="filter-tab" data-filter="completed">Completed ({{ $stats['completed'] }})</div>
        @if($stats['overdue'] > 0)
          <div class="filter-tab" data-filter="overdue" style="color:var(--error); border-color:#fecaca;">Overdue ({{ $stats['overdue'] }})</div>
        @endif
      </div>

      {{-- Tasks Grid --}}
      <div class="tasks-grid">
        @forelse($assignedTasks as $task)
          @php
            $progress = 0;
            if($task->status === 'completed') $progress = 100;
            elseif($task->status === 'in_progress') $progress = 50;
            elseif($task->submission && $task->submission->submission_status === 'submitted') $progress = 75;

            $urgencyClass = 'u-normal';
            $urgencyText  = 'No deadline';
            if($task->status === 'completed') {
              $urgencyClass = 'u-done';
              $urgencyText  = 'Completed ✓';
            } elseif($task->is_overdue) {
              $urgencyClass = 'u-overdue';
              $urgencyText  = 'Overdue by ' . (int) now()->diffInDays($task->due_date) . ' days';
            } elseif($task->is_due_soon) {
              $urgencyClass = 'u-soon';
              $urgencyText  = 'Due in ' . max(1, (int) now()->diffInDays($task->due_date)) . ' days';
            } elseif($task->due_date) {
              $urgencyText  = 'Due ' . $task->due_date->format('M d, Y');
            }

            $isQuiz = in_array($task->action_type, ['quiz_test', 'practice_session']);
            $activeQuiz = $quizAssignments->where('status', 'active')->first();
            $quizStartUrl = $activeQuiz ? route('quiz.start', $activeQuiz) : null;

            $subStatus = $task->submission?->submission_status ?? null;
          @endphp

          <div class="task-card filter-item" data-status="{{ $task->status }}" data-overdue="{{ $task->is_overdue ? '1' : '0' }}">
            <div class="tc-priority-strip
              @if($task->priority === 'Critical') p-critical
              @elseif($task->priority === 'High') p-high
              @elseif($task->priority === 'Medium') p-medium
              @else p-low @endif"></div>

            <div class="tc-header">
              <div class="tc-subject">{{ $task->action_type_label }}</div>
              <h3 class="tc-title">{{ $task->title }}</h3>
              <div class="tc-teacher">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                {{ $task->assignedByUser?->name ?? 'Teacher' }}
                @if($subStatus)
                  &nbsp;·
                  <span style="padding:1px 7px; border-radius:100px; font-size:10px; font-weight:700;
                    background:{{ $task->submission->status_color }}20; color:{{ $task->submission->status_color }}">
                    {{ $task->submission->status_label }}
                  </span>
                @endif
              </div>
            </div>

            <div class="tc-body">
              <div class="tc-progress-bg">
                <div class="tc-progress-fill" style="width:{{ $progress }}%; background: var(--{{ $progress == 100 ? 'success' : 'primary' }});"></div>
              </div>
              <div class="tc-meta">
                <span>Progress</span>
                <span style="color:var(--{{ $progress == 100 ? 'success' : 'primary' }});">{{ $progress }}%</span>
              </div>
              <div class="tc-urgency {{ $urgencyClass }}">
                @if($urgencyClass === 'u-overdue') 🚨
                @elseif($urgencyClass === 'u-soon') ⏳
                @elseif($urgencyClass === 'u-done') ✅
                @else 📅 @endif
                {{ $urgencyText }}
              </div>
            </div>

            <div class="tc-footer">
              @if($task->status !== 'completed')
                @if($isQuiz && $activeQuiz)
                  <a href="{{ $quizStartUrl }}" class="tc-btn tc-btn-primary" style="display:inline-block; text-decoration:none; box-sizing:border-box;">🚀 Start Quiz</a>
                @elseif($task->is_interactive)
                  <a href="{{ route('remedial.submit.show', $task) }}" class="tc-btn tc-btn-primary" style="display:inline-block; text-decoration:none; box-sizing:border-box;">
                    {{ $subStatus === 'needs_improvement' ? '🔄 Resubmit' : ($subStatus === 'submitted' ? '👁 View Submission' : ($task->status === 'in_progress' ? '▶ Continue' : '🚀 Start Task')) }}
                  </a>
                @else
                  <a href="{{ route('remedial.submit.show', $task) }}" class="tc-btn tc-btn-primary" style="display:inline-block; text-decoration:none; box-sizing:border-box;">✅ Confirm</a>
                @endif
              @else
                <span class="tc-btn" style="background:#ecfdf5; color:#10b981; cursor:default;">✅ Completed</span>
              @endif
            </div>
          </div>
        @empty
          <div style="grid-column: 1 / -1; background:#fff; border:1px dashed #cbd5e1; border-radius:16px; padding:64px; text-align:center;">
            <div style="width:80px; height:80px; border-radius:50%; background:#f8fafc; display:flex; align-items:center; justify-content:center; font-size:32px; margin:0 auto 16px;">🎉</div>
            <h3 class="font-poppins font-bold text-xl text-gray-900 mb-2">You're all caught up!</h3>
            <p class="text-muted max-w-md mx-auto">You have no tasks assigned right now. Great work!</p>
          </div>
        @endforelse
      </div>
    </div>

    {{-- ── Sidebar ── --}}
    <div>
      {{-- Assigned Quizzes --}}
      @if(isset($quizAssignments) && $quizAssignments->count() > 0)
        <div class="sb-panel" style="background: linear-gradient(135deg, #fff 0%, #fdfbfb 100%);">
          <h3 class="sb-title">📝 My Quizzes</h3>
          @foreach($quizAssignments as $qa)
            @php
              $quiz = $qa->quiz;
              $attemptsUsed = $qa->attempts_used;
              $totalAttempts = $qa->repeat_days;
              $progress = $totalAttempts > 0 ? round(($attemptsUsed / $totalAttempts) * 100) : 0;
              $canAttempt = $qa->today_attempt_available;
              $latestScore = $qa->latest_score;
            @endphp
            <div class="rec-card" style="margin-bottom: {{ $loop->last ? '0' : '12px' }}; border-left: 3px solid {{ $qa->status === 'completed' ? '#10b981' : '#6C5CE7' }};">
              <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px;">
                <div style="font-size:14px; font-weight:700; color:#0f172a;">{{ $quiz->title }}</div>
                <span style="display:inline-flex;padding:2px 8px;border-radius:100px;font-size:10px;font-weight:700;
                  background:{{ $qa->status === 'completed' ? '#ecfdf5' : '#eef2ff' }};
                  color:{{ $qa->status === 'completed' ? '#10b981' : '#6C5CE7' }};">
                  {{ $qa->status === 'completed' ? 'Done' : 'Active' }}
                </span>
              </div>
              <div style="font-size:12px;color:#64748b;margin-bottom:4px;">📚 {{ $quiz->subject->name ?? 'General' }} · {{ ucfirst($quiz->difficulty_level) }}</div>
              <div style="font-size:12px;color:#94a3b8;margin-bottom:10px;">Attempts: {{ $attemptsUsed }}/{{ $totalAttempts }}
                @if($latestScore !== null) · Last: {{ $latestScore }}% @endif
              </div>
              <div style="height:6px;background:#f1f5f9;border-radius:100px;overflow:hidden;margin-bottom:10px;">
                <div style="height:100%;width:{{ $progress }}%;background:linear-gradient(90deg,#6C5CE7,#8B5CF6);border-radius:100px;transition:width 0.4s;"></div>
              </div>
              @if($canAttempt)
                <a href="{{ route('quiz.start', $qa) }}" class="tc-btn tc-btn-primary" style="width:100%;padding:8px;font-size:12px;text-align:center;display:block;text-decoration:none;border-radius:8px;">
                  🚀 Start Quiz
                </a>
              @elseif($qa->status === 'completed')
                <div style="text-align:center;font-size:12px;color:#10b981;font-weight:600;">✅ All attempts completed!</div>
              @else
                <div style="text-align:center;font-size:12px;color:#94a3b8;font-weight:500;">⏳ Next attempt unlocks tomorrow</div>
              @endif
            </div>
          @endforeach
        </div>
      @endif

      {{-- Recommended Quizzes (Smart Recommendations) --}}
      @if(isset($recommendedQuizzes) && $recommendedQuizzes->count() > 0)
        <div class="sb-panel" style="background: linear-gradient(135deg, #eef2ff 0%, #fff 100%); border-color:#c7d2fe;">
          <h3 class="sb-title" style="color:#4338ca;">🎯 Recommended for You</h3>
          <p style="font-size:12px; color:#6366f1; margin:-8px 0 14px;">Based on your weak subjects</p>
          @foreach($recommendedQuizzes as $rq)
            <div style="padding:12px 14px; background:#fff; border:1px solid #e0e7ff; border-radius:12px; margin-bottom:10px;">
              <div style="font-weight:700; font-size:13px; color:#1e293b; margin-bottom:4px;">{{ $rq->title }}</div>
              <div style="font-size:12px; color:#64748b; margin-bottom:8px;">📚 {{ $rq->subject->name ?? 'General' }} · {{ ucfirst($rq->difficulty_level) }} · {{ $rq->question_count }} Qs</div>
              <div style="font-size:11px; color:#6366f1; font-weight:700;">Ask your teacher to assign this quiz!</div>
            </div>
          @endforeach
        </div>
      @endif

      {{-- Recommended Practice (weak subjects) --}}
      @if($weakSubjects->count() > 0)
        <div class="sb-panel" style="background: linear-gradient(135deg, #fff 0%, #fdfbfb 100%);">
          <h3 class="sb-title">🎯 Focus Areas</h3>
          @foreach($weakSubjects as $weak)
            <div class="rec-card" style="margin-bottom: {{ $loop->last ? '0' : '12px' }};">
              <div style="font-size:24px; margin-bottom:8px;">💡</div>
              <div style="font-size:13px; font-weight:700; color:#b45309; margin-bottom:4px;">{{ $weak['name'] }}</div>
              <div style="font-size:12px; color:#92400e;">Your average is currently {{ $weak['pct'] }}%. Ask your teacher for practice quizzes.</div>
            </div>
          @endforeach
        </div>
      @endif

      {{-- Gamification Profile --}}
      <div class="sb-panel">
        <h3 class="sb-title">🎮 Gamification Profile</h3>
        <div class="gami-row">
          <span class="gami-label">Activity Level</span>
          <span class="gami-val text-primary">{{ $xpEarned >= 200 ? 'Expert' : ($xpEarned >= 100 ? 'Intermediate' : 'Novice') }}</span>
        </div>
        <div class="gami-row">
          <span class="gami-label">Class Rank</span>
          <span class="gami-val">#{{ $rank }}</span>
        </div>
        <div class="gami-row">
          <span class="gami-label">Study Streak</span>
          <span class="gami-val" style="color:var(--warning);">🔥 {{ $streak }} Days</span>
        </div>
      </div>

      {{-- Activity Timeline --}}
      @if($timeline->count() > 0)
        <div class="sb-panel">
          <h3 class="sb-title">⏱ Recent Activity</h3>
          <div>
            @foreach($timeline as $act)
              <div class="tl-item">
                <div class="tl-icon" style="background: {{ $act['color'] }}20; color: {{ $act['color'] }};">{{ $act['icon'] }}</div>
                <div class="tl-content">
                  <div class="tl-title">{{ $act['title'] }}</div>
                  <div class="tl-date">{{ $act['date']->diffForHumans() }}</div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </div>

  {{-- ── Task Details Modal ── --}}
  <div class="modal-overlay" id="taskModal" onclick="closeTaskModal(event)">
    <div class="modal-content" onclick="event.stopPropagation()">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h3 class="font-poppins font-bold text-xl text-gray-900" id="modalTitle">Task Details</h3>
        <button onclick="closeTaskModal()" style="background:none; border:none; font-size:24px; color:#94a3b8; cursor:pointer;">&times;</button>
      </div>
      <p id="modalDesc" style="color:var(--text-muted); font-size:14px; line-height:1.6; margin-bottom:32px;"></p>
      <div style="display:flex; gap:12px; justify-content:flex-end;" id="modalActions">
        <button class="tc-btn tc-btn-outline" onclick="closeTaskModal()">Close</button>
        <button class="tc-btn tc-btn-primary" onclick="alert('Feature coming soon!'); closeTaskModal();">Proceed to Workspace</button>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      // ── Tab Filtering Logic ──
      const tabs = document.querySelectorAll('.filter-tab');
      const items = document.querySelectorAll('.filter-item');

      tabs.forEach(tab => {
        tab.addEventListener('click', () => {
          tabs.forEach(t => t.classList.remove('active'));
          tab.classList.add('active');
          const filter = tab.getAttribute('data-filter');

          items.forEach(item => {
            if (filter === 'all') {
              item.style.display = 'flex';
            } else if (filter === 'overdue') {
              item.style.display = item.getAttribute('data-overdue') === '1' ? 'flex' : 'none';
            } else {
              item.style.display = item.getAttribute('data-status') === filter ? 'flex' : 'none';
            }
          });
        });
      });

      // ── Modal Logic ──
      function openTaskModal(title, desc, isQuiz, quizUrl) {
        document.getElementById('modalTitle').innerText = title;
        document.getElementById('modalDesc').innerText = desc;

        const actionsContainer = document.getElementById('modalActions');
        if (isQuiz && quizUrl) {
          actionsContainer.innerHTML = `
            <button class="tc-btn tc-btn-outline" onclick="closeTaskModal()">Close</button>
            <a href="${quizUrl}" class="tc-btn tc-btn-primary" style="display:inline-block; text-decoration:none; text-align:center;">🚀 Start Assigned Quiz</a>
          `;
        } else if (isQuiz) {
          actionsContainer.innerHTML = `
            <button class="tc-btn tc-btn-outline" onclick="closeTaskModal()">Close</button>
            <button class="tc-btn tc-btn-primary" onclick="alert('No active interactive quiz assigned for this task yet. Check your My Quizzes panel or ask your teacher!'); closeTaskModal();">Check Assigned Quizzes</button>
          `;
        } else {
          actionsContainer.innerHTML = `
            <button class="tc-btn tc-btn-outline" onclick="closeTaskModal()">Close</button>
            <button class="tc-btn tc-btn-primary" onclick="alert('Task marked as acknowledged. Keep up the great work in your studies!'); closeTaskModal();">Mark in Progress</button>
          `;
        }

        document.getElementById('taskModal').classList.add('active');
      }

      function closeTaskModal(event) {
        if(event && event.target !== document.getElementById('taskModal')) return;
        document.getElementById('taskModal').classList.remove('active');
      }
    </script>
  @endpush

</x-app-layout>
