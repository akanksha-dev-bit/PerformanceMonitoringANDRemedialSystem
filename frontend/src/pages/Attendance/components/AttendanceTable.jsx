import React from 'react';

export default function AttendanceTable({ slots, completedKeys }) {
  return (
    <div style={{ overflowX: 'auto' }}>
      <table className="w-full text-left border-collapse">
        <thead>
          <tr className="border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase">
            <th className="py-3 px-4">Period</th>
            <th className="py-3 px-4">Classroom</th>
            <th className="py-3 px-4">Subject</th>
            <th className="py-3 px-4">Time</th>
            <th className="py-3 px-4">Status</th>
          </tr>
        </thead>
        <tbody>
          {slots.map((slot) => {
            const isMarked = completedKeys.includes(slot.period_number);
            return (
              <tr key={slot.id} className="border-b border-gray-100 hover:bg-gray-50">
                <td className="py-3 px-4 font-semibold text-gray-900">Period {slot.period_number}</td>
                <td className="py-3 px-4">{slot.classroom?.display_name || '—'}</td>
                <td className="py-3 px-4 font-medium text-indigo-600">{slot.subject?.name || '—'}</td>
                <td className="py-3 px-4 text-sm text-gray-500">{slot.start_time} - {slot.end_time}</td>
                <td className="py-3 px-4">
                  <span className={`px-2 py-1 text-xs font-bold rounded-full ${isMarked ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'}`}>
                    {isMarked ? 'Marked' : 'Pending'}
                  </span>
                </td>
              </tr>
            );
          })}
        </tbody>
      </table>
    </div>
  );
}
