/**
 * ============================================================================
 * services/authService.js — Authentication API Calls
 * ============================================================================
 * Handles all auth-related API communication.
 * NOTE: Login/Register pages remain as Blade for Phase 1.
 *       This service is ready for Phase 2 when auth is migrated to React.
 * ============================================================================
 */

import api from './api';

const authService = {
  /**
   * Get the currently authenticated user.
   * Returns null if unauthenticated.
   */
  getUser: () => api.get('/api/user'),

  /**
   * Log the user out.
   * Clears Laravel session.
   */
  logout: () => api.post('/logout'),

  /**
   * Login (Phase 2+).
   * @param {{ email: string, password: string, remember?: boolean }} credentials
   */
  login: (credentials) => api.post('/login', credentials),

  /**
   * Register a new user (Phase 2+).
   */
  register: (data) => api.post('/register', data),

  /**
   * Send password reset link.
   */
  forgotPassword: (email) => api.post('/forgot-password', { email }),

  /**
   * Reset password with token.
   */
  resetPassword: (data) => api.post('/reset-password', data),
};

export default authService;
