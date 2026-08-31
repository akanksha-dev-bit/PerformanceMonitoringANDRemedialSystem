import React from "react";
import { motion, useReducedMotion } from "framer-motion";
import { FEATURE_COMPARISON, USER_LIMITS, PLANS } from "@/config/pricing";

const NAVY = "#152238";
const MARIGOLD = "#F2A93B";

function CellValue({ value }) {
  if (value === true) return <span className="text-[16px] font-bold" style={{ color: NAVY }}>&#10003;</span>;
  if (value === false) return <span className="text-[#1a1a1a]/20 text-[14px] font-medium">&mdash;</span>;
  return <span className="text-[11px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-full" style={{ backgroundColor: `${MARIGOLD}20`, color: MARIGOLD }}>{value}</span>;
}

export default function FeatureComparison() {
  const reduceMotion = useReducedMotion();
  const planNames = PLANS.map((p) => p.name);

  return (
    <section className="py-20 md:py-28 px-6">
      <motion.div
        initial={reduceMotion ? {} : { opacity: 0, y: 20 }}
        whileInView={{ opacity: 1, y: 0 }}
        viewport={{ once: true }}
        transition={{ duration: 0.6 }}
        className="mx-auto max-w-[1100px]"
      >
        <div className="text-center mb-14">
          <h2
            className="font-medium tracking-tight mb-3"
            style={{ fontFamily: "'Instrument Serif', serif", fontSize: "clamp(28px, 4vw, 44px)", color: NAVY }}
          >
            Compare plans
          </h2>
          <p className="text-[#1a1a1a]/60 text-[16px] font-medium max-w-md mx-auto">
            Choose the capabilities your institution needs.
          </p>
        </div>

        {/* Feature Comparison Table */}
        <div className="overflow-x-auto -mx-6 px-6">
          <table className="w-full min-w-[640px] border-collapse">
            <thead>
              <tr className="border-b-2 border-black/10">
                <th className="text-left py-4 pr-4 text-[14px] font-bold text-[#1a1a1a]/40 uppercase tracking-wider w-[40%]">Feature</th>
                {planNames.map((name, i) => (
                  <th key={name} className="text-center py-4 px-3 text-[14px] font-bold uppercase tracking-wider" style={{ color: PLANS[i].highlighted ? MARIGOLD : NAVY }}>
                    {name}
                  </th>
                ))}
              </tr>
            </thead>
            <tbody>
              {FEATURE_COMPARISON.map((group) => (
                <React.Fragment key={group.category}>
                  <tr>
                    <td colSpan={4} className="pt-8 pb-3 text-[13px] font-bold uppercase tracking-widest" style={{ color: MARIGOLD }}>
                      {group.category}
                    </td>
                  </tr>
                  {group.features.map((feature) => (
                    <tr key={feature.name} className="border-b border-black/[0.04] hover:bg-black/[0.01] transition-colors">
                      <td className="py-3.5 pr-4 text-[14px] font-medium text-[#1a1a1a]/70">{feature.name}</td>
                      <td className="py-3.5 px-3 text-center"><CellValue value={feature.starter} /></td>
                      <td className="py-3.5 px-3 text-center"><CellValue value={feature.professional} /></td>
                      <td className="py-3.5 px-3 text-center"><CellValue value={feature.enterprise} /></td>
                    </tr>
                  ))}
                </React.Fragment>
              ))}
            </tbody>
          </table>
        </div>

        {/* User Limits Table */}
        <div className="mt-16">
          <h3
            className="font-medium tracking-tight mb-6 text-center"
            style={{ fontFamily: "'Instrument Serif', serif", fontSize: "clamp(22px, 3vw, 32px)", color: NAVY }}
          >
            User &amp; seat limits
          </h3>
          <div className="overflow-x-auto -mx-6 px-6">
            <table className="w-full min-w-[500px] border-collapse">
              <thead>
                <tr className="border-b-2 border-black/10">
                  <th className="text-left py-3 pr-4 text-[13px] font-bold text-[#1a1a1a]/40 uppercase tracking-wider">Plan</th>
                  <th className="text-center py-3 px-3 text-[13px] font-bold text-[#1a1a1a]/40 uppercase tracking-wider">Students</th>
                  <th className="text-center py-3 px-3 text-[13px] font-bold text-[#1a1a1a]/40 uppercase tracking-wider">Teachers</th>
                  <th className="text-center py-3 px-3 text-[13px] font-bold text-[#1a1a1a]/40 uppercase tracking-wider">Admins</th>
                </tr>
              </thead>
              <tbody>
                {USER_LIMITS.map((row) => (
                  <tr key={row.plan} className="border-b border-black/[0.04]">
                    <td className="py-3.5 pr-4 text-[14px] font-bold" style={{ color: NAVY }}>{row.plan}</td>
                    <td className="py-3.5 px-3 text-center text-[14px] font-medium text-[#1a1a1a]/70">{row.students}</td>
                    <td className="py-3.5 px-3 text-center text-[14px] font-medium text-[#1a1a1a]/70">{row.teachers}</td>
                    <td className="py-3.5 px-3 text-center text-[14px] font-medium text-[#1a1a1a]/70">{row.admins}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </motion.div>
    </section>
  );
}