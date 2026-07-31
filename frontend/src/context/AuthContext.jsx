import { createContext, useContext, useEffect, useState, useCallback, useMemo } from 'react';
import api from '@/services/api';
import { ROLES } from '@/constants/roles';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const fetchUser = useCallback(async () => {
    setLoading(true);
    setError(null);
    try {
      const { data } = await api.get('/api/user');
      setUser(data);
    } catch (err) {
      setUser(null);
      if (err.response?.status !== 401) {
        setError(err.message);
      }
    } finally {
      setLoading(false);
    }
  }, []);

  const logout = useCallback(async () => {
    try {
      await api.post('/logout');
    } finally {
      setUser(null);
      window.location.href = `${import.meta.env.VITE_BACKEND_URL || 'http://localhost:8000'}/login`;
    }
  }, []);

  useEffect(() => {
    fetchUser();
  }, [fetchUser]);

  const isAdmin = useCallback(() => user?.role === ROLES.ADMIN, [user]);
  const isTeacher = useCallback(() => user?.role === ROLES.TEACHER, [user]);
  const isStudent = useCallback(() => user?.role === ROLES.STUDENT, [user]);
  const isAuthenticated = useCallback(() => !!user, [user]);

  const getRoleLabel = useCallback(() => {
    if (user?.role === ROLES.ADMIN) return 'Administrator';
    if (user?.role === ROLES.TEACHER) return 'Teacher';
    if (user?.role === ROLES.STUDENT) return 'Student';
    return 'User';
  }, [user]);

  const getDashboardPath = useCallback(() => {
    if (user?.role === ROLES.ADMIN) return '/dashboard/admin';
    if (user?.role === ROLES.TEACHER) return '/dashboard/teacher';
    if (user?.role === ROLES.STUDENT) return '/dashboard/student';
    return '/dashboard';
  }, [user]);

  const value = useMemo(() => ({
    user,
    loading,
    error,
    logout,
    fetchUser,
    isAdmin,
    isTeacher,
    isStudent,
    isAuthenticated,
    getRoleLabel,
    getDashboardPath,
  }), [user, loading, error, logout, fetchUser, isAdmin, isTeacher, isStudent, isAuthenticated, getRoleLabel, getDashboardPath]);

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuthContext() {
  const ctx = useContext(AuthContext);
  if (!ctx) {
    throw new Error('useAuthContext must be used within an <AuthProvider>');
  }
  return ctx;
}

export default AuthContext;
