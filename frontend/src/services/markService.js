/**
 * ============================================================================
 * services/markService.js — Marks API Service
 * ============================================================================
 * Maps to backend MarkController routes.
 * ============================================================================
 */

import api from './api';

const markService = {
  /** GET /marks — index (expects JSON) */
  getAll: (params = {}) => api.get('/marks', { params }),

  /** GET /marks/create — create options (students & subjects) */
  getCreateOptions: () => api.get('/marks/create'),

  /** GET /marks/:id/edit — edit mark details */
  getById: (id) => api.get(`/marks/${id}/edit`),

  /** POST /marks — store new mark */
  create: (data) => api.post('/marks', data),

  /** PUT /marks/:id — update mark */
  update: (id, data) => api.put(`/marks/${id}`, data),

  /** DELETE /marks/:id — destroy mark */
  delete: (id) => api.delete(`/marks/${id}`),
};

export default markService;
