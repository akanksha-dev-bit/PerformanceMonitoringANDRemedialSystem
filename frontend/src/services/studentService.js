/**
 * ============================================================================
 * services/studentService.js — Student API Service
 * ============================================================================
 * Maps to backend StudentController and LookupController routes.
 * ============================================================================
 */

import api from './api';

const studentService = {
  /** GET /students — index (expects JSON) */
  getStudents: (params = {}) => api.get('/students', { params }),
  
  /** GET /students/:id/edit — edit (expects JSON) */
  getStudent: (id) => api.get(`/students/${id}/edit`),

  /** GET /students/:id — show (expects JSON) */
  getStudentProfile: (id) => api.get(`/students/${id}`),

  /** POST /students — store (expects JSON) */
  createStudent: (data) => api.post('/students', data),

  /** PUT /students/:id — update (expects JSON) */
  updateStudent: (id, data) => api.put(`/students/${id}`, data),

  /** DELETE /students/:id — destroy (expects JSON) */
  deleteStudent: (id) => api.delete(`/students/${id}`),

  /** GET /lookups/classes — lookup classrooms */
  lookupClasses: () => api.get('/lookups/classes'),

  /** GET /lookups/teachers — lookup teachers */
  lookupTeachers: () => api.get('/lookups/teachers'),

  /** GET /lookups/subjects — lookup subjects */
  lookupSubjects: () => api.get('/lookups/subjects'),
};

export default studentService;
