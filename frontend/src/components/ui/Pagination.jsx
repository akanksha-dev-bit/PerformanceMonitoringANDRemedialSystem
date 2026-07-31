/**
 * ============================================================================
 * components/ui/Pagination.jsx — Page Navigation
 * ============================================================================
 * Matches Laravel's paginator response shape:
 * { current_page, last_page, per_page, total, data }
 * ============================================================================
 */

import { cn } from '@/utils/cn';

function Pagination({ meta, onPageChange, className }) {
  if (!meta || meta.last_page <= 1) return null;

  const { current_page, last_page, total, per_page } = meta;

  // Generate page numbers to display (max 7 visible)
  const getPages = () => {
    const pages = [];
    const delta = 2;
    const range = [];

    for (let i = Math.max(1, current_page - delta); i <= Math.min(last_page, current_page + delta); i++) {
      range.push(i);
    }

    if (range[0] > 1) {
      pages.push(1);
      if (range[0] > 2) pages.push('...');
    }

    pages.push(...range);

    if (range[range.length - 1] < last_page) {
      if (range[range.length - 1] < last_page - 1) pages.push('...');
      pages.push(last_page);
    }

    return pages;
  };

  const from = (current_page - 1) * per_page + 1;
  const to   = Math.min(current_page * per_page, total);

  return (
    <div className={cn('flex items-center justify-between gap-4 pt-4', className)}>
      <p className="text-xs text-[var(--c-muted)]">
        Showing <span className="font-semibold text-[var(--c-text)]">{from}–{to}</span> of <span className="font-semibold text-[var(--c-text)]">{total}</span> results
      </p>

      <div className="flex items-center gap-1">
        {/* Prev */}
        <button
          onClick={() => onPageChange(current_page - 1)}
          disabled={current_page === 1}
          className="w-8 h-8 flex items-center justify-center rounded-lg border border-[var(--c-border)] text-[var(--c-text-2)] hover:bg-[var(--c-primary-bg)] hover:text-[var(--c-primary)] hover:border-[rgba(108,92,231,0.3)] disabled:opacity-40 disabled:cursor-not-allowed transition-all text-sm"
        >
          ‹
        </button>

        {getPages().map((page, i) =>
          page === '...' ? (
            <span key={`ellipsis-${i}`} className="w-8 h-8 flex items-center justify-center text-[var(--c-muted)] text-sm">…</span>
          ) : (
            <button
              key={page}
              onClick={() => onPageChange(page)}
              className={cn(
                'w-8 h-8 flex items-center justify-center rounded-lg text-sm font-medium transition-all',
                page === current_page
                  ? 'bg-gradient-to-br from-[#6C5CE7] to-[#5A4BD6] text-white shadow-[0_4px_12px_rgba(108,92,231,0.3)]'
                  : 'border border-[var(--c-border)] text-[var(--c-text-2)] hover:bg-[var(--c-primary-bg)] hover:text-[var(--c-primary)] hover:border-[rgba(108,92,231,0.3)]'
              )}
            >
              {page}
            </button>
          )
        )}

        {/* Next */}
        <button
          onClick={() => onPageChange(current_page + 1)}
          disabled={current_page === last_page}
          className="w-8 h-8 flex items-center justify-center rounded-lg border border-[var(--c-border)] text-[var(--c-text-2)] hover:bg-[var(--c-primary-bg)] hover:text-[var(--c-primary)] hover:border-[rgba(108,92,231,0.3)] disabled:opacity-40 disabled:cursor-not-allowed transition-all text-sm"
        >
          ›
        </button>
      </div>
    </div>
  );
}

export default Pagination;
