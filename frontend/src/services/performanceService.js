/**
 * ============================================================================
 * services/performanceService.js — Performance API Calls
 * ============================================================================
 * Maps to:
 *   GET /performance            → PerformanceController@index
 *   GET /performance/student/:id → PerformanceController@show
 *   GET /performance/slow-learners → PerformanceController@slowLearners
 * ============================================================================
 */

import api from './api';

const performanceService = {
  /** GET /performance — overview of all student performance */
  getAll: (params = {}) => api.get('/api/performance', { params }),

  /** GET /performance/student/:studentId */
  getByStudent: (studentId) => api.get(`/api/performance/student/${studentId}`),

  /** GET /performance/slow-learners */
  getSlowLearners: () => api.get('/api/performance/slow-learners'),
};

export default performanceService;
