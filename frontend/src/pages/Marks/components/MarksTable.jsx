/**
 * ============================================================================
 * pages/Marks/components/MarksTable.jsx — Marks Table Component
 * ============================================================================
 * 99% visual match port of table in backend/resources/views/marks/index.blade.php.
 * Renders student info, score percentages, Pass/Fail badges, and action buttons.
 * ============================================================================
 */

import { Link } from 'react-router-dom';

export default function MarksTable({
  marks = [],
  isStudent = false,
  onDelete,
  loading = false,
}) {
  const getInitials = (name) => {
    if (!name) return 'ST';
    return name.trim().substring(0, 2).toUpperCase();
  };

  const formatExamType = (type) => {
    if (!type) return '—';
    return type.replace('_', ' ').replace(/\b\w/g, c => c.toUpperCase());
  };

  const getScoreColor = (pct) => {
    const p = parseFloat(pct);
    if (isNaN(p)) return '#64748b';
    if (p >= 75) return '#10b981'; // var(--success)
    if (p >= 40) return '#f59e0b'; // var(--warning)
    return '#ef4444';             // var(--error)
  };

  return (
    <div>
      <style>{`
        .premium-table-wrapper {
          width: 100%;
          overflow-x: auto;
        }
        .premium-table {
          width: 100%;
          border-collapse: collapse;
        }
        .premium-table th {
          text-align: left;
          padding: 16px 24px;
          font-size: 12px;
          font-weight: 600;
          color: #6b7280;
          text-transform: uppercase;
          letter-spacing: 0.05em;
          background: #fdfdfd;
          border-bottom: 1px solid #e5e7eb;
        }
        .premium-table td {
          padding: 16px 24px;
          font-size: 14px;
          border-bottom: 1px solid rgba(0,0,0,0.03);
          vertical-align: middle;
          color: #374151;
        }
        .premium-table tr:last-child td {
          border-bottom: none;
        }
        .premium-table tbody tr:hover {
          background: rgba(108,92,231,0.02);
        }
        .st-cell {
          display: flex;
          align-items: center;
          gap: 12px;
        }
        .st-avatar {
          width: 40px;
          height: 40px;
          border-radius: 10px;
          display: flex;
          align-items: center;
          justify-content: center;
          font-weight: 700;
          font-size: 13px;
          background: #e0e7ff;
          color: #6C5CE7;
        }
        .st-name {
          font-weight: 600;
          color: #111827;
          font-size: 14px;
        }
        .st-roll {
          font-size: 12px;
          color: #6b7280;
          margin-top: 2px;
        }
        .badge {
          display: inline-flex;
          align-items: center;
          font-weight: 600;
          border-radius: 9999px;
        }
        .badge-primary {
          background: #e0e7ff;
          color: #4338ca;
        }
        .badge-success {
          background: #d1fae5;
          color: #065f46;
        }
        .badge-error {
          background: #fee2e2;
          color: #991b1b;
        }
        .btn-danger {
          padding: 6px 12px;
          font-size: 12px;
          border-radius: 6px;
          border: 1px solid #fca5a5;
          background: #fef2f2;
          color: #ef4444;
          cursor: pointer;
          font-weight: 600;
          transition: all 0.2s;
        }
        .btn-danger:hover {
          background: #ef4444;
          color: #ffffff;
        }
        .btn-edit {
          padding: 6px 12px;
          font-size: 12px;
          border-radius: 6px;
          border: 1px solid #cbd5e1;
          background: #f8fafc;
          color: #334155;
          text-decoration: none;
          display: inline-flex;
          align-items: center;
          gap: 4px;
          font-weight: 600;
          transition: all 0.2s;
        }
        .btn-edit:hover {
          background: #e2e8f0;
          color: #0f172a;
        }
      `}</style>

      <div className="premium-table-wrapper">
        <table className="premium-table">
          <thead>
            <tr>
              {!isStudent && <th>Student Info</th>}
              <th>Subject</th>
              <th>Term / Exam</th>
              <th>Score</th>
              <th>Status</th>
              <th>Year</th>
              {!isStudent && <th>Action</th>}
            </tr>
          </thead>
          <tbody>
            {loading ? (
              Array.from({ length: 5 }).map((_, i) => (
                <tr key={`skeleton-${i}`}>
                  {!isStudent && (
                    <td>
                      <div className="st-cell">
                        <div className="w-10 h-10 bg-gray-200 rounded-lg animate-pulse" />
                        <div>
                          <div className="h-4 bg-gray-200 rounded w-28 mb-1 animate-pulse" />
                          <div className="h-3 bg-gray-200 rounded w-16 animate-pulse" />
                        </div>
                      </div>
                    </td>
                  )}
                  <td><div className="h-4 bg-gray-200 rounded w-24 animate-pulse" /></td>
                  <td><div className="h-4 bg-gray-200 rounded w-20 animate-pulse" /></td>
                  <td><div className="h-5 bg-gray-200 rounded w-16 animate-pulse" /></td>
                  <td><div className="h-6 bg-gray-200 rounded-full w-14 animate-pulse" /></td>
                  <td><div className="h-4 bg-gray-200 rounded w-16 animate-pulse" /></td>
                  {!isStudent && <td><div className="h-7 bg-gray-200 rounded w-20 animate-pulse" /></td>}
                </tr>
              ))
            ) : marks.length === 0 ? (
              <tr>
                <td colSpan={isStudent ? 5 : 7} style={{ textAlign: 'center', padding: '64px' }}>
                  <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
                    <div style={{
                      width: '80px', height: '80px', borderRadius: '50%',
                      background: '#f1f5f9', color: '#64748b',
                      display: 'flex', alignItems: 'center', justifyContent: 'center',
                      marginBottom: '16px'
                    }}>
                      <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                      </svg>
                    </div>
                    <h3 style={{ fontSize: '18px', fontWeight: '600', color: '#111827', margin: '0 0 4px 0' }}>
                      No Marks Recorded
                    </h3>
                    <p style={{ color: '#64748b', margin: '0 0 16px 0', fontSize: '14px' }}>
                      There are no academic records matching your criteria.
                    </p>
                    {!isStudent && (
                      <Link to="/marks/create" className="btn-primary" style={{ padding: '10px 20px', borderRadius: '10px', color: '#fff', textDecoration: 'none', fontWeight: '700' }}>
                        Record Marks Now
                      </Link>
                    )}
                  </div>
                </td>
              </tr>
            ) : (
              marks.map((mark) => {
                const studentName = mark.student?.name || mark.student?.user?.name || '—';
                const studentRoll = mark.student?.roll_no || '—';
                const subjectName = mark.subject?.name || '—';
                const pct = mark.percentage ?? (mark.max_marks > 0 ? ((mark.marks_obtained / mark.max_marks) * 100).toFixed(2) : 0);
                const isPass = mark.is_pass ?? (pct >= 40);

                return (
                  <tr key={mark.id}>
                    {!isStudent && (
                      <td>
                        <div className="st-cell">
                          <div className="st-avatar">{getInitials(studentName)}</div>
                          <div>
                            <div className="st-name">{studentName}</div>
                            <div className="st-roll">{studentRoll}</div>
                          </div>
                        </div>
                      </td>
                    )}
                    <td style={{ fontWeight: 600, color: '#111827' }}>
                      {subjectName}
                    </td>
                    <td>
                      <div style={{ display: 'flex', flexDirection: 'column' }}>
                        <span className="badge badge-primary" style={{ width: 'fit-content', fontSize: '11px', padding: '2px 8px' }}>
                          {formatExamType(mark.exam_type)}
                        </span>
                      </div>
                    </td>
                    <td>
                      <div style={{ display: 'flex', flexDirection: 'column', gap: '2px' }}>
                        <span style={{
                          fontFamily: "'Poppins', sans-serif",
                          fontSize: '16px',
                          fontWeight: 700,
                          color: getScoreColor(pct)
                        }}>
                          {pct}%
                        </span>
                        <span style={{ fontSize: '12px', color: '#6b7280' }}>
                          {mark.marks_obtained} / {mark.max_marks}
                        </span>
                      </div>
                    </td>
                    <td>
                      {isPass ? (
                        <span className="badge badge-success" style={{ padding: '6px 12px' }}>Pass</span>
                      ) : (
                        <span className="badge badge-error" style={{ padding: '6px 12px' }}>Fail</span>
                      )}
                    </td>
                    <td style={{ color: '#6b7280' }}>{mark.academic_year || '—'}</td>
                    {!isStudent && (
                      <td>
                        <div style={{ display: 'flex', gap: '8px', alignItems: 'center' }}>
                          <Link to={`/marks/${mark.id}/edit`} className="btn-edit" id={`edit-mark-${mark.id}`}>
                            Edit
                          </Link>
                          <button
                            type="button"
                            className="btn-danger"
                            id={`delete-mark-${mark.id}`}
                            onClick={() => onDelete && onDelete(mark.id)}
                          >
                            Delete
                          </button>
                        </div>
                      </td>
                    )}
                  </tr>
                );
              })
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}
