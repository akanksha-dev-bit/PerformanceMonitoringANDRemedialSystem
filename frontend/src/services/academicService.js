/**
 * ============================================================================
 * services/academicService.js — Academic Structure API Service
 * ============================================================================
 * Calls the new JSON API routes added for the academic structure redesign:
 *   /api/academic-years  → Academic years (CRUD + set-current)
 *   /api/classes         → Grade levels (CRUD)
 *   /api/sections        → Sections / classrooms (CRUD + filters)
 *   /api/allocations     → Teacher-subject-section assignments (CRUD)
 *   /api/timetable-slots → Timetable entries (CRUD + filters)
 *
 * All routes are admin-only and session-authenticated (same as the rest of
 * the app — no separate token needed).
 * ============================================================================
 */

import api from './api';

const BASE = '/api'; // routes are registered under /api prefix

const academicService = {
  // ── Academic Years ─────────────────────────────────────────────────────────

  /** GET /api/academic-years */
  getYears: () => api.get(`${BASE}/academic-years`),

  /** POST /api/academic-years */
  createYear: (data) => api.post(`${BASE}/academic-years`, data),

  /** PUT /api/academic-years/:id */
  updateYear: (id, data) => api.put(`${BASE}/academic-years/${id}`, data),

  /** DELETE /api/academic-years/:id */
  deleteYear: (id) => api.delete(`${BASE}/academic-years/${id}`),

  /** POST /api/academic-years/:id/set-current */
  setCurrentYear: (id) => api.post(`${BASE}/academic-years/${id}/set-current`),

  // ── Academic Classes ───────────────────────────────────────────────────────

  /** GET /api/classes */
  getClasses: () => api.get(`${BASE}/classes`),

  /** POST /api/classes */
  createClass: (data) => api.post(`${BASE}/classes`, data),

  /** PUT /api/classes/:id */
  updateClass: (id, data) => api.put(`${BASE}/classes/${id}`, data),

  /** DELETE /api/classes/:id */
  deleteClass: (id) => api.delete(`${BASE}/classes/${id}`),

  // ── Sections ───────────────────────────────────────────────────────────────

  /**
   * GET /api/sections
   * @param {Object} params - Optional: { year_id, class_id }
   */
  getSections: (params = {}) => api.get(`${BASE}/sections`, { params }),

  /** GET /api/sections/:id */
  getSection: (id) => api.get(`${BASE}/sections/${id}`),

  /** POST /api/sections */
  createSection: (data) => api.post(`${BASE}/sections`, data),

  /** PUT /api/sections/:id */
  updateSection: (id, data) => api.put(`${BASE}/sections/${id}`, data),

  /** DELETE /api/sections/:id */
  deleteSection: (id) => api.delete(`${BASE}/sections/${id}`),

  // ── Allocations ────────────────────────────────────────────────────────────

  /**
   * GET /api/allocations
   * @param {Object} params - Optional: { year_id, section_id, teacher_id }
   */
  getAllocations: (params = {}) => api.get(`${BASE}/allocations`, { params }),

  /** POST /api/allocations */
  createAllocation: (data) => api.post(`${BASE}/allocations`, data),

  /** DELETE /api/allocations/:id */
  deleteAllocation: (id) => api.delete(`${BASE}/allocations/${id}`),

  // ── Timetable Slots ────────────────────────────────────────────────────────

  /**
   * GET /api/timetable-slots
   * @param {Object} params - Optional: { section_id, year_id, day, teacher_id }
   */
  getTimetableSlots: (params = {}) => api.get(`${BASE}/timetable-slots`, { params }),

  /** POST /api/timetable-slots */
  createTimetableSlot: (data) => api.post(`${BASE}/timetable-slots`, data),

  /** DELETE /api/timetable-slots/:id */
  deleteTimetableSlot: (id) => api.delete(`${BASE}/timetable-slots/${id}`),
};

export default academicService;
