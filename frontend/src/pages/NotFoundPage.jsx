/**
 * ============================================================================
 * pages/NotFoundPage.jsx — 404 Page
 * ============================================================================
 */

import { Link } from 'react-router-dom';
import { motion } from 'motion/react';

function NotFoundPage() {
  return (
    <div className="min-h-screen flex items-center justify-center px-6" style={{ background: 'linear-gradient(180deg,#f8fafc 0%,#eef2ff 100%)' }}>
      <motion.div
        initial={{ opacity: 0, y: 24 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.4 }}
        className="text-center max-w-md"
      >
        <div className="text-8xl font-bold text-[var(--c-primary)] opacity-20 mb-4">404</div>
        <h1 className="text-2xl font-bold text-[var(--c-text)] mb-2">Page Not Found</h1>
        <p className="text-sm text-[var(--c-muted)] mb-6">
          The page you're looking for doesn't exist or has been moved.
        </p>
        <Link
          to="/dashboard"
          className="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-semibold transition-all"
          style={{ background: 'var(--grad-primary)', boxShadow: '0 4px 14px rgba(108,92,231,0.3)' }}
        >
          ← Go to Dashboard
        </Link>
      </motion.div>
    </div>
  );
}

export default NotFoundPage;
