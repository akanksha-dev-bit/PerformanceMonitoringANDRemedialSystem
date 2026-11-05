/**
 * ============================================================================
 * pages/dashboard/components/PerformanceTrendChart.jsx
 * ============================================================================
 * Visual trend card for academy performance.
 * ============================================================================
 */

import React, { useState } from 'react';
import { Link } from 'react-router-dom';

export default function PerformanceTrendChart({ trendData }) {
  const [filter, setFilter] = useState('all');
  const [hovered, setHovered] = useState(null);
  const rows = (Array.isArray(trendData) ? trendData : []).filter(row => filter === 'all' || row.exam_type === filter);
  const points = rows.map((row, index) => ({
    x: rows.length === 1 ? 50 : 8 + 84 * index / (rows.length - 1),
    y: 88 - Math.max(0, Math.min(100, Number(row.avg) || 0)) * .72,
    row,
  }));
  const line = points.map((point, index) => `${index ? 'L' : 'M'} ${point.x} ${point.y}`).join(' ');
  const area = points.length ? `${line} L ${points.at(-1).x} 88 L ${points[0].x} 88 Z` : '';

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

      <div className="trend-controls" role="group" aria-label="Assessment filter">
        {[['all', 'All assessments'], ['unit_test', 'Unit tests'], ['midterm', 'Mid-term'], ['final', 'Finals'], ['practical', 'Practical']].map(([value, label]) => (
          <button key={value} type="button" className="trend-filter" aria-pressed={filter === value} onClick={() => { setFilter(value); setHovered(null); }}>{label}</button>
        ))}
      </div>
      {rows.length ? <>
        <div className="trend-graph">
          <div className="trend-y-axis"><span>100%</span><span>75%</span><span>50%</span><span>25%</span><span>0%</span></div>
          <div className="trend-plot">
            <svg viewBox="0 0 100 100" preserveAspectRatio="none" role="img" aria-label={`Average marks: ${rows.map(row => `${row.label} ${row.avg}%`).join(', ')}`}>
              <defs><linearGradient id="trend-fill" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stopColor="#7c6aed" stopOpacity=".3"/><stop offset="100%" stopColor="#7c6aed" stopOpacity="0"/></linearGradient></defs>
              {[16, 34, 52, 70, 88].map(y => <line key={y} x1="8" x2="92" y1={y} y2={y} stroke="#eceaf5" strokeWidth=".5" strokeDasharray="1.3 1.3" />)}
              <path d={area} fill="url(#trend-fill)" /><path d={line} fill="none" stroke="#6c5ce7" strokeWidth="1" strokeLinecap="round" strokeLinejoin="round" vectorEffect="non-scaling-stroke" style={{ strokeWidth: 3 }} />
              {points.map((point, i) => <circle key={i} cx={point.x} cy={point.y} r="1.4" fill="#fff" stroke="#6c5ce7" strokeWidth=".7" />)}
            </svg>
            {points.map((point, i) => <button key={i} className="trend-hit" type="button" style={{ left: `${point.x}%`, top: `${point.y}%` }} aria-label={`${point.row.label}: ${point.row.avg}% average from ${point.row.count} marks`} onMouseEnter={() => setHovered(i)} onMouseLeave={() => setHovered(null)} onFocus={() => setHovered(i)} onBlur={() => setHovered(null)} />)}
            {hovered !== null && points[hovered] && <div className="trend-tooltip" style={{ left: `${points[hovered].x}%`, top: `${points[hovered].y}%` }} role="status"><strong>{points[hovered].row.label}</strong><span>{Number(points[hovered].row.avg).toFixed(1)}% average · {points[hovered].row.count} marks</span></div>}
          </div>
        </div>
        <div className="trend-x-labels">{rows.map((row, i) => <span key={i} title={row.label}>{row.label}</span>)}</div>
        <div className="trend-legend"><span className="trend-legend-dot" />Average marks · {rows.length} assessment groups</div>
      </> : <div className="trend-empty">No marks for this assessment type yet. Record marks to see performance trends.</div>}
    </div>
  );
}
