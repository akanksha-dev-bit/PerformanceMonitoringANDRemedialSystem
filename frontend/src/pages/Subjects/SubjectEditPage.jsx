import React, { useState, useEffect } from 'react';
import { useNavigate, useParams, Link } from 'react-router-dom';
import subjectService from '../../services/subjectService';
import SubjectForm from './components/SubjectForm';

const SubjectEditPage = () => {
  const navigate = useNavigate();
  const { id } = useParams();
  const [formData, setFormData] = useState({
    name: '',
    code: '',
    class: '',
    type: '',
    max_marks: 100,
    is_active: true,
  });
  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(false);
  const [fetching, setFetching] = useState(true);

  useEffect(() => {
    const fetchSubject = async () => {
      try {
        const response = await subjectService.getSubject(id);
        setFormData({
          name: response.data.name || '',
          code: response.data.code || '',
          class: String(response.data.class) || '',
          type: response.data.type || '',
          max_marks: response.data.max_marks ?? 100,
          is_active: !!response.data.is_active,
        });
      } catch (err) {
        console.error('Error fetching subject details:', err);
        alert('Could not load subject details.');
        navigate('/subjects');
      } finally {
        setFetching(false);
      }
    };
    fetchSubject();
  }, [id, navigate]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setErrors({});

    try {
      await subjectService.updateSubject(id, formData);
      navigate('/subjects', { state: { successMessage: 'Subject updated successfully.' } });
    } catch (err) {
      if (err.response?.status === 422) {
        setErrors(err.response.data.errors || {});
      } else {
        console.error('Error updating subject:', err);
        alert(err.response?.data?.message || 'Something went wrong while saving updates.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="page-content">
      <div className="page-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
        <div>
          <h2 className="page-title">Edit Subject</h2>
          <p className="page-subtitle">Update details for {fetching ? '...' : formData.name}</p>
        </div>
        <Link to="/subjects" className="btn-back-premium">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
          </svg>
          Back to Subjects
        </Link>
      </div>

      <div className="page-layout">
        {/* Main Form Card */}
        <div className="form-create-card">
          {fetching ? (
            <div style={{ textAlign: 'center', padding: '40px 0', color: '#64748b' }}>
              <p>Loading subject details...</p>
            </div>
          ) : (
            <SubjectForm
              formData={formData}
              setFormData={setFormData}
              onSubmit={handleSubmit}
              errors={errors}
              loading={loading}
              submitLabel="Update Subject"
            />
          )}
        </div>

        {/* Info Panel */}
        <div className="info-panel">
          <h3>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" strokeWidth="2">
              <circle cx="12" cy="12" r="10"></circle>
              <path d="M12 16v-4"></path>
              <path d="M12 8h.01"></path>
            </svg>
            Subject Setup Guide
          </h3>
          <ul className="info-list">
            <li><strong>Subject Code:</strong> Use a unique, standard identifier (like MAT101) to easily distinguish subjects.</li>
            <li><strong>Class Allocation:</strong> Assign the subject to a specific class or select "All Classes" for general subjects.</li>
            <li><strong>Evaluation Type:</strong> Define whether the subject is evaluated based on Theory, Practical, or Both.</li>
            <li><strong>Active Status:</strong> Inactive subjects will be hidden from student dashboards and new grading forms.</li>
          </ul>
          <div style={{ marginTop: '24px', paddingTop: '20px', borderTop: '1px solid #f1f5f9', textAlign: 'center' }}>
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" strokeWidth="1.5" style={{ margin: '0 auto 8px' }}>
              <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
              <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            </svg>
            <p style={{ fontSize: '12px', color: '#94a3b8', margin: 0, fontWeight: 500 }}>Curriculum Management</p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default SubjectEditPage;
