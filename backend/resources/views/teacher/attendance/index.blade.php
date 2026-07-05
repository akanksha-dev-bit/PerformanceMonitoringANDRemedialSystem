<x-app-layout>
  <x-slot name="title">Mark Attendance</x-slot>

  @push('styles')
  <style>
    /* ─── Base ──────────────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; }
    .at-wrap { font-family: 'Inter', 'Segoe UI', system-ui, sans-serif; color: #1e293b; }

    /* ─── Page Header ───────────────────────────────────────── */
    .at-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 16px;
      flex-wrap: wrap;
      margin-bottom: 24px;
    }
    .at-eyebrow {
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: #94a3b8;
      margin-bottom: 4px;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .at-eyebrow-dot {
      width: 7px; height: 7px;
      border-radius: 50%;
      background: #22c55e;
      display: inline-block;
      box-shadow: 0 0 0 3px rgba(34,197,94,0.18);
    }
    .at-title {
      font-size: 22px;
      font-weight: 700;
      color: #0f172a;
      letter-spacing: -0.02em;
      margin: 0 0 3px;
      line-height: 1.2;
    }
    .at-subtitle { font-size: 13px; color: #64748b; margin: 0; }

    /* ─── Progress Summary Bar ──────────────────────────────── */
    .at-summary {
      background: #fff;
      border: 1px solid #f1f5f9;
      border-radius: 16px;
      padding: 18px 22px;
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 22px;
      flex-wrap: wrap;
    }
    .at-summary-stat {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 2px;
      min-width: 56px;
    }
    .at-summary-val {
      font-size: 22px;
      font-weight: 800;
      color: #0f172a;
      line-height: 1;
    }
    .at-summary-lbl {
      font-size: 10px;
      font-weight: 600;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      white-space: nowrap;
    }
    .at-summary-divider {
      width: 1px;
      height: 36px;
      background: #f1f5f9;
      flex-shrink: 0;
    }
    .at-summary-track-wrap { flex: 1; min-width: 160px; }
    .at-summary-track-label {
      display: flex;
      justify-content: space-between;
      font-size: 12px;
      font-weight: 600;
      color: #64748b;
      margin-bottom: 7px;
    }
    .at-summary-track {
      height: 8px;
      background: #f1f5f9;
      border-radius: 100px;
      overflow: hidden;
    }
    .at-summary-fill {
      height: 100%;
      border-radius: 100px;
      background: linear-gradient(90deg, #4f46e5, #7c3aed);
      transition: width 0.6s ease;
    }
    .at-summary-fill.complete { background: linear-gradient(90deg, #10b981, #059669); }
    .at-summary-note {
      font-size: 11px;
      color: #94a3b8;
      margin-top: 6px;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    /* ─── Flash ─────────────────────────────────────────────── */
    .at-flash {
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
    .at-flash svg { flex-shrink: 0; color: #22c55e; }

    /* ─── Cards Grid ────────────────────────────────────────── */
    .at-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 14px;
    }
    @media (max-width: 480px) {
      .at-grid { grid-template-columns: 1fr; }
    }

    /* ─── Period Card ───────────────────────────────────────── */
    .at-card {
      background: #fff;
      border: 1px solid #f1f5f9;
      border-radius: 18px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      transition: box-shadow 0.18s, transform 0.18s, border-color 0.18s;
      position: relative;
    }
    .at-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 30px -4px rgba(0,0,0,0.10);
      border-color: #e0e7ff;
    }
    /* Left accent stripe */
    .at-card-stripe {
      height: 4px;
      width: 100%;
      flex-shrink: 0;
    }
    .stripe-pending  { background: linear-gradient(90deg, #4f46e5, #7c3aed); }
    .stripe-done     { background: linear-gradient(90deg, #10b981, #059669); }

    .at-card-body { padding: 18px 20px; flex: 1; display: flex; flex-direction: column; gap: 0; }

    /* Top row: period badge + status */
    .at-card-top {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 14px;
    }
    .at-period-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 11px;
      font-weight: 700;
      padding: 4px 10px;
      border-radius: 100px;
      letter-spacing: 0.04em;
      text-transform: uppercase;
    }
    .badge-pend { background: #ede9fe; color: #5b21b6; }
    .badge-done { background: #d1fae5; color: #065f46; }

    .at-status-pill {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 11px;
      font-weight: 600;
      padding: 4px 10px;
      border-radius: 100px;
    }
    .pill-pending  { background: #fff7ed; color: #c2410c; }
    .pill-done     { background: #d1fae5; color: #065f46; }
    .at-status-dot {
      width: 6px; height: 6px;
      border-radius: 50%;
      flex-shrink: 0;
    }

    /* Class + Subject */
    .at-class-name {
      font-size: 17px;
      font-weight: 800;
      color: #0f172a;
      margin: 0 0 3px;
      line-height: 1.2;
    }
    .at-subject {
      font-size: 13px;
      font-weight: 600;
      color: #4f46e5;
      margin-bottom: 12px;
    }

    /* Meta row */
    .at-card-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      margin-bottom: 16px;
    }
    .at-meta-item {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 12px;
      font-weight: 500;
      color: #64748b;
    }
    .at-meta-item svg { flex-shrink: 0; color: #94a3b8; }

    /* CTA Button */
    .at-card-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 11px 20px;
      border-radius: 12px;
      font-size: 13px;
      font-weight: 700;
      text-decoration: none;
      border: none;
      cursor: pointer;
      transition: all 0.16s;
      width: 100%;
      margin-top: auto;
    }
    .btn-mark {
      background: #4f46e5;
      color: #fff;
      box-shadow: 0 4px 14px -2px rgba(79,70,229,0.38);
    }
    .btn-mark:hover {
      background: #4338ca;
      color: #fff;
      transform: translateY(-1px);
      box-shadow: 0 6px 18px -2px rgba(79,70,229,0.44);
    }
    .btn-review {
      background: #f8fafc;
      color: #10b981;
      border: 1.5px solid #bbf7d0;
    }
    .btn-review:hover {
      background: #f0fdf4;
      border-color: #86efac;
      color: #059669;
    }
    .btn-review svg, .btn-mark svg { flex-shrink: 0; }

    /* ─── Empty State ────────────────────────────────────────── */
    .at-empty {
      grid-column: 1 / -1;
      background: #fff;
      border: 1px solid #f1f5f9;
      border-radius: 18px;
      padding: 64px 32px;
      text-align: center;
    }
    .at-empty-icon {
      width: 60px; height: 60px;
      border-radius: 18px;
      background: #f8fafc;
      display: flex; align-items: center; justify-content: center;
      color: #cbd5e1;
      margin: 0 auto 16px;
    }
    .at-empty-icon svg { width: 28px; height: 28px; }
    .at-empty-title { font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 6px; }
    .at-empty-desc  { font-size: 13px; color: #64748b; }

    /* ─── Mobile tweaks ─────────────────────────────────────── */
    @media (max-width: 600px) {
      .at-title    { font-size: 20px; }
      .at-summary  { gap: 14px; }
      .at-summary-val { font-size: 20px; }
      .at-card-body { padding: 16px; }
      .at-class-name { font-size: 16px; }
    }
  </style>
  @endpush

  <div class="at-wrap">

    {{-- ── Page Header ── --}}
    <div class="at-header">
      <div>
        <div class="at-eyebrow">
          <span class="at-eyebrow-dot"></span>
          {{ $today }}
        </div>
        <h2 class="at-title">Attendance Workspace</h2>
        <p class="at-subtitle">Mark today's scheduled periods — all in one place</p>
      </div>
    </div>

    {{-- ── Progress Summary ── --}}
    @php
      $total     = count($todaySlots);
      $marked    = count(array_filter($todaySlots->toArray(), fn($s) => in_array($s['period_number'] ?? 0, $completedSessionKeys)));
      $pending   = $total - $marked;
      $pct       = $total > 0 ? round($marked / $total * 100) : 0;
      $allDone   = $total > 0 && $marked === $total;
    @endphp

    @if($total > 0)
    <div class="at-summary">
      <div class="at-summary-stat">
        <div class="at-summary-val">{{ $total }}</div>
        <div class="at-summary-lbl">Total</div>
      </div>
      <div class="at-summary-divider"></div>
      <div class="at-summary-stat">
        <div class="at-summary-val" style="color:#10b981;">{{ $marked }}</div>
        <div class="at-summary-lbl">Marked</div>
      </div>
      <div class="at-summary-divider"></div>
      <div class="at-summary-stat">
        <div class="at-summary-val" style="color:#f59e0b;">{{ $pending }}</div>
        <div class="at-summary-lbl">Pending</div>
      </div>
      <div class="at-summary-divider"></div>
      <div class="at-summary-track-wrap">
        <div class="at-summary-track-label">
          <span>{{ $allDone ? '🎉 All done for today!' : 'Today\'s progress' }}</span>
          <span style="color:{{ $allDone ? '#10b981' : '#4f46e5' }}; font-weight:700;">{{ $pct }}%</span>
        </div>
        <div class="at-summary-track">
          <div class="at-summary-fill {{ $allDone ? 'complete' : '' }}" style="width:{{ $pct }}%;"></div>
        </div>
        <div class="at-summary-note">
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          @if($allDone)
            All {{ $total }} periods marked — great work!
          @else
            {{ $pending }} period{{ $pending > 1 ? 's' : '' }} still need{{ $pending === 1 ? 's' : '' }} to be marked
          @endif
        </div>
      </div>
    </div>
    @endif

    {{-- ── Flash ── --}}
    @if(session('success'))
    <div class="at-flash">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      {{ session('success') }}
    </div>
    @endif

    {{-- ── Period Cards ── --}}
    <div class="at-grid">
      @forelse($todaySlots as $slot)
        @php $isMarked = in_array($slot->period_number, $completedSessionKeys); @endphp

        <div class="at-card">
          <div class="at-card-stripe {{ $isMarked ? 'stripe-done' : 'stripe-pending' }}"></div>

          <div class="at-card-body">
            {{-- Top: period badge + status pill --}}
            <div class="at-card-top">
              <span class="at-period-badge {{ $isMarked ? 'badge-done' : 'badge-pend' }}">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Period {{ $slot->period_number }}
              </span>
              <span class="at-status-pill {{ $isMarked ? 'pill-done' : 'pill-pending' }}">
                <span class="at-status-dot" style="background: {{ $isMarked ? '#10b981' : '#f97316' }};"></span>
                {{ $isMarked ? 'Marked' : 'Pending' }}
              </span>
            </div>

            {{-- Class & Subject --}}
            <div class="at-class-name">{{ $slot->classroom->display_name }}</div>
            <div class="at-subject">{{ $slot->subject->name }}</div>

            {{-- Meta: time + (optionally student count) --}}
            <div class="at-card-meta">
              <div class="at-meta-item">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} — {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
              </div>
              @if(isset($slot->classroom->students_count))
              <div class="at-meta-item">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                {{ $slot->classroom->students_count }} students
              </div>
              @endif
            </div>

            {{-- CTA --}}
            @if($isMarked)
              <a href="{{ route('attendance.mark', $slot->id) }}" class="at-card-btn btn-review">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                Review Attendance
              </a>
            @else
              <a href="{{ route('attendance.mark', $slot->id) }}" class="at-card-btn btn-mark">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Mark Attendance
              </a>
            @endif
          </div>
        </div>

      @empty
        <div class="at-empty">
          <div class="at-empty-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          </div>
          <div class="at-empty-title">No periods scheduled today</div>
          <div class="at-empty-desc">You have no timetabled slots for {{ $today }}.<br>Enjoy your free day or contact admin if this seems wrong.</div>
        </div>
      @endforelse
    </div>

  </div>{{-- /at-wrap --}}
</x-app-layout>