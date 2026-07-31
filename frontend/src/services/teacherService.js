/**
 * ============================================================================
 * services/teacherService.js — Teacher API Service
 * ============================================================================
 * Maps to backend TeacherController routes.
 * ============================================================================
 */

import api from './api';

const teacherService = {
  /** GET /teachers — index (expects JSON) */
  getTeachers: (params = {}) => api.get('/teachers', { params }),
  
  /** GET /teachers/:id/edit — edit (expects JSON) */
  getTeacher: (id) => api.get(`/teachers/${id}/edit`),

  /** POST /teachers — store (expects JSON) */
  createTeacher: (data) => api.post('/teachers', data),

  /** PUT /teachers/:id — update (expects JSON) */
  updateTeacher: (id, data) => api.put(`/teachers/${id}`, data),

  /** DELETE /teachers/:id — destroy (expects JSON) */
  deleteTeacher: (id) => api.delete(`/teachers/${id}`),
};

export default teacherService;
