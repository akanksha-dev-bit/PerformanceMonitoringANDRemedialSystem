/**
 * ============================================================================
 * services/subjectService.js — Subject API Service
 * ============================================================================
 * Maps to backend SubjectController routes.
 * ============================================================================
 */

import api from './api';

const subjectService = {
  /** GET /subjects — index (expects JSON) */
  getSubjects: (params = {}) => api.get('/subjects', { params }),
  
  /** GET /subjects/:id/edit — edit (expects JSON) */
  getSubject: (id) => api.get(`/subjects/${id}/edit`),

  /** POST /subjects — store (expects JSON) */
  createSubject: (data) => api.post('/subjects', data),

  /** PUT /subjects/:id — update (expects JSON) */
  updateSubject: (id, data) => api.put(`/subjects/${id}`, data),

  /** DELETE /subjects/:id — destroy (expects JSON) */
  deleteSubject: (id) => api.delete(`/subjects/${id}`),
};

export default subjectService;
