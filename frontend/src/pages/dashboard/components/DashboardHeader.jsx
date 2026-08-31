/**
 * ============================================================================
 * pages/dashboard/components/DashboardHeader.jsx
 * ============================================================================
 * Welcome banner & primary header CTA buttons.
 * ============================================================================
 */

import React from 'react';
import { Link } from 'react-router-dom';

export default function DashboardHeader({ adminName, onOpenAddTeacher }) {
  return (
    <div className="admin-header">
      <div style={{ position: 'relative', zIndex: 2 }}>
        <h2 className="admin-title">Welcome back, {adminName || 'Admin'} 👋</h2>
        <p className="admin-subtitle">Here is what's happening at your academy today.</p>
      </div>

      <div style={{ display: 'flex', alignItems: 'center', gap: '12px', position: 'relative', zIndex: 2 }}>
        <button type="button" className="btn-solid-dark" onClick={onOpenAddTeacher} id="admin-add-teacher-btn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
            <path d="M16 21v-2a4 4 0 0 0-4-4H5c-1.1 0-2 .9-2 2v2" />
            <circle cx="8.5" cy="7" r="4" />
            <line x1="20" y1="8" x2="20" y2="14" />
            <line x1="23" y1="11" x2="17" y2="11" />
          </svg>
          Add Teacher
        </button>
        <Link to="/students/create" className="btn-solid-primary" id="admin-add-student-btn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
            <circle cx="12" cy="7" r="4" />
            <line x1="12" y1="11" x2="12" y2="17" />
            <line x1="9" y1="14" x2="15" y2="14" />
          </svg>
          Add Student
        </Link>
      </div>
    </div>
  );
}
