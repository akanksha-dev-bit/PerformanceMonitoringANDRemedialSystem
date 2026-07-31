import React, { useState, useEffect } from 'react';
import { useNavigate, useParams, Link } from 'react-router-dom';
import studentService from '@/services/studentService';
import StudentForm from './components/StudentForm';

const StudentEditPage = () => {
  const navigate = useNavigate();
  const { id } = useParams();
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
  const [fetching, setFetching] = useState(true);

  useEffect(() => {
    const loadDetails = async () => {
      try {
        // Fetch class lookup
        const classesRes = await studentService.lookupClasses();
        if (Array.isArray(classesRes.data)) {
          setClassesList(classesRes.data);
        }

        // Fetch student details
        const studentRes = await studentService.getStudent(id);
        const s = studentRes.data;
        
        setFormData({
          name: s.name || '',
          email: s.email || '',
          password: '', // blank unless updating
          roll_no: s.roll_no || '',
          class: s.class || '',
          section: s.section || '',
          dob: s.dob || '',
          gender: s.gender || '',
          phone: s.phone || '',
          guardian_name: s.guardian_name || '',
          is_active: !!s.is_active,
        });
      } catch (err) {
        console.error('Error fetching student details:', err);
        alert('Could not load student details.');
        navigate('/students');
      } finally {
        setFetching(false);
      }
    };
    loadDetails();
  }, [id, navigate]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setErrors({});

    try {
      await studentService.updateStudent(id, formData);
      navigate('/students', { state: { successMessage: 'Student updated successfully.' } });
    } catch (err) {
      if (err.response?.status === 422) {
        setErrors(err.response.data.errors || {});
      } else {
        console.error('Error updating student:', err);
        alert(err.response?.data?.message || 'Something went wrong while saving updates.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="w-full max-w-[1400px] mx-auto px-4 md:px-8 py-8">
      <style>{`
        .form-page-header {
          text-align: center;
          margin-bottom: 32px;
        }
        .form-page-header h2 { font-family: 'Poppins', sans-serif; font-size: 28px; font-weight: 700; color: #111827; letter-spacing: -0.02em; margin: 0; }
        .form-page-header p { font-size: 15px; color: #64748b; margin-top: 8px; }

        .premium-form-card {
          background: #fff;
          border: 1px solid #e2e8f0;
          border-radius: 20px;
          box-shadow: 0 10px 40px rgba(0,0,0,0.04);
          max-width: 840px;
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

        .btn {
          display: inline-flex;
          align-items: center;
          justify-content: center;
          padding: 10px 20px;
          border-radius: 10px;
          font-size: 14px;
          font-weight: 600;
          text-decoration: none;
          transition: all 0.2s ease;
          cursor: pointer;
          border: none;
        }
        .btn-primary {
          background: linear-gradient(135deg, #6C5CE7, #5A4BD6);
          color: #fff;
          box-shadow: 0 4px 12px rgba(108, 92, 231, 0.3);
        }
        .btn-primary:hover {
          transform: translateY(-1px);
          box-shadow: 0 6px 16px rgba(108, 92, 231, 0.4);
          color: #fff;
        }
        .btn-outline {
          background: #fff;
          border: 1px solid #d1d5db;
          color: #374151;
        }
        .btn-outline:hover {
          background: #f9fafb;
          border-color: #9ca3af;
          color: #111827;
        }

        @media (max-width: 640px) {
          .premium-row { grid-template-columns: 1fr; gap: 0; }
        }
      `}</style>

      <div className="form-page-header">
        <h2>Edit Profile</h2>
        <p>Update information and academic details for {fetching ? '...' : formData.name}</p>
      </div>

      <div className="premium-form-card">
        <div className="pfc-header">
          <div style={{ fontWeight: 600, color: '#111827', fontSize: '16px' }}>Student Information</div>
          <Link to={`/students/${id}`} className="btn btn-outline" style={{ padding: '6px 14px', fontSize: '13px' }} id="back-btn">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style={{ marginRight: '4px' }}>
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Profile
          </Link>
        </div>

        {fetching ? (
          <div style={{ textAlign: 'center', padding: '40px 0', color: '#64748b' }}>
            <p>Loading student details...</p>
          </div>
        ) : (
          <StudentForm
            formData={formData}
            setFormData={setFormData}
            onSubmit={handleSubmit}
            errors={errors}
            loading={loading}
            submitLabel="Save Changes"
            classesList={classesList}
            isEdit={true}
          />
        )}
      </div>
    </div>
  );
};

export default StudentEditPage;
