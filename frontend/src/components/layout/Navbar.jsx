/**
 * ============================================================================
 * components/layout/Navbar.jsx — Floating Top Navbar
 * ============================================================================
 * Pixel-perfect React port of the app.blade.php navbar.
 *
 * Features:
 *   - Glassmorphism floating bar (sticky top)
 *   - Logo + school name badge
 *   - Role-aware navigation links (admin/teacher/student)
 *   - Profile pill with dropdown (dashboard, tasks, sign out)
 *   - Scroll blur effect
 *   - Hamburger button (triggers mobile sidebar)
 *
 * CSS classes are preserved from the Blade implementation
 * (defined in src/index.css) so visual output is identical.
 * ============================================================================
 */

import { useEffect, useRef, useState } from 'react';
import { NavLink, useNavigate }        from 'react-router-dom';
import { AnimatePresence, motion }     from 'motion/react';
import { useAuth }                     from '@/hooks/useAuth';
import { getInitials }                 from '@/utils/format';
import { ROUTES }                      from '@/constants/routes';

// ── Nav link sets by role ─────────────────────────────────────────────────────

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
  { label: 'Dashboard',        to: ROUTES.DASHBOARD_TEACHER },
  { label: 'Mark Attendance',  to: ROUTES.ATTENDANCE },
  { label: 'My Students',      to: ROUTES.STUDENTS },
  { label: 'Performance',      to: ROUTES.PERFORMANCE },
  { label: 'Quizzes',          to: ROUTES.QUIZZES },
];

const STUDENT_LINKS = [
  { label: 'Dashboard',     to: ROUTES.DASHBOARD_STUDENT },
  { label: 'My Attendance', to: ROUTES.MY_ATTENDANCE },
  { label: 'My Progress',   to: ROUTES.MY_PROGRESS },
  { label: 'My Tasks',      to: ROUTES.MY_TASKS },
];

// ── Component ─────────────────────────────────────────────────────────────────
function Navbar({ onMenuOpen }) {
  const { user, isAdmin, isTeacher, logout } = useAuth();
  const [scrolled,       setScrolled]       = useState(false);
  const [profileOpen,    setProfileOpen]    = useState(false);
  const profileRef                          = useRef(null);
  const navigate                            = useNavigate();

  // Determine nav links based on role
  const navLinks = isAdmin() ? ADMIN_LINKS : isTeacher() ? TEACHER_LINKS : STUDENT_LINKS;

  // Scroll blur effect
  useEffect(() => {
    const handler = () => setScrolled(window.scrollY > 8);
    window.addEventListener('scroll', handler, { passive: true });
    return () => window.removeEventListener('scroll', handler);
  }, []);

  // Close profile dropdown on outside click
  useEffect(() => {
    if (!profileOpen) return;
    const handler = (e) => {
      if (profileRef.current && !profileRef.current.contains(e.target)) {
        setProfileOpen(false);
      }
    };
    document.addEventListener('mousedown', handler);
    return () => document.removeEventListener('mousedown', handler);
  }, [profileOpen]);

  // Profile dropdown items by role
  const profileItems = () => {
    if (isAdmin()) {
      return [{ icon: '🏠', label: 'Dashboard', to: ROUTES.DASHBOARD_ADMIN }];
    }
    if (isTeacher()) {
      return [
        { icon: '🏠', label: 'Dashboard', to: ROUTES.DASHBOARD_TEACHER },
        { icon: '📝', label: 'My Quizzes', to: ROUTES.QUIZZES },
      ];
    }
    return [
      { icon: '🏠', label: 'Dashboard',   to: ROUTES.DASHBOARD_STUDENT },
      { icon: '📊', label: 'My Progress', to: ROUTES.MY_PROGRESS },
      { icon: '📋', label: 'My Tasks',    to: ROUTES.MY_TASKS },
    ];
  };

  const roleLabel = isAdmin() ? 'Administrator' : isTeacher() ? 'Teacher' : 'Student';

  return (
    <div className="pmrs-navbar-outer">
      <header className={`pmrs-navbar${scrolled ? ' scrolled' : ''}`}>

        {/* ── Logo ── */}
        <NavLink to={isAdmin() ? ROUTES.DASHBOARD_ADMIN : isTeacher() ? ROUTES.DASHBOARD_TEACHER : ROUTES.DASHBOARD_STUDENT} className="nb-logo">
          <img
            src="/logo.png"
            alt="PMRS Logo"
            onError={(e) => { e.target.style.display = 'none'; e.target.nextElementSibling.style.display = 'flex'; }}
          />
          <div style={{ display: 'none', width: 32, height: 32, background: 'var(--grad-primary)', borderRadius: 8, alignItems: 'center', justifyContent: 'center', color: '#fff' }}>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
            </svg>
          </div>
        </NavLink>

        {/* ── School Badge ── */}
        {user?.school?.name && (
          <div style={{ marginLeft: 8, padding: '4px 10px', background: 'rgba(108,92,231,0.08)', border: '1px solid rgba(108,92,231,0.2)', borderRadius: 8, fontSize: 11, fontWeight: 700, color: '#5A4BD6', letterSpacing: '0.02em', display: 'flex', alignItems: 'center', gap: 4 }}>
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
              <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            {user.school.name.toUpperCase()}
          </div>
        )}

        {/* ── Desktop Nav Pills ── */}
        <nav className="nb-nav">
          {navLinks.map((link) => (
            <NavLink
              key={link.to}
              to={link.to}
              className={({ isActive }) => isActive ? 'active' : ''}
              end={link.to === ROUTES.DASHBOARD_ADMIN || link.to === ROUTES.DASHBOARD_TEACHER || link.to === ROUTES.DASHBOARD_STUDENT}
            >
              {link.label}
            </NavLink>
          ))}
        </nav>

        {/* ── Hamburger (mobile) ── */}
        <button
          className="nb-hamburger"
          onClick={onMenuOpen}
          aria-label="Open menu"
        >
          <span /><span /><span />
        </button>

        {/* ── Spacer ── */}
        <div className="nb-spacer" />

        {/* ── Profile Pill ── */}
        <div className="nb-profile" ref={profileRef} onClick={() => setProfileOpen((o) => !o)}>
          <div className="nb-avatar">{getInitials(user?.name)}</div>
          <div className="nb-user-text">
            <span className="nb-user-name">{user?.name}</span>
            <span className="nb-user-role">{roleLabel}</span>
          </div>
          <svg className="nb-chevron" style={{ transform: profileOpen ? 'rotate(180deg)' : 'rotate(0deg)' }} width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
            <polyline points="6 9 12 15 18 9"/>
          </svg>

          {/* ── Profile Dropdown ── */}
          <AnimatePresence>
            {profileOpen && (
              <motion.div
                className="nb-profile-dropdown"
                initial={{ opacity: 0, y: -8, scale: 0.97 }}
                animate={{ opacity: 1, y:  0, scale: 1 }}
                exit={{   opacity: 0, y: -8, scale: 0.97 }}
                transition={{ duration: 0.18 }}
                onClick={(e) => e.stopPropagation()}
              >
                {/* Header */}
                <div className="nb-dropdown-header">
                  <div className="dd-avatar" style={{ width: 38, height: 38, borderRadius: '50%', background: 'linear-gradient(135deg,#6C5CE7,#8B5CF6)', color: '#fff', fontWeight: 700, fontSize: 13, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                    {getInitials(user?.name)}
                  </div>
                  <div>
                    <div style={{ fontWeight: 700, color: 'var(--c-text)', fontSize: 14, letterSpacing: '-0.01em' }}>{user?.name}</div>
                    <div style={{ fontSize: 11.5, color: 'var(--c-muted)', marginTop: 1, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{user?.email}</div>
                  </div>
                </div>

                {/* Nav items */}
                <div className="nb-dropdown-section">
                  {profileItems().map((item) => (
                    <button
                      key={item.to}
                      className="nb-dropdown-item"
                      onClick={() => { navigate(item.to); setProfileOpen(false); }}
                    >
                      <span className="dd-icon">{item.icon}</span>
                      {item.label}
                    </button>
                  ))}
                </div>

                <div className="nb-dropdown-sep" />

                {/* Sign Out */}
                <div className="nb-dropdown-section">
                  <button className="nb-dropdown-item danger" onClick={logout}>
                    <span className="dd-icon">🚪</span>
                    Sign Out
                  </button>
                </div>
              </motion.div>
            )}
          </AnimatePresence>
        </div>

      </header>
    </div>
  );
}

export default Navbar;
