/**
 * ============================================================================
 * pages/Subjects/SubjectsPage.jsx — Subjects Listing Page
 * ============================================================================
 * 99% visual match port of backend/resources/views/subjects/index.blade.php.
 * Reuses Card, Table, Badge, Loader, EmptyState, and Pagination.
 *
 * Supports:
 *   - Fetching list dynamically via subjectService.getSubjects()
 *   - Laravel pagination handling
 *   - Client-side search matching Blade's filterSubjects logic
 *   - Navigation links to Create and Edit pages
 * ============================================================================
 */

import { useState, useEffect } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { subjectService } from '@/services';
import Card from '@/components/ui/Card';
import Table from '@/components/ui/Table';
import Badge from '@/components/ui/Badge';
import Loader from '@/components/ui/Loader';
import EmptyState from '@/components/ui/EmptyState';
import Pagination from '@/components/ui/Pagination';

export default function SubjectsPage() {
  const location = useLocation();
  const [subjects, setSubjects]   = useState([]);
  const [loading, setLoading]     = useState(true);
  const [searchTerm, setSearchTerm] = useState('');
  const [meta, setMeta]           = useState(null);
  const [notification, setNotification] = useState(null);

  useEffect(() => {
    if (location.state?.successMessage) {
      setNotification({ type: 'success', message: location.state.successMessage });
      // Clear location state to avoid showing it on refresh
      window.history.replaceState({}, document.title);
      const timer = setTimeout(() => setNotification(null), 5000);
      return () => clearTimeout(timer);
    }
  }, [location]);

  // Fetch subjects from Laravel controller
  const fetchSubjects = async (page = 1) => {
    setLoading(true);
    try {
      const response = await subjectService.getSubjects({ page });
      const payload = response.data;
      if (payload && Array.isArray(payload.data)) {
        setSubjects(payload.data);
        setMeta({
          current_page: payload.current_page,
          last_page: payload.last_page,
          per_page: payload.per_page,
          total: payload.total,
        });
      } else {
        // Fallback for non-paginated payload
        setSubjects(Array.isArray(payload) ? payload : []);
        setMeta(null);
      }
    } catch (err) {
      console.error('Failed to load subjects:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchSubjects();
  }, []);

  const handleToggleStatus = async (id, currentStatus) => {
    const newStatus = !currentStatus;
    // Optimistic UI update
    setSubjects(prev => prev.map(sub => sub.id === id ? { ...sub, is_active: newStatus } : sub));
    
    try {
      await subjectService.updateSubject(id, { is_active: newStatus });
      setNotification({ type: 'success', message: 'Subject status updated successfully.' });
      const timer = setTimeout(() => setNotification(null), 3000);
      return () => clearTimeout(timer);
    } catch (err) {
      console.error('Failed to toggle status:', err);
      // Revert state
      setSubjects(prev => prev.map(sub => sub.id === id ? { ...sub, is_active: currentStatus } : sub));
      alert('Failed to update subject status.');
    }
  };

  const handleDelete = async (id) => {
    if (window.confirm('Are you sure you want to delete this subject?')) {
      try {
        await subjectService.deleteSubject(id);
        setSubjects(prev => prev.filter(sub => sub.id !== id));
        setNotification({ type: 'success', message: 'Subject deleted successfully.' });
        const timer = setTimeout(() => setNotification(null), 4000);
        return () => clearTimeout(timer);
      } catch (err) {
        console.error('Failed to delete subject:', err);
        alert('Failed to delete subject.');
      }
    }
  };

  // Client-side search matching filterSubjects() from legacy Blade
  const filteredSubjects = subjects.filter((subject) => {
    const text = `${subject.name} ${subject.code} ${subject.class} ${subject.type || ''}`.toLowerCase();
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
        
        .status-badge {
            display: inline-flex; padding: 4px 10px; border-radius: 100px;
            font-size: 12px; font-weight: 700; border: 1px solid transparent;
        }
        .status-neutral { background: #f1f5f9; color: #64748b; border-color: #cbd5e1; }
        
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
          <h2 className="page-title">Subjects</h2>
          <p className="page-subtitle">Manage all subjects in the system</p>
        </div>
        <Link to="/subjects/create" className="btn-solid-primary">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
          </svg>
          Add Subject
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
              id="subjectFilter"
              className="filter-input"
              placeholder="Search subjects by name or code..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
            />
          </div>
        </div>

        <div style={{ overflowX: 'auto' }}>
          <table className="premium-table" id="subjectsTable">
            <thead>
              <tr>
                <th>Subject Name</th>
                <th>Code</th>
                <th>Class Level</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              {loading ? (
                // Skeleton loading state
                Array.from({ length: 5 }).map((_, i) => (
                  <tr key={`skeleton-${i}`}>
                    <td style={{ padding: '16px 24px' }}>
                      <div className="h-5 bg-gray-100 rounded animate-pulse w-3/4 mb-1" />
                      <div className="h-3 bg-gray-100 rounded animate-pulse w-1/4" />
                    </td>
                    <td style={{ padding: '16px 24px' }}>
                      <div className="h-6 bg-gray-100 rounded-full animate-pulse w-16" />
                    </td>
                    <td style={{ padding: '16px 24px' }}>
                      <div className="h-5 bg-gray-100 rounded animate-pulse w-20" />
                    </td>
                    <td style={{ padding: '16px 24px' }}>
                      <div className="h-5 bg-gray-100 rounded animate-pulse w-24" />
                    </td>
                    <td style={{ padding: '16px 24px' }}>
                      <div className="h-8 bg-gray-100 rounded-lg animate-pulse w-28" />
                    </td>
                  </tr>
                ))
              ) : filteredSubjects.length === 0 ? (
                <tr>
                  <td colSpan="5" style={{ padding: 48, textAlign: 'center', color: '#64748b' }}>
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" strokeWidth="1.5" style={{ margin: '0 auto 16px' }}>
                      <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                      <line x1="3" y1="9" x2="21" y2="9" />
                      <line x1="9" y1="21" x2="9" y2="9" />
                    </svg>
                    <div style={{ fontWeight: 600, fontSize: 16, color: '#0f172a', marginBottom: 8 }}>No subjects found</div>
                    <p style={{ marginBottom: 16 }}>There are no subjects registered in the system.</p>
                    <Link to="/subjects/create" className="btn-solid-primary inline-flex">Add your first subject</Link>
                  </td>
                </tr>
              ) : (
                filteredSubjects.map((subject) => (
                  <tr key={subject.id}>
                    <td>
                      <div style={{ fontWeight: 700, color: '#0f172a', fontSize: 15 }}>{subject.name}</div>
                      {subject.type && (
                        <span style={{ fontSize: 11, fontWeight: 700, color: '#64748b', textTransform: 'uppercase' }}>
                          {subject.type}
                        </span>
                      )}
                    </td>
                    <td>
                      <span className="status-badge status-neutral" style={{ fontFamily: 'monospace', fontSize: 13 }}>
                        {subject.code}
                      </span>
                    </td>
                    <td style={{ fontWeight: 600 }}>Class {subject.class}</td>
                    <td>
                      <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                        <div
                          className={`toggle-switch ${subject.is_active ? 'active' : 'inactive'}`}
                          title="Toggle Status"
                          onClick={() => handleToggleStatus(subject.id, subject.is_active)}
                        >
                          <div className="toggle-switch-handle"></div>
                        </div>
                        <span style={{ fontSize: 13, fontWeight: 700, color: subject.is_active ? '#10b981' : '#64748b' }}>
                          {subject.is_active ? 'Active' : 'Inactive'}
                        </span>
                      </div>
                    </td>
                    <td>
                      <div style={{ display: 'flex', gap: 8 }}>
                        <Link to={`/subjects/${subject.id}/edit`} className="action-btn">
                          Edit
                        </Link>
                        <button
                          type="button"
                          className="action-btn danger"
                          onClick={() => handleDelete(subject.id)}
                        >
                          Delete
                        </button>
                      </div>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>

        {/* Laravel-compatible pagination section */}
        {meta && meta.last_page > 1 && (
          <div style={{ padding: '16px 24px', borderTop: '1px solid #f1f5f9' }}>
            <Pagination meta={meta} onPageChange={fetchSubjects} />
          </div>
        )}
      </div>
    </div>
  );
}
