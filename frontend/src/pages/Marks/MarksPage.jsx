/**
 * ============================================================================
 * pages/Marks/MarksPage.jsx — Marks Listing Page
 * ============================================================================
 * 99% visual match port of backend/resources/views/marks/index.blade.php.
 * Supports:
 *   - Fetching list dynamically via markService.getAll()
 *   - Pagination handling
 *   - Filters by student and subject (for Admin / Teacher)
 *   - Read-only view for Student role
 *   - Record Marks, Edit, and Delete actions
 * ============================================================================
 */

import { useState, useEffect, useCallback } from 'react';
import { Link, useLocation, useSearchParams } from 'react-router-dom';
import markService from '@/services/markService';
import { useAuthContext } from '@/context/AuthContext';
import MarksTable from './components/MarksTable';
import Pagination from '@/components/ui/Pagination';

export default function MarksPage() {
  const { user } = useAuthContext();
  const location = useLocation();
  const [searchParams, setSearchParams] = useSearchParams();

  const isStudent = user?.role === 'student';

  const [marks, setMarks] = useState([]);
  const [students, setStudents] = useState([]);
  const [subjects, setSubjects] = useState([]);
  const [loading, setLoading] = useState(true);
  const [meta, setMeta] = useState(null);
  const [notification, setNotification] = useState(null);

  // Filter state
  const [selectedStudent, setSelectedStudent] = useState(searchParams.get('student_id') || '');
  const [selectedSubject, setSelectedSubject] = useState(searchParams.get('subject_id') || '');

  useEffect(() => {
    if (location.state?.successMessage) {
      setNotification({ type: 'success', message: location.state.successMessage });
      window.history.replaceState({}, document.title);
      const timer = setTimeout(() => setNotification(null), 5000);
      return () => clearTimeout(timer);
    }
  }, [location]);

  const fetchMarks = useCallback(async (page = 1) => {
    setLoading(true);
    try {
      const studentId = searchParams.get('student_id') || '';
      const subjectId = searchParams.get('subject_id') || '';
      const params = { page };
      if (studentId) params.student_id = studentId;
      if (subjectId) params.subject_id = subjectId;

      const res = await markService.getAll(params);
      const data = res.data;

      if (data && data.marks && Array.isArray(data.marks.data)) {
        setMarks(data.marks.data);
        setMeta({
          current_page: data.marks.current_page,
          last_page: data.marks.last_page,
          per_page: data.marks.per_page,
          total: data.marks.total,
        });
        if (data.students) setStudents(data.students);
        if (data.subjects) setSubjects(data.subjects);
      } else if (data && Array.isArray(data.data)) {
        setMarks(data.data);
        setMeta({
          current_page: data.current_page,
          last_page: data.last_page,
          per_page: data.per_page,
          total: data.total,
        });
      } else {
        setMarks(Array.isArray(data) ? data : []);
        setMeta(null);
      }
    } catch (err) {
      console.error('Failed to load marks:', err);
    } finally {
      setLoading(false);
    }
  }, [searchParams]);

  useEffect(() => {
    fetchMarks(1);
  }, [fetchMarks]);

  const handleFilterSubmit = (e) => {
    e.preventDefault();
    const params = {};
    if (selectedStudent) params.student_id = selectedStudent;
    if (selectedSubject) params.subject_id = selectedSubject;
    setSearchParams(params);
  };

  const handleResetFilters = () => {
    setSelectedStudent('');
    setSelectedSubject('');
    setSearchParams({});
  };

  const handleDelete = async (id) => {
    if (window.confirm('Are you sure you want to delete this mark entry?')) {
      try {
        await markService.delete(id);
        setMarks(prev => prev.filter(m => m.id !== id));
        setNotification({ type: 'success', message: 'Mark entry deleted.' });
        const timer = setTimeout(() => setNotification(null), 4000);
        return () => clearTimeout(timer);
      } catch (err) {
        console.error('Failed to delete mark entry:', err);
        alert(err.response?.data?.message || 'Failed to delete mark entry.');
      }
    }
  };

  return (
    <div className="max-w-7xl mx-auto px-4 py-8">
      <style>{`
        .premium-page-header {
          background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);
          border: 1px solid #e2e8f0;
          border-radius: 16px;
          padding: 32px;
          display: flex;
          align-items: center;
          justify-content: space-between;
          margin-bottom: 32px;
          box-shadow: 0 1px 3px rgba(0,0,0,0.05);
          flex-wrap: wrap;
          gap: 16px;
        }
        .pph-title {
          font-family: 'Poppins', sans-serif;
          font-size: 28px;
          font-weight: 700;
          color: #111827;
          letter-spacing: -0.02em;
          margin: 0;
        }
        .pph-subtitle {
          font-size: 15px;
          color: #64748b;
          margin-top: 4px;
          margin-bottom: 0;
        }
        .btn-primary {
          display: inline-flex;
          align-items: center;
          gap: 6px;
          padding: 12px 24px;
          background: linear-gradient(135deg, #6C5CE7, #5A4BD6);
          color: #ffffff;
          border-radius: 12px;
          font-size: 14px;
          font-weight: 700;
          text-decoration: none;
          transition: all 0.2s;
          box-shadow: 0 4px 12px rgba(108,92,231,0.3);
          border: none;
          cursor: pointer;
        }
        .btn-primary:hover {
          transform: translateY(-2px);
          box-shadow: 0 6px 16px rgba(108,92,231,0.4);
          color: #ffffff;
        }
        .premium-toolbar {
          background: #fff;
          border: 1px solid #e2e8f0;
          border-radius: 16px;
          padding: 20px 24px;
          margin-bottom: 24px;
          box-shadow: 0 4px 12px rgba(0,0,0,0.02);
          display: flex;
          gap: 16px;
          align-items: flex-end;
          flex-wrap: wrap;
        }
        .toolbar-group {
          display: flex;
          flex-direction: column;
          gap: 6px;
        }
        .toolbar-label {
          font-size: 12px;
          font-weight: 600;
          color: #64748b;
          text-transform: uppercase;
          letter-spacing: 0.05em;
        }
        .toolbar-select {
          padding: 10px 36px 10px 16px;
          border-radius: 8px;
          border: 1px solid #d1d5db;
          background: #fdfdfd;
          font-size: 14px;
          color: #111827;
          min-width: 200px;
          outline: none;
          transition: all 0.2s;
        }
        .toolbar-select:focus {
          border-color: #6C5CE7;
          box-shadow: 0 0 0 3px rgba(108,92,231,0.1);
        }
        .btn-outline {
          padding: 10px 16px;
          border-radius: 8px;
          border: 1px solid #d1d5db;
          background: #ffffff;
          color: #374151;
          font-size: 14px;
          font-weight: 600;
          text-decoration: none;
          cursor: pointer;
        }
        .btn-outline:hover {
          background: #f3f4f6;
        }
        .premium-data-card {
          background: #fff;
          border: 1px solid #e2e8f0;
          border-radius: 16px;
          box-shadow: 0 10px 40px rgba(0,0,0,0.03);
          overflow: hidden;
        }
        .alert-success {
          background: #d1fae5;
          border: 1px solid #a7f3d0;
          color: #065f46;
          padding: 14px 20px;
          border-radius: 12px;
          font-size: 14px;
          font-weight: 600;
          display: flex;
          align-items: center;
          margin-bottom: 24px;
        }
      `}</style>

      {/* Header */}
      <div className="premium-page-header">
        <div>
          <h2 className="pph-title">{isStudent ? 'My Marks' : 'Marks Management'}</h2>
          <p className="pph-subtitle">
            {isStudent ? 'View all your recorded academic performance' : 'View and manage all recorded marks'}
          </p>
        </div>
        {!isStudent && (
          <Link to="/marks/create" className="btn-primary" id="add-marks-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" width="18" height="18" style={{ marginRight: '6px' }}>
              <line x1="12" y1="5" x2="12" y2="19" />
              <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Record Marks
          </Link>
        )}
      </div>

      {notification && (
        <div className="alert-success">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" style={{ marginRight: '8px' }}>
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
          </svg>
          <span>{notification.message}</span>
        </div>
      )}

      {/* Filters (Hide for students) */}
      {!isStudent && (
        <form onSubmit={handleFilterSubmit} className="premium-toolbar">
          <div className="toolbar-group">
            <label className="toolbar-label">Student</label>
            <select
              name="student_id"
              className="toolbar-select"
              id="marks-student-filter"
              value={selectedStudent}
              onChange={(e) => setSelectedStudent(e.target.value)}
            >
              <option value="">All Students</option>
              {students.map((s) => (
                <option key={s.id} value={s.id}>
                  {s.name} ({s.roll_no})
                </option>
              ))}
            </select>
          </div>

          <div className="toolbar-group">
            <label className="toolbar-label">Subject</label>
            <select
              name="subject_id"
              className="toolbar-select"
              id="marks-subject-filter"
              value={selectedSubject}
              onChange={(e) => setSelectedSubject(e.target.value)}
            >
              <option value="">All Subjects</option>
              {subjects.map((sub) => (
                <option key={sub.id} value={sub.id}>
                  {sub.name}
                </option>
              ))}
            </select>
          </div>

          <div style={{ display: 'flex', gap: '12px', marginLeft: 'auto' }}>
            <button
              type="button"
              className="btn-outline"
              id="marks-reset-btn"
              onClick={handleResetFilters}
            >
              Reset
            </button>
            <button
              type="submit"
              className="btn-primary"
              id="marks-filter-btn"
              style={{ padding: '10px 20px' }}
            >
              Apply Filters
            </button>
          </div>
        </form>
      )}

      {/* Data Card */}
      <div className="premium-data-card">
        <MarksTable
          marks={marks}
          isStudent={isStudent}
          onDelete={handleDelete}
          loading={loading}
        />

        {meta && meta.last_page > 1 && (
          <div style={{ padding: '16px 24px', borderTop: '1px solid #e2e8f0' }}>
            <Pagination meta={meta} onPageChange={(page) => fetchMarks(page)} />
          </div>
        )}
      </div>
    </div>
  );
}
