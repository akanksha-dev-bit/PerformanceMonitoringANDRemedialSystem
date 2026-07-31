import React, { useState, useEffect } from 'react';
import { Link, useLocation } from 'react-router-dom';
import attendanceService from '@/services/attendanceService';
import Loader from '@/components/ui/Loader';

export default function AttendancePage() {
  const location = useLocation();
  const [scheduleData, setScheduleData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [flashSuccess, setFlashSuccess] = useState(location.state?.success || null);

  useEffect(() => {
    fetchSchedule();
  }, []);

  const fetchSchedule = async () => {
    setLoading(true);
    try {
      const response = await attendanceService.getTodaySchedule();
      setScheduleData(response.data);
    } catch (err) {
      console.error('Error fetching attendance schedule:', err);
    } finally {
      setLoading(false);
    }
  };

  const formatTime = (timeStr) => {
    if (!timeStr) return '';
    try {
      const [hours, minutes] = timeStr.split(':');
      let h = parseInt(hours, 10);
      const ampm = h >= 12 ? 'PM' : 'AM';
      h = h % 12 || 12;
      return `${h}:${minutes} ${ampm}`;
    } catch {
      return timeStr;
    }
  };

  if (loading) {
    return <Loader visible />;
  }

  const todaySlots = scheduleData?.todaySlots || [];
  const completedSessionKeys = scheduleData?.completedSessionKeys || [];
  const today = scheduleData?.today || new Date().toLocaleDateString('en-US', { weekday: 'long' });

  const total = todaySlots.length;
  const marked = todaySlots.filter(s => completedSessionKeys.includes(s.period_number)).length;
  const pending = total - marked;
  const pct = total > 0 ? Math.round((marked / total) * 100) : 0;
  const allDone = total > 0 && marked === total;

  return (
    <div className="w-full max-w-[1600px] mx-auto px-4 md:px-8 py-8">
      {/* Blade visual parity CSS */}
      <style>{`
        .at-wrap { font-family: 'Inter', system-ui, -apple-system, sans-serif; color: #1e293b; }

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
          font-size: 24px;
          font-weight: 800;
          color: #0f172a;
          letter-spacing: -0.02em;
          margin: 0 0 3px;
          line-height: 1.2;
        }
        .at-subtitle { font-size: 13.5px; color: #64748b; margin: 0; }

        .at-summary {
          background: #ffffff;
          border: 1px solid #f1f5f9;
          border-radius: 18px;
          padding: 20px 24px;
          display: flex;
          align-items: center;
          gap: 20px;
          margin-bottom: 24px;
          flex-wrap: wrap;
          box-shadow: 0 4px 20px -2px rgba(0,0,0,0.03);
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
          font-weight: 700;
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
        .at-summary-track-wrap { flex: 1; min-width: 180px; }
        .at-summary-track-label {
          display: flex;
          justify-content: space-between;
          font-size: 12.5px;
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
          font-size: 11.5px;
          color: #94a3b8;
          margin-top: 6px;
          display: flex;
          align-items: center;
          gap: 5px;
        }

        .at-flash {
          display: flex;
          align-items: center;
          gap: 10px;
          padding: 14px 18px;
          background: #f0fdf4;
          border: 1px solid #bbf7d0;
          border-radius: 14px;
          font-size: 13.5px;
          font-weight: 600;
          color: #15803d;
          margin-bottom: 24px;
        }

        .at-grid {
          display: grid;
          grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
          gap: 18px;
        }

        .at-card {
          background: #ffffff;
          border: 1px solid #f1f5f9;
          border-radius: 20px;
          overflow: hidden;
          display: flex;
          flex-direction: column;
          transition: box-shadow 0.2s, transform 0.2s, border-color 0.2s;
          position: relative;
          box-shadow: 0 4px 14px rgba(0,0,0,0.03);
        }
        .at-card:hover {
          transform: translateY(-3px);
          box-shadow: 0 12px 30px -4px rgba(0,0,0,0.08);
          border-color: #e0e7ff;
        }
        .at-card-stripe {
          height: 4px;
          width: 100%;
          flex-shrink: 0;
        }
        .stripe-pending { background: linear-gradient(90deg, #4f46e5, #7c3aed); }
        .stripe-done    { background: linear-gradient(90deg, #10b981, #059669); }

        .at-card-body { padding: 20px 22px; flex: 1; display: flex; flex-direction: column; }

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
        .pill-pending { background: #fff7ed; color: #c2410c; }
        .pill-done    { background: #d1fae5; color: #065f46; }
        .at-status-dot {
          width: 6px; height: 6px;
          border-radius: 50%;
          flex-shrink: 0;
        }

        .at-class-name {
          font-size: 18px;
          font-weight: 800;
          color: #0f172a;
          margin: 0 0 4px;
          line-height: 1.2;
        }
        .at-subject {
          font-size: 13.5px;
          font-weight: 600;
          color: #4f46e5;
          margin-bottom: 14px;
        }

        .at-card-meta {
          display: flex;
          flex-wrap: wrap;
          gap: 14px;
          margin-bottom: 18px;
        }
        .at-meta-item {
          display: flex;
          align-items: center;
          gap: 6px;
          font-size: 12.5px;
          font-weight: 500;
          color: #64748b;
        }

        .at-card-btn {
          display: flex;
          align-items: center;
          justify-content: center;
          gap: 8px;
          padding: 12px 20px;
          border-radius: 12px;
          font-size: 13.5px;
          font-weight: 700;
          text-decoration: none;
          border: none;
          cursor: pointer;
          transition: all 0.2s;
          width: 100%;
          margin-top: auto;
        }
        .btn-mark {
          background: #4f46e5;
          color: #ffffff;
          box-shadow: 0 4px 14px -2px rgba(79,70,229,0.38);
        }
        .btn-mark:hover {
          background: #4338ca;
          color: #ffffff;
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

        .at-empty {
          grid-column: 1 / -1;
          background: #ffffff;
          border: 1px solid #f1f5f9;
          border-radius: 20px;
          padding: 64px 32px;
          text-align: center;
          box-shadow: 0 4px 14px rgba(0,0,0,0.02);
        }
        .at-empty-icon {
          width: 64px; height: 64px;
          border-radius: 20px;
          background: #f8fafc;
          display: flex; align-items: center; justify-content: center;
          color: #cbd5e1;
          margin: 0 auto 16px;
        }
        .at-empty-title { font-size: 17px; font-weight: 800; color: #0f172a; margin-bottom: 6px; }
        .at-empty-desc  { font-size: 13.5px; color: #64748b; }
      `}</style>

      <div className="at-wrap">
        {/* Page Header */}
        <div className="at-header">
          <div>
            <div className="at-eyebrow">
              <span className="at-eyebrow-dot"></span>
              {today}
            </div>
            <h2 className="at-title">Attendance Workspace</h2>
            <p className="at-subtitle">Mark today's scheduled periods — all in one place</p>
          </div>
        </div>

        {/* Progress Summary */}
        {total > 0 && (
          <div className="at-summary">
            <div className="at-summary-stat">
              <div className="at-summary-val">{total}</div>
              <div className="at-summary-lbl">Total</div>
            </div>
            <div className="at-summary-divider"></div>
            <div className="at-summary-stat">
              <div className="at-summary-val" style={{ color: '#10b981' }}>{marked}</div>
              <div className="at-summary-lbl">Marked</div>
            </div>
            <div className="at-summary-divider"></div>
            <div className="at-summary-stat">
              <div className="at-summary-val" style={{ color: '#f59e0b' }}>{pending}</div>
              <div className="at-summary-lbl">Pending</div>
            </div>
            <div className="at-summary-divider"></div>
            <div className="at-summary-track-wrap">
              <div className="at-summary-track-label">
                <span>{allDone ? '🎉 All done for today!' : "Today's progress"}</span>
                <span style={{ color: allDone ? '#10b981' : '#4f46e5', fontWeight: 700 }}>{pct}%</span>
              </div>
              <div className="at-summary-track">
                <div className={`at-summary-fill ${allDone ? 'complete' : ''}`} style={{ width: `${pct}%` }}></div>
              </div>
              <div className="at-summary-note">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                {allDone ? (
                  `All ${total} periods marked — great work!`
                ) : (
                  `${pending} period${pending > 1 ? 's' : ''} still need${pending === 1 ? 's' : ''} to be marked`
                )}
              </div>
            </div>
          </div>
        )}

        {/* Flash Notification */}
        {flashSuccess && (
          <div className="at-flash">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {flashSuccess}
          </div>
        )}

        {/* Period Cards Grid */}
        <div className="at-grid">
          {todaySlots.length > 0 ? (
            todaySlots.map((slot) => {
              const isMarked = completedSessionKeys.includes(slot.period_number);
              return (
                <div className="at-card" key={slot.id}>
                  <div className={`at-card-stripe ${isMarked ? 'stripe-done' : 'stripe-pending'}`}></div>

                  <div className="at-card-body">
                    {/* Top row */}
                    <div className="at-card-top">
                      <span className={`at-period-badge ${isMarked ? 'badge-done' : 'badge-pend'}`}>
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Period {slot.period_number}
                      </span>
                      <span className={`at-status-pill ${isMarked ? 'pill-done' : 'pill-pending'}`}>
                        <span className="at-status-dot" style={{ background: isMarked ? '#10b981' : '#f97316' }}></span>
                        {isMarked ? 'Marked' : 'Pending'}
                      </span>
                    </div>

                    {/* Class & Subject */}
                    <div className="at-class-name">{slot.classroom?.display_name || `Class ${slot.classroom_id}`}</div>
                    <div className="at-subject">{slot.subject?.name || 'Subject'}</div>

                    {/* Meta */}
                    <div className="at-card-meta">
                      <div className="at-meta-item">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        {formatTime(slot.start_time)} — {formatTime(slot.end_time)}
                      </div>
                      {slot.classroom?.students_count !== undefined && (
                        <div className="at-meta-item">
                          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                          {slot.classroom.students_count} students
                        </div>
                      )}
                    </div>

                    {/* CTA Button */}
                    <Link 
                      to={`/attendance/mark/${slot.id}`} 
                      className={`at-card-btn ${isMarked ? 'btn-review' : 'btn-mark'}`}
                      id={`mark-btn-${slot.id}`}
                    >
                      {isMarked ? (
                        <>
                          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                          Review Attendance
                        </>
                      ) : (
                        <>
                          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                          Mark Attendance
                        </>
                      )}
                    </Link>
                  </div>
                </div>
              );
            })
          ) : (
            <div className="at-empty">
              <div className="at-empty-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              </div>
              <div className="at-empty-title">No periods scheduled today</div>
              <div className="at-empty-desc">You have no timetabled slots for {today}.<br />Enjoy your free day or contact admin if this seems wrong.</div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
