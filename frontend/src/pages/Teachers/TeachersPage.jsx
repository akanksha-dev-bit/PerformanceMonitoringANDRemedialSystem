import { useState, useEffect } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { teacherService } from '@/services';
import Card from '@/components/ui/Card';
import Loader from '@/components/ui/Loader';
import Pagination from '@/components/ui/Pagination';
import TeacherTable from './components/TeacherTable';

export default function TeachersPage() {
  const location = useLocation();
  const [teachers, setTeachers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');
  const [meta, setMeta] = useState(null);
  const [notification, setNotification] = useState(null);

  useEffect(() => {
    if (location.state?.successMessage) {
      setNotification({ type: 'success', message: location.state.successMessage });
      window.history.replaceState({}, document.title);
      const timer = setTimeout(() => setNotification(null), 5000);
      return () => clearTimeout(timer);
    }
  }, [location]);

  const fetchTeachers = async (page = 1) => {
    setLoading(true);
    try {
      const response = await teacherService.getTeachers({ page });
      const payload = response.data;
      if (payload && Array.isArray(payload.data)) {
        setTeachers(payload.data);
        setMeta({
          current_page: payload.current_page,
          last_page: payload.last_page,
          per_page: payload.per_page,
          total: payload.total,
        });
      } else {
        setTeachers(Array.isArray(payload) ? payload : []);
        setMeta(null);
      }
    } catch (err) {
      console.error('Failed to load teachers:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchTeachers();
  }, []);

  const handleToggleStatus = async (id, currentStatus) => {
    const newStatus = !currentStatus;
    // Optimistic UI update
    setTeachers(prev => 
      prev.map(t => t.id === id ? { ...t, user: { ...t.user, is_active: newStatus } } : t)
    );
    
    try {
      await teacherService.updateTeacher(id, { is_active: newStatus });
      setNotification({ type: 'success', message: 'Teacher status updated successfully.' });
      const timer = setTimeout(() => setNotification(null), 3000);
      return () => clearTimeout(timer);
    } catch (err) {
      console.error('Failed to toggle status:', err);
      // Revert state
      setTeachers(prev => 
        prev.map(t => t.id === id ? { ...t, user: { ...t.user, is_active: currentStatus } } : t)
      );
      alert('Failed to update teacher status.');
    }
  };

  const handleDelete = async (id) => {
    if (window.confirm('Delete this teacher?')) {
      try {
        await teacherService.deleteTeacher(id);
        setTeachers(prev => prev.filter(t => t.id !== id));
        setNotification({ type: 'success', message: 'Teacher deleted successfully.' });
        const timer = setTimeout(() => setNotification(null), 4000);
        return () => clearTimeout(timer);
      } catch (err) {
        console.error('Failed to delete teacher:', err);
        alert('Failed to delete teacher.');
      }
    }
  };

  // Client-side search matching legacy Blade filter & name/email/subject
  const filteredTeachers = teachers.filter((teacher) => {
    const name = teacher.user?.name || '';
    const email = teacher.user?.email || '';
    const subjectName = teacher.subject?.name || '';
    const className = teacher.subject ? `class ${teacher.subject.class}` : '';
    const text = `${name} ${email} ${subjectName} ${className}`.toLowerCase();
    return text.includes(searchTerm.toLowerCase());
  });

  return (
    <div className="max-w-7xl mx-auto px-4 py-8">
      {/* Inject legacy Blade styles for perfect visual parity */}
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
        .premium-table th { text-align: left; padding: 16px 24px; color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
        .premium-table td { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; color: #334155; font-size: 14px; }
        .premium-table tbody tr:hover td { background: #f8fafc; }
        
        .status-badge {
            display: inline-flex; padding: 4px 10px; border-radius: 100px;
            font-size: 12px; font-weight: 700; border: 1px solid transparent;
        }
        .status-neutral { background: #f1f5f9; color: #64748b; border-color: #cbd5e1; }
        
        .avatar-initials {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, #1e293b, #0f172a);
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
      `}</style>

      {/* Header Area */}
      <div className="page-header">
        <div>
          <h2 className="page-title">Teachers</h2>
          <p className="page-subtitle">Manage all teachers in your school</p>
        </div>
        <Link to="/teachers/create" className="btn-solid-primary">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" style={{ marginRight: '4px' }}>
            <path d="M16 21v-2a4 4 0 0 0-4-4H5c-1.1 0-2 .9-2 2v2" />
            <circle cx="8.5" cy="7" r="4" />
            <line x1="20" y1="8" x2="20" y2="14" />
            <line x1="23" y1="11" x2="17" y2="11" />
          </svg>
          Add Teacher
        </Link>
      </div>

      {notification && (
        <div className={`alert alert-${notification.type}`} style={{ marginBottom: '24px' }}>
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" style={{ marginRight: '8px' }}>
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
          </svg>
          <span>{notification.message}</span>
        </div>
      )}

      {/* Table Card container */}
      <div className="premium-card">
        <div className="filter-bar">
          <div className="input-icon-wrapper">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <circle cx="11" cy="11" r="8" />
              <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input
              type="text"
              id="teacherFilter"
              className="filter-input"
              placeholder="Search teachers by name, email or subject..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
            />
          </div>
        </div>

        <div style={{ overflowX: 'auto' }}>
          <TeacherTable 
            teachers={filteredTeachers}
            loading={loading}
            onToggleStatus={handleToggleStatus}
            onDelete={handleDelete}
          />
        </div>

        {/* Laravel-compatible pagination section */}
        {meta && meta.last_page > 1 && (
          <div style={{ padding: '16px 24px', borderTop: '1px solid #f1f5f9' }}>
            <Pagination meta={meta} onPageChange={fetchTeachers} />
          </div>
        )}
      </div>
    </div>
  );
}
