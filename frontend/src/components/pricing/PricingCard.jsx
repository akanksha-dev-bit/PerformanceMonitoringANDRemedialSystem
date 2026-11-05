import React, { useState } from "react";
import { AnimatePresence, motion, useReducedMotion } from "framer-motion";
import { ArrowRight, CheckCircle2, ChevronDown, Users } from "lucide-react";

const NAVY = "#152238";
const MARIGOLD = "#F2A93B";
const BASE_URL = import.meta.env.VITE_BACKEND_URL || "http://localhost:8000";

export default function PricingCard({ plan, compact = false }) {
  const reduceMotion = useReducedMotion();
  const [showFeatures, setShowFeatures] = useState(false);
  const visibleFeatures = compact ? plan.features.slice(0, 5) : plan.features;

  const handleCta = () => {
    window.location.href = `${BASE_URL}/register?plan=${encodeURIComponent(plan.id)}`;
  };

  const ctaStyles = {
    primary: {
      backgroundColor: MARIGOLD,
      color: NAVY,
      border: "none",
    },
    secondary: {
      backgroundColor: NAVY,
      color: "#fff",
      border: "none",
    },
    outline: {
      backgroundColor: "transparent",
      color: NAVY,
      border: `2px solid ${NAVY}`,
    },
  };

  return (
    <motion.div
      initial={reduceMotion ? {} : { opacity: 0, y: 24 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true }}
      transition={{ duration: 0.5, ease: [0.16, 1, 0.3, 1] }}
      className={`relative flex h-full flex-col rounded-[24px] p-6 transition-transform duration-300 hover:-translate-y-1 ${
        plan.highlighted
          ? "border-2 bg-white shadow-xl"
          : "border border-black/[0.08] bg-white/90 shadow-[0_12px_35px_-25px_rgba(21,34,56,0.35)]"
      }`}
      style={{
        borderColor: plan.highlighted ? MARIGOLD : undefined,
        boxShadow: plan.highlighted
          ? `0 0 0 1px ${MARIGOLD}, 0 20px 60px -12px rgba(242,169,59,0.15), 0 8px 24px -8px rgba(0,0,0,0.08)`
          : undefined,
      }}
    >
      {plan.badge && (
        <span
          className="absolute -top-3.5 left-1/2 -translate-x-1/2 text-[12px] font-bold uppercase tracking-widest px-5 py-1.5 rounded-full shadow-sm whitespace-nowrap"
          style={{ backgroundColor: MARIGOLD, color: NAVY }}
        >
          {plan.badge}
        </span>
      )}

      <div className="mb-4 min-h-[66px]">
        <h3 className="mb-1.5 text-[21px] font-bold text-[#152238]">{plan.name}</h3>
        <p className="text-[14px] font-medium leading-relaxed text-[#1a1a1a]/60">
          {plan.description}
        </p>
      </div>

      <div className="mb-1 whitespace-nowrap">
        <span className="text-[clamp(29px,2.6vw,38px)] font-bold tracking-tight" style={{ color: NAVY }}>
          {plan.priceDisplay}
        </span>
        <span className="ml-1 text-[14px] font-semibold text-[#1a1a1a]/50">
          / {plan.billingPeriod}
        </span>
      </div>
      <p className="mb-4 text-[12px] font-medium text-[#1a1a1a]/45">
        Equivalent to {plan.monthlyEquivalent}/month billed annually
        {plan.setupFee > 0 && <span className="block mt-1">One-time setup fee: ₹{plan.setupFee.toLocaleString('en-IN')}</span>}
      </p>

      <div className="mb-4 flex items-center gap-2 rounded-xl bg-[#152238]/[0.045] px-3.5 py-2">
        <Users className="h-4 w-4 text-[#152238]/60" aria-hidden="true" />
        <span className="text-[13px] font-semibold text-[#152238]/75">
          Up to {plan.studentLimit.toLocaleString("en-IN")} students
        </span>
      </div>

      {compact && (
        <>
          <div className="mb-4 h-px bg-[#152238]/[0.08]" />
          <p className="mb-3 text-[11px] font-bold uppercase tracking-[0.15em] text-[#152238]/45">What’s included</p>
          <ul className="mb-4 flex flex-1 flex-col gap-2" role="list">
            {visibleFeatures.map((feature) => (
              <li key={feature} className="flex items-start gap-2.5 text-[13px] font-medium leading-snug text-[#1a1a1a]/70">
                <CheckCircle2 className="mt-0.5 h-4 w-4 flex-shrink-0" style={{ color: plan.highlighted ? MARIGOLD : NAVY }} aria-hidden="true" />
                <span>{feature}</span>
              </li>
            ))}
          </ul>
          <p className="mb-5 pl-6 text-[12px] font-semibold text-[#152238]/50">
            + {plan.features.length - visibleFeatures.length} more features
          </p>
        </>
      )}

      <button
        onClick={handleCta}
        className="w-full cursor-pointer rounded-xl py-3.5 text-[15px] font-bold tracking-wide transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#F2A93B]"
        style={ctaStyles[plan.ctaType]}
      >
        {plan.cta}
      </button>

      {!compact && (
        <div className="mt-5 border-t border-[#152238]/[0.08] pt-4">
          <button
            type="button"
            onClick={() => setShowFeatures((current) => !current)}
            aria-expanded={showFeatures}
            aria-controls={`plan-features-${plan.id}`}
            className="flex w-full items-center justify-between text-left text-[13px] font-bold text-[#152238] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#F2A93B]"
          >
            {showFeatures ? "Hide included features" : "See what’s included"}
            <ChevronDown className={`h-4 w-4 transition-transform duration-200 ${showFeatures ? "rotate-180" : ""}`} aria-hidden="true" />
          </button>
          <div id={`plan-features-${plan.id}`}>
            <AnimatePresence initial={false}>
              {showFeatures && (
                <motion.ul
                  role="list"
                  initial={reduceMotion ? false : { height: 0, opacity: 0 }}
                  animate={{ height: "auto", opacity: 1 }}
                  exit={reduceMotion ? undefined : { height: 0, opacity: 0 }}
                  transition={{ duration: reduceMotion ? 0 : 0.25 }}
                  className="mt-4 flex flex-col gap-2.5 overflow-hidden pb-1"
                >
                  {visibleFeatures.map((feature) => (
                    <li key={feature} className="flex items-start gap-2.5 text-[13px] font-medium leading-snug text-[#1a1a1a]/70">
                      <CheckCircle2 className="mt-0.5 h-4 w-4 flex-shrink-0" style={{ color: plan.highlighted ? MARIGOLD : NAVY }} aria-hidden="true" />
                      <span>{feature}</span>
                    </li>
                  ))}
                </motion.ul>
              )}
            </AnimatePresence>
          </div>
          <a href="/pricing/#compare-plans" className="mt-3 inline-flex w-fit items-center gap-1.5 text-[12px] font-semibold text-[#152238]/65 underline decoration-[#F2A93B] underline-offset-4 transition-all hover:gap-2.5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#F2A93B]">
            Compare plans <ArrowRight className="h-3.5 w-3.5" aria-hidden="true" />
          </a>
        </div>
      )}
    </motion.div>
  );
}
