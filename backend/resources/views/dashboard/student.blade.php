<x-app-layout>
  <x-slot name="title">Student Dashboard</x-slot>

  @push('styles')
    <style>
      :root {
        --grad: linear-gradient(135deg, #6C5CE7, #5A4BD6);
      }

      .sd-wrap {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2px 2px 4px;
        display: flex;
        flex-direction: column;
        gap: 20px;
      }

      .sd-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
      }

      .sd-header-left {
        display: flex;
        align-items: center;
        gap: 16px;
      }

      .sd-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: var(--grad);
        color: #fff;
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 16px rgba(108, 92, 231, 0.35);
        flex-shrink: 0;
      }

      .sd-header-title {
        font-size: 28px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
      }

      .sd-header-sub {
        font-size: 16px;
        color: #94a3b8;
        margin-top: 3px;
      }

      .sd-quick-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
      }

      .sd-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 18px;
        border-radius: 100px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all .2s ease;
        border: none;
      }

      .sd-btn-primary {
        background: var(--grad);
        color: #fff;
        box-shadow: 0 4px 14px rgba(108, 92, 231, 0.3);
      }

      .sd-btn-primary:hover {
        opacity: .88;
        transform: translateY(-1px);
      }

      .sd-btn-outline {
        background: #fff;
        color: #6C5CE7;
        border: 1.5px solid #6C5CE7;
      }

      .sd-btn-outline:hover {
        background: #6C5CE7;
        color: #fff;
      }

      .sd-btn-ghost {
        background: #f8fafc;
        color: #64748b;
        border: 1.5px solid #e2e8f0;
      }

      .sd-btn-ghost:hover {
        background: #e2e8f0;
        color: #1e293b;
      }

      /* KPI Grid */
      .sd-kpi {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
      }

      .sd-kpi-card {
        position: relative;
        background: #fff;
        border-radius: 20px;
        padding: 24px;
        border: 1px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        gap: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
      }

      .sd-kpi-card::before {
        content: "";
        position: absolute;
        top: 0; right: 0;
        width: 100px; height: 100px;
        background: currentColor;
        filter: blur(50px);
        opacity: 0.05;
        transform: translate(20%, -20%);
        transition: opacity 0.3s;
      }

      .sd-kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.1);
      }

      .sd-kpi-card:hover::before {
        opacity: 0.1;
      }

      .sd-kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        background: #f8fafc;
        color: inherit;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
      }

      .sd-kpi-content {
        display: flex;
        flex-direction: column;
        gap: 4px;
      }

      .sd-kpi-val {
        font-size: 32px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        letter-spacing: -0.02em;
      }

      .sd-kpi-lbl {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
      }

      /* KPI Themes */
      .kpi-purple { color: #6c5ce7; background: linear-gradient(135deg, #f5f3ff, #fff); }
      .kpi-blue { color: #3b82f6; background: linear-gradient(135deg, #eff6ff, #fff); }
      .kpi-green { color: #10b981; background: linear-gradient(135deg, #f0fdf4, #fff); }
      .kpi-gold { color: #f59e0b; background: linear-gradient(135deg, #fffbeb, #fff); }
      .kpi-orange { color: #f97316; background: linear-gradient(135deg, #fff7ed, #fff); }

      .kpi-purple .sd-kpi-icon { background: #ede9fe; }
      .kpi-blue .sd-kpi-icon { background: #dbeafe; }
      .kpi-green .sd-kpi-icon { background: #dcfce7; }
      .kpi-gold .sd-kpi-icon { background: #fef3c7; }
      .kpi-orange .sd-kpi-icon { background: #ffedd5; }

      /* Two column layout */
      .sd-row {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 24px;
      }

      .sd-row.full {
        grid-template-columns: 1fr;
      }

      @media(max-width:900px) {
        .sd-row {
          grid-template-columns: 1fr;
        }
      }

      /* Cards */
      .sd-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid rgba(0, 0, 0, 0.06);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
      }

      .sd-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 18px;
      }

      .sd-card-title span {
        font-size: 18px;
      }

      /* Subject bars */
      .sd-subject-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 14px;
      }

      .sd-subject-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .sd-subject-name {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
      }

      .sd-subject-pct {
        font-size: 12px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 100px;
      }

      .sd-bar-bg {
        height: 7px;
        background: #f1f5f9;
        border-radius: 100px;
        overflow: hidden;
      }

      .sd-bar-fill {
        height: 100%;
        border-radius: 100px;
        transition: width 1s ease;
      }

      /* Rank card */
      .sd-rank-card {
        text-align: center;
        padding: 40px 24px;
        position: relative;
        overflow: hidden;
        background: linear-gradient(180deg, #fff 0%, #f5f3ff 100%);
      }

      .sd-rank-visual {
        width: 140px;
        height: 140px;
        margin: 0 auto 20px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .sd-rank-aura {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: var(--grad);
        opacity: 0.1;
        animation: pulse 3s infinite;
      }

      @keyframes pulse {
        0% { transform: scale(1); opacity: 0.1; }
        50% { transform: scale(1.1); opacity: 0.15; }
        100% { transform: scale(1); opacity: 0.1; }
      }

      .sd-rank-big {
        font-size: 72px;
        font-weight: 900;
        background: var(--grad);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1;
        z-index: 2;
      }

      .sd-rank-medal {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 48px;
        height: 48px;
        background: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border: 2px solid #fff;
        z-index: 3;
        animation: float 4s ease-in-out infinite;
      }

      @keyframes float {
        0% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-8px) rotate(5deg); }
        100% { transform: translateY(0px) rotate(0deg); }
      }

      .sd-rank-of {
        font-size: 15px;
        font-weight: 600;
        color: #64748b;
        margin-top: 4px;
      }

      .sd-rank-percentile {
        display: inline-flex;
        padding: 4px 12px;
        background: rgba(108, 92, 231, 0.1);
        color: #6C5CE7;
        border-radius: 100px;
        font-size: 12px;
        font-weight: 700;
        margin-top: 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
      }

      .sd-rank-label {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        margin-top: 16px;
        line-height: 1.5;
        max-width: 200px;
        margin-left: auto;
        margin-right: auto;
      }

      /* Recommendations */
      .sd-rec-item {
        display: flex;
        gap: 12px;
        padding: 14px;
        border-radius: 12px;
        background: #fafafa;
        border: 1px solid #f1f5f9;
        margin-bottom: 10px;
        transition: all .2s;
      }

      .sd-rec-item:hover {
        background: #f5f3ff;
        border-color: #e0d9ff;
      }

      .sd-rec-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 16px;
      }

      .sd-rec-urgent {
        background: #fff0f0;
      }

      .sd-rec-warn {
        background: #fffbeb;
      }

      .sd-rec-title {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
      }

      .sd-rec-tip {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
        line-height: 1.4;
      }

      /* Premium Badges */
      .sd-badges {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 24px;
        padding: 10px 0;
      }

      .sd-badge-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        cursor: pointer;
        position: relative;
      }

      .sd-badge-item:hover {
        transform: translateY(-8px) scale(1.05);
      }

      .sd-badge-visual {
        width: 80px;
        height: 80px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        z-index: 1;
      }

      .sd-badge-visual::before {
        content: "";
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FFD700, #FFA500); /* Default Gold */
        z-index: -1;
        opacity: 0.8;
        transition: opacity 0.3s;
      }

      .sd-badge-item:hover .sd-badge-visual::before {
        opacity: 1;
        animation: rotate 3s linear infinite;
      }

      @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
      }

      .sd-badge-inner {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
      }

      .sd-badge-label {
        font-size: 12px;
        font-weight: 700;
        color: #1e293b;
        text-align: center;
        line-height: 1.2;
      }

      /* Specific Badge Themes */
      .badge-gold .sd-badge-visual::before { background: linear-gradient(135deg, #FFD700, #FFA500); }
      .badge-silver .sd-badge-visual::before { background: linear-gradient(135deg, #E2E2E2, #999999); }
      .badge-bronze .sd-badge-visual::before { background: linear-gradient(135deg, #CD7F32, #8B4513); }
      .badge-purple .sd-badge-visual::before { background: linear-gradient(135deg, #a78bfa, #6d28d9); }
      .badge-blue .sd-badge-visual::before { background: linear-gradient(135deg, #60a5fa, #1d4ed8); }
      .badge-fire .sd-badge-visual::before { background: linear-gradient(135deg, #fbbf24, #ef4444); }

      .sd-badge-glow {
        position: absolute;
        width: 100%;
        height: 100%;
        background: inherit;
        filter: blur(15px);
        opacity: 0;
        transition: opacity 0.3s;
        z-index: 0;
        border-radius: 50%;
      }

      .sd-badge-item:hover .sd-badge-glow {
        opacity: 0.4;
      }

      /* Streak */
      .sd-streak {
        display: flex;
        align-items: center;
        gap: 16px;
      }

      .sd-streak-num {
        font-size: 48px;
        font-weight: 900;
        color: #f59e0b;
        line-height: 1;
      }

      .sd-streak-label {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
      }

      .sd-streak-sub {
        font-size: 12px;
        color: #94a3b8;
      }

      /* Profile info rows */
      .sd-info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
      }

      .sd-info-row:last-child {
        border-bottom: none;
      }

      .sd-info-label {
        color: #94a3b8;
        font-weight: 500;
      }

      .sd-info-val {
        color: #1e293b;
        font-weight: 600;
      }

      /* Status badge */
      .sd-status {
        padding: 3px 10px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
      }

      .sd-status-good {
        background: #dcfce7;
        color: #16a34a;
      }

      .sd-status-warn {
        background: #fef9c3;
        color: #b45309;
      }

      .sd-status-bad {
        background: #fee2e2;
        color: #dc2626;
      }

      .sd-status-none {
        background: #f1f5f9;
        color: #94a3b8;
      }

      /* Table */
      .sd-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
      }

      .sd-table th {
        padding: 10px 14px;
        background: #f8fafc;
        color: #94a3b8;
        font-weight: 600;
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .05em;
      }

      .sd-table td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
      }

      .sd-table tr:hover td {
        background: #fafeff;
      }

      .sd-table tr:last-child td {
        border-bottom: none;
      }

      /* Empty */
      .sd-empty {
        text-align: center;
        padding: 40px;
        color: #94a3b8;
      }

      .sd-empty-icon {
        font-size: 48px;
        margin-bottom: 12px;
      }
    </style>
  @endpush

  <div class="sd-wrap">

    {{-- ── Header ── --}}
    <div class="sd-header">
      <div class="sd-header-left">
        
        <div>
          <div class="sd-header-title">Welcome back, {{ $studentProfile->name }} 👋</div>
          <div class="sd-header-sub">{{ $studentProfile->class }} {{ $studentProfile->section }} · Roll
            #{{ $studentProfile->roll_no ?? 'N/A' }}</div>
        </div>
      </div>
      <div class="sd-quick-actions">
        <a href="{{ route('student.progress') }}" class="sd-btn sd-btn-primary">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
          </svg>
          View Progress
        </a>
        <a href="{{ route('marks.index') }}" class="sd-btn sd-btn-outline">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
            <polyline points="14 2 14 8 20 8" />
          </svg>
          My Marks
        </a>
        <button class="sd-btn sd-btn-ghost" onclick="window.print()" title="Download report">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="6 9 6 2 18 2 18 9" />
            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
            <rect x="6" y="14" width="12" height="8" />
          </svg>
          Download
        </button>
      </div>
    </div>

    {{-- ── KPI Cards ── --}}
    <div class="sd-kpi">
      <div class="sd-kpi-card kpi-purple">
        <div class="sd-kpi-icon">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
        </div>
        <div class="sd-kpi-content">
          <div class="sd-kpi-val">{{ $marks->count() }}</div>
          <div class="sd-kpi-lbl">Exams Recorded</div>
        </div>
      </div>

      <div class="sd-kpi-card kpi-blue">
        <div class="sd-kpi-icon">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
        </div>
        <div class="sd-kpi-content">
          <div class="sd-kpi-val">{{ $studentProfile->has_marks ? $averagePercentage . '%' : 'N/A' }}</div>
          <div class="sd-kpi-lbl">Overall Average</div>
        </div>
      </div>

      <div class="sd-kpi-card kpi-green">
        <div class="sd-kpi-icon">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <div class="sd-kpi-content">
          <div class="sd-kpi-val" style="font-size:24px;">{{ $performanceLabel }}</div>
          <div class="sd-kpi-lbl">System Status</div>
        </div>
      </div>

      <div class="sd-kpi-card kpi-gold">
        <div class="sd-kpi-icon">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path><path d="M4 22h16"></path><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path><path d="M18 2H6v7a6 6 0 0 0 12 0V2z"></path></svg>
        </div>
        <div class="sd-kpi-content">
          <div class="sd-kpi-val">
            {{ $rank ?? 'N/A' }}@if($rank)<span style="font-size:16px;color:#94a3b8;margin-left:2px;">/{{ $totalInClass }}</span>@endif
          </div>
          <div class="sd-kpi-lbl">Class Rank</div>
        </div>
      </div>

      <div class="sd-kpi-card kpi-orange">
        <div class="sd-kpi-icon">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.5 3.5 6.5 1 1.5 1 4.5-.5 6-2.75 2.67-3.5 3-6.5 3-3 0-4.5-1.5-4.5-4.5 0-1.25.75-2.25 2-3 .5 1 1 2 1.5 3z"></path></svg>
        </div>
        <div class="sd-kpi-content">
          <div class="sd-kpi-val">{{ $streak }}</div>
          <div class="sd-kpi-lbl">Activity Score</div>
        </div>
      </div>
    </div>

    {{-- ── Progress Chart + Rank ── --}}
    <div class="sd-row">
      <div class="sd-card">
        <div class="sd-card-title"> Performance Chart</div>
        @if($marks->count() > 0)
          <canvas id="sdChart" style="max-height:260px;"></canvas>
        @else
          <div class="sd-empty">
            <div class="sd-empty-icon">📉</div>
            <div>No marks recorded yet.</div>
          </div>
        @endif
      </div>
      <div class="sd-card sd-rank-card">
        <div class="sd-card-title" style="justify-content:center; margin-bottom:20px;">🏆 Class Leaderboard</div>
        @if($rank)
          @php 
            $percentile = $totalInClass > 1 ? round((($totalInClass - $rank) / ($totalInClass - 1)) * 100) : 100;
          @endphp
          <div class="sd-rank-visual">
            <div class="sd-rank-aura"></div>
            <div class="sd-rank-big">{{ $rank }}</div>
            <div class="sd-rank-medal">
              @if($rank === 1) 🥇
              @elseif($rank === 2) 🥈
              @elseif($rank === 3) 🥉
              @else ⭐️
              @endif
            </div>
          </div>
          <div class="sd-rank-of">out of {{ $totalInClass }} students</div>
          <div class="sd-rank-percentile">Top {{ 101 - $percentile }}% of Class</div>
          
          <div class="sd-rank-label">
            @if($rank === 1) 
              <span style="color:#f59e0b;">You are the Class Topper!</span><br/>Absolutely incredible work.
            @elseif($rank <= 3) 
              Great Work! You're in the <span style="color:#6C5CE7;">Top 3</span> of your class.
            @elseif($averagePercentage >= 75) 
              You're doing excellent! Keep up the consistency.
            @else 
              Focus on weak subjects to climb the leaderboard.
            @endif
          </div>
        @else
          <div class="sd-rank-visual">
             <div class="sd-rank-aura" style="background:#e2e8f0;"></div>
             <div class="sd-rank-big" style="font-size:40px;color:#94a3b8;">N/A</div>
          </div>
          <div class="sd-rank-of">No ranking data available</div>
        @endif
      </div>
    </div>

    {{-- ── Subject Insights + Recommendations ── --}}
    <div class="sd-row">
      <div class="sd-card">
        <div class="sd-card-title">💡 Recommended Actions</div>
        <p class="sd-card-sub" style="font-size:12px; color:#64748b; margin-top:-10px; margin-bottom:20px;">Personalized suggestions based on your performance</p>
        @forelse($recommendations as $r)
          <div class="sd-rec-item">
            <div class="sd-rec-icon {{ $r['pct'] < 40 ? 'sd-rec-urgent' : 'sd-rec-warn' }}">
              {{ $r['pct'] < 40 ? '🚨' : '⚠️' }}
            </div>
            <div>
              <div class="sd-rec-title">{{ $r['subject'] }} — {{ $r['pct'] }}%</div>
              <div class="sd-rec-tip">{{ $r['tip'] }}</div>
            </div>
          </div>
        @empty
          <div class="sd-empty">
            <div class="sd-empty-icon">✅</div>
            <div style="color:#16a34a;font-weight:600;">All subjects passing!</div>
            <div style="font-size:12px;margin-top:4px;">Keep up the great work.</div>
          </div>
        @endforelse
      </div>

      <div class="sd-card">
         <div class="sd-card-title">📈 Performance Trend</div>
         <p style="font-size:13px; color:#64748b; line-height:1.6;">
            Your overall average is <strong>{{ $averagePercentage }}%</strong>. 
            @if($averagePercentage >= 75)
              You are performing exceptionally well across most subjects.
            @elseif($averagePercentage >= 40)
              You're on the right track, but focus on the "Recommended Actions" to boost your scores.
            @else
              We've identified some areas for improvement. Check with your teachers for additional support.
            @endif
         </p>
         <div style="margin-top:20px; padding:15px; background:#f8fafc; border-radius:12px; border:1px solid #f1f5f9;">
            <div style="font-size:11px; text-transform:uppercase; color:#94a3b8; font-weight:700; letter-spacing:0.05em; margin-bottom:8px;">System Insight</div>
            <div style="font-size:13px; color:#1e293b; font-weight:600;">
               {{ $performanceLabel === 'Excellent' ? '🏆 Maintain this streak to stay at the top!' : '🎯 Target 5% improvement in your weakest subject.' }}
            </div>
         </div>
      </div>
    </div>

    {{-- ── Assigned Remedial Tasks ── --}}
    @if(count($assignedTasks) > 0)
    <div class="sd-row full">
      <div class="sd-card">
        <div class="sd-card-title" style="display:flex; justify-content:space-between; align-items:center;">
          <span>📋 My Assigned Tasks</span>
          <span style="font-size:12px; background:#fff1f2; color:#be123c; padding:4px 10px; border-radius:100px;">{{ $assignedTasks->where('status', '!=', 'completed')->count() }} Pending</span>
        </div>
        <div style="overflow-x:auto;">
          <table class="sd-table">
            <thead>
              <tr>
                <th>Task / Intervention</th>
                <th>Type</th>
                <th>Status</th>
                <th>Scheduled Date</th>
              </tr>
            </thead>
            <tbody>
              @foreach($assignedTasks as $task)
                <tr>
                  <td>
                    <div style="font-weight:600; color:#111827;">{{ $task->title }}</div>
                    @if($task->description)
                      <div style="font-size:12px; color:#64748b; margin-top:2px; max-width:300px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $task->description }}</div>
                    @endif
                  </td>
                  <td>
                    <span style="background:#f1f5f9; color:#475569; padding:4px 8px; border-radius:6px; font-size:12px; font-weight:600;">
                      {{ ucwords(str_replace('_', ' ', $task->action_type)) }}
                    </span>
                  </td>
                  <td>
                    @if($task->status === 'completed')
                      <span class="sd-status sd-status-good">Completed</span>
                    @elseif($task->status === 'in_progress')
                      <span class="sd-status sd-status-warn" style="background:#dbeafe; color:#1d4ed8;">In Progress</span>
                    @elseif($task->status === 'pending')
                      <span class="sd-status sd-status-warn">Pending</span>
                    @else
                      <span class="sd-status sd-status-none">Cancelled</span>
                    @endif
                  </td>
                  <td style="color:#64748b; font-size:13px;">
                    {{ $task->scheduled_date ? $task->scheduled_date->format('M d, Y') : 'Not scheduled' }}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    @endif

    {{-- ── Marks Table + Profile ── --}}
    <div class="sd-row">
      <div class="sd-card">
        <div class="sd-card-title">Recent Exam Marks</div>
        <div style="overflow-x:auto;">
          <table class="sd-table">
            <thead>
              <tr>
                <th>Subject</th>
                <th>Term / Exam</th>
                <th>Score</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($marks->take(10) as $mark)
                @php $pct = $mark->max_marks > 0 ? ($mark->marks_obtained / $mark->max_marks) * 100 : 0; @endphp
                <tr>
                  <td style="font-weight:600;">{{ $mark->subject->name ?? 'Unknown' }}</td>
                  <td style="color:#64748b;">{{ $mark->term }} / {{ $mark->exam_type }}</td>
                  <td><strong>{{ $mark->marks_obtained }}</strong><span style="color:#94a3b8;"> /
                      {{ $mark->max_marks }}</span></td>
                  <td>
                    @if($pct >= 60)<span class="sd-status sd-status-good">Passing</span>
                    @elseif($pct >= 40)<span class="sd-status sd-status-warn">Borderline</span>
                    @else<span class="sd-status sd-status-bad">Failing</span>@endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="sd-empty">
                    <div class="sd-empty-icon">📄</div>
                    <div>No marks recorded yet.</div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <div class="sd-card">
        <div class="sd-card-title"> My Profile</div>
        <div class="sd-info-row"><span class="sd-info-label">Full Name</span><span
            class="sd-info-val">{{ $studentProfile->name }}</span></div>
        <div class="sd-info-row"><span class="sd-info-label">Email</span><span class="sd-info-val"
            style="font-size:12px;">{{ $studentProfile->email }}</span></div>
        <div class="sd-info-row"><span class="sd-info-label">Class & Section</span><span
            class="sd-info-val">{{ $studentProfile->class }} {{ $studentProfile->section }}</span></div>
        <div class="sd-info-row"><span class="sd-info-label">Roll Number</span><span
            class="sd-info-val">{{ $studentProfile->roll_no ?? 'Not Assigned' }}</span></div>
        <div class="sd-info-row"><span class="sd-info-label">Phone</span><span
            class="sd-info-val">{{ $studentProfile->phone ?? '—' }}</span></div>
        <div class="sd-info-row"><span class="sd-info-label">Guardian</span><span
            class="sd-info-val">{{ $studentProfile->guardian_name ?? '—' }}</span></div>
        <div class="sd-info-row"><span class="sd-info-label">Status</span>
          <span
            class="sd-status {{ $studentProfile->is_active ? 'sd-status-good' : 'sd-status-bad' }}">{{ $studentProfile->is_active ? 'Active' : 'Inactive' }}</span>
        </div>
      </div>
    </div>

    {{-- ── Achievements ── --}}
    @if(count($badges) > 0)
      <div class="sd-card">
        <div class="sd-card-title"> Achievements & Badges</div>
        <div class="sd-badges">
          @foreach($badges as $b)
            <div class="sd-badge-item {{ $b['theme'] ?? 'badge-gold' }}" title="{{ $b['label'] }}">
              <div class="sd-badge-visual">
                <div class="sd-badge-glow"></div>
                <div class="sd-badge-inner">
                  {{ $b['icon'] }}
                </div>
              </div>
              <div class="sd-badge-label">{{ $b['label'] }}</div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

  </div>

  @push('scripts')
    <script>
      @if($marks->count() > 0)
        (function () {
          const ctx = document.getElementById('sdChart');
          if (!ctx) return;
          new Chart(ctx, {
            type: 'bar',
            data: {
              labels: {!! json_encode($chartLabels) !!},
              datasets: [{
                label: 'Score (%)',
                data: {!! json_encode($chartData) !!},
                backgroundColor: {!! json_encode($chartColors->map(fn($c) => $c . '44')->values()) !!},
                borderColor: {!! json_encode($chartColors) !!},
                borderWidth: 2,
                borderRadius: { topLeft: 12, topRight: 12, bottomLeft: 0, bottomRight: 0 },
                borderSkipped: false,
                hoverBackgroundColor: {!! json_encode($chartColors) !!},
              }]
            },
            options: {
              indexAxis: 'x',
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: { display: false },
                tooltip: {
                  backgroundColor: '#0f172a',
                  padding: 12,
                  titleFont: { size: 14, weight: 'bold' },
                  callbacks: { label: (ctx) => `Score: ${ctx.raw}%` }
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  max: 100,
                  grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                  ticks: { callback: v => v + '%', font: { size: 11 } }
                },
                x: {
                  grid: { display: false, drawBorder: false },
                  ticks: { font: { weight: '600', size: 12 }, color: '#1e293b' }
                }
              },
              animation: { duration: 2000, easing: 'easeOutQuart' }
            }
          });
        })();
      @endif
    </script>
  @endpush

</x-app-layout>