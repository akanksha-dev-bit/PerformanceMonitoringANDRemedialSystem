/**
 * ============================================================================
 * pages/dashboard/components/AddTeacherModal.jsx
 * ============================================================================
 * Modal form for creating a new teacher using teacherService.js.
 * ============================================================================
 */

import React, { useState } from 'react';
import teacherService from '@/services/teacherService';

export default function AddTeacherModal({ isOpen, onClose, onSuccess }) {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    department: '',
    phone: '',
    password: '',
    password_confirmation: '',
  });

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  if (!isOpen) return null;

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    try {
      await teacherService.createTeacher(formData);
      setFormData({
        name: '',
        email: '',
        department: '',
        phone: '',
        password: '',
        password_confirmation: '',
      });
      if (onSuccess) onSuccess();
      onClose();
    } catch (err) {
      console.error('Failed to create teacher:', err);
      const msg = err.response?.data?.message || err.response?.data?.error || 'Failed to create teacher account.';
      setError(msg);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className={`modal-overlay ${isOpen ? 'active' : ''}`} onClick={onClose}>
      <div className="modal-content" onClick={(e) => e.stopPropagation()}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
          <div>
            <h3 style={{ fontSize: '18px', fontWeight: 800, color: '#0f172a', margin: 0 }}>Add New Teacher</h3>
            <p style={{ fontSize: '13px', color: '#64748b', margin: '2px 0 0' }}>Register a new faculty member to your academy.</p>
          </div>
          <button type="button" onClick={onClose} style={{ background: 'none', border: 'none', cursor: 'pointer', color: '#94a3b8' }}>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <line x1="18" y1="6" x2="6" y2="18" />
              <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
          </button>
        </div>

        {error && (
          <div style={{ background: '#fef2f2', border: '1px solid #fecaca', color: '#b91c1c', padding: '10px 14px', borderRadius: '10px', fontSize: '13px', marginBottom: '16px' }}>
            {error}
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label style={{ fontSize: '12px', fontWeight: 700, color: '#475569', textTransform: 'uppercase', letterSpacing: '0.04em' }}>Full Name</label>
            <div className="input-icon-wrapper">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" /><circle cx="12" cy="7" r="4" /></svg>
              <input type="text" name="name" required className="form-control" placeholder="e.g. Dr. Jane Smith" value={formData.name} onChange={handleChange} />
            </div>
          </div>

          <div>
            <label style={{ fontSize: '12px', fontWeight: 700, color: '#475569', textTransform: 'uppercase', letterSpacing: '0.04em' }}>Email Address</label>
            <div className="input-icon-wrapper">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" /><polyline points="22,6 12,13 2,6" /></svg>
              <input type="email" name="email" required className="form-control" placeholder="e.g. teacher@academy.com" value={formData.email} onChange={handleChange} />
            </div>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px' }}>
            <div>
              <label style={{ fontSize: '12px', fontWeight: 700, color: '#475569', textTransform: 'uppercase', letterSpacing: '0.04em' }}>Department</label>
              <input type="text" name="department" className="form-control" style={{ paddingLeft: '14px' }} placeholder="Mathematics" value={formData.department} onChange={handleChange} />
            </div>
            <div>
              <label style={{ fontSize: '12px', fontWeight: 700, color: '#475569', textTransform: 'uppercase', letterSpacing: '0.04em' }}>Phone</label>
              <input type="text" name="phone" className="form-control" style={{ paddingLeft: '14px' }} placeholder="+1 555-0192" value={formData.phone} onChange={handleChange} />
            </div>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px' }}>
            <div>
              <label style={{ fontSize: '12px', fontWeight: 700, color: '#475569', textTransform: 'uppercase', letterSpacing: '0.04em' }}>Password</label>
              <input type="password" name="password" required className="form-control" style={{ paddingLeft: '14px' }} placeholder="••••••••" value={formData.password} onChange={handleChange} />
            </div>
            <div>
              <label style={{ fontSize: '12px', fontWeight: 700, color: '#475569', textTransform: 'uppercase', letterSpacing: '0.04em' }}>Confirm Password</label>
              <input type="password" name="password_confirmation" required className="form-control" style={{ paddingLeft: '14px' }} placeholder="••••••••" value={formData.password_confirmation} onChange={handleChange} />
            </div>
          </div>

          <div style={{ display: 'flex', gap: '12px', justifyContent: 'flex-end', marginTop: '24px' }}>
            <button type="button" onClick={onClose} className="inv-btn-secondary" disabled={loading}>
              Cancel
            </button>
            <button type="submit" className="inv-btn-primary" disabled={loading}>
              {loading ? 'Creating...' : 'Create Teacher'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
