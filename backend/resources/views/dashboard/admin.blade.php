<x-app-layout>
    <x-slot name="title">Admin Dashboard</x-slot>

    @push('styles')
    <style>
        /* Overall Page Styles */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 32px;
            flex-wrap: wrap;
            gap: 16px;
        }
        .dashboard-title {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin: 0 0 4px 0;
        }
        .dashboard-subtitle {
            font-size: 15px;
            color: #64748b;
            margin: 0;
        }

        /* Highly Visible Solid Buttons */
        .btn-solid-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(135deg, #6C5CE7, #5A4BD6);
            color: #ffffff;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(108, 92, 231, 0.3);
            border: none;
            cursor: pointer;
        }
        .btn-solid-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(108, 92, 231, 0.4);
            color: #ffffff;
        }
        
        .btn-solid-dark {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: #0f172a;
            color: #ffffff;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
            border: none;
            cursor: pointer;
        }
        .btn-solid-dark:hover {
            background: #1e293b;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.3);
            color: #ffffff;
        }

        /* Invite Banner */
        .invite-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-radius: 20px;
            padding: 32px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 24px;
            margin-bottom: 32px;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.3);
            position: relative;
            overflow: hidden;
        }
        .invite-banner::before {
            content: '';
            position: absolute;
            top: -50%; right: -10%;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(108, 92, 231, 0.4) 0%, transparent 70%);
            border-radius: 50%;
        }

        /* KPI Grid */
        .kpi-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }
        .kpi-box {
            background: #fff;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 20px -2px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.02);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .kpi-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px -4px rgba(0,0,0,0.08);
        }
        
        .kpi-icon-wrap {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .kpi-icon-wrap svg { width: 24px; height: 24px; }
        
        .kpi-value { font-size: 26px; font-weight: 800; color: #0f172a; line-height: 1.1; }
        .kpi-label { font-size: 13px; font-weight: 600; color: #64748b; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.05em;}

        .kpi-primary .kpi-icon-wrap { background: #eef2ff; color: #6366f1; }
        .kpi-success .kpi-icon-wrap { background: #ecfdf5; color: #10b981; }
        .kpi-warning .kpi-icon-wrap { background: #fffbeb; color: #f59e0b; }
        .kpi-info .kpi-icon-wrap { background: #eff6ff; color: #3b82f6; }
        .kpi-danger .kpi-icon-wrap { background: #fef2f2; color: #ef4444; }

        /* Main Grid Layout */
        .main-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }
        @media(max-width: 1024px) {
            .main-grid { grid-template-columns: 1fr; }
        }

        /* Premium Cards */
        .premium-card {
            background: #fff;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 20px -2px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.02);
            height: 100%;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
        }
        .card-title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .card-subtitle {
            font-size: 13px;
            color: #64748b;
        }

        /* Quick Action Buttons (Strong Colors & High Contrast) */
        .btn-quick-action {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 16px;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            color: #1e293b;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-quick-action:hover {
            background: #1e293b;
            border-color: #1e293b;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(15, 23, 42, 0.15);
        }

        /* Alerts */
        .alert-item {
            background: #fffbeb;
            padding: 16px;
            border-radius: 12px;
            border-left: 4px solid #f59e0b;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .alert-title { font-weight: 700; font-size: 14px; color: #92400e; }
        .alert-desc { font-size: 12px; color: #b45309; margin-top: 4px; }
        .btn-review { 
            font-size: 12px; font-weight: 700; color: #fff; background: #d97706; 
            padding: 6px 12px; border-radius: 6px; text-decoration: none; transition: 0.2s; 
        }
        .btn-review:hover { background: #b45309; }

        /* Tables */
        .premium-table { width: 100%; border-collapse: collapse; }
        .premium-table th { text-align: left; padding: 12px 16px; color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; border-bottom: 1px solid #e2e8f0; }
        .premium-table td { padding: 16px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        
        .student-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, #6C5CE7, #5A4BD6);
            color: white; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 12px;
        }

        .status-badge {
            display: inline-flex; padding: 4px 10px; border-radius: 100px;
            font-size: 12px; font-weight: 700; border: 1px solid transparent;
        }
        .status-danger { background: #fef2f2; color: #ef4444; border-color: #fecaca; }
        .status-success { background: #ecfdf5; color: #10b981; border-color: #a7f3d0; }
        .status-warning { background: #fffbeb; color: #f59e0b; border-color: #fde68a; }

        /* Modal Overrides */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px);
            display: flex; align-items: center; justify-content: center;
            z-index: 1000; opacity: 0; visibility: hidden; transition: all 0.3s;
        }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-content {
            background: #fff; border-radius: 24px; width: 100%; max-width: 500px;
            padding: 32px; box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            transform: translateY(20px); transition: all 0.3s;
        }
        .modal-overlay.active .modal-content { transform: translateY(0); }
        .form-control {
            width: 100%; padding: 12px 16px; padding-left: 40px; border: 1px solid #cbd5e1;
            border-radius: 12px; font-size: 14px; transition: 0.2s;
        }
        .form-control:focus { border-color: #6C5CE7; outline: none; box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1); }
        .input-icon-wrapper { position: relative; margin-top: 8px; margin-bottom: 16px; }
        .input-icon-wrapper svg {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            width: 18px; height: 18px; color: #94a3b8;
        }
        
    </style>
    @endpush

    <div class="dashboard-header">
        <div>
            <h2 class="dashboard-title">Welcome back, {{ auth()->user()->name }}</h2>
            <p class="dashboard-subtitle">Here is what's happening at your academy today.</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="btn-solid-dark" onclick="openTeacherModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5c-1.1 0-2 .9-2 2v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                Add Teacher
            </button>
            <a href="{{ route('students.create') }}" class="btn-solid-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/><line x1="12" y1="11" x2="12" y2="17"/><line x1="9" y1="14" x2="15" y2="14"/></svg>
                Add Student
            </a>
        </div>
    </div>

    {{-- Invite Section --}}
    <div class="invite-banner">
        <div style="flex: 1; min-width: 300px; position: relative; z-index: 2;">
            <h3 class="font-bold text-2xl mb-2">Invite Students & Teachers</h3>
            <p class="text-sm text-slate-300 mb-6" style="max-width: 500px; line-height: 1.6;">Share this secure link or QR code with your organization members for them to automatically join <strong>{{ auth()->user()->school->name ?? 'your school' }}</strong>.</p>
            
            <div class="flex items-center gap-3">
                <input type="text" value="{{ $inviteLink }}" id="inviteLinkInput" readonly style="flex: 1; max-width: 360px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: #fff; padding: 12px 16px; border-radius: 12px; font-size: 14px; outline: none;">
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
                <div class="card-header"><h3 class="card-title">Quick Actions</h3></div>
                <div style="display: grid; grid-template-columns: 1fr; gap: 16px;">
                    <a href="{{ route('marks.create') }}" class="btn-quick-action">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Upload Marks
                    </a>
                    <a href="{{ route('remedial.index') }}" class="btn-quick-action">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                        Assign Remedial Tasks
                    </a>
                    <a href="{{ route('subjects.index') }}" class="btn-quick-action">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                        Manage Subjects
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
                <div class="card-header"><h3 class="card-title">Subject Rankings</h3></div>
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @forelse($subjectAvgs->sortByDesc('avg')->take(4) as $idx => $s)
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 28px; height: 28px; border-radius: 8px; background: {{ $idx === 0 ? 'rgba(108, 92, 231, 0.1)' : '#f1f5f9' }}; color: {{ $idx === 0 ? '#6C5CE7' : '#64748b' }}; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800;">
                                    #{{ $idx + 1 }}
                                </div>
                                <span style="font-size: 14px; font-weight: 600; color: #1e293b;">{{ $s['subject'] }}</span>
                            </div>
                            <span style="font-size: 14px; font-weight: 800; color: {{ $idx === 0 ? '#6C5CE7' : '#475569' }};">{{ number_format($s['avg'], 1) }}%</span>
                        </div>
                    @empty
                        <p style="color: #64748b; font-size: 13px; text-align: center;">No subject data available.</p>
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
