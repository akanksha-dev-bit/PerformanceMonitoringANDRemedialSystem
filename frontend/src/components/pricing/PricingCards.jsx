import React from "react";
import { PLANS } from "@/config/pricing";
import PricingCard from "./PricingCard";

export default function PricingCards({ compact = false }) {
  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-6 items-stretch max-w-[1200px] mx-auto">
      {PLANS.map((plan) => (
        <PricingCard key={plan.id} plan={plan} compact={compact} />
      ))}
    </div>
  );
}