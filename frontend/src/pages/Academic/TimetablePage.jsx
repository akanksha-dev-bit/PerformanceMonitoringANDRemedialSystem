/**
 * ============================================================================
 * pages/Academic/TimetablePage.jsx — Timetable Management
 * ============================================================================
 * Admin page for scheduling teacher-subject-section combinations into
 * period slots across the week.
 *
 * Features:
 *   - Year + section selectors
 *   - Weekly grid view (Mon→Sat × periods 1–N)
 *   - Add slot form: picks from approved allocations for the selected section
 *   - Conflict errors surfaced from the API/model in a readable way
 *   - Click a slot to delete it
 * ============================================================================
 */

import { useState, useEffect, useCallback } from 'react';
import { Link } from 'react-router-dom';
import academicService from '@/services/academicService';

// ── Helpers ───────────────────────────────────────────────────────────────────

function Toast({ msg, type, onClose }) {
  if (!msg) return null;
  const bg = type === 'error' ? '#fee2e2' : '#dcfce7';
  const border = type === 'error' ? '#fca5a5' : '#86efac';
  const color = type === 'error' ? '#b91c1c' : '#166534';
  return (
    <div style={{ position: 'fixed', top: 24, right: 24, zIndex: 9999, background: bg, border: `1px solid ${border}`, color, borderRadius: 12, padding: '12px 20px', fontSize: 14, fontWeight: 600, boxShadow: '0 4px 24px rgba(0,0,0,0.10)', display: 'flex', alignItems: 'center', gap: 10, maxWidth: 480 }}>
      <span style={{ flex: 1 }}>{msg}</span>
      <button onClick={onClose} style={{ background: 'none', border: 'none', cursor: 'pointer', fontSize: 18, color }}>×</button>
    </div>
  );
}

function btnStyle(bg, color, disabled = false) {
  return { background: bg, color, border: 'none', borderRadius: 8, padding: '6px 14px', fontSize: 13, fontWeight: 600, cursor: disabled ? 'not-allowed' : 'pointer', opacity: disabled ? 0.6 : 1 };
}

const DAYS = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
const selectStyle = { border: '1.5px solid #e5e7eb', borderRadius: 8, padding: '8px 12px', fontSize: 14, outline: 'none', background: '#fff' };
const inputStyle = { border: '1.5px solid #e5e7eb', borderRadius: 8, padding: '8px 12px', fontSize: 14, outline: 'none' };
const labelStyle = { fontSize: 12, fontWeight: 700, color: '#6b7280', display: 'block', marginBottom: 4 };

// Slot pill in the weekly grid
function SlotPill({ slot, onDelete }) {
  return (
    <div style={{ background: 'linear-gradient(135deg, #152238, #1e3a5f)', color: '#fff', borderRadius: 10, padding: '8px 10px', marginBottom: 4, position: 'relative', fontSize: 12 }}>
      <div style={{ fontWeight: 700, marginBottom: 2 }}>{slot.subject?.name}</div>
      <div style={{ opacity: 0.8 }}>{slot.teacher?.name}</div>
      <div style={{ opacity: 0.6, fontSize: 11 }}>{slot.start_time}–{slot.end_time}</div>
      <button
        onClick={() => onDelete(slot.id)}
        title="Delete slot"
        style={{ position: 'absolute', top: 4, right: 4, background: 'rgba(255,255,255,0.15)', border: 'none', borderRadius: 6, color: '#fff', cursor: 'pointer', fontSize: 12, lineHeight: 1, padding: '2px 5px', fontWeight: 700 }}
      >×</button>
    </div>
  );
}

// ── Main Page ─────────────────────────────────────────────────────────────────

export default function TimetablePage() {
  const [years, setYears] = useState([]);
  const [sections, setSections] = useState([]);
  const [allocations, setAllocations] = useState([]);
  const [slots, setSlots] = useState([]);

  const [selectedYear, setSelectedYear] = useState(null);
  const [selectedSection, setSelectedSection] = useState('');
  const [loading, setLoading] = useState(false);
  const [toast, setToast] = useState(null);

  const [form, setForm] = useState({
    allocation_id: '',
    day_of_week: 'Monday',
    period_number: 1,
    start_time: '09:00',
    end_time: '09:45',
  });
  const [creating, setCreating] = useState(false);
  const [errors, setErrors] = useState({});

  const notify = (msg, type = 'success') => { setToast({ msg, type }); setTimeout(() => setToast(null), 6000); };

  // Bootstrap years
  useEffect(() => {
    academicService.getYears().then(res => {
      const ys = res.data || [];
      setYears(ys);
      const current = ys.find(y => y.is_current) ?? ys[0];
      if (current) setSelectedYear(current.id);
    }).catch(() => notify('Failed to load years.', 'error'));
  }, []);

  // Load sections when year changes
  useEffect(() => {
    if (!selectedYear) return;
    academicService.getSections({ year_id: selectedYear }).then(res => {
      const secs = res.data || [];
      setSections(secs);
      if (secs.length > 0) setSelectedSection(secs[0].id);
    });
  }, [selectedYear]);

  // Load allocations + slots when section changes
  const loadData = useCallback(async () => {
    if (!selectedSection || !selectedYear) return;
    setLoading(true);
    try {
      const [aRes, sRes] = await Promise.all([
        academicService.getAllocations({ section_id: selectedSection, year_id: selectedYear }),
        academicService.getTimetableSlots({ section_id: selectedSection, year_id: selectedYear }),
      ]);
      setAllocations(aRes.data || []);
      setSlots(sRes.data || []);
      if ((aRes.data || []).length > 0) {
        setForm(f => ({ ...f, allocation_id: aRes.data[0].id }));
      }
    } catch {
      notify('Failed to load timetable data.', 'error');
    } finally {
      setLoading(false);
    }
  }, [selectedSection, selectedYear]);

  useEffect(() => { loadData(); }, [loadData]);

  const handleCreate = async (e) => {
    e.preventDefault();
    setErrors({});
    if (!form.allocation_id) return;
    setCreating(true);
    try {
      await academicService.createTimetableSlot({
        classroom_id: selectedSection,
        academic_year_id: selectedYear,
        ...form,
        period_number: Number(form.period_number),
      });
      notify('Slot scheduled.');
      loadData();
    } catch (err) {
      const data = err.response?.data;
      if (data?.errors) {
        setErrors(data.errors);
        notify(data.message || 'Scheduling conflict.', 'error');
      } else {
        notify(data?.message || 'Failed to create slot.', 'error');
      }
    } finally {
      setCreating(false);
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('Delete this timetable slot?')) return;
    try {
      await academicService.deleteTimetableSlot(id);
      notify('Slot deleted.');
      setSlots(prev => prev.filter(s => s.id !== id));
    } catch (err) {
      notify(err.response?.data?.message || 'Failed to delete slot.', 'error');
    }
  };

  // Build weekly grid
  const allPeriods = [...new Set(slots.map(s => s.period_number))].sort((a, b) => a - b);
  const maxPeriod = Math.max(8, ...allPeriods);
  const periods = Array.from({ length: maxPeriod }, (_, i) => i + 1);

  const slotsByDayPeriod = {};
  slots.forEach(s => {
    const key = `${s.day_of_week}-${s.period_number}`;
    if (!slotsByDayPeriod[key]) slotsByDayPeriod[key] = [];
    slotsByDayPeriod[key].push(s);
  });

  const selectedSectionLabel = sections.find(s => s.id === Number(selectedSection))?.display_name ?? '';

  return (
    <div style={{ padding: '32px 24px', maxWidth: 1100, margin: '0 auto' }}>
      <Toast msg={toast?.msg} type={toast?.type} onClose={() => setToast(null)} />

      {/* Header */}
      <div style={{ marginBottom: 28 }}>
        <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', flexWrap: 'wrap', gap: 12 }}>
          <div>
            <h1 style={{ fontSize: 26, fontWeight: 800, color: '#111', margin: 0, letterSpacing: '-0.03em' }}>Timetable</h1>
            <p style={{ color: '#6b7280', marginTop: 6, fontSize: 14 }}>
              Schedule approved teacher-subject allocations into period slots per section.
            </p>
          </div>
          <div style={{ display: 'flex', gap: 10, alignItems: 'center' }}>
            <select value={selectedYear ?? ''} onChange={e => setSelectedYear(Number(e.target.value))} style={selectStyle}>
              {years.map(y => <option key={y.id} value={y.id}>{y.label}{y.is_current ? ' ★' : ''}</option>)}
            </select>
            <select value={selectedSection} onChange={e => setSelectedSection(e.target.value)} style={selectStyle}>
              <option value="">Select section…</option>
              {sections.map(s => <option key={s.id} value={s.id}>{s.display_name}</option>)}
            </select>
          </div>
        </div>
        <div style={{ marginTop: 12, display: 'flex', gap: 8 }}>
          <Link to="/teacher-allocations" style={{ fontSize: 13, color: '#152238', fontWeight: 600 }}>← Allocations</Link>
        </div>
      </div>

      {/* Add slot form */}
      {selectedSection && (
        <form onSubmit={handleCreate} style={{ background: '#fff', border: '1px solid #e5e7eb', borderRadius: 16, padding: '20px 24px', marginBottom: 32, boxShadow: '0 2px 12px rgba(0,0,0,0.04)' }}>
          <h2 style={{ fontSize: 14, fontWeight: 800, color: '#374151', textTransform: 'uppercase', letterSpacing: '0.06em', margin: '0 0 16px' }}>
            Add Slot for {selectedSectionLabel}
          </h2>
          {allocations.length === 0 ? (
            <p style={{ color: '#f59e0b', fontSize: 14, fontWeight: 600 }}>
              ⚠ No allocations for this section yet. <Link to="/teacher-allocations">Add allocations first →</Link>
            </p>
          ) : (
            <div style={{ display: 'grid', gridTemplateColumns: '2fr 1fr 1fr 1fr 1fr auto', gap: 12, alignItems: 'end', flexWrap: 'wrap' }}>
              <div>
                <label style={labelStyle}>Teacher · Subject</label>
                <select value={form.allocation_id} onChange={e => setForm(f => ({ ...f, allocation_id: e.target.value }))} style={{ ...selectStyle, width: '100%' }}>
                  {allocations.map(a => <option key={a.id} value={a.id}>{a.teacher?.name} — {a.subject?.name}</option>)}
                </select>
                {errors.teacher_id && <p style={{ color: '#dc2626', fontSize: 12, marginTop: 4 }}>{errors.teacher_id}</p>}
              </div>
              <div>
                <label style={labelStyle}>Day</label>
                <select value={form.day_of_week} onChange={e => setForm(f => ({ ...f, day_of_week: e.target.value }))} style={{ ...selectStyle, width: '100%' }}>
                  {DAYS.map(d => <option key={d} value={d}>{d}</option>)}
                </select>
              </div>
              <div>
                <label style={labelStyle}>Period #</label>
                <input type="number" min={1} max={12} value={form.period_number}
                  onChange={e => setForm(f => ({ ...f, period_number: e.target.value }))}
                  style={{ ...inputStyle, width: '100%', boxSizing: 'border-box' }} />
                {errors.period_number && <p style={{ color: '#dc2626', fontSize: 12, marginTop: 4 }}>{errors.period_number}</p>}
              </div>
              <div>
                <label style={labelStyle}>Start Time</label>
                <input type="time" value={form.start_time} onChange={e => setForm(f => ({ ...f, start_time: e.target.value }))}
                  style={{ ...inputStyle, width: '100%', boxSizing: 'border-box' }} />
              </div>
              <div>
                <label style={labelStyle}>End Time</label>
                <input type="time" value={form.end_time} onChange={e => setForm(f => ({ ...f, end_time: e.target.value }))}
                  style={{ ...inputStyle, width: '100%', boxSizing: 'border-box' }} />
              </div>
              <button type="submit" disabled={creating} style={btnStyle('#152238', '#fff', creating)}>
                {creating ? '…' : '+ Add'}
              </button>
            </div>
          )}
          {errors.classroom_id && <p style={{ color: '#dc2626', fontSize: 13, marginTop: 8 }}>{errors.classroom_id}</p>}
        </form>
      )}

      {/* Weekly grid */}
      {!selectedSection ? (
        <div style={{ textAlign: 'center', padding: 48, color: '#9ca3af' }}>Select a section to view the timetable.</div>
      ) : loading ? (
        <div style={{ textAlign: 'center', padding: 48, color: '#9ca3af' }}>Loading…</div>
      ) : (
        <div style={{ overflowX: 'auto' }}>
          <table style={{ width: '100%', borderCollapse: 'collapse', minWidth: 700 }}>
            <thead>
              <tr style={{ background: '#152238' }}>
                <th style={{ padding: '12px 14px', color: '#94a3b8', fontSize: 12, fontWeight: 700, textAlign: 'left', width: 60 }}>Period</th>
                {DAYS.map(d => (
                  <th key={d} style={{ padding: '12px 14px', color: '#f1f5f9', fontSize: 13, fontWeight: 700, textAlign: 'left' }}>{d}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {periods.map(p => (
                <tr key={p} style={{ background: p % 2 === 0 ? '#f9fafb' : '#fff', borderBottom: '1px solid #f0f0f0' }}>
                  <td style={{ padding: '10px 14px', fontSize: 13, fontWeight: 800, color: '#9ca3af', textAlign: 'center' }}>{p}</td>
                  {DAYS.map(d => {
                    const key = `${d}-${p}`;
                    const daySlots = slotsByDayPeriod[key] || [];
                    return (
                      <td key={d} style={{ padding: '8px 10px', verticalAlign: 'top', minWidth: 120 }}>
                        {daySlots.map(slot => <SlotPill key={slot.id} slot={slot} onDelete={handleDelete} />)}
                      </td>
                    );
                  })}
                </tr>
              ))}
            </tbody>
          </table>
          {slots.length === 0 && (
            <p style={{ textAlign: 'center', color: '#9ca3af', marginTop: 24, fontSize: 14 }}>
              No slots scheduled yet for {selectedSectionLabel}.
            </p>
          )}
        </div>
      )}
    </div>
  );
}
