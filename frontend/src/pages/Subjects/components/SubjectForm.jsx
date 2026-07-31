import React from 'react';
import { Link } from 'react-router-dom';

const SubjectForm = ({ 
  formData, 
  setFormData, 
  onSubmit, 
  errors, 
  loading, 
  submitLabel = 'Save Subject' 
}) => {
  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value
    }));
  };

  return (
    <form onSubmit={onSubmit}>
      <div className="section-title">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" strokeWidth="2">
          <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
          <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
        </svg>
        Subject Details
      </div>
      
      <div className="form-grid">
        <div>
          <label className="premium-label" htmlFor="name">Subject Name *</label>
          <input 
            type="text" 
            id="name" 
            name="name" 
            value={formData.name || ''} 
            onChange={handleChange}
            className="premium-input" 
            placeholder="e.g. Mathematics" 
            required 
            disabled={loading}
          />
          {errors?.name && <div className="form-error">{errors.name[0]}</div>}
        </div>

        <div>
          <label className="premium-label" htmlFor="code">Subject Code *</label>
          <input 
            type="text" 
            id="code" 
            name="code" 
            value={formData.code || ''} 
            onChange={handleChange}
            className="premium-input" 
            placeholder="e.g. MAT101" 
            required 
            disabled={loading}
          />
          {errors?.code && <div className="form-error">{errors.code[0]}</div>}
        </div>

        <div>
          <label className="premium-label" htmlFor="class">Class *</label>
          <select 
            id="class" 
            name="class" 
            value={formData.class || ''} 
            onChange={handleChange}
            className="premium-input" 
            required
            disabled={loading}
          >
            <option value="">Select Class</option>
            {[...Array(12)].map((_, i) => (
              <option key={i + 1} value={String(i + 1)}>Class {i + 1}</option>
            ))}
            <option value="All">All Classes</option>
          </select>
          {errors?.class && <div className="form-error">{errors.class[0]}</div>}
        </div>

        <div>
          <label className="premium-label" htmlFor="type">Type</label>
          <select 
            id="type" 
            name="type" 
            value={formData.type || ''} 
            onChange={handleChange}
            className="premium-input"
            disabled={loading}
          >
            <option value="">Select Type</option>
            <option value="theory">Theory</option>
            <option value="practical">Practical</option>
            <option value="both">Both</option>
          </select>
          {errors?.type && <div className="form-error">{errors.type[0]}</div>}
        </div>
      </div>

      <div className="section-title" style={{ marginTop: '32px' }}>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" strokeWidth="2">
          <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
        </svg>
        Evaluation Settings
      </div>

      <div className="form-grid">
        <div>
          <label className="premium-label" htmlFor="max_marks">Max Marks</label>
          <input 
            type="number" 
            id="max_marks" 
            name="max_marks" 
            value={formData.max_marks || ''} 
            onChange={handleChange}
            className="premium-input" 
            placeholder="100" 
            disabled={loading}
          />
          {errors?.max_marks && <div className="form-error">{errors.max_marks[0]}</div>}
        </div>

        <div style={{ display: 'flex', alignItems: 'center', paddingTop: '24px' }}>
          <label className="checkbox-wrapper">
            <input 
              type="checkbox" 
              name="is_active" 
              checked={!!formData.is_active} 
              onChange={handleChange}
              disabled={loading}
            />
            <span style={{ fontWeight: 600, color: '#1e293b', fontSize: '14px' }}>Subject is Active</span>
          </label>
        </div>
      </div>

      <div style={{ 
        display: 'flex', 
        gap: '12px', 
        marginTop: '32px', 
        justifyContent: 'flex-end', 
        borderTop: '1px solid #f1f5f9', 
        paddingTop: '24px' 
      }}>
        <Link to="/subjects" className="btn-back-premium" style={{ boxShadow: 'none' }}>Cancel</Link>
        <button 
          type="submit" 
          className="btn-enroll-premium"
          disabled={loading}
        >
          {loading ? 'Saving...' : submitLabel}
        </button>
      </div>
    </form>
  );
};

export default SubjectForm;
