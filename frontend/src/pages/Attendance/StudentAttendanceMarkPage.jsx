import React, { useState, useEffect } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom';
import attendanceService from '@/services/attendanceService';
import Loader from '@/components/ui/Loader';

export default function StudentAttendanceMarkPage() {
  const { timetableId } = useParams();
  const navigate = useNavigate();

  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [errorMsg, setErrorMsg] = useState(null);

  const [timetable, setTimetable] = useState(null);
  const [students, setStudents] = useState([]);
  const [attendance, setAttendance] = useState({}); // { [studentId]: boolean (true = present) }

  // Modal State
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [physicalHeadcount, setPhysicalHeadcount] = useState('');

  useEffect(() => {
    fetchMarkingData();
  }, [timetableId]);

  const fetchMarkingData = async () => {
    setLoading(true);
    setErrorMsg(null);
    try {
      const response = await attendanceService.getMarkingSheet(timetableId);
      const data = response.data;
      setTimetable(data.timetable);
      setStudents(data.students || []);

      // Pre-fill attendance state
      const initialAttendance = {};
      const savedRecords = data.savedRecords || {};
      
      (data.students || []).forEach(student => {
        if (savedRecords[student.id] !== undefined) {
          initialAttendance[student.id] = savedRecords[student.id] === 'present';
        } else {
          initialAttendance[student.id] = true; // Default to present
        }
      });
      setAttendance(initialAttendance);
    } catch (err) {
      console.error('Error loading marking sheet:', err);
      setErrorMsg('Could not load attendance marking sheet. Access denied or period not found.');
    } finally {
      setLoading(false);
    }
  };

  const handleToggle = (studentId) => {
    setAttendance(prev => ({
      ...prev,
      [studentId]: !prev[studentId]
    }));
  };

  const setAllAttendance = (isPresent) => {
    const nextAttendance = {};
    students.forEach(s => {
      nextAttendance[s.id] = isPresent;
    });
    setAttendance(nextAttendance);
  };

  // Calculate live counts
  const presentCount = Object.values(attendance).filter(Boolean).length;
  const absentCount = students.length - presentCount;

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

  const handleOpenModal = () => {
    setErrorMsg(null);
    setPhysicalHeadcount('');
    setIsModalOpen(true);
  };

  const handleCloseModal = () => {
    setIsModalOpen(false);
  };

  const parsedHeadcount = parseInt(physicalHeadcount, 10);
  const isHeadcountValid = !isNaN(parsedHeadcount) && parsedHeadcount === presentCount;
  const isHeadcountMismatch = physicalHeadcount !== '' && !isNaN(parsedHeadcount) && parsedHeadcount !== presentCount;

  const handleSubmit = async () => {
    if (!isHeadcountValid) return;
    setSubmitting(true);
    setErrorMsg(null);

    const payloadAttendance = {};
    students.forEach(s => {
      payloadAttendance[s.id] = attendance[s.id] ? 'present' : 'absent';
    });

    try {
      const response = await attendanceService.saveAttendance(timetableId, {
        headcount: parsedHeadcount,
        attendance: payloadAttendance,
      });

      setIsModalOpen(false);
      navigate('/attendance', {
        state: { success: response.data?.message || 'Attendance marked successfully!' }
      });
    } catch (err) {
      console.error('Attendance submission error:', err);
      const msg = err.response?.data?.message || 'Failed to submit attendance. Please try again.';
      setErrorMsg(msg);
      setIsModalOpen(false);
    } finally {
      setSubmitting(false);
    }
  };

  if (loading) {
    return <Loader visible />;
  }

  if (!timetable) {
    return (
      <div className="w-full max-w-4xl mx-auto px-4 py-16 text-center">
        <h2 style={{ fontSize: '20px', fontWeight: 700, color: '#0f172a', marginBottom: '8px' }}>Period Session Not Found</h2>
        <p style={{ color: '#64748b', marginBottom: '20px' }}>{errorMsg || 'The requested timetable slot is unavailable or unauthorized.'}</p>
        <Link to="/attendance" className="btn-solid-primary">← Back to Workspace</Link>
      </div>
    );
  }

  return (
    <div className="w-full max-w-[1600px] mx-auto px-4 md:px-8 py-8">
      {/* Blade Parity CSS */}
      <style>{`
        .page-header {
          margin-bottom: 28px;
          display: flex;
          justify-content: space-between;
          align-items: center;
        }
        .page-title {
          font-size: 24px;
          font-weight: 800;
          color: #0f172a;
          letter-spacing: -0.02em;
          margin: 0;
        }
        .grid-container {
          display: grid;
          grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
          gap: 16px;
          margin-top: 24px;
        }
        .student-card {
          background: #ffffff;
          border-radius: 16px;
          border: 2px solid #e2e8f0;
          padding: 16px;
          display: flex;
          align-items: center;
          justify-content: space-between;
          transition: all 0.2s ease;
          cursor: pointer;
          user-select: none;
        }
        .student-card.present {
          border-color: #22c55e;
          background: rgba(34, 197, 94, 0.03);
        }
        .student-card.absent {
          border-color: #ef4444;
          background: rgba(239, 68, 68, 0.03);
        }
        .avatar {
          width: 40px;
          height: 40px;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          font-weight: 700;
          color: #ffffff;
          font-size: 15px;
          flex-shrink: 0;
        }
        .avatar.present { background: #22c55e; }
        .avatar.absent { background: #ef4444; }

        /* Switch Design */
        .switch {
          position: relative;
          display: inline-block;
          width: 50px;
          height: 26px;
          flex-shrink: 0;
        }
        .switch input { 
          opacity: 0;
          width: 0;
          height: 0;
        }
        .slider {
          position: absolute;
          cursor: pointer;
          top: 0; left: 0; right: 0; bottom: 0;
          background-color: #ef4444;
          transition: .3s;
          border-radius: 34px;
        }
        .slider:before {
          position: absolute;
          content: "";
          height: 18px;
          width: 18px;
          left: 4px;
          bottom: 4px;
          background-color: white;
          transition: .3s;
          border-radius: 50%;
        }
        input:checked + .slider {
          background-color: #22c55e;
        }
        input:checked + .slider:before {
          transform: translateX(24px);
        }

        /* Modal Styling */
        .modal-overlay {
          position: fixed;
          top: 0; left: 0; right: 0; bottom: 0;
          background: rgba(15, 23, 42, 0.6);
          backdrop-filter: blur(4px);
          display: flex;
          align-items: center;
          justify-content: center;
          z-index: 1000;
          opacity: 0;
          pointer-events: none;
          visibility: hidden;
          transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .modal-overlay.active {
          opacity: 1;
          pointer-events: auto;
          visibility: visible !important;
        }
        .modal-container {
          background: #ffffff;
          border-radius: 24px;
          padding: 32px;
          width: 480px;
          max-width: 90%;
          box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
          transform: scale(0.9);
          transition: transform 0.3s ease;
        }
        .modal-overlay.active .modal-container {
          transform: scale(1);
        }
        .modal-input {
          width: 100%;
          padding: 12px 16px;
          border: 2px solid #e2e8f0;
          border-radius: 12px;
          font-size: 15px;
          outline: none;
          transition: border-color 0.2s;
          margin-top: 8px;
        }
        .modal-input:focus {
          border-color: #6C5CE7;
        }
        .modal-title {
          font-size: 20px;
          font-weight: 800;
          color: #0f172a;
          margin: 0 0 12px 0;
        }
        .modal-btn {
          padding: 12px 24px;
          border-radius: 12px;
          font-weight: 700;
          font-size: 14px;
          cursor: pointer;
          transition: all 0.2s;
          border: none;
        }
        .modal-btn.primary {
          background: linear-gradient(135deg, #6C5CE7, #5A4BD6);
          color: #ffffff;
          box-shadow: 0 4px 12px rgba(108, 92, 231, 0.3);
        }
        .modal-btn.primary:disabled {
          background: #cbd5e1;
          color: #94a3b8;
          box-shadow: none;
          cursor: not-allowed;
        }
        .modal-btn.secondary {
          background: #f1f5f9;
          color: #475569;
        }
        .modal-btn.secondary:hover {
          background: #e2e8f0;
        }
      `}</style>

      {/* Header Area */}
      <div className="page-header">
        <div>
          <Link to="/attendance" style={{ color: '#6C5CE7', fontWeight: 700, textDecoration: 'none', display: 'inline-flex', alignItems: 'center', gap: '6px', fontSize: '14px', marginBottom: '8px' }}>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Back to Workspace
          </Link>
          <h2 className="page-title">
            {timetable.classroom?.display_name || `Class ${timetable.classroom_id}`} — {timetable.subject?.name || 'Subject'}
          </h2>
          <p style={{ color: '#64748b', fontSize: '14px', margin: '4px 0 0 0' }}>
            Period {timetable.period_number} ({formatTime(timetable.start_time)} - {formatTime(timetable.end_time)})
          </p>
        </div>
      </div>

      {/* Error Alert */}
      {errorMsg && (
        <div style={{ padding: '16px', backgroundColor: '#fef2f2', color: '#ef4444', border: '1px solid #fca5a5', marginBottom: '24px', borderRadius: '12px', fontWeight: 600, fontSize: '14px', display: 'flex', alignItems: 'center', gap: '8px' }}>
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          {errorMsg}
        </div>
      )}

      {/* Action Bar */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', background: '#ffffff', padding: '16px 24px', borderRadius: '16px', boxShadow: '0 4px 20px -2px rgba(0,0,0,0.03)', border: '1px solid #f1f5f9', marginBottom: '24px', flexWrap: 'wrap', gap: '12px' }}>
        <div>
          <span style={{ fontSize: '13px', color: '#64748b', fontWeight: 600 }}>Attendance Helper:</span>
          <span style={{ fontWeight: 700, marginLeft: '8px', color: '#10b981' }} id="present-count-badge">{presentCount} Present</span>
          <span style={{ color: '#cbd5e1', margin: '0 8px' }}>|</span>
          <span style={{ fontWeight: 700, color: '#ef4444' }} id="absent-count-badge">{absentCount} Absent</span>
        </div>

        <div style={{ display: 'flex', gap: '12px' }}>
          <button type="button" className="modal-btn secondary" style={{ padding: '8px 16px', fontSize: '13px' }} onClick={() => setAllAttendance(true)}>
            Mark All Present
          </button>
          <button type="button" className="modal-btn secondary" style={{ padding: '8px 16px', fontSize: '13px' }} onClick={() => setAllAttendance(false)}>
            Mark All Absent
          </button>
        </div>
      </div>

      {/* Student Cards Grid */}
      <div className="grid-container">
        {students.length > 0 ? (
          students.map((student) => {
            const isPresent = !!attendance[student.id];
            const initialLetter = student.name ? student.name.charAt(0).toUpperCase() : 'S';
            return (
              <div 
                key={student.id} 
                className={`student-card ${isPresent ? 'present' : 'absent'}`} 
                onClick={() => handleToggle(student.id)}
              >
                <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                  <div className={`avatar ${isPresent ? 'present' : 'absent'}`}>
                    {initialLetter}
                  </div>
                  <div>
                    <div style={{ fontWeight: 700, color: '#0f172a', fontSize: '14px' }}>{student.name}</div>
                    <div style={{ fontSize: '11px', color: '#64748b', marginTop: '2px' }}>Roll No: {student.roll_no || 'N/A'}</div>
                  </div>
                </div>

                <label className="switch" onClick={(e) => e.stopPropagation()}>
                  <input 
                    type="checkbox" 
                    checked={isPresent} 
                    onChange={() => handleToggle(student.id)} 
                  />
                  <span className="slider"></span>
                </label>
              </div>
            );
          })
        ) : (
          <div style={{ gridColumn: '1/-1', padding: '48px', textAlign: 'center', color: '#64748b', background: '#ffffff', borderRadius: '16px', border: '1px solid #f1f5f9' }}>
            No students enrolled in this classroom.
          </div>
        )}
      </div>

      {/* Submit Button */}
      {students.length > 0 && (
        <div style={{ marginTop: '32px', display: 'flex', justifyContent: 'flex-end' }}>
          <button 
            type="button" 
            className="modal-btn primary" 
            style={{ padding: '14px 32px', fontSize: '15px' }}
            onClick={handleOpenModal}
            id="submit-attendance-btn"
          >
            Submit Attendance
          </button>
        </div>
      )}

      {/* Physical Headcount Modal */}
      <div className={`modal-overlay ${isModalOpen ? 'active' : ''}`}>
        <div className="modal-container">
          <h3 className="modal-title">Verify Physical Headcount</h3>
          <p style={{ color: '#64748b', fontSize: '14px', margin: '0 0 20px 0', lineHeight: '1.5' }}>
            PMRS requires verification of actual student presence to maintain sensitive academic records.
          </p>

          <div style={{ marginBottom: '20px', background: '#f8fafc', padding: '16px', borderRadius: '14px', border: '1px solid #e2e8f0' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '8px', fontSize: '13px', fontWeight: 600, color: '#475569' }}>
              <span>Students Checked Present:</span>
              <span style={{ color: '#22c55e', fontWeight: 800 }}>{presentCount}</span>
            </div>
            <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '13px', fontWeight: 600, color: '#475569' }}>
              <span>Students Checked Absent:</span>
              <span style={{ color: '#ef4444', fontWeight: 800 }}>{absentCount}</span>
            </div>
          </div>

          <div style={{ marginBottom: '24px' }}>
            <label style={{ fontSize: '12px', fontWeight: 700, color: '#64748b', textTransform: 'uppercase', letterSpacing: '0.05em' }}>
              Physical headcount in room
            </label>
            <input 
              type="number" 
              className="modal-input" 
              placeholder={`e.g. ${presentCount}`}
              min="0" 
              value={physicalHeadcount}
              onChange={(e) => setPhysicalHeadcount(e.target.value)}
              id="physical-headcount-input"
            />
            
            {isHeadcountMismatch && (
              <div style={{ color: '#ef4444', fontSize: '12.5px', fontWeight: 600, marginTop: '8px', display: 'flex', alignItems: 'center', gap: '6px' }}>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Mismatch: Headcount does not equal Present Count ({presentCount}).
              </div>
            )}
          </div>

          <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '12px' }}>
            <button type="button" className="modal-btn secondary" onClick={handleCloseModal}>
              Back to Edit
            </button>
            <button 
              type="button" 
              className="modal-btn primary" 
              id="confirm-save-btn" 
              onClick={handleSubmit} 
              disabled={!isHeadcountValid || submitting}
            >
              {submitting ? 'Saving...' : 'Confirm & Save'}
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
