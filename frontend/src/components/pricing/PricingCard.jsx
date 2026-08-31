import React from "react";
import { motion, useReducedMotion } from "framer-motion";
import { CheckCircle2, Users } from "lucide-react";
import { CONTACT_EMAIL } from "@/config/pricing";

const NAVY = "#152238";
const MARIGOLD = "#F2A93B";
const BASE_URL = import.meta.env.VITE_BACKEND_URL || "http://localhost:8000";

export default function PricingCard({ plan, compact = false }) {
  const reduceMotion = useReducedMotion();

  const handleCta = () => {
    if (plan.ctaType === "outline") {
      window.location.href = `mailto:${CONTACT_EMAIL}`;
    } else {
      window.location.href = `${BASE_URL}/register`;
    }
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
      className={`relative flex flex-col rounded-2xl p-8 h-full ${
        plan.highlighted
          ? "bg-white border-2 shadow-xl"
          : "bg-white/80 border border-black/[0.06] shadow-md"
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

      <div className="mb-6">
        <h3 className="text-[20px] font-bold text-[#1a1a1a] mb-2">{plan.name}</h3>
        <p className="text-[14px] text-[#1a1a1a]/60 font-medium leading-relaxed">
          {plan.description}
        </p>
      </div>

      <div className="mb-1">
        <span className="text-[36px] font-bold tracking-tight" style={{ color: NAVY }}>
          {plan.priceDisplay}
        </span>
        <span className="text-[15px] text-[#1a1a1a]/50 font-semibold ml-1.5">
          / {plan.billingPeriod}
        </span>
      </div>
      <p className="text-[13px] text-[#1a1a1a]/40 font-medium mb-6">
        Equivalent to {plan.monthlyEquivalent}/month billed annually
      </p>

      <div className="flex items-center gap-2 mb-6 pb-6 border-b border-black/[0.06]">
        <Users className="w-4 h-4 text-[#1a1a1a]/40" aria-hidden="true" />
        <span className="text-[14px] font-semibold text-[#1a1a1a]/70">
          Up to {plan.studentLimit.toLocaleString("en-IN")} students
        </span>
      </div>

      {!compact && (
        <ul className="flex flex-col gap-3 mb-8 flex-1" role="list">
          {plan.features.map((feature) => (
            <li key={feature} className="flex items-start gap-2.5 text-[14px] font-medium text-[#1a1a1a]/70">
              <CheckCircle2
                className="w-4 h-4 mt-0.5 flex-shrink-0"
                style={{ color: plan.highlighted ? MARIGOLD : NAVY }}
                aria-hidden="true"
              />
              <span>{feature}</span>
            </li>
          ))}
        </ul>
      )}

      {compact && (
        <ul className="flex flex-col gap-2.5 mb-8 flex-1" role="list">
          {plan.features.slice(0, 5).map((feature) => (
            <li key={feature} className="flex items-start gap-2.5 text-[14px] font-medium text-[#1a1a1a]/70">
              <CheckCircle2
                className="w-4 h-4 mt-0.5 flex-shrink-0"
                style={{ color: plan.highlighted ? MARIGOLD : NAVY }}
                aria-hidden="true"
              />
              <span>{feature}</span>
            </li>
          ))}
          {plan.features.length > 5 && (
            <li className="text-[13px] font-semibold text-[#1a1a1a]/40 pl-6">
              + {plan.features.length - 5} more features
            </li>
          )}
        </ul>
      )}

      <button
        onClick={handleCta}
        className="w-full py-3.5 rounded-xl text-[15px] font-bold tracking-wide transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg cursor-pointer"
        style={ctaStyles[plan.ctaType]}
      >
        {plan.cta}
      </button>
    </motion.div>
  );
}