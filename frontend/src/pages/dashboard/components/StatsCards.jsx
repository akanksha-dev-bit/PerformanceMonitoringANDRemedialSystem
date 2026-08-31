/**
 * ============================================================================
 * pages/dashboard/components/StatsCards.jsx
 * ============================================================================
 * KPI summary metrics cards.
 * ============================================================================
 */

import React from 'react';

export default function StatsCards({ stats }) {
  const {
    students = 0,
    teachers = 0,
    slow_learners = 0,
    not_evaluated = 0,
    active_remedials = 0,
  } = stats || {};

  return (
    <div className="kpi-container">
      {/* Total Students */}
      <div className="kpi-box kpi-primary">
        <div className="kpi-icon-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
          </svg>
        </div>
        <div>
          <div className="kpi-value">{students}</div>
          <div className="kpi-label">Total Students</div>
        </div>
      </div>

      {/* Active Teachers */}
      <div className="kpi-box kpi-success">
        <div className="kpi-icon-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
          </svg>
        </div>
        <div>
          <div className="kpi-value">{teachers}</div>
          <div className="kpi-label">Active Teachers</div>
        </div>
      </div>

      {/* Slow Learners */}
      <div className="kpi-box kpi-warning">
        <div className="kpi-icon-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
            <line x1="12" y1="9" x2="12" y2="13" />
            <line x1="12" y1="17" x2="12.01" y2="17" />
          </svg>
        </div>
        <div>
          <div className="kpi-value">{slow_learners}</div>
          <div className="kpi-label">Slow Learners</div>
        </div>
      </div>

      {/* Not Evaluated */}
      <div className="kpi-box kpi-info">
        <div className="kpi-icon-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="8" x2="12" y2="16" />
            <line x1="8" y1="12" x2="16" y2="12" />
          </svg>
        </div>
        <div>
          <div className="kpi-value">{not_evaluated}</div>
          <div className="kpi-label">Not Evaluated</div>
        </div>
      </div>

      {/* Active Remedials */}
      <div className="kpi-box kpi-danger">
        <div className="kpi-icon-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
            <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
          </svg>
        </div>
        <div>
          <div className="kpi-value">{active_remedials}</div>
          <div className="kpi-label">Active Remedials</div>
        </div>
      </div>
    </div>
  );
}
