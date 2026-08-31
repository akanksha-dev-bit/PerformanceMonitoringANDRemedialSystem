/**
 * ============================================================================
 * pages/dashboard/components/ActionRequiredAlerts.jsx
 * ============================================================================
 * Alerts panel for flagged slow learners needing attention.
 * ============================================================================
 */

import React from 'react';
import { Link } from 'react-router-dom';

export default function ActionRequiredAlerts({ alerts = [] }) {
  return (
    <div className="premium-card">
      <div className="card-header" style={{ marginBottom: '16px' }}>
        <h3 className="card-title" style={{ color: '#ef4444', display: 'flex', alignItems: 'center', gap: '6px' }}>
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
            <line x1="12" y1="9" x2="12" y2="13" />
            <line x1="12" y1="17" x2="12.01" y2="17" />
          </svg>
          Action Required
        </h3>
      </div>

      <div className="flex flex-col">
        {alerts.length > 0 ? (
          alerts.map((learner) => (
            <div key={learner.id} className="alert-item">
              <div>
                <div className="alert-title">{learner.name}</div>
                <div className="alert-desc">Avg: {learner.avg_pct}% — Needs Attention</div>
              </div>
              <Link to={`/students/${learner.id}`} className="btn-review">
                Review
              </Link>
            </div>
          ))
        ) : (
          <div style={{ padding: '24px', textAlign: 'center', borderRadius: '12px', background: '#f8fafc', border: '1px dashed #cbd5e1' }}>
            <p style={{ color: '#64748b', fontWeight: 500, fontSize: '13px', margin: 0 }}>
              All students are performing well. No critical alerts.
            </p>
          </div>
        )}
      </div>
    </div>
  );
}
