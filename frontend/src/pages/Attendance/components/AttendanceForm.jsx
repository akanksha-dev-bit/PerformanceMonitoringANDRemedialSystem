import React from 'react';

export default function AttendanceForm({ students, attendance, onToggle, onSetAll }) {
  return (
    <div>
      <div style={{ display: 'flex', gap: '12px', marginBottom: '16px' }}>
        <button type="button" className="btn btn-outline" onClick={() => onSetAll(true)}>Mark All Present</button>
        <button type="button" className="btn btn-outline" onClick={() => onSetAll(false)}>Mark All Absent</button>
      </div>

      <div className="grid-container">
        {students.map((s) => {
          const isPresent = !!attendance[s.id];
          return (
            <div 
              key={s.id} 
              className={`student-card ${isPresent ? 'present' : 'absent'}`}
              onClick={() => onToggle(s.id)}
            >
              <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                <div className={`avatar ${isPresent ? 'present' : 'absent'}`}>
                  {s.name ? s.name.charAt(0).toUpperCase() : 'S'}
                </div>
                <div>
                  <div style={{ fontWeight: 700, color: '#0f172a' }}>{s.name}</div>
                  <div style={{ fontSize: '11px', color: '#64748b' }}>Roll No: {s.roll_no || 'N/A'}</div>
                </div>
              </div>
              <input 
                type="checkbox" 
                checked={isPresent} 
                onChange={() => onToggle(s.id)} 
                onClick={(e) => e.stopPropagation()} 
              />
            </div>
          );
        })}
      </div>
    </div>
  );
}
