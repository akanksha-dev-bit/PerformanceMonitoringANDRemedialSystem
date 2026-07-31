/**
 * ============================================================================
 * utils/cn.js — Class Name Merger
 * ============================================================================
 * Combines clsx + a minimal tailwind-merge equivalent for clean class merging.
 * Usage: cn('base-class', isActive && 'active-class', props.className)
 * ============================================================================
 */

import { clsx } from 'clsx';

/**
 * Merges class names conditionally.
 * @param {...(string|boolean|null|undefined|object)} inputs
 * @returns {string}
 */
export function cn(...inputs) {
  return clsx(...inputs);
}

export default cn;
