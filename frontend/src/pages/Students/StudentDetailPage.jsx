import React, { useState, useEffect } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom';
import studentService from '@/services/studentService';
import Loader from '@/components/ui/Loader';

export default function StudentDetailPage() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [student, setStudent] = useState(null);
  const [loading, setLoading] = useState(true);
  const [activeTab, setActiveTab] = useState('marks'); // 'marks', 'attendance', 'remedial'

  useEffect(() => {
    const fetchProfile = async () => {
      try {
        const response = await studentService.getStudentProfile(id);
        setStudent(response.data);
      } catch (err) {
        console.error('Error loading student profile:', err);
        alert('Could not load student profile.');
        navigate('/students');
      } finally {
        setLoading(false);
      }
    };
    fetchProfile();
  }, [id, navigate]);

  if (loading) {
    return <Loader visible />;
  }

  if (!student) {
    return (
      <div className="w-full max-w-4xl mx-auto px-4 py-16 text-center">
        <div style={{ width: '80px', height: '80px', background: '#f1f5f9', borderRadius: '50%', display: 'flex', alignItems: 'center', justifyCenter: 'center', margin: '0 auto 20px', color: '#94a3b8' }}>
          <svg width="40" height="40" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
          </svg>
        </div>
        <h2 style={{ fontSize: '24px', fontWeight: 700, color: '#0f172a', marginBottom: '8px' }}>Student Profile Not Found</h2>
        <p style={{ color: '#64748b', marginBottom: '24px' }}>The requested student record could not be loaded or has been removed.</p>
        <Link to="/students" className="btn-solid-primary">
          ← Back to Students Directory
        </Link>
      </div>
    );
  }

  // Calculate degrees for radial gauge wheels
  const avgMarks = student.average_percentage || 0;
  const avgMarksDeg = (avgMarks / 100) * 360;

  const attendancePct = student.overall_attendance ?? 100.0;
  const attendanceDeg = (attendancePct / 100) * 360;
  const attendanceColor = attendancePct >= 75 ? '#10B981' : '#EF4444';

  // Group attendance records by subject
  const subjectAttendance = [];
  const totalSessions = student.attendance_records?.length || 0;
  if (totalSessions > 0) {
    const grouped = {};
    student.attendance_records.forEach(rec => {
      const sub = rec.session?.subject;
      if (sub) {
        if (!grouped[sub.id]) {
          grouped[sub.id] = { name: sub.name, total: 0, present: 0 };
        }
        grouped[sub.id].total += 1;
        if (rec.status === 'present') {
          grouped[sub.id].present += 1;
        }
      }
    });

    Object.keys(grouped).forEach(subId => {
      const g = grouped[subId];
      const pct = g.total > 0 ? Math.round((g.present / g.total) * 100) : 100;
      subjectAttendance.push({
        id: subId,
        name: g.name,
        total: g.total,
        present: g.present,
        absent: g.total - g.present,
        pct
      });
    });
  }

  const presentSessions = student.attendance_records?.filter(r => r.status === 'present').length || 0;
  const initials = student.name ? student.name.substring(0, 2).toUpperCase() : 'ST';

  return (
    <div className="w-full max-w-[1600px] mx-auto px-4 md:px-8 py-8">
      {/* World-Class SPA Parity CSS Styles */}
      <style>{`
        .student-header-card {
          background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);
          border: 1px solid #e2e8f0;
          border-radius: 20px;
          padding: 28px 36px;
          display: flex;
          align-items: center;
          justify-content: space-between;
          margin-bottom: 28px;
          box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05);
          position: relative;
          overflow: hidden;
          flex-wrap: wrap;
          gap: 20px;
        }
        .student-header-card::before {
          content: '';
          position: absolute;
          top: 0; right: 0; width: 340px; height: 100%;
          background: radial-gradient(circle at top right, rgba(108,92,231,0.08), transparent 70%);
          pointer-events: none;
        }
        .sh-title { 
          font-family: 'Inter', system-ui, -apple-system, sans-serif; 
          font-size: 30px; 
          font-weight: 800; 
          color: #0f172a; 
          letter-spacing: -0.02em; 
          line-height: 1.2; 
          margin: 0 0 6px 0; 
        }
        .sh-subtitle { 
          font-size: 14px; 
          color: #64748b; 
          display: flex; 
          align-items: center; 
          gap: 10px; 
          margin: 0; 
          flex-wrap: wrap;
        }
        .sh-actions { display: flex; gap: 12px; z-index: 1; flex-wrap: wrap; }

        .btn-action-outline {
          display: inline-flex;
          align-items: center;
          gap: 8px;
          padding: 10px 18px;
          background: #ffffff;
          border: 1px solid #cbd5e1;
          border-radius: 12px;
          color: #334155;
          font-size: 13.5px;
          font-weight: 600;
          text-decoration: none;
          transition: all 0.2s ease;
          box-shadow: 0 1px 2px rgba(0,0,0,0.05);
          cursor: pointer;
        }
        .btn-action-outline:hover {
          background: #f8fafc;
          border-color: #94a3b8;
          color: #0f172a;
          transform: translateY(-1px);
        }

        .btn-solid-primary {
          display: inline-flex;
          align-items: center;
          gap: 8px;
          padding: 10px 20px;
          background: linear-gradient(135deg, #6C5CE7, #5A4BD6);
          color: #ffffff;
          border-radius: 12px;
          font-size: 13.5px;
          font-weight: 700;
          text-decoration: none;
          transition: all 0.2s ease;
          box-shadow: 0 4px 14px rgba(108, 92, 231, 0.3);
          border: none;
          cursor: pointer;
        }
        .btn-solid-primary:hover {
          transform: translateY(-2px);
          box-shadow: 0 6px 18px rgba(108, 92, 231, 0.4);
          color: #ffffff;
        }

        .grid-container {
          display: grid;
          grid-template-columns: 340px 1fr;
          gap: 28px;
          margin-bottom: 32px;
        }

        /* Profile Left Card */
        .profile-card {
          background: #ffffff;
          border-radius: 20px;
          border: 1px solid #e2e8f0;
          box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05);
          overflow: hidden;
          height: fit-content;
        }
        .profile-card-top {
          background: linear-gradient(135deg, #6C5CE7 0%, #4834d4 100%);
          height: 110px;
          position: relative;
        }
        .profile-card-content {
          padding: 0 24px 28px;
          text-align: center;
        }
        .profile-avatar-lg {
          width: 92px; height: 92px;
          border-radius: 50%;
          background: #ffffff;
          color: #6C5CE7;
          font-size: 30px; 
          font-weight: 800;
          display: flex; align-items: center; justify-content: center;
          margin: -46px auto 16px;
          border: 4px solid #ffffff;
          box-shadow: 0 10px 25px rgba(108,92,231,0.25);
          position: relative;
        }
        .profile-name { font-size: 22px; font-weight: 800; color: #0f172a; margin-bottom: 2px; }
        .profile-email { font-size: 13.5px; color: #64748b; word-break: break-all; }

        .info-list { margin-top: 24px; text-align: left; border-top: 1px solid #f1f5f9; padding-top: 18px; }
        .info-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px dashed #f1f5f9; font-size: 13.5px; }
        .info-item:last-child { border-bottom: none; }
        .info-label { color: #64748b; font-weight: 500; display: flex; align-items: center; gap: 8px; }
        .info-val { font-weight: 700; color: #0f172a; }

        /* Gauges & Performance Cards */
        .perf-card {
          background: #ffffff;
          border-radius: 20px;
          border: 1px solid #e2e8f0;
          padding: 28px;
          box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05);
          display: flex; 
          align-items: center; 
          gap: 24px;
        }
        .perf-score-wrap {
          width: 110px; height: 110px;
          border-radius: 50%;
          display: flex; align-items: center; justify-content: center;
          position: relative;
          flex-shrink: 0;
        }
        .perf-score-inner {
          width: 92px; height: 92px;
          background: #ffffff; border-radius: 50%;
          display: flex; flex-direction: column; align-items: center; justify-content: center;
          box-shadow: inset 0 2px 6px rgba(0,0,0,0.04);
        }
        .perf-score-inner .val { font-size: 24px; font-weight: 800; line-height: 1; }
        .perf-score-inner .lbl { font-size: 10px; color: #64748b; font-weight: 700; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
        .perf-details { flex: 1; }

        /* Pill Badge Styles */
        .badge-pill {
          display: inline-flex;
          align-items: center;
          gap: 6px;
          padding: 5px 12px;
          border-radius: 100px;
          font-size: 12.5px;
          font-weight: 700;
          line-height: 1;
        }
        .badge-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }
        .badge-warning { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .badge-neutral { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
        .badge-primary { background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; }

        /* Navigation Tab Bar */
        .records-card {
          background: #ffffff;
          border-radius: 20px;
          border: 1px solid #e2e8f0;
          box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05);
          overflow: hidden;
        }
        .tab-bar {
          display: flex;
          align-items: center;
          background: #f8fafc;
          border-bottom: 1px solid #e2e8f0;
          padding: 8px 16px 0 16px;
          gap: 8px;
          overflow-x: auto;
        }
        .tab-btn {
          display: inline-flex;
          align-items: center;
          gap: 8px;
          padding: 12px 20px;
          font-size: 14px;
          font-weight: 600;
          color: #64748b;
          border: none;
          background: transparent;
          cursor: pointer;
          border-bottom: 3px solid transparent;
          transition: all 0.2s ease;
          border-top-left-radius: 8px;
          border-top-right-radius: 8px;
        }
        .tab-btn:hover {
          color: #0f172a;
          background: rgba(255,255,255,0.6);
        }
        .tab-btn.active {
          color: #6C5CE7;
          border-bottom-color: #6C5CE7;
          background: #ffffff;
          font-weight: 700;
        }
        .tab-count {
          padding: 2px 8px;
          border-radius: 100px;
          font-size: 11px;
          font-weight: 700;
          background: #e2e8f0;
          color: #475569;
        }
        .tab-btn.active .tab-count {
          background: rgba(108, 92, 231, 0.15);
          color: #6C5CE7;
        }

        /* Table Design */
        .premium-table { width: 100%; border-collapse: collapse; }
        .premium-table th { 
          text-align: left; 
          padding: 14px 20px; 
          font-size: 12px; 
          font-weight: 700; 
          color: #64748b; 
          text-transform: uppercase; 
          letter-spacing: 0.05em; 
          border-bottom: 1px solid #e2e8f0; 
          background: #ffffff; 
        }
        .premium-table td { 
          padding: 16px 20px; 
          font-size: 14px; 
          border-bottom: 1px solid #f1f5f9; 
          vertical-align: middle; 
          color: #334155; 
        }
        .premium-table tbody tr:last-child td { border-bottom: none; }
        .premium-table tbody tr:hover td { background: #f8fafc; }

        .subject-icon { 
          width: 38px; height: 38px; 
          border-radius: 10px; 
          display: flex; align-items: center; justify-content: center; 
          background: rgba(108, 92, 231, 0.08); 
          color: #6C5CE7; 
          margin-right: 14px; 
          flex-shrink: 0;
        }

        @media (max-width: 992px) {
          .grid-container { grid-template-columns: 1fr; }
          .student-header-card { flex-direction: column; align-items: flex-start; }
          .sh-actions { width: 100%; justify-content: flex-start; }
        }
      `}</style>

      {/* Header Banner */}
      <div className="student-header-card">
        <div>
          <h2 className="sh-title">{student.name}</h2>
          <div className="sh-subtitle">
            <span className="badge-pill badge-primary">
              {student.section_name || student.classroom?.display_name || 'No Section'}
            </span>
            <span style={{ color: '#cbd5e1' }}>•</span>
            <span>Roll No: <strong style={{ color: '#0f172a' }}>{student.roll_no}</strong></span>
            <span style={{ color: '#cbd5e1' }}>•</span>
            <span className={`badge-pill ${student.is_active ? 'badge-success' : 'badge-danger'}`}>
              {student.is_active ? 'Active Student' : 'Inactive'}
            </span>
          </div>
        </div>
        <div className="sh-actions">
          <Link to={`/students/${student.id}/edit`} className="btn-action-outline" id="edit-student-btn">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <path d="M12 20h9" />
              <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
            </svg>
            Edit Profile
          </Link>
          <Link to={`/marks/create?student_id=${student.id}`} className="btn-action-outline" id="add-marks-btn">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <line x1="12" y1="5" x2="12" y2="19" />
              <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Add Marks
          </Link>
          <Link to={`/remedial/create?student_id=${student.id}`} className="btn-solid-primary" id="assign-remedial-btn">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
            </svg>
            Assign Remedial
          </Link>
        </div>
      </div>

      <div className="grid-container">
        {/* Left Column: Profile Card */}
        <div className="profile-card">
          <div className="profile-card-top"></div>
          <div className="profile-card-content">
            <div className="profile-avatar-lg">{initials}</div>
            <div className="profile-name">{student.name}</div>
            <div className="profile-email">{student.email || 'No email registered'}</div>
            
            <div style={{ marginTop: '16px' }}>
              {!student.has_marks ? (
                <span className="badge-pill badge-neutral">Not Evaluated Yet</span>
              ) : student.is_slow_learner ? (
                <span className="badge-pill badge-danger">⚠️ Needs Intervention (Slow Learner)</span>
              ) : student.average_percentage >= 60 ? (
                <span className="badge-pill badge-success">✓ High Performer</span>
              ) : (
                <span className="badge-pill badge-warning">~ Academic Risk</span>
              )}
            </div>

            <div className="info-list">
              <div className="info-item">
                <span className="info-label">
                  <svg width="14" height="14" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                  Roll Number
                </span>
                <span className="info-val">{student.roll_no}</span>
              </div>
              <div className="info-item">
                <span className="info-label">
                  <svg width="14" height="14" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
                  Gender
                </span>
                <span className="info-val" style={{ textTransform: 'capitalize' }}>{student.gender || '—'}</span>
              </div>
              <div className="info-item">
                <span className="info-label">
                  <svg width="14" height="14" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                  Date of Birth
                </span>
                <span className="info-val">{student.dob || '—'}</span>
              </div>
              <div className="info-item">
                <span className="info-label">
                  <svg width="14" height="14" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                  Contact Phone
                </span>
                <span className="info-val">{student.phone || '—'}</span>
              </div>
              <div className="info-item">
                <span className="info-label">
                  <svg width="14" height="14" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                  Guardian Name
                </span>
                <span className="info-val">{student.guardian_name || '—'}</span>
              </div>
            </div>
          </div>
        </div>

        {/* Right Column: Performance Gauges & Tabbed Records */}
        <div style={{ display: 'flex', flexDirection: 'column', gap: '28px' }}>
          
          {/* Top Gauges Grid */}
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(320px, 1fr))', gap: '24px' }}>
            
            {/* Academic Performance Card */}
            <div className="perf-card">
              {student.has_marks ? (
                <>
                  <div 
                    className="perf-score-wrap" 
                    style={{ 
                      background: `conic-gradient(${student.performance_color || '#6C5CE7'} ${avgMarksDeg}deg, #f1f5f9 0deg)`
                    }}
                  >
                    <div className="perf-score-inner">
                      <span className="val" style={{ color: student.performance_color || '#6C5CE7' }}>{avgMarks}%</span>
                      <span className="lbl">Overall</span>
                    </div>
                  </div>
                  <div className="perf-details">
                    <h3 style={{ fontSize: '17px', fontWeight: '800', marginBottom: '6px', color: '#0f172a' }}>Academic Performance</h3>
                    <p style={{ fontSize: '13.5px', color: '#64748b', lineHeight: '1.5', margin: 0 }}>
                      {student.name} is currently demonstrating <strong>{student.performance_label?.toLowerCase() || 'evaluated'}</strong> performance based on {student.marks?.length || 0} examination record(s).
                    </p>
                  </div>
                </>
              ) : (
                <div style={{ width: '100%', textAlign: 'center', padding: '12px 0' }}>
                  <div style={{ width: '56px', height: '56px', background: '#f1f5f9', borderRadius: '50%', display: 'flex', alignItems: 'center', justifyCenter: 'center', margin: '0 auto 12px', color: '#94a3b8' }}>
                    <svg width="24" height="24" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                  </div>
                  <h3 style={{ fontSize: '15px', fontWeight: '700', color: '#0f172a', marginBottom: '4px' }}>No Exam Scores Available</h3>
                  <p style={{ fontSize: '13px', color: '#64748b', marginBottom: '14px' }}>Record marks to calculate average percentage and risk status.</p>
                  <Link to={`/marks/create?student_id=${student.id}`} className="btn-solid-primary" style={{ padding: '8px 16px', fontSize: '13px' }}>
                    + Add First Score
                  </Link>
                </div>
              )}
            </div>

            {/* Attendance Summary Card */}
            <div className="perf-card">
              <div 
                className="perf-score-wrap" 
                style={{ 
                  background: `conic-gradient(${attendanceColor} ${attendanceDeg}deg, #f1f5f9 0deg)`
                }}
              >
                <div className="perf-score-inner">
                  <span className="val" style={{ color: attendanceColor }}>{Math.round(attendancePct)}%</span>
                  <span className="lbl">Attendance</span>
                </div>
              </div>
              <div className="perf-details">
                <h3 style={{ fontSize: '17px', fontWeight: '800', marginBottom: '6px', color: '#0f172a' }}>Attendance Tracking</h3>
                {totalSessions > 0 ? (
                  <p style={{ fontSize: '13.5px', color: '#64748b', lineHeight: '1.5', margin: 0 }}>
                    Attended <strong>{presentSessions}</strong> of <strong>{totalSessions}</strong> logged sessions.
                    {attendancePct < 75 ? (
                      <span style={{ display: 'block', marginTop: '6px', color: '#ef4444', fontWeight: '700', fontSize: '12.5px' }}>
                        ⚠️ Below 75% institutional attendance threshold.
                      </span>
                    ) : (
                      <span style={{ display: 'block', marginTop: '6px', color: '#10b981', fontWeight: '700', fontSize: '12.5px' }}>
                        ✓ Satisfactory attendance record.
                      </span>
                    )}
                  </p>
                ) : (
                  <p style={{ fontSize: '13.5px', color: '#64748b', lineHeight: '1.5', margin: 0 }}>
                    No attendance sessions logged for this student yet.
                  </p>
                )}
              </div>
            </div>

          </div>

          {/* Interactive Navigation Tab Container */}
          <div className="records-card">
            <div className="tab-bar">
              <button 
                className={`tab-btn ${activeTab === 'marks' ? 'active' : ''}`}
                onClick={() => setActiveTab('marks')}
              >
                <svg width="16" height="16" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                Subject Records
                <span className="tab-count">{student.marks?.length || 0}</span>
              </button>

              <button 
                className={`tab-btn ${activeTab === 'attendance' ? 'active' : ''}`}
                onClick={() => setActiveTab('attendance')}
              >
                <svg width="16" height="16" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                Subject-wise Attendance
                <span className="tab-count">{subjectAttendance.length}</span>
              </button>

              <button 
                className={`tab-btn ${activeTab === 'remedial' ? 'active' : ''}`}
                onClick={() => setActiveTab('remedial')}
              >
                <svg width="16" height="16" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" /></svg>
                Intervention Plans
                <span className="tab-count">{student.remedial_actions?.length || 0}</span>
              </button>
            </div>

            {/* Tab 1: Subject Marks Table */}
            {activeTab === 'marks' && (
              <div style={{ overflowX: 'auto' }}>
                <table className="premium-table">
                  <thead>
                    <tr>
                      <th>Subject</th>
                      <th>Examination Type</th>
                      <th>Score Achieved</th>
                      <th>Result</th>
                    </tr>
                  </thead>
                  <tbody>
                    {student.marks && student.marks.length > 0 ? (
                      student.marks.map((mark) => (
                        <tr key={mark.id}>
                          <td>
                            <div style={{ display: 'flex', alignItems: 'center' }}>
                              <div className="subject-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                  <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                                  <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                                </svg>
                              </div>
                              <div>
                                <div style={{ fontWeight: '700', color: '#0f172a' }}>{mark.subject?.name || '—'}</div>
                                <div style={{ fontSize: '12px', color: '#64748b' }}>{mark.subject?.code || '—'}</div>
                              </div>
                            </div>
                          </td>
                          <td>
                            <span className="badge-pill badge-neutral" style={{ textTransform: 'capitalize' }}>
                              {mark.exam_type ? mark.exam_type.replace('_', ' ') : '—'}
                            </span>
                          </td>
                          <td>
                            <div style={{ display: 'flex', flexDirection: 'column', gap: '2px' }}>
                              <span style={{ fontWeight: '800', color: '#0f172a', fontSize: '15px' }}>{mark.percentage}%</span>
                              <span style={{ fontSize: '12px', color: '#64748b' }}>{mark.marks_obtained} / {mark.max_marks} marks</span>
                            </div>
                          </td>
                          <td>
                            {mark.is_pass ? (
                              <span className="badge-pill badge-success">Pass</span>
                            ) : (
                              <span className="badge-pill badge-danger">Fail</span>
                            )}
                          </td>
                        </tr>
                      ))
                    ) : (
                      <tr>
                        <td colSpan="4" style={{ textAlign: 'center', padding: '40px', color: '#64748b' }}>
                          <p style={{ margin: '0 0 12px 0' }}>No evaluation records logged for this student.</p>
                          <Link to={`/marks/create?student_id=${student.id}`} className="btn-action-outline" style={{ fontSize: '13px' }}>
                            + Enter Exam Marks
                          </Link>
                        </td>
                      </tr>
                    )}
                  </tbody>
                </table>
              </div>
            )}

            {/* Tab 2: Subject-wise Attendance Table */}
            {activeTab === 'attendance' && (
              <div style={{ overflowX: 'auto' }}>
                <table className="premium-table">
                  <thead>
                    <tr>
                      <th>Subject</th>
                      <th>Classes Attended</th>
                      <th>Attendance Progress</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    {subjectAttendance.length > 0 ? (
                      subjectAttendance.map((subAtt) => (
                        <tr key={subAtt.id}>
                          <td>
                            <div style={{ display: 'flex', alignItems: 'center' }}>
                              <div className="subject-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                              </div>
                              <div>
                                <div style={{ fontWeight: '700', color: '#0f172a' }}>{subAtt.name}</div>
                              </div>
                            </div>
                          </td>
                          <td>
                            <span style={{ fontWeight: '700', color: '#334155' }}>{subAtt.present} / {subAtt.total}</span>
                            <span style={{ fontSize: '12px', color: '#64748b', marginLeft: '6px' }}>sessions</span>
                          </td>
                          <td>
                            <div style={{ display: 'flex', flexDirection: 'column', gap: '4px', width: '140px' }}>
                              <span style={{ fontWeight: '800', color: '#0f172a' }}>{subAtt.pct}%</span>
                              <div style={{ height: '5px', background: '#e2e8f0', borderRadius: '100px', overflow: 'hidden' }}>
                                <div 
                                  style={{ 
                                    height: '100%', 
                                    background: subAtt.pct >= 75 ? '#10b981' : '#ef4444', 
                                    width: `${subAtt.pct}%` 
                                  }} 
                                />
                              </div>
                            </div>
                          </td>
                          <td>
                            {subAtt.pct >= 75 ? (
                              <span className="badge-pill badge-success">Good Stand</span>
                            ) : (
                              <span className="badge-pill badge-danger">Low Attendance</span>
                            )}
                          </td>
                        </tr>
                      ))
                    ) : (
                      <tr>
                        <td colSpan="4" style={{ textAlign: 'center', padding: '40px', color: '#64748b' }}>
                          No subject-wise attendance sessions logged.
                        </td>
                      </tr>
                    )}
                  </tbody>
                </table>
              </div>
            )}

            {/* Tab 3: Remedial / Intervention Plans */}
            {activeTab === 'remedial' && (
              <div style={{ overflowX: 'auto' }}>
                <table className="premium-table">
                  <thead>
                    <tr>
                      <th>Plan Title</th>
                      <th>Action Type</th>
                      <th>Scheduled Date</th>
                      <th>Current Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    {student.remedial_actions && student.remedial_actions.length > 0 ? (
                      student.remedial_actions.map((action) => (
                        <tr key={action.id}>
                          <td style={{ fontWeight: '700', color: '#0f172a' }}>{action.title}</td>
                          <td>
                            <span className="badge-pill badge-primary">
                              {action.action_type ? action.action_type.replace('_', ' ') : '—'}
                            </span>
                          </td>
                          <td>{action.scheduled_date || '—'}</td>
                          <td>
                            <span 
                              className="badge-pill" 
                              style={{ 
                                background: `${action.status_badge_color || '#6C5CE7'}15`, 
                                color: action.status_badge_color || '#6C5CE7',
                                border: `1px solid ${action.status_badge_color || '#6C5CE7'}30`
                              }}
                            >
                              {action.status ? action.status.replace('_', ' ') : '—'}
                            </span>
                          </td>
                        </tr>
                      ))
                    ) : (
                      <tr>
                        <td colSpan="4" style={{ textAlign: 'center', padding: '40px', color: '#64748b' }}>
                          <p style={{ margin: '0 0 12px 0' }}>No active remedial intervention plans assigned.</p>
                          <Link to={`/remedial/create?student_id=${student.id}`} className="btn-solid-primary" style={{ fontSize: '13px' }}>
                            + Assign Remedial Action
                          </Link>
                        </td>
                      </tr>
                    )}
                  </tbody>
                </table>
              </div>
            )}

          </div>

        </div>
      </div>
    </div>
  );
}
