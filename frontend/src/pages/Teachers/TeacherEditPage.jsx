import React, { useState, useEffect } from 'react';
import { useNavigate, useParams, Link } from 'react-router-dom';
import { teacherService, subjectService } from '@/services';
import TeacherForm from './components/TeacherForm';

const TeacherEditPage = () => {
  const navigate = useNavigate();
  const { id } = useParams();
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
  const [fetching, setFetching] = useState(true);

  useEffect(() => {
    const loadDetails = async () => {
      try {
        // Fetch subjects
        const subjectsRes = await subjectService.getSubjects({ all: true });
        if (Array.isArray(subjectsRes.data)) {
          setSubjects(subjectsRes.data);
        } else if (subjectsRes.data && Array.isArray(subjectsRes.data.data)) {
          setSubjects(subjectsRes.data.data);
        }

        // Fetch teacher details
        const teacherRes = await teacherService.getTeacher(id);
        const teacherData = teacherRes.data;
        
        setFormData({
          name: teacherData.user?.name || '',
          email: teacherData.user?.email || '',
          password: '', // Kept empty unless changing
          subject_id: teacherData.subject_id || '',
          is_active: !!teacherData.user?.is_active,
        });
      } catch (err) {
        console.error('Error fetching teacher details:', err);
        alert('Could not load teacher details.');
        navigate('/teachers');
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
      await teacherService.updateTeacher(id, formData);
      navigate('/teachers', { state: { successMessage: 'Teacher updated successfully.' } });
    } catch (err) {
      if (err.response?.status === 422) {
        setErrors(err.response.data.errors || {});
      } else {
        console.error('Error updating teacher:', err);
        alert(err.response?.data?.message || 'Something went wrong while saving updates.');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      <div className="page-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
        <div>
          <h2 className="page-title">Edit Teacher</h2>
          <p className="page-subtitle">Update details for {fetching ? '...' : formData.name}</p>
        </div>
        <Link to="/teachers" className="btn btn-outline">
          Back to Teachers
        </Link>
      </div>

      <div className="card" style={{ maxWidth: '640px', marginTop: '24px' }}>
        {fetching ? (
          <div style={{ textAlign: 'center', padding: '40px 0', color: '#64748b' }}>
            <p>Loading teacher details...</p>
          </div>
        ) : (
          <TeacherForm
            formData={formData}
            setFormData={setFormData}
            onSubmit={handleSubmit}
            errors={errors}
            loading={loading}
            submitLabel="Update Teacher"
            subjects={subjects}
            isEdit={true}
          />
        )}
      </div>
    </>
  );
};

export default TeacherEditPage;
