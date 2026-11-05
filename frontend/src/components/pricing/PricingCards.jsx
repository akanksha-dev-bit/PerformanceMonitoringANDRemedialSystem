import React from "react";
import { FEATURE_LABELS, PLAN_PRESENTATION } from "@/config/pricing";
import { usePublicPlans } from "@/hooks/usePublicPlans";
import PricingCard from "./PricingCard";

export default function PricingCards({ compact = false }) {
  const { plans, error } = usePublicPlans();
  if (error) return <p className="mx-auto max-w-xl rounded-2xl bg-white p-6 text-center text-[#152238]" role="alert">Plan details are temporarily unavailable. Please contact the PMRS team for current pricing.</p>;
  if (!plans) return <p className="py-12 text-center text-[#152238]/60">Loading current plans…</p>;

  return (
    <div className="mx-auto grid max-w-[1360px] grid-cols-1 items-stretch gap-5 md:grid-cols-2 lg:grid-cols-3 lg:gap-6">
      {plans.map((plan) => (
        <PricingCard key={plan.slug} compact={compact} plan={{
          ...PLAN_PRESENTATION[plan.slug],
          id: plan.slug,
          name: plan.name,
          priceDisplay: `₹${plan.annual_price_inr.toLocaleString('en-IN')}`,
          billingPeriod: 'year',
          monthlyEquivalent: `₹${Math.round(plan.annual_price_inr / 12).toLocaleString('en-IN')}`,
          studentLimit: plan.student_limit,
          setupFee: plan.setup_fee_inr,
          features: plan.features.map((feature) => FEATURE_LABELS[feature] || feature),
        }} />
      ))}
    </div>
  );
}
