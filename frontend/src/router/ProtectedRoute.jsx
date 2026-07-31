import { useAuth } from '@/hooks/useAuth';
import Loader from '@/components/ui/Loader';

const BACKEND_URL = import.meta.env.VITE_BACKEND_URL || 'http://localhost:8000';

function ProtectedRoute({ children, roles = [] }) {
  const { user, loading } = useAuth();

  if (loading) {
    return <Loader visible />;
  }

  if (!user) {
    window.location.href = `${BACKEND_URL}/login`;
    return null;
  }

  if (roles.length > 0 && !roles.includes(user.role)) {
    const dashMap = { admin: '/dashboard/admin', teacher: '/dashboard/teacher', student: '/dashboard/student' };
    window.location.replace(dashMap[user.role] || '/dashboard');
    return null;
  }

  return children;
}

export default ProtectedRoute;
