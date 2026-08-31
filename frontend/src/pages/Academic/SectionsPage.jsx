/**
 * ============================================================================
 * pages/Academic/SectionsPage.jsx — Sections (Classrooms) Management
 * ============================================================================
 * Admin page for managing sections within grade levels, grouped by year.
 * Redesigned with custom components, Tailwind CSS styling, and standard Modals.
 * ============================================================================
 */

import { useState, useEffect, useCallback } from 'react';
import { Link } from 'react-router-dom';
import academicService from '@/services/academicService';
import { Card, Button, Input, Loader, EmptyState, Badge, Modal } from '@/components/ui';
import { Columns, Plus, Edit2, Trash2, Users, Calendar, GraduationCap } from 'lucide-react';
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

// ── Edit Section Modal ────────────────────────────────────────────────────────
function EditSectionModal({ open, section, classes, onSave, onClose }) {
  const [name, setName] = useState(section?.name || '');
  const [classId, setClassId] = useState(section?.academic_class?.id ?? '');
  const [capacity, setCapacity] = useState(section?.capacity ?? '');
  const [saving, setSaving] = useState(false);

  // Sync state if section changes
  useEffect(() => {
    if (section) {
      setName(section.name);
      setClassId(section.academic_class?.id ?? '');
      setCapacity(section.capacity ?? '');
    }
  }, [section]);

  const save = async () => {
    setSaving(true);
    await onSave(section.id, { name, academic_class_id: classId, capacity: capacity || null });
    setSaving(false);
  };

  const footerActions = (
    <>
      <Button variant="secondary" size="md" onClick={onClose}>
        Cancel
      </Button>
      <Button 
        variant="primary" 
        size="md" 
        onClick={save} 
        disabled={saving || !name.trim()} 
        loading={saving}
      >
        Save Changes
      </Button>
    </>
  );

  return (
    <Modal
      open={open}
      onClose={onClose}
      title="Edit Section Details"
      footer={footerActions}
      size="sm"
    >
      <div className="flex flex-col gap-4">
        <Input 
          label="Section Name" 
          value={name} 
          onChange={e => setName(e.target.value)} 
          placeholder="e.g. A, Section A"
          className="focus:ring-2 focus:ring-[#6C5CE7]/20 focus:border-[#6C5CE7]"
        />
        
        <Input.Select
          label="Grade Class Level"
          value={classId}
          onChange={e => setClassId(e.target.value)}
          className="focus:ring-2 focus:ring-[#6C5CE7]/20 focus:border-[#6C5CE7]"
        >
          {classes.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
        </Input.Select>

        <Input 
          type="number" 
          min={1} 
          label="Classroom Student Capacity (Optional)" 
          value={capacity} 
          onChange={e => setCapacity(e.target.value)} 
          placeholder="e.g. 40"
          className="focus:ring-2 focus:ring-[#6C5CE7]/20 focus:border-[#6C5CE7]"
        />
      </div>
    </Modal>
  );
}

// ── Main Page Component ───────────────────────────────────────────────────────
export default function SectionsPage() {
  const [years, setYears] = useState([]);
  const [selectedYear, setSelectedYear] = useState(null);
  const [classes, setClasses] = useState([]);
  const [sections, setSections] = useState([]);
  const [loading, setLoading] = useState(true);
  const [editTarget, setEditTarget] = useState(null);
  const [toast, setToast] = useState(null);

  // Creation form state
  const [form, setForm] = useState({ name: '', academic_class_id: '', capacity: '' });
  const [creating, setCreating] = useState(false);

  const notify = (msg, type = 'success') => { setToast({ msg, type }); setTimeout(() => setToast(null), 5000); };

  // Load academic years + class levels on load
  useEffect(() => {
    Promise.all([academicService.getYears(), academicService.getClasses()])
      .then(([yRes, cRes]) => {
        const ys = yRes.data || [];
        const cs = cRes.data || [];
        setYears(ys);
        setClasses(cs);
        const current = ys.find(y => y.is_current) ?? ys[0];
        if (current) setSelectedYear(current.id);
        if (cs.length > 0) setForm(f => ({ ...f, academic_class_id: cs[0].id }));
      })
      .catch(() => notify('Failed to load classes and years.', 'error'));
  }, []);

  // Fetch sections when active academic year changes
  const loadSections = useCallback(async () => {
    if (!selectedYear) return;
    setLoading(true);
    try {
      const res = await academicService.getSections({ year_id: selectedYear });
      setSections(res.data || []);
    } catch {
      notify('Failed to load sections.', 'error');
    } finally {
      setLoading(false);
    }
  }, [selectedYear]);

  useEffect(() => { loadSections(); }, [loadSections]);

  const handleCreate = async (e) => {
    e.preventDefault();
    if (!form.name.trim() || !form.academic_class_id) return;
    setCreating(true);
    try {
      await academicService.createSection({ 
        ...form, 
        academic_year_id: selectedYear, 
        capacity: form.capacity || null 
      });
      setForm(f => ({ ...f, name: '', capacity: '' }));
      notify('Section classroom created successfully.');
      loadSections();
    } catch (err) {
      notify(err.response?.data?.message || 'Failed to create section.', 'error');
    } finally {
      setCreating(false);
    }
  };

  const handleEdit = async (id, data) => {
    try {
      await academicService.updateSection(id, data);
      notify('Section updated successfully.');
      setEditTarget(null);
      loadSections();
    } catch (err) {
      notify(err.response?.data?.message || 'Failed to update section.', 'error');
    }
  };

  const handleDelete = async (id) => {
    const sec = sections.find(s => s.id === id);
    if (!window.confirm(`Delete section "${sec?.display_name}"?`)) return;
    try {
      await academicService.deleteSection(id);
      notify('Section deleted successfully.');
      setSections(prev => prev.filter(s => s.id !== id));
    } catch (err) {
      notify(err.response?.data?.message || 'Cannot delete this section. It may have registered students.', 'error');
    }
  };

  // Group sections by their class name
  const grouped = sections.reduce((acc, s) => {
    const key = s.academic_class?.name ?? 'Unassigned';
    if (!acc[key]) acc[key] = [];
    acc[key].push(s);
    return acc;
  }, {});

  const currentYearLabel = years.find(y => y.id === selectedYear)?.label;

  return (
    <div className="max-w-[1400px] mx-auto px-4 py-8">
      <Toast msg={toast?.msg} type={toast?.type} onClose={() => setToast(null)} />
      
      {editTarget && (
        <EditSectionModal 
          open={!!editTarget}
          section={editTarget} 
          classes={classes} 
          onSave={handleEdit} 
          onClose={() => setEditTarget(null)} 
        />
      )}

      {/* Modern Header Section */}
      <div className="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8 border-b border-black/[0.05] pb-6">
        <div>
          <div className="flex items-center gap-2.5 text-gray-500 mb-1.5">
            <Columns className="w-5 h-5 text-[#6C5CE7]" />
            <span className="text-xs font-bold uppercase tracking-wider">Academic Structure</span>
          </div>
          <h1 className="text-3xl font-extrabold text-gray-900 tracking-tight leading-none">
            Classrooms &amp; Sections
          </h1>
          <p className="text-sm text-gray-500 mt-2 max-w-xl leading-relaxed">
            Manage student sections and classrooms. Each classroom is bound to a grade level and is active for the chosen academic year.
          </p>
        </div>

        {/* Filters and Year Selection */}
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
              to="/classes"
              className="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-900 rounded-xl transition-all"
            >
              <GraduationCap className="w-3.5 h-3.5" />
              Grade Levels
            </Link>
            <Link
              to="/teacher-allocations"
              className="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-900 rounded-xl transition-all"
            >
              <Calendar className="w-3.5 h-3.5" />
              Teacher Allocations
            </Link>
          </div>
        </div>
      </div>

      {/* Redesigned Create Section Form Card */}
      <Card className="mb-8 p-5 bg-gradient-to-br from-white to-gray-50/50">
        <h2 className="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3.5">
          Add New Section Classroom
        </h2>
        <form onSubmit={handleCreate} className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
          <Input.Select 
            label="Class Level"
            value={form.academic_class_id} 
            onChange={e => setForm(f => ({ ...f, academic_class_id: e.target.value }))}
            className="w-full h-11 focus:ring-2 focus:ring-[#6C5CE7]/20 focus:border-[#6C5CE7]"
          >
            <option value="">Select class level…</option>
            {classes.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
          </Input.Select>

          <Input
            label="Section Name"
            value={form.name} 
            onChange={e => setForm(f => ({ ...f, name: e.target.value }))}
            placeholder='e.g. "A", "10A"'
            className="w-full h-11 focus:ring-2 focus:ring-[#6C5CE7]/20 focus:border-[#6C5CE7]"
          />

          <Input
            type="number" 
            min={1} 
            label="Capacity (optional)"
            value={form.capacity} 
            onChange={e => setForm(f => ({ ...f, capacity: e.target.value }))}
            placeholder="e.g. 40"
            className="w-full h-11 focus:ring-2 focus:ring-[#6C5CE7]/20 focus:border-[#6C5CE7]"
          />

          <Button 
            type="submit" 
            disabled={creating || !form.name.trim() || !form.academic_class_id} 
            variant="primary"
            className="h-11 w-full flex items-center justify-center gap-1.5"
          >
            <Plus className="w-4 h-4" />
            {creating ? 'Adding…' : 'Add Section'}
          </Button>
        </form>
      </Card>

      {/* Main Grid View of Grouped Classrooms */}
      {loading ? (
        <Card className="flex flex-col items-center justify-center py-20 gap-4">
          <Loader size="lg" />
          <span className="text-sm font-medium text-gray-500 animate-pulse">Loading sections data...</span>
        </Card>
      ) : Object.keys(grouped).length === 0 ? (
        <Card className="py-16">
          <EmptyState
            title={`No Sections Configured for ${currentYearLabel ?? 'this year'}`}
            description="To structure student registries, assign new sections within grade levels using the creator card above."
          />
        </Card>
      ) : (
        <div className="space-y-8">
          {Object.entries(grouped).map(([className, secs]) => (
            <div key={className} className="space-y-4">
              <div className="flex items-center gap-2">
                <span className="w-1.5 h-4 rounded-full bg-[#6C5CE7]" />
                <h2 className="text-sm font-bold text-gray-800 uppercase tracking-widest">
                  {className}
                </h2>
                <span className="text-xs bg-gray-100 text-gray-500 font-semibold px-2 py-0.5 rounded-full">
                  {secs.length} {secs.length === 1 ? 'room' : 'rooms'}
                </span>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                {secs.map(sec => (
                  <Card 
                    key={sec.id} 
                    padding={false}
                    className="group border border-black/[0.06] hover:border-[#6C5CE7]/30 hover:shadow-[0_4px_20px_-4px_rgba(108,92,231,0.08)] transition-all duration-200"
                  >
                    <div className="p-5">
                      <div className="flex justify-between items-start mb-2.5">
                        <span className="text-xl font-extrabold text-gray-900 group-hover:text-[#6C5CE7] transition-colors duration-150">
                          {sec.name}
                        </span>
                        {sec.is_full === true && (
                          <Badge variant="danger" size="sm" dot>
                            Full
                          </Badge>
                        )}
                      </div>
                      
                      {/* Section details */}
                      <div className="flex items-center gap-1.5 text-sm text-gray-500 mb-4">
                        <Users className="w-4 h-4 text-gray-400" />
                        <span className="font-semibold text-gray-700">
                          {sec.students_count ?? 0}
                        </span>
                        <span>/</span>
                        <span>{sec.capacity ?? '∞'} Students</span>
                      </div>

                      {/* Card Actions */}
                      <div className="flex gap-2 border-t border-black/[0.04] pt-3.5">
                        <Button 
                          onClick={() => setEditTarget(sec)} 
                          variant="secondary" 
                          size="sm" 
                          className="flex-1 py-1.5 text-xs rounded-xl"
                        >
                          <Edit2 className="w-3 h-3 mr-1 text-gray-500" />
                          Edit
                        </Button>
                        <Button 
                          onClick={() => handleDelete(sec.id)} 
                          variant="secondary" 
                          size="sm"
                          className="flex-1 py-1.5 text-xs rounded-xl text-red-600 hover:text-white hover:bg-red-600 hover:border-red-600"
                        >
                          <Trash2 className="w-3 h-3 mr-1" />
                          Delete
                        </Button>
                      </div>
                    </div>
                  </Card>
                ))}
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
