<x-app-layout>
    <x-slot name="title">Admin Dashboard</x-slot>

    @push('styles')
    <style>
        /* ── Page Header ── */
        .admin-header {
            background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);
            border: 1px solid rgba(0,0,0,0.06);
            border-radius: 20px;
            padding: 28px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            position: relative;
            overflow: hidden;
            flex-wrap: wrap;
            gap: 16px;
        }
        .admin-header::after {
            content: '';
            position: absolute; right: 0; top: 0;
            width: 350px; height: 100%;
            background: radial-gradient(circle at top right, rgba(108,92,231,0.06), transparent 70%);
            pointer-events: none;
        }
        .admin-title { font-family:'Poppins',sans-serif; font-size: 26px; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; margin: 0 0 4px; }
        .admin-subtitle { font-size: 14px; color: #64748b; margin: 0; }

        /* ── Action Buttons ── */
        .btn-solid-primary {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #6C5CE7, #8B5CF6);
            color: #fff; border-radius: 12px; font-size: 13px; font-weight: 700;
            text-decoration: none; border: none; cursor: pointer;
            box-shadow: 0 4px 14px rgba(108,92,231,0.28);
            transition: all 0.22s cubic-bezier(.4,0,.2,1);
        }
        .btn-solid-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(108,92,231,0.35); color:#fff; }
        .btn-solid-dark {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 20px;
            background: #0f172a; color: #fff;
            border-radius: 12px; font-size: 13px; font-weight: 700;
            text-decoration: none; border: none; cursor: pointer;
            box-shadow: 0 4px 12px rgba(15,23,42,0.18);
            transition: all 0.22s cubic-bezier(.4,0,.2,1);
        }
        .btn-solid-dark:hover { background: #1e293b; transform: translateY(-2px); box-shadow: 0 8px 18px rgba(15,23,42,0.25); color:#fff; }
        .btn-sm { padding: 7px 14px; font-size: 12px; border-radius: 9px; }

        /* ----- Card shell ----- */
        .inv-card {
            display: flex;
            align-items: stretch;
            border-radius: 18px;
            overflow: hidden;
            border: 1.5px solid #e8e3ff;
            box-shadow: 0 2px 28px rgba(99, 77, 220, 0.09), 0 1px 4px rgba(99, 77, 220, 0.05);
            background: #ffffff;
            margin-bottom: 1.5rem;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* ----- Left violet stripe ----- */
        .inv-stripe {
            width: 200px;
            flex-shrink: 0;
            background: linear-gradient(155deg, #5b38c0 0%, #7c5af6 55%, #a78bfa 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2rem 1.5rem;
            position: relative;
            overflow: hidden;
        }
        .inv-stripe-circles::before,
        .inv-stripe-circles::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.07);
        }
        .inv-stripe-circles::before { width: 130px; height: 130px; bottom: -35px; right: -35px; }
        .inv-stripe-circles::after  { width: 75px;  height: 75px;  top: -20px;    left: -18px; }

        /* Status pill */
        .inv-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.28);
            border-radius: 100px;
            padding: 3px 11px;
            margin-bottom: 14px;
            width: fit-content;
            position: relative;
            z-index: 1;
        }
        .inv-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #4ade80;
            box-shadow: 0 0 0 3px rgba(74, 222, 128, 0.25);
            flex-shrink: 0;
        }
        .inv-pill-text {
            font-size: 10.5px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.92);
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        /* Stripe heading */
        .inv-stripe-title {
            font-size: 22px;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.2;
            letter-spacing: -0.4px;
            margin: 0 0 6px;
            position: relative;
            z-index: 1;
        }
        .inv-stripe-school {
            font-size: 11.5px;
            color: rgba(255, 255, 255, 0.68);
            margin: 0;
            position: relative;
            z-index: 1;
            line-height: 1.4;
        }

        /* ----- Middle content ----- */
        .inv-mid {
            flex: 1;
            padding: 1.75rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 16px;
            border-right: 1.5px solid #ede9fe;
            min-width: 0;
        }
        .inv-desc {
            font-size: 13.5px;
            color: #6b7280;
            line-height: 1.7;
            margin: 0;
        }
        .inv-desc strong {
            color: #3d2fa0;
            font-weight: 600;
        }

        /* Action row */
        .inv-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* Link display box */
        .inv-link-box {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8f7ff;
            border: 1.5px solid #ddd6fe;
            border-radius: 10px;
            padding: 9px 13px;
            min-width: 200px;
            max-width: 340px;
            flex: 1;
            overflow: hidden;
        }
        .inv-link-icon {
            width: 14px;
            height: 14px;
            color: #a78bfa;
            flex-shrink: 0;
        }
        .inv-link-val {
            font-size: 12px;
            color: #4c3d9e;
            font-weight: 500;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            flex: 1;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        }

        /* Primary CTA */
        .inv-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: linear-gradient(135deg, #6d4ac7 0%, #8b5cf6 100%);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
            letter-spacing: 0.01em;
            font-family: inherit;
            box-shadow: 0 3px 14px rgba(109, 74, 199, 0.32);
            transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.2s ease;
        }
        .inv-btn-primary svg { width: 14px; height: 14px; flex-shrink: 0; }
        .inv-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(109, 74, 199, 0.40);
        }
        .inv-btn-primary:active { transform: scale(0.97); box-shadow: none; }

        /* Secondary CTA */
        .inv-btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #ffffff;
            color: #6d4ac7;
            border: 1.5px solid #ddd6fe;
            border-radius: 10px;
            padding: 9px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            font-family: inherit;
            transition: background 0.15s ease, border-color 0.15s ease, transform 0.12s ease;
        }
        .inv-btn-secondary svg { width: 14px; height: 14px; flex-shrink: 0; }
        .inv-btn-secondary:hover { background: #f5f3ff; border-color: #c4b5fd; }
        .inv-btn-secondary:active { transform: scale(0.97); }

        /* ----- Right QR panel ----- */
        .inv-qr-panel {
            width: 200px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 1.5rem 1.25rem;
            background: #fafafa;
        }
        .inv-qr-frame {
            background: #ffffff;
            border: 1.5px solid #ede9fe;
            border-radius: 12px;
            padding: 10px;
            line-height: 0;
        }
        .inv-qr-frame img,
        .inv-qr-frame canvas {
            display: block;
            border-radius: 4px;
            width: 130px !important;
            height: 130px !important;
        }
        .inv-scan-label {
            font-size: 10.5px;
            color: #9ca3af;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            text-align: center;
        }
        .inv-scan-code {
            font-size: 10px;
            color: #c4b5fd;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-align: center;
            font-family: monospace;
        }

        /* ----- Responsive ----- */
        @media (max-width: 768px) {
            .inv-card { flex-direction: column; }
            .inv-stripe { width: 100%; flex-direction: row; align-items: center; gap: 16px; padding: 1.25rem 1.5rem; }
            .inv-stripe-title { font-size: 18px; }
            .inv-mid { border-right: none; border-top: 1.5px solid #ede9fe; }
            .inv-qr-panel { width: 100%; flex-direction: row; justify-content: center; padding: 1rem 1.5rem; border-top: 1.5px solid #ede9fe; }
        }

        /* ── KPI Grid ── */
        .kpi-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 20px; margin-bottom: 28px;
        }
        .kpi-box {
            background: #fff; border-radius: 18px; padding: 22px;
            border: 1px solid rgba(0,0,0,0.04);
            box-shadow: 0 4px 16px rgba(0,0,0,0.03);
            display: flex; align-items: center; gap: 16px;
            transition: all 0.25s cubic-bezier(.4,0,.2,1);
            position: relative; overflow: hidden;
        }
        .kpi-box::after {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0;
            height: 3px; border-radius: 0 0 18px 18px; opacity: 0;
            transition: opacity 0.25s;
        }
        .kpi-box:hover { transform: translateY(-4px); box-shadow: 0 14px 32px rgba(0,0,0,0.07); }
        .kpi-box:hover::after { opacity: 1; }
        .kpi-primary::after  { background: #6366f1; }
        .kpi-success::after  { background: #10b981; }
        .kpi-warning::after  { background: #f59e0b; }
        .kpi-info::after     { background: #3b82f6; }
        .kpi-danger::after   { background: #ef4444; }
        .kpi-icon-wrap {
            width: 50px; height: 50px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .kpi-icon-wrap svg { width: 22px; height: 22px; }
        .kpi-value { font-family:'Poppins',sans-serif; font-size: 28px; font-weight: 800; color: #0f172a; line-height: 1; }
        .kpi-label { font-size: 12px; font-weight: 600; color: #64748b; margin-top: 5px; text-transform: uppercase; letter-spacing: 0.06em; }
        .kpi-primary .kpi-icon-wrap { background: #eef2ff; color: #6366f1; }
        .kpi-success .kpi-icon-wrap { background: #ecfdf5; color: #10b981; }
        .kpi-warning .kpi-icon-wrap { background: #fffbeb; color: #f59e0b; }
        .kpi-info    .kpi-icon-wrap { background: #eff6ff; color: #3b82f6; }
        .kpi-danger  .kpi-icon-wrap { background: #fef2f2; color: #ef4444; }

        /* ── Layout ── */
        .main-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
        @media(max-width: 1024px) { .main-grid { grid-template-columns: 1fr; } }

        /* ── Premium Cards ── */
        .premium-card {
            background: #fff; border-radius: 20px; padding: 24px;
            border: 1px solid rgba(0,0,0,0.04);
            box-shadow: 0 4px 16px rgba(0,0,0,0.03);
        }
        .card-header {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 20px;
        }
        .card-title { font-family:'Poppins',sans-serif; font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 3px; }
        .card-subtitle { font-size: 12px; color: #64748b; }

        /* ── Quick Actions ── */
        .btn-quick-action {
            display: flex; align-items: center; gap: 12px;
            padding: 14px 16px;
            background: #f8fafc; border: 1.5px solid #e2e8f0;
            border-radius: 14px; color: #1e293b;
            font-weight: 600; font-size: 13px;
            transition: all 0.22s cubic-bezier(.4,0,.2,1);
            text-decoration: none;
        }
        .btn-quick-action:hover {
            background: linear-gradient(135deg, #6C5CE7, #8B5CF6);
            border-color: transparent; color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(108,92,231,0.22);
        }
        .btn-quick-action:hover svg { stroke: #fff; }
        .qa-icon {
            width: 36px; height: 36px; border-radius: 10px; background: #fff;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.06);
            transition: background 0.22s;
        }
        .btn-quick-action:hover .qa-icon { background: rgba(255,255,255,0.2); box-shadow: none; }

        /* ── Alerts ── */
        .alert-item {
            background: #fffbeb; padding: 14px 16px; border-radius: 12px;
            border-left: 3px solid #f59e0b; margin-bottom: 10px;
            display: flex; justify-content: space-between; align-items: center;
            gap: 12px; transition: all 0.18s;
        }
        .alert-item:hover { background: #fef3c7; transform: translateX(2px); }
        .alert-item:last-child { margin-bottom: 0; }
        .alert-title { font-weight: 700; font-size: 13px; color: #92400e; }
        .alert-desc { font-size: 11.5px; color: #b45309; margin-top: 2px; }
        .btn-review {
            font-size: 12px; font-weight: 700; color: #fff; background: #d97706;
            padding: 5px 12px; border-radius: 7px; text-decoration: none;
            transition: all 0.18s; flex-shrink: 0;
        }
        .btn-review:hover { background: #b45309; transform: translateY(-1px); }

        /* ── Table ── */
        .premium-table { width: 100%; border-collapse: collapse; }
        .premium-table th {
            text-align: left; padding: 12px 16px;
            color: #94a3b8; font-size: 11px; text-transform: uppercase;
            font-weight: 700; letter-spacing: 0.05em;
            border-bottom: 1px solid #f1f5f9;
        }
        .premium-table td { padding: 14px 16px; border-bottom: 1px solid #f8fafc; vertical-align: middle; }
        .premium-table tr:last-child td { border-bottom: none; }
        .premium-table tbody tr:hover { background: rgba(108,92,231,0.02); }
        .student-avatar {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, #6C5CE7, #8B5CF6);
            color: white; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 13px; flex-shrink: 0;
        }
        .status-badge {
            display: inline-flex; padding: 4px 10px; border-radius: 100px;
            font-size: 11.5px; font-weight: 700;
        }
        .status-danger  { background: #fef2f2; color: #ef4444; }
        .status-success { background: #ecfdf5; color: #10b981; }
        .status-warning { background: #fffbeb; color: #f59e0b; }
        .status-neutral { background: #f8fafc; color: #64748b; }

        /* ── Subject rankings ── */
        .subject-rank-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 10px 0; border-bottom: 1px solid #f8fafc;
        }
        .subject-rank-row:last-child { border-bottom: none; padding-bottom: 0; }
        .sr-bar-bg { flex: 1; height: 6px; background: #f1f5f9; border-radius: 100px; margin: 0 12px; overflow: hidden; }
        .sr-bar-fill { height: 100%; border-radius: 100px; background: linear-gradient(90deg, #6C5CE7, #8B5CF6); }

        /* ── Modal ── */
        .modal-overlay {
            position: fixed; top:0; left:0; width:100%; height:100%;
            background: rgba(15,23,42,0.55); backdrop-filter: blur(6px);
            display: flex; align-items: center; justify-content: center;
            z-index: 1000; opacity:0; visibility:hidden; transition: all 0.3s;
        }
        .modal-overlay.active { opacity:1; visibility:visible; }
        .modal-content {
            background: #fff; border-radius: 24px; width: 100%; max-width: 480px;
            padding: 32px; box-shadow: 0 24px 48px rgba(0,0,0,0.12);
            transform: translateY(24px) scale(0.98); transition: all 0.3s cubic-bezier(.4,0,.2,1);
        }
        .modal-overlay.active .modal-content { transform: translateY(0) scale(1); }
        .form-control {
            width: 100%; padding: 11px 16px 11px 40px;
            border: 1.5px solid #e2e8f0; border-radius: 12px;
            font-size: 14px; font-family: 'Inter', sans-serif;
            transition: all 0.2s; color: #0f172a; background: #fdfdfd;
            box-sizing: border-box;
        }
        .form-control:focus { border-color: #6C5CE7; outline:none; box-shadow: 0 0 0 4px rgba(108,92,231,0.10); background: #fff; }
        .input-icon-wrapper { position: relative; margin-top: 6px; margin-bottom: 16px; }
        .input-icon-wrapper svg {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            width: 17px; height: 17px; color: #94a3b8; pointer-events: none;
        }
        .admin-header, .premium-card, .inv-card, .kpi-box { box-shadow:0 12px 34px rgba(36,32,91,.055); }
        .admin-header { padding:32px 36px; border-radius:24px; }
        .admin-title { font-size:clamp(22px,2.3vw,30px); }
        .kpi-box { border-radius:20px; transition:transform .2s ease,box-shadow .2s ease; }
        .kpi-box:hover { transform:translateY(-4px); box-shadow:0 18px 36px rgba(36,32,91,.11); }
        .premium-card { border-radius:22px; }
        .inv-card { border-radius:22px; }
        .trend-controls { display:flex; flex-wrap:wrap; gap:7px; margin:4px 0 18px; }
        .trend-filter { border:1px solid #e7e4f5; background:#fff; color:#64748b; border-radius:999px; padding:7px 13px; font-size:12px; font-weight:700; cursor:pointer; transition:all .2s; }
        .trend-filter:hover,.trend-filter[aria-pressed="true"] { color:#5b38c0; border-color:#b8abf7; background:#f3efff; }
        .trend-chart-wrap { height:280px; position:relative; min-width:0; }
        .trend-legend { display:flex; align-items:center; gap:8px; color:#64748b; font-size:12px; font-weight:650; margin-top:14px; }
        .trend-legend-dot { width:10px; height:10px; border-radius:50%; background:#6c5ce7; box-shadow:0 0 0 4px #eeeaff; }
        .trend-empty { height:100%; display:flex; align-items:center; justify-content:center; text-align:center; color:#64748b; font-size:14px; background:#faf9ff; border:1px dashed #ddd6fe; border-radius:16px; padding:20px; }
        .plan-widget { background:linear-gradient(145deg,#211d43,#392878); border:1px solid #514395; color:#fff; overflow:hidden; position:relative; }
        .plan-widget::after { content:""; position:absolute; width:190px; height:190px; right:-60px; top:-70px; border-radius:50%; border:28px solid rgba(255,255,255,.055); pointer-events:none; }
        .plan-widget .card-title { color:#fff; }
        .plan-widget .card-subtitle { color:#c8c2ed; }
        .plan-status { display:inline-flex; border:1px solid rgba(255,255,255,.23); background:rgba(255,255,255,.1); border-radius:999px; padding:5px 10px; font-size:11px; font-weight:750; text-transform:capitalize; }
        .plan-current { font-size:27px; font-weight:850; letter-spacing:-.04em; margin:14px 0 3px; }
        .plan-usage-row { display:flex; justify-content:space-between; gap:12px; font-size:12px; color:#ddd8f8; margin:17px 0 8px; }
        .plan-meter { height:9px; background:rgba(255,255,255,.17); border-radius:99px; overflow:hidden; }
        .plan-meter span { display:block; height:100%; border-radius:inherit; background:linear-gradient(90deg,#b7a5ff,#76e3d5); }
        .plan-features { font-size:12px; color:#d8d2f4; margin:15px 0 18px; line-height:1.55; }
        .plan-tiers { display:flex; flex-wrap:wrap; gap:6px; }
        .plan-tiers span { font-size:10px; font-weight:700; padding:5px 8px; background:rgba(255,255,255,.09); border-radius:7px; }
        .plan-cta { display:inline-flex; justify-content:center; align-items:center; width:100%; margin-top:17px; padding:11px 16px; border-radius:11px; background:#fff; color:#4e36b0; font-size:13px; font-weight:800; text-decoration:none; transition:transform .2s,box-shadow .2s; }
        .plan-cta:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(0,0,0,.15); }
        @media(max-width:600px) { .admin-header { padding:23px; } .trend-chart-wrap { height:245px; } }
        @media(prefers-reduced-motion:reduce) { .kpi-box,.trend-filter,.plan-cta { transition:none; } }
    </style>
    @endpush

    <div class="admin-header">
        <div style="position:relative;z-index:2;">
            <h2 class="admin-title">Welcome back, {{ auth()->user()->name }} 👋</h2>
            <p class="admin-subtitle">Here is what's happening at your academy today.</p>
        </div>
        <div style="display:flex;align-items:center;gap:12px;position:relative;z-index:2;">
            <button class="btn-solid-dark" onclick="openTeacherModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5c-1.1 0-2 .9-2 2v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                Add Teacher
            </button>
            <a href="{{ route('students.create') }}" class="btn-solid-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/><line x1="12" y1="11" x2="12" y2="17"/><line x1="9" y1="14" x2="15" y2="14"/></svg>
                Add Student
            </a>
        </div>
    </div>

    {{-- Invite Section --}}
    <div class="inv-card" id="academy-profile">

        {{-- LEFT STRIPE --}}
        <div class="inv-stripe">
            <div class="inv-stripe-circles"></div>
            <div class="inv-pill">
                <span class="inv-dot"></span>
                <span class="inv-pill-text">Live</span>
            </div>
            <h3 class="inv-stripe-title">Invite<br>Students</h3>
            <p class="inv-stripe-school">{{ auth()->user()->school->name ?? 'Your School' }}</p>
        </div>

        {{-- MIDDLE CONTENT --}}
        <div class="inv-mid">
            <p class="inv-desc">
                Share this link and students will automatically join
                <strong>{{ auth()->user()->school->name ?? 'your school' }}</strong>
                — no manual setup needed.
            </p>

            <div class="inv-actions">
                <div class="inv-link-box">
                    <svg class="inv-link-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                    </svg>
                    <span class="inv-link-val" id="inviteLinkVal">{{ $inviteLink }}</span>
                    <input type="hidden" id="inviteLinkInput" value="{{ $inviteLink }}">
                    <input type="hidden" id="schoolCodeInput" value="{{ $schoolCode }}">
                </div>

                <button class="inv-btn-primary" id="invCopyBtn" onclick="copyInviteLink()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                    </svg>
                    <span id="invCopyLabel">Copy Link</span>
                </button>

                <button class="inv-btn-secondary" onclick="shareInviteLink()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/>
                        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                        <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                    </svg>
                    Share
                </button>
            </div>
        </div>

        {{-- RIGHT QR --}}
        <div class="inv-qr-panel">
            <div class="inv-qr-frame" id="qrcode-container">
                {{-- QR injected by JS --}}
            </div>
            <span class="inv-scan-label">Scan to join</span>
            <span class="inv-scan-code">{{ $schoolCode }}</span>
        </div>

    </div>

    {{-- KPI Cards --}}
    <div class="kpi-container">
        <div class="kpi-box kpi-primary">
            <div class="kpi-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <div class="kpi-value">{{ $studentCount }}</div>
                <div class="kpi-label">Total Students</div>
            </div>
        </div>
        <div class="kpi-box kpi-success">
            <div class="kpi-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            </div>
            <div>
                <div class="kpi-value">{{ $teacherCount }}</div>
                <div class="kpi-label">Active Teachers</div>
            </div>
        </div>
        <div class="kpi-box kpi-warning">
            <div class="kpi-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div>
                <div class="kpi-value">{{ $summary['slow_learners'] }}</div>
                <div class="kpi-label">Slow Learners</div>
            </div>
        </div>
        <div class="kpi-box kpi-info">
            <div class="kpi-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            </div>
            <div>
                <div class="kpi-value">{{ $summary['not_evaluated'] }}</div>
                <div class="kpi-label">Not Evaluated</div>
            </div>
        </div>
        <div class="kpi-box kpi-danger">
            <div class="kpi-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <div>
                <div class="kpi-value">{{ $activeRemedials }}</div>
                <div class="kpi-label">Active Remedials</div>
            </div>
        </div>
    </div>

    {{-- Main Dashboard Grids --}}
    <div class="main-grid">
        
        {{-- Left Column --}}
        <div style="display: flex; flex-direction: column; gap: 24px;">
            
            {{-- Performance Trend Chart --}}
            <div class="premium-card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Academy Performance Trends</h3>
                        <p class="card-subtitle">Average marks progression over recent assessments.</p>
                    </div>
                    <a href="{{ route('reports.index') }}" class="btn-solid-dark" style="padding: 8px 16px; font-size: 13px;">Full Report</a>
                </div>
                <div class="trend-controls" role="group" aria-label="Assessment filter">
                    <button type="button" class="trend-filter" data-trend-filter="all" aria-pressed="true">All assessments</button>
                    <button type="button" class="trend-filter" data-trend-filter="unit_test" aria-pressed="false">Unit tests</button>
                    <button type="button" class="trend-filter" data-trend-filter="midterm" aria-pressed="false">Mid-term</button>
                    <button type="button" class="trend-filter" data-trend-filter="final" aria-pressed="false">Finals</button>
                    <button type="button" class="trend-filter" data-trend-filter="practical" aria-pressed="false">Practical</button>
                </div>
                <div class="trend-chart-wrap">
                    <canvas id="performanceChart"></canvas>
                </div>
                <div class="trend-legend"><span class="trend-legend-dot"></span> Average marks <span id="trendPointCount" aria-live="polite"></span></div>
            </div>

            {{-- Recent Students Table --}}
            <div class="premium-card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Recent Students</h3>
                        <p class="card-subtitle">Latest enrollments at the academy.</p>
                    </div>
                    <a href="{{ route('students.index') }}" class="btn-solid-dark" style="padding: 8px 16px; font-size: 13px;">View All</a>
                </div>
                <div style="overflow-x: auto;">
                    <table class="premium-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Class/Sec</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentStudents as $student)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div class="student-avatar">{{ strtoupper(substr($student->user->name ?? 'U', 0, 1)) }}</div>
                                        <div>
                                            <div style="font-weight: 700; color: #0f172a;">{{ $student->user->name ?? '-' }}</div>
                                            <div style="font-size: 12px; color: #64748b;">Roll: {{ $student->roll_number ?? $student->roll_no }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span style="font-weight: 600; color: #334155;">{{ $student->class }}</span> <span style="color: #94a3b8;">{{ $student->section }}</span></td>
                                <td>
                                    @php
                                        $perfLabel = $student->performance_label ?? 'No Data';
                                        $statusClass = match(true) {
                                            str_contains(strtolower($perfLabel), 'good') => 'status-success',
                                            str_contains(strtolower($perfLabel), 'risk') => 'status-warning',
                                            str_contains(strtolower($perfLabel), 'slow') => 'status-danger',
                                            default => 'status-neutral',
                                        };
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ $perfLabel }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 32px; color: #94a3b8; font-weight: 500;">No students enrolled yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div style="display: flex; flex-direction: column; gap: 24px;">
            {{-- School plan comes from the backend subscription state. Legacy schools keep full access. --}}
            @if($subscriptionState)
            @php
                $planUsage = (int) ($subscriptionState['student_usage'] ?? 0);
                $planLimit = $subscriptionState['student_limit'] ?? null;
                $planPercent = $planLimit ? min(100, round($planUsage * 100 / $planLimit)) : 0;
                $planName = $subscriptionState['plan_name'] ?? ($subscriptionState['status'] === 'legacy' ? 'Legacy access' : 'Select a plan');
            @endphp
            <section class="premium-card plan-widget" aria-label="School subscription">
                <div class="card-header" style="margin-bottom:8px;"><div><h3 class="card-title">Your school plan</h3><p class="card-subtitle">Subscription and capacity at a glance</p></div><span class="plan-status">{{ $subscriptionState['status'] }}</span></div>
                <div class="plan-current">{{ $planName }}</div>
                <div class="plan-tiers" aria-label="Available tiers"><span>Starter</span><span>Growth / Professional</span><span>Enterprise</span></div>
                <div class="plan-usage-row"><span>Active students</span><strong>{{ number_format($planUsage) }}{{ $planLimit ? ' / '.number_format($planLimit) : ' · no plan limit' }}</strong></div>
                @if($planLimit)<div class="plan-meter" role="progressbar" aria-label="Student capacity used" aria-valuenow="{{ $planUsage }}" aria-valuemin="0" aria-valuemax="{{ $planLimit }}"><span style="width:{{ $planPercent }}%"></span></div>@endif
                <p class="plan-features">{{ count($subscriptionState['features'] ?? []) }} features available{{ $planLimit ? ' · '.number_format(max(0, $planLimit - $planUsage)).' student places remaining' : '' }}</p>
                <a href="{{ route('billing') }}" class="plan-cta">View billing &amp; plans →</a>
            </section>
            
            {{-- Action Buttons --}}
            <div class="premium-card">
                <div class="card-header" style="margin-bottom:16px;"><div><h3 class="card-title">Quick Actions</h3><p class="card-subtitle">Frequently used shortcuts</p></div></div>
                <div style="display:grid;grid-template-columns:1fr;gap:10px;">
                    <a href="{{ route('marks.create') }}" class="btn-quick-action">
                        <div class="qa-icon" style="color:#6C5CE7;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </div>
                        Upload Marks
                    </a>
                    <a href="{{ route('remedial.index') }}" class="btn-quick-action">
                        <div class="qa-icon" style="color:#10b981;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                        </div>
                        Assign Remedial Tasks
                    </a>
                    <a href="{{ route('subjects.index') }}" class="btn-quick-action">
                        <div class="qa-icon" style="color:#f59e0b;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                        </div>
                        Manage Subjects
                    </a>
                    <a href="{{ route('performance.index') }}" class="btn-quick-action">
                        <div class="qa-icon" style="color:#3b82f6;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        </div>
                        View Performance
                    </a>
                </div>
            </div>

            {{-- Alerts Panel --}}
            <div class="premium-card">
                <div class="card-header" style="margin-bottom: 16px;">
                    <h3 class="card-title" style="color: #ef4444;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        Action Required
                    </h3>
                </div>
                <div class="flex flex-col">
                    @forelse($slowLearners as $learner)
                        <div class="alert-item">
                            <div>
                                <div class="alert-title">{{ $learner->user->name ?? 'Unknown Student' }}</div>
                                <div class="alert-desc">Avg: {{ number_format($learner->avg_pct, 1) }}% — Multiple fails</div>
                            </div>
                            <a href="{{ route('performance.show', $learner->id) }}" class="btn-review">Review</a>
                        </div>
                    @empty
                        <div style="padding: 24px; text-align: center; border-radius: 12px; background: #f8fafc; border: 1px dashed #cbd5e1;">
                            <p style="color: #64748b; font-weight: 500; font-size: 13px;">All students are performing well. No critical alerts.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Subject Rankings --}}
            <div class="premium-card">
                <div class="card-header" style="margin-bottom:16px;">
                    <div><h3 class="card-title">Subject Rankings</h3><p class="card-subtitle">Top performing subjects</p></div>
                </div>
                <div>
                    @forelse($subjectAvgs->sortByDesc('avg')->take(5) as $idx => $s)
                        <div class="subject-rank-row">
                            <div style="display:flex;align-items:center;gap:8px;min-width:0;">
                                <div style="width:24px;height:24px;border-radius:7px;background:{{ $idx===0?'#eef2ff':'#f8fafc' }};color:{{ $idx===0?'#6366f1':'#94a3b8' }};display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;flex-shrink:0;">#{{ $idx+1 }}</div>
                                <span style="font-size:13px;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $s['subject'] }}</span>
                            </div>
                            <div class="sr-bar-bg"><div class="sr-bar-fill" style="width:{{ min(100, $s['avg']) }}%;"></div></div>
                            <span style="font-size:13px;font-weight:800;color:{{ $idx===0?'#6C5CE7':'#475569' }};min-width:40px;text-align:right;">{{ number_format($s['avg'],1) }}%</span>
                        </div>
                    @empty
                        <p style="color:#94a3b8;font-size:13px;text-align:center;padding:16px 0;">No subject data available.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- Add Teacher Modal --}}
    <div class="modal-overlay" id="teacherModal">
        <div class="modal-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <div>
                    <h3 style="font-weight: 800; font-size: 20px; color: #0f172a;">Add New Teacher</h3>
                    <p style="font-size: 13px; color: #64748b; margin-top: 4px;">Create a new academic account</p>
                </div>
                <button onclick="closeTeacherModal()" style="background: none; border: none; cursor: pointer; color: #94a3b8; transition: 0.2s;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#94a3b8'">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{{ route('teachers.store') }}" method="POST">
                @csrf
                <div>
                    <div>
                        <label style="font-weight: 700; font-size: 13px; color: #334155;">Full Name *</label>
                        <div class="input-icon-wrapper">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <input type="text" name="name" class="form-control" placeholder="e.g. John Smith" value="{{ old('name') }}" required>
                        </div>
                        @error('name') <p style="color: #ef4444; font-size: 12px; margin-top: -8px; margin-bottom: 12px;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="font-weight: 700; font-size: 13px; color: #334155;">Email Address *</label>
                        <div class="input-icon-wrapper">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <input type="email" name="email" class="form-control" placeholder="teacher@school.com" value="{{ old('email') }}" required>
                        </div>
                        @error('email') <p style="color: #ef4444; font-size: 12px; margin-top: -8px; margin-bottom: 12px;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="font-weight: 700; font-size: 13px; color: #334155;">Password *</label>
                        <div class="input-icon-wrapper">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <input type="password" name="password" class="form-control" placeholder="Create a strong password" required>
                        </div>
                        @error('password') <p style="color: #ef4444; font-size: 12px; margin-top: -8px; margin-bottom: 12px;">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div style="display: flex; gap: 12px; margin-top: 24px; justify-content: flex-end;">
                    <button type="button" class="btn-solid-dark" style="background: transparent; color: #64748b; box-shadow: none;" onclick="closeTeacherModal()">Cancel</button>
                    <button type="submit" class="btn-solid-primary">Create Account</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <!-- Must include QRCode.js before this template is rendered or locally here -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        function openTeacherModal() {
            document.getElementById('teacherModal').classList.add('active');
        }

        function closeTeacherModal() {
            document.getElementById('teacherModal').classList.remove('active');
        }

        @if($errors->has('name') || $errors->has('email') || $errors->has('password'))
            window.addEventListener('DOMContentLoaded', openTeacherModal);
        @endif

        function copyInviteLink() {
            var link = document.getElementById('inviteLinkInput').value;
            var btn  = document.getElementById('invCopyBtn');
            var lbl  = document.getElementById('invCopyLabel');

            navigator.clipboard.writeText(link).then(function () {
                lbl.textContent = 'Copied!';
                btn.style.background = 'linear-gradient(135deg, #059669 0%, #10b981 100%)';
                btn.style.boxShadow  = '0 3px 14px rgba(5, 150, 105, 0.32)';

                setTimeout(function () {
                    lbl.textContent = 'Copy Link';
                    btn.style.background = 'linear-gradient(135deg, #6d4ac7 0%, #8b5cf6 100%)';
                    btn.style.boxShadow  = '0 3px 14px rgba(109, 74, 199, 0.32)';
                }, 2500);
            }).catch(function () {
                /* Fallback for older browsers */
                var tmp = document.createElement('textarea');
                tmp.value = link;
                document.body.appendChild(tmp);
                tmp.select();
                document.execCommand('copy');
                document.body.removeChild(tmp);
                lbl.textContent = 'Copied!';
                setTimeout(function () { lbl.textContent = 'Copy Link'; }, 2500);
            });
        }

        function shareInviteLink() {
            var link  = document.getElementById('inviteLinkInput').value;
            var school = '{{ auth()->user()->school->name ?? "your school" }}';

            if (navigator.share) {
                navigator.share({
                    title: 'Join ' + school,
                    text:  'Use this link to join ' + school + ' on PMRS.',
                    url:   link
                }).catch(function () {});
            } else {
                copyInviteLink();
            }
        }

        /* Generate QR code after DOM + library are ready */
        window.addEventListener('DOMContentLoaded', function () {
            var container = document.getElementById('qrcode-container');
            var link      = document.getElementById('inviteLinkInput').value;

            if (typeof QRCode !== 'undefined' && container && link) {
                new QRCode(container, {
                    text:         link,
                    width:        130,
                    height:       130,
                    colorDark:    '#4c3d9e',
                    colorLight:   '#ffffff',
                    correctLevel: QRCode.CorrectLevel.M
                });
            }
        });

        // Draw the actual, school-scoped assessment averages returned by PerformanceService.
        window.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('performanceChart');
            if (!ctx) return;
            var trendData = {{ \Illuminate\Support\Js::from($trendData ?? []) }};
            var chart = null;
            var countLabel = document.getElementById('trendPointCount');
            function renderTrend(filter) {
                var rows = filter === 'all' ? trendData : trendData.filter(function(row) { return row.exam_type === filter; });
                if (chart) { chart.destroy(); chart = null; }
                var wrap = ctx.parentElement;
                var empty = wrap.querySelector('.trend-empty');
                if (empty) empty.remove();
                ctx.hidden = rows.length === 0 || typeof Chart === 'undefined';
                countLabel.textContent = rows.length ? '· ' + rows.length + ' assessment groups' : '';
                if (ctx.hidden) {
                    var message = document.createElement('div');
                    message.className = 'trend-empty';
                    message.textContent = typeof Chart === 'undefined' ? 'Chart could not load. Please refresh the page.' : 'No marks for this assessment type yet.';
                    wrap.appendChild(message);
                    return;
                }
                var gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 280);
                gradient.addColorStop(0, 'rgba(108,92,231,.28)');
                gradient.addColorStop(1, 'rgba(108,92,231,.015)');
                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: rows.map(function(row) { return row.label; }),
                        datasets: [{
                            label: 'Average marks',
                            data: rows.map(function(row) { return row.avg; }),
                            borderColor: '#6C5CE7',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            tension: 0.35,
                            fill: true,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#6C5CE7',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 8,
                            pointHitRadius: 16
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { intersect: false, mode: 'index' },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                titleFont: { size: 12, weight: '700' },
                                bodyFont: { size: 13, weight: '600' },
                                padding: 12,
                                cornerRadius: 10,
                                displayColors: false,
                                callbacks: { label: function(item) { return item.parsed.y.toFixed(1) + '% average · ' + rows[item.dataIndex].count + ' marks'; } }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, max: 100, ticks: { callback: function(value) { return value + '%'; } }, grid: { color: '#f1f5f9' }, border: { display: false } },
                            x: { grid: { display: false }, border: { display: false }, ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 5 } }
                        }
                    }
                });
            }
            document.querySelectorAll('[data-trend-filter]').forEach(function(button) {
                button.addEventListener('click', function() {
                    document.querySelectorAll('[data-trend-filter]').forEach(function(item) { item.setAttribute('aria-pressed', String(item === button)); });
                    renderTrend(button.dataset.trendFilter);
                });
            });
            renderTrend('all');
        });
    </script>
    @endpush
</x-app-layout>
