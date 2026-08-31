/**
 * ============================================================================
 * pages/dashboard/components/PerformanceTrendChart.jsx
 * ============================================================================
 * Visual trend card for academy performance.
 * ============================================================================
 */

import React from 'react';
import { Link } from 'react-router-dom';

export default function PerformanceTrendChart({ trendData }) {
  const hasData = Array.isArray(trendData) && trendData.length > 0;

  return (
    <div className="premium-card">
      <div className="card-header">
        <div>
          <h3 className="card-title">Academy Performance Trends</h3>
          <p className="card-subtitle">Average marks progression over recent assessments.</p>
        </div>
        <Link to="/reports" className="btn-solid-dark" style={{ padding: '8px 16px', fontSize: '13px' }}>
          Full Report
        </Link>
      </div>

      <div style={{ height: '220px', width: '100%', display: 'flex', alignItems: 'center', justifyContent: 'center', background: '#f8fafc', borderRadius: '14px', border: '1px dashed #cbd5e1' }}>
        {hasData ? (
          <div style={{ width: '100%', padding: '20px' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '8px', fontSize: '13px', fontWeight: '600', color: '#475569' }}>
              <span>Recent Assessments Trend</span>
              <span>Average: {Math.round(trendData.reduce((acc, curr) => acc + (curr.avg || 0), 0) / trendData.length)}%</span>
            </div>
            <div style={{ display: 'flex', gap: '8px', alignItems: 'flex-end', height: '140px' }}>
              {trendData.map((item, idx) => (
                <div key={idx} style={{ flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center', height: '100%', justifyContent: 'flex-end' }}>
                  <div
                    style={{
                      width: '100%',
                      maxWidth: '40px',
                      height: `${Math.min(100, Math.max(10, item.avg || 0))}%`,
                      background: 'linear-gradient(180deg, #6C5CE7 0%, #8B5CF6 100%)',
                      borderRadius: '6px 6px 0 0',
                      transition: 'height 0.4s ease',
                    }}
                  ></div>
                  <span style={{ fontSize: '11px', color: '#64748b', marginTop: '6px' }}>{item.label || `T${idx + 1}`}</span>
                </div>
              ))}
            </div>
          </div>
        ) : (
          <div style={{ textAlign: 'center', padding: '24px' }}>
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" strokeWidth="1.5" style={{ margin: '0 auto 8px' }}>
              <path d="M3 3v18h18" />
              <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3" />
            </svg>
            <p style={{ color: '#64748b', fontWeight: '500', fontSize: '14px', margin: 0 }}>No trend data available yet.</p>
            <span style={{ color: '#94a3b8', fontSize: '12px' }}>Record marks to populate performance progression analytics.</span>
          </div>
        )}
      </div>
    </div>
  );
}
