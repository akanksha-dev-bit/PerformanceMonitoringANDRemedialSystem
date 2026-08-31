import React from "react";
import { motion, useReducedMotion } from "framer-motion";
import { PRICING_STEPS } from "@/config/pricing";

const NAVY = "#152238";
const MARIGOLD = "#F2A93B";

export default function PricingProcess() {
  const reduceMotion = useReducedMotion();

  return (
    <section className="py-20 md:py-28 px-6">
      <motion.div
        initial={reduceMotion ? {} : { opacity: 0, y: 20 }}
        whileInView={{ opacity: 1, y: 0 }}
        viewport={{ once: true }}
        transition={{ duration: 0.6 }}
        className="mx-auto max-w-[1100px]"
      >
        <div className="text-center mb-16">
          <h2
            className="font-medium tracking-tight mb-3"
            style={{ fontFamily: "'Instrument Serif', serif", fontSize: "clamp(28px, 4vw, 44px)", color: NAVY }}
          >
            How pricing works
          </h2>
        </div>

        <div className="grid md:grid-cols-3 gap-16 md:gap-12 relative">
          <div
            className="hidden md:block absolute top-7 left-[10%] right-[10%] h-px"
            style={{ background: `linear-gradient(90deg, transparent, ${MARIGOLD}30, transparent)` }}
            aria-hidden="true"
          />

          {PRICING_STEPS.map((s, i) => (
            <motion.div
              key={s.step}
              initial={reduceMotion ? {} : { opacity: 0, y: 16 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: i * 0.12, duration: 0.5 }}
              className="flex flex-col gap-5 items-center text-center relative z-10"
            >
              <div className="relative flex items-center justify-center w-14 h-14 bg-[#faf9f6]">
                <svg
                  className="absolute inset-0 w-full h-full"
                  viewBox="0 0 56 56"
                  fill="none"
                  stroke={`${MARIGOLD}50`}
                  strokeWidth="1"
                  aria-hidden="true"
                >
                  <circle cx="28" cy="28" r="27" />
                </svg>
                <span
                  className="text-3xl"
                  style={{ fontFamily: "'Instrument Serif', serif", color: MARIGOLD }}
                >
                  {s.step}
                </span>
              </div>
              <h3 className="font-bold text-[18px]" style={{ color: NAVY }}>{s.title}</h3>
              <p className="text-[#1a1a1a]/60 text-[15px] font-medium leading-relaxed max-w-xs">
                {s.description}
              </p>
            </motion.div>
          ))}
        </div>
      </motion.div>
    </section>
  );
}