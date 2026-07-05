<x-app-layout>
  <x-slot name="title">Remedial Actions</x-slot>

  @push('styles')
  <style>
    /* ─── Base ──────────────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; }
    .ra-wrap { font-family: 'Inter', 'Segoe UI', system-ui, sans-serif; color: #1e293b; }

    /* ─── Page Header ───────────────────────────────────────── */
    .ra-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 16px;
      flex-wrap: wrap;
      margin-bottom: 24px;
    }
    .ra-eyebrow {
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: #94a3b8;
      margin-bottom: 4px;
    }
    .ra-title {
      font-size: 22px;
      font-weight: 700;
      color: #0f172a;
      letter-spacing: -0.02em;
      margin: 0 0 3px;
      line-height: 1.2;
    }
    .ra-subtitle { font-size: 13px; color: #64748b; margin: 0; }

    /* ─── Buttons ───────────────────────────────────────────── */
    .ra-btn-primary {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 10px 18px;
      background: #4f46e5;
      color: #fff;
      border-radius: 12px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      border: none;
      cursor: pointer;
      transition: all 0.16s;
      box-shadow: 0 4px 14px -2px rgba(79,70,229,0.4);
      flex-shrink: 0;
    }
    .ra-btn-primary:hover {
      background: #4338ca;
      transform: translateY(-1px);
      box-shadow: 0 6px 18px -2px rgba(79,70,229,0.45);
      color: #fff;
    }
    .ra-btn-ghost {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 9px 16px;
      background: #fff;
      color: #475569;
      border-radius: 11px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      border: 1px solid #e2e8f0;
      cursor: pointer;
      transition: all 0.16s;
    }
    .ra-btn-ghost:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; }
    .ra-btn-dark {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 9px 16px;
      background: #0f172a;
      color: #fff;
      border-radius: 11px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      border: none;
      cursor: pointer;
      transition: all 0.16s;
    }
    .ra-btn-dark:hover { background: #1e293b; color: #fff; }

    /* ─── Status Pipeline ───────────────────────────────────── */
    /* Signature element: a horizontal pipeline showing counts
       per status so teachers see the big picture instantly     */
    .ra-pipeline {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
      margin-bottom: 20px;
    }
    @media (max-width: 640px) { .ra-pipeline { grid-template-columns: 1fr 1fr; } }

    .ra-pipe-item {
      background: #fff;
      border: 1px solid #f1f5f9;
      border-radius: 14px;
      padding: 16px 18px;
      cursor: pointer;
      text-decoration: none;
      display: block;
      transition: box-shadow 0.16s, transform 0.16s, border-color 0.16s;
      position: relative;
      overflow: hidden;
    }
    .ra-pipe-item::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 3px;
      border-radius: 14px 14px 0 0;
    }
    .ra-pipe-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px -4px rgba(0,0,0,0.09);
    }
    .ra-pipe-item.active { border-color: currentColor; }

    .pipe-pending::before   { background: #f59e0b; }
    .pipe-inprog::before    { background: #4f46e5; }
    .pipe-done::before      { background: #10b981; }
    .pipe-cancelled::before { background: #94a3b8; }

    .ra-pipe-top {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      margin-bottom: 10px;
    }
    .ra-pipe-count {
      font-size: 28px;
      font-weight: 800;
      color: #0f172a;
      line-height: 1;
    }
    .ra-pipe-dot {
      width: 32px; height: 32px;
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .ra-pipe-dot svg { width: 16px; height: 16px; }
    .dot-pending   { background: #fef3c7; color: #b45309; }
    .dot-inprog    { background: #ede9fe; color: #5b21b6; }
    .dot-done      { background: #d1fae5; color: #065f46; }
    .dot-cancelled { background: #f1f5f9; color: #64748b; }

    .ra-pipe-label {
      font-size: 12px;
      font-weight: 600;
      color: #64748b;
    }
    .ra-pipe-sub {
      font-size: 11px;
      color: #94a3b8;
      margin-top: 2px;
    }

    /* ─── Flash Message ─────────────────────────────────────── */
    .ra-flash {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 13px 18px;
      background: #f0fdf4;
      border: 1px solid #bbf7d0;
      border-radius: 12px;
      font-size: 13px;
      font-weight: 600;
      color: #15803d;
      margin-bottom: 20px;
    }
    .ra-flash svg { flex-shrink: 0; color: #22c55e; }

    /* ─── Card ──────────────────────────────────────────────── */
    .ra-card {
      background: #fff;
      border: 1px solid #f1f5f9;
      border-radius: 16px;
      overflow: hidden;
      margin-bottom: 16px;
    }

    /* ─── Filter Bar ────────────────────────────────────────── */
    .ra-filter-bar {
      display: flex;
      align-items: flex-end;
      gap: 12px;
      padding: 18px 20px;
      border-bottom: 1px solid #f1f5f9;
      background: #fafafa;
      flex-wrap: wrap;
    }
    .ra-filter-group { display: flex; flex-direction: column; gap: 5px; }
    .ra-filter-label {
      font-size: 11px;
      font-weight: 600;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    .ra-filter-input {
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 8px 14px;
      font-size: 13px;
      color: #0f172a;
      transition: border-color 0.15s, box-shadow 0.15s;
      min-width: 160px;
      font-family: inherit;
    }
    .ra-filter-input:focus {
      border-color: #6d28d9;
      box-shadow: 0 0 0 3px rgba(109,40,217,0.1);
      outline: none;
    }
    .ra-filter-actions { display: flex; gap: 8px; align-items: flex-end; margin-left: auto; }

    /* ─── Table ─────────────────────────────────────────────── */
    .ra-table { width: 100%; border-collapse: collapse; }
    .ra-table th {
      text-align: left;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: #94a3b8;
      padding: 11px 20px;
      background: #f8fafc;
      border-top: 1px solid #f1f5f9;
      border-bottom: 1px solid #f1f5f9;
      white-space: nowrap;
    }
    .ra-table td {
      padding: 14px 20px;
      border-bottom: 1px solid #f8fafc;
      vertical-align: middle;
      font-size: 13px;
      color: #334155;
    }
    .ra-table tbody tr:last-child td { border-bottom: none; }
    .ra-table tbody tr { transition: background 0.12s; }
    .ra-table tbody tr:hover td { background: #fafafa; }

    /* ─── Avatar ────────────────────────────────────────────── */
    .ra-avatar {
      width: 36px; height: 36px;
      border-radius: 10px;
      background: linear-gradient(135deg, #4f46e5, #7c3aed);
      color: #fff;
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; font-size: 12px;
      flex-shrink: 0;
      letter-spacing: 0.02em;
    }

    /* ─── Badges ─────────────────────────────────────────────── */
    .ra-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 4px 10px;
      border-radius: 100px;
      font-size: 11px;
      font-weight: 600;
      white-space: nowrap;
    }
    .ra-badge-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }

    .ra-badge-pending   { background: #fef3c7; color: #92400e; }
    .ra-badge-inprog    { background: #ede9fe; color: #5b21b6; }
    .ra-badge-done      { background: #d1fae5; color: #065f46; }
    .ra-badge-cancelled { background: #f1f5f9; color: #475569; }
    .ra-badge-type      { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

    /* ─── Action Buttons (row) ──────────────────────────────── */
    .ra-row-actions { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }
    .ra-act-btn {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 5px 11px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
      text-decoration: none;
      border: 1px solid transparent;
      cursor: pointer;
      transition: all 0.15s;
      white-space: nowrap;
    }
    .ra-act-edit {
      background: #f1f5f9;
      color: #475569;
      border-color: #e2e8f0;
    }
    .ra-act-edit:hover { background: #e2e8f0; color: #0f172a; }

    .ra-act-sub {
      background: #ede9fe;
      color: #5b21b6;
      border-color: #ddd6fe;
    }
    .ra-act-sub:hover { background: #ddd6fe; color: #4c1d95; }

    .ra-act-del {
      background: #fff;
      color: #dc2626;
      border-color: #fecaca;
    }
    .ra-act-del:hover { background: #fef2f2; border-color: #fca5a5; }

    .ra-sub-count {
      background: #5b21b6;
      color: #fff;
      border-radius: 100px;
      padding: 1px 6px;
      font-size: 10px;
      font-weight: 700;
    }

    /* ─── Date Cell ─────────────────────────────────────────── */
    .ra-date-cell {
      display: flex;
      align-items: center;
      gap: 6px;
      font-weight: 500;
    }
    .ra-date-cell svg { flex-shrink: 0; color: #94a3b8; }
    .ra-date-overdue { color: #dc2626 !important; }
    .ra-date-soon    { color: #b45309 !important; }

    /* ─── Empty State ────────────────────────────────────────── */
    .ra-empty {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 56px 32px;
      text-align: center;
    }
    .ra-empty-icon {
      width: 60px; height: 60px;
      border-radius: 18px;
      background: #f8fafc;
      display: flex; align-items: center; justify-content: center;
      color: #cbd5e1;
      margin: 0 auto 16px;
    }
    .ra-empty-icon svg { width: 28px; height: 28px; }
    .ra-empty-title { font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 6px; }
    .ra-empty-desc  { font-size: 13px; color: #64748b; margin-bottom: 20px; }

    /* ─── Pagination ─────────────────────────────────────────── */
    .ra-pagination {
      padding: 14px 20px;
      border-top: 1px solid #f1f5f9;
      background: #fafafa;
    }

    /* ─── Title cell ─────────────────────────────────────────── */
    .ra-title-cell { font-weight: 700; color: #0f172a; font-size: 13px; }
    .ra-title-desc { font-size: 11px; color: #94a3b8; font-weight: 500; margin-top: 2px; }
  </style>
  @endpush

  <div class="ra-wrap">

    {{-- ── Page Header ── --}}
    <div class="ra-header">
      <div>
        <p class="ra-eyebrow">Intervention Tracking</p>
        <h2 class="ra-title">Remedial Actions</h2>
        <p class="ra-subtitle">Manage and track improvement interventions for struggling students</p>
      </div>
      <a href="{{ route('remedial.create') }}" class="ra-btn-primary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Action
      </a>
    </div>

    {{-- ── Status Pipeline (Signature element) ── --}}
    @php
      $countPending   = $actions->getCollection()->where('status','pending')->count();
      $countInprog    = $actions->getCollection()->where('status','in_progress')->count();
      $countDone      = $actions->getCollection()->where('status','completed')->count();
      $countCancelled = $actions->getCollection()->where('status','cancelled')->count();
    @endphp
    <div class="ra-pipeline">

      <a href="{{ route('remedial.index', ['status'=>'pending']) }}" class="ra-pipe-item pipe-pending {{ request('status')==='pending' ? 'active' : '' }}">
        <div class="ra-pipe-top">
          <div class="ra-pipe-count">{{ $countPending }}</div>
          <div class="ra-pipe-dot dot-pending">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          </div>
        </div>
        <div class="ra-pipe-label">Pending</div>
        <div class="ra-pipe-sub">Awaiting start</div>
      </a>

      <a href="{{ route('remedial.index', ['status'=>'in_progress']) }}" class="ra-pipe-item pipe-inprog {{ request('status')==='in_progress' ? 'active' : '' }}">
        <div class="ra-pipe-top">
          <div class="ra-pipe-count">{{ $countInprog }}</div>
          <div class="ra-pipe-dot dot-inprog">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
          </div>
        </div>
        <div class="ra-pipe-label">In Progress</div>
        <div class="ra-pipe-sub">Actively running</div>
      </a>

      <a href="{{ route('remedial.index', ['status'=>'completed']) }}" class="ra-pipe-item pipe-done {{ request('status')==='completed' ? 'active' : '' }}">
        <div class="ra-pipe-top">
          <div class="ra-pipe-count">{{ $countDone }}</div>
          <div class="ra-pipe-dot dot-done">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
        </div>
        <div class="ra-pipe-label">Completed</div>
        <div class="ra-pipe-sub">Intervention done</div>
      </a>

      <a href="{{ route('remedial.index', ['status'=>'cancelled']) }}" class="ra-pipe-item pipe-cancelled {{ request('status')==='cancelled' ? 'active' : '' }}">
        <div class="ra-pipe-top">
          <div class="ra-pipe-count">{{ $countCancelled }}</div>
          <div class="ra-pipe-dot dot-cancelled">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </div>
        </div>
        <div class="ra-pipe-label">Cancelled</div>
        <div class="ra-pipe-sub">No longer active</div>
      </a>

    </div>

    {{-- ── Flash Message ── --}}
    @if(session('success'))
    <div class="ra-flash">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      {{ session('success') }}
    </div>
    @endif

    {{-- ── Main Table Card ── --}}
    <div class="ra-card">

      {{-- Filter Bar --}}
      <form method="GET" class="ra-filter-bar">
        <div class="ra-filter-group">
          <label class="ra-filter-label" for="filter-status">Status</label>
          <select name="status" id="filter-status" class="ra-filter-input">
            <option value="">All statuses</option>
            @foreach(['pending'=>'Pending','in_progress'=>'In Progress','completed'=>'Completed','cancelled'=>'Cancelled'] as $val => $lbl)
              <option value="{{ $val }}" {{ request('status')==$val ? 'selected' : '' }}>{{ $lbl }}</option>
            @endforeach
          </select>
        </div>

        <div class="ra-filter-group" style="flex:1; max-width:340px;">
          <label class="ra-filter-label" for="filter-student">Student</label>
          <select name="student_id" id="filter-student" class="ra-filter-input" style="width:100%;">
            <option value="">All students</option>
            @foreach($students as $s)
              <option value="{{ $s->id }}" {{ request('student_id')==$s->id ? 'selected' : '' }}>
                {{ $s->name }} — Roll {{ $s->roll_no }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="ra-filter-actions">
          <button type="submit" class="ra-btn-dark">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            Filter
          </button>
          <a href="{{ route('remedial.index') }}" class="ra-btn-ghost">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
            Reset
          </a>
        </div>
      </form>

      {{-- Table --}}
      <div style="overflow-x: auto;">
        <table class="ra-table">
          <thead>
            <tr>
              <th style="padding-left:20px;">Action</th>
              <th>Student</th>
              <th>Type</th>
              <th>Scheduled</th>
              <th>Status</th>
              <th>Options</th>
            </tr>
          </thead>
          <tbody>
            @forelse($actions as $action)
            @php
              $isOverdue = $action->scheduled_date && $action->scheduled_date->isPast() && $action->status !== 'completed' && $action->status !== 'cancelled';
              $isSoon    = $action->scheduled_date && !$isOverdue && $action->scheduled_date->diffInDays(now()) <= 2;

              $statusClass = match($action->status) {
                'pending'     => 'ra-badge-pending',
                'in_progress' => 'ra-badge-inprog',
                'completed'   => 'ra-badge-done',
                default       => 'ra-badge-cancelled',
              };
              $dotColor = match($action->status) {
                'pending'     => '#f59e0b',
                'in_progress' => '#7c3aed',
                'completed'   => '#10b981',
                default       => '#94a3b8',
              };
            @endphp
            <tr>
              {{-- Title --}}
              <td>
                <div class="ra-title-cell">{{ $action->title }}</div>
                @if($action->description ?? false)
                  <div class="ra-title-desc">{{ Str::limit($action->description, 48) }}</div>
                @endif
              </td>

              {{-- Student --}}
              <td>
                <div style="display:flex; align-items:center; gap:10px;">
                  <div class="ra-avatar">{{ strtoupper(substr($action->student?->name ?? 'NA', 0, 2)) }}</div>
                  <div>
                    <div style="font-weight:600; color:#0f172a; font-size:13px;">{{ $action->student?->name ?? 'Unknown' }}</div>
                    <div style="font-size:11px; color:#94a3b8;">Roll {{ $action->student?->roll_no ?? '—' }}</div>
                  </div>
                </div>
              </td>

              {{-- Type --}}
              <td>
                <span class="ra-badge ra-badge-type">{{ $action->action_type_label }}</span>
              </td>

              {{-- Date --}}
              <td>
                @if($action->scheduled_date)
                  <div class="ra-date-cell {{ $isOverdue ? 'ra-date-overdue' : ($isSoon ? 'ra-date-soon' : '') }}">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span>{{ $action->scheduled_date->format('d M Y') }}</span>
                    @if($isOverdue)
                      <span class="ra-badge ra-badge-pending" style="padding:2px 7px; font-size:10px; margin-left:2px;">Overdue</span>
                    @elseif($isSoon)
                      <span class="ra-badge ra-badge-inprog" style="padding:2px 7px; font-size:10px; margin-left:2px;">Soon</span>
                    @endif
                  </div>
                @else
                  <span style="color:#94a3b8; font-size:12px;">Not scheduled</span>
                @endif
              </td>

              {{-- Status --}}
              <td>
                <span class="ra-badge {{ $statusClass }}">
                  <span class="ra-badge-dot" style="background:{{ $dotColor }};"></span>
                  {{ ucfirst(str_replace('_', ' ', $action->status)) }}
                </span>
              </td>

              {{-- Actions --}}
              <td>
                <div class="ra-row-actions">
                  @if($action->is_interactive)
                    <a href="{{ route('remedial.submissions', $action) }}" class="ra-act-btn ra-act-sub">
                      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                      Submissions
                      @if($action->submission)
                        <span class="ra-sub-count">1</span>
                      @endif
                    </a>
                  @endif
                  <a href="{{ route('remedial.edit', $action) }}" class="ra-act-btn ra-act-edit">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit
                  </a>
                  <form method="POST" action="{{ route('remedial.destroy', $action) }}" onsubmit="return confirm('Delete this remedial action? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="ra-act-btn ra-act-del">
                      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                      Delete
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" style="padding:0; border:none;">
                <div class="ra-empty">
                  <div class="ra-empty-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                  </div>
                  <div class="ra-empty-title">No remedial actions found</div>
                  <div class="ra-empty-desc">
                    @if(request('status') || request('student_id'))
                      No actions match your current filters. Try resetting.
                    @else
                      No interventions have been scheduled yet. Create the first one.
                    @endif
                  </div>
                  @if(request('status') || request('student_id'))
                    <a href="{{ route('remedial.index') }}" class="ra-btn-ghost">Reset filters</a>
                  @else
                    <a href="{{ route('remedial.create') }}" class="ra-btn-primary">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                      Assign New Action
                    </a>
                  @endif
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      @if($actions->hasPages())
      <div class="ra-pagination">
        {{ $actions->links() }}
      </div>
      @endif

    </div>

  </div>{{-- /ra-wrap --}}

</x-app-layout>