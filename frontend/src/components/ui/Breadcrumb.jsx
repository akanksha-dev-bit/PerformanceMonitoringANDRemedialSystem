/**
 * ============================================================================
 * components/ui/Breadcrumb.jsx — Breadcrumb Navigation
 * ============================================================================
 * Props:
 *   items: [{ label, href? }]  — last item is the current page (no href)
 * ============================================================================
 */

import { Link } from 'react-router-dom';
import { cn }   from '@/utils/cn';

function Breadcrumb({ items = [], className }) {
  return (
    <nav className={cn('flex items-center gap-1.5 text-xs', className)} aria-label="Breadcrumb">
      {items.map((item, i) => {
        const isLast = i === items.length - 1;
        return (
          <span key={i} className="flex items-center gap-1.5">
            {i > 0 && (
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="text-[var(--c-muted)]">
                <polyline points="9 18 15 12 9 6" />
              </svg>
            )}
            {isLast || !item.href ? (
              <span className={cn('font-medium', isLast ? 'text-[var(--c-text)]' : 'text-[var(--c-muted)]')}>
                {item.label}
              </span>
            ) : (
              <Link
                to={item.href}
                className="text-[var(--c-muted)] hover:text-[var(--c-primary)] transition-colors"
              >
                {item.label}
              </Link>
            )}
          </span>
        );
      })}
    </nav>
  );
}

export default Breadcrumb;
