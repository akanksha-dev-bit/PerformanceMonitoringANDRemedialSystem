/**
 * ============================================================================
 * layouts/GuestLayout.jsx — Unauthenticated Guest Layout
 * ============================================================================
 * Used for pages accessible without authentication.
 * Currently a passthrough (auth pages still served by Laravel Blade).
 * Will be used in Phase 2 when login/register are migrated to React.
 * ============================================================================
 */

import { Outlet } from 'react-router-dom';
import { motion } from 'motion/react';

function GuestLayout({ children }) {
  return (
    <div className="min-h-screen flex items-center justify-center" style={{ background: 'linear-gradient(180deg,#f8fafc 0%,#eef2ff 100%)' }}>
      <motion.div
        initial={{ opacity: 0, y: 16 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.3, ease: [0.4, 0, 0.2, 1] }}
        className="w-full max-w-md"
      >
        {children ?? <Outlet />}
      </motion.div>
    </div>
  );
}

export default GuestLayout;
