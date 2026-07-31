/**
 * ============================================================================
 * hooks/useApi.js — Generic API Request Hook
 * ============================================================================
 *
 * PURPOSE:
 *   Wraps any async service call with loading, error, and data states.
 *   Prevents the need to manage these states manually in every component.
 *
 * USAGE:
 *   const { data, loading, error, execute } = useApi(studentService.getAll);
 *   useEffect(() => { execute(); }, []);
 *
 * OR with params:
 *   const { data, loading, execute } = useApi(studentService.getById);
 *   execute(studentId);
 * ============================================================================
 */

import { useState, useCallback } from 'react';

export function useApi(serviceFunction) {
  const [data, setData]       = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError]     = useState(null);

  const execute = useCallback(async (...args) => {
    setLoading(true);
    setError(null);
    try {
      const result = await serviceFunction(...args);
      // Support both { data: ... } axios responses and raw objects
      const payload = result?.data !== undefined ? result.data : result;
      setData(payload);
      return payload;
    } catch (err) {
      const message =
        err.response?.data?.message ||
        err.response?.data?.error   ||
        err.message                 ||
        'An unexpected error occurred.';
      setError(message);
      throw err;
    } finally {
      setLoading(false);
    }
  }, [serviceFunction]);

  const reset = useCallback(() => {
    setData(null);
    setError(null);
    setLoading(false);
  }, []);

  return { data, loading, error, execute, reset };
}

export default useApi;
