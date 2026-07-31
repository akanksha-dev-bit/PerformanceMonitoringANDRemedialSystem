/**
 * ============================================================================
 * constants/routes.js — Centralized Route Paths
 * ============================================================================
 * All React Router paths in one place.
 * Import ROUTES.DASHBOARD instead of hard-coding '/dashboard/admin'.
 * ============================================================================
 */

export const ROUTES = {
  // ── Auth (Laravel Blade) ──────────────────────────────────────────────────
  // These are served by Laravel, not React Router.
  // Keep here for reference when building redirect links.
  LOGIN:           '/login',
  REGISTER:        '/register',
  FORGOT_PASSWORD: '/forgot-password',
  VERIFY_EMAIL:    '/verify-email',

  // ── Dashboards ────────────────────────────────────────────────────────────
  DASHBOARD:         '/dashboard',
  DASHBOARD_ADMIN:   '/dashboard/admin',
  DASHBOARD_TEACHER: '/dashboard/teacher',
  DASHBOARD_STUDENT: '/dashboard/student',
  MY_PROGRESS:       '/my-progress',
  MY_TASKS:          '/my-tasks',

  // ── Students ──────────────────────────────────────────────────────────────
  STUDENTS:                '/students',
  STUDENT_CREATE:          '/students/create',
  STUDENT_SHOW:            (id) => `/students/${id}`,
  STUDENT_EDIT:            (id) => `/students/${id}/edit`,
  COMPLETE_PROFILE:        '/complete-profile',

  // ── Teachers ──────────────────────────────────────────────────────────────
  TEACHERS:       '/teachers',
  TEACHER_CREATE: '/teachers/create',
  TEACHER_EDIT:   (id) => `/teachers/${id}/edit`,

  // ── Subjects ──────────────────────────────────────────────────────────────
  SUBJECTS:       '/subjects',
  SUBJECT_CREATE: '/subjects/create',
  SUBJECT_EDIT:   (id) => `/subjects/${id}/edit`,

  // ── Academic Classes ──────────────────────────────────────────────────────
  CLASSES:    '/classes',
  CLASSROOMS: '/classrooms',

  // ── Allocations & Timetable ───────────────────────────────────────────────
  ALLOCATIONS: '/teacher-allocations',
  TIMETABLE:   '/timetables',

  // ── Marks ─────────────────────────────────────────────────────────────────
  MARKS:        '/marks',
  MARKS_CREATE: '/marks/create',

  // ── Performance ───────────────────────────────────────────────────────────
  PERFORMANCE:              '/performance',
  PERFORMANCE_STUDENT:      (id) => `/performance/student/${id}`,
  PERFORMANCE_SLOW_LEARNERS: '/performance/slow-learners',

  // ── Remedial ──────────────────────────────────────────────────────────────
  REMEDIAL:              '/remedial',
  REMEDIAL_CREATE:       '/remedial/create',
  REMEDIAL_EDIT:         (id) => `/remedial/${id}/edit`,
  REMEDIAL_SUBMISSIONS:  (id) => `/remedial/${id}/submissions`,
  REMEDIAL_WORKSPACE:    (id) => `/remedial/${id}/workspace`,
  REMEDIAL_REVIEW:       (id) => `/remedial-submissions/${id}/review`,

  // ── Attendance ────────────────────────────────────────────────────────────
  ATTENDANCE:         '/attendance',
  ATTENDANCE_MARK:    (id) => `/attendance/mark/${id}`,
  MY_ATTENDANCE:      '/my-attendance',

  // ── Quizzes ───────────────────────────────────────────────────────────────
  QUIZZES:          '/quizzes',
  QUIZ_CREATE:      '/quizzes/create',
  QUIZ_SHOW:        (id) => `/quizzes/${id}`,
  QUIZ_EDIT:        (id) => `/quizzes/${id}/edit`,
  QUIZ_ASSIGN:      (id) => `/quizzes/${id}/assign`,
  QUIZ_ANALYTICS:   (id) => `/quiz-assignments/${id}/analytics`,
  QUIZ_ATTEMPT:     (id) => `/quiz/attempt/${id}`,
  QUIZ_RESULTS:     (id) => `/quiz/attempt/${id}/results`,

  // ── Admin Analytics ───────────────────────────────────────────────────────
  ADMIN_ATTENDANCE_ANALYTICS: '/analytics/attendance',

  // ── Reports ───────────────────────────────────────────────────────────────
  REPORTS: '/reports',

  // ── Profile ───────────────────────────────────────────────────────────────
  PROFILE: '/profile',

  // ── Misc ──────────────────────────────────────────────────────────────────
  JOIN: (code) => `/join/${code}`,
};
