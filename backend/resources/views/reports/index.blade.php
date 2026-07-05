<x-app-layout>
  <x-slot name="title">Reports & Analytics</x-slot>

  @push('styles')
  <style>
    /* ─── Base ─────────────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; }

    .rp-wrap {
      font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
      color: #1e293b;
    }

    /* ─── Page Header ───────────────────────────────────────── */
    .rp-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 16px;
      flex-wrap: wrap;
      margin-bottom: 28px;
    }
    .rp-eyebrow {
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: #94a3b8;
      margin-bottom: 4px;
    }
    .rp-title {
      font-size: 22px;
      font-weight: 700;
      color: #0f172a;
      line-height: 1.2;
      margin: 0 0 3px;
    }
    .rp-subtitle {
      font-size: 13px;
      color: #64748b;
      margin: 0;
    }
    .rp-actions {
      display: flex;
      gap: 8px;
      align-items: center;
      flex-shrink: 0;
      flex-wrap: wrap;
    }

    /* ─── Buttons ───────────────────────────────────────────── */
    .btn-rp {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 9px 16px;
      border-radius: 10px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      border: 1px solid #e2e8f0;
      background: #ffffff;
      color: #475569;
      transition: all 0.15s ease;
      text-decoration: none;
      white-space: nowrap;
    }
    .btn-rp:hover {
      background: #f8fafc;
      border-color: #cbd5e1;
      color: #0f172a;
      transform: translateY(-1px);
    }
    .btn-rp svg { flex-shrink: 0; }

    .btn-rp-primary {
      background: #4f46e5;
      color: #ffffff;
      border-color: #4f46e5;
    }
    .btn-rp-primary:hover {
      background: #4338ca;
      border-color: #4338ca;
      color: #ffffff;
    }
    .btn-rp-export {
      background: #f0fdf4;
      color: #15803d;
      border-color: #bbf7d0;
    }
    .btn-rp-export:hover {
      background: #dcfce7;
      border-color: #86efac;
      color: #166534;
    }

    /* ─── KPI Grid ──────────────────────────────────────────── */
    .rp-kpi-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
      margin-bottom: 24px;
    }
    .rp-kpi {
      background: #ffffff;
      border: 1px solid #f1f5f9;
      border-radius: 16px;
      padding: 20px;
      display: flex;
      align-items: flex-start;
      gap: 16px;
      transition: box-shadow 0.2s, transform 0.2s;
    }
    .rp-kpi:hover {
      box-shadow: 0 8px 24px -4px rgba(0,0,0,0.08);
      transform: translateY(-2px);
    }
    .rp-kpi-icon {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .rp-kpi-icon svg { width: 22px; height: 22px; }
    .rp-kpi-icon.violet  { background: #ede9fe; color: #6d28d9; }
    .rp-kpi-icon.red     { background: #fee2e2; color: #b91c1c; }
    .rp-kpi-icon.amber   { background: #fef3c7; color: #b45309; }
    .rp-kpi-icon.emerald { background: #d1fae5; color: #065f46; }

    .rp-kpi-body { flex: 1; min-width: 0; }
    .rp-kpi-value {
      font-size: 28px;
      font-weight: 800;
      color: #0f172a;
      line-height: 1;
      margin-bottom: 4px;
    }
    .rp-kpi-label {
      font-size: 12px;
      font-weight: 600;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    .rp-kpi-note {
      font-size: 11px;
      margin-top: 6px;
      display: flex;
      align-items: center;
      gap: 3px;
    }
    .rp-kpi-note.good { color: #059669; }
    .rp-kpi-note.bad  { color: #dc2626; }

    /* ─── Content Grid ──────────────────────────────────────── */
    .rp-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      margin-bottom: 16px;
    }
    @media (max-width: 900px) {
      .rp-grid { grid-template-columns: 1fr; }
    }

    /* ─── Cards ─────────────────────────────────────────────── */
    .rp-card {
      background: #ffffff;
      border: 1px solid #f1f5f9;
      border-radius: 16px;
      overflow: hidden;
    }
    .rp-card-inner { padding: 24px; }
    .rp-card-head {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    .rp-card-title {
      font-size: 14px;
      font-weight: 700;
      color: #0f172a;
      display: flex;
      align-items: center;
      gap: 7px;
    }
    .rp-card-title svg { color: #6d28d9; flex-shrink: 0; }
    .rp-tag {
      font-size: 11px;
      font-weight: 600;
      padding: 3px 10px;
      border-radius: 100px;
    }
    .rp-tag-violet { background: #ede9fe; color: #5b21b6; }
    .rp-tag-slate  { background: #f1f5f9; color: #475569; }

    /* ─── Chart ─────────────────────────────────────────────── */
    .rp-chart-wrap {
      position: relative;
      width: 100%;
    }
    .rp-chart-legend {
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
      margin-top: 14px;
    }
    .rp-legend-item {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 12px;
      color: #64748b;
      font-weight: 500;
    }
    .rp-legend-dot {
      width: 10px;
      height: 10px;
      border-radius: 3px;
      flex-shrink: 0;
    }

    /* ─── Perf Distribution Card (new layout) ────────────────── */
    .rp-perf-card {
      background: #ffffff;
      border: 1px solid #f1f5f9;
      border-radius: 16px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }
    .rp-perf-top {
      display: grid;
      grid-template-columns: 1fr auto;
      gap: 0;
    }
    /* Left: horizontal bar chart */
    .rp-perf-bars {
      padding: 24px 20px 24px 24px;
      flex: 1;
      min-width: 0;
    }
    .rp-hbar-row {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 14px;
    }
    .rp-hbar-row:last-child { margin-bottom: 0; }
    .rp-hbar-label {
      font-size: 12px;
      font-weight: 600;
      color: #64748b;
      width: 52px;
      flex-shrink: 0;
      white-space: nowrap;
    }
    .rp-hbar-track {
      flex: 1;
      height: 28px;
      background: #f8fafc;
      border-radius: 8px;
      overflow: hidden;
      display: flex;
      min-width: 0;
    }
    .rp-hbar-good {
      background: #10b981;
      height: 100%;
      border-radius: 8px 0 0 8px;
      transition: width 0.6s ease;
      display: flex;
      align-items: center;
      justify-content: flex-end;
      padding-right: 6px;
    }
    .rp-hbar-good-txt {
      font-size: 10px;
      font-weight: 700;
      color: #fff;
      white-space: nowrap;
    }
    .rp-hbar-bad {
      background: #fca5a5;
      height: 100%;
      border-radius: 0 8px 8px 0;
      transition: width 0.6s ease;
      display: flex;
      align-items: center;
      padding-left: 5px;
    }
    .rp-hbar-bad-txt {
      font-size: 10px;
      font-weight: 700;
      color: #991b1b;
      white-space: nowrap;
    }
    .rp-hbar-total {
      font-size: 11px;
      font-weight: 600;
      color: #94a3b8;
      width: 28px;
      flex-shrink: 0;
      text-align: right;
    }

    /* Right: donut summary */
    .rp-perf-donut {
      width: 168px;
      flex-shrink: 0;
      border-left: 1px solid #f1f5f9;
      padding: 24px 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 14px;
    }
    .rp-donut-wrap {
      position: relative;
      width: 100px;
      height: 100px;
    }
    .rp-donut-canvas { width: 100px; height: 100px; }
    .rp-donut-center {
      position: absolute;
      inset: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      pointer-events: none;
    }
    .rp-donut-pct {
      font-size: 20px;
      font-weight: 800;
      color: #0f172a;
      line-height: 1;
    }
    .rp-donut-sub {
      font-size: 10px;
      font-weight: 600;
      color: #94a3b8;
      margin-top: 2px;
    }
    .rp-donut-stats {
      width: 100%;
    }
    .rp-donut-stat {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 5px 0;
      border-bottom: 1px solid #f8fafc;
    }
    .rp-donut-stat:last-child { border-bottom: none; }
    .rp-donut-stat-left {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 11px;
      font-weight: 600;
      color: #64748b;
    }
    .rp-donut-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      flex-shrink: 0;
    }
    .rp-donut-stat-val {
      font-size: 12px;
      font-weight: 700;
      color: #0f172a;
    }

    /* Legend row */
    .rp-perf-legend {
      display: flex;
      gap: 20px;
      padding: 12px 24px;
      border-top: 1px solid #f8fafc;
      background: #fafafa;
      flex-wrap: wrap;
    }

    /* ─── Tables ─────────────────────────────────────────────── */
    .rp-table-wrap { overflow-x: auto; }
    .rp-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }
    .rp-table th {
      text-align: left;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: #94a3b8;
      padding: 10px 14px;
      background: #f8fafc;
      border-top: 1px solid #f1f5f9;
      border-bottom: 1px solid #f1f5f9;
      white-space: nowrap;
    }
    .rp-table td {
      padding: 13px 14px;
      color: #334155;
      border-bottom: 1px solid #f8fafc;
      vertical-align: middle;
    }
    .rp-table tbody tr:last-child td { border-bottom: none; }
    .rp-table tbody tr:hover td { background: #fafafa; }
    .rp-table .fw { font-weight: 700; color: #0f172a; }

    /* ─── Badges ─────────────────────────────────────────────── */
    .rp-badge {
      display: inline-flex;
      align-items: center;
      padding: 3px 9px;
      border-radius: 100px;
      font-size: 11px;
      font-weight: 600;
      white-space: nowrap;
    }
    .rp-badge-red    { background: #fee2e2; color: #991b1b; }
    .rp-badge-green  { background: #d1fae5; color: #065f46; }
    .rp-badge-amber  { background: #fef3c7; color: #92400e; }
    .rp-badge-slate  { background: #f1f5f9; color: #475569; }
    .rp-badge-violet { background: #ede9fe; color: #5b21b6; }

    /* ─── Alert Bar ──────────────────────────────────────────── */
    .rp-alert {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      background: #fff7ed;
      border: 1px solid #fed7aa;
      border-radius: 10px;
      padding: 12px 16px;
      margin-bottom: 20px;
    }
    .rp-alert svg { flex-shrink: 0; margin-top: 1px; color: #c2410c; }
    .rp-alert-text { font-size: 13px; color: #7c2d12; line-height: 1.5; }
    .rp-alert-text strong { color: #9a3412; }

    /* ─── Student Rows ───────────────────────────────────────── */
    .rp-student-row {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 14px 24px;
      border-bottom: 1px solid #f8fafc;
      transition: background 0.12s;
    }
    .rp-student-row:last-child { border-bottom: none; }
    .rp-student-row:hover { background: #fafafa; }

    .rp-avatar {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: linear-gradient(135deg, #6d28d9, #4f46e5);
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 12px;
      flex-shrink: 0;
      letter-spacing: 0.02em;
    }
    .rp-stu-name {
      font-weight: 700;
      font-size: 14px;
      color: #0f172a;
      margin-bottom: 2px;
    }
    .rp-stu-meta {
      font-size: 12px;
      color: #94a3b8;
    }
    .rp-stu-pct {
      font-size: 17px;
      font-weight: 800;
      color: #dc2626;
      margin-left: auto;
      flex-shrink: 0;
    }
    .rp-stu-status { margin-left: 12px; flex-shrink: 0; }

    /* ─── Progress bar (Risk %) ──────────────────────────────── */
    .rp-risk-bar-wrap {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .rp-risk-bar {
      flex: 1;
      height: 6px;
      background: #f1f5f9;
      border-radius: 100px;
      overflow: hidden;
      min-width: 50px;
    }
    .rp-risk-bar-fill {
      height: 100%;
      border-radius: 100px;
      transition: width 0.4s;
    }
    .fill-low    { background: #10b981; }
    .fill-medium { background: #f59e0b; }
    .fill-high   { background: #ef4444; }

    /* ─── Empty States ───────────────────────────────────────── */
    .rp-empty {
      text-align: center;
      padding: 48px 24px;
      color: #94a3b8;
    }
    .rp-empty svg {
      width: 40px;
      height: 40px;
      margin: 0 auto 12px;
      color: #cbd5e1;
      display: block;
    }
    .rp-empty-title { font-size: 14px; font-weight: 600; color: #64748b; margin-bottom: 4px; }
    .rp-empty-desc  { font-size: 13px; }

    /* ─── View-all footer ────────────────────────────────────── */
    .rp-view-all {
      text-align: center;
      padding: 14px 24px;
      border-top: 1px solid #f1f5f9;
    }
    .rp-view-all a,
    .rp-view-all button {
      font-size: 13px;
      font-weight: 600;
      color: #4f46e5;
      background: none;
      border: none;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 0;
    }
    .rp-view-all a:hover,
    .rp-view-all button:hover { color: #4338ca; text-decoration: underline; }

    /* ─── Divider ────────────────────────────────────────────── */
    .rp-divider { height: 1px; background: #f1f5f9; margin: 0; }
  </style>
  @endpush

  <div class="rp-wrap">

    {{-- ── Page Header ── --}}
    <div class="rp-header">
      <div>
        <p class="rp-eyebrow">Academic Year {{ date('Y') }}&ndash;{{ date('y', strtotime('+1 year')) }}</p>
        <h2 class="rp-title">Reports &amp; Analytics</h2>
        <p class="rp-subtitle">Institutional performance &mdash; class-wise breakdown &amp; student insights</p>
      </div>
      <div class="rp-actions">
        <button onclick="window.print()" class="btn-rp">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
          Print
        </button>
        <button onclick="exportTableToCSV('pmrs-report.csv')" class="btn-rp btn-rp-export">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Export CSV
        </button>
      </div>
    </div>

    {{-- ── KPI Cards ── --}}
    <div class="rp-kpi-grid">
      <div class="rp-kpi">
        <div class="rp-kpi-icon violet">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="rp-kpi-body">
          <div class="rp-kpi-value">{{ $summary['total_students'] }}</div>
          <div class="rp-kpi-label">Total Students</div>
          <div class="rp-kpi-note good">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            Enrolled this session
          </div>
        </div>
      </div>

      <div class="rp-kpi">
        <div class="rp-kpi-icon red">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div class="rp-kpi-body">
          <div class="rp-kpi-value">{{ $summary['slow_learners'] }}</div>
          <div class="rp-kpi-label">Slow Learners</div>
          @if($summary['total_students'] > 0)
          <div class="rp-kpi-note bad">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ round($summary['slow_learners'] / $summary['total_students'] * 100, 1) }}% of total
          </div>
          @endif
        </div>
      </div>

      <div class="rp-kpi">
        <div class="rp-kpi-icon amber">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="rp-kpi-body">
          <div class="rp-kpi-value">{{ $summary['at_risk'] }}</div>
          <div class="rp-kpi-label">At Risk</div>
          <div class="rp-kpi-note bad">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Needs intervention
          </div>
        </div>
      </div>

      <div class="rp-kpi">
        <div class="rp-kpi-icon emerald">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="rp-kpi-body">
          <div class="rp-kpi-value">{{ $summary['performing_well'] }}</div>
          <div class="rp-kpi-label">Performing Well</div>
          @if($summary['total_students'] > 0)
          <div class="rp-kpi-note good">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            {{ round($summary['performing_well'] / $summary['total_students'] * 100, 1) }}% of total
          </div>
          @endif
        </div>
      </div>
    </div>

    {{-- ── Chart + Class Table ── --}}
    <div class="rp-grid">

      {{-- Performance Distribution — horizontal bars + donut --}}
      <div class="rp-perf-card">
        {{-- Header --}}
        <div class="rp-card-inner" style="padding-bottom:0; flex-shrink:0;">
          <div class="rp-card-head" style="margin-bottom:16px;">
            <div class="rp-card-title">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
              Performance Distribution
            </div>
            <span class="rp-tag rp-tag-violet">By class</span>
          </div>
        </div>

        @if(count($classBreakdown) > 0)
        {{-- Body: bars + donut side by side --}}
        <div class="rp-perf-top">
          {{-- Horizontal bars --}}
          <div class="rp-perf-bars">
            @foreach($classBreakdown as $row)
            @php
              $total   = max($row['total'], 1);
              $goodPct = round($row['good'] / $total * 100);
              $badPct  = round($row['slow'] / $total * 100);
            @endphp
            <div class="rp-hbar-row">
              <div class="rp-hbar-label">Cls {{ $row['class'] }}</div>
              <div class="rp-hbar-track">
                <div class="rp-hbar-good" style="width:{{ $goodPct }}%;">
                  @if($goodPct > 12)<span class="rp-hbar-good-txt">{{ $row['good'] }}</span>@endif
                </div>
                <div class="rp-hbar-bad" style="width:{{ $badPct }}%;">
                  @if($badPct > 8)<span class="rp-hbar-bad-txt">{{ $row['slow'] }}</span>@endif
                </div>
              </div>
              <div class="rp-hbar-total">{{ $row['total'] }}</div>
            </div>
            @endforeach
          </div>

          {{-- Donut summary --}}
          @php
            $totalAll = $classBreakdown->sum('total');
            $goodAll  = $classBreakdown->sum('good');
            $slowAll  = $classBreakdown->sum('slow');
            $goodPctAll = $totalAll > 0 ? round($goodAll / $totalAll * 100) : 0;
          @endphp
          <div class="rp-perf-donut">
            <div class="rp-donut-wrap">
              <canvas id="donutChart" class="rp-donut-canvas"
                width="100" height="100"
                aria-label="Donut chart: {{ $goodPctAll }}% performing well"></canvas>
              <div class="rp-donut-center">
                <div class="rp-donut-pct">{{ $goodPctAll }}%</div>
                <div class="rp-donut-sub">on track</div>
              </div>
            </div>
            <div class="rp-donut-stats">
              <div class="rp-donut-stat">
                <div class="rp-donut-stat-left">
                  <div class="rp-donut-dot" style="background:#10b981;"></div>
                  Good
                </div>
                <div class="rp-donut-stat-val">{{ $goodAll }}</div>
              </div>
              <div class="rp-donut-stat">
                <div class="rp-donut-stat-left">
                  <div class="rp-donut-dot" style="background:#fca5a5;"></div>
                  Slow
                </div>
                <div class="rp-donut-stat-val">{{ $slowAll }}</div>
              </div>
              <div class="rp-donut-stat">
                <div class="rp-donut-stat-left">
                  <div class="rp-donut-dot" style="background:#e2e8f0;"></div>
                  Total
                </div>
                <div class="rp-donut-stat-val">{{ $totalAll }}</div>
              </div>
            </div>
          </div>
        </div>

        {{-- Legend footer --}}
        <div class="rp-perf-legend">
          <div class="rp-legend-item">
            <div class="rp-legend-dot" style="background:#10b981;"></div>
            Performing well
          </div>
          <div class="rp-legend-item">
            <div class="rp-legend-dot" style="background:#fca5a5;"></div>
            Slow learners
          </div>
          <div class="rp-legend-item" style="margin-left:auto; color:#94a3b8; font-size:11px;">
            Numbers shown = student count
          </div>
        </div>

        @else
        <div class="rp-empty" style="padding: 48px 24px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:40px;height:40px;margin:0 auto 12px;color:#cbd5e1;display:block;"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
          <div class="rp-empty-title">No chart data yet</div>
          <div class="rp-empty-desc">Add marks to students to see class performance here.</div>
        </div>
        @endif
      </div>

      {{-- Class breakdown table --}}
      <div class="rp-card" style="display:flex; flex-direction:column;">
        <div class="rp-card-inner" style="padding-bottom:0;">
          <div class="rp-card-head" style="margin-bottom:0;">
            <div class="rp-card-title">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
              Class-wise Analytics
            </div>
            <span class="rp-tag rp-tag-slate">{{ count($classBreakdown) }} classes</span>
          </div>
        </div>
        <div class="rp-divider" style="margin-top:16px;"></div>
        <div class="rp-table-wrap" style="flex:1;">
          <table class="rp-table" id="reportTable">
            <thead>
              <tr>
                <th>Class</th>
                <th>Total</th>
                <th>Slow</th>
                <th>Good</th>
                <th>Risk %</th>
              </tr>
            </thead>
            <tbody>
              @forelse($classBreakdown as $row)
              @php
                $pct = $row['total'] > 0 ? round($row['slow'] / $row['total'] * 100, 1) : 0;
                $fillClass = $pct > 30 ? 'fill-high' : ($pct > 15 ? 'fill-medium' : 'fill-low');
                $badgePct  = $pct > 30 ? 'rp-badge-red' : ($pct > 15 ? 'rp-badge-amber' : 'rp-badge-green');
              @endphp
              <tr>
                <td class="fw">Class {{ $row['class'] }}</td>
                <td style="font-weight:600;">{{ $row['total'] }}</td>
                <td><span class="rp-badge rp-badge-red">{{ $row['slow'] }}</span></td>
                <td><span class="rp-badge rp-badge-green">{{ $row['good'] }}</span></td>
                <td>
                  <div class="rp-risk-bar-wrap">
                    <div class="rp-risk-bar">
                      <div class="rp-risk-bar-fill {{ $fillClass }}" style="width:{{ min($pct, 100) }}%;"></div>
                    </div>
                    <span class="rp-badge {{ $badgePct }}" style="min-width:42px; justify-content:center;">{{ $pct }}%</span>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5">
                  <div class="rp-empty" style="padding:32px 16px;">
                    <div class="rp-empty-title">No class data available</div>
                    <div class="rp-empty-desc">Class analytics will appear once marks are recorded.</div>
                  </div>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- ── Slow Learners ── --}}
    <div class="rp-card">
      {{-- Header --}}
      <div class="rp-card-inner" style="padding-bottom:16px;">
        <div class="rp-card-head" style="margin-bottom:0;">
          <div class="rp-card-title" style="color:#991b1b;">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            Critical Attention — Slow Learners
            <span class="rp-badge rp-badge-red" style="margin-left:4px;">{{ count($slowLearners) }}</span>
          </div>
        </div>
      </div>

      {{-- Alert --}}
      @php $noRemedial = $slowLearners->filter(fn($s) => $s->remedialActions->count() === 0)->count(); @endphp
      @if($noRemedial > 0)
      <div style="padding: 0 24px 16px;">
        <div class="rp-alert">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <div class="rp-alert-text">
            <strong>{{ $noRemedial }} student{{ $noRemedial > 1 ? 's have' : ' has' }} no remedial actions assigned.</strong>
            Review the list below and assign tasks to ensure timely support.
          </div>
        </div>
      </div>
      @endif

      <div class="rp-divider"></div>

      {{-- Table header --}}
      <table class="rp-table" style="width:100%;">
        <thead>
          <tr>
            <th style="padding-left:24px; width:35%;">Student</th>
            <th>Roll No</th>
            <th>Class</th>
            <th>Avg %</th>
            <th style="padding-right:24px;">Remedial Status</th>
          </tr>
        </thead>
      </table>

      {{-- Student rows --}}
      @forelse($slowLearners as $student)
      <div class="rp-student-row">
        <div class="rp-avatar">{{ strtoupper(substr($student->name, 0, 2)) }}</div>
        <div style="flex:1; min-width:0;">
          <div class="rp-stu-name">{{ $student->name }}</div>
          <div class="rp-stu-meta">{{ $student->email }}</div>
        </div>
        <span class="rp-badge rp-badge-slate" style="flex-shrink:0;">{{ $student->roll_no }}</span>
        <span style="font-size:13px; color:#475569; white-space:nowrap; flex-shrink:0;">
          Class {{ $student->class }}{{ $student->section ? '-'.$student->section : '' }}
        </span>
        <div class="rp-stu-pct">{{ $student->average_percentage }}%</div>
        <div class="rp-stu-status">
          @if($student->remedialActions->count() > 0)
            <span class="rp-badge rp-badge-amber">
              {{ $student->remedialActions->count() }} active task{{ $student->remedialActions->count() > 1 ? 's' : '' }}
            </span>
          @else
            <span class="rp-badge rp-badge-red">No remedials</span>
          @endif
        </div>
      </div>
      @empty
      <div class="rp-empty">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="20 6 9 17 4 12"/><circle cx="12" cy="12" r="10"/></svg>
        <div class="rp-empty-title">All students are on track</div>
        <div class="rp-empty-desc">No slow learners identified this session. Keep it up!</div>
      </div>
      @endforelse

      @if(count($slowLearners) > 0)
      <div class="rp-view-all">
        <button onclick="window.location.href='#'">
          View full slow-learner report
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
      </div>
      @endif
    </div>

  </div>{{-- /rp-wrap --}}

  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    /* ── CSV Export ── */
    function exportTableToCSV(filename) {
      const rows = document.querySelectorAll('#reportTable tr');
      const csv  = Array.from(rows).map(row =>
        Array.from(row.querySelectorAll('td, th'))
          .map(cell => '"' + cell.innerText.replace(/"/g, '""').trim() + '"')
          .join(',')
      );
      const blob = new Blob([csv.join('\n')], { type: 'text/csv' });
      const a    = Object.assign(document.createElement('a'), {
        href: URL.createObjectURL(blob),
        download: filename,
        style: 'display:none'
      });
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    }

    /* ── Donut Chart ── */
    window.addEventListener('DOMContentLoaded', function () {
      var donutCanvas = document.getElementById('donutChart');
      if (!donutCanvas) return;

      var rawData = {!! json_encode($classBreakdown) !!};
      if (!rawData.length) return;

      var goodAll = rawData.reduce(function(s, d){ return s + d.good; }, 0);
      var slowAll = rawData.reduce(function(s, d){ return s + d.slow; }, 0);

      Chart.defaults.font.family = "'Inter', 'Segoe UI', system-ui, sans-serif";

      new Chart(donutCanvas, {
        type: 'doughnut',
        data: {
          labels: ['Performing well', 'Slow learners'],
          datasets: [{
            data: [goodAll, slowAll],
            backgroundColor: ['#10b981', '#fca5a5'],
            borderColor:     ['#10b981', '#fca5a5'],
            borderWidth: 0,
            hoverOffset: 4,
          }]
        },
        options: {
          responsive: false,
          cutout: '72%',
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: '#0f172a',
              titleFont: { size: 11, weight: '700' },
              bodyFont: { size: 11 },
              padding: 8,
              cornerRadius: 6,
            }
          }
        }
      });
    });
  </script>
  @endpush
</x-app-layout>