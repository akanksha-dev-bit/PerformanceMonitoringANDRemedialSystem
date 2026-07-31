/**
 * ============================================================================
 * components/ui/Loader.jsx — Global Lottie Page Loader
 * ============================================================================
 * Uses the Teacher.lottie animation (local copy in /public/animations/).
 * Shown during page transitions and initial auth check.
 * Matches the global-loader behaviour from app.blade.php.
 * ============================================================================
 */

import { DotLottieReact } from '@lottiefiles/dotlottie-react';
import { AnimatePresence, motion } from 'motion/react';

/**
 * Full-screen page loader overlay.
 * @param {{ visible: boolean }} props
 */
function Loader({ visible = true }) {
  return (
    <AnimatePresence>
      {visible && (
        <motion.div
          key="global-loader"
          initial={{ opacity: 1 }}
          exit={{ opacity: 0 }}
          transition={{ duration: 0.4, ease: [0.4, 0, 0.2, 1] }}
          className="fixed inset-0 z-[99999] flex items-center justify-center bg-white"
        >
          <DotLottieReact
            src="/animations/Teacher.lottie"
            autoplay
            loop
            style={{ width: 400, height: 400 }}
          />
        </motion.div>
      )}
    </AnimatePresence>
  );
}

/**
 * Inline spinner for button loading states or small loading areas.
 * @param {{ size?: number, className?: string }} props
 */
Loader.Spinner = function Spinner({ size = 20, className = '' }) {
  return (
    <svg
      className={`animate-spin ${className}`}
      style={{ width: size, height: size }}
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
    >
      <circle
        className="opacity-25"
        cx="12" cy="12" r="10"
        stroke="var(--c-primary)"
        strokeWidth="4"
      />
      <path
        className="opacity-75"
        fill="var(--c-primary)"
        d="M4 12a8 8 0 018-8v8H4z"
      />
    </svg>
  );
};

export default Loader;
