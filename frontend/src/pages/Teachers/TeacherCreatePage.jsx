import React, { useState, useEffect } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { teacherService, subjectService } from '@/services';
import TeacherForm from './components/TeacherForm';

const TeacherCreatePage = () => {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    subject_id: '',
    is_active: true,
  });
  const [subjects, setSubjects] = useState([]);
  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    const fetchSubjects = async () => {
      try {
        const response = await subjectService.getSubjects({ all: true });
        if (Array.isArray(response.data)) {
          setSubjects(response.data);
        } else if (response.data && Array.isArray(response.data.data)) {
          setSubjects(response.data.data);
        }
      } catch (err) {
        console.error('Error fetching subjects:', err);
      }
    };
    fetchSubjects();
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setErrors({});

    try {
      await teacherService.createTeacher(formData);
      navigate('/teachers', { state: { successMessage: 'Teacher created successfully.' } });
    } catch (err) {
      if (err.response?.status === 422) {
        setErrors(err.response.data.errors || {});
      } else {
        console.error('Error creating teacher:', err);
        alert(err.response?.data?.message || 'Something went wrong while saving.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      <div className="page-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
        <div>
          <h2 className="page-title">Add Teacher</h2>
          <p className="page-subtitle">Register a new teacher in your school</p>
        </div>
        <Link to="/teachers" className="btn-back-premium">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
          </svg>
          Back to Teachers
        </Link>
      </div>

      <div className="page-layout">
        {/* Main Form Card */}
        <div className="form-create-card">
          <TeacherForm
            formData={formData}
            setFormData={setFormData}
            onSubmit={handleSubmit}
            errors={errors}
            loading={loading}
            submitLabel="Save Teacher"
            subjects={subjects}
            isEdit={false}
          />
        </div>

        {/* Info Panel */}
        <div className="info-panel">
          <h3>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" strokeWidth="2">
              <circle cx="12" cy="12" r="10"></circle>
              <path d="M12 16v-4"></path>
              <path d="M12 8h.01"></path>
            </svg>
            Teacher Setup Guide
          </h3>
          <ul className="info-list">
            <li><strong>Teacher Login:</strong> Provide a secure password. The teacher will use this email and password to log in.</li>
            <li><strong>Permissions:</strong> Newly added teachers will automatically have teacher-level access to the system.</li>
            <li><strong>Subject Assignment:</strong> Assigning a primary subject here links the teacher to that subject. They can evaluate and manage marks for it.</li>
          </ul>
          <div style={{ marginTop: '24px', paddingTop: '20px', borderTop: '1px solid #f1f5f9', textAlign: 'center' }}>
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" strokeWidth="1.5" style={{ margin: '0 auto 8px' }}>
              <path d="M16 21v-2a4 4 0 0 0-4-4H5c-1.1 0-2 .9-2 2v2"></path>
              <circle cx="8.5" cy="7" r="4"></circle>
              <line x1="20" y1="8" x2="20" y2="14"></line>
              <line x1="23" y1="11" x2="17" y2="11"></line>
            </svg>
            <p style={{ fontSize: '12px', color: '#94a3b8', margin: 0, fontWeight: 500 }}>Faculty Management</p>
          </div>
        </div>
      </div>
    </>
  );
};

export default TeacherCreatePage;
