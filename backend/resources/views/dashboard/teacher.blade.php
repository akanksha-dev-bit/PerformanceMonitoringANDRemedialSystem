<x-app-layout>
    <x-slot name="title">Teacher Dashboard</x-slot>

    <style>
        .premium-welcome-banner {
            background: linear-gradient(135deg, var(--primary) 0%, #4c3cc7 100%);
            border-radius: var(--radius-lg);
            padding: 40px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
            box-shadow: 0 15px 40px rgba(108,92,231,0.2);
            position: relative;
            overflow: hidden;
        }
        .premium-welcome-banner::after {
            content: ''; position: absolute; right: 0; top: 0; width: 400px; height: 100%;
            background: radial-gradient(circle at top right, rgba(255,255,255,0.15), transparent 70%);
            pointer-events: none;
        }
        .pwb-title { font-family: 'Poppins', sans-serif; font-size: 32px; font-weight: 700; letter-spacing: -0.02em; line-height: 1.2; margin-bottom: 8px; }
        .pwb-subtitle { font-size: 16px; color: rgba(255,255,255,0.8); }
        .pwb-btn { background: #fff; color: var(--primary); padding: 12px 24px; border-radius: 12px; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-decoration: none; position: relative; z-index: 1; }
        .pwb-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.15); color: var(--primary-dark); }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        .premium-kpi {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 32px;
            display: flex;
            align-items: center;
            gap: 24px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .premium-kpi:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.06);
        }
        .pkpi-icon {
            width: 72px; height: 72px; border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            background: var(--kpi-bg); color: var(--kpi-color);
        }
        .pkpi-val { font-family: 'Poppins', sans-serif; font-size: 40px; font-weight: 700; color: #111827; line-height: 1; margin-bottom: 4px; }
        .pkpi-lbl { font-size: 14px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }

        .premium-data-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: 0 10px 40px rgba(0,0,0,0.03);
            overflow: hidden;
            height: 100%;
        }
        .pdc-header {
            padding: 24px 32px;
            border-bottom: 1px solid rgba(0,0,0,0.04);
            display: flex; align-items: center; gap: 16px;
        }
        .pdc-title { font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 700; color: #111827; }
        .pdc-subtitle { font-size: 13px; color: var(--text-muted); }
        
        .premium-table { width: 100%; border-collapse: collapse; }
        .premium-table th { text-align: left; padding: 16px 32px; font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; background: #fdfdfd; border-bottom: 1px solid var(--border); }
        .premium-table td { padding: 16px 32px; font-size: 14px; border-bottom: 1px solid rgba(0,0,0,0.03); vertical-align: middle; }
        .premium-table tr:last-child td { border-bottom: none; }
        .premium-table tr:hover { background: rgba(108,92,231,0.02); }

        .st-cell { display: flex; align-items: center; gap: 16px; }
        .st-avatar { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; background: var(--primary-light); color: var(--primary); }
        .st-name { font-weight: 600; color: #111827; font-size: 15px; }

        .assignment-item {
            background: #fdfdfd;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s;
        }
        .assignment-item:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(108,92,231,0.08);
            transform: translateX(4px);
        }

        @media (max-width: 992px) {
            .premium-welcome-banner { flex-direction: column; align-items: flex-start; gap: 24px; padding: 32px; }
        }
    </style>

    <div class="premium-welcome-banner">
        <div>
            <h2 class="pwb-title">Welcome back, {{ auth()->user()->name }}</h2>
            <p class="pwb-subtitle">Here is your class overview and performance insights for today.</p>
        </div>
        <div>
            <a href="{{ route('marks.index') }}" class="pwb-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                Manage Marks
            </a>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="kpi-grid">
        <div class="premium-kpi" style="--kpi-color: var(--primary); --kpi-bg: var(--primary-light);">
            <div class="pkpi-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <div class="pkpi-val">{{ $assignedStudentsCount }}</div>
                <div class="pkpi-lbl">My Students</div>
            </div>
        </div>
        <div class="premium-kpi" style="--kpi-color: var(--success); --kpi-bg: rgba(0,196,140,0.1);">
            <div class="pkpi-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            </div>
            <div>
                <div class="pkpi-val">{{ $assignedClassesCount }}</div>
                <div class="pkpi-lbl">Classes Assigned</div>
            </div>
        </div>
    </div>

    {{-- Data Grids --}}
    <div class="chart-grid">
        <div class="premium-data-card">
            <div class="pdc-header">
                <div>
                    <h3 class="pdc-title">Recent Students</h3>
                    <p class="pdc-subtitle">Students belonging to your assigned classes</p>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Student Info</th>
                            <th>Class/Sec</th>
                            <th>Performance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentStudents as $student)
                        <tr>
                            <td>
                                <div class="st-cell">
                                    <div class="st-avatar">{{ strtoupper(substr($student->user->name ?? 'U', 0, 1)) }}</div>
                                    <div class="st-name">{{ $student->user->name ?? '-' }}</div>
                                </div>
                            </td>
                            <td><span class="badge badge-muted">{{ $student->class }} {{ $student->section }}</span></td>
                            <td>
                                <span class="badge" style="background: {{ $student->performance_color }}15; color: {{ $student->performance_color }}; padding:6px 12px;">
                                    {{ $student->performance_label }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 48px; color: var(--text-muted);">
                                No students assigned yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="premium-data-card">
            <div class="pdc-header">
                <h3 class="pdc-title">My Assignments</h3>
            </div>
            <div style="padding: 24px 32px; display:flex; flex-direction:column; gap:16px;">
                @forelse($assignments as $assignment)
                <div class="assignment-item">
                    <div style="font-weight:600; color:#111827; font-size:15px;">Class {{ $assignment->class }}</div>
                    <div class="badge badge-primary" style="padding:6px 12px;">Section {{ $assignment->section }}</div>
                </div>
                @empty
                <div style="text-align:center; padding:32px 0;">
                    <div style="width:64px; height:64px; border-radius:50%; background:#f1f5f9; color:var(--text-muted); display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p style="color:var(--text-muted); font-size:14px;">No specific classes assigned to you right now.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
