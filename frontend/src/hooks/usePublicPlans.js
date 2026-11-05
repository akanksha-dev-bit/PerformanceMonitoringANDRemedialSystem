import { useEffect, useState } from 'react';

const BACKEND_URL = import.meta.env.VITE_BACKEND_URL || 'http://localhost:8000';
let cachedRequest;

export function usePublicPlans() {
  const [plans, setPlans] = useState(null);
  const [error, setError] = useState(null);

  useEffect(() => {
    cachedRequest ??= fetch(`${BACKEND_URL}/api/plans`, { headers: { Accept: 'application/json' } })
      .then((response) => {
        if (!response.ok) throw new Error('Plan details are temporarily unavailable.');
        return response.json();
      })
      .catch((err) => {
        cachedRequest = null;
        throw err;
      });

    cachedRequest.then((data) => setPlans(data.plans)).catch((err) => setError(err.message));
  }, []);

  return { plans, error };
}
