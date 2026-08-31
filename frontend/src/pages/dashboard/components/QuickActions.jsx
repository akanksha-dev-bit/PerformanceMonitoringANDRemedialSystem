/**
 * ============================================================================
 * pages/dashboard/components/QuickActions.jsx
 * ============================================================================
 * Shortcut buttons for common admin workflows using React Router.
 * ============================================================================
 */

import React from 'react';
import { Link } from 'react-router-dom';

export default function QuickActions() {
  return (
    <div className="premium-card">
      <div className="card-header" style={{ marginBottom: '16px' }}>
        <div>
          <h3 className="card-title">Quick Actions</h3>
          <p className="card-subtitle">Frequently used shortcuts</p>
        </div>
      </div>

      <div style={{ display: 'grid', gridTemplateColumns: '1fr', gap: '10px' }}>
        <Link to="/marks/create" className="btn-quick-action" id="qa-upload-marks">
          <div className="qa-icon" style={{ color: '#6C5CE7' }}>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
            </svg>
          </div>
          Upload Marks
        </Link>

        <Link to="/students/create" className="btn-quick-action" id="qa-add-student">
          <div className="qa-icon" style={{ color: '#10b981' }}>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <path d="M16 21v-2a4 4 0 0 0-4-4H5c-1.1 0-2 .9-2 2v2" />
              <circle cx="8.5" cy="7" r="4" />
              <line x1="20" y1="8" x2="20" y2="14" />
              <line x1="23" y1="11" x2="17" y2="11" />
            </svg>
          </div>
          Add New Student
        </Link>

        <Link to="/subjects" className="btn-quick-action" id="qa-manage-subjects">
          <div className="qa-icon" style={{ color: '#f59e0b' }}>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
              <line x1="3" y1="9" x2="21" y2="9" />
              <line x1="9" y1="21" x2="9" y2="9" />
            </svg>
          </div>
          Manage Subjects
        </Link>

        <Link to="/teachers" className="btn-quick-action" id="qa-view-teachers">
          <div className="qa-icon" style={{ color: '#3b82f6' }}>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
              <circle cx="9" cy="7" r="4" />
            </svg>
          </div>
          View Teachers Directory
        </Link>
      </div>
    </div>
  );
}
