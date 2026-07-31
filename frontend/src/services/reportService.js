/**
 * ============================================================================
 * services/reportService.js — Report API Calls
 * ============================================================================
 * Maps to Laravel: GET /reports → ReportController@index
 * ============================================================================
 */

import api from './api';

const reportService = {
  /** GET /reports — full report data */
  getAll: (params = {}) => api.get('/api/reports', { params }),

  /** GET /analytics/attendance — admin attendance analytics */
  getAttendanceAnalytics: (params = {}) => api.get('/api/analytics/attendance', { params }),
};

export default reportService;
