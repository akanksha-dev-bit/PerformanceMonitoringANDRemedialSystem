import React from "react";
import { motion, useReducedMotion } from "framer-motion";

const NAVY = "#152238";

export default function PricingHero() {
  const reduceMotion = useReducedMotion();

  return (
    <section className="relative overflow-hidden px-6 pb-7 pt-28 text-center md:pb-8 md:pt-28">
      <div className="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div className="absolute -top-16 left-1/2 h-[420px] w-[120vw] max-w-[1300px] -translate-x-1/2 bg-[radial-gradient(ellipse_at_center,rgba(242,169,59,0.11)_0%,transparent_70%)]" />
      </div>

      <motion.div
        initial={reduceMotion ? {} : { opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.7, ease: [0.16, 1, 0.3, 1] }}
        className="relative z-10 mx-auto flex max-w-4xl flex-col items-center gap-3"
      >
        <span className="inline-flex items-center gap-2 rounded-full border border-[#F2A93B]/30 bg-[#F2A93B]/10 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-[#8a5c16]">
          <span className="h-1.5 w-1.5 rounded-full bg-[#F2A93B]" />
          Simple annual pricing
        </span>
        <h1
          className="font-medium tracking-tight leading-[1.05]"
          style={{ fontFamily: "'Instrument Serif', serif", fontSize: "clamp(38px, 5.6vw, 66px)", color: NAVY }}
        >
          School management plans for growing schools
        </h1>
        <p className="max-w-2xl text-[16px] font-medium leading-relaxed text-[#1a1a1a]/60 md:text-[18px]">
          Choose the right tools for your school today, with room to grow tomorrow.
        </p>
      </motion.div>
    </section>
  );
}
