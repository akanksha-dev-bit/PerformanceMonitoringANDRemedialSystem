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

        /* ── Invite Banner ── */
        .invite-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #312e81 100%);
            border-radius: 22px; padding: 30px 32px; color: #fff;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 24px; margin-bottom: 28px;
            box-shadow: 0 12px 32px -4px rgba(15,23,42,0.3);
            position: relative; overflow: hidden;
        }
        .invite-banner::before {
            content: ''; position: absolute; top: -60%; right: -5%;
            width: 320px; height: 320px; border-radius: 50%;
            background: radial-gradient(circle, rgba(108,92,231,0.45) 0%, transparent 70%);
        }
        .invite-banner::after {
            content: ''; position: absolute; bottom: -40%; left: 20%;
            width: 180px; height: 180px; border-radius: 50%;
            background: radial-gradient(circle, rgba(139,92,246,0.25) 0%, transparent 70%);
        }
        .invite-input {
            flex: 1; max-width: 360px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.18);
            color: #fff; padding: 11px 16px;
            border-radius: 12px; font-size: 13px; outline: none;
            transition: border-color 0.2s;
        }
        .invite-input:focus { border-color: rgba(139,92,246,0.6); }

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
    <div class="invite-banner">
        <div style="flex:1;min-width:280px;position:relative;z-index:2;">
            <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);border-radius:100px;padding:4px 12px;margin-bottom:14px;">
                <span style="width:7px;height:7px;border-radius:50%;background:#10b981;"></span>
                <span style="font-size:12px;font-weight:600;color:rgba(255,255,255,0.85);">School Invite Portal</span>
            </div>
            <h3 style="font-family:'Poppins',sans-serif;font-weight:800;font-size:22px;margin:0 0 8px;">Invite Students & Teachers</h3>
            <p style="font-size:13px;color:rgba(255,255,255,0.7);margin:0 0 20px;max-width:460px;line-height:1.7;">Share this link with your organization to automatically join <strong style="color:#fff;">{{ auth()->user()->school->name ?? 'your school' }}</strong>.</p>
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <input type="text" value="{{ $inviteLink }}" id="inviteLinkInput" readonly style="flex:1;max-width:360px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:#fff;padding:11px 16px;border-radius:12px;font-size:13px;outline:none;">
                <button class="btn-solid-primary" onclick="copyInviteLink()">Copy Link</button>
                <input type="hidden" value="{{ $schoolCode }}" id="schoolCodeInput">
            </div>
        </div>
        
        <div style="background: #fff; padding: 12px; border-radius: 16px; display: flex; align-items: center; justify-content: center; position: relative; z-index: 2;" id="qrcode-container">
            {{-- QR Code injected by JS --}}
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
                <div style="height: 300px; width: 100%; position: relative;">
                    <canvas id="performanceChart"></canvas>
                </div>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        // Generate QR Code dynamically
        window.addEventListener('DOMContentLoaded', function() {
            var inviteLink = "{{ $inviteLink }}";
            new QRCode(document.getElementById("qrcode-container"), {
                text: inviteLink,
                width: 100,
                height: 100,
                colorDark : "#0f172a",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.L
            });
        });

        function copyInviteLink() {
            var input = document.getElementById("inviteLinkInput");
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value);
            
            // Temporary button state change
            let btn = event.currentTarget;
            let originalText = btn.innerText;
            btn.innerText = "Copied!";
            btn.style.background = "#10b981";
            setTimeout(() => { 
                btn.innerText = originalText; 
                btn.style.background = ""; 
            }, 2000);
        }

        // Performance Trend Chart - Premium UI
        window.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('performanceChart');
            if(ctx) {
                var trendData = {!! json_encode($trendData ?? []) !!};
                var labels = trendData.map(d => d.date || d.label || d.assessment_name || 'Assessment');
                var dataPoints = trendData.map(d => d.avg_score || d.value || d.score || 0);
                
                // Fallback dummy data if empty
                if(dataPoints.length === 0) {
                    labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May'];
                    dataPoints = [65, 72, 68, 81, 85];
                }

                Chart.defaults.font.family = "'Inter', 'Segoe UI', Roboto, sans-serif";
                Chart.defaults.color = '#64748b';

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Average Score (%)',
                            data: dataPoints,
                            borderColor: '#6C5CE7',
                            backgroundColor: 'rgba(108, 92, 231, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#6C5CE7',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                titleFont: { size: 13, weight: '700' },
                                bodyFont: { size: 13 },
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: false
                            }
                        },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                max: 100, 
                                grid: { color: '#f1f5f9', borderDash: [4, 4], drawBorder: false },
                                border: { display: false }
                            },
                            x: { 
                                grid: { display: false, drawBorder: false } 
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
