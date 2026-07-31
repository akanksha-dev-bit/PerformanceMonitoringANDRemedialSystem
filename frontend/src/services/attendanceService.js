/**
 * ============================================================================
 * services/attendanceService.js — Attendance API Service
 * ============================================================================
 * Maps to backend Teacher/AttendanceController routes.
 * ============================================================================
 */

import api from './api';

const attendanceService = {
  /** GET /attendance — get today's schedule slots & completion keys */
  getTodaySchedule: (params = {}) => api.get('/attendance', { params }),

  /** GET /attendance/mark/:timetableId — get marking sheet data */
  getMarkingSheet: (timetableId) => api.get(`/attendance/mark/${timetableId}`),

  /** POST /attendance/store/:timetableId — store attendance records */
  saveAttendance: (timetableId, data) => api.post(`/attendance/store/${timetableId}`, data),
};

export default attendanceService;
