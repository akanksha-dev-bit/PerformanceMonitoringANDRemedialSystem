import React from "react";
import { motion, useReducedMotion } from "framer-motion";
import { ArrowRight } from "lucide-react";
import { CONTACT_EMAIL } from "@/config/pricing";

const NAVY = "#152238";
const MARIGOLD = "#F2A93B";
const BASE_URL = import.meta.env.VITE_BACKEND_URL || "http://localhost:8000";

export default function PricingCTA() {
  const reduceMotion = useReducedMotion();

  return (
    <section className="relative w-full bg-[#0c0d10] py-28 md:py-36 overflow-hidden px-6 border-t border-white/[0.04]">
      <div className="absolute inset-0 pointer-events-none flex items-center justify-center" aria-hidden="true">
        <div className="absolute w-[120vw] md:w-[70vw] max-w-[900px] aspect-square bg-[radial-gradient(circle_at_center,rgba(203,162,115,0.12)_0%,transparent_65%)] rounded-full blur-[100px]" />
      </div>

      <motion.div
        initial={reduceMotion ? {} : { opacity: 0, y: 24 }}
        whileInView={{ opacity: 1, y: 0 }}
        viewport={{ once: true }}
        transition={{ duration: 0.7, ease: [0.16, 1, 0.3, 1] }}
        className="relative z-10 flex flex-col items-center text-center gap-6 max-w-2xl mx-auto"
      >
        <h2
          className="font-medium tracking-tight text-white leading-[1.08]"
          style={{ fontFamily: "'Instrument Serif', serif", fontSize: "clamp(32px, 5vw, 56px)" }}
        >
          Ready to modernize your school&rsquo;s academic management?
        </h2>
        <p className="text-white/50 text-[17px] font-medium leading-relaxed max-w-lg">
          See how PMRS can help your institution manage students, monitor performance and deliver more personalized remedial support.
        </p>

        <div className="flex flex-wrap items-center justify-center gap-4 mt-4">
          <a
            href={`${BASE_URL}/register`}
            target="_blank"
            rel="noopener noreferrer"
            className="group inline-flex items-center gap-2.5 px-8 py-4 rounded-full bg-white text-[#0c0d10] font-bold text-[15px] tracking-wide hover:-translate-y-0.5 hover:shadow-[0_16px_32px_-8px_rgba(255,255,255,0.12)] transition-all duration-300"
          >
            Request a Demo
            <ArrowRight className="w-4 h-4 text-[#0c0d10]/50 group-hover:text-[#0c0d10] group-hover:translate-x-0.5 transition-all" aria-hidden="true" />
          </a>
          <a
            href="#plans"
            onClick={(e) => { e.preventDefault(); window.scrollTo({ top: 0, behavior: reduceMotion ? "auto" : "smooth" }); }}
            className="inline-flex items-center gap-2 px-8 py-4 rounded-full border border-white/20 text-white font-bold text-[15px] tracking-wide hover:bg-white/5 transition-all duration-300"
          >
            View Plans
          </a>
          <a
            href={`mailto:${CONTACT_EMAIL}`}
            className="inline-flex items-center gap-2 px-8 py-4 rounded-full border border-white/20 text-white font-bold text-[15px] tracking-wide hover:bg-white/5 transition-all duration-300"
          >
            Contact Us
          </a>
        </div>
      </motion.div>
    </section>
  );
}