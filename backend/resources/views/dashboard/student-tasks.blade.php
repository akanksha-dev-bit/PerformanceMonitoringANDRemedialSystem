<x-app-layout>
  <x-slot name="title">My Tasks</x-slot>

  @push('styles')
    <style>
      .ts-page { max-width:1440px; margin:0 auto; color:#18233d; }
      .ts-header { display:flex; justify-content:space-between; align-items:flex-end; gap:18px; margin-bottom:24px; }
      .ts-eyebrow { display:block; margin-bottom:7px; color:#6b5de4; font-size:11px; font-weight:800; letter-spacing:.14em; text-transform:uppercase; }
      .ts-header h1 { margin:0; color:#14203b; font-size:clamp(27px,3vw,34px); line-height:1.15; letter-spacing:-.04em; }
      .ts-header p { margin:8px 0 0; color:#758197; font-size:14px; }
      .ts-back { display:inline-flex; align-items:center; justify-content:center; gap:8px; flex:none; min-height:40px; padding:9px 13px; border:1px solid #e2e7f2; border-radius:11px; background:#fff; color:#4c5972; text-decoration:none; font-size:12px; font-weight:750; box-shadow:0 3px 9px rgba(30,42,79,.04); }
      .ts-back svg { width:16px; height:16px; flex:none; stroke:currentColor; }
      .ts-back:hover { border-color:#bcb5f8; color:#5d50d8; background:#f9f8ff; }
      .ts-back:focus-visible,.ts-filter:focus-visible,.ts-action:focus-visible { outline:3px solid #8174ef; outline-offset:3px; }
      .ts-summary { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:14px; margin-bottom:21px; }
      .ts-stat { min-width:0; position:relative; overflow:hidden; border:1px solid #e8eaf3; border-radius:18px; background:#fff; padding:19px 20px; box-shadow:0 7px 25px rgba(30,42,79,.045); }
      .ts-stat::after { content:""; position:absolute; width:95px; height:95px; top:-53px; right:-39px; border-radius:50%; background:var(--tint,#e9e6ff); filter:blur(12px); }
      .ts-stat:nth-child(2) { --tint:#dcf7ef; }
      .ts-stat:nth-child(3) { --tint:#fff0d9; }
      .ts-stat:nth-child(4) { --tint:#ffe8e6; }
      .ts-stat-label { position:relative; z-index:1; display:block; color:#8390a2; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; }
      .ts-stat-value { position:relative; z-index:1; display:block; margin:9px 0 4px; color:#18243d; font-size:30px; font-weight:850; line-height:1; letter-spacing:-.04em; }
      .ts-stat-note { position:relative; z-index:1; color:#8d99aa; font-size:11px; }
      .ts-layout { display:grid; grid-template-columns:minmax(0,1fr) minmax(280px,320px); gap:20px; align-items:start; }
      .ts-main,.ts-aside { min-width:0; }
      .ts-filterbar { display:flex; align-items:center; gap:7px; max-width:100%; overflow-x:auto; padding:0 0 12px; margin-bottom:6px; scrollbar-width:thin; }
      .ts-filter { display:inline-flex; align-items:center; justify-content:center; gap:7px; flex:none; min-height:35px; padding:8px 12px; border:1px solid #e4e8f1; border-radius:10px; background:#fff; color:#627087; font:inherit; font-size:11px; font-weight:750; white-space:nowrap; cursor:pointer; transition:background .2s,border-color .2s,color .2s; }
      .ts-filter:hover { border-color:#bcb5f8; color:#5b4ed2; }
      .ts-filter[aria-pressed="true"] { border-color:#6858e7; background:#6858e7; color:#fff; box-shadow:0 5px 13px rgba(104,88,231,.2); }
      .ts-filter-count { padding:2px 5px; border-radius:5px; background:rgba(106,89,226,.09); font-size:10px; }
      .ts-filter[aria-pressed="true"] .ts-filter-count { background:rgba(255,255,255,.2); }
      .ts-task-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(min(100%,285px),1fr)); gap:14px; }
      .ts-task { display:flex; flex-direction:column; min-width:0; overflow:hidden; border:1px solid #e8eaf3; border-radius:18px; background:#fff; box-shadow:0 7px 25px rgba(30,42,79,.045); transition:transform .22s,box-shadow .22s,border-color .22s; }
      .ts-task:hover { transform:translateY(-3px); border-color:#d0caf7; box-shadow:0 15px 32px rgba(48,42,105,.1); }
      .ts-task[hidden] { display:none !important; }
      .ts-task-top { height:4px; background:var(--priority-color,#8a7cf0); }
      .ts-task[data-priority="Critical"] { --priority-color:#e97770; }
      .ts-task[data-priority="High"] { --priority-color:#efad5f; }
      .ts-task[data-priority="Medium"] { --priority-color:#8b7af0; }
      .ts-task[data-priority="Low"] { --priority-color:#91bbd6; }
      .ts-task-content { flex:1; padding:18px 19px 16px; }
      .ts-task-tags { display:flex; align-items:center; flex-wrap:wrap; gap:6px; margin-bottom:13px; }
      .ts-type { padding:5px 8px; border-radius:8px; background:#f0eeff; color:#6253d5; font-size:10px; font-weight:800; }
      .ts-priority { padding:5px 7px; border-radius:8px; background:#f6f7fa; color:#718096; font-size:10px; font-weight:800; }
      .ts-task-title { margin:0; color:#19243c; font-size:16px; line-height:1.3; font-weight:800; overflow-wrap:anywhere; }
      .ts-task-desc { margin:7px 0 0; color:#79869b; font-size:12px; line-height:1.5; display:-webkit-box; -webkit-box-orient:vertical; -webkit-line-clamp:2; overflow:hidden; }
      .ts-task-meta { display:flex; flex-wrap:wrap; align-items:center; gap:7px 12px; margin-top:14px; color:#8591a3; font-size:11px; }
      .ts-task-meta span { display:inline-flex; align-items:center; gap:5px; }
      .ts-task-meta svg { width:13px; height:13px; stroke:currentColor; fill:none; flex:none; }
      .ts-stage { margin-top:19px; }
      .ts-stage-row { display:flex; justify-content:space-between; align-items:center; gap:8px; margin-bottom:8px; font-size:11px; font-weight:750; }
      .ts-stage-row span:first-child { color:#7e8a9d; }
      .ts-stage-row span:last-child { color:#5f52d5; }
      .ts-stage-track { display:grid; grid-template-columns:repeat(4,1fr); gap:4px; }
      .ts-stage-track span { height:5px; border-radius:99px; background:#e9edf4; }
      .ts-stage-track span.is-filled { background:linear-gradient(90deg,#6c5ce7,#a092f7); }
      .ts-task-footer { display:flex; align-items:center; justify-content:space-between; gap:9px; padding:12px 19px 16px; border-top:1px solid #f0f2f7; }
      .ts-due { display:inline-flex; align-items:center; max-width:48%; padding:6px 8px; border-radius:8px; background:#f3f5f9; color:#6b7890; font-size:10px; font-weight:750; line-height:1.25; }
      .ts-due.is-overdue { background:#fff0ed; color:#ca5f56; }
      .ts-due.is-soon { background:#fff5e7; color:#b67720; }
      .ts-due.is-done { background:#e9f9f3; color:#168d6a; }
      .ts-action { display:inline-flex; align-items:center; justify-content:center; gap:7px; min-height:35px; padding:8px 11px; border-radius:9px; background:#6657e5; color:#fff; text-align:center; text-decoration:none; font-size:11px; font-weight:800; white-space:nowrap; box-shadow:0 5px 12px rgba(99,83,221,.2); }
      .ts-action:hover { background:#5546d0; color:#fff; }
      .ts-action svg { width:13px; height:13px; stroke:currentColor; fill:none; flex:none; }
      .ts-done { color:#168d6a; font-size:11px; font-weight:800; }
      .ts-empty { grid-column:1/-1; padding:48px 20px; border:1px dashed #d7dcef; border-radius:18px; background:#fff; text-align:center; color:#7d8a9e; font-size:12px; }
      .ts-empty strong { display:block; margin:8px 0 5px; color:#1e2a43; font-size:16px; }
      .ts-empty-icon { display:grid; place-items:center; width:56px; height:56px; margin:0 auto; border-radius:16px; background:#f0eeff; color:#6757df; font-size:25px; }
      .ts-side-card { min-width:0; overflow:hidden; padding:19px; margin-bottom:14px; border:1px solid #e8eaf3; border-radius:18px; background:#fff; box-shadow:0 7px 25px rgba(30,42,79,.045); }
      .ts-side-card h2 { display:flex; align-items:center; gap:7px; margin:0 0 6px; color:#19243c; font-size:15px; }
      .ts-side-card > p { margin:0 0 14px; color:#8a96a8; font-size:11px; line-height:1.45; }
      .ts-quiz { padding:13px 0; border-top:1px solid #eef1f7; }
      .ts-quiz:first-of-type { border-top:0; }
      .ts-quiz-head { display:flex; align-items:flex-start; justify-content:space-between; gap:8px; }
      .ts-quiz-head strong { color:#263149; font-size:12px; line-height:1.35; overflow-wrap:anywhere; }
      .ts-quiz-badge { flex:none; padding:4px 6px; border-radius:7px; background:#edf8f3; color:#168d6a; font-size:9px; font-weight:800; }
      .ts-quiz-badge.is-active { background:#f0eeff; color:#6556d9; }
      .ts-quiz-meta { margin:6px 0 9px; color:#8a96a7; font-size:10px; line-height:1.4; }
      .ts-quiz-track { height:5px; margin-bottom:11px; border-radius:99px; background:#edf0f6; overflow:hidden; }
      .ts-quiz-track span { display:block; height:100%; width:var(--quiz-progress); border-radius:inherit; background:linear-gradient(90deg,#6758e5,#a291f8); }
      .ts-quiz .ts-action { width:100%; box-sizing:border-box; }
      .ts-quiz-wait { color:#8996a8; font-size:10px; font-weight:700; }
      .ts-focus { padding:10px 0; border-top:1px solid #eff1f7; }
      .ts-focus strong { display:block; font-size:12px; color:#293550; }
      .ts-focus span { display:block; margin-top:3px; color:#8995a6; font-size:10px; line-height:1.45; }
      .ts-profile-row { display:flex; align-items:center; justify-content:space-between; gap:10px; padding:10px 0; border-top:1px solid #eff1f7; font-size:11px; }
      .ts-profile-row span { color:#8290a3; }
      .ts-profile-row strong { color:#293550; font-size:12px; }
      .ts-activity { display:flex; gap:9px; padding:10px 0; border-top:1px solid #eff1f7; }
      .ts-activity-dot { flex:none; width:27px; height:27px; display:grid; place-items:center; border-radius:9px; background:#f0eeff; font-size:12px; }
      .ts-activity strong { display:block; color:#293550; font-size:11px; line-height:1.35; }
      .ts-activity small { display:block; margin-top:3px; color:#9ba5b4; font-size:10px; }
      @media(max-width:1100px) { .ts-layout { grid-template-columns:minmax(0,1fr); } .ts-aside { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; } .ts-side-card { margin:0; } }
      @media(max-width:680px) { .ts-summary { grid-template-columns:repeat(2,minmax(0,1fr)); gap:9px; } .ts-header { flex-direction:column; align-items:flex-start; } .ts-aside { grid-template-columns:1fr; } .ts-stat { padding:15px; } .ts-stat-value { font-size:25px; } .ts-filterbar { flex-wrap:wrap; overflow:visible; padding-bottom:0; margin-bottom:12px; } .ts-filter { flex:1 1 calc(50% - 7px); } }
      @media(prefers-reduced-motion:reduce) { .ts-task,.ts-filter { transition:none; } .ts-task:hover { transform:none; } }
    </style>
  @endpush

  <main class="ts-page">
    <header class="ts-header">
      <div>
        <span class="ts-eyebrow">Learning workspace</span>
        <h1>My Tasks</h1>
        <p>Keep your next steps clear and your progress moving.</p>
      </div>
      <a href="{{ route('dashboard.student') }}" class="ts-back" aria-label="Back to dashboard">
        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        <span>Dashboard</span>
      </a>
    </header>

    <section class="ts-summary" aria-label="Task summary">
      <div class="ts-stat"><span class="ts-stat-label">Pending tasks</span><strong class="ts-stat-value">{{ $stats['pending'] }}</strong><span class="ts-stat-note">Ready to work on</span></div>
      <div class="ts-stat"><span class="ts-stat-label">Completed</span><strong class="ts-stat-value">{{ $stats['completed'] }}</strong><span class="ts-stat-note">Milestones finished</span></div>
      <div class="ts-stat"><span class="ts-stat-label">Activity XP</span><strong class="ts-stat-value">{{ number_format((int) $xpEarned) }}</strong><span class="ts-stat-note">Earned across learning</span></div>
      <div class="ts-stat"><span class="ts-stat-label">Overdue</span><strong class="ts-stat-value">{{ $stats['overdue'] }}</strong><span class="ts-stat-note">Needs your attention</span></div>
    </section>

    <div class="ts-layout">
      <section class="ts-main" aria-label="Assigned tasks">
        <div class="ts-filterbar" id="task-filters" role="group" aria-label="Filter tasks">
          <button type="button" class="ts-filter" data-filter="all" aria-pressed="true">All tasks <span class="ts-filter-count">{{ $stats['total'] }}</span></button>
          <button type="button" class="ts-filter" data-filter="pending" aria-pressed="false">Pending <span class="ts-filter-count">{{ $stats['pending'] }}</span></button>
          <button type="button" class="ts-filter" data-filter="completed" aria-pressed="false">Completed <span class="ts-filter-count">{{ $stats['completed'] }}</span></button>
          <button type="button" class="ts-filter" data-filter="overdue" aria-pressed="false">Overdue <span class="ts-filter-count">{{ $stats['overdue'] }}</span></button>
        </div>

        <div class="ts-task-grid" id="task-grid">
          @forelse($assignedTasks as $task)
            @php
              $submissionStatus = $task->submission?->submission_status;
              $stage = $task->status === 'completed' ? 4
                : (in_array($submissionStatus, ['submitted', 'reviewed'], true) ? 3
                : ($task->status === 'in_progress' || in_array($submissionStatus, ['draft', 'needs_improvement'], true) ? 2 : 1));
              $stageLabel = $task->status === 'cancelled' ? 'Cancelled' : ($task->status === 'completed' ? 'Completed'
                : ($submissionStatus === 'submitted' ? 'Submitted'
                : ($submissionStatus === 'needs_improvement' ? 'Needs revision'
                : ($stage === 2 ? 'In progress' : 'Assigned'))));
              $dueClass = $task->status === 'completed' ? 'is-done' : ($task->is_overdue ? 'is-overdue' : ($task->is_due_soon ? 'is-soon' : ''));
              $dueLabel = $task->status === 'cancelled' ? 'Cancelled' : ($task->status === 'completed' ? 'Completed'
                : ($task->is_overdue ? 'Overdue · '.$task->due_date?->format('M j')
                : ($task->due_date ? 'Due '.$task->due_date->format('M j, Y') : 'No deadline')));
              $actionLabel = $submissionStatus === 'needs_improvement' ? 'Resubmit'
                : ($submissionStatus === 'submitted' ? 'View submission'
                : ($task->status === 'in_progress' ? 'Continue task' : 'Open task'));
            @endphp
            <article class="ts-task" data-status="{{ $task->status }}" data-overdue="{{ $task->is_overdue ? '1' : '0' }}" data-priority="{{ $task->priority }}">
              <div class="ts-task-top"></div>
              <div class="ts-task-content">
                <div class="ts-task-tags">
                  <span class="ts-type">{{ $task->action_type_label }}</span>
                  <span class="ts-priority">{{ $task->priority }} priority</span>
                </div>
                <h2 class="ts-task-title">{{ $task->title }}</h2>
                @if($task->description)<p class="ts-task-desc">{{ $task->description }}</p>@endif
                <div class="ts-task-meta">
                  <span>
                    <svg aria-hidden="true" viewBox="0 0 24 24" stroke-width="1.8"><circle cx="12" cy="8" r="3.5"/><path d="M5 21a7 7 0 0 1 14 0"/></svg>
                    {{ $task->assignedByUser?->name ?? 'Teacher' }}
                  </span>
                  @if($task->scheduled_date)
                    <span>
                      <svg aria-hidden="true" viewBox="0 0 24 24" stroke-width="1.8"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
                      Assigned {{ $task->scheduled_date->format('M j') }}
                    </span>
                  @endif
                </div>
                <div class="ts-stage">
                  <div class="ts-stage-row"><span>Task stage</span><span>{{ $stageLabel }}</span></div>
                  <div class="ts-stage-track" role="progressbar" aria-label="{{ $task->title }} progress" aria-valuemin="0" aria-valuemax="4" aria-valuenow="{{ $stage }}">
                    @for($step = 1; $step <= 4; $step++)<span class="{{ $step <= $stage ? 'is-filled' : '' }}"></span>@endfor
                  </div>
                </div>
              </div>
              <div class="ts-task-footer">
                <span class="ts-due {{ $dueClass }}">{{ $dueLabel }}</span>
                @if($task->status === 'completed')
                  <span class="ts-done">✓ Finished</span>
                @elseif($task->status === 'cancelled')
                  <span class="ts-done" style="color:#8793a6;">Cancelled</span>
                @else
                  <a href="{{ route('remedial.submit.show', $task) }}" class="ts-action">
                    <span>{{ $actionLabel }}</span>
                    <svg aria-hidden="true" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14m-6-6 6 6-6 6"/></svg>
                  </a>
                @endif
              </div>
            </article>
          @empty
            <div class="ts-empty"><span class="ts-empty-icon" aria-hidden="true">✓</span><strong>All clear for now</strong>No tasks have been assigned to you yet.</div>
          @endforelse
          <div class="ts-empty" id="task-filter-empty" hidden><span class="ts-empty-icon" aria-hidden="true">⌕</span><strong>No tasks in this view</strong>Try another filter to see your assigned work.</div>
        </div>
      </section>

      <aside class="ts-aside" aria-label="Learning extras">
        @if($quizAssignments->isNotEmpty())
          <section class="ts-side-card" aria-labelledby="ts-quiz-heading">
            <h2 id="ts-quiz-heading">🧩 Assigned quizzes</h2>
            <p>Practice at your own pace when an attempt is available.</p>
            @foreach($quizAssignments as $assignment)
              @php
                $attemptsUsed = $assignment->attempts_used;
                $attemptLimit = $assignment->max_attempts ?? $assignment->repeat_days;
                $quizProgress = $attemptLimit > 0 ? min(100, round($attemptsUsed / $attemptLimit * 100)) : 0;
                $canAttempt = $assignment->today_attempt_available;
                $latestScore = $assignment->latest_score;
              @endphp
              <div class="ts-quiz">
                <div class="ts-quiz-head">
                  <strong>{{ $assignment->quiz?->title ?? 'Assigned quiz' }}</strong>
                  <span class="ts-quiz-badge {{ $assignment->status === 'active' ? 'is-active' : '' }}">{{ $assignment->status === 'active' ? 'Active' : 'Done' }}</span>
                </div>
                <div class="ts-quiz-meta">
                  {{ $assignment->quiz?->subject?->name ?? 'General' }} · {{ $attemptsUsed }}/{{ $attemptLimit }} attempts
                  @if($latestScore !== null) · Last score {{ $latestScore }}% @endif
                </div>
                <div class="ts-quiz-track" role="progressbar" aria-label="Quiz attempts used" aria-valuemin="0" aria-valuemax="{{ max(1, (int) $attemptLimit) }}" aria-valuenow="{{ $attemptsUsed }}" style="--quiz-progress:{{ $quizProgress }}%"><span></span></div>
                @if($canAttempt)
                  <a href="{{ route('quiz.start', $assignment) }}" class="ts-action">Start quiz
                    <svg aria-hidden="true" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14m-6-6 6 6-6 6"/></svg>
                  </a>
                @elseif($assignment->status === 'completed')
                  <span class="ts-quiz-wait">All attempts completed</span>
                @else
                  <span class="ts-quiz-wait">Next attempt is not available yet</span>
                @endif
              </div>
            @endforeach
          </section>
        @endif

        @if($recommendedQuizzes->isNotEmpty() || $weakSubjects->isNotEmpty())
          <section class="ts-side-card" aria-labelledby="ts-focus-heading">
            <h2 id="ts-focus-heading">🎯 Focus areas</h2>
            <p>Topics that deserve some extra practice.</p>
            @foreach($weakSubjects as $subject)
              <div class="ts-focus"><strong>{{ $subject['name'] }}</strong><span>{{ $subject['pct'] }}% average · Ask your teacher for practice.</span></div>
            @endforeach
            @foreach($recommendedQuizzes as $quiz)
              <div class="ts-focus"><strong>{{ $quiz->title }}</strong><span>{{ $quiz->subject?->name ?? 'General' }} · Ask your teacher to assign this quiz.</span></div>
            @endforeach
          </section>
        @endif

        <section class="ts-side-card" aria-labelledby="ts-profile-heading">
          <h2 id="ts-profile-heading">✦ Learning profile</h2>
          <p>Your activity at a glance.</p>
          <div class="ts-profile-row"><span>Activity level</span><strong>{{ $xpEarned >= 200 ? 'Expert' : ($xpEarned >= 100 ? 'Intermediate' : 'Novice') }}</strong></div>
          <div class="ts-profile-row"><span>Class rank</span><strong>{{ is_numeric($rank) ? '#'.$rank : '—' }}</strong></div>
          <div class="ts-profile-row"><span>Study streak</span><strong>{{ $streak }} {{ \Illuminate\Support\Str::plural('day', $streak) }}</strong></div>
        </section>

        @if($timeline->isNotEmpty())
          <section class="ts-side-card" aria-labelledby="ts-activity-heading">
            <h2 id="ts-activity-heading">◷ Recent activity</h2>
            <p>Your latest learning updates.</p>
            @foreach($timeline as $activity)
              <div class="ts-activity">
                <span class="ts-activity-dot" aria-hidden="true">{{ $activity['icon'] }}</span>
                <div><strong>{{ $activity['title'] }}</strong><small>{{ $activity['date']?->diffForHumans() ?? 'Date unavailable' }}</small></div>
              </div>
            @endforeach
          </section>
        @endif
      </aside>
    </div>
  </main>

  @push('scripts')
    <script>
      (() => {
        const filters = [...document.querySelectorAll('.ts-filter')];
        const tasks = [...document.querySelectorAll('.ts-task')];
        const empty = document.getElementById('task-filter-empty');
        if (!filters.length || !empty) return;

        filters.forEach(button => button.addEventListener('click', () => {
          const selected = button.dataset.filter;
          filters.forEach(filter => filter.setAttribute('aria-pressed', String(filter === button)));

          let shown = 0;
          tasks.forEach(task => {
            const match = selected === 'all'
              || (selected === 'pending' && ['pending', 'in_progress'].includes(task.dataset.status))
              || (selected === 'completed' && task.dataset.status === 'completed')
              || (selected === 'overdue' && task.dataset.overdue === '1');
            task.hidden = !match;
            if (match) shown++;
          });
          empty.hidden = shown > 0 || tasks.length === 0;
        }));
      })();
    </script>
  @endpush
</x-app-layout>
