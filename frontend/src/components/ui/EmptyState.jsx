/**
 * ============================================================================
 * components/ui/EmptyState.jsx — Empty Data Placeholder
 * ============================================================================
 * Used when a list/table has no data to show.
 * Matches the .at-empty style from the Blade attendance view.
 * ============================================================================
 */

import { cn } from '@/utils/cn';

function EmptyState({ icon, title = 'Nothing here yet', description, action, className }) {
  return (
    <div className={cn('flex flex-col items-center justify-center text-center py-12 px-6', className)}>
      {/* Default icon or custom */}
      <div className="w-16 h-16 rounded-2xl bg-[var(--c-primary-bg)] flex items-center justify-center mb-4">
        {icon || (
          <svg
            width="28" height="28"
            viewBox="0 0 24 24" fill="none"
            stroke="var(--c-primary)" strokeWidth="1.5"
          >
            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
            <rect x="9" y="3" width="6" height="4" rx="1"/>
            <path d="M9 12h6M9 16h4"/>
          </svg>
        )}
      </div>

      <h4 className="text-sm font-semibold text-[var(--c-text)] mb-1">{title}</h4>

      {description && (
        <p className="text-xs text-[var(--c-muted)] max-w-[280px] leading-relaxed">{description}</p>
      )}

      {action && <div className="mt-4">{action}</div>}
    </div>
  );
}

export default EmptyState;
