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
        grid-template-columns: minmax(0, 1fr) 340px;
        gap: 24px;
      }

      .sd-row > .sd-card { min-width: 0; }

      .sd-row.full {
        grid-template-columns: minmax(0, 1fr);
      }

      @media(max-width:900px) {
        .sd-row {
          grid-template-columns: minmax(0, 1fr);
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

      .sd-performance-card { border:1px solid #e8eaf4; border-radius:22px; box-shadow:0 9px 30px rgba(38,48,95,.06); overflow:hidden; }
      .sd-performance-head { display:flex; align-items:flex-start; justify-content:space-between; gap:14px; margin-bottom:18px; }
      .sd-performance-head .sd-card-title { margin:0 0 5px; font-size:17px; }
      .sd-performance-subtitle { margin:0; color:#748199; font-size:12px; line-height:1.45; }
      .sd-performance-link { flex:none; color:#6254dd; background:#f3f1ff; padding:8px 11px; border-radius:10px; font-size:11px; font-weight:750; text-decoration:none; }
      .sd-performance-link:hover { background:#e8e4ff; }
      .sd-performance-chart { position:relative; height:250px; }
      .sd-performance-chart canvas { width:100% !important; height:100% !important; }
      .sd-performance-empty { display:grid; place-items:center; min-height:220px; color:#718096; text-align:center; font-size:13px; }

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

      /* Achievement collection */
      .sd-achv-head { display:flex; justify-content:space-between; align-items:flex-start; gap:16px; margin-bottom:22px; }
      .sd-achv-head .sd-card-title { margin-bottom:5px; }
      .sd-achv-subtitle { margin:0; color:#64748b; font-size:13px; line-height:1.5; }
      .sd-achv-summary { display:flex; flex-wrap:wrap; gap:8px; justify-content:flex-end; }
      .sd-achv-summary span { white-space:nowrap; border:1px solid #e4e8f2; border-radius:999px; background:#f8faff; color:#334155; padding:7px 11px; font-size:12px; font-weight:700; }
      .sd-achv-grid { display:grid; grid-template-columns:repeat(4, minmax(0, 1fr)); gap:14px; }
      .sd-achv-card { --achv-accent:#64748b; --achv-dark:#334155; display:flex; flex-direction:column; align-items:center; min-width:0; min-height:218px; padding:19px 12px 16px; border:1px solid #e8eaf2; border-radius:18px; background:linear-gradient(155deg,#fff 0%,#fbfcff 76%,#f4f7fc 100%); color:#17233d; text-align:center; cursor:pointer; position:relative; overflow:hidden; box-shadow:0 7px 25px rgba(31,45,77,.045); transition:transform .24s ease, box-shadow .24s ease, border-color .24s ease; font:inherit; }
      .sd-achv-card:hover { transform:translateY(-5px) rotateX(3deg); border-color:var(--achv-accent); box-shadow:0 15px 32px rgba(31,45,77,.13), 0 0 22px color-mix(in srgb, var(--achv-accent) 20%, transparent); }
      .sd-achv-card:focus-visible, .sd-achv-close:focus-visible { outline:3px solid #6366f1; outline-offset:3px; }
      .sd-achv-card[data-tier="common"] { --achv-accent:#38bdf8; --achv-dark:#2563a5; }
      .sd-achv-card[data-tier="rare"] { --achv-accent:#45d4c3; --achv-dark:#077e87; }
      .sd-achv-card[data-tier="epic"] { --achv-accent:#ab8afb; --achv-dark:#6943bb; }
      .sd-achv-card[data-tier="legendary"] { --achv-accent:#ffc467; --achv-dark:#c26619; }
      .sd-achv-card::before { content:""; position:absolute; width:120px; height:120px; top:-64px; right:-55px; border-radius:50%; background:var(--achv-accent); filter:blur(43px); opacity:.16; pointer-events:none; }
      .sd-achv-visual { width:78px; height:88px; display:grid; place-items:center; position:relative; margin:1px 0 13px; filter:drop-shadow(0 9px 8px color-mix(in srgb, var(--achv-dark) 25%, transparent)); transition:transform .25s ease; }
      .sd-achv-card:hover .sd-achv-visual { transform:rotate(-6deg) scale(1.08); }
      .sd-achv-shield { display:grid; place-items:center; width:100%; height:100%; clip-path:polygon(50% 0,91% 18%,91% 65%,50% 100%,9% 65%,9% 18%); background:linear-gradient(132deg,#fff 1%,var(--achv-accent) 26%,var(--achv-dark) 72%,#fff 108%); position:relative; }
      .sd-achv-shield::before { content:""; position:absolute; inset:5px; clip-path:inherit; background:linear-gradient(145deg,rgba(255,255,255,.85),rgba(255,255,255,.25) 35%,rgba(13,24,52,.16) 78%); }
      .sd-achv-shield::after { content:""; position:absolute; width:22px; height:130px; left:-35px; top:-20px; background:rgba(255,255,255,.55); transform:rotate(30deg); filter:blur(7px); }
      .sd-achv-card:hover .sd-achv-shield::after { animation:sd-achv-shimmer .8s ease forwards; }
      @keyframes sd-achv-shimmer { to { left:110px; } }
      .sd-achv-glyph { position:relative; z-index:1; font-size:32px; font-weight:900; line-height:1; color:#fff; text-shadow:0 2px 5px rgba(16,29,57,.35); }
      .sd-achv-lock { position:absolute; right:-4px; bottom:0; z-index:2; width:26px; height:26px; display:grid; place-items:center; border-radius:8px; background:#fff; border:1px solid #d9dfe9; box-shadow:0 3px 8px rgba(32,43,68,.18); font-size:14px; }
      .sd-achv-card.is-locked .sd-achv-visual { filter:grayscale(1) opacity(.59); }
      .sd-achv-card.is-locked .sd-achv-lock { filter:none; }
      .sd-achv-tier { color:var(--achv-dark); background:color-mix(in srgb, var(--achv-accent) 16%, white); border:1px solid color-mix(in srgb, var(--achv-accent) 30%, white); border-radius:999px; padding:3px 9px; text-transform:uppercase; letter-spacing:.09em; font-size:9px; font-weight:800; }
      .sd-achv-name { display:block; margin-top:9px; font-size:13px; font-weight:800; line-height:1.3; }
      .sd-achv-state { display:block; margin-top:5px; color:#66758d; font-size:11px; line-height:1.35; }
      .sd-achv-progress { width:100%; max-width:155px; height:5px; margin-top:auto; background:#e9edf4; border-radius:99px; overflow:hidden; }
      .sd-achv-progress span { display:block; height:100%; width:var(--progress); background:linear-gradient(90deg,var(--achv-dark),var(--achv-accent)); border-radius:inherit; }
      .sd-achv-card.is-unlocked .sd-achv-progress { background:color-mix(in srgb, var(--achv-accent) 25%, white); }
      .sd-achv-dialog { position:fixed; inset:0; margin:auto; width:min(440px,calc(100% - 32px)); max-height:calc(100dvh - 32px); overflow:auto; border:1px solid #e5e9f2; border-radius:22px; padding:27px; color:#1e293b; background:#fff; box-shadow:0 28px 80px rgba(14,27,54,.24); }
      .sd-achv-dialog::backdrop { background:rgba(12,21,42,.55); backdrop-filter:blur(5px); }
      .sd-achv-dialog-top { display:flex; justify-content:space-between; align-items:flex-start; gap:12px; }
      .sd-achv-dialog h2 { margin:4px 0 10px; font-size:23px; line-height:1.2; }
      .sd-achv-dialog p { margin:0 0 16px; color:#58677f; line-height:1.55; }
      .sd-achv-close { border:0; background:#f1f4f9; color:#334155; width:32px; height:32px; border-radius:10px; cursor:pointer; font-size:21px; line-height:1; }
      .sd-achv-detail { display:flex; justify-content:space-between; gap:14px; padding:10px 0; border-top:1px solid #edf0f5; font-size:13px; }
      .sd-achv-detail span { color:#64748b; }
      .sd-achv-detail strong { text-align:right; }
      @media (max-width:900px) { .sd-achv-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
      @media (max-width:560px) { .sd-achv-head { flex-direction:column; } .sd-achv-summary { justify-content:flex-start; } .sd-achv-grid { gap:9px; } .sd-achv-card { min-height:202px; padding:15px 7px 13px; } .sd-achv-visual { transform:scale(.85); margin:-4px 0 3px; } }
      @media (prefers-reduced-motion:reduce) { .sd-achv-card, .sd-achv-visual { transition:none; } .sd-achv-card:hover { transform:none; } .sd-achv-card:hover .sd-achv-shield::after { animation:none; } }

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

    {{-- ── Today's Classes ── --}}
    <div class="sd-card">
      <div class="sd-card-title">📅 Today's Classes & Schedule</div>
      @if($todayClasses->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
          @foreach($todayClasses as $slot)
            <div style="padding: 16px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; display:flex; flex-direction:column; justify-content:space-between;">
              <div>
                <span style="background: rgba(108, 92, 231, 0.08); color: #6C5CE7; font-weight:700; font-size:10px; padding: 2px 6px; border-radius: 100px;">
                  Period {{ $slot->period_number }}
                </span>
                <div style="font-weight:700; color:#0f172a; font-size:14px; margin-top:8px;">{{ $slot->subject->name }}</div>
                <div style="font-size:12px; color:#64748b; margin-top:2px;">Teacher: {{ $slot->teacher->name }}</div>
              </div>
              <div style="font-size:11px; font-weight:600; color:#94a3b8; margin-top:12px; border-top:1px solid #e2e8f0; padding-top:6px; display:flex; align-items:center; gap:4px;">
                🕒 {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="sd-empty" style="padding: 24px;">
          <div class="sd-empty-icon" style="font-size:32px;">🌴</div>
          <div style="font-size:14px; font-weight:650; color:#64748b;">No classes scheduled for today. Enjoy your day!</div>
        </div>
      @endif
    </div>

    {{-- ── Progress Chart + Rank ── --}}
    <div class="sd-row">
      <div class="sd-card sd-performance-card">
        <div class="sd-performance-head">
          <div>
            <h2 class="sd-card-title">Subject performance</h2>
            <p class="sd-performance-subtitle">Your average score by subject · hover for grade details</p>
          </div>
          <a class="sd-performance-link" href="{{ route('student.progress') }}">View progress ↗</a>
        </div>
        @if($marks->count() > 0)
          <div class="sd-performance-chart">
            <canvas id="sdChart" role="img" aria-label="Subject performance chart. Scores: {{ $subjectBreakdown->map(fn($s) => $s['name'].': '.$s['pct'].'%')->implode(', ') }}"></canvas>
          </div>
        @else
          <div class="sd-performance-empty">Your chart will appear after your first exam result.</div>
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
    <section class="sd-card" aria-labelledby="sd-achv-heading">
      <div class="sd-achv-head">
        <div>
          <h2 class="sd-card-title" id="sd-achv-heading">Achievements & Badges</h2>
          <p class="sd-achv-subtitle">Your milestones, challenges, and next wins.</p>
        </div>
        <div class="sd-achv-summary" aria-label="Achievement summary">
          <span>{{ collect($badges)->where('unlocked', true)->count() }} / {{ count($badges) }} unlocked</span>
          <span>{{ number_format((int) ($studentProfile->xp_points ?? 0)) }} total XP</span>
        </div>
      </div>
      <div class="sd-achv-grid">
        @foreach($badges as $b)
          <button type="button" class="sd-achv-card {{ $b['unlocked'] ? 'is-unlocked' : 'is-locked' }}"
            data-tier="{{ $b['tier'] }}" data-name="{{ $b['label'] }}"
            data-description="{{ $b['description'] }}" data-status="{{ $b['unlocked'] ? 'Unlocked' : 'In progress' }}"
            data-progress="{{ $b['progress'] }}" data-earned="{{ $b['earned_at'] ?? '' }}"
            aria-label="{{ $b['label'] }}, {{ $b['unlocked'] ? 'unlocked' : 'locked' }}. View details">
            <span class="sd-achv-visual" aria-hidden="true">
              <span class="sd-achv-shield"><span class="sd-achv-glyph">{{ $b['glyph'] }}</span></span>
              @unless($b['unlocked'])<span class="sd-achv-lock">🔒</span>@endunless
            </span>
            <span class="sd-achv-tier">{{ $b['tier'] }}</span>
            <span class="sd-achv-name">{{ $b['label'] }}</span>
            <span class="sd-achv-state">{{ $b['unlocked'] ? 'Unlocked' : $b['progress'] }}</span>
            <span class="sd-achv-progress" role="progressbar" aria-label="{{ $b['label'] }} progress"
              aria-valuemin="0" aria-valuemax="{{ $b['target'] }}" aria-valuenow="{{ $b['current'] }}"
              style="--progress:{{ $b['progress_percent'] }}%"><span></span></span>
          </button>
        @endforeach
      </div>
    </section>

    <dialog class="sd-achv-dialog" id="sd-achv-dialog" aria-labelledby="sd-achv-dialog-title">
      <div class="sd-achv-dialog-top">
        <span class="sd-achv-tier" id="sd-achv-dialog-tier"></span>
        <button type="button" class="sd-achv-close" id="sd-achv-dialog-close" aria-label="Close badge details">&times;</button>
      </div>
      <h2 id="sd-achv-dialog-title"></h2>
      <p id="sd-achv-dialog-description"></p>
      <div class="sd-achv-detail"><span>Status</span><strong id="sd-achv-dialog-status"></strong></div>
      <div class="sd-achv-detail"><span>Progress</span><strong id="sd-achv-dialog-progress"></strong></div>
      <div class="sd-achv-detail"><span>Date earned</span><strong id="sd-achv-dialog-earned"></strong></div>
      <div class="sd-achv-detail"><span>XP awarded</span><strong>Not tracked yet</strong></div>
    </dialog>

  </div>

  @push('scripts')
    <script>
      (() => {
        const dialog = document.getElementById('sd-achv-dialog');
        const close = document.getElementById('sd-achv-dialog-close');
        if (!dialog || !close) return;

        let trigger = null;
        document.querySelectorAll('.sd-achv-card').forEach(card => {
          card.addEventListener('click', () => {
            trigger = card;
            const fields = {
              tier: card.dataset.tier,
              title: card.dataset.name,
              description: card.dataset.description,
              status: card.dataset.status,
              progress: card.dataset.progress,
              earned: card.dataset.earned || (card.dataset.status === 'Unlocked' ? 'Not recorded' : 'Not earned yet'),
            };
            document.getElementById('sd-achv-dialog-tier').textContent = fields.tier;
            document.getElementById('sd-achv-dialog-title').textContent = fields.title;
            document.getElementById('sd-achv-dialog-description').textContent = fields.description;
            document.getElementById('sd-achv-dialog-status').textContent = fields.status;
            document.getElementById('sd-achv-dialog-progress').textContent = fields.progress;
            document.getElementById('sd-achv-dialog-earned').textContent = fields.earned;
            dialog.showModal();
            close.focus();
          });
        });
        close.addEventListener('click', () => dialog.close());
        dialog.addEventListener('click', event => { if (event.target === dialog) dialog.close(); });
        dialog.addEventListener('close', () => trigger?.focus());
      })();
    </script>
    <script src="{{ asset('js/student-performance-chart.js') }}"></script>
    @if($marks->count() > 0)
      <script>
        window.createStudentPerformanceChart(
          document.getElementById('sdChart'),
          {{ Illuminate\Support\Js::from($chartLabels) }},
          {{ Illuminate\Support\Js::from($chartData) }}
        );
      </script>
    @endif
  @endpush

</x-app-layout>
