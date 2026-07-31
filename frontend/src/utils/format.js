/**
 * ============================================================================
 * utils/format.js — Data Formatting Utilities
 * ============================================================================
 * Centralizes all data display formatting used across the PMRS UI.
 * ============================================================================
 */

/**
 * Format a date string to a readable format.
 * @param {string|Date} date
 * @param {Intl.DateTimeFormatOptions} options
 * @returns {string}
 */
export function formatDate(date, options = {}) {
  if (!date) return '—';
  return new Intl.DateTimeFormat('en-IN', {
    day:   '2-digit',
    month: 'short',
    year:  'numeric',
    ...options,
  }).format(new Date(date));
}

/**
 * Format a number as a percentage.
 * @param {number} value
 * @param {number} total
 * @param {number} decimals
 * @returns {string}
 */
export function formatPercent(value, total, decimals = 1) {
  if (!total || total === 0) return '0%';
  return `${((value / total) * 100).toFixed(decimals)}%`;
}

/**
 * Get initials from a full name (up to 2 characters).
 * @param {string} name
 * @returns {string}
 */
export function getInitials(name) {
  if (!name) return '??';
  return name
    .split(' ')
    .map((part) => part[0])
    .join('')
    .toUpperCase()
    .slice(0, 2);
}

/**
 * Truncate a string to a max length.
 * @param {string} str
 * @param {number} maxLength
 * @returns {string}
 */
export function truncate(str, maxLength = 50) {
  if (!str) return '';
  return str.length > maxLength ? str.slice(0, maxLength) + '…' : str;
}

/**
 * Capitalize the first letter of each word.
 * @param {string} str
 * @returns {string}
 */
export function titleCase(str) {
  if (!str) return '';
  return str.replace(/\w\S*/g, (txt) => txt[0].toUpperCase() + txt.slice(1).toLowerCase());
}

/**
 * Get a score colour class based on percentage.
 * @param {number} score - 0-100
 * @returns {'success'|'warning'|'danger'}
 */
export function scoreColor(score) {
  if (score >= 75) return 'success';
  if (score >= 50) return 'warning';
  return 'danger';
}
