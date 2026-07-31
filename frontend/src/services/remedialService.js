/**
 * ============================================================================
 * services/remedialService.js — Remedial System API Calls
 * ============================================================================
 * Maps to:
 *   Route::resource('remedial', RemedialController)
 *   Route::resource('remedial-submissions', RemedialSubmissionController)
 * ============================================================================
 */

import api from './api';

const remedialService = {
  // ── Teacher CRUD ───────────────────────────────────────────────────────────
  getAll:    (params = {}) => api.get('/api/remedial', { params }),
  getById:   (id)  => api.get(`/api/remedial/${id}`),
  create:    (data) => api.post('/api/remedial', data),
  update:    (id, data) => api.put(`/api/remedial/${id}`, data),
  delete:    (id)  => api.delete(`/api/remedial/${id}`),

  /** GET /remedial/:id/submissions — all submissions for a remedial task */
  getSubmissions: (remedialId) => api.get(`/api/remedial/${remedialId}/submissions`),

  // ── Teacher Review ─────────────────────────────────────────────────────────
  /** GET /remedial-submissions/:id/review */
  getSubmissionReview: (submissionId) => api.get(`/api/remedial-submissions/${submissionId}/review`),

  /** POST /remedial-submissions/:id/grade */
  grade: (submissionId, data) => api.post(`/api/remedial-submissions/${submissionId}/grade`, data),

  /** POST /remedial-submissions/:id/reopen */
  reopen: (submissionId) => api.post(`/api/remedial-submissions/${submissionId}/reopen`),

  // ── Student Submission ─────────────────────────────────────────────────────
  /** GET /remedial/:id/workspace */
  getWorkspace: (remedialId) => api.get(`/api/remedial/${remedialId}/workspace`),

  /** POST /remedial/:id/draft */
  saveDraft: (remedialId, data) => api.post(`/api/remedial/${remedialId}/draft`, data),

  /** POST /remedial/:id/submit */
  submit: (remedialId, data) => api.post(`/api/remedial/${remedialId}/submit`, data),

  /** POST /remedial/:id/upload */
  uploadFile: (remedialId, formData) =>
    api.post(`/api/remedial/${remedialId}/upload`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),
};

export default remedialService;
