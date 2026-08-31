/**
 * ============================================================================
 * services/dashboardService.js — Admin Dashboard API Service
 * ============================================================================
 * Handles fetching aggregated dashboard stats for the Admin overview page.
 * ============================================================================
 */

import api from './api';

const dashboardService = {
  /** GET /dashboard/admin (expects JSON) */
  getAdminDashboard: () => api.get('/dashboard/admin'),
};

export default dashboardService;
