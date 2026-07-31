/**
 * ============================================================================
 * services/quizService.js — Quiz API Calls
 * ============================================================================
 * Maps to:
 *   Route::resource('quizzes', QuizController)
 *   QuizAssignmentController, QuizAttemptController
 * ============================================================================
 */

import api from './api';

const quizService = {
  // ── Quiz CRUD (Teacher/Admin) ──────────────────────────────────────────────
  getAll:  (params = {}) => api.get('/api/quizzes', { params }),
  getById: (id) => api.get(`/api/quizzes/${id}`),
  create:  (data) => api.post('/api/quizzes', data),
  update:  (id, data) => api.put(`/api/quizzes/${id}`, data),
  delete:  (id) => api.delete(`/api/quizzes/${id}`),

  // ── Assignment ─────────────────────────────────────────────────────────────
  /** GET /quizzes/:id/assign */
  getAssignForm: (quizId) => api.get(`/api/quizzes/${quizId}/assign`),

  /** POST /quizzes/:id/assign */
  assign: (quizId, data) => api.post(`/api/quizzes/${quizId}/assign`, data),

  /** GET /quiz-assignments/:id/analytics */
  getAnalytics: (assignmentId) => api.get(`/api/quiz-assignments/${assignmentId}/analytics`),

  // ── Student Attempt ────────────────────────────────────────────────────────
  /** GET /quiz/:assignmentId/start — start or resume */
  start: (assignmentId) => api.get(`/api/quiz/${assignmentId}/start`),

  /** GET /quiz/attempt/:attemptId */
  getAttempt: (attemptId) => api.get(`/api/quiz/attempt/${attemptId}`),

  /** POST /quiz/attempt/:attemptId/submit */
  submitAttempt: (attemptId, data) => api.post(`/api/quiz/attempt/${attemptId}/submit`, data),

  /** GET /quiz/attempt/:attemptId/results */
  getResults: (attemptId) => api.get(`/api/quiz/attempt/${attemptId}/results`),
};

export default quizService;
