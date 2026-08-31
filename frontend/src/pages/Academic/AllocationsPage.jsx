/**
 * ============================================================================
 * pages/Academic/AllocationsPage.jsx — Teacher-Subject-Section Allocations
 * ============================================================================
 * Admin page for linking a teacher to a subject within a specific section
 * for a given academic year. Redesigned with reusable UI components and Tailwind.
 * ============================================================================
 */

import { useState, useEffect, useCallback } from 'react';
import { Link } from 'react-router-dom';
import academicService from '@/services/academicService';
import { teacherService, subjectService } from '@/services';
import { Card, Button, Input, Table, Badge, Loader, EmptyState } from '@/components/ui';
import { UserCheck, Plus, Trash2, Calendar, Columns, Filter, BookOpen } from 'lucide-react';
import { cn } from '@/utils/cn';

// ── Floating Toast Notification ──────────────────────────────────────────────
function Toast({ msg, type, onClose }) {
  if (!msg) return null;
  const isError = type === 'error';
  return (
    <div
      className={cn(
        "fixed top-6 right-6 z-[9999] flex items-center gap-3 px-5 py-3.5 rounded-2xl border text-sm font-semibold shadow-2xl transition-all duration-300 transform translate-y-0",
        isError 
          ? "bg-red-50 border-red-200 text-red-800" 
          : "bg-green-50 border-green-200 text-green-800"
      )}
    >
      <div className={cn("w-2 h-2 rounded-full", isError ? "bg-red-500" : "bg-green-500")} />
      <span className="flex-1">{msg}</span>
      <button 
        onClick={onClose} 
        className="text-lg leading-none opacity-60 hover:opacity-100 transition-opacity ml-2"
      >
        ×
      </button>
    </div>
  );
}

// ── Main Page Component ───────────────────────────────────────────────────────
export default function AllocationsPage() {
  const [years, setYears] = useState([]);
  const [sections, setSections] = useState([]);
  const [teachers, setTeachers] = useState([]);
  const [subjects, setSubjects] = useState([]);
  const [allocations, setAllocations] = useState([]);

  const [selectedYear, setSelectedYear] = useState(null);
  const [filterSection, setFilterSection] = useState('');
  const [loading, setLoading] = useState(true);
  const [toast, setToast] = useState(null);

  const [form, setForm] = useState({ teacher_id: '', subject_id: '', classroom_id: '' });
  const [creating, setCreating] = useState(false);

  const notify = (msg, type = 'success') => { setToast({ msg, type }); setTimeout(() => setToast(null), 6000); };

  // Bootstrap static lookup collections (years, teachers, subjects)
  useEffect(() => {
    Promise.all([
      academicService.getYears(),
      teacherService.getTeachers({ all: 1 }),
      subjectService.getSubjects({ all: 1 }),
    ]).then(([yRes, tRes, sRes]) => {
      const ys = yRes.data || [];
      setYears(ys);
      const current = ys.find(y => y.is_current) ?? ys[0];
      if (current) setSelectedYear(current.id);

      const ts = Array.isArray(tRes.data?.data) ? tRes.data.data : (Array.isArray(tRes.data) ? tRes.data : []);
      setTeachers(ts);

      const ss = Array.isArray(sRes.data?.data) ? sRes.data.data : (Array.isArray(sRes.data) ? sRes.data : []);
      setSubjects(ss);
    }).catch(() => notify('Failed to load allocation data.', 'error'));
  }, []);

  // Fetch sections relative to selected academic year
  useEffect(() => {
    if (!selectedYear) return;
    academicService.getSections({ year_id: selectedYear })
      .then(res => setSections(res.data || []))
      .catch(() => notify('Failed to load sections.', 'error'));
  }, [selectedYear]);

  // Load allocations based on year/filter section
  const loadAllocations = useCallback(async () => {
    if (!selectedYear) return;
    setLoading(true);
    try {
      const params = { year_id: selectedYear };
      if (filterSection) params.section_id = filterSection;
      const res = await academicService.getAllocations(params);
      setAllocations(res.data || []);
    } catch {
      notify('Failed to load teacher allocations.', 'error');
    } finally {
      setLoading(false);
    }
  }, [selectedYear, filterSection]);

  useEffect(() => { loadAllocations(); }, [loadAllocations]);

  const handleCreate = async (e) => {
    e.preventDefault();
    if (!form.teacher_id || !form.subject_id || !form.classroom_id) return;
    setCreating(true);
    try {
      await academicService.createAllocation({ ...form, academic_year_id: selectedYear });
      setForm({ teacher_id: '', subject_id: '', classroom_id: '' });
      notify('Teacher allocation created successfully.');
      loadAllocations();
    } catch (err) {
      notify(err.response?.data?.message || 'Failed to create allocation.', 'error');
    } finally {
      setCreating(false);
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('Are you sure you want to remove this teacher allocation?')) return;
    try {
      await academicService.deleteAllocation(id);
      notify('Allocation removed successfully.');
      setAllocations(prev => prev.filter(a => a.id !== id));
    } catch (err) {
      notify(err.response?.data?.message || 'Cannot remove this allocation. It may have timetabled entries.', 'error');
    }
  };

  // Define table column scheme
  const columns = [
    {
      key: 'teacher',
      label: 'Teacher',
      render: (teacher) => {
        const initials = teacher?.name
          ? teacher.name.split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase()
          : 'TR';
        return (
          <div className="flex items-center gap-3">
            <div className="w-8 h-8 rounded-full bg-gradient-to-br from-[#6C5CE7]/15 to-[#6C5CE7]/5 border border-[#6C5CE7]/10 flex items-center justify-center text-xs font-bold text-[#6C5CE7]">
              {initials}
            </div>
            <span className="font-semibold text-gray-900">{teacher?.name || '—'}</span>
          </div>
        );
      }
    },
    {
      key: 'subject',
      label: 'Subject & Code',
      render: (subject) => (
        <div className="flex items-center gap-2.5">
          <span className="text-gray-700 font-medium">{subject?.name || '—'}</span>
          {subject?.code && (
            <Badge variant="info" size="sm">
              {subject.code}
            </Badge>
          )}
        </div>
      )
    },
    {
      key: 'section',
      label: 'Classroom Section',
      render: (section) => (
        <Badge variant="primary" size="sm">
          {section?.display_name || '—'}
        </Badge>
      )
    },
    {
      key: 'academic_year',
      label: 'Academic Year',
      render: (year) => (
        <span className="text-xs text-gray-400 font-medium">{year?.label || '—'}</span>
      )
    },
    {
      key: 'id',
      label: 'Actions',
      headerClassName: 'text-right',
      className: 'text-right',
      render: (id) => (
        <Button
          onClick={() => handleDelete(id)}
          variant="secondary"
          size="sm"
          className="text-red-600 hover:text-white hover:bg-red-600 hover:border-red-600 py-1.5 px-3 text-xs"
        >
          <Trash2 className="w-3.5 h-3.5 mr-1" />
          Remove
        </Button>
      )
    }
  ];

  const emptyStateElement = (
    <EmptyState
      title="No Teacher Allocations Found"
      description={filterSection 
        ? "No allocation registry matches the selected classroom section filter." 
        : "Start by assigning a teacher to a subject and section classroom using the creator card above."
      }
    />
  );

  return (
    <div className="max-w-[1400px] mx-auto px-4 py-8">
      <Toast msg={toast?.msg} type={toast?.type} onClose={() => setToast(null)} />

      {/* Modern Header Section */}
      <div className="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8 border-b border-black/[0.05] pb-6">
        <div>
          <div className="flex items-center gap-2.5 text-gray-500 mb-1.5">
            <UserCheck className="w-5 h-5 text-[#6C5CE7]" />
            <span className="text-xs font-bold uppercase tracking-wider">Academic Structure</span>
          </div>
          <h1 className="text-3xl font-extrabold text-gray-900 tracking-tight leading-none">
            Teacher Allocations
          </h1>
          <p className="text-sm text-gray-500 mt-2 max-w-xl leading-relaxed">
            Link and allocate faculty teachers to teach specific subjects within classroom sections for the chosen academic year.
          </p>
        </div>

        {/* Year Select & Link Navigation */}
        <div className="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
          <div className="flex items-center gap-2">
            <span className="text-xs font-bold uppercase text-gray-400 tracking-wider">Academic Year:</span>
            <Input.Select 
              value={selectedYear ?? ''} 
              onChange={e => setSelectedYear(Number(e.target.value))}
              className="py-1.5 px-3 min-w-[130px] rounded-xl focus:ring-2 focus:ring-[#6C5CE7]/20 focus:border-[#6C5CE7]"
            >
              {years.map(y => <option key={y.id} value={y.id}>{y.label}{y.is_current ? ' ★' : ''}</option>)}
            </Input.Select>
          </div>
          
          <div className="flex bg-gray-100/85 p-1 rounded-2xl border border-black/[0.04] self-stretch sm:self-auto justify-center">
            <Link
              to="/classrooms"
              className="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-900 rounded-xl transition-all"
            >
              <Columns className="w-3.5 h-3.5" />
              Sections
            </Link>
            <Link
              to="/timetables"
              className="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-900 rounded-xl transition-all"
            >
              <Calendar className="w-3.5 h-3.5" />
              Timetables
            </Link>
          </div>
        </div>
      </div>

      {/* Redesigned Allocations Panel Card */}
      <Card className="mb-8 p-5 bg-gradient-to-br from-white to-gray-50/50">
        <h2 className="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3.5">
          Assign New Academic Allocation
        </h2>
        <form onSubmit={handleCreate} className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
          <Input.Select
            label="Assign Teacher"
            value={form.teacher_id} 
            onChange={e => setForm(f => ({ ...f, teacher_id: e.target.value }))}
            className="w-full h-11 focus:ring-2 focus:ring-[#6C5CE7]/20 focus:border-[#6C5CE7]"
          >
            <option value="">Select teacher…</option>
            {teachers.map(t => <option key={t.id} value={t.id}>{t.name}</option>)}
          </Input.Select>

          <Input.Select
            label="Subject Code"
            value={form.subject_id} 
            onChange={e => setForm(f => ({ ...f, subject_id: e.target.value }))}
            className="w-full h-11 focus:ring-2 focus:ring-[#6C5CE7]/20 focus:border-[#6C5CE7]"
          >
            <option value="">Select subject…</option>
            {subjects.map(s => <option key={s.id} value={s.id}>{s.name} ({s.code})</option>)}
          </Input.Select>

          <Input.Select
            label="Classroom Section"
            value={form.classroom_id} 
            onChange={e => setForm(f => ({ ...f, classroom_id: e.target.value }))}
            className="w-full h-11 focus:ring-2 focus:ring-[#6C5CE7]/20 focus:border-[#6C5CE7]"
          >
            <option value="">Select section classroom…</option>
            {sections.map(s => <option key={s.id} value={s.id}>{s.display_name}</option>)}
          </Input.Select>

          <Button 
            type="submit" 
            disabled={creating || !form.teacher_id || !form.subject_id || !form.classroom_id}
            variant="primary"
            className="h-11 w-full flex items-center justify-center gap-1.5"
          >
            <Plus className="w-4 h-4" />
            {creating ? 'Allocating…' : 'Allocate'}
          </Button>
        </form>
      </Card>

      {/* Filter and Overview Stats Header */}
      <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
        <span className="text-xs font-bold uppercase tracking-wider text-gray-400">
          Current Allocations ({allocations.length})
        </span>

        {/* Inline Section Filter */}
        <div className="flex items-center gap-2 self-stretch sm:self-auto">
          <Filter className="w-4 h-4 text-gray-400" />
          <Input.Select 
            value={filterSection} 
            onChange={e => setFilterSection(e.target.value)} 
            className="py-1 px-3 text-xs min-w-[150px] rounded-xl focus:ring-2 focus:ring-[#6C5CE7]/20"
          >
            <option value="">All Section Classrooms</option>
            {sections.map(s => <option key={s.id} value={s.id}>{s.display_name}</option>)}
          </Input.Select>
        </div>
      </div>

      {/* Allocations Data Table Card */}
      <Card padding={false} className="overflow-hidden shadow-[0_4px_20px_-2px_rgba(0,0,0,0.03)]">
        {loading ? (
          <div className="flex flex-col items-center justify-center py-20 gap-4">
            <Loader size="lg" />
            <span className="text-sm font-medium text-gray-500 animate-pulse">Loading allocations...</span>
          </div>
        ) : (
          <Table 
            columns={columns} 
            data={allocations} 
            empty={emptyStateElement}
            className="w-full"
          />
        )}
      </Card>
    </div>
  );
}
