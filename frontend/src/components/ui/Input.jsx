/**
 * ============================================================================
 * components/ui/Input.jsx — Form Input Component
 * ============================================================================
 * Matches the form styling used across PMRS Blade forms.
 * Props:
 *   label:   string
 *   error:   string  — validation error message
 *   hint:    string  — helper text below the input
 *   prefix:  ReactNode — icon/text before the input
 *   suffix:  ReactNode — icon/text after the input
 * ============================================================================
 */

import { forwardRef } from 'react';
import { cn } from '@/utils/cn';

const Input = forwardRef(function Input(
  { label, error, hint, prefix, suffix, className, id, ...props },
  ref
) {
  const inputId = id || `input-${label?.toLowerCase().replace(/\s+/g, '-')}`;

  return (
    <div className="flex flex-col gap-1.5">
      {label && (
        <label
          htmlFor={inputId}
          className="text-sm font-medium text-[var(--c-text)] tracking-tight"
        >
          {label}
        </label>
      )}

      <div className="relative flex items-center">
        {prefix && (
          <div className="absolute left-3 flex items-center pointer-events-none text-[var(--c-muted)]">
            {prefix}
          </div>
        )}

        <input
          ref={ref}
          id={inputId}
          className={cn(
            'w-full px-3 py-2 text-sm text-[var(--c-text)]',
            'bg-white border rounded-xl',
            'placeholder:text-[var(--c-muted)]',
            'transition-all duration-200',
            'outline-none focus:ring-2 focus:ring-[var(--c-primary)]/20 focus:border-[var(--c-primary)]',
            error
              ? 'border-red-400 focus:ring-red-100 focus:border-red-400'
              : 'border-[var(--c-border)] hover:border-[rgba(108,92,231,0.3)]',
            prefix && 'pl-9',
            suffix && 'pr-9',
            className
          )}
          {...props}
        />

        {suffix && (
          <div className="absolute right-3 flex items-center text-[var(--c-muted)]">
            {suffix}
          </div>
        )}
      </div>

      {error && (
        <p className="text-xs text-red-500 font-medium flex items-center gap-1">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
          </svg>
          {error}
        </p>
      )}

      {hint && !error && (
        <p className="text-xs text-[var(--c-muted)]">{hint}</p>
      )}
    </div>
  );
});

/**
 * Textarea variant
 */
Input.Textarea = forwardRef(function Textarea({ label, error, hint, className, id, ...props }, ref) {
  const inputId = id || `textarea-${label?.toLowerCase().replace(/\s+/g, '-')}`;
  return (
    <div className="flex flex-col gap-1.5">
      {label && (
        <label htmlFor={inputId} className="text-sm font-medium text-[var(--c-text)] tracking-tight">
          {label}
        </label>
      )}
      <textarea
        ref={ref}
        id={inputId}
        className={cn(
          'w-full px-3 py-2 text-sm text-[var(--c-text)]',
          'bg-white border rounded-xl resize-y min-h-[100px]',
          'placeholder:text-[var(--c-muted)]',
          'transition-all duration-200',
          'outline-none focus:ring-2 focus:ring-[var(--c-primary)]/20 focus:border-[var(--c-primary)]',
          error ? 'border-red-400' : 'border-[var(--c-border)] hover:border-[rgba(108,92,231,0.3)]',
          className
        )}
        {...props}
      />
      {error && <p className="text-xs text-red-500 font-medium">{error}</p>}
      {hint && !error && <p className="text-xs text-[var(--c-muted)]">{hint}</p>}
    </div>
  );
});

/**
 * Select variant
 */
Input.Select = forwardRef(function Select({ label, error, hint, className, id, children, ...props }, ref) {
  const inputId = id || `select-${label?.toLowerCase().replace(/\s+/g, '-')}`;
  return (
    <div className="flex flex-col gap-1.5">
      {label && (
        <label htmlFor={inputId} className="text-sm font-medium text-[var(--c-text)] tracking-tight">
          {label}
        </label>
      )}
      <select
        ref={ref}
        id={inputId}
        className={cn(
          'w-full px-3 py-2 text-sm text-[var(--c-text)]',
          'bg-white border rounded-xl appearance-none cursor-pointer',
          'transition-all duration-200',
          'outline-none focus:ring-2 focus:ring-[var(--c-primary)]/20 focus:border-[var(--c-primary)]',
          error ? 'border-red-400' : 'border-[var(--c-border)] hover:border-[rgba(108,92,231,0.3)]',
          className
        )}
        {...props}
      >
        {children}
      </select>
      {error && <p className="text-xs text-red-500 font-medium">{error}</p>}
      {hint && !error && <p className="text-xs text-[var(--c-muted)]">{hint}</p>}
    </div>
  );
});

export default Input;
