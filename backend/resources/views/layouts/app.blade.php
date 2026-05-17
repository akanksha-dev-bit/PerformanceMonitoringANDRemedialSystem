<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $title ?? 'Dashboard' }} — PMRS</title>
  <meta name="description" content="PMRS — Performance Monitoring & Remedial System for schools" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
  <style>
    /* ── Design Tokens ── */
    :root {
      --c-primary:    #6C5CE7;
      --c-primary-d:  #5A4BD6;
      --c-primary-l:  #A29BFE;
      --c-primary-bg: rgba(108,92,231,0.08);
      --c-success:    #22c55e;
      --c-warning:    #f59e0b;
      --c-danger:     #ef4444;
      --c-info:       #3b82f6;
      --c-neutral:    #94a3b8;
      --c-text:       #0f172a;
      --c-text-2:     #64748b;
      --c-muted:      #94a3b8;
      --c-border:     rgba(0,0,0,0.07);
      --c-card:       #ffffff;
      --grad-primary: linear-gradient(135deg,#6C5CE7 0%,#5A4BD6 100%);
    }

    /* ── Page ── */
    html,body { margin:0; padding:0; }
    body { background: linear-gradient(180deg,#f8fafc 0%,#eef2ff 100%) fixed; min-height:100vh; font-family:'Inter',sans-serif; color:var(--c-text); }

    /* ── Floating Navbar outer ── */
    .pmrs-navbar-outer {
      position: sticky;
      top: 0;
      z-index: 200;
      padding: 10px 20px 0;
      pointer-events: none;
    }

    /* ── Navbar ── */
    .pmrs-navbar {
      pointer-events: all;
      height: 60px;
      display: flex;
      align-items: center;
      padding: 0 18px;
      gap: 8px;
      background: rgba(255,255,255,0.78);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255,255,255,0.55);
      border-radius: 16px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.06), 0 1px 0 rgba(255,255,255,0.5) inset;
      transition: all 0.22s ease;
    }
    .pmrs-navbar.scrolled {
      background: rgba(255,255,255,0.96);
      box-shadow: 0 12px 40px rgba(108,92,231,0.10), 0 1px 3px rgba(0,0,0,0.05);
    }

    /* ── Logo ── */
    .nb-logo { display:flex; align-items:center; gap:10px; text-decoration:none; flex-shrink:0; }
    .nb-logo img { height:34px; width:auto; object-fit:contain; }

    /* ── Desktop Nav pills ── */
    .nb-nav { display:flex; align-items:center; gap:2px; margin-left:10px; }
    .nb-nav a {
      display:flex; align-items:center;
      padding: 6px 13px;
      border-radius: 100px;
      font-size: 13px; font-weight:500;
      color: var(--c-text-2);
      text-decoration:none;
      transition: all 0.22s cubic-bezier(.4,0,.2,1);
      white-space:nowrap; letter-spacing: -0.01em;
    }
    .nb-nav a:hover { color:var(--c-text); background:rgba(108,92,231,0.06); transform:translateY(-1px); box-shadow: 0 2px 8px rgba(108,92,231,0.06); }
    .nb-nav a.active {
      color:#fff;
      background: linear-gradient(135deg, #6C5CE7 0%, #8B5CF6 100%);
      font-weight:650;
      box-shadow: 0 8px 18px rgba(108,92,231,0.28), 0 2px 6px rgba(108,92,231,0.18);
      letter-spacing: -0.01em;
    }
    .nb-nav a.active:hover { opacity:.92; transform:translateY(-1px); }

    /* ── Spacer ── */
    .nb-spacer { flex:1; }

    /* ── Search ── */
    .nb-search-wrap { position:relative; display:flex; align-items:center; }
    .nb-search-icon { position:absolute; left:12px; color:var(--c-muted); pointer-events:none; display:flex; }
    .nb-search-input {
      width: 200px;
      padding: 8px 72px 8px 36px;
      border-radius: 100px;
      border: 1px solid rgba(0,0,0,0.07);
      background: rgba(255,255,255,0.55);
      font-size:13px; font-family:'Inter',sans-serif; color:var(--c-text);
      outline:none;
      box-shadow: inset 0 1px 2px rgba(0,0,0,0.03);
      transition: all 0.25s cubic-bezier(.4,0,.2,1);
    }
    .nb-search-input::placeholder { color:var(--c-muted); font-size:12.5px; }
    .nb-search-input:focus {
      width:290px;
      border-color: rgba(108,92,231,0.45);
      background:#fff;
      box-shadow: 0 0 0 4px rgba(108,92,231,0.10), inset 0 1px 2px rgba(0,0,0,0.02);
    }
    .nb-search-kbd { position:absolute; right:10px; display:flex; align-items:center; gap:3px; pointer-events:none; }
    .nb-search-kbd kbd {
      background:#f1f5f9; color:var(--c-muted);
      padding:1px 5px; border-radius:5px;
      font-family:monospace; font-size:10px; font-weight:700;
      border:1px solid #e2e8f0;
    }

    /* ── Search Dropdown ── */
    .nb-search-dropdown {
      display:none; position:absolute;
      top:calc(100% + 10px); left:0; right:0; min-width:280px;
      background:rgba(255,255,255,0.98);
      backdrop-filter:blur(12px);
      border:1px solid var(--c-border);
      border-radius:14px;
      box-shadow:0 12px 40px rgba(0,0,0,0.10);
      overflow:hidden; z-index:300;
      animation:nbDropIn .18s ease;
    }

    /* ── Divider ── */
    .nb-divider { width:1px; height:26px; background:rgba(0,0,0,0.08); flex-shrink:0; }

  

    /* ── Notification / Quick-action icon buttons ── */
    .nb-icon-btn {
      position: relative; display:flex; align-items:center; justify-content:center;
      width:36px; height:36px; border-radius:10px;
      border: 1px solid rgba(0,0,0,0.06);
      background: rgba(255,255,255,0.5);
      color: var(--c-text-2); cursor:pointer;
      transition: all 0.2s ease; flex-shrink:0;
    }
    .nb-icon-btn:hover { background: var(--c-primary-bg); color:var(--c-primary); border-color:rgba(108,92,231,0.18); transform:translateY(-1px); }
    .nb-icon-btn .notif-dot {
      position:absolute; top:7px; right:7px;
      width:7px; height:7px; border-radius:50%;
      background:#ef4444; border:1.5px solid #fff;
    }

    /* ── Profile pill ── */
    .nb-profile {
      position:relative; display:flex; align-items:center; gap:8px; cursor:pointer;
      padding:4px 10px 4px 4px;
      border-radius:100px;
      border:1px solid rgba(0,0,0,0.07);
      background: rgba(255,255,255,0.45);
      transition: all 0.22s ease; flex-shrink:0;
    }
    .nb-profile:hover { background: var(--c-primary-bg); border-color:rgba(108,92,231,0.2); }
    .nb-avatar {
      width:32px; height:32px; border-radius:50%;
      background: linear-gradient(135deg,#6C5CE7,#8B5CF6);
      color: #fff;
      font-weight:700; font-size:12px;
      display:flex; align-items:center; justify-content:center; flex-shrink:0;
      box-shadow:0 2px 8px rgba(108,92,231,0.30);
      transition:transform 0.22s cubic-bezier(.4,0,.2,1), box-shadow 0.22s ease;
    }
    .nb-profile:hover .nb-avatar { transform:scale(1.06); box-shadow:0 4px 14px rgba(108,92,231,0.4); }
    .nb-user-text { display:flex; flex-direction:column; }
    .nb-user-name { font-size:13px; font-weight:650; color:var(--c-text); line-height:1.25; letter-spacing:-0.01em; }
    .nb-user-role { font-size:10.5px; color:var(--c-muted); line-height:1.25; font-weight:500; }
    .nb-chevron { color:var(--c-muted); transition:transform 0.22s ease; flex-shrink:0; }
    .nb-profile.open .nb-chevron { transform:rotate(180deg); }

    /* ── Profile Dropdown ── */
    .nb-profile-dropdown {
      display:none; position:absolute;
      top:calc(100% + 12px); right:0; width:248px;
      background: rgba(255,255,255,0.92);
      backdrop-filter: blur(22px);
      -webkit-backdrop-filter: blur(22px);
      border: 1px solid rgba(255,255,255,0.6);
      border-radius:20px;
      box-shadow: 0 18px 40px rgba(0,0,0,0.10), 0 2px 8px rgba(0,0,0,0.04);
      overflow:hidden; z-index:300;
      animation:nbDropIn .2s cubic-bezier(.4,0,.2,1);
    }
    .nb-profile-dropdown.open { display:block; }
    .nb-dropdown-header {
      padding:16px;
      border-bottom:1px solid rgba(0,0,0,0.05);
      display:flex; gap:10px; align-items:center;
    }
    .nb-dropdown-header .dd-avatar {
      width:38px; height:38px; border-radius:50%; flex-shrink:0;
      background: linear-gradient(135deg,#6C5CE7,#8B5CF6);
      color:#fff; font-weight:700; font-size:13px;
      display:flex; align-items:center; justify-content:center;
    }
    .nb-dropdown-header .full-name { font-weight:700; color:var(--c-text); font-size:14px; line-height:1.3; letter-spacing:-0.01em; }
    .nb-dropdown-header .email { font-size:11.5px; color:var(--c-muted); margin-top:1px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .nb-dropdown-section { padding:6px 8px; }
    .nb-dropdown-sep { height:1px; background:rgba(0,0,0,0.05); margin:4px 0; }
    .nb-dropdown-item {
      display:flex; align-items:center; gap:10px;
      padding:9px 10px; border-radius:10px;
      font-size:13px; color:var(--c-text-2); cursor:pointer;
      transition: all 0.15s ease; text-decoration:none;
      border:none; background:transparent; width:100%; text-align:left;
    }
    .nb-dropdown-item:hover { background:rgba(108,92,231,0.06); color:var(--c-text); transform:translateX(2px); }
    .nb-dropdown-item .dd-icon {
      width:28px; height:28px; border-radius:7px; background:#f8fafc;
      display:flex; align-items:center; justify-content:center; flex-shrink:0;
      font-size:14px;
    }
    .nb-dropdown-item.danger { color:#dc2626; }
    .nb-dropdown-item.danger .dd-icon { background:rgba(239,68,68,0.08); }
    .nb-dropdown-item.danger:hover { background:rgba(239,68,68,0.08); color:#dc2626; transform:translateX(2px); }

    /* ── Notification Dropdown ── */
    .nb-notif-dropdown {
      display:none; position:absolute;
      top:calc(100% + 12px); right:0; width:300px;
      background: rgba(255,255,255,0.92);
      backdrop-filter: blur(22px); -webkit-backdrop-filter: blur(22px);
      border:1px solid rgba(255,255,255,0.6);
      border-radius:18px;
      box-shadow: 0 18px 40px rgba(0,0,0,0.10);
      overflow:hidden; z-index:300;
      animation:nbDropIn .2s cubic-bezier(.4,0,.2,1);
    }
    .nb-notif-dropdown.open { display:block; }
    .nb-notif-header { padding:14px 16px; border-bottom:1px solid rgba(0,0,0,0.05); display:flex; justify-content:space-between; align-items:center; }
    .nb-notif-header h4 { font-size:14px; font-weight:700; color:var(--c-text); margin:0; }
    .nb-notif-item {
      display:flex; gap:10px; align-items:flex-start;
      padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.03);
      transition:background 0.15s;
    }
    .nb-notif-item:last-child { border-bottom:none; }
    .nb-notif-item:hover { background:rgba(108,92,231,0.04); }
    .nb-notif-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; margin-top:5px; }
    .nb-notif-title { font-size:13px; font-weight:600; color:#1e293b; }
    .nb-notif-time { font-size:11px; color:var(--c-muted); margin-top:2px; }

    @keyframes nbDropIn {
      from { opacity:0; transform:translateY(-8px) scale(0.97); }
      to   { opacity:1; transform:translateY(0) scale(1); }
    }

    /* ── Search result items ── */
    .nb-result-item {
      display:flex; align-items:center; gap:12px;
      padding:11px 16px; color:var(--c-text); text-decoration:none;
      transition:background .15s; border-bottom:1px solid rgba(0,0,0,0.04);
    }
    .nb-result-item:last-child { border-bottom:none; }
    .nb-result-item:hover { background:rgba(108,92,231,0.06); }
    .nb-result-icon { width:32px; height:32px; border-radius:8px; background:#f8fafc; display:flex; align-items:center; justify-content:center; color:var(--c-primary); flex-shrink:0; }
    .nb-result-title { font-size:14px; font-weight:600; }
    .nb-result-sub { font-size:12px; color:var(--c-muted); margin-top:1px; }
    .nb-result-msg { padding:14px 16px; font-size:13px; color:var(--c-muted); text-align:center; }

    /* ── Hamburger (mobile only) ── */
    .nb-hamburger {
      display:none;
      width:36px; height:36px;
      border:1px solid var(--c-border);
      background:rgba(0,0,0,0.02);
      border-radius:10px;
      flex-direction:column; align-items:center; justify-content:center; gap:5px;
      cursor:pointer; flex-shrink:0;
      transition:all .2s ease;
    }
    .nb-hamburger span { display:block; width:18px; height:1.5px; background:var(--c-text-2); border-radius:2px; transition:all .25s ease; }
    .nb-hamburger.open span:nth-child(1) { transform:translateY(6.5px) rotate(45deg); }
    .nb-hamburger.open span:nth-child(2) { opacity:0; transform:scaleX(0); }
    .nb-hamburger.open span:nth-child(3) { transform:translateY(-6.5px) rotate(-45deg); }

    /* ── Mobile Drawer ── */
    .nb-drawer-overlay {
      display:none; position:fixed; inset:0;
      background:rgba(0,0,0,0.35); backdrop-filter:blur(2px);
      z-index:299;
      animation:fadeIn .2s ease;
    }
    .nb-drawer-overlay.open { display:block; }
    @keyframes fadeIn { from{opacity:0} to{opacity:1} }

    .nb-drawer {
      position:fixed; top:0; left:0; bottom:0; width:270px;
      background:#fff;
      box-shadow:6px 0 32px rgba(0,0,0,0.12);
      z-index:300; display:flex; flex-direction:column;
      transform:translateX(-100%);
      transition:transform .3s cubic-bezier(.4,0,.2,1);
    }
    .nb-drawer.open { transform:translateX(0); }
    .nb-drawer-header {
      padding:20px 20px 16px;
      border-bottom:1px solid var(--c-border);
      display:flex; align-items:center; gap:10px;
    }
    .nb-drawer-nav { padding:12px 10px; display:flex; flex-direction:column; gap:2px; flex:1; overflow-y:auto; }
    .nb-drawer-nav a {
      display:flex; align-items:center; gap:10px;
      padding:10px 14px; border-radius:10px;
      font-size:14px; font-weight:500;
      color:var(--c-text-2); text-decoration:none;
      transition:all .18s ease;
    }
    .nb-drawer-nav a:hover { background:rgba(0,0,0,0.05); color:var(--c-text); }
    .nb-drawer-nav a.active { background:var(--c-primary-bg); color:var(--c-primary); font-weight:600; }
    .nb-drawer-footer { padding:16px 20px; border-top:1px solid var(--c-border); }

    /* ── Mobile search toggle ── */
    .nb-search-toggle {
      display:none;
      width:36px; height:36px;
      border:1px solid var(--c-border);
      background:rgba(0,0,0,0.02);
      border-radius:50%;
      align-items:center; justify-content:center;
      cursor:pointer; color:var(--c-text-2);
      transition:all .2s ease; flex-shrink:0;
    }
    .nb-search-toggle:hover { background:var(--c-primary-bg); color:var(--c-primary); }

    /* ── Responsive Breakpoints ── */
    @media (max-width: 1024px) {
      .nb-nav { margin-left:8px; }
      .nb-nav a { padding:5px 10px; font-size:12.5px; }
      .nb-search-input { width:150px; }
      .nb-search-input:focus { width:200px; }
      .nb-user-text { display:none; }
      .nb-chevron { display:none; }
    }

    @media (max-width: 768px) {
      .pmrs-navbar-outer { padding:8px 12px 0; }
      .pmrs-navbar { height:56px; padding:0 14px; border-radius:12px; }
      .nb-nav { display:none; }
      .nb-divider { display:none; }
      .nb-search-wrap { display:none; }
      .nb-search-kbd { display:none; }
      .nb-hamburger { display:flex; }
      .nb-search-toggle { display:flex; }
      .nb-profile { padding:4px; border:none; background:transparent; }
      .nb-profile:hover { background:transparent; }
    }

    @media (max-width: 480px) {
      .pmrs-navbar-outer { padding:6px 8px 0; }
      .pmrs-navbar { height:52px; padding:0 10px; border-radius:10px; }
    }

    /* ── Mobile search bar expansion ── */
    .nb-search-mobile-wrap {
      display:none; position:fixed;
      top:80px; left:12px; right:12px;
      z-index:250;
      animation:nbDropIn .2s ease;
    }
    .nb-search-mobile-wrap.open { display:block; }
    .nb-search-mobile-input {
      width:100%; padding:12px 20px 12px 44px;
      border-radius:100px;
      border:1.5px solid var(--c-primary);
      background:#fff;
      font-size:14px; font-family:'Inter',sans-serif; color:var(--c-text);
      outline:none;
      box-shadow:0 0 0 3px rgba(108,92,231,0.12), 0 8px 24px rgba(0,0,0,0.08);
    }
    .nb-search-mobile-icon { position:absolute; left:16px; top:50%; transform:translateY(-50%); color:var(--c-primary); pointer-events:none; display:flex; }
  </style>
  @stack('styles')

</head>
<body>
<div class="app-layout">
  <div class="main-content" id="main-content">

    {{-- ── Floating Navbar ── --}}
    <div class="pmrs-navbar-outer" id="pmrs-navbar-outer">
    <header class="pmrs-navbar" id="pmrs-navbar">

      {{-- LEFT: Logo & School Branding --}}
      <a href="{{ auth()->check() ? route('dashboard') : '/' }}" class="nb-logo">
        <img src="{{ asset('logo.png') }}" alt="PMRS Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
        <div style="display:none; width:32px; height:32px; background:var(--grad-primary); border-radius:8px; align-items:center; justify-content:center; color:#fff;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        </div>
      </a>

      @auth
      @if(auth()->user()->school)
        <div style="margin-left:8px; padding: 4px 10px; background: rgba(108,92,231,0.08); border: 1px solid rgba(108,92,231,0.2); border-radius: 8px; font-size: 11px; font-weight: 700; color: #5A4BD6; letter-spacing: 0.02em; display: flex; align-items: center; gap: 4px; box-shadow: 0 2px 8px rgba(108,92,231,0.05); user-select: none;">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          {{ strtoupper(auth()->user()->school->name) }}
        </div>
      @endif
      @endauth

      {{-- Nav Pills --}}
      @auth
      <nav class="nb-nav">
        @if(auth()->user()->isAdmin())
          <a href="{{ route('dashboard.admin') }}" class="{{ request()->routeIs('dashboard.*') ? 'active' : '' }}">Dashboard</a>
          <a href="{{ route('students.index') }}" class="{{ request()->routeIs('students.*') ? 'active' : '' }}">Students</a>
          <a href="{{ route('subjects.index') }}" class="{{ request()->routeIs('subjects.*') ? 'active' : '' }}">Subjects</a>
          <a href="{{ route('teachers.index') }}" class="{{ request()->routeIs('teachers.*') ? 'active' : '' }}">Teachers</a>
          <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">Reports</a>
        @elseif(auth()->user()->isTeacher())
          <a href="{{ route('dashboard.teacher') }}" class="{{ request()->routeIs('dashboard.*') ? 'active' : '' }}">Dashboard</a>
          <a href="{{ route('students.index') }}" class="{{ request()->routeIs('students.*') ? 'active' : '' }}">My Students</a>
          <a href="{{ route('performance.index') }}" class="{{ request()->routeIs('performance.*') ? 'active' : '' }}">Performance</a>
          <a href="{{ route('quizzes.index') }}" class="{{ request()->routeIs('quizzes.*') ? 'active' : '' }}">Quizzes</a>
        @elseif(auth()->user()->isStudent())
          <a href="{{ route('dashboard.student') }}" class="{{ request()->routeIs('dashboard.*') ? 'active' : '' }}">Dashboard</a>
          <a href="{{ route('student.progress') }}" class="{{ request()->routeIs('student.progress') ? 'active' : '' }}">My Progress</a>
          <a href="{{ route('student.tasks') }}" class="{{ request()->routeIs('student.tasks') ? 'active' : '' }}">My Tasks</a>
        @endif
      </nav>
      @endauth

      {{-- Mobile: Hamburger (hidden on desktop) --}}
      @auth
      <button class="nb-hamburger" id="nb-hamburger" onclick="toggleDrawer()" aria-label="Menu">
        <span></span><span></span><span></span>
      </button>
      @endauth

      <div class="nb-spacer"></div>

      {{-- Notification Bell --}}
      <div style="position:relative;" id="nb-notif-wrap">
        <button class="nb-icon-btn" onclick="toggleNotifMenu()" aria-label="Notifications">
          <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          <span class="notif-dot"></span>
        </button>
        <div class="nb-notif-dropdown" id="nb-notif-menu">
          <div class="nb-notif-header">
            <h4>Notifications</h4>
            <span style="font-size:11px;color:var(--c-primary);font-weight:600;cursor:pointer;">Mark all read</span>
          </div>
          <div class="nb-notif-item">
            <div class="nb-notif-dot" style="background:#6C5CE7;"></div>
            <div><div class="nb-notif-title">New remedial task assigned</div><div class="nb-notif-time">2 min ago</div></div>
          </div>
          <div class="nb-notif-item">
            <div class="nb-notif-dot" style="background:#10b981;"></div>
            <div><div class="nb-notif-title">Exam marks updated</div><div class="nb-notif-time">1 hour ago</div></div>
          </div>
          <div class="nb-notif-item">
            <div class="nb-notif-dot" style="background:#f59e0b;"></div>
            <div><div class="nb-notif-title">Progress report available</div><div class="nb-notif-time">Yesterday</div></div>
          </div>
        </div>
      </div>

      <div class="nb-divider"></div>

      {{-- Profile --}}
      <div class="nb-profile" id="nb-profile-btn" onclick="toggleProfileMenu(this)">
        <div class="nb-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
        <div class="nb-user-text">
          <span class="nb-user-name">{{ auth()->user()->name }}</span>
          <span class="nb-user-role">{{ auth()->user()->isAdmin() ? 'Administrator' : (auth()->user()->isTeacher() ? 'Teacher' : 'Student') }}</span>
        </div>
        <svg class="nb-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>

        {{-- Dropdown --}}
        <div class="nb-profile-dropdown" id="nb-profile-menu">
          <div class="nb-dropdown-header">
            <div class="dd-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div>
              <div class="full-name">{{ auth()->user()->name }}</div>
              <div class="email">{{ auth()->user()->email }}</div>
            </div>
          </div>

          <div class="nb-dropdown-section">
            @if(auth()->user()->isStudent())
              <a href="{{ route('dashboard.student') }}" class="nb-dropdown-item">
                <span class="dd-icon">🏠</span> Dashboard
              </a>
              <a href="{{ route('student.progress') }}" class="nb-dropdown-item">
                <span class="dd-icon">📊</span> My Progress
              </a>
              <a href="{{ route('student.tasks') }}" class="nb-dropdown-item">
                <span class="dd-icon">📋</span> My Tasks
              </a>
            @elseif(auth()->user()->isTeacher())
              <a href="{{ route('dashboard.teacher') }}" class="nb-dropdown-item">
                <span class="dd-icon">🏠</span> Dashboard
              </a>
              <a href="{{ route('quizzes.index') }}" class="nb-dropdown-item">
                <span class="dd-icon">📝</span> My Quizzes
              </a>
            @else
              <a href="{{ route('dashboard.admin') }}" class="nb-dropdown-item">
                <span class="dd-icon">🏠</span> Dashboard
              </a>
            @endif
          </div>

          <div class="nb-dropdown-sep"></div>

          <div class="nb-dropdown-section">
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
              @csrf
              <button type="submit" class="nb-dropdown-item danger">
                <span class="dd-icon">🚪</span> Sign Out
              </button>
            </form>
          </div>
        </div>
      </div>
      @endauth
    </div>{{-- end .pmrs-navbar-outer --}}

    {{-- Mobile Drawer Overlay --}}
    @auth
    <div class="nb-drawer-overlay" id="nb-drawer-overlay" onclick="closeDrawer()"></div>
    <aside class="nb-drawer" id="nb-drawer">
      <div class="nb-drawer-header" style="padding:20px; border-bottom:1px solid var(--c-border); background:linear-gradient(135deg,#f8faff,#fff);">
        <img src="{{ asset('logo.png') }}" alt="PMRS" style="height:28px; object-fit:contain;">
      </div>
      {{-- Mobile profile mini-card --}}
      <div style="padding:14px 16px; display:flex; align-items:center; gap:10px; background:#f8fafc; border-bottom:1px solid var(--c-border);">
        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#6C5CE7,#8B5CF6);color:#fff;font-weight:700;font-size:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
        <div>
          <div style="font-size:14px;font-weight:700;color:#111827;">{{ auth()->user()->name }}</div>
          <div style="font-size:11px;color:var(--c-muted);">{{ auth()->user()->isAdmin() ? 'Administrator' : (auth()->user()->isTeacher() ? 'Teacher' : 'Student') }}</div>
        </div>
      </div>
      <nav class="nb-drawer-nav">
        @if(auth()->user()->isAdmin())
          <a href="{{ route('dashboard.admin') }}" class="{{ request()->routeIs('dashboard.*') ? 'active' : '' }}" onclick="closeDrawer()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Dashboard
          </a>
          <a href="{{ route('students.index') }}" class="{{ request()->routeIs('students.*') ? 'active' : '' }}" onclick="closeDrawer()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            Students
          </a>
          <a href="{{ route('subjects.index') }}" class="{{ request()->routeIs('subjects.*') ? 'active' : '' }}" onclick="closeDrawer()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            Subjects
          </a>
          <a href="{{ route('teachers.index') }}" class="{{ request()->routeIs('teachers.*') ? 'active' : '' }}" onclick="closeDrawer()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            Teachers
          </a>
          <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}" onclick="closeDrawer()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            Reports
          </a>
        @elseif(auth()->user()->isTeacher())
          <a href="{{ route('dashboard.teacher') }}" class="{{ request()->routeIs('dashboard.*') ? 'active' : '' }}" onclick="closeDrawer()">Dashboard</a>
          <a href="{{ route('students.index') }}" class="{{ request()->routeIs('students.*') ? 'active' : '' }}" onclick="closeDrawer()">My Students</a>
          <a href="{{ route('performance.index') }}" class="{{ request()->routeIs('performance.*') ? 'active' : '' }}" onclick="closeDrawer()">Performance</a>
          <a href="{{ route('quizzes.index') }}" class="{{ request()->routeIs('quizzes.*') ? 'active' : '' }}" onclick="closeDrawer()">Quizzes</a>
        @elseif(auth()->user()->isStudent())
          <a href="{{ route('dashboard.student') }}" class="{{ request()->routeIs('dashboard.*') ? 'active' : '' }}" onclick="closeDrawer()">Dashboard</a>
          <a href="{{ route('student.progress') }}" class="{{ request()->routeIs('student.progress') ? 'active' : '' }}" onclick="closeDrawer()">My Progress</a>
          <a href="{{ route('student.tasks') }}" class="{{ request()->routeIs('student.tasks') ? 'active' : '' }}" onclick="closeDrawer()">My Tasks</a>
        @endif
      </nav>
      <div class="nb-drawer-footer">
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
          @csrf
          <button type="submit" style="width:100%; display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:10px; border:1px solid rgba(239,68,68,0.18); background:rgba(239,68,68,0.06); color:#dc2626; font-size:13px; font-weight:600; cursor:pointer; transition:all 0.2s;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Sign Out
          </button>
        </form>
      </div>
    </aside>
    @endauth

    {{-- Flash messages --}}
    <div style="padding: 0 32px; margin-top: 20px;">
      @if(session('success'))
        <div class="alert alert-success" id="flash-success">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
          {{ session('success') }}
        </div>
      @endif
      @if(session('error'))
        <div class="alert alert-error" id="flash-error">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
          {{ session('error') }}
        </div>
      @endif
    </div>

    {{-- Page content --}}
    <div class="page-content">
      {{ $slot }}
    </div>

  </div>
</div>

{{-- Chart.js & QR Code --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
  // ── Flash auto-dismiss ──
  setTimeout(() => {
    ['flash-success','flash-error'].forEach(id => {
      const el = document.getElementById(id);
      if (el) { el.style.opacity = '0'; el.style.transition = 'opacity 0.4s'; setTimeout(() => el.remove(), 400); }
    });
  }, 4000);

  // ── Navbar scroll blur ──
  const navbar = document.getElementById('pmrs-navbar');
  window.addEventListener('scroll', () => {
    if (navbar) navbar.classList.toggle('scrolled', window.scrollY > 8);
  }, { passive: true });

  // ── Mobile Drawer ──
  function toggleDrawer() {
    const drawer = document.getElementById('nb-drawer');
    const overlay = document.getElementById('nb-drawer-overlay');
    const btn = document.getElementById('nb-hamburger');
    if (!drawer) return;
    const open = drawer.classList.toggle('open');
    overlay.classList.toggle('open', open);
    btn.classList.toggle('open', open);
    document.body.style.overflow = open ? 'hidden' : '';
  }
  function closeDrawer() {
    const drawer = document.getElementById('nb-drawer');
    const overlay = document.getElementById('nb-drawer-overlay');
    const btn = document.getElementById('nb-hamburger');
    if (!drawer) return;
    drawer.classList.remove('open');
    overlay.classList.remove('open');
    if (btn) btn.classList.remove('open');
    document.body.style.overflow = '';
  }

  // ── Profile dropdown ──
  function toggleProfileMenu(el) {
    const menu = document.getElementById('nb-profile-menu');
    const notif = document.getElementById('nb-notif-menu');
    if (notif) notif.classList.remove('open');
    const isOpen = menu.classList.contains('open');
    menu.classList.toggle('open', !isOpen);
    el.classList.toggle('open', !isOpen);
  }

  // ── Notification dropdown ──
  function toggleNotifMenu() {
    const menu = document.getElementById('nb-notif-menu');
    const profileMenu = document.getElementById('nb-profile-menu');
    const profileBtn = document.getElementById('nb-profile-btn');
    if (profileMenu) profileMenu.classList.remove('open');
    if (profileBtn) profileBtn.classList.remove('open');
    if (menu) menu.classList.toggle('open');
  }

  document.addEventListener('click', (e) => {
    const profileBtn = document.getElementById('nb-profile-btn');
    const profileMenu = document.getElementById('nb-profile-menu');
    const notifWrap = document.getElementById('nb-notif-wrap');
    const notifMenu = document.getElementById('nb-notif-menu');
    if (profileBtn && !profileBtn.contains(e.target)) {
      if (profileMenu) profileMenu.classList.remove('open');
      profileBtn.classList.remove('open');
    }
    if (notifWrap && !notifWrap.contains(e.target)) {
      if (notifMenu) notifMenu.classList.remove('open');
    }
  });

  document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeDrawer(); } });
</script>
@stack('scripts')
</body>
</html>
