import { useState, useEffect } from 'react';
import { Link, useLocation } from 'react-router-dom';
import studentService from '@/services/studentService';
import Card from '@/components/ui/Card';
import Loader from '@/components/ui/Loader';
import Pagination from '@/components/ui/Pagination';
import StudentTable from './components/StudentTable';

export default function StudentsPage() {
  const location = useLocation();
  const [students, setStudents] = useState([]);
  const [classesList, setClassesList] = useState([]);
  const [selectedClass, setSelectedClass] = useState('');
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');
  const [debouncedSearch, setDebouncedSearch] = useState('');
  const [meta, setMeta] = useState(null);
  const [notification, setNotification] = useState(null);
  
  // Quick View Student State
  const [selectedStudent, setSelectedStudent] = useState(null);

  // Debounce search term
  useEffect(() => {
    const handler = setTimeout(() => {
      setDebouncedSearch(searchTerm);
    }, 400);
    return () => clearTimeout(handler);
  }, [searchTerm]);

  useEffect(() => {
    if (location.state?.successMessage) {
      setNotification({ type: 'success', message: location.state.successMessage });
      window.history.replaceState({}, document.title);
      const timer = setTimeout(() => setNotification(null), 5000);
      return () => clearTimeout(timer);
    }
  }, [location]);

  // Load Classes lookup on mount
  useEffect(() => {
    const fetchClasses = async () => {
      try {
        const res = await studentService.lookupClasses();
        if (Array.isArray(res.data)) {
          setClassesList(res.data);
        }
      } catch (err) {
        console.error('Failed to load class lookups:', err);
      }
    };
    fetchClasses();
  }, []);

  const fetchStudents = async (page = 1) => {
    setLoading(true);
    try {
      const params = {
        page,
        search: debouncedSearch,
        class: selectedClass
      };
      const response = await studentService.getStudents(params);
      const payload = response.data;
      if (payload && Array.isArray(payload.data)) {
        setStudents(payload.data);
        setMeta({
          current_page: payload.current_page,
          last_page: payload.last_page,
          per_page: payload.per_page,
          total: payload.total,
        });
      } else {
        setStudents(Array.isArray(payload) ? payload : []);
        setMeta(null);
      }
    } catch (err) {
      console.error('Failed to load students:', err);
    } finally {
      setLoading(false);
    }
  };

  // Re-fetch when debounced search or class filter changes
  useEffect(() => {
    fetchStudents(1);
  }, [debouncedSearch, selectedClass]);

  const handleToggleStatus = async (id, currentStatus) => {
    const newStatus = !currentStatus;
    // Optimistic UI update
    setStudents(prev => 
      prev.map(s => s.id === id ? { ...s, is_active: newStatus } : s)
    );
    
    try {
      await studentService.updateStudent(id, { is_active: newStatus });
      setNotification({ type: 'success', message: 'Student status updated successfully.' });
      const timer = setTimeout(() => setNotification(null), 3000);
      return () => clearTimeout(timer);
    } catch (err) {
      console.error('Failed to toggle status:', err);
      // Revert state
      setStudents(prev => 
        prev.map(s => s.id === id ? { ...s, is_active: currentStatus } : s)
      );
      alert('Failed to update student status.');
    }
  };

  const handleDelete = async (id) => {
    if (window.confirm('Are you sure you want to remove this student? All their academic records and marks will be permanently deleted.')) {
      try {
        await studentService.deleteStudent(id);
        setStudents(prev => prev.filter(s => s.id !== id));
        setNotification({ type: 'success', message: 'Student removed successfully.' });
        const timer = setTimeout(() => setNotification(null), 4000);
        return () => clearTimeout(timer);
      } catch (err) {
        console.error('Failed to delete student:', err);
        alert('Failed to delete student.');
      }
    }
  };

  const handleQuickView = (student) => {
    setSelectedStudent(student);
  };

  return (
    <div className="w-full max-w-[1600px] mx-auto px-4 md:px-8 py-8">
      {/* Parity CSS styles matching Blade design */}
      <style>{`
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 32px;
            flex-wrap: wrap;
            gap: 16px;
        }
        .page-title {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 4px 0;
            letter-spacing: -0.02em;
        }
        .page-subtitle {
            font-size: 15px;
            color: #64748b;
            margin: 0;
        }
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
        .premium-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 20px -2px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.02);
            overflow: hidden;
            margin-bottom: 24px;
        }
        .filter-bar {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }
        .filter-input {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 16px;
            padding-left: 40px;
            font-size: 14px;
            width: 100%;
            color: #1e293b;
            transition: all 0.2s;
        }
        .filter-input:focus {
            background: #fff;
            border-color: #6C5CE7;
            box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1);
            outline: none;
        }
        .input-icon-wrapper {
            position: relative;
            width: 100%;
            max-width: 400px;
        }
        .input-icon-wrapper svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        .premium-table { width: 100%; border-collapse: collapse; }
        .premium-table th { text-align: left; padding: 14px 16px; color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
        .premium-table td { padding: 14px 16px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; color: #334155; font-size: 14px; }
        .premium-table tbody tr:hover td { background: #f8fafc; }
        .premium-table tbody tr:hover td { background: #f8fafc; }
        
        .status-badge {
            display: inline-flex; padding: 4px 10px; border-radius: 100px;
            font-size: 12px; font-weight: 700; border: 1px solid transparent;
        }
        .status-success { background: #ecfdf5; color: #059669; border-color: #a7f3d0; }
        .status-warning { background: #fffbeb; color: #d97706; border-color: #fde68a; }
        .status-danger { background: #fef2f2; color: #dc2626; border-color: #fca5a5; }
        .status-neutral { background: #f1f5f9; color: #64748b; border-color: #cbd5e1; }
        
        .avatar-initials {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, #6C5CE7, #5A4BD6);
            color: white; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 13px; flex-shrink: 0;
        }
        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            background: #f1f5f9;
            border: 1px solid transparent;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
        }
        .action-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }
        .action-btn.primary { background: #e0e7ff; color: #4f46e5; }
        .action-btn.primary:hover { background: #4f46e5; color: #fff; }
        .action-btn.danger { background: #fef2f2; color: #ef4444; }
        .action-btn.danger:hover { background: #ef4444; color: #fff; }

        /* Custom Toggle Switch */
        .toggle-switch {
            width: 36px;
            height: 20px;
            border-radius: 20px;
            position: relative;
            cursor: pointer;
            transition: background 0.3s;
        }
        .toggle-switch.active { background: #10b981; }
        .toggle-switch.inactive { background: #cbd5e1; }
        .toggle-switch-handle {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #fff;
            position: absolute;
            top: 2px;
            transition: left 0.3s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        .toggle-switch.active .toggle-switch-handle { left: 18px; }
        .toggle-switch.inactive .toggle-switch-handle { left: 2px; }

        /* Quick View Modal styling */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .modal-container {
            background: #fff;
            border-radius: 24px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            border: 1px solid rgba(255,255,255,0.8);
            overflow: hidden;
            animation: modalFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes modalFadeIn {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .modal-header {
            padding: 24px 24px 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
        }
        .modal-body {
            padding: 24px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .info-value {
            font-size: 14px;
            color: #1e293b;
            font-weight: 600;
        }
      `}</style>

      {/* Header Area */}
      <div className="page-header">
        <div>
          <h2 className="page-title">Students</h2>
          <p className="page-subtitle">Manage student enrollment, performance status, and records</p>
        </div>
        <Link to="/students/create" className="btn-solid-primary">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" style={{ marginRight: '4px' }}>
            <path d="M16 21v-2a4 4 0 0 0-4-4H5c-1.1 0-2 .9-2 2v2" />
            <circle cx="8.5" cy="7" r="4" />
            <line x1="20" y1="8" x2="20" y2="14" />
            <line x1="23" y1="11" x2="17" y2="11" />
          </svg>
          Enroll Student
        </Link>
      </div>

      {notification && (
        <div className={`alert alert-${notification.type}`} style={{ marginBottom: '24px' }}>
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" style={{ marginRight: '8px' }}>
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <polyline points="22 4 12 14.01 9 11.01" />
          </svg>
          <span>{notification.message}</span>
        </div>
      )}

      {/* Table Container Card */}
      <div className="premium-card">
        <div className="filter-bar">
          <div className="input-icon-wrapper">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <circle cx="11" cy="11" r="8" />
              <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input
              type="text"
              id="studentFilter"
              className="filter-input"
              placeholder="Search by name, roll no, or class name..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
            />
          </div>

          <div style={{ display: 'flex', gap: '12px', alignItems: 'center' }}>
            <select
              id="classFilter"
              className="filter-input"
              style={{ paddingLeft: '14px', maxWidth: '200px', cursor: 'pointer' }}
              value={selectedClass}
              onChange={(e) => setSelectedClass(e.target.value)}
            >
              <option value="">All Classes</option>
              {[...new Set(classesList.map(c => c.class))].filter(Boolean).map(c => (
                <option key={c} value={c}>Class {c}</option>
              ))}
            </select>
          </div>
        </div>

        <div style={{ overflowX: 'auto' }}>
          <StudentTable 
            students={students}
            loading={loading}
            onToggleStatus={handleToggleStatus}
            onDelete={handleDelete}
            onQuickView={handleQuickView}
          />
        </div>

        {/* Laravel-compatible pagination section */}
        {meta && meta.last_page > 1 && (
          <div style={{ padding: '16px 24px', borderTop: '1px solid #f1f5f9' }}>
            <Pagination meta={meta} onPageChange={fetchStudents} />
          </div>
        )}
      </div>

      {/* Student Quick View Modal */}
      {selectedStudent && (
        <div className="modal-overlay" onClick={() => setSelectedStudent(null)}>
          <div className="modal-container" onClick={(e) => e.stopPropagation()}>
            <div className="modal-header">
              <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                <div className="avatar-initials" style={{ width: '40px', height: '40px', fontSize: '15px' }}>
                  {selectedStudent.name ? selectedStudent.name.substring(0,2).toUpperCase() : 'ST'}
                </div>
                <div>
                  <h3 style={{ fontWeight: 800, fontSize: '16px', color: '#0f172a', margin: 0 }}>{selectedStudent.name}</h3>
                  <span style={{ fontSize: '12px', color: '#64748b' }}>{selectedStudent.email || 'No Email'}</span>
                </div>
              </div>
              <button 
                type="button" 
                onClick={() => setSelectedStudent(null)}
                style={{ background: 'transparent', border: 'none', cursor: 'pointer', color: '#94a3b8' }}
              >
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                  <line x1="18" y1="6" x2="6" y2="18" />
                  <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
              </button>
            </div>
            <div className="modal-body">
              <div className="info-grid">
                <div className="info-item">
                  <span className="info-label">Roll Number</span>
                  <span className="info-value">{selectedStudent.roll_no}</span>
                </div>
                <div className="info-item">
                  <span className="info-label">Class & Section</span>
                  <span className="info-value">{selectedStudent.class}{selectedStudent.section ? ` - ${selectedStudent.section}` : ''}</span>
                </div>
                <div className="info-item">
                  <span className="info-label">Gender</span>
                  <span className="info-value" style={{ textTransform: 'capitalize' }}>{selectedStudent.gender || '—'}</span>
                </div>
                <div className="info-item">
                  <span className="info-label">Status</span>
                  <span className="info-value" style={{ color: selectedStudent.is_active ? '#10b981' : '#64748b' }}>
                    {selectedStudent.is_active ? 'Active' : 'Inactive'}
                  </span>
                </div>
                <div className="info-item" style={{ gridColumn: '1 / -1' }}>
                  <span className="info-label">Academic Status</span>
                  <div>
                    {selectedStudent.marks_count === 0 ? (
                      <span className="status-badge status-neutral">Not Evaluated</span>
                    ) : selectedStudent.is_slow_learner ? (
                      <span className="status-badge status-danger">Slow Learner</span>
                    ) : selectedStudent.average_percentage >= 75 ? (
                      <span className="status-badge status-success">Excellent</span>
                    ) : (
                      <span className="status-badge status-warning">Average</span>
                    )}
                  </div>
                </div>
                {selectedStudent.marks_count > 0 && (
                  <>
                    <div className="info-item">
                      <span className="info-label">Assessments</span>
                      <span className="info-value">{selectedStudent.marks_count}</span>
                    </div>
                    <div className="info-item">
                      <span className="info-label">Average Score</span>
                      <span className="info-value" style={{ color: selectedStudent.average_percentage >= 75 ? '#10b981' : selectedStudent.average_percentage >= 40 ? '#f59e0b' : '#ef4444' }}>
                        {selectedStudent.average_percentage}%
                      </span>
                    </div>
                  </>
                )}
              </div>
              <div style={{ display: 'flex', gap: '12px', marginTop: '28px', justifyContent: 'flex-end' }}>
                <Link to={`/students/${selectedStudent.id}`} className="btn btn-outline" style={{ display: 'inline-flex', alignItems: 'center', fontSize: '13px' }}>
                  View Full Profile
                </Link>
                <Link to={`/students/${selectedStudent.id}/edit`} className="btn btn-primary" style={{ display: 'inline-flex', alignItems: 'center', fontSize: '13px' }}>
                  Edit Details
                </Link>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
