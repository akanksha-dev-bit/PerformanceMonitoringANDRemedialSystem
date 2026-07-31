/**
 * ============================================================================
 * router/index.jsx — Application Router
 * ============================================================================
 * Defines the complete route tree for the PMRS SPA.
 *
 * Current state (Phase 1):
 *   - All dashboard routes show a placeholder "Coming Soon" page
 *   - Auth pages remain on Laravel Blade (not in React Router)
 *
 * Phase 2+:
 *   - Uncomment and import the real page components as they are migrated
 *   - Replace the placeholder <DashboardPage> stubs with real pages
 *
 * Route Structure:
 *   /                         → Landing Page (existing, untouched)
 *   /dashboard/*              → Protected (AppLayout)
 *   /students/*               → Protected (AppLayout)
 *   ... (all app routes)
 *   *                         → 404 NotFoundPage
 * ============================================================================
 */

import { lazy, Suspense } from 'react';
import { Routes, Route, Navigate } from 'react-router-dom';

import AppLayout     from '@/layouts/AppLayout';
import GuestLayout   from '@/layouts/GuestLayout';
import ProtectedRoute from './ProtectedRoute';
import Loader        from '@/components/ui/Loader';
import { ROLES }     from '@/constants/roles';

// ── Page Imports (lazy-loaded) ────────────────────────────────────────────────
// Phase 1: only stub pages
const DashboardPage = lazy(() => import('@/pages/dashboard/DashboardPage'));
const NotFoundPage  = lazy(() => import('@/pages/NotFoundPage'));
const LandingPage   = lazy(() => import('@/pages/LandingPage'));
const SubjectsPage  = lazy(() => import('@/pages/Subjects/SubjectsPage'));
const SubjectCreatePage = lazy(() => import('@/pages/Subjects/SubjectCreatePage'));
const SubjectEditPage   = lazy(() => import('@/pages/Subjects/SubjectEditPage'));
const TeachersPage  = lazy(() => import('@/pages/Teachers/TeachersPage'));
const TeacherCreatePage = lazy(() => import('@/pages/Teachers/TeacherCreatePage'));
const TeacherEditPage   = lazy(() => import('@/pages/Teachers/TeacherEditPage'));
const StudentsPage  = lazy(() => import('@/pages/Students/StudentsPage'));
const StudentCreatePage = lazy(() => import('@/pages/Students/StudentCreatePage'));
const StudentEditPage   = lazy(() => import('@/pages/Students/StudentEditPage'));
const StudentDetailPage = lazy(() => import('@/pages/Students/StudentDetailPage'));
const AttendancePage  = lazy(() => import('@/pages/Attendance/AttendancePage'));
const StudentAttendanceMarkPage = lazy(() => import('@/pages/Attendance/StudentAttendanceMarkPage'));
const AttendanceEditPage = lazy(() => import('@/pages/Attendance/AttendanceEditPage'));
const MarksPage      = lazy(() => import('@/pages/Marks/MarksPage'));
const MarksEntryPage = lazy(() => import('@/pages/Marks/MarksEntryPage'));
const MarksEditPage  = lazy(() => import('@/pages/Marks/MarksEditPage'));

// ── Loading fallback (Lottie loader) ─────────────────────────────────────────
const PageLoader = () => <Loader visible />;

function AppRouter() {
  return (
    <Suspense fallback={<PageLoader />}>
      <Routes>

        {/* ── Public Landing Page ─────────────────────────────────────────── */}
        <Route path="/" element={<LandingPage />} />

        {/* ── Protected App Routes ───────────────────────────────────────── */}
        <Route
          element={
            <ProtectedRoute>
              <AppLayout />
            </ProtectedRoute>
          }
        >
          {/* Dashboard */}
          <Route path="/dashboard"         element={<DashboardPage />} />
          <Route path="/dashboard/admin"   element={<DashboardPage role="admin"   />} />
          <Route path="/dashboard/teacher" element={<DashboardPage role="teacher" />} />
          <Route path="/dashboard/student" element={<DashboardPage role="student" />} />

          <Route path="/students"            element={<StudentsPage />} />
          <Route path="/students/create"     element={<StudentCreatePage />} />
          <Route path="/students/:id"        element={<StudentDetailPage />} />
          <Route path="/students/:id/edit"   element={<StudentEditPage />} />

          <Route path="/teachers"            element={<TeachersPage />} />
          <Route path="/teachers/create"     element={<TeacherCreatePage />} />
          <Route path="/teachers/:id/edit"   element={<TeacherEditPage />} />

          <Route path="/subjects"            element={<SubjectsPage />} />
          <Route path="/subjects/create"     element={<SubjectCreatePage />} />
          <Route path="/subjects/:id/edit"   element={<SubjectEditPage />} />

          <Route path="/classes"             element={<DashboardPage stub="Academic Classes" />} />
          <Route path="/classrooms"          element={<DashboardPage stub="Classrooms" />} />
          <Route path="/teacher-allocations" element={<DashboardPage stub="Teacher Allocations" />} />
          <Route path="/timetables"          element={<DashboardPage stub="Timetable" />} />

          <Route path="/marks"               element={<MarksPage />} />
          <Route path="/marks/create"        element={<MarksEntryPage />} />
          <Route path="/marks/entry/:id"     element={<MarksEntryPage />} />
          <Route path="/marks/:id/edit"      element={<MarksEditPage />} />

          <Route path="/performance"         element={<DashboardPage stub="Performance" />} />
          <Route path="/performance/student/:id" element={<DashboardPage stub="Student Performance" />} />
          <Route path="/performance/slow-learners" element={<DashboardPage stub="Slow Learners" />} />

          <Route path="/remedial"            element={<DashboardPage stub="Remedial" />} />
          <Route path="/remedial/create"     element={<DashboardPage stub="Create Remedial Task" />} />
          <Route path="/remedial/:id"        element={<DashboardPage stub="Remedial Detail" />} />
          <Route path="/remedial/:id/edit"   element={<DashboardPage stub="Edit Remedial Task" />} />
          <Route path="/remedial/:id/submissions" element={<DashboardPage stub="Remedial Submissions" />} />
          <Route path="/remedial/:id/workspace"   element={<DashboardPage stub="Remedial Workspace" />} />

          <Route path="/attendance"                  element={<AttendancePage />} />
          <Route path="/attendance/mark/:timetableId" element={<StudentAttendanceMarkPage />} />
          <Route path="/attendance/:id/edit"         element={<AttendanceEditPage />} />
          <Route path="/my-attendance"               element={<DashboardPage stub="My Attendance" />} />

          <Route path="/quizzes"             element={<DashboardPage stub="Quizzes" />} />
          <Route path="/quizzes/create"      element={<DashboardPage stub="Create Quiz" />} />
          <Route path="/quizzes/:id"         element={<DashboardPage stub="Quiz Detail" />} />
          <Route path="/quizzes/:id/edit"    element={<DashboardPage stub="Edit Quiz" />} />
          <Route path="/quizzes/:id/assign"  element={<DashboardPage stub="Assign Quiz" />} />

          <Route path="/quiz/attempt/:id"    element={<DashboardPage stub="Quiz Attempt" />} />
          <Route path="/quiz/attempt/:id/results" element={<DashboardPage stub="Quiz Results" />} />

          <Route path="/analytics/attendance" element={<DashboardPage stub="Attendance Analytics" />} />

          <Route path="/reports"             element={<DashboardPage stub="Reports" />} />
          <Route path="/profile"             element={<DashboardPage stub="Profile" />} />
          <Route path="/my-progress"         element={<DashboardPage stub="My Progress" />} />
          <Route path="/my-tasks"            element={<DashboardPage stub="My Tasks" />} />

          {/* Remedial review (teacher) */}
          <Route path="/remedial-submissions/:id/review" element={<DashboardPage stub="Submission Review" />} />
        </Route>

        {/* ── Root redirect ──────────────────────────────────────────────── */}
        {/* The authenticated app starts at /dashboard */}
        <Route path="/dashboard" element={<Navigate to="/dashboard" replace />} />

        {/* ── 404 ────────────────────────────────────────────────────────── */}
        <Route path="*" element={<NotFoundPage />} />

      </Routes>
    </Suspense>
  );
}

export default AppRouter;
