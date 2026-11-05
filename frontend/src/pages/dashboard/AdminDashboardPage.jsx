/**
 * ============================================================================
 * pages/dashboard/AdminDashboardPage.jsx — Admin Dashboard Page
 * ============================================================================
 * 99% visual match port of backend/resources/views/dashboard/admin.blade.php.
 * Features:
 *   - Aggregated data fetch via dashboardService.getAdminDashboard()
 *   - Modular presentational component layout
 *   - Full responsiveness across desktop, tablet, and mobile
 * ============================================================================
 */

import React, { useState, useEffect, useCallback } from 'react';
import dashboardService from '@/services/dashboardService';
import ContentSkeleton from '@/components/ui/ContentSkeleton';
import { Link } from 'react-router-dom';
import { useAuthContext } from '@/context/AuthContext';

import DashboardHeader from './components/DashboardHeader';
import InviteCard from './components/InviteCard';
import StatsCards from './components/StatsCards';
import PerformanceTrendChart from './components/PerformanceTrendChart';
import RecentStudentsTable from './components/RecentStudentsTable';
import ActionRequiredAlerts from './components/ActionRequiredAlerts';
import SubjectRankings from './components/SubjectRankings';
import QuickActions from './components/QuickActions';
import AddTeacherModal from './components/AddTeacherModal';

export default function AdminDashboardPage() {
  const { user: signedInUser } = useAuthContext();
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [isAddTeacherOpen, setIsAddTeacherOpen] = useState(false);
  const [notification, setNotification] = useState(null);

  const fetchDashboardData = useCallback(async () => {
    setLoading(true);
    setError(null);
    try {
      const res = await dashboardService.getAdminDashboard();
      setData(res.data);
    } catch (err) {
      console.error('Failed to load admin dashboard data:', err);
      setError('Unable to load dashboard overview. Please try refreshing.');
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    fetchDashboardData();
  }, [fetchDashboardData]);

  const handleTeacherCreated = () => {
    setNotification('Teacher account created successfully!');
    fetchDashboardData();
    setTimeout(() => setNotification(null), 4000);
  };

  if (loading && !data) {
    return <ContentSkeleton />;
  }

  const {
    user = {},
    school = {},
    stats = {},
    recent_students = [],
    alerts = [],
    subject_rankings = [],
    trend_data = [],
  } = data || {};

  return (
    <div className="w-full px-6 py-8 space-y-6">
      <style>{`
        /* Header */
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
        .admin-title { font-family:'Poppins',sans-serif; font-size: 26px; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; margin: 0 0 4px; }
        .admin-subtitle { font-size: 14px; color: #64748b; margin: 0; }

        /* Buttons */
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

        /* Invite Card */
        .inv-card {
          display: flex;
          align-items: stretch;
          border-radius: 18px;
          overflow: hidden;
          border: 1.5px solid #e8e3ff;
          box-shadow: 0 2px 28px rgba(99, 77, 220, 0.09);
          background: #ffffff;
          margin-bottom: 1.5rem;
        }
        .inv-stripe {
          width: 200px; flex-shrink: 0;
          background: linear-gradient(155deg, #5b38c0 0%, #7c5af6 55%, #a78bfa 100%);
          display: flex; flex-direction: column; justify-content: center;
          padding: 2rem 1.5rem; position: relative; overflow: hidden;
        }
        .inv-pill {
          display: inline-flex; align-items: center; gap: 6px;
          background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.28);
          border-radius: 100px; padding: 3px 11px; margin-bottom: 14px; width: fit-content;
        }
        .inv-dot { width: 6px; height: 6px; border-radius: 50%; background: #4ade80; }
        .inv-pill-text { font-size: 10.5px; font-weight: 700; color: rgba(255, 255, 255, 0.92); text-transform: uppercase; }
        .inv-stripe-title { font-size: 22px; font-weight: 800; color: #ffffff; margin: 0 0 6px; }
        .inv-stripe-school { font-size: 11.5px; color: rgba(255, 255, 255, 0.68); margin: 0; }
        .inv-mid { flex: 1; padding: 1.75rem 2rem; display: flex; flex-direction: column; justify-content: center; gap: 16px; border-right: 1.5px solid #ede9fe; }
        .inv-desc { font-size: 13.5px; color: #6b7280; margin: 0; }
        .inv-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .inv-link-box { display: flex; align-items: center; gap: 8px; background: #f8f7ff; border: 1.5px solid #ddd6fe; border-radius: 10px; padding: 9px 13px; min-width: 200px; flex: 1; }
        .inv-link-icon { width: 14px; height: 14px; color: #a78bfa; }
        .inv-link-val { font-size: 12px; color: #4c3d9e; font-weight: 500; font-family: monospace; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .inv-btn-primary { display: inline-flex; align-items: center; gap: 7px; background: linear-gradient(135deg, #6d4ac7, #8b5cf6); color: #fff; border: none; border-radius: 10px; padding: 10px 18px; font-size: 13px; font-weight: 700; cursor: pointer; }
        .inv-btn-secondary { display: inline-flex; align-items: center; gap: 7px; background: #fff; color: #6d4ac7; border: 1.5px solid #ddd6fe; border-radius: 10px; padding: 9px 16px; font-size: 13px; font-weight: 600; cursor: pointer; }
        .inv-qr-panel { width: 200px; flex-shrink: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; padding: 1.5rem 1.25rem; background: #fafafa; }
        .inv-qr-frame { background: #fff; border: 1.5px solid #ede9fe; border-radius: 12px; padding: 10px; }
        .inv-scan-label { font-size: 10.5px; color: #9ca3af; font-weight: 600; text-transform: uppercase; }
        .inv-scan-code { font-size: 10px; color: #c4b5fd; font-weight: 600; font-family: monospace; }

        /* KPI Container */
        .kpi-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 20px; margin-bottom: 28px; }
        .kpi-box { background: #fff; border-radius: 18px; padding: 22px; border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 4px 16px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 16px; position: relative; overflow: hidden; }
        .kpi-box::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px; border-radius: 0 0 18px 18px; opacity: 1; }
        .kpi-primary::after { background: #6366f1; }
        .kpi-success::after { background: #10b981; }
        .kpi-warning::after { background: #f59e0b; }
        .kpi-info::after    { background: #3b82f6; }
        .kpi-danger::after  { background: #ef4444; }
        .kpi-icon-wrap { width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .kpi-icon-wrap svg { width: 22px; height: 22px; }
        .kpi-primary .kpi-icon-wrap { background: #eef2ff; color: #6366f1; }
        .kpi-success .kpi-icon-wrap { background: #ecfdf5; color: #10b981; }
        .kpi-warning .kpi-icon-wrap { background: #fffbeb; color: #f59e0b; }
        .kpi-info    .kpi-icon-wrap { background: #eff6ff; color: #3b82f6; }
        .kpi-danger  .kpi-icon-wrap { background: #fef2f2; color: #ef4444; }
        .kpi-value { font-family:'Poppins',sans-serif; font-size: 28px; font-weight: 800; color: #0f172a; }
        .kpi-label { font-size: 12px; font-weight: 600; color: #64748b; margin-top: 5px; text-transform: uppercase; }

        /* Main Grid */
        .main-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
        @media(max-width: 1024px) { .main-grid { grid-template-columns: 1fr; } }
        @media(max-width: 768px) {
          .inv-card { flex-direction: column; }
          .inv-stripe { width: 100%; }
          .inv-mid { border-right: none; border-top: 1.5px solid #ede9fe; }
          .inv-qr-panel { width: 100%; border-top: 1.5px solid #ede9fe; }
        }

        /* Cards & Tables */
        .premium-card { background: #fff; border-radius: 20px; padding: 24px; border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 4px 16px rgba(0,0,0,0.03); }
        .card-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
        .card-title { font-family:'Poppins',sans-serif; font-size: 16px; font-weight: 800; color: #0f172a; margin: 0 0 3px; }
        .card-subtitle { font-size: 12px; color: #64748b; margin: 0; }
        .btn-quick-action { display: flex; align-items: center; gap: 12px; padding: 14px 16px; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; color: #1e293b; font-weight: 600; font-size: 13px; text-decoration: none; transition: all 0.2s; }
        .btn-quick-action:hover { background: linear-gradient(135deg, #6C5CE7, #8B5CF6); border-color: transparent; color: #fff; }
        .qa-icon { width: 36px; height: 36px; border-radius: 10px; background: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 6px rgba(0,0,0,0.06); }
        .alert-item { background: #fffbeb; padding: 14px 16px; border-radius: 12px; border-left: 3px solid #f59e0b; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .alert-title { font-weight: 700; font-size: 13px; color: #92400e; }
        .alert-desc { font-size: 11.5px; color: #b45309; margin-top: 2px; }
        .btn-review { font-size: 12px; font-weight: 700; color: #fff; background: #d97706; padding: 5px 12px; border-radius: 7px; text-decoration: none; }
        .premium-table { width: 100%; border-collapse: collapse; }
        .premium-table th { text-align: left; padding: 12px 16px; color: #94a3b8; font-size: 11px; text-transform: uppercase; font-weight: 700; border-bottom: 1px solid #f1f5f9; }
        .premium-table td { padding: 14px 16px; border-bottom: 1px solid #f8fafc; vertical-align: middle; }
        .student-avatar { width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #6C5CE7, #8B5CF6); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; flex-shrink: 0; }
        .status-badge { display: inline-flex; padding: 4px 10px; border-radius: 100px; font-size: 11.5px; font-weight: 700; }
        .status-danger  { background: #fef2f2; color: #ef4444; }
        .status-success { background: #ecfdf5; color: #10b981; }
        .status-warning { background: #fffbeb; color: #f59e0b; }
        .status-neutral { background: #f8fafc; color: #64748b; }
        .subject-rank-row { display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f8fafc; }
        .sr-bar-bg { flex: 1; height: 6px; background: #f1f5f9; border-radius: 100px; margin: 0 12px; overflow: hidden; }
        .sr-bar-fill { height: 100%; border-radius: 100px; background: linear-gradient(90deg, #6C5CE7, #8B5CF6); }

        /* Modal */
        .modal-overlay { position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(15,23,42,0.55); backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center; z-index: 1000; opacity:0; visibility:hidden; transition: all 0.3s; }
        .modal-overlay.active { opacity:1; visibility:visible; }
        .modal-content { background: #fff; border-radius: 24px; width: 100%; max-width: 480px; padding: 32px; box-shadow: 0 24px 48px rgba(0,0,0,0.12); }
        .form-control { width: 100%; padding: 11px 16px 11px 40px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 14px; color: #0f172a; background: #fdfdfd; box-sizing: border-box; }
        .form-control:focus { border-color: #6C5CE7; outline:none; background: #fff; }
        .input-icon-wrapper { position: relative; margin-top: 6px; margin-bottom: 16px; }
        .input-icon-wrapper svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 17px; height: 17px; color: #94a3b8; pointer-events: none; }
        .admin-header,.premium-card,.inv-card,.kpi-box { box-shadow:0 12px 34px rgba(36,32,91,.055); }
        .admin-header { padding:32px 36px; border-radius:24px; }
        .kpi-box { border-radius:20px; transition:transform .2s ease,box-shadow .2s ease; }
        .kpi-box:hover { transform:translateY(-4px); box-shadow:0 18px 36px rgba(36,32,91,.11); }
        .premium-card,.inv-card { border-radius:22px; }
        .btn-quick-action { transition:transform .2s ease,background .2s ease,box-shadow .2s ease; }
        .btn-quick-action:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(108,92,231,.2); }
        .trend-controls { display:flex;flex-wrap:wrap;gap:7px;margin-bottom:18px; }
        .trend-filter { border:1px solid #e7e4f5;background:#fff;color:#64748b;border-radius:999px;padding:7px 13px;font-size:12px;font-weight:700;cursor:pointer; }
        .trend-filter:hover,.trend-filter[aria-pressed="true"] { color:#5b38c0;border-color:#b8abf7;background:#f3efff; }
        .trend-graph { display:flex;height:250px;gap:10px; }
        .trend-y-axis { display:flex;flex-direction:column;justify-content:space-between;color:#94a3b8;font-size:10px;font-weight:700;padding:27px 0 25px; }
        .trend-plot { position:relative;flex:1;min-width:0; }
        .trend-plot svg { width:100%;height:100%;overflow:visible; }
        .trend-hit { position:absolute;transform:translate(-50%,-50%);width:27px;height:27px;border-radius:50%;border:0;background:transparent;cursor:pointer; }
        .trend-hit:focus-visible { outline:3px solid #6c5ce7; }
        .trend-tooltip { position:absolute;transform:translate(-50%,-110%);min-width:165px;display:flex;flex-direction:column;gap:3px;padding:9px 12px;background:#172033;color:#fff;border-radius:10px;box-shadow:0 8px 22px rgba(0,0,0,.2);font-size:11px;pointer-events:none;z-index:2; }
        .trend-tooltip span { color:#dbe2ef; }
        .trend-x-labels { display:flex;justify-content:space-between;gap:5px;padding-left:40px;color:#64748b;font-size:10px; }
        .trend-x-labels span { overflow:hidden;text-overflow:ellipsis;white-space:nowrap;min-width:0;max-width:90px; }
        .trend-legend { display:flex;align-items:center;gap:8px;color:#64748b;font-size:12px;font-weight:650;margin-top:18px; }
        .trend-legend-dot { width:10px;height:10px;border-radius:50%;background:#6c5ce7;box-shadow:0 0 0 4px #eeeaff; }
        .trend-empty { display:flex;min-height:240px;align-items:center;justify-content:center;text-align:center;padding:22px;color:#64748b;background:#faf9ff;border:1px dashed #ddd6fe;border-radius:16px;font-size:13px; }
        .plan-widget { background:linear-gradient(145deg,#211d43,#392878);border:1px solid #514395;color:#fff;overflow:hidden;position:relative; }
        .plan-widget::after { content:'';position:absolute;width:190px;height:190px;right:-60px;top:-70px;border-radius:50%;border:28px solid rgba(255,255,255,.055);pointer-events:none; }
        .plan-widget .card-title { color:#fff; }.plan-widget .card-subtitle { color:#c8c2ed; }
        .plan-status { border:1px solid rgba(255,255,255,.23);background:rgba(255,255,255,.1);border-radius:999px;padding:5px 10px;font-size:11px;font-weight:750;text-transform:capitalize; }
        .plan-current { font-size:27px;font-weight:850;letter-spacing:-.04em;margin:14px 0 3px; }
        .plan-tiers { display:flex;flex-wrap:wrap;gap:6px; }.plan-tiers span { font-size:10px;font-weight:700;padding:5px 8px;background:rgba(255,255,255,.09);border-radius:7px; }
        .plan-usage-row { display:flex;justify-content:space-between;gap:12px;font-size:12px;color:#ddd8f8;margin:17px 0 8px; }
        .plan-meter { height:9px;background:rgba(255,255,255,.17);border-radius:99px;overflow:hidden; }.plan-meter span { display:block;height:100%;border-radius:inherit;background:linear-gradient(90deg,#b7a5ff,#76e3d5); }
        .plan-features { font-size:12px;color:#d8d2f4;margin:15px 0 18px;line-height:1.55; }
        .plan-cta { display:inline-flex;justify-content:center;width:100%;margin-top:4px;padding:11px 16px;border-radius:11px;background:#fff;color:#4e36b0;font-size:13px;font-weight:800;text-decoration:none; }
        .plan-cta:hover { color:#4e36b0;transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,0,0,.15); }
        @media(max-width:600px) { .admin-header { padding:23px; } .trend-graph { height:210px; } }
        @media(prefers-reduced-motion:reduce) { .kpi-box,.btn-quick-action { transition:none; } }
      `}</style>

      {notification && (
        <div style={{ background: '#d1fae5', border: '1px solid #a7f3d0', color: '#065f46', padding: '14px 20px', borderRadius: '12px', fontSize: '14px', fontWeight: 600 }}>
          {notification}
        </div>
      )}

      {error && (
        <div style={{ background: '#fef2f2', border: '1px solid #fecaca', color: '#b91c1c', padding: '14px 20px', borderRadius: '12px', fontSize: '14px', fontWeight: 600 }}>
          {error}
        </div>
      )}

      {/* 1. Header */}
      <DashboardHeader
        adminName={user.name}
        onOpenAddTeacher={() => setIsAddTeacherOpen(true)}
      />

      {/* 2. Student Invite Card */}
      <InviteCard school={school} />

      {/* 3. KPI Statistics Cards */}
      <StatsCards stats={stats} />

      {/* 4. Main Grids */}
      <div className="main-grid">
        {/* Left Column */}
        <div className="space-y-6">
          <PerformanceTrendChart trendData={trend_data} />
          <RecentStudentsTable recentStudents={recent_students} />
        </div>

        {/* Right Column */}
        <div className="space-y-6">
          {(() => {
            const plan = data?.subscription || signedInUser?.subscription;
            if (!plan) return null;
            const limit = plan.student_limit;
            const usage = Number(plan.student_usage) || 0;
            return <section className="premium-card plan-widget" aria-label="School subscription">
              <div className="card-header" style={{ marginBottom: 8 }}><div><h3 className="card-title">Your school plan</h3><p className="card-subtitle">Subscription and capacity at a glance</p></div><span className="plan-status">{plan.status}</span></div>
              <div className="plan-current">{plan.plan_name || (plan.status === 'legacy' ? 'Legacy access' : 'Select a plan')}</div>
              <div className="plan-tiers"><span>Starter</span><span>Growth / Professional</span><span>Enterprise</span></div>
              <div className="plan-usage-row"><span>Active students</span><strong>{usage.toLocaleString()}{limit ? ` / ${Number(limit).toLocaleString()}` : ' · no plan limit'}</strong></div>
              {limit && <div className="plan-meter" role="progressbar" aria-label="Student capacity used" aria-valuenow={usage} aria-valuemin="0" aria-valuemax={limit}><span style={{ width: `${Math.min(100, usage * 100 / limit)}%` }} /></div>}
              <p className="plan-features">{plan.features?.length || 0} features available{limit ? ` · ${Math.max(0, limit - usage).toLocaleString()} student places remaining` : ''}</p>
              <Link to="/billing" className="plan-cta">View billing &amp; plans →</Link>
            </section>;
          })()}
          <QuickActions />
          <ActionRequiredAlerts alerts={alerts} />
          <SubjectRankings rankings={subject_rankings} />
        </div>
      </div>

      {/* Add Teacher Modal */}
      <AddTeacherModal
        isOpen={isAddTeacherOpen}
        onClose={() => setIsAddTeacherOpen(false)}
        onSuccess={handleTeacherCreated}
      />
    </div>
  );
}
