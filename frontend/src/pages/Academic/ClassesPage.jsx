/**
 * ============================================================================
 * pages/Academic/ClassesPage.jsx — Academic Classes (Grade Levels) Management
 * ============================================================================
 * Admin-only page for managing academic classes (e.g. "Class 10", "Class 9").
 * Redesigned with premium Tailwind CSS layout, animations, and icons.
 * ============================================================================
 */

import { useState, useEffect, useCallback } from 'react';
import { Link } from 'react-router-dom';
import academicService from '@/services/academicService';
import { Card, Button, Input, Loader, EmptyState, Badge } from '@/components/ui';
import { GraduationCap, Plus, Edit2, Trash2, Save, X, Calendar, Columns } from 'lucide-react';
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

// ── Class Row ────────────────────────────────────────────────────────────────
function ClassRow({ cls, onRename, onDelete }) {
  const [editing, setEditing] = useState(false);
  const [name, setName] = useState(cls.name);
  const [saving, setSaving] = useState(false);

  const save = async () => {
    if (!name.trim() || name === cls.name) { setEditing(false); return; }
    setSaving(true);
    await onRename(cls.id, name.trim());
    setSaving(false);
    setEditing(false);
  };

  return (
    <tr className="border-b border-black/[0.04] hover:bg-gray-50/50 transition-colors duration-150">
      <td className="px-6 py-4.5 w-full">
        {editing ? (
          <div className="flex items-center gap-2 max-w-md">
            <Input
              autoFocus
              value={name}
              onChange={e => setName(e.target.value)}
              onKeyDown={e => { 
                if (e.key === 'Enter') save(); 
                if (e.key === 'Escape') { setName(cls.name); setEditing(false); } 
              }}
              className="py-1 px-3 text-sm focus:ring-2 focus:ring-[#6C5CE7]/20 focus:border-[#6C5CE7]"
              placeholder="Class name"
            />
          </div>
        ) : (
          <div className="group flex items-center gap-2">
            <span
              className="text-sm font-semibold text-gray-900 cursor-text select-all"
              onDoubleClick={() => setEditing(true)}
              title="Double-click to rename"
            >
              {cls.name}
            </span>
            <button 
              onClick={() => setEditing(true)}
              className="opacity-0 group-hover:opacity-100 p-1 text-gray-400 hover:text-[#6C5CE7] hover:bg-[#6C5CE7]/5 rounded-lg transition-all duration-150"
              title="Rename class"
            >
              <Edit2 className="w-3.5 h-3.5" />
            </button>
          </div>
        )}
      </td>
      <td className="px-6 py-4.5 whitespace-nowrap">
        <Badge variant={cls.classrooms_count > 0 ? "primary" : "neutral"} dot={cls.classrooms_count > 0}>
          {cls.classrooms_count ?? 0} {cls.classrooms_count === 1 ? 'Section' : 'Sections'}
        </Badge>
      </td>
      <td className="px-6 py-4.5 whitespace-nowrap text-right">
        <div className="flex items-center justify-end gap-2.5">
          {editing ? (
            <>
              <Button 
                onClick={save} 
                loading={saving}
                variant="primary" 
                size="sm"
                className="shadow-sm"
              >
                <Save className="w-3.5 h-3.5 mr-1" />
                Save
              </Button>
              <Button 
                onClick={() => { setName(cls.name); setEditing(false); }} 
                variant="secondary" 
                size="sm"
              >
                <X className="w-3.5 h-3.5 mr-1" />
                Cancel
              </Button>
            </>
          ) : (
            <>
              <Link 
                to={`/classrooms?class_id=${cls.id}`} 
                className="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-[#6C5CE7] bg-[#6C5CE7]/8 hover:bg-[#6C5CE7]/15 rounded-xl transition-all duration-200"
              >
                <Columns className="w-3.5 h-3.5" />
                Sections
              </Link>
              <Button 
                onClick={() => onDelete(cls.id)} 
                variant="secondary" 
                size="sm"
                className="text-red-600 hover:text-white hover:bg-red-600 hover:border-red-600"
                title={cls.classrooms_count > 0 ? 'Remove all sections first' : 'Delete class'}
              >
                <Trash2 className="w-3.5 h-3.5" />
              </Button>
            </>
          )}
        </div>
      </td>
    </tr>
  );
}

// ── Main Classes Page Component ──────────────────────────────────────────────
export default function ClassesPage() {
  const [classes, setClasses] = useState([]);
  const [loading, setLoading] = useState(true);
  const [newName, setNewName] = useState('');
  const [creating, setCreating] = useState(false);
  const [toast, setToast] = useState(null);

  const notify = (msg, type = 'success') => {
    setToast({ msg, type });
    setTimeout(() => setToast(null), 5000);
  };

  const load = useCallback(async () => {
    setLoading(true);
    try {
      const res = await academicService.getClasses();
      setClasses(res.data || []);
    } catch {
      notify('Failed to load classes.', 'error');
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => { load(); }, [load]);

  const handleCreate = async (e) => {
    e.preventDefault();
    if (!newName.trim()) return;
    setCreating(true);
    try {
      await academicService.createClass({ name: newName.trim() });
      setNewName('');
      notify('Class grade level created successfully.');
      load();
    } catch (err) {
      notify(err.response?.data?.message || 'Failed to create class.', 'error');
    } finally {
      setCreating(false);
    }
  };

  const handleRename = async (id, name) => {
    try {
      await academicService.updateClass(id, { name });
      notify('Class renamed successfully.');
      setClasses(prev => prev.map(c => c.id === id ? { ...c, name } : c));
    } catch (err) {
      notify(err.response?.data?.message || 'Failed to rename class.', 'error');
    }
  };

  const handleDelete = async (id) => {
    const cls = classes.find(c => c.id === id);
    if (cls?.classrooms_count > 0) {
      notify(`"${cls.name}" has active sections. Please remove all sections first.`, 'error');
      return;
    }
    if (!window.confirm(`Delete "${cls?.name}"? This action cannot be undone.`)) return;
    try {
      await academicService.deleteClass(id);
      notify('Class deleted successfully.');
      setClasses(prev => prev.filter(c => c.id !== id));
    } catch (err) {
      notify(err.response?.data?.message || 'Failed to delete class.', 'error');
    }
  };

  return (
    <div className="max-w-[1400px] mx-auto px-4 py-8">
      <Toast msg={toast?.msg} type={toast?.type} onClose={() => setToast(null)} />

      {/* Modern Header Section */}
      <div className="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8 border-b border-black/[0.05] pb-6">
        <div>
          <div className="flex items-center gap-2.5 text-gray-500 mb-1.5">
            <GraduationCap className="w-5 h-5 text-[#6C5CE7]" />
            <span className="text-xs font-bold uppercase tracking-wider">Academic Structure</span>
          </div>
          <h1 className="text-3xl font-extrabold text-gray-900 tracking-tight leading-none">
            Grade Levels &amp; Classes
          </h1>
          <p className="text-sm text-gray-500 mt-2 max-w-xl leading-relaxed">
            Configure school-wide grade levels (e.g., Class 8, Class 10). Academic year specific classrooms and sections are mapped within these grade levels.
          </p>
        </div>

        {/* Pill Navigation */}
        <div className="flex flex-wrap gap-2.5 bg-gray-100/80 p-1.5 rounded-2xl border border-black/[0.04] self-start md:self-auto">
          <Link
            to="/classrooms"
            className="flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-900 rounded-xl transition-all"
          >
            <Columns className="w-3.5 h-3.5" />
            Classrooms &amp; Sections
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

      {/* Modern Create Form Card */}
      <Card className="mb-8 p-5 bg-gradient-to-br from-white to-gray-50/50">
        <form onSubmit={handleCreate} className="flex flex-col sm:flex-row gap-4 items-end sm:items-center">
          <div className="flex-1 w-full">
            <Input
              value={newName}
              onChange={e => setNewName(e.target.value)}
              placeholder='Enter grade level name, e.g. "Class 10"'
              label="Create New Class Level"
              className="w-full h-11 focus:ring-2 focus:ring-[#6C5CE7]/20 focus:border-[#6C5CE7]"
            />
          </div>
          <Button 
            type="submit" 
            disabled={creating || !newName.trim()}
            variant="primary"
            className="h-11 px-6 w-full sm:w-auto flex-shrink-0"
          >
            <Plus className="w-4 h-4 mr-1.5" />
            {creating ? 'Adding…' : 'Add Class'}
          </Button>
        </form>
      </Card>

      {/* Main Content Card Wrapper */}
      <Card padding={false} className="overflow-hidden shadow-[0_4px_20px_-2px_rgba(0,0,0,0.03)]">
        {loading ? (
          <div className="flex flex-col items-center justify-center py-20 gap-4">
            <Loader size="lg" />
            <span className="text-sm font-medium text-gray-500 animate-pulse">Loading classes...</span>
          </div>
        ) : classes.length === 0 ? (
          <div className="py-16">
            <EmptyState
              title="No Classes Setup Yet"
              description="Begin by adding grade levels (like Class 8, Class 9, or Class 10) in the input card above."
            />
          </div>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="bg-gray-50/80 border-b border-black/[0.06]">
                  <th className="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Class Level</th>
                  <th className="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Assigned Sections</th>
                  <th className="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-black/[0.04]">
                {classes.map(cls => (
                  <ClassRow key={cls.id} cls={cls} onRename={handleRename} onDelete={handleDelete} />
                ))}
              </tbody>
            </table>
          </div>
        )}
      </Card>

      {/* Helper Footer Note */}
      <p className="mt-4 text-center text-xs text-gray-400">
        💡 Pro-tip: You can double-click on any class name to rename it inline.
      </p>
    </div>
  );
}
