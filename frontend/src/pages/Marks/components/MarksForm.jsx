/**
 * ============================================================================
 * pages/Marks/components/MarksForm.jsx — Marks Entry & Edit Form
 * ============================================================================
 * 99% visual match port of backend/resources/views/marks/create.blade.php.
 * Replicates form styling, field hierarchy, class-based subject filtering,
 * and handles Laravel HTTP 422 validation response errors.
 * ============================================================================
 */

import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';

export default function MarksForm({
  initialData = {},
  students = [],
  subjects = [],
  onSubmit,
  isSubmitting = false,
  errors = {},
  isEditMode = false,
}) {
  const [formData, setFormData] = useState({
    student_id: initialData.student_id || '',
    subject_id: initialData.subject_id || '',
    marks_obtained: initialData.marks_obtained ?? '',
    max_marks: initialData.max_marks ?? 100,
    exam_type: initialData.exam_type || 'unit_test',
    academic_year: initialData.academic_year || '2024-25',
    remarks: initialData.remarks || '',
  });

  const [filteredSubjects, setFilteredSubjects] = useState(subjects);

  // Synchronize form values when initialData or options change
  useEffect(() => {
    if (initialData && Object.keys(initialData).length > 0) {
      setFormData({
        student_id: initialData.student_id || '',
        subject_id: initialData.subject_id || '',
        marks_obtained: initialData.marks_obtained ?? '',
        max_marks: initialData.max_marks ?? 100,
        exam_type: initialData.exam_type || 'unit_test',
        academic_year: initialData.academic_year || '2024-25',
        remarks: initialData.remarks || '',
      });
    }
  }, [initialData]);

  // Filter subjects based on selected student's class (matching Blade's filterSubjects logic)
  useEffect(() => {
    if (!formData.student_id) {
      setFilteredSubjects([]);
      return;
    }

    const selectedStudent = students.find(s => String(s.id) === String(formData.student_id));
    if (!selectedStudent) {
      setFilteredSubjects(subjects);
      return;
    }

    const stClass = String(selectedStudent.class || '').trim().toLowerCase();

    const matched = subjects.filter(sub => {
      if (!sub.class || sub.class === 'All' || sub.class === 'all') return true;
      const subClass = String(sub.class).trim().toLowerCase();
      return subClass === stClass;
    });

    setFilteredSubjects(matched.length > 0 ? matched : subjects);
  }, [formData.student_id, students, subjects]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => {
      const nextState = { ...prev, [name]: value };
      // If student changed, check if current subject is still valid
      if (name === 'student_id') {
        nextState.subject_id = '';
      }
      return nextState;
    });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    onSubmit(formData);
  };

  return (
    <div>
      <style>{`
        .premium-form-card {
          background: #fff;
          border: 1px solid #e2e8f0;
          border-radius: 16px;
          box-shadow: 0 10px 40px rgba(0,0,0,0.03);
          max-width: 760px;
          margin: 0 auto;
          overflow: hidden;
        }
        .pfc-header {
          background: linear-gradient(135deg, #f8faff 0%, #ffffff 100%);
          padding: 24px 32px;
          border-bottom: 1px solid #e2e8f0;
          display: flex; align-items: center; justify-content: space-between;
        }
        .pfc-body {
          padding: 32px;
        }
        .premium-form-group {
          margin-bottom: 24px;
        }
        .premium-label {
          display: block;
          font-size: 13px;
          font-weight: 600;
          color: #374151;
          margin-bottom: 8px;
        }
        .premium-input {
          width: 100%;
          padding: 12px 16px;
          border-radius: 10px;
          border: 1px solid #d1d5db;
          background: #fdfdfd;
          font-size: 14px;
          font-family: 'Inter', sans-serif;
          color: #111827;
          transition: all 0.2s ease;
          box-shadow: inset 0 1px 2px rgba(0,0,0,0.02);
        }
        .premium-input:focus {
          outline: none;
          border-color: #6C5CE7;
          background: #fff;
          box-shadow: 0 0 0 4px rgba(108,92,231,0.1);
        }
        textarea.premium-input {
          resize: vertical;
          min-height: 100px;
        }
        .premium-row {
          display: grid;
          grid-template-columns: 1fr 1fr;
          gap: 24px;
        }
        .pfc-footer {
          padding: 24px 32px;
          background: #f9fafb;
          border-top: 1px solid #e2e8f0;
          display: flex;
          justify-content: flex-end;
          gap: 16px;
        }
        .form-error {
          color: #ef4444;
          font-size: 12px;
          margin-top: 6px;
        }
        .btn-outline {
          padding: 10px 20px;
          border-radius: 10px;
          border: 1px solid #d1d5db;
          background: #ffffff;
          color: #374151;
          font-size: 14px;
          font-weight: 600;
          text-decoration: none;
          display: inline-flex;
          align-items: center;
          gap: 6px;
          cursor: pointer;
        }
        .btn-outline:hover {
          background: #f3f4f6;
        }
        .btn-primary {
          padding: 10px 24px;
          border-radius: 10px;
          border: none;
          background: linear-gradient(135deg, #6C5CE7, #5A4BD6);
          color: #ffffff;
          font-size: 14px;
          font-weight: 700;
          cursor: pointer;
          transition: all 0.2s;
          box-shadow: 0 4px 12px rgba(108,92,231,0.3);
        }
        .btn-primary:disabled {
          opacity: 0.6;
          cursor: not-allowed;
        }
        .btn-primary:hover:not(:disabled) {
          transform: translateY(-1px);
          box-shadow: 0 6px 16px rgba(108,92,231,0.4);
        }
        @media (max-width: 640px) {
          .premium-row { grid-template-columns: 1fr; gap: 0; }
        }
      `}</style>

      <div className="premium-form-card">
        <div className="pfc-header">
          <div style={{ fontWeight: 600, color: '#111827', fontSize: '16px', display: 'flex', alignItems: 'center', gap: '8px' }}>
            <svg width="20" height="20" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24" style={{ color: '#6C5CE7' }}>
              <path d="M12 20h9M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
            </svg>
            {isEditMode ? 'Edit Academic Record' : 'Academic Record'}
          </div>
          <Link to="/marks" className="btn-outline" style={{ padding: '6px 14px', fontSize: '13px' }} id="back-marks-btn">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Marks
          </Link>
        </div>

        <form onSubmit={handleSubmit} id={isEditMode ? 'edit-marks-form' : 'create-marks-form'}>
          <div className="pfc-body">
            <div className="premium-row">
              {/* Select Student */}
              <div className="premium-form-group">
                <label className="premium-label" htmlFor="student_id">Select Student *</label>
                <select
                  name="student_id"
                  id="student_id"
                  className="premium-input"
                  value={formData.student_id}
                  onChange={handleChange}
                  required
                >
                  <option value="">Choose a student…</option>
                  {students.map((s) => (
                    <option key={s.id} value={s.id}>
                      {s.name} — {s.roll_no} {s.class ? `(Class ${s.class})` : ''}
                    </option>
                  ))}
                </select>
                {errors.student_id && (
                  <div className="form-error">{Array.isArray(errors.student_id) ? errors.student_id[0] : errors.student_id}</div>
                )}
              </div>

              {/* Select Subject */}
              <div className="premium-form-group">
                <label className="premium-label" htmlFor="subject_id">Select Subject *</label>
                <select
                  name="subject_id"
                  id="subject_id"
                  className="premium-input"
                  value={formData.subject_id}
                  onChange={handleChange}
                  required
                >
                  <option value="">Choose a subject…</option>
                  {filteredSubjects.map((sub) => (
                    <option key={sub.id} value={sub.id}>
                      {sub.name} ({sub.code})
                    </option>
                  ))}
                </select>
                {errors.subject_id && (
                  <div className="form-error">{Array.isArray(errors.subject_id) ? errors.subject_id[0] : errors.subject_id}</div>
                )}
              </div>
            </div>

            <div className="premium-row">
              {/* Marks Obtained */}
              <div className="premium-form-group">
                <label className="premium-label" htmlFor="marks_obtained">Marks Obtained *</label>
                <input
                  type="number"
                  id="marks_obtained"
                  name="marks_obtained"
                  value={formData.marks_obtained}
                  onChange={handleChange}
                  min="0"
                  className="premium-input"
                  placeholder="e.g. 78"
                  required
                />
                {errors.marks_obtained && (
                  <div className="form-error">{Array.isArray(errors.marks_obtained) ? errors.marks_obtained[0] : errors.marks_obtained}</div>
                )}
              </div>

              {/* Maximum Marks */}
              <div className="premium-form-group">
                <label className="premium-label" htmlFor="max_marks">Maximum Marks *</label>
                <input
                  type="number"
                  id="max_marks"
                  name="max_marks"
                  value={formData.max_marks}
                  onChange={handleChange}
                  min="1"
                  className="premium-input"
                  required
                />
                {errors.max_marks && (
                  <div className="form-error">{Array.isArray(errors.max_marks) ? errors.max_marks[0] : errors.max_marks}</div>
                )}
              </div>
            </div>

            <div className="premium-row">
              {/* Exam Type */}
              <div className="premium-form-group">
                <label className="premium-label" htmlFor="exam_type">Exam Type *</label>
                <select
                  name="exam_type"
                  id="exam_type"
                  className="premium-input"
                  value={formData.exam_type}
                  onChange={handleChange}
                  required
                >
                  <option value="unit_test">Unit Test</option>
                  <option value="midterm">Midterm</option>
                  <option value="final">Final</option>
                  <option value="practical">Practical</option>
                </select>
                {errors.exam_type && (
                  <div className="form-error">{Array.isArray(errors.exam_type) ? errors.exam_type[0] : errors.exam_type}</div>
                )}
              </div>

              {/* Academic Year */}
              <div className="premium-form-group">
                <label className="premium-label" htmlFor="academic_year">Academic Year *</label>
                <input
                  type="text"
                  id="academic_year"
                  name="academic_year"
                  value={formData.academic_year}
                  onChange={handleChange}
                  className="premium-input"
                  placeholder="2024-25"
                  required
                />
                {errors.academic_year && (
                  <div className="form-error">{Array.isArray(errors.academic_year) ? errors.academic_year[0] : errors.academic_year}</div>
                )}
              </div>
            </div>

            {/* Remarks */}
            <div className="premium-form-group" style={{ marginBottom: 0 }}>
              <label className="premium-label" htmlFor="remarks">Teacher Remarks</label>
              <textarea
                id="remarks"
                name="remarks"
                className="premium-input"
                value={formData.remarks}
                onChange={handleChange}
                placeholder="Optional notes about the student's performance…"
              />
              {errors.remarks && (
                <div className="form-error">{Array.isArray(errors.remarks) ? errors.remarks[0] : errors.remarks}</div>
              )}
            </div>
          </div>

          <div className="pfc-footer">
            <Link to="/marks" className="btn-outline">Cancel</Link>
            <button type="submit" className="btn-primary" id="submit-marks-btn" disabled={isSubmitting}>
              {isSubmitting ? (isEditMode ? 'Updating...' : 'Saving...') : (isEditMode ? 'Update Marks' : 'Save Marks')}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
