/**
 * ============================================================================
 * components/layout/Sidebar.jsx — Mobile Drawer Sidebar
 * ============================================================================
 * Pixel-perfect React port of the .nb-drawer from app.blade.php.
 * Slides in from the left on mobile.
 *
 * Props:
 *   open:    boolean
 *   onClose: function
 * ============================================================================
 */

import { NavLink, useNavigate } from 'react-router-dom';
import { motion, AnimatePresence } from 'motion/react';
import { useAuth } from '@/hooks/useAuth';
import { getInitials } from '@/utils/format';
import { ROUTES } from '@/constants/routes';

const ADMIN_LINKS = [
  { label: 'Dashboard',   to: ROUTES.DASHBOARD_ADMIN },
  { label: 'Students',    to: ROUTES.STUDENTS },
  { label: 'Subjects',    to: ROUTES.SUBJECTS },
  { label: 'Teachers',    to: ROUTES.TEACHERS },
  { label: 'Classes',     to: ROUTES.CLASSES },
  { label: 'Rooms',       to: ROUTES.CLASSROOMS },
  { label: 'Allocations', to: ROUTES.ALLOCATIONS },
  { label: 'Timetable',   to: ROUTES.TIMETABLE },
  { label: 'Attendance',  to: ROUTES.ADMIN_ATTENDANCE_ANALYTICS },
  { label: 'Reports',     to: ROUTES.REPORTS },
];

const TEACHER_LINKS = [
  { label: 'Dashboard',       to: ROUTES.DASHBOARD_TEACHER },
  { label: 'Mark Attendance', to: ROUTES.ATTENDANCE },
  { label: 'My Students',     to: ROUTES.STUDENTS },
  { label: 'Performance',     to: ROUTES.PERFORMANCE },
  { label: 'Quizzes',         to: ROUTES.QUIZZES },
];

const STUDENT_LINKS = [
  { label: 'Dashboard',     to: ROUTES.DASHBOARD_STUDENT },
  { label: 'My Attendance', to: ROUTES.MY_ATTENDANCE },
  { label: 'My Progress',   to: ROUTES.MY_PROGRESS },
  { label: 'My Tasks',      to: ROUTES.MY_TASKS },
];

function Sidebar({ open, onClose }) {
  const { user, isAdmin, isTeacher, logout } = useAuth();

  const navLinks = isAdmin() ? ADMIN_LINKS : isTeacher() ? TEACHER_LINKS : STUDENT_LINKS;
  const roleLabel = isAdmin() ? 'Administrator' : isTeacher() ? 'Teacher' : 'Student';

  return (
    <AnimatePresence>
      {open && (
        <>
          {/* Overlay */}
          <motion.div
            key="sidebar-overlay"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            transition={{ duration: 0.2 }}
            className="nb-drawer-overlay"
            onClick={onClose}
          />

          {/* Drawer */}
          <motion.aside
            key="sidebar-drawer"
            className="nb-drawer"
            initial={{ x: '-100%' }}
            animate={{ x: 0 }}
            exit={{ x: '-100%' }}
            transition={{ type: 'spring', damping: 26, stiffness: 260 }}
          >
            {/* Header */}
            <div style={{ padding: 20, borderBottom: '1px solid var(--c-border)', background: 'linear-gradient(135deg,#f8faff,#fff)' }}>
              <img src="/logo.png" alt="PMRS" style={{ height: 28, objectFit: 'contain' }} />
            </div>

            {/* User mini-card */}
            <div style={{ padding: '14px 16px', display: 'flex', alignItems: 'center', gap: 10, background: '#f8fafc', borderBottom: '1px solid var(--c-border)' }}>
              <div style={{ width: 36, height: 36, borderRadius: '50%', background: 'linear-gradient(135deg,#6C5CE7,#8B5CF6)', color: '#fff', fontWeight: 700, fontSize: 13, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                {getInitials(user?.name)}
              </div>
              <div>
                <div style={{ fontSize: 14, fontWeight: 700, color: '#111827' }}>{user?.name}</div>
                <div style={{ fontSize: 11, color: 'var(--c-muted)' }}>{roleLabel}</div>
              </div>
            </div>

            {/* Nav links */}
            <nav className="nb-drawer-nav">
              {navLinks.map((link) => (
                <NavLink
                  key={link.to}
                  to={link.to}
                  className={({ isActive }) => isActive ? 'active' : ''}
                  onClick={onClose}
                  end={link.to === ROUTES.DASHBOARD_ADMIN || link.to === ROUTES.DASHBOARD_TEACHER || link.to === ROUTES.DASHBOARD_STUDENT}
                >
                  {link.label}
                </NavLink>
              ))}
            </nav>

            {/* Sign out footer */}
            <div className="nb-drawer-footer">
              <button
                onClick={logout}
                style={{ width: '100%', display: 'flex', alignItems: 'center', gap: 10, padding: '10px 12px', borderRadius: 10, border: '1px solid rgba(239,68,68,0.18)', background: 'rgba(239,68,68,0.06)', color: '#dc2626', fontSize: 13, fontWeight: 600, cursor: 'pointer', transition: 'all 0.2s' }}
              >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                  <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                  <polyline points="16 17 21 12 16 7"/>
                  <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Sign Out
              </button>
            </div>
          </motion.aside>
        </>
      )}
    </AnimatePresence>
  );
}

export default Sidebar;
