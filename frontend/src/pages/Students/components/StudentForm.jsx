import React from 'react';
import { Link } from 'react-router-dom';

const StudentForm = ({ 
  formData, 
  setFormData, 
  onSubmit, 
  errors, 
  loading, 
  submitLabel = 'Enroll Student',
  isEdit = false,
  classesList = []
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
      <form onSubmit={onSubmit} id="edit-student-form">
        <div className="pfc-body">
          <div className="premium-row">
            <div className="premium-form-group">
              <label className="premium-label" htmlFor="name">Full Name *</label>
              <input 
                type="text" 
                id="name" 
                name="name" 
                value={formData.name || ''} 
                onChange={handleChange}
                className="premium-input" 
                required 
                disabled={loading}
              />
              {errors?.name && (
                <div className="form-error" style={{ color: '#ef4444', fontSize: '12px', marginTop: '6px' }}>
                  {errors.name[0]}
                </div>
              )}
            </div>
            <div className="premium-form-group">
              <label className="premium-label" htmlFor="email">Email Address</label>
              <input 
                type="email" 
                id="email" 
                name="email" 
                value={formData.email || ''} 
                onChange={handleChange}
                className="premium-input" 
                placeholder="student@example.com"
                disabled={loading}
              />
              {errors?.email && (
                <div className="form-error" style={{ color: '#ef4444', fontSize: '12px', marginTop: '6px' }}>
                  {errors.email[0]}
                </div>
              )}
            </div>
          </div>

          <div className="premium-row">
            <div className="premium-form-group">
              <label className="premium-label" htmlFor="password">Password (Leave blank to keep current)</label>
              <input 
                type="password" 
                id="password" 
                name="password" 
                value={formData.password || ''} 
                onChange={handleChange}
                className="premium-input" 
                placeholder="Change password (optional)"
                disabled={loading}
              />
              {errors?.password && (
                <div className="form-error" style={{ color: '#ef4444', fontSize: '12px', marginTop: '6px' }}>
                  {errors.password[0]}
                </div>
              )}
            </div>
            <div className="premium-form-group">
              <label className="premium-label" htmlFor="roll_no">Roll Number *</label>
              <input 
                type="text" 
                id="roll_no" 
                name="roll_no" 
                value={formData.roll_no || ''} 
                onChange={handleChange}
                className="premium-input" 
                required 
                disabled={loading}
              />
              {errors?.roll_no && (
                <div className="form-error" style={{ color: '#ef4444', fontSize: '12px', marginTop: '6px' }}>
                  {errors.roll_no[0]}
                </div>
              )}
            </div>
          </div>

          <div className="premium-row">
            <div className="premium-form-group">
              <label className="premium-label" htmlFor="class">Class *</label>
              <input 
                type="text" 
                id="class" 
                name="class" 
                value={formData.class || ''} 
                onChange={handleChange}
                className="premium-input" 
                required 
                disabled={loading}
                placeholder="e.g. 10, BSc-1, etc."
                list="classes-datalist"
              />
              <datalist id="classes-datalist">
                {[...new Set(classesList.map(c => c.class))].filter(Boolean).map(c => (
                  <option key={c} value={c} />
                ))}
              </datalist>
              {errors?.class && (
                <div className="form-error" style={{ color: '#ef4444', fontSize: '12px', marginTop: '6px' }}>
                  {errors.class[0]}
                </div>
              )}
            </div>
            <div className="premium-form-group">
              <label className="premium-label" htmlFor="section">Section</label>
              <input 
                type="text" 
                id="section" 
                name="section" 
                value={formData.section || ''} 
                onChange={handleChange}
                className="premium-input" 
                placeholder="A, B, C…"
                disabled={loading}
              />
              {errors?.section && (
                <div className="form-error" style={{ color: '#ef4444', fontSize: '12px', marginTop: '6px' }}>
                  {errors.section[0]}
                </div>
              )}
            </div>
          </div>

          <div className="premium-row">
            <div className="premium-form-group">
              <label className="premium-label" htmlFor="dob">Date of Birth</label>
              <input 
                type="date" 
                id="dob" 
                name="dob" 
                value={formData.dob || ''} 
                onChange={handleChange}
                className="premium-input" 
                disabled={loading}
              />
              {errors?.dob && (
                <div className="form-error" style={{ color: '#ef4444', fontSize: '12px', marginTop: '6px' }}>
                  {errors.dob[0]}
                </div>
              )}
            </div>
            <div className="premium-form-group">
              <label className="premium-label" htmlFor="gender">Gender</label>
              <select 
                id="gender" 
                name="gender" 
                value={formData.gender || ''} 
                onChange={handleChange}
                className="premium-input"
                disabled={loading}
              >
                <option value="">Select gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
              </select>
              {errors?.gender && (
                <div className="form-error" style={{ color: '#ef4444', fontSize: '12px', marginTop: '6px' }}>
                  {errors.gender[0]}
                </div>
              )}
            </div>
          </div>

          <div className="premium-row">
            <div className="premium-form-group">
              <label className="premium-label" htmlFor="phone">Phone Number</label>
              <input 
                type="text" 
                id="phone" 
                name="phone" 
                value={formData.phone || ''} 
                onChange={handleChange}
                className="premium-input" 
                placeholder="+1 (555) 000-0000"
                disabled={loading}
              />
              {errors?.phone && (
                <div className="form-error" style={{ color: '#ef4444', fontSize: '12px', marginTop: '6px' }}>
                  {errors.phone[0]}
                </div>
              )}
            </div>
            <div className="premium-form-group">
              <label className="premium-label" htmlFor="guardian_name">Guardian Name</label>
              <input 
                type="text" 
                id="guardian_name" 
                name="guardian_name" 
                value={formData.guardian_name || ''} 
                onChange={handleChange}
                className="premium-input" 
                placeholder="Parent / Guardian full name"
                disabled={loading}
              />
              {errors?.guardian_name && (
                <div className="form-error" style={{ color: '#ef4444', fontSize: '12px', marginTop: '6px' }}>
                  {errors.guardian_name[0]}
                </div>
              )}
            </div>
          </div>

          <div className="premium-row">
            <div className="premium-form-group" style={{ marginBottom: 0 }}>
              <label className="premium-label" htmlFor="status">Account Status</label>
              <select 
                id="status" 
                name="status" 
                value={formData.is_active ? 'active' : 'inactive'} 
                onChange={(e) => setFormData(prev => ({ ...prev, is_active: e.target.value === 'active' }))}
                className="premium-input"
                disabled={loading}
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </div>
        </div>

        <div className="pfc-footer">
          <Link to="/students" className="btn btn-outline">Cancel</Link>
          <button type="submit" className="btn btn-primary" id="update-student-btn" disabled={loading}>
            {loading ? 'Saving...' : submitLabel}
          </button>
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
        Account Details (For Student Login)
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
            placeholder="e.g. Aditya Sharma" 
            required 
            disabled={loading}
          />
          {errors?.name && <div className="form-error">{errors.name[0]}</div>}
        </div>

        <div>
          <label className="premium-label" htmlFor="email">Email Address</label>
          <input 
            type="email" 
            id="email" 
            name="email" 
            value={formData.email || ''} 
            onChange={handleChange}
            className="premium-input" 
            placeholder="student@example.com" 
            disabled={loading}
          />
          <small style={{ color: '#94a3b8', fontSize: '11px', marginTop: '4px', display: 'block' }}>Required for student login</small>
          {errors?.email && <div className="form-error">{errors.email[0]}</div>}
        </div>

        <div>
          <label className="premium-label" htmlFor="password">Password</label>
          <input 
            type="password" 
            id="password" 
            name="password" 
            value={formData.password || ''} 
            onChange={handleChange}
            className="premium-input" 
            placeholder="Create a strong password" 
            disabled={loading}
          />
          <small style={{ color: '#94a3b8', fontSize: '11px', marginTop: '4px', display: 'block' }}>Leave blank to use default (password123)</small>
          {errors?.password && <div className="form-error">{errors.password[0]}</div>}
        </div>
      </div>

      <div className="section-title" style={{ marginTop: '32px' }}>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" strokeWidth="2">
          <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
          <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
        </svg>
        Academic Information
      </div>

      <div className="form-grid">
        <div>
          <label className="premium-label" htmlFor="roll_no">Roll Number *</label>
          <input 
            type="text" 
            id="roll_no" 
            name="roll_no" 
            value={formData.roll_no || ''} 
            onChange={handleChange}
            className="premium-input" 
            placeholder="e.g. 2024-CS-001" 
            required 
            disabled={loading}
          />
          {errors?.roll_no && <div className="form-error">{errors.roll_no[0]}</div>}
        </div>

        <div>
          <label className="premium-label" htmlFor="class">Class *</label>
          <input 
            type="text" 
            id="class" 
            name="class" 
            value={formData.class || ''} 
            onChange={handleChange}
            className="premium-input" 
            placeholder="e.g. 10, BSc-1, etc." 
            required 
            disabled={loading}
            list="classes-datalist"
          />
          <datalist id="classes-datalist">
            {[...new Set(classesList.map(c => c.class))].filter(Boolean).map(c => (
              <option key={c} value={c} />
            ))}
          </datalist>
          {errors?.class && <div className="form-error">{errors.class[0]}</div>}
        </div>

        <div>
          <label className="premium-label" htmlFor="section">Section</label>
          <input 
            type="text" 
            id="section" 
            name="section" 
            value={formData.section || ''} 
            onChange={handleChange}
            className="premium-input" 
            placeholder="A, B, C…" 
            disabled={loading}
          />
          {errors?.section && <div className="form-error">{errors.section[0]}</div>}
        </div>

        <div>
          <label className="premium-label" htmlFor="gender">Gender</label>
          <select 
            id="gender" 
            name="gender" 
            value={formData.gender || ''} 
            onChange={handleChange}
            className="premium-input"
            disabled={loading}
          >
            <option value="">Select gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
          {errors?.gender && <div className="form-error">{errors.gender[0]}</div>}
        </div>
      </div>

      <div className="section-title" style={{ marginTop: '32px' }}>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" strokeWidth="2">
          <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
        </svg>
        Personal Details
      </div>

      <div className="form-grid">
        <div>
          <label className="premium-label" htmlFor="dob">Date of Birth</label>
          <input 
            type="date" 
            id="dob" 
            name="dob" 
            value={formData.dob || ''} 
            onChange={handleChange}
            className="premium-input" 
            disabled={loading}
          />
          {errors?.dob && <div className="form-error">{errors.dob[0]}</div>}
        </div>

        <div>
          <label className="premium-label" htmlFor="phone">Phone Number</label>
          <input 
            type="text" 
            id="phone" 
            name="phone" 
            value={formData.phone || ''} 
            onChange={handleChange}
            className="premium-input" 
            placeholder="+91 98000 00000" 
            disabled={loading}
          />
          {errors?.phone && <div className="form-error">{errors.phone[0]}</div>}
        </div>

        <div style={{ gridColumn: '1 / -1' }}>
          <label className="premium-label" htmlFor="guardian_name">Guardian Name</label>
          <input 
            type="text" 
            id="guardian_name" 
            name="guardian_name" 
            value={formData.guardian_name || ''} 
            onChange={handleChange}
            className="premium-input" 
            placeholder="Parent / Guardian full name" 
            disabled={loading}
          />
          {errors?.guardian_name && <div className="form-error">{errors.guardian_name[0]}</div>}
        </div>

        <div style={{ display: 'flex', alignItems: 'center', paddingTop: '24px' }}>
          <label className="checkbox-wrapper">
            <input 
              type="checkbox" 
              name="is_active" 
              checked={formData.is_active !== false} 
              onChange={handleChange}
              disabled={loading}
            />
            <span style={{ fontWeight: 600, color: '#1e293b', fontSize: '14px', marginLeft: '8px' }}>Student is Active</span>
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
        <Link to="/students" className="btn-back-premium" style={{ boxShadow: 'none', background: 'transparent', borderColor: 'transparent', color: '#64748b' }}>Cancel</Link>
        <button 
          type="submit" 
          className="btn-enroll-premium"
          disabled={loading}
        >
          {loading ? 'Enrolling...' : submitLabel}
        </button>
      </div>
    </form>
  );
};

export default StudentForm;
