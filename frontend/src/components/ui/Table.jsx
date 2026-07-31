/**
 * ============================================================================
 * components/ui/Table.jsx — Generic Data Table
 * ============================================================================
 * Matches the table styles from PMRS Blade views.
 * Props:
 *   columns: [{ key, label, render?, className? }]
 *   data:    array of row objects
 *   loading: boolean
 *   empty:   ReactNode — custom empty state
 *   keyField: string — field to use as React key (default 'id')
 * ============================================================================
 */

import EmptyState from './EmptyState';
import { cn } from '@/utils/cn';

function Table({ columns = [], data = [], loading = false, empty, keyField = 'id', className }) {
  return (
    <div className={cn('overflow-x-auto', className)}>
      <table className="w-full text-sm border-collapse">
        {/* Head */}
        <thead>
          <tr className="border-b border-black/[0.07]">
            {columns.map((col) => (
              <th
                key={col.key}
                className={cn(
                  'px-4 py-3 text-left text-xs font-semibold text-[var(--c-muted)] uppercase tracking-wider whitespace-nowrap',
                  col.headerClassName
                )}
              >
                {col.label}
              </th>
            ))}
          </tr>
        </thead>

        {/* Body */}
        <tbody>
          {loading ? (
            // Skeleton rows
            Array.from({ length: 5 }).map((_, i) => (
              <tr key={i} className="border-b border-black/[0.04]">
                {columns.map((col) => (
                  <td key={col.key} className="px-4 py-3">
                    <div className="h-4 bg-gray-100 rounded animate-pulse" style={{ width: `${60 + Math.random() * 30}%` }} />
                  </td>
                ))}
              </tr>
            ))
          ) : data.length === 0 ? (
            <tr>
              <td colSpan={columns.length} className="py-12">
                {empty || (
                  <EmptyState
                    title="No data found"
                    description="There is nothing to display here yet."
                  />
                )}
              </td>
            </tr>
          ) : (
            data.map((row, rowIndex) => (
              <tr
                key={row[keyField] ?? rowIndex}
                className="border-b border-black/[0.04] hover:bg-[var(--c-primary-bg)]/40 transition-colors duration-100"
              >
                {columns.map((col) => (
                  <td
                    key={col.key}
                    className={cn('px-4 py-3 text-[var(--c-text)]', col.className)}
                  >
                    {col.render ? col.render(row[col.key], row, rowIndex) : row[col.key] ?? '—'}
                  </td>
                ))}
              </tr>
            ))
          )}
        </tbody>
      </table>
    </div>
  );
}

export default Table;
