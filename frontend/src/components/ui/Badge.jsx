/**
 * ============================================================================
 * components/ui/Badge.jsx — Status Badge Component
 * ============================================================================
 * Matches the pill badges used in PMRS tables and cards.
 * Props:
 *   variant: 'success'|'warning'|'danger'|'info'|'neutral'|'primary'
 *   size:    'sm'|'md'
 *   dot:     boolean — show a small coloured dot before text
 * ============================================================================
 */

import { cn } from '@/utils/cn';

const variants = {
  success: 'bg-green-50  text-green-700  border-green-200/70',
  warning: 'bg-amber-50  text-amber-700  border-amber-200/70',
  danger:  'bg-red-50    text-red-700    border-red-200/70',
  info:    'bg-blue-50   text-blue-700   border-blue-200/70',
  neutral: 'bg-gray-50   text-gray-600   border-gray-200/70',
  primary: 'bg-[var(--c-primary-bg)] text-[var(--c-primary)] border-[rgba(108,92,231,0.2)]',
};

const dotColors = {
  success: 'bg-green-500',
  warning: 'bg-amber-500',
  danger:  'bg-red-500',
  info:    'bg-blue-500',
  neutral: 'bg-gray-400',
  primary: 'bg-[var(--c-primary)]',
};

const sizes = {
  sm: 'px-2 py-0.5 text-[10px] font-semibold',
  md: 'px-2.5 py-1 text-xs font-semibold',
};

function Badge({ children, variant = 'neutral', size = 'md', dot = false, className }) {
  return (
    <span
      className={cn(
        'inline-flex items-center gap-1.5 rounded-full border uppercase tracking-wide',
        variants[variant],
        sizes[size],
        className
      )}
    >
      {dot && (
        <span className={cn('w-1.5 h-1.5 rounded-full flex-shrink-0', dotColors[variant])} />
      )}
      {children}
    </span>
  );
}

export default Badge;
