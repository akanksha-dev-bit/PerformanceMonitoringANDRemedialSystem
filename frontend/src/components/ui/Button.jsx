/**
 * ============================================================================
 * components/ui/Button.jsx — Reusable Button Component
 * ============================================================================
 * Variants match the Blade button styles used across PMRS.
 * Props:
 *   variant: 'primary' | 'secondary' | 'danger' | 'ghost' | 'outline'
 *   size:    'sm' | 'md' | 'lg'
 *   loading: boolean
 *   disabled: boolean
 * ============================================================================
 */

import { forwardRef } from 'react';
import { cn } from '@/utils/cn';

const variants = {
  primary: [
    'text-white font-semibold',
    'bg-gradient-to-br from-[#6C5CE7] to-[#5A4BD6]',
    'shadow-[0_4px_14px_rgba(108,92,231,0.3)]',
    'hover:shadow-[0_6px_20px_rgba(108,92,231,0.4)] hover:-translate-y-0.5',
    'active:translate-y-0 active:shadow-sm',
    'disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none',
  ],
  secondary: [
    'text-[var(--c-text)] font-medium',
    'bg-white border border-[var(--c-border)]',
    'shadow-sm hover:shadow-md hover:-translate-y-0.5',
    'hover:border-[rgba(108,92,231,0.3)] hover:bg-[var(--c-primary-bg)]',
    'active:translate-y-0',
    'disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none',
  ],
  danger: [
    'text-white font-semibold',
    'bg-gradient-to-br from-red-500 to-red-600',
    'shadow-[0_4px_14px_rgba(239,68,68,0.3)]',
    'hover:shadow-[0_6px_20px_rgba(239,68,68,0.4)] hover:-translate-y-0.5',
    'active:translate-y-0',
    'disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none',
  ],
  ghost: [
    'text-[var(--c-text-2)] font-medium',
    'bg-transparent hover:bg-[var(--c-primary-bg)] hover:text-[var(--c-primary)]',
    'disabled:opacity-50 disabled:cursor-not-allowed',
  ],
  outline: [
    'text-[var(--c-primary)] font-semibold',
    'bg-transparent border border-[var(--c-primary)]',
    'hover:bg-[var(--c-primary-bg)]',
    'disabled:opacity-50 disabled:cursor-not-allowed',
  ],
};

const sizes = {
  sm: 'px-3 py-1.5 text-xs rounded-lg gap-1.5',
  md: 'px-4 py-2   text-sm rounded-xl gap-2',
  lg: 'px-6 py-2.5 text-sm rounded-xl gap-2',
};

const Button = forwardRef(function Button(
  {
    children,
    variant  = 'primary',
    size     = 'md',
    loading  = false,
    disabled = false,
    className,
    type     = 'button',
    ...props
  },
  ref
) {
  return (
    <button
      ref={ref}
      type={type}
      disabled={disabled || loading}
      className={cn(
        'inline-flex items-center justify-center transition-all duration-200 select-none focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--c-primary)] focus-visible:ring-offset-2',
        variants[variant],
        sizes[size],
        className
      )}
      {...props}
    >
      {loading && (
        <svg
          className="animate-spin -ml-1 mr-1.5"
          style={{ width: size === 'sm' ? 12 : 14, height: size === 'sm' ? 12 : 14 }}
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
        >
          <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
          <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
        </svg>
      )}
      {children}
    </button>
  );
});

export default Button;
