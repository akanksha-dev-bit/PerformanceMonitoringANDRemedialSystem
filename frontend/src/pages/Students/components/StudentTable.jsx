import React from 'react';
import { Link } from 'react-router-dom';

const StudentTable = ({ 
  students, 
  loading, 
  onToggleStatus, 
  onDelete,
  onQuickView
}) => {
  if (loading) {
    return (
      <table className="premium-table">
        <thead>
          <tr>
            <th>Student Profile</th>
            <th>Roll No</th>
            <th>Class</th>
            <th>Assessments</th>
            <th>Average Score</th>
            <th>Status</th>
            <th>Academic Status</th>
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
                <div className="h-5 bg-gray-100 rounded animate-pulse w-16" />
              </td>
              <td style={{ padding: '16px 24px' }}>
                <div className="h-5 bg-gray-100 rounded animate-pulse w-12" />
              </td>
              <td style={{ padding: '16px 24px' }}>
                <div className="h-6 bg-gray-100 rounded-full animate-pulse w-8" />
              </td>
              <td style={{ padding: '16px 24px' }}>
                <div className="h-4 bg-gray-100 rounded animate-pulse w-24" />
              </td>
              <td style={{ padding: '16px 24px' }}>
                <div className="h-5 bg-gray-100 rounded animate-pulse w-16" />
              </td>
              <td style={{ padding: '16px 24px' }}>
                <div className="h-6 bg-gray-100 rounded-full animate-pulse w-16" />
              </td>
              <td style={{ padding: '16px 24px' }}>
                <div className="h-8 bg-gray-100 rounded-lg animate-pulse w-24" />
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    );
  }

  if (students.length === 0) {
    return (
      <table className="premium-table">
        <thead>
          <tr>
            <th>Student Profile</th>
            <th>Roll No</th>
            <th>Class</th>
            <th>Assessments</th>
            <th>Average Score</th>
            <th>Status</th>
            <th>Academic Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colSpan="8" style={{ padding: 48, textAlign: 'center', color: '#64748b' }}>
              <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" strokeWidth="1.5" style={{ margin: '0 auto 16px' }}>
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
              </svg>
              <div style={{ fontWeight: 600, fontSize: 16, color: '#0f172a', marginBottom: 8 }}>No students found</div>
              <p style={{ marginBottom: 16 }}>There are no students registered in the system matching the criteria.</p>
              <Link to="/students/create" className="btn-solid-primary">Add your first student</Link>
            </td>
          </tr>
        </tbody>
      </table>
    );
  }

  return (
    <table className="premium-table">
      <thead>
        <tr>
          <th>Student Profile</th>
          <th>Roll No</th>
          <th>Class</th>
          <th>Assessments</th>
          <th>Average Score</th>
          <th>Status</th>
          <th>Academic Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        {students.map((student) => {
          const avg = student.average_percentage;
          const marksCount = student.marks_count || 0;
          
          let scoreColor = '#94a3b8';
          if (marksCount > 0) {
            if (avg >= 75) scoreColor = '#10b981';
            else if (avg >= 40) scoreColor = '#f59e0b';
            else scoreColor = '#ef4444';
          }

          let statusBadgeClass = 'status-badge status-neutral';
          let statusText = 'N/A';
          if (marksCount > 0) {
            if (student.is_slow_learner) {
              statusBadgeClass = 'status-badge status-danger';
              statusText = 'Slow Learner';
            } else if (avg >= 75) {
              statusBadgeClass = 'status-badge status-success';
              statusText = 'Excellent';
            } else {
              statusBadgeClass = 'status-badge status-warning';
              statusText = 'Average';
            }
          }

          // Initial letters
          const initials = student.name ? student.name.substring(0, 2).toUpperCase() : 'ST';

          return (
            <tr key={student.id}>
              <td>
                <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                  <div className="avatar-initials" style={{ background: 'linear-gradient(135deg, #6C5CE7, #5A4BD6)' }}>
                    {initials}
                  </div>
                  <div>
                    <div style={{ fontWeight: 700, color: '#0f172a' }}>{student.name}</div>
                    <div style={{ fontSize: 12, color: '#64748b' }}>{student.email || 'No email'}</div>
                  </div>
                </div>
              </td>
              <td>
                <span className="status-badge status-neutral" style={{ background: '#f1f5f9', textTransform: 'none' }}>
                  {student.roll_no}
                </span>
              </td>
              <td style={{ fontWeight: 600 }}>
                {student.section_name || '—'}
              </td>
              <td>
                <span className="status-badge status-neutral" style={{ background: '#eef2ff', color: '#6366f1', borderColor: '#e0e7ff' }}>
                  {marksCount}
                </span>
              </td>
              <td>
                {marksCount === 0 ? (
                  <span style={{ fontWeight: 600, color: '#94a3b8', fontStyle: 'italic', fontSize: '13px' }}>Not Evaluated</span>
                ) : (
                  <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                    <span style={{ fontWeight: 700, color: scoreColor }}>{avg}%</span>
                    <div style={{ width: '60px', height: '6px', background: '#f1f5f9', borderRadius: '3px', overflow: 'hidden' }}>
                      <div style={{ height: '100%', width: `${avg}%`, background: scoreColor }} />
                    </div>
                  </div>
                )}
              </td>
              <td>
                <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                  <div
                    className={`toggle-switch ${student.is_active ? 'active' : 'inactive'}`}
                    title="Toggle Status"
                    onClick={() => onToggleStatus(student.id, student.is_active)}
                  >
                    <div className="toggle-switch-handle" />
                  </div>
                  <span style={{ fontSize: 13, fontWeight: 700, color: student.is_active ? '#10b981' : '#64748b' }}>
                    {student.is_active ? 'Active' : 'Inactive'}
                  </span>
                </div>
              </td>
              <td>
                <span className={statusBadgeClass}>{statusText}</span>
              </td>
              <td>
                <div style={{ display: 'flex', gap: '6px' }}>
                  <button 
                    type="button" 
                    className="action-btn"
                    onClick={() => onQuickView(student)}
                    title="Quick View"
                  >
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                      <circle cx="12" cy="12" r="3"/>
                    </svg>
                  </button>
                  <Link to={`/students/${student.id}`} className="action-btn primary" id={`view-student-${student.id}`}>
                    Profile
                  </Link>
                  <Link to={`/students/${student.id}/edit`} className="action-btn" id={`edit-student-${student.id}`}>
                    Edit
                  </Link>
                  <button
                    type="button"
                    className="action-btn danger"
                    onClick={() => onDelete(student.id)}
                    title="Delete Student"
                  >
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          );
        })}
      </tbody>
    </table>
  );
};

export default StudentTable;
