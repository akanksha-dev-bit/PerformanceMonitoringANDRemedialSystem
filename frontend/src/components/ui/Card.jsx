/**
 * ============================================================================
 * components/ui/Card.jsx — Reusable Card Container
 * ============================================================================
 * Matches the card styles used throughout PMRS Blade views.
 * Props:
 *   title:    string | ReactNode  — optional card header title
 *   subtitle: string              — optional subtitle below title
 *   actions:  ReactNode           — optional right-side header actions
 *   footer:   ReactNode           — optional card footer
 *   padding:  boolean             — whether to add body padding (default true)
 *   className: string
 * ============================================================================
 */

import { cn } from '@/utils/cn';

function Card({ children, title, subtitle, actions, footer, padding = true, className, bodyClassName, ...props }) {
  return (
    <div
      className={cn(
        'bg-white rounded-2xl border border-black/[0.07] shadow-[0_2px_12px_rgba(0,0,0,0.05)]',
        className
      )}
      {...props}
    >
      {/* Header */}
      {(title || actions) && (
        <div className="flex items-center justify-between px-6 py-4 border-b border-black/[0.06]">
          <div>
            {title && (
              typeof title === 'string'
                ? <h3 className="text-[15px] font-semibold text-[var(--c-text)] tracking-tight">{title}</h3>
                : title
            )}
            {subtitle && (
              <p className="text-xs text-[var(--c-muted)] mt-0.5">{subtitle}</p>
            )}
          </div>
          {actions && <div className="flex items-center gap-2">{actions}</div>}
        </div>
      )}

      {/* Body */}
      <div className={cn(padding && 'p-6', bodyClassName)}>
        {children}
      </div>

      {/* Footer */}
      {footer && (
        <div className="px-6 py-3 border-t border-black/[0.06] bg-gray-50/50 rounded-b-2xl">
          {footer}
        </div>
      )}
    </div>
  );
}

/**
 * Stat card variant — used in dashboards.
 * Props: label, value, icon, trend ('+12%'), trendUp (bool), color
 */
Card.Stat = function StatCard({ label, value, icon, trend, trendUp, color = 'primary', className }) {
  const colorMap = {
    primary: { bg: 'bg-[var(--c-primary-bg)]',  text: 'text-[var(--c-primary)]' },
    success: { bg: 'bg-green-50',                 text: 'text-green-600' },
    warning: { bg: 'bg-amber-50',                 text: 'text-amber-600' },
    danger:  { bg: 'bg-red-50',                   text: 'text-red-600' },
    info:    { bg: 'bg-blue-50',                   text: 'text-blue-600' },
  };
  const c = colorMap[color] || colorMap.primary;

  return (
    <div className={cn('bg-white rounded-2xl border border-black/[0.07] p-5 shadow-[0_2px_12px_rgba(0,0,0,0.04)]', className)}>
      <div className="flex items-start justify-between">
        <div>
          <p className="text-xs font-medium text-[var(--c-muted)] uppercase tracking-wider mb-1">{label}</p>
          <p className="text-2xl font-bold text-[var(--c-text)] tracking-tight">{value ?? '—'}</p>
          {trend && (
            <p className={cn('text-xs font-medium mt-1', trendUp ? 'text-green-600' : 'text-red-500')}>
              {trendUp ? '↑' : '↓'} {trend}
            </p>
          )}
        </div>
        {icon && (
          <div className={cn('w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0', c.bg, c.text)}>
            {icon}
          </div>
        )}
      </div>
    </div>
  );
};

export default Card;
