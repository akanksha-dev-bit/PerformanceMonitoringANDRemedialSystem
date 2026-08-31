/**
 * ============================================================================
 * pages/dashboard/components/RecentStudentsTable.jsx
 * ============================================================================
 * Latest student enrollments table.
 * ============================================================================
 */

import React from 'react';
import { Link } from 'react-router-dom';

export default function RecentStudentsTable({ recentStudents = [] }) {
  const getBadgeClass = (label = '') => {
    const l = label.toLowerCase();
    if (l.includes('good')) return 'status-success';
    if (l.includes('risk')) return 'status-warning';
    if (l.includes('slow')) return 'status-danger';
    return 'status-neutral';
  };

  return (
    <div className="premium-card">
      <div className="card-header">
        <div>
          <h3 className="card-title">Recent Students</h3>
          <p className="card-subtitle">Latest enrollments at the academy.</p>
        </div>
        <Link to="/students" className="btn-solid-dark" style={{ padding: '8px 16px', fontSize: '13px' }}>
          View All
        </Link>
      </div>

      <div style={{ overflowX: 'auto' }}>
        <table className="premium-table">
          <thead>
            <tr>
              <th>Student</th>
              <th>Class/Sec</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            {recentStudents.length > 0 ? (
              recentStudents.map((student) => (
                <tr key={student.id}>
                  <td>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                      <div className="student-avatar">
                        {(student.name || 'U').charAt(0).toUpperCase()}
                      </div>
                      <div>
                        <div style={{ fontWeight: 700, color: '#0f172a' }}>{student.name}</div>
                        <div style={{ fontSize: '12px', color: '#64748b' }}>Roll: {student.roll_no}</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span style={{ fontWeight: 600, color: '#334155' }}>{student.class}</span>{' '}
                    <span style={{ color: '#94a3b8' }}>{student.section}</span>
                  </td>
                  <td>
                    <span className={`status-badge ${getBadgeClass(student.performance_label)}`}>
                      {student.performance_label || 'No Data'}
                    </span>
                  </td>
                </tr>
              ))
            ) : (
              <tr>
                <td colSpan="3" style={{ textAlign: 'center', padding: '32px', color: '#94a3b8', fontWeight: 500 }}>
                  No students enrolled yet.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}
