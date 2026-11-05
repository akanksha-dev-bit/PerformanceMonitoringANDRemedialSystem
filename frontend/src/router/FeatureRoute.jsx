import { Link } from 'react-router-dom';
import { useAuth } from '@/hooks/useAuth';

export default function FeatureRoute({ feature, children }) {
  const { user } = useAuth();
  if (!user?.subscription?.enforcement_enabled || user.subscription.features?.includes(feature)) return children;
  return <div className="mx-auto max-w-xl px-6 py-20 text-center text-[#152238]">
    <h1 className="text-3xl font-bold">This feature is not included in your plan</h1>
    <p className="mt-4 text-slate-600">Your school administrator can review the available PMRS plans.</p>
    <Link to="/billing" className="mt-6 inline-block rounded-xl bg-[#152238] px-5 py-3 font-semibold text-white">View school plan</Link>
  </div>;
}
