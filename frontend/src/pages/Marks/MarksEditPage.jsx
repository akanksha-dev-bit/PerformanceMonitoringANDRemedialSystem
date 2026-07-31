/**
 * ============================================================================
 * pages/Marks/MarksEditPage.jsx — Edit Marks Page
 * ============================================================================
 * Handles fetching existing mark details by ID, populating MarksForm, and
 * submitting updates to backend MarkController.
 * ============================================================================
 */

import { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import markService from '@/services/markService';
import MarksForm from './components/MarksForm';
import Loader from '@/components/ui/Loader';

export default function MarksEditPage() {
  const { id } = useParams();
  const navigate = useNavigate();

  const [mark, setMark] = useState(null);
  const [options, setOptions] = useState({ students: [], subjects: [] });
  const [loading, setLoading] = useState(true);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [errors, setErrors] = useState({});

  useEffect(() => {
    const fetchMarkData = async () => {
      try {
        const res = await markService.getById(id);
        const data = res.data;
        if (data.mark) {
          setMark(data.mark);
          setOptions({
            students: data.students || [],
            subjects: data.subjects || [],
          });
        } else {
          setMark(data);
        }
      } catch (err) {
        console.error('Failed to load mark record:', err);
        alert('Could not load mark record.');
        navigate('/marks');
      } finally {
        setLoading(false);
      }
    };
    fetchMarkData();
  }, [id, navigate]);

  const handleSubmit = async (formData) => {
    setIsSubmitting(true);
    setErrors({});
    try {
      await markService.update(id, formData);
      navigate('/marks', { state: { successMessage: 'Mark entry updated successfully.' } });
    } catch (err) {
      console.error('Failed to update mark:', err);
      if (err.response && err.response.status === 422 && err.response.data?.errors) {
        setErrors(err.response.data.errors);
      } else if (err.response && err.response.data?.message) {
        alert(err.response.data.message);
      } else {
        alert('An unexpected error occurred while updating marks.');
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
        <h2>Edit Marks</h2>
        <p>Update examination marks for a student</p>
      </div>

      <MarksForm
        initialData={{
          student_id: mark?.student_id || '',
          subject_id: mark?.subject_id || '',
          marks_obtained: mark?.marks_obtained ?? '',
          max_marks: mark?.max_marks ?? 100,
          exam_type: mark?.exam_type || 'unit_test',
          academic_year: mark?.academic_year || '2024-25',
          remarks: mark?.remarks || '',
        }}
        students={options.students}
        subjects={options.subjects}
        onSubmit={handleSubmit}
        isSubmitting={isSubmitting}
        errors={errors}
        isEditMode={true}
      />
    </div>
  );
}
