<x-app-layout>
  <x-slot name="title">Teacher Dashboard</x-slot>

  @push('styles')
  <style>
    /* ─── Reset & Base ──────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; }

    .td-wrap {
      font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
      color: #1e293b;
    }

    /* ─── Welcome Banner ────────────────────────────────────── */
    .td-banner {
      position: relative;
      background: linear-gradient(120deg, #4f46e5 0%, #6d28d9 55%, #7c3aed 100%);
      border-radius: 20px;
      padding: 36px 40px;
      margin-bottom: 24px;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 24px;
      flex-wrap: wrap;
    }
    /* Subtle geometric accent shapes */
    .td-banner::before {
      content: '';
      position: absolute;
      width: 320px; height: 320px;
      border-radius: 50%;
      background: rgba(255,255,255,0.06);
      top: -100px; right: -60px;
      pointer-events: none;
    }
    .td-banner::after {
      content: '';
      position: absolute;
      width: 180px; height: 180px;
      border-radius: 50%;
      background: rgba(255,255,255,0.05);
      bottom: -60px; right: 200px;
      pointer-events: none;
    }
    .td-banner-left { position: relative; z-index: 1; }
    .td-banner-eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(255,255,255,0.14);
      border: 1px solid rgba(255,255,255,0.22);
      border-radius: 100px;
      padding: 4px 12px;
      font-size: 11px;
      font-weight: 600;
      color: rgba(255,255,255,0.9);
      letter-spacing: 0.06em;
      text-transform: uppercase;
      margin-bottom: 14px;
    }
    .td-banner-eyebrow span { width: 6px; height: 6px; border-radius: 50%; background: #86efac; display: inline-block; }
    .td-banner-title {
      font-size: 28px;
      font-weight: 700;
      color: #ffffff;
      line-height: 1.2;
      margin: 0 0 8px;
      letter-spacing: -0.02em;
    }
    .td-banner-sub {
      font-size: 14px;
      color: rgba(255,255,255,0.72);
      margin: 0;
    }
    .td-banner-actions {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      position: relative;
      z-index: 1;
      flex-shrink: 0;
    }
    .td-btn-ghost {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 10px 18px;
      border-radius: 12px;
      font-size: 13px;
      font-weight: 600;
      color: rgba(255,255,255,0.9);
      background: rgba(255,255,255,0.12);
      border: 1px solid rgba(255,255,255,0.22);
      text-decoration: none;
      transition: all 0.18s;
      cursor: pointer;
    }
    .td-btn-ghost:hover {
      background: rgba(255,255,255,0.2);
      color: #ffffff;
      transform: translateY(-1px);
    }
    .td-btn-white {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 10px 18px;
      border-radius: 12px;
      font-size: 13px;
      font-weight: 600;
      color: #4f46e5;
      background: #ffffff;
      border: 1px solid #ffffff;
      text-decoration: none;
      transition: all 0.18s;
      box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    }
    .td-btn-white:hover {
      background: #f5f3ff;
      color: #4338ca;
      transform: translateY(-1px);
      box-shadow: 0 4px 14px rgba(0,0,0,0.16);
    }
    .td-btn-white svg, .td-btn-ghost svg { flex-shrink: 0; }

    /* ─── KPI Grid ──────────────────────────────────────────── */
    .td-kpi-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 14px;
      margin-bottom: 24px;
    }
    .td-kpi {
      background: #ffffff;
      border: 1px solid #f1f5f9;
      border-radius: 16px;
      padding: 22px 24px;
      display: flex;
      align-items: center;
      gap: 18px;
      transition: box-shadow 0.2s, transform 0.2s;
      text-decoration: none;
      cursor: default;
    }
    a.td-kpi { cursor: pointer; }
    .td-kpi:hover {
      box-shadow: 0 8px 28px -4px rgba(0,0,0,0.08);
      transform: translateY(-2px);
    }
    .td-kpi-icon {
      width: 48px; height: 48px;
      border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .td-kpi-icon svg { width: 22px; height: 22px; }
    .td-kpi-icon.violet  { background: #ede9fe; color: #5b21b6; }
    .td-kpi-icon.emerald { background: #d1fae5; color: #065f46; }
    .td-kpi-icon.amber   { background: #fef3c7; color: #92400e; }
    .td-kpi-icon.sky     { background: #e0f2fe; color: #075985; }

    .td-kpi-body {}
    .td-kpi-value {
      font-size: 26px;
      font-weight: 800;
      color: #0f172a;
      line-height: 1;
      margin-bottom: 4px;
    }
    .td-kpi-label {
      font-size: 12px;
      font-weight: 600;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    .td-kpi-note {
      font-size: 11px;
      margin-top: 5px;
      display: inline-flex;
      align-items: center;
      gap: 3px;
      font-weight: 500;
    }
    .td-kpi-note.good    { color: #059669; }
    .td-kpi-note.pending { color: #b45309; }
    .td-kpi-note.done    { color: #4f46e5; }

    /* ─── Two-col grid ──────────────────────────────────────── */
    .td-grid {
      display: grid;
      grid-template-columns: 1.4fr 1fr;
      gap: 14px;
      margin-bottom: 14px;
    }
    @media (max-width: 860px) {
      .td-grid { grid-template-columns: 1fr; }
    }

    /* ─── Cards ─────────────────────────────────────────────── */
    .td-card {
      background: #ffffff;
      border: 1px solid #f1f5f9;
      border-radius: 16px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }
    .td-card-head {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      padding: 22px 24px 0 24px;
      margin-bottom: 16px;
    }
    .td-card-title {
      font-size: 14px;
      font-weight: 700;
      color: #0f172a;
      margin: 0 0 3px;
    }
    .td-card-sub {
      font-size: 12px;
      color: #94a3b8;
      font-weight: 500;
    }
    .td-card-action {
      font-size: 12px;
      font-weight: 600;
      color: #4f46e5;
      text-decoration: none;
      white-space: nowrap;
      padding: 5px 10px;
      border-radius: 8px;
      background: #ede9fe;
      transition: background 0.15s;
      flex-shrink: 0;
    }
    .td-card-action:hover { background: #ddd6fe; }

    /* ─── Divider ────────────────────────────────────────────── */
    .td-divider { height: 1px; background: #f8fafc; margin: 0; }

    /* ─── Student Table ──────────────────────────────────────── */
    .td-table { width: 100%; border-collapse: collapse; }
    .td-table th {
      text-align: left;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: #94a3b8;
      padding: 10px 24px;
      background: #f8fafc;
      border-top: 1px solid #f1f5f9;
      border-bottom: 1px solid #f1f5f9;
      white-space: nowrap;
    }
    .td-table td {
      padding: 13px 24px;
      border-bottom: 1px solid #f8fafc;
      vertical-align: middle;
      font-size: 13px;
      color: #334155;
    }
    .td-table tbody tr:last-child td { border-bottom: none; }
    .td-table tbody tr:hover td { background: #fafafa; }

    /* Student cell */
    .td-stu-cell { display: flex; align-items: center; gap: 12px; }
    .td-avatar {
      width: 36px; height: 36px;
      border-radius: 10px;
      background: linear-gradient(135deg, #4f46e5, #7c3aed);
      color: #fff;
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; font-size: 13px;
      flex-shrink: 0;
    }
    .td-stu-name { font-weight: 600; color: #0f172a; font-size: 13px; }
    .td-stu-meta { font-size: 11px; color: #94a3b8; margin-top: 1px; }

    /* Badges */
    .td-badge {
      display: inline-flex;
      align-items: center;
      padding: 3px 9px;
      border-radius: 100px;
      font-size: 11px;
      font-weight: 600;
      white-space: nowrap;
    }
    .td-badge-violet { background: #ede9fe; color: #5b21b6; }
    .td-badge-green  { background: #d1fae5; color: #065f46; }
    .td-badge-amber  { background: #fef3c7; color: #92400e; }
    .td-badge-red    { background: #fee2e2; color: #991b1b; }
    .td-badge-slate  { background: #f1f5f9; color: #475569; }
    .td-badge-sky    { background: #e0f2fe; color: #075985; }

    /* ─── Assignment List ────────────────────────────────────── */
    .td-assign-list {
      display: flex;
      flex-direction: column;
      gap: 0;
      flex: 1;
    }
    .td-assign-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 24px;
      border-bottom: 1px solid #f8fafc;
      transition: background 0.12s;
      gap: 12px;
    }
    .td-assign-row:last-child { border-bottom: none; }
    .td-assign-row:hover { background: #fafafa; }
    .td-assign-left { display: flex; align-items: center; gap: 12px; }
    .td-assign-icon {
      width: 36px; height: 36px;
      border-radius: 10px;
      background: #ede9fe;
      display: flex; align-items: center; justify-content: center;
      color: #5b21b6;
      flex-shrink: 0;
    }
    .td-assign-icon svg { width: 16px; height: 16px; }
    .td-assign-class { font-weight: 700; font-size: 13px; color: #0f172a; }
    .td-assign-sub { font-size: 11px; color: #94a3b8; margin-top: 1px; }

    /* ─── Attendance Progress Card ──────────────────────────── */
    .td-attend-card {
      background: #ffffff;
      border: 1px solid #f1f5f9;
      border-radius: 16px;
      overflow: hidden;
    }
    .td-attend-inner { padding: 22px 24px; }
    .td-attend-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 10px;
    }
    .td-attend-label { font-size: 13px; font-weight: 600; color: #334155; }
    .td-attend-count { font-size: 13px; font-weight: 700; color: #0f172a; }
    .td-progress-track {
      width: 100%;
      height: 8px;
      background: #f1f5f9;
      border-radius: 100px;
      overflow: hidden;
    }
    .td-progress-fill {
      height: 100%;
      border-radius: 100px;
      background: linear-gradient(90deg, #4f46e5, #7c3aed);
      transition: width 0.5s ease;
    }
    .td-attend-note {
      font-size: 11px;
      color: #94a3b8;
      margin-top: 8px;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    /* ─── Empty States ───────────────────────────────────────── */
    .td-empty {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 40px 24px;
      text-align: center;
      flex: 1;
    }
    .td-empty-icon {
      width: 48px; height: 48px;
      border-radius: 14px;
      background: #f8fafc;
      display: flex; align-items: center; justify-content: center;
      color: #cbd5e1;
      margin: 0 auto 14px;
    }
    .td-empty-icon svg { width: 22px; height: 22px; }
    .td-empty-title { font-size: 14px; font-weight: 600; color: #64748b; margin-bottom: 4px; }
    .td-empty-desc  { font-size: 12px; color: #94a3b8; }

    /* ─── Quick-action Buttons ───────────────────────────────── */
    .td-quick-strip {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
      margin-bottom: 20px;
    }
    @media (max-width: 700px) {
      .td-quick-strip { grid-template-columns: 1fr 1fr; }
    }

    .td-quick-link {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 12px;
      padding: 18px 20px;
      border-radius: 16px;
      text-decoration: none;
      border: 1.5px solid transparent;
      transition: transform 0.16s, box-shadow 0.16s, filter 0.16s;
      position: relative;
      overflow: hidden;
    }
    .td-quick-link::after {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(255,255,255,0);
      transition: background 0.16s;
    }
    .td-quick-link:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 28px -4px rgba(0,0,0,0.14);
      filter: brightness(1.03);
    }

    /* Amber — Mark Attendance */
    .td-ql-amber {
      background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
      box-shadow: 0 4px 16px -2px rgba(245,158,11,0.35);
    }
    /* Violet — Enter Marks */
    .td-ql-violet {
      background: linear-gradient(135deg, #6d28d9 0%, #4f46e5 100%);
      box-shadow: 0 4px 16px -2px rgba(109,40,217,0.35);
    }
    /* Emerald — Remedial */
    .td-ql-emerald {
      background: linear-gradient(135deg, #059669 0%, #10b981 100%);
      box-shadow: 0 4px 16px -2px rgba(5,150,105,0.30);
    }
    /* Sky — Reports */
    .td-ql-sky {
      background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);
      box-shadow: 0 4px 16px -2px rgba(2,132,199,0.30);
    }

    .td-quick-icon {
      width: 40px; height: 40px;
      border-radius: 12px;
      background: rgba(255,255,255,0.22);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .td-quick-icon svg { width: 20px; height: 20px; stroke: #fff; }

    .td-quick-body { flex: 1; }
    .td-quick-label {
      font-size: 13px;
      font-weight: 700;
      color: #fff;
      line-height: 1.2;
      margin-bottom: 2px;
    }
    .td-quick-hint {
      font-size: 11px;
      color: rgba(255,255,255,0.72);
      font-weight: 500;
    }
    .td-quick-arrow {
      align-self: flex-end;
      width: 28px; height: 28px;
      border-radius: 8px;
      background: rgba(255,255,255,0.18);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .td-quick-arrow svg { width: 14px; height: 14px; stroke: #fff; }
  </style>
  @endpush

  <div class="td-wrap">

    {{-- ── Welcome Banner ── --}}
    <div class="td-banner">
      <div class="td-banner-left">
        <div class="td-banner-eyebrow">
          <span></span>
          Active · {{ now()->format('l, d M Y') }}
        </div>
        <h2 class="td-banner-title">Welcome back, {{ auth()->user()->name }} 👋</h2>
        <p class="td-banner-sub">Here's your class overview and performance snapshot for today.</p>
      </div>
      <div class="td-banner-actions">
        <a href="{{ route('remedial.index') }}" class="td-btn-ghost">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M12 8v8"/><path d="M8 12h8"/></svg>
          Remedial Actions
        </a>
        <a href="{{ route('marks.index') }}" class="td-btn-white">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
          Manage Marks
        </a>
      </div>
    </div>

    {{-- ── KPI Row ── --}}
    <div class="td-kpi-grid">

      {{-- My Students --}}
      <div class="td-kpi">
        <div class="td-kpi-icon violet">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="td-kpi-body">
          <div class="td-kpi-value">{{ $assignedStudentsCount }}</div>
          <div class="td-kpi-label">My Students</div>
          <div class="td-kpi-note good">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Across all assigned classes
          </div>
        </div>
      </div>

      {{-- Classes --}}
      <div class="td-kpi">
        <div class="td-kpi-icon emerald">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
        </div>
        <div class="td-kpi-body">
          <div class="td-kpi-value">{{ $assignedClassesCount }}</div>
          <div class="td-kpi-label">Classes Assigned</div>
          <div class="td-kpi-note good">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            This academic session
          </div>
        </div>
      </div>

      {{-- Attendance --}}
      <a href="{{ route('attendance.index') }}" class="td-kpi">
        <div class="td-kpi-icon amber">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        </div>
        <div class="td-kpi-body">
          <div class="td-kpi-value">
            @if($todayClassesCount > 0)
              {{ $todayMarkedCount }}/{{ $todayClassesCount }}
            @else
              &mdash;
            @endif
          </div>
          <div class="td-kpi-label">Today's Attendance</div>
          @if($todayClassesCount > 0 && $todayMarkedCount === $todayClassesCount)
            <div class="td-kpi-note done">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              All classes marked ✓
            </div>
          @elseif($todayClassesCount > 0)
            <div class="td-kpi-note pending">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              {{ $todayClassesCount - $todayMarkedCount }} class{{ ($todayClassesCount - $todayMarkedCount) > 1 ? 'es' : '' }} pending
            </div>
          @else
            <div class="td-kpi-note pending">Tap to mark attendance</div>
          @endif
        </div>
      </a>

    </div>

    {{-- ── Quick Action Buttons ── --}}
    <div class="td-quick-strip">

      {{-- Mark Attendance --}}
      <a href="{{ route('attendance.index') }}" class="td-quick-link td-ql-amber">
        <div style="display:flex; align-items:center; justify-content:space-between; width:100%;">
          <div class="td-quick-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" stroke="currentColor"/></svg>
          </div>
          <div class="td-quick-arrow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
          </div>
        </div>
        <div class="td-quick-body">
          <div class="td-quick-label">Mark Attendance</div>
          <div class="td-quick-hint">Record today's classes</div>
        </div>
      </a>

      {{-- Enter Marks --}}
      <a href="{{ route('marks.index') }}" class="td-quick-link td-ql-violet">
        <div style="display:flex; align-items:center; justify-content:space-between; width:100%;">
          <div class="td-quick-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
          </div>
          <div class="td-quick-arrow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
          </div>
        </div>
        <div class="td-quick-body">
          <div class="td-quick-label">Enter Marks</div>
          <div class="td-quick-hint">Add or update scores</div>
        </div>
      </a>

      {{-- Remedial Actions --}}
      <a href="{{ route('remedial.index') }}" class="td-quick-link td-ql-emerald">
        <div style="display:flex; align-items:center; justify-content:space-between; width:100%;">
          <div class="td-quick-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M12 8v8"/><path d="M8 12h8"/></svg>
          </div>
          <div class="td-quick-arrow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
          </div>
        </div>
        <div class="td-quick-body">
          <div class="td-quick-label">Remedial Actions</div>
          <div class="td-quick-hint">Assign student support</div>
        </div>
      </a>

      {{-- View Reports --}}
      <a href="{{ route('reports.index') ?? '#' }}" class="td-quick-link td-ql-sky">
        <div style="display:flex; align-items:center; justify-content:space-between; width:100%;">
          <div class="td-quick-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
          </div>
          <div class="td-quick-arrow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
          </div>
        </div>
        <div class="td-quick-body">
          <div class="td-quick-label">View Reports</div>
          <div class="td-quick-hint">Analytics &amp; insights</div>
        </div>
      </a>

    </div>

    {{-- ── Main Two-Col Grid ── --}}
    <div class="td-grid">

      {{-- Recent Students --}}
      <div class="td-card">
        <div class="td-card-head">
          <div>
            <div class="td-card-title">Recent Students</div>
            <div class="td-card-sub">From your assigned classes</div>
          </div>
          <a href="#" class="td-card-action">View all</a>
        </div>
        <div class="td-divider"></div>
        <div style="overflow-x: auto; flex: 1;">
          <table class="td-table">
            <thead>
              <tr>
                <th>Student</th>
                <th>Class</th>
                <th>Performance</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentStudents as $student)
              <tr>
                <td>
                  <div class="td-stu-cell">
                    <div class="td-avatar">{{ strtoupper(substr($student->user->name ?? 'U', 0, 2)) }}</div>
                    <div>
                      <div class="td-stu-name">{{ $student->user->name ?? '—' }}</div>
                      <div class="td-stu-meta">Roll {{ $student->roll_no ?? '—' }}</div>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="td-badge td-badge-slate">{{ $student->classroom->academicClass->name ?? 'N/A' }} {{ $student->classroom->name ?? '' }}</span>
                </td>
                <td>
                  @php
                    $perf  = $student->performance_label ?? 'N/A';
                    $color = $student->performance_color ?? '#64748b';
                    $bgHex = $color . '18';
                  @endphp
                  <span style="
                    display:inline-flex; align-items:center;
                    padding: 3px 10px;
                    border-radius: 100px;
                    font-size: 11px;
                    font-weight: 600;
                    background: {{ $bgHex }};
                    color: {{ $color }};
                  ">{{ $perf }}</span>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="3" style="padding:0; border:none;">
                  <div class="td-empty">
                    <div class="td-empty-icon">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </div>
                    <div class="td-empty-title">No students yet</div>
                    <div class="td-empty-desc">Students will appear once assigned to your classes.</div>
                  </div>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      {{-- My Assignments + Attendance --}}
      <div style="display: flex; flex-direction: column; gap: 14px;">

        {{-- Assignments Card --}}
        <div class="td-card" style="flex: 1;">
          <div class="td-card-head">
            <div>
              <div class="td-card-title">My Assignments</div>
              <div class="td-card-sub">Classes you are responsible for</div>
            </div>
            <span class="td-badge td-badge-violet" style="flex-shrink:0;">{{ count($assignments) }} classes</span>
          </div>
          <div class="td-divider"></div>
          <div class="td-assign-list">
            @forelse($assignments as $assignment)
            <div class="td-assign-row">
              <div class="td-assign-left">
                <div class="td-assign-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div>
                  <div class="td-assign-class">Class {{ $assignment->academicClass->name ?? 'Unknown' }}</div>
                  <div class="td-assign-sub">Section {{ $assignment->name }}</div>
                </div>
              </div>
              <span class="td-badge td-badge-violet">Sec {{ $assignment->name }}</span>
            </div>
            @empty
            <div class="td-empty">
              <div class="td-empty-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              </div>
              <div class="td-empty-title">No classes assigned</div>
              <div class="td-empty-desc">Contact your admin to get classes assigned.</div>
            </div>
            @endforelse
          </div>
        </div>

        {{-- Today's Attendance Progress --}}
        @if($todayClassesCount > 0)
        <div class="td-attend-card">
          <div class="td-attend-inner">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px;">
              <div>
                <div style="font-size:13px; font-weight:700; color:#0f172a; margin-bottom:2px;">Today's Attendance</div>
                <div style="font-size:11px; color:#94a3b8; font-weight:500;">{{ now()->format('l, d M') }}</div>
              </div>
              <a href="{{ route('attendance.index') }}" style="font-size:12px; font-weight:600; color:#4f46e5; background:#ede9fe; padding:5px 12px; border-radius:8px; text-decoration:none;">
                @if($todayMarkedCount === $todayClassesCount) Review @else Mark Now @endif
              </a>
            </div>
            @php
              $pct = $todayClassesCount > 0 ? round($todayMarkedCount / $todayClassesCount * 100) : 0;
            @endphp
            <div class="td-attend-row">
              <div class="td-attend-label">
                @if($todayMarkedCount === $todayClassesCount)
                  All classes marked ✓
                @else
                  {{ $todayClassesCount - $todayMarkedCount }} class{{ ($todayClassesCount - $todayMarkedCount) > 1 ? 'es' : '' }} remaining
                @endif
              </div>
              <div class="td-attend-count" style="color: {{ $todayMarkedCount === $todayClassesCount ? '#059669' : '#b45309' }};">
                {{ $todayMarkedCount }}/{{ $todayClassesCount }}
              </div>
            </div>
            <div class="td-progress-track">
              <div class="td-progress-fill" style="width: {{ $pct }}%;
                @if($todayMarkedCount === $todayClassesCount)
                  background: linear-gradient(90deg, #10b981, #059669);
                @endif
              "></div>
            </div>
            <div class="td-attend-note">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              {{ $pct }}% complete for today
            </div>
          </div>
        </div>
        @endif

      </div>
    </div>

  </div>{{-- /td-wrap --}}

  @push('scripts')
  {{-- No JS needed — purely CSS-driven --}}
  @endpush
</x-app-layout>