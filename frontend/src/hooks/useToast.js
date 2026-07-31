/**
 * ============================================================================
 * hooks/useToast.js — Toast Notification Hook
 * ============================================================================
 * Simple toast state manager. Pairs with a Toast component rendered in layouts.
 * Mirrors the Laravel flash message UX (auto-dismiss after 4 seconds).
 * ============================================================================
 */

import { useState, useCallback } from 'react';

/**
 * @typedef {'success'|'error'|'warning'|'info'} ToastType
 * @typedef {{ id: number, type: ToastType, message: string }} Toast
 */

export function useToast() {
  const [toasts, setToasts] = useState([]);

  const addToast = useCallback((message, type = 'success') => {
    const id = Date.now();
    setToasts((prev) => [...prev, { id, type, message }]);
    // Auto-dismiss after 4 seconds (same as Blade flash messages)
    setTimeout(() => {
      setToasts((prev) => prev.filter((t) => t.id !== id));
    }, 4000);
  }, []);

  const removeToast = useCallback((id) => {
    setToasts((prev) => prev.filter((t) => t.id !== id));
  }, []);

  const toast = {
    success: (msg) => addToast(msg, 'success'),
    error:   (msg) => addToast(msg, 'error'),
    warning: (msg) => addToast(msg, 'warning'),
    info:    (msg) => addToast(msg, 'info'),
  };

  return { toasts, toast, removeToast };
}

export default useToast;
