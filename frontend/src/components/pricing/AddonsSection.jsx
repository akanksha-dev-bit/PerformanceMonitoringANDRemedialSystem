import React from "react";
import { motion, useReducedMotion } from "framer-motion";
import { Sparkles, FileBarChart, Users, Settings } from "lucide-react";
import { ADDONS, CONTACT_EMAIL } from "@/config/pricing";

const NAVY = "#152238";
const MARIGOLD = "#F2A93B";

const ICONS = {
  "ai-learning": Sparkles,
  "custom-reports": FileBarChart,
  "extra-capacity": Users,
  "integrations": Settings,
};

export default function AddonsSection() {
  const reduceMotion = useReducedMotion();

  return (
    <section className="py-20 md:py-28 px-6 bg-[#f7f6f3] border-y border-black/[0.04]">
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
            Extend your PMRS plan
          </h2>
          <p className="text-[#1a1a1a]/60 text-[16px] font-medium max-w-md mx-auto">
            Add capabilities as your institution's requirements grow.
          </p>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          {ADDONS.map((addon, i) => {
            const Icon = ICONS[addon.id] || Settings;
            return (
              <motion.div
                key={addon.id}
                initial={reduceMotion ? {} : { opacity: 0, y: 16 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ delay: i * 0.08, duration: 0.5 }}
                className="relative bg-white rounded-2xl p-6 border border-black/[0.06] shadow-sm flex flex-col"
              >
                {addon.badge && (
                  <span
                    className="absolute -top-2.5 right-4 text-[11px] font-bold uppercase tracking-widest px-3 py-1 rounded-full"
                    style={{ backgroundColor: `${MARIGOLD}20`, color: MARIGOLD }}
                  >
                    {addon.badge}
                  </span>
                )}

                <div
                  className="w-10 h-10 rounded-xl flex items-center justify-center mb-4"
                  style={{ backgroundColor: `${NAVY}08` }}
                >
                  <Icon className="w-5 h-5" style={{ color: NAVY }} aria-hidden="true" />
                </div>

                <h3 className="text-[16px] font-bold text-[#1a1a1a] mb-2">{addon.title}</h3>
                <p className="text-[14px] text-[#1a1a1a]/60 font-medium leading-relaxed flex-1">
                  {addon.description}
                </p>

                {addon.cta && (
                  <a
                    href={`mailto:${CONTACT_EMAIL}`}
                    className="mt-5 text-[14px] font-bold transition-colors hover:opacity-80"
                    style={{ color: NAVY }}
                  >
                    {addon.cta} &rarr;
                  </a>
                )}
              </motion.div>
            );
          })}
        </div>
      </motion.div>
    </section>
  );
}