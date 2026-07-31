/**
 * ============================================================================
 * pages/Marks/MarksEntryPage.jsx — Record Marks Page
 * ============================================================================
 * 99% visual match port of backend/resources/views/marks/create.blade.php.
 * Handles fetching students and subjects, pre-selecting student ID from query string,
 * submitting mark entries, and displaying validation errors.
 * ============================================================================
 */

import { useState, useEffect } from 'react';
import { useNavigate, useSearchParams } from 'react-router-dom';
import markService from '@/services/markService';
import MarksForm from './components/MarksForm';
import Loader from '@/components/ui/Loader';

export default function MarksEntryPage() {
  const navigate = useNavigate();
  const [searchParams] = useSearchParams();
  const preselectedStudentId = searchParams.get('student_id') || '';

  const [options, setOptions] = useState({ students: [], subjects: [] });
  const [loading, setLoading] = useState(true);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [errors, setErrors] = useState({});

  useEffect(() => {
    const fetchOptions = async () => {
      try {
        const res = await markService.getCreateOptions();
        const data = res.data;
        setOptions({
          students: data.students || [],
          subjects: data.subjects || [],
        });
      } catch (err) {
        console.error('Failed to load mark entry options:', err);
      } finally {
        setLoading(false);
      }
    };
    fetchOptions();
  }, []);

  const handleSubmit = async (formData) => {
    setIsSubmitting(true);
    setErrors({});
    try {
      await markService.create(formData);
      navigate('/marks', { state: { successMessage: 'Marks recorded successfully!' } });
    } catch (err) {
      console.error('Failed to create mark:', err);
      if (err.response && err.response.status === 422 && err.response.data?.errors) {
        setErrors(err.response.data.errors);
      } else if (err.response && err.response.data?.message) {
        alert(err.response.data.message);
      } else {
        alert('An unexpected error occurred while saving marks.');
      }
    } finally {
      setIsSubmitting(false);
    }
  };

  if (loading) {
    return <Loader visible />;
  }

  return (
    <div className="max-w-7xl mx-auto px-4 py-8">
      <style>{`
        .form-page-header {
          text-align: center;
          margin-bottom: 32px;
        }
        .form-page-header h2 {
          font-family: 'Poppins', sans-serif;
          font-size: 28px;
          font-weight: 700;
          color: #111827;
          letter-spacing: -0.02em;
          margin: 0;
        }
        .form-page-header p {
          font-size: 15px;
          color: #64748b;
          margin-top: 8px;
          margin-bottom: 0;
        }
      `}</style>

      <div className="form-page-header">
        <h2>Record Marks</h2>
        <p>Enter examination marks for a student</p>
      </div>

      <MarksForm
        initialData={{ student_id: preselectedStudentId, max_marks: 100, academic_year: '2024-25', exam_type: 'unit_test' }}
        students={options.students}
        subjects={options.subjects}
        onSubmit={handleSubmit}
        isSubmitting={isSubmitting}
        errors={errors}
        isEditMode={false}
      />
    </div>
  );
}
