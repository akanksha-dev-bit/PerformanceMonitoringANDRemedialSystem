import React from 'react';
import { Link } from 'react-router-dom';

const TeacherForm = ({ 
  formData, 
  setFormData, 
  onSubmit, 
  errors, 
  loading, 
  submitLabel = 'Save Teacher',
  isEdit = false,
  subjects = []
}) => {
  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value
    }));
  };

  if (isEdit) {
    return (
      <form onSubmit={onSubmit}>
        <div className="form-group">
          <label className="form-label" htmlFor="name">Name *</label>
          <input 
            type="text" 
            id="name" 
            name="name" 
            value={formData.name || ''} 
            onChange={handleChange}
            className="form-control" 
            required 
            disabled={loading}
          />
          {errors?.name && (
            <div className="form-error" style={{ color: '#ef4444', fontSize: '14px', marginTop: '4px' }}>
              {errors.name[0]}
            </div>
          )}
        </div>

        <div className="form-group" style={{ marginTop: '15px' }}>
          <label className="form-label" htmlFor="email">Email Address *</label>
          <input 
            type="email" 
            id="email" 
            name="email" 
            value={formData.email || ''} 
            onChange={handleChange}
            className="form-control" 
            required 
            disabled={loading}
          />
          {errors?.email && (
            <div className="form-error" style={{ color: '#ef4444', fontSize: '14px', marginTop: '4px' }}>
              {errors.email[0]}
            </div>
          )}
        </div>

        <div className="form-group" style={{ marginTop: '15px' }}>
          <label className="form-label" htmlFor="password">Password (Leave blank to keep current)</label>
          <input 
            type="password" 
            id="password" 
            name="password" 
            value={formData.password || ''} 
            onChange={handleChange}
            className="form-control" 
            disabled={loading}
          />
          {errors?.password && (
            <div className="form-error" style={{ color: '#ef4444', fontSize: '14px', marginTop: '4px' }}>
              {errors.password[0]}
            </div>
          )}
        </div>

        <div className="form-group" style={{ marginTop: '15px' }}>
          <label className="form-label" htmlFor="subject_id">Primary Subject (Optional)</label>
          <select 
            id="subject_id" 
            name="subject_id" 
            value={formData.subject_id || ''} 
            onChange={handleChange}
            className="form-control form-select"
            disabled={loading}
          >
            <option value="">No Subject</option>
            {subjects.map((subject) => (
              <option key={subject.id} value={subject.id}>
                {subject.name} (Class {subject.class})
              </option>
            ))}
          </select>
          {errors?.subject_id && (
            <div className="form-error" style={{ color: '#ef4444', fontSize: '14px', marginTop: '4px' }}>
              {errors.subject_id[0]}
            </div>
          )}
        </div>

        <div className="form-group" style={{ marginTop: '15px' }}>
          <label className="form-label" style={{ display: 'flex', alignItems: 'center', cursor: 'pointer' }}>
            <input 
              type="checkbox" 
              name="is_active" 
              checked={!!formData.is_active} 
              onChange={handleChange}
              disabled={loading}
              style={{ marginRight: '8px' }}
            />
            Active
          </label>
        </div>

        <div style={{ display: 'flex', gap: '12px', marginTop: '20px' }}>
          <button type="submit" className="btn btn-primary" disabled={loading}>
            {loading ? 'Saving...' : submitLabel}
          </button>
          <Link to="/teachers" className="btn btn-outline">Cancel</Link>
        </div>
      </form>
    );
  }

  return (
    <form onSubmit={onSubmit}>
      <div className="section-title">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" strokeWidth="2">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
          <circle cx="12" cy="7" r="4"></circle>
        </svg>
        Account Details
      </div>
      
      <div className="form-grid">
        <div>
          <label className="premium-label" htmlFor="name">Full Name *</label>
          <input 
            type="text" 
            id="name" 
            name="name" 
            value={formData.name || ''} 
            onChange={handleChange}
            className="premium-input" 
            placeholder="e.g. John Doe" 
            required 
            disabled={loading}
          />
          {errors?.name && <div className="form-error">{errors.name[0]}</div>}
        </div>

        <div>
          <label className="premium-label" htmlFor="email">Email Address *</label>
          <input 
            type="email" 
            id="email" 
            name="email" 
            value={formData.email || ''} 
            onChange={handleChange}
            className="premium-input" 
            placeholder="teacher@example.com" 
            required 
            disabled={loading}
          />
          {errors?.email && <div className="form-error">{errors.email[0]}</div>}
        </div>

        <div>
          <label className="premium-label" htmlFor="password">Password *</label>
          <input 
            type="password" 
            id="password" 
            name="password" 
            value={formData.password || ''} 
            onChange={handleChange}
            className="premium-input" 
            placeholder="Create a strong password" 
            required 
            disabled={loading}
          />
          {errors?.password && <div className="form-error">{errors.password[0]}</div>}
        </div>
      </div>

      <div className="section-title" style={{ marginTop: '32px' }}>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" strokeWidth="2">
          <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
          <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
        </svg>
        Academic Assignment
      </div>

      <div className="form-grid">
        <div style={{ gridColumn: '1 / -1' }}>
          <label className="premium-label" htmlFor="subject_id">Primary Subject (Optional)</label>
          <select 
            id="subject_id" 
            name="subject_id" 
            value={formData.subject_id || ''} 
            onChange={handleChange}
            className="premium-input"
            disabled={loading}
          >
            <option value="">No Subject assigned yet</option>
            {subjects.map((subject) => (
              <option key={subject.id} value={subject.id}>
                {subject.name} (Class {subject.class})
              </option>
            ))}
          </select>
          <small style={{ color: '#94a3b8', fontSize: '11px', marginTop: '4px', display: 'block' }}>
            You can assign more subjects later from the teacher's profile.
          </small>
          {errors?.subject_id && <div className="form-error">{errors.subject_id[0]}</div>}
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
            <span style={{ fontWeight: 600, color: '#1e293b', fontSize: '14px' }}>Teacher is Active</span>
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
        <Link to="/teachers" className="btn-back-premium" style={{ boxShadow: 'none', background: 'transparent', borderColor: 'transparent', color: '#64748b' }}>Cancel</Link>
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

export default TeacherForm;
