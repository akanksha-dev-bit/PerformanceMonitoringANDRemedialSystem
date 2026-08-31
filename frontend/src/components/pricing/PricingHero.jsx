import React from "react";
import { motion, useReducedMotion } from "framer-motion";

const NAVY = "#152238";

export default function PricingHero() {
  const reduceMotion = useReducedMotion();

  return (
    <section className="relative pt-36 pb-16 md:pt-44 md:pb-20 px-6 text-center overflow-hidden">
      <div className="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div className="absolute top-0 left-1/2 -translate-x-1/2 w-[120vw] max-w-[1200px] h-[500px] bg-[radial-gradient(ellipse_at_center,rgba(242,169,59,0.06)_0%,transparent_70%)]" />
      </div>

      <motion.div
        initial={reduceMotion ? {} : { opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.7, ease: [0.16, 1, 0.3, 1] }}
        className="relative z-10 mx-auto max-w-3xl flex flex-col items-center gap-5"
      >
        <h1
          className="font-medium tracking-tight leading-[1.08]"
          style={{ fontFamily: "'Instrument Serif', serif", fontSize: "clamp(36px, 6vw, 64px)", color: NAVY }}
        >
          Plans designed for growing schools
        </h1>
        <p className="text-[#1a1a1a]/60 text-[17px] md:text-[19px] font-medium leading-relaxed max-w-xl">
          Powerful academic management, performance monitoring and remedial learning tools designed around your institution.
        </p>

        <div className="mt-4 inline-flex items-center gap-2 bg-white/80 backdrop-blur border border-black/[0.06] rounded-full px-5 py-2 shadow-sm">
          <span className="text-[13px] font-bold uppercase tracking-widest" style={{ color: NAVY }}>
            Annual Billing
          </span>
        </div>
      </motion.div>
    </section>
  );
}