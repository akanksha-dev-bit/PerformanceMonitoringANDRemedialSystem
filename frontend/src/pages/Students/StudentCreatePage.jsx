import React, { useState, useEffect } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import studentService from '@/services/studentService';
import StudentForm from './components/StudentForm';

const StudentCreatePage = () => {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    roll_no: '',
    class: '',
    section: '',
    dob: '',
    gender: '',
    phone: '',
    guardian_name: '',
    is_active: true,
  });
  const [classesList, setClassesList] = useState([]);
  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    const fetchClasses = async () => {
      try {
        const response = await studentService.lookupClasses();
        if (Array.isArray(response.data)) {
          setClassesList(response.data);
        }
      } catch (err) {
        console.error('Error fetching class lookups:', err);
      }
    };
    fetchClasses();
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setErrors({});

    try {
      await studentService.createStudent(formData);
      navigate('/students', { state: { successMessage: 'Student enrolled successfully.' } });
    } catch (err) {
      if (err.response?.status === 422) {
        setErrors(err.response.data.errors || {});
      } else {
        console.error('Error creating student:', err);
        alert(err.response?.data?.message || 'Something went wrong while saving.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="w-full max-w-[1400px] mx-auto px-4 md:px-8 py-8">
      <style>{`
        .page-layout {
          display: flex;
          gap: 32px;
          margin-top: 24px;
          align-items: flex-start;
          width: 100%;
        }
        .student-create-card {
          background: #fff;
          border-radius: 20px;
          padding: 32px 40px;
          box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
          border: 1px solid rgba(0,0,0,0.04);
          flex: 1; 
          min-width: 0; 
        }

        .info-panel {
          width: 320px;
          background: #fff;
          border-radius: 20px;
          padding: 24px;
          box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
          border: 1px solid rgba(0,0,0,0.04);
          position: sticky;
          top: 24px;
          flex-shrink: 0;
        }

        .info-panel h3 {
          font-size: 16px;
          font-weight: 700;
          color: #1e293b;
          margin-bottom: 16px;
          display: flex;
          align-items: center;
          gap: 8px;
        }

        .info-list {
          list-style: none;
          padding: 0;
          margin: 0;
        }

        .info-list li {
          position: relative;
          padding-left: 20px;
          font-size: 13px;
          color: #475569;
          margin-bottom: 16px;
          line-height: 1.6;
        }

        .info-list li::before {
          content: "•";
          color: #6C5CE7;
          font-size: 16px;
          font-weight: bold;
          position: absolute;
          left: 0;
          top: -2px;
        }

        .section-title {
          font-size: 16px;
          font-weight: 700;
          color: #1e293b;
          margin-bottom: 20px;
          padding-bottom: 10px;
          border-bottom: 1px solid #f1f5f9;
          display: flex;
          align-items: center;
          gap: 8px;
        }
        
        .premium-input {
          background: #f8fafc;
          border: 1px solid #e2e8f0;
          border-radius: 12px;
          padding: 12px 16px;
          font-size: 14px;
          transition: all 0.2s;
          width: 100%;
          color: #1e293b;
        }
        
        .premium-input:focus {
          background: #fff;
          border-color: #6C5CE7;
          box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1);
          outline: none;
        }
        
        .premium-label {
          font-size: 13px;
          font-weight: 600;
          color: #475569;
          margin-bottom: 8px;
          display: block;
        }
        
        .btn-back-premium {
          display: inline-flex;
          align-items: center;
          gap: 8px;
          padding: 8px 16px;
          background: rgba(108, 92, 231, 0.08);
          border: 1px solid rgba(108, 92, 231, 0.2);
          border-radius: 100px;
          color: #6C5CE7;
          font-size: 13px;
          font-weight: 700;
          text-decoration: none;
          transition: all 0.2s;
          box-shadow: 0 2px 4px rgba(108, 92, 231, 0.05);
        }
        
        .btn-back-premium:hover {
          background: rgba(108, 92, 231, 0.15);
          color: #5A4BD6;
          transform: translateY(-1px);
          box-shadow: 0 4px 6px rgba(108, 92, 231, 0.1);
        }
        
        .btn-enroll-premium {
          background: linear-gradient(135deg, #6C5CE7, #5A4BD6);
          color: #fff;
          border: none;
          padding: 12px 24px;
          border-radius: 12px;
          font-weight: 600;
          font-size: 14px;
          cursor: pointer;
          transition: all 0.2s;
          box-shadow: 0 4px 12px rgba(108, 92, 231, 0.3);
        }
        
        .btn-enroll-premium:hover {
          transform: translateY(-2px);
          box-shadow: 0 6px 16px rgba(108, 92, 231, 0.4);
        }
        
        .form-grid {
          display: grid;
          grid-template-columns: 1fr 1fr;
          gap: 20px;
          margin-bottom: 24px;
        }
        
        .form-error {
          color: #ef4444;
          font-size: 12px;
          margin-top: 6px;
        }
        
        @media (max-width: 1024px) {
          .info-panel { display: none; }
        }
        
        @media (max-width: 768px) {
          .form-grid { grid-template-columns: 1fr; }
          .student-create-card { padding: 24px; }
          .page-layout { gap: 0; }
        }
      `}</style>

      <div className="page-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
        <div>
          <h2 className="page-title" style={{ fontSize: '28px', fontWeight: 800, color: '#0f172a', margin: '0 0 4px 0', letterSpacing: '-0.02em' }}>Add Student</h2>
          <p className="page-subtitle" style={{ fontSize: '15px', color: '#64748b', margin: 0 }}>Enroll a new student and set up their account</p>
        </div>
        <Link to="/students" className="btn-back-premium">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
          </svg>
          Back to Students
        </Link>
      </div>

      <div className="page-layout">
        {/* Main Form Card */}
        <div className="student-create-card">
          <StudentForm
            formData={formData}
            setFormData={setFormData}
            onSubmit={handleSubmit}
            errors={errors}
            loading={loading}
            submitLabel="Enroll Student"
            classesList={classesList}
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
            Student Setup Guide
          </h3>
          <ul className="info-list">
            <li><strong>Email Login:</strong> The email provided will be used as the student's username to access the dashboard.</li>
            <li><strong>Default Password:</strong> If left blank, the default password <code>password123</code> is assigned automatically.</li>
            <li><strong>Class & Section:</strong> Ensure these match your existing system exactly (e.g., "10" and "A").</li>
            <li><strong>Roll Numbers:</strong> Must be unique within the school to prevent data conflicts.</li>
          </ul>
          <div style={{ marginTop: '24px', paddingTop: '20px', borderTop: '1px solid #f1f5f9', textAlign: 'center' }}>
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" strokeWidth="1.5" style={{ margin: '0 auto 8px' }}>
              <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
              <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
            </svg>
            <p style={{ fontSize: '12px', color: '#94a3b8', margin: 0, fontWeight: 500 }}>Secure Student Enrollment</p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default StudentCreatePage;
