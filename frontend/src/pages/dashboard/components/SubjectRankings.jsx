/**
 * ============================================================================
 * pages/dashboard/components/SubjectRankings.jsx
 * ============================================================================
 * Top subject performance ranking list with animated progress bars.
 * ============================================================================
 */

import React from 'react';

export default function SubjectRankings({ rankings = [] }) {
  const topRankings = Array.isArray(rankings) ? rankings.slice(0, 5) : [];

  return (
    <div className="premium-card">
      <div className="card-header" style={{ marginBottom: '16px' }}>
        <div>
          <h3 className="card-title">Subject Rankings</h3>
          <p className="card-subtitle">Top performing subjects</p>
        </div>
      </div>

      <div>
        {topRankings.length > 0 ? (
          topRankings.map((s, idx) => (
            <div key={idx} className="subject-rank-row">
              <div style={{ display: 'flex', alignItems: 'center', gap: '8px', minWidth: 0 }}>
                <div
                  style={{
                    width: '24px',
                    height: '24px',
                    borderRadius: '7px',
                    background: idx === 0 ? '#eef2ff' : '#f8fafc',
                    color: idx === 0 ? '#6366f1' : '#94a3b8',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    fontSize: '11px',
                    fontWeight: 800,
                    flexShrink: 0,
                  }}
                >
                  #{idx + 1}
                </div>
                <span style={{ fontSize: '13px', fontWeight: 600, color: '#1e293b', whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>
                  {s.subject}
                </span>
              </div>
              <div className="sr-bar-bg">
                <div className="sr-bar-fill" style={{ width: `${Math.min(100, Math.max(0, s.avg))}%` }}></div>
              </div>
              <span style={{ fontSize: '13px', fontWeight: 800, color: idx === 0 ? '#6C5CE7' : '#475569', minWidth: '45px', textAlign: 'right' }}>
                {s.avg}%
              </span>
            </div>
          ))
        ) : (
          <p style={{ color: '#94a3b8', fontSize: '13px', textAlign: 'center', padding: '16px 0', margin: 0 }}>
            No subject data available.
          </p>
        )}
      </div>
    </div>
  );
}
