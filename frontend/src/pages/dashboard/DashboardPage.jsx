/**
 * ============================================================================
 * pages/dashboard/DashboardPage.jsx — Phase 1 Dashboard Placeholder
 * ============================================================================
 * Serves as the stub for all dashboard routes during Phase 1.
 * Will be replaced by real dashboard components in Phase 2+.
 *
 * For stub routes (e.g. /students), renders a "Migration Pending" card
 * so the router works end-to-end without crashing.
 * ============================================================================
 */

import { useAuth } from '@/hooks/useAuth';
import Card from '@/components/ui/Card';

function DashboardPage({ role, stub }) {
  const { user, isAdmin, isTeacher, isStudent, getRoleLabel } = useAuth();

  // Stub pages show a "coming in Phase 2" message
  if (stub) {
    return (
      <div className="max-w-2xl mx-auto pt-8">
        <Card>
          <div className="flex flex-col items-center gap-4 py-8 text-center">
            <div className="w-16 h-16 rounded-2xl bg-[var(--c-primary-bg)] flex items-center justify-center">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--c-primary)" strokeWidth="1.5">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
              </svg>
            </div>
            <div>
              <h2 className="text-lg font-bold text-[var(--c-text)] mb-1">{stub}</h2>
              <p className="text-sm text-[var(--c-muted)]">
                This page is being migrated to React in Phase 2.
              </p>
              <p className="text-xs text-[var(--c-muted)] mt-1">
                The Blade version remains fully functional at <code className="bg-gray-100 px-1.5 py-0.5 rounded text-[var(--c-primary)]">localhost:8000</code>
              </p>
            </div>
            <div className="px-4 py-2 rounded-full bg-[var(--c-primary-bg)] text-[var(--c-primary)] text-xs font-semibold uppercase tracking-wider">
              Phase 1 — Architecture Complete
            </div>
          </div>
        </Card>
      </div>
    );
  }

  // Dashboard placeholder
  const roleLabel = role || getRoleLabel();

  return (
    <div className="max-w-3xl mx-auto pt-4">
      {/* Welcome header */}
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-[var(--c-text)] tracking-tight">
          Welcome back, {user?.name?.split(' ')[0]} 👋
        </h1>
        <p className="text-sm text-[var(--c-muted)] mt-1">
          {getRoleLabel()} Dashboard · PMRS React SPA
        </p>
      </div>

      {/* Architecture success card */}
      <Card className="border-[rgba(108,92,231,0.2)] bg-gradient-to-br from-white to-[var(--c-primary-bg)]">
        <div className="flex items-start gap-5">
          <div className="w-12 h-12 rounded-2xl bg-[var(--c-primary)] flex items-center justify-center flex-shrink-0 shadow-[0_4px_14px_rgba(108,92,231,0.3)]">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </div>
          <div className="flex-1">
            <h2 className="text-[15px] font-bold text-[var(--c-text)] mb-1">
              Phase 1 Complete — Architecture Ready
            </h2>
            <p className="text-sm text-[var(--c-text-2)] leading-relaxed">
              The React SPA foundation is live. Auth context, API services, all UI components,
              routing, and layouts are set up. Phase 2 will begin migrating individual pages.
            </p>
            <div className="mt-3 flex flex-wrap gap-2">
              {['AuthContext', 'API Services', 'React Router', 'AppLayout', 'Navbar', 'Sidebar', 'UI Components'].map((tag) => (
                <span key={tag} className="px-2.5 py-1 rounded-full bg-[var(--c-primary-bg)] text-[var(--c-primary)] text-xs font-semibold border border-[rgba(108,92,231,0.2)]">
                  {tag}
                </span>
              ))}
            </div>
          </div>
        </div>
      </Card>

      {/* User info card */}
      <Card className="mt-4" title="Current Session">
        <div className="grid grid-cols-2 gap-4 text-sm">
          <div>
            <p className="text-xs text-[var(--c-muted)] font-medium uppercase tracking-wider mb-1">Name</p>
            <p className="font-semibold text-[var(--c-text)]">{user?.name || '—'}</p>
          </div>
          <div>
            <p className="text-xs text-[var(--c-muted)] font-medium uppercase tracking-wider mb-1">Email</p>
            <p className="font-semibold text-[var(--c-text)]">{user?.email || '—'}</p>
          </div>
          <div>
            <p className="text-xs text-[var(--c-muted)] font-medium uppercase tracking-wider mb-1">Role</p>
            <p className="font-semibold text-[var(--c-text)]">{getRoleLabel()}</p>
          </div>
          <div>
            <p className="text-xs text-[var(--c-muted)] font-medium uppercase tracking-wider mb-1">Status</p>
            <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-semibold border border-green-200">
              <span className="w-1.5 h-1.5 rounded-full bg-green-500" />
              Authenticated
            </span>
          </div>
        </div>
      </Card>
    </div>
  );
}

export default DashboardPage;
