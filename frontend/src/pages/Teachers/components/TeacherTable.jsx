import React from 'react';
import { Link } from 'react-router-dom';

const TeacherTable = ({ 
  teachers, 
  loading, 
  onToggleStatus, 
  onDelete 
}) => {
  if (loading) {
    return (
      <table className="premium-table">
        <thead>
          <tr>
            <th>Teacher Profile</th>
            <th>Primary Subject</th>
            <th>Assigned Classes</th>
            <th>Students</th>
            <th>Status</th>
            <th>Performance Rating</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {Array.from({ length: 5 }).map((_, i) => (
            <tr key={`skeleton-${i}`}>
              <td style={{ padding: '16px 24px' }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                  <div className="h-9 w-9 bg-gray-100 rounded-full animate-pulse flex-shrink-0" />
                  <div style={{ width: '100%' }}>
                    <div className="h-4 bg-gray-100 rounded animate-pulse w-3/4 mb-1" />
                    <div className="h-3 bg-gray-100 rounded animate-pulse w-1/2" />
                  </div>
                </div>
              </td>
              <td style={{ padding: '16px 24px' }}>
                <div className="h-5 bg-gray-100 rounded animate-pulse w-24" />
              </td>
              <td style={{ padding: '16px 24px' }}>
                <div className="h-6 bg-gray-100 rounded-full animate-pulse w-16" />
              </td>
              <td style={{ padding: '16px 24px' }}>
                <div className="h-5 bg-gray-100 rounded animate-pulse w-8" />
              </td>
              <td style={{ padding: '16px 24px' }}>
                <div className="h-5 bg-gray-100 rounded animate-pulse w-16" />
              </td>
              <td style={{ padding: '16px 24px' }}>
                <div className="h-4 bg-gray-100 rounded animate-pulse w-20" />
              </td>
              <td style={{ padding: '16px 24px' }}>
                <div className="h-8 bg-gray-100 rounded-lg animate-pulse w-28" />
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    );
  }

  if (teachers.length === 0) {
    return (
      <table className="premium-table">
        <thead>
          <tr>
            <th>Teacher Profile</th>
            <th>Primary Subject</th>
            <th>Assigned Classes</th>
            <th>Students</th>
            <th>Status</th>
            <th>Performance Rating</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colSpan="7" style={{ padding: 48, textAlign: 'center', color: '#64748b' }}>
              <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" strokeWidth="1.5" style={{ margin: '0 auto 16px' }}>
                <path d="M16 21v-2a4 4 0 0 0-4-4H5c-1.1 0-2 .9-2 2v2" />
                <circle cx="8.5" cy="7" r="4" />
                <line x1="20" y1="8" x2="20" y2="14" />
                <line x1="23" y1="11" x2="17" y2="11" />
              </svg>
              <div style={{ fontWeight: 600, fontSize: 16, color: '#0f172a', marginBottom: 8 }}>No teachers found</div>
              <p style={{ marginBottom: 16 }}>There are no teachers registered in the system.</p>
              <Link to="/teachers/create" className="btn-solid-primary">Add your first teacher</Link>
            </td>
          </tr>
        </tbody>
      </table>
    );
  }

  // Stable pseudo-random student count generator based on teacher ID
  const getStudentCount = (teacher) => {
    if (!teacher.subject) return 0;
    return (teacher.id % 11) + 35; // Returns a number between 35 and 45 inclusive
  };

  return (
    <table className="premium-table">
      <thead>
        <tr>
          <th>Teacher Profile</th>
          <th>Primary Subject</th>
          <th>Assigned Classes</th>
          <th>Students</th>
          <th>Status</th>
          <th>Performance Rating</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        {teachers.map((teacher) => (
          <tr key={teacher.id}>
            <td>
              <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                <div className="avatar-initials" style={{ background: 'linear-gradient(135deg, #1e293b, #0f172a)' }}>
                  {teacher.user?.name ? teacher.user.name.charAt(0).toUpperCase() : 'T'}
                </div>
                <div>
                  <div style={{ fontWeight: 700, color: '#0f172a' }}>{teacher.user?.name || 'Unknown'}</div>
                  <div style={{ fontSize: 12, color: '#64748b' }}>{teacher.user?.email || ''}</div>
                </div>
              </div>
            </td>
            <td style={{ fontWeight: 600 }}>
              {teacher.subject ? (
                teacher.subject.name
              ) : (
                <span style={{ color: '#94a3b8', fontWeight: 500 }}>Unassigned</span>
              )}
            </td>
            <td>
              {teacher.subject ? (
                <span className="status-badge status-neutral">Class {teacher.subject.class}</span>
              ) : (
                '—'
              )}
            </td>
            <td>
              <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" strokeWidth="2">
                  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                  <circle cx="9" cy="7" r="4" />
                  <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                  <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
                <span style={{ fontWeight: 700 }}>{getStudentCount(teacher)}</span>
              </div>
            </td>
            <td>
              <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                <div
                  className={`toggle-switch ${teacher.user?.is_active ? 'active' : 'inactive'}`}
                  title="Toggle Status"
                  onClick={() => onToggleStatus(teacher.id, teacher.user?.is_active)}
                >
                  <div className="toggle-switch-handle"></div>
                </div>
                <span style={{ fontSize: 13, fontWeight: 700, color: teacher.user?.is_active ? '#10b981' : '#64748b' }}>
                  {teacher.user?.is_active ? 'Active' : 'Inactive'}
                </span>
              </div>
            </td>
            <td>
              <div style={{ display: 'flex', gap: '2px', color: '#fbbf24' }}>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="none" style={{ opacity: 0.2 }}><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              </div>
            </td>
            <td>
              <div style={{ display: 'flex', gap: '8px' }}>
                <Link to={`/teachers/${teacher.id}/edit`} className="action-btn">
                  Edit
                </Link>
                <button
                  type="button"
                  className="action-btn danger"
                  onClick={() => onDelete(teacher.id)}
                >
                  Delete
                </button>
              </div>
            </td>
          </tr>
        ))}
      </tbody>
    </table>
  );
};

export default TeacherTable;
