import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import api from '@/services/api';
import { useAuthContext } from '@/context/AuthContext';
import { usePublicPlans } from '@/hooks/usePublicPlans';

const money = (amount) => `₹${Number(amount).toLocaleString('en-IN')}`;

function loadCheckout() {
  if (window.Razorpay) return Promise.resolve();
  return new Promise((resolve, reject) => {
    const script = document.createElement('script');
    script.src = 'https://checkout.razorpay.com/v1/checkout.js';
    script.async = true;
    script.onload = resolve;
    script.onerror = () => reject(new Error('The secure checkout could not load. Please retry.'));
    document.body.appendChild(script);
  });
}

export default function BillingPage() {
  const { user, fetchUser } = useAuthContext();
  const { plans, error: planError } = usePublicPlans();
  const [history, setHistory] = useState([]);
  const [busy, setBusy] = useState(false);
  const [prepared, setPrepared] = useState(null);
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');
  const subscription = user?.subscription;
  const isAdmin = user?.role === 'admin';

  useEffect(() => {
    if (isAdmin) api.get('/api/payments').then(({ data }) => setHistory(data)).catch(() => {});
  }, [isAdmin]);

  async function reviewCheckout(plan) {
    setBusy(true);
    setError('');
    setMessage('');
    try {
      if (!subscription?.checkout_enabled) throw new Error('Test checkout is currently disabled. Contact PMRS for assistance.');
      const { data: order } = await api.post('/api/payments/order', { plan: plan.slug });
      if (!order.key_id?.startsWith('rzp_test_')) throw new Error('Only Razorpay Test Mode checkout is permitted.');
      setPrepared({ plan, order });
    } catch (err) {
      setError(err.response?.data?.message || err.message || 'Unable to prepare test checkout.');
    } finally {
      setBusy(false);
    }
  }

  async function checkout() {
    if (!prepared) return;
    const { plan, order } = prepared;
    setBusy(true);
    setError('');
    try {
      await loadCheckout();
      const instance = new window.Razorpay({
        key: order.key_id,
        amount: order.amount,
        currency: order.currency,
        order_id: order.order_id,
        name: 'PMRS',
        description: `${plan.name} annual plan · test payment`,
        prefill: { name: user?.name, email: user?.email },
        theme: { color: '#152238' },
        handler: async (result) => {
          try {
            await api.post('/api/payments/verify', result);
            await fetchUser();
            const { data } = await api.get('/api/payments');
            setHistory(data);
            setMessage('Test payment verified. Your plan is active.');
          } catch (err) {
            setError(err.response?.data?.message || 'We could not verify this payment yet. Please contact PMRS with the payment reference.');
          } finally {
            setBusy(false);
          }
        },
        modal: { ondismiss: () => setBusy(false) },
      });
      instance.on('payment.failed', () => {
        setError('The test payment did not complete. Please retry.');
        setBusy(false);
      });
      setPrepared(null);
      instance.open();
    } catch (err) {
      setError(err.message || 'Unable to start test checkout.');
      setBusy(false);
    }
  }

  return (
    <div className="mx-auto max-w-6xl px-5 py-10 text-[#152238]">
      <div className="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
          <p className="text-sm font-semibold uppercase tracking-widest text-amber-600">School billing</p>
          <h1 className="mt-2 text-3xl font-bold">Plan and subscription</h1>
          <p className="mt-2 text-slate-600">Manage your school’s annual PMRS plan.</p>
        </div>
        <Link to="/dashboard" className="rounded-xl border border-slate-300 bg-white px-4 py-2 font-semibold">Back to dashboard</Link>
      </div>

      <div className="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div className="flex flex-wrap items-center justify-between gap-3">
          <div>
            <h2 className="text-lg font-bold">{subscription?.plan_name || 'Existing school account'}</h2>
            <p className="text-sm text-slate-600">Status: <span className="font-semibold capitalize">{subscription?.status || 'Loading'}</span></p>
          </div>
          {subscription?.plan_slug && <span className="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-900">{subscription.checkout_enabled ? 'Razorpay Test Mode' : 'Checkout disabled'}</span>}
        </div>
        {subscription?.ends_at && <p className="mt-3 text-sm text-slate-600">Current period ends {new Date(subscription.ends_at).toLocaleDateString('en-IN')}.</p>}
        {subscription?.status === 'provisional' && <p className="mt-3 text-sm text-amber-800">This is temporary unpaid Starter access while billing is being prepared. Premium features remain unavailable when plan enforcement is enabled.</p>}
        {subscription?.student_limit && <p className="mt-2 text-sm text-slate-600">Active students: {subscription.student_usage} / {subscription.student_limit}</p>}
      </div>

      {message && <div role="status" className="mb-5 rounded-xl bg-green-50 p-4 text-green-800">{message}</div>}
      {new URLSearchParams(window.location.search).get('reason') === 'PLAN_FEATURE_REQUIRED' &&
        <div role="status" className="mb-5 rounded-xl bg-amber-50 p-4 text-amber-900">That feature is available with a higher school plan.</div>}
      {new URLSearchParams(window.location.search).get('reason') === 'SUBSCRIPTION_REQUIRED' &&
        <div role="status" className="mb-5 rounded-xl bg-amber-50 p-4 text-amber-900">Activate or renew your school plan to continue.</div>}
      {error && <div role="alert" className="mb-5 rounded-xl bg-red-50 p-4 text-red-800">{error}</div>}
      {planError && <div role="alert" className="mb-5 rounded-xl bg-amber-50 p-4 text-amber-900">Plan details are temporarily unavailable.</div>}

      {isAdmin ? (
        <div className="grid gap-5 md:grid-cols-3">
          {(plans || []).map((plan) => {
            const differentActivePlan = subscription?.status === 'active' && subscription.plan_slug !== plan.slug;
            return <article key={plan.slug} className={`rounded-2xl border bg-white p-6 shadow-sm ${subscription?.plan_slug === plan.slug ? 'border-amber-400' : 'border-slate-200'}`}>
              <h2 className="text-xl font-bold">{plan.name}</h2>
              <p className="mt-4 text-3xl font-bold">{money(plan.annual_price_inr)}<span className="text-sm font-medium text-slate-500"> / year</span></p>
              <p className="mt-2 text-sm text-slate-600">One-time setup: {money(plan.setup_fee_inr)} · Up to {plan.student_limit.toLocaleString('en-IN')} active students</p>
              <button type="button" onClick={() => reviewCheckout(plan)} disabled={busy || !subscription?.checkout_enabled || differentActivePlan}
                className="mt-6 w-full rounded-xl bg-[#152238] px-4 py-3 font-semibold text-white disabled:cursor-not-allowed disabled:opacity-45">
                {busy ? 'Preparing test order…' : subscription?.status === 'active' && subscription.plan_slug === plan.slug ? 'Review test renewal' : 'Review test checkout'}
              </button>
              {differentActivePlan && <p className="mt-2 text-xs text-slate-500">Contact PMRS for a mid-term plan change.</p>}
            </article>;
          })}
        </div>
      ) : <p className="rounded-xl bg-white p-6 text-slate-600">Your school administrator manages billing.</p>}

      {prepared && <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-4">
        <div role="dialog" aria-modal="true" aria-labelledby="checkout-review-title" className="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
          <p className="text-xs font-bold uppercase tracking-widest text-amber-700">Razorpay Test Mode</p>
          <h2 id="checkout-review-title" className="mt-2 text-2xl font-bold">Review {prepared.plan.name} payment</h2>
          <div className="mt-6 space-y-3 border-y border-slate-200 py-5 text-sm">
            <div className="flex justify-between"><span>Annual plan</span><strong>{money(prepared.order.annual_amount_paise / 100)}</strong></div>
            <div className="flex justify-between"><span>One-time setup</span><strong>{money(prepared.order.setup_amount_paise / 100)}</strong></div>
            <div className="flex justify-between text-lg"><span className="font-bold">Total payable</span><strong>{money(prepared.order.amount / 100)}</strong></div>
          </div>
          <p className="mt-4 text-xs text-slate-600">This is a test transaction. The plan activates only after Laravel verifies the captured payment.</p>
          <div className="mt-6 flex gap-3">
            <button type="button" onClick={() => setPrepared(null)} disabled={busy} className="flex-1 rounded-xl border border-slate-300 px-4 py-3 font-semibold">Cancel</button>
            <button type="button" onClick={checkout} disabled={busy} className="flex-1 rounded-xl bg-[#152238] px-4 py-3 font-semibold text-white disabled:opacity-50">{busy ? 'Opening…' : 'Continue to test checkout'}</button>
          </div>
        </div>
      </div>}

      {isAdmin && <section className="mt-10">
        <h2 className="mb-4 text-xl font-bold">Payment history</h2>
        <div className="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
          <table className="w-full min-w-[520px] text-left text-sm">
            <thead className="bg-slate-50 text-slate-500"><tr><th className="p-4">Date</th><th className="p-4">Plan</th><th className="p-4">Amount</th><th className="p-4">Status</th></tr></thead>
            <tbody>{history.map((payment) => <tr key={payment.id} className="border-t border-slate-100"><td className="p-4">{new Date(payment.created_at).toLocaleDateString('en-IN')}</td><td className="p-4 capitalize">{payment.plan_slug}</td><td className="p-4">{money(payment.total_amount_paise / 100)}</td><td className="p-4 capitalize">{payment.status}</td></tr>)}</tbody>
          </table>
          {!history.length && <p className="p-5 text-sm text-slate-500">No payments yet.</p>}
        </div>
      </section>}
    </div>
  );
}
