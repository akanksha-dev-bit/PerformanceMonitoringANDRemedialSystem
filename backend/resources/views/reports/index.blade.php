<x-app-layout>
  <x-slot name="title">Reports & Analytics</x-slot>

  @push('styles')
  <style>
    /* Premium Page Header */
    .reports-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      margin-bottom: 32px;
    }
    .reports-title {
      font-size: 24px;
      font-weight: 800;
      color: #0f172a;
      letter-spacing: -0.02em;
      margin: 0 0 4px 0;
    }
    .reports-subtitle {
      font-size: 14px;
      color: #64748b;
      margin: 0;
    }

    /* Action Buttons */
    .btn-action {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 18px;
      border-radius: 12px;
      font-size: 13px;
      font-weight: 600;
      transition: all 0.2s;
      border: 1px solid transparent;
      cursor: pointer;
    }
    .btn-export {
      background: #f0fdf4;
      color: #15803d;
      border-color: #bbf7d0;
    }
    .btn-export:hover {
      background: #dcfce7;
      transform: translateY(-1px);
    }
    .btn-print {
      background: #f8fafc;
      color: #475569;
      border-color: #e2e8f0;
    }
    .btn-print:hover {
      background: #f1f5f9;
      color: #0f172a;
      transform: translateY(-1px);
    }

    /* KPI Grid */
    .kpi-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 24px;
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
      gap: 20px;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .kpi-box:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 30px -4px rgba(0,0,0,0.08);
    }
    .kpi-icon-wrap {
      width: 56px;
      height: 56px;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .kpi-icon-wrap svg {
      width: 28px;
      height: 28px;
    }
    .kpi-details {
      display: flex;
      flex-direction: column;
    }
    .kpi-value {
      font-size: 28px;
      font-weight: 800;
      color: #0f172a;
      line-height: 1.2;
    }
    .kpi-label {
      font-size: 13px;
      font-weight: 600;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-top: 2px;
    }

    /* KPI Colors */
    .kpi-primary .kpi-icon-wrap { background: #eef2ff; color: #6366f1; }
    .kpi-danger .kpi-icon-wrap { background: #fef2f2; color: #ef4444; }
    .kpi-warning .kpi-icon-wrap { background: #fffbeb; color: #f59e0b; }
    .kpi-success .kpi-icon-wrap { background: #ecfdf5; color: #10b981; }

    /* Layout Grids */
    .content-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
      margin-bottom: 24px;
    }
    @media(max-width: 1024px) {
      .content-grid { grid-template-columns: 1fr; }
    }

    /* Cards */
    .premium-card {
      background: #fff;
      border-radius: 20px;
      padding: 28px;
      box-shadow: 0 4px 20px -2px rgba(0,0,0,0.04);
      border: 1px solid rgba(0,0,0,0.02);
      display: flex;
      flex-direction: column;
    }
    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
    }
    .card-title {
      font-size: 16px;
      font-weight: 700;
      color: #0f172a;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    /* Tables */
    .premium-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
    }
    .premium-table th {
      text-align: left;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: #64748b;
      padding: 16px 12px;
      border-bottom: 1px solid #e2e8f0;
      background: #f8fafc;
    }
    .premium-table th:first-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
    .premium-table th:last-child { border-top-right-radius: 12px; border-bottom-right-radius: 12px; }
    
    .premium-table td {
      padding: 16px 12px;
      font-size: 14px;
      color: #334155;
      border-bottom: 1px solid #f1f5f9;
      vertical-align: middle;
    }
    .premium-table tr:last-child td { border-bottom: none; }
    .premium-table tbody tr:hover td {
      background: #f8fafc;
    }

    /* Badges */
    .status-badge {
      display: inline-flex;
      align-items: center;
      padding: 4px 10px;
      border-radius: 100px;
      font-size: 12px;
      font-weight: 600;
    }
    .status-danger { background: #fef2f2; color: #ef4444; border: 1px solid #fecaca; }
    .status-success { background: #ecfdf5; color: #10b981; border: 1px solid #a7f3d0; }
    .status-warning { background: #fffbeb; color: #f59e0b; border: 1px solid #fde68a; }
    .status-neutral { background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }

    /* Student Avatar */
    .student-avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: linear-gradient(135deg, #6C5CE7, #5A4BD6);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 12px;
      box-shadow: 0 2px 4px rgba(108, 92, 231, 0.3);
    }
  </style>
  @endpush

  <div class="reports-header">
    <div>
      <h2 class="reports-title">Reports & Analytics</h2>
      <p class="reports-subtitle">Comprehensive overview of institutional performance</p>
    </div>
    <div style="display:flex; gap:12px;">
      <button onclick="exportTableToCSV('pmrs-report.csv')" class="btn-action btn-export">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        Export CSV
      </button>
      <button onclick="window.print()" class="btn-action btn-print">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        Print Report
      </button>
    </div>
  </div>

  {{-- Top KPIs --}}
  <div class="kpi-container">
    <div class="kpi-box kpi-primary">
      <div class="kpi-icon-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      </div>
      <div class="kpi-details">
        <div class="kpi-value">{{ $summary['total_students'] }}</div>
        <div class="kpi-label">Total Students</div>
      </div>
    </div>
    <div class="kpi-box kpi-danger">
      <div class="kpi-icon-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      </div>
      <div class="kpi-details">
        <div class="kpi-value">{{ $summary['slow_learners'] }}</div>
        <div class="kpi-label">Slow Learners</div>
      </div>
    </div>
    <div class="kpi-box kpi-warning">
      <div class="kpi-icon-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
      <div class="kpi-details">
        <div class="kpi-value">{{ $summary['at_risk'] }}</div>
        <div class="kpi-label">At Risk Students</div>
      </div>
    </div>
    <div class="kpi-box kpi-success">
      <div class="kpi-icon-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <div class="kpi-details">
        <div class="kpi-value">{{ $summary['performing_well'] }}</div>
        <div class="kpi-label">Performing Well</div>
      </div>
    </div>
  </div>

  {{-- Chart and Tables Layout --}}
  <div class="content-grid">
    
    {{-- Class Breakdown Chart --}}
    <div class="premium-card">
      <div class="card-header">
        <div class="card-title">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
          Performance Distribution
        </div>
      </div>
      <div style="flex:1; min-height: 320px; position: relative; width:100%;">
        <canvas id="classBreakdownChart"></canvas>
      </div>
    </div>

    {{-- Class Breakdown Table --}}
    <div class="premium-card" style="padding:0; overflow:hidden;">
      <div class="card-header" style="padding: 24px 28px 0 28px; margin-bottom: 16px;">
        <div class="card-title">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
          Class-wise Analytics
        </div>
      </div>
      <div style="overflow-x: auto; padding: 0 20px 20px 20px;">
        <table class="premium-table" id="reportTable">
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
            <tr>
              <td style="font-weight:700; color:#0f172a;">Class {{ $row['class'] }}</td>
              <td style="font-weight:600;">{{ $row['total'] }}</td>
              <td><span class="status-badge status-danger">{{ $row['slow'] }}</span></td>
              <td><span class="status-badge status-success">{{ $row['good'] }}</span></td>
              <td>
                @php $pct = $row['total'] > 0 ? round($row['slow']/$row['total']*100,1) : 0; @endphp
                <span class="status-badge {{ $pct > 30 ? 'status-danger' : ($pct > 15 ? 'status-warning' : 'status-success') }}">
                  {{ $pct }}%
                </span>
              </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center; padding:32px; color:#94a3b8;">No analytical data available.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Slow Learners List --}}
  <div class="premium-card" style="padding:0; overflow:hidden;">
    <div class="card-header" style="padding: 24px 28px 0 28px; margin-bottom: 16px;">
      <div class="card-title" style="color: #ef4444;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        Critical Attention Required: Slow Learners
      </div>
    </div>
    <div style="overflow-x: auto;">
      <table class="premium-table">
        <thead>
          <tr>
            <th style="padding-left: 28px;">Student Profile</th>
            <th>Roll No</th>
            <th>Class</th>
            <th>Average %</th>
            <th style="padding-right: 28px;">Remedial Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($slowLearners as $student)
          <tr>
            <td style="padding-left: 28px;">
              <div style="display:flex; align-items:center; gap:12px;">
                <div class="student-avatar">{{ strtoupper(substr($student->name, 0, 2)) }}</div>
                <div>
                  <div style="font-weight:700; color:#0f172a;">{{ $student->name }}</div>
                  <div style="font-size:12px; color:#64748b;">{{ $student->email }}</div>
                </div>
              </div>
            </td>
            <td><span class="status-badge status-neutral">{{ $student->roll_no }}</span></td>
            <td style="font-weight:500;">Class {{ $student->class }}{{ $student->section ? '-'.$student->section : '' }}</td>
            <td>
              <span style="font-weight:700; color:#ef4444; font-size:16px;">{{ $student->average_percentage }}%</span>
            </td>
            <td style="padding-right: 28px;">
              @if($student->remedialActions->count() > 0)
                <span class="status-badge status-warning">{{ $student->remedialActions->count() }} Active Tasks</span>
              @else
                <span class="status-badge status-danger">No Remedials Assigned</span>
              @endif
            </td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center; padding:40px; color:#94a3b8; font-weight:500;">All students are performing well. No critical attention needed.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Export CSV functionality
    function exportTableToCSV(filename) {
      let csv = [];
      let rows = document.querySelectorAll("#reportTable tr");
      
      for (let i = 0; i < rows.length; i++) {
        let row = [], cols = rows[i].querySelectorAll("td, th");
        for (let j = 0; j < cols.length; j++) row.push(cols[j].innerText.replace(/,/g, '').trim());
        csv.push(row.join(","));
      }

      let csvFile = new Blob([csv.join("\n")], {type: "text/csv"});
      let downloadLink = document.createElement("a");
      downloadLink.download = filename;
      downloadLink.href = window.URL.createObjectURL(csvFile);
      downloadLink.style.display = "none";
      document.body.appendChild(downloadLink);
      downloadLink.click();
      document.body.removeChild(downloadLink);
    }

    // Chart.js Visualization - Premium UI
    window.addEventListener('DOMContentLoaded', function() {
      var ctx = document.getElementById('classBreakdownChart');
      if(ctx) {
        var rawData = {!! json_encode($classBreakdown) !!};
        var labels = rawData.map(d => 'Class ' + d.class);
        var goodData = rawData.map(d => d.good);
        var slowData = rawData.map(d => d.slow);

        Chart.defaults.font.family = "'Inter', 'Segoe UI', Roboto, sans-serif";
        Chart.defaults.color = '#64748b';

        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [
              {
                label: 'Performing Well',
                data: goodData,
                backgroundColor: '#10b981', // emerald-500
                borderRadius: {topLeft: 6, topRight: 6, bottomLeft: 6, bottomRight: 6},
                borderSkipped: false,
                barPercentage: 0.6,
              },
              {
                label: 'Slow Learners',
                data: slowData,
                backgroundColor: '#ef4444', // red-500
                borderRadius: {topLeft: 6, topRight: 6, bottomLeft: 6, bottomRight: 6},
                borderSkipped: false,
                barPercentage: 0.6,
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
              mode: 'index',
              intersect: false,
            },
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  usePointStyle: true,
                  padding: 20,
                  font: { weight: '600' }
                }
              },
              tooltip: {
                backgroundColor: 'rgba(15, 23, 42, 0.95)',
                titleFont: { size: 13, weight: '700' },
                bodyFont: { size: 13 },
                padding: 12,
                cornerRadius: 8,
                displayColors: true,
                boxPadding: 4
              }
            },
            scales: {
              x: { 
                stacked: true, 
                grid: { display: false, drawBorder: false }
              },
              y: { 
                stacked: true, 
                beginAtZero: true, 
                grid: { 
                  color: '#f1f5f9',
                  drawBorder: false,
                  borderDash: [4, 4] 
                },
                border: { display: false }
              }
            }
          }
        });
      }
    });
  </script>
  @endpush
</x-app-layout>
