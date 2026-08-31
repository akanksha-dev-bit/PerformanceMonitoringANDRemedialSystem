import React, { useState } from "react";
import { motion, AnimatePresence, useReducedMotion } from "framer-motion";
import { ChevronRight } from "lucide-react";
import { FAQ } from "@/config/pricing";

const NAVY = "#152238";
const MARIGOLD = "#F2A93B";

function FAQItem({ item, isOpen, onToggle }) {
  const reduceMotion = useReducedMotion();

  return (
    <div className="border-b border-black/[0.06]">
      <button
        onClick={onToggle}
        className="w-full flex items-center justify-between py-5 text-left cursor-pointer group"
        aria-expanded={isOpen}
      >
        <span className="text-[16px] font-bold text-[#1a1a1a] pr-6 group-hover:opacity-80 transition-opacity">
          {item.question}
        </span>
        <motion.span
          animate={{ rotate: isOpen ? 90 : 0 }}
          transition={{ duration: reduceMotion ? 0 : 0.2 }}
          className="flex-shrink-0"
        >
          <ChevronRight className="w-5 h-5 text-[#1a1a1a]/30" aria-hidden="true" />
        </motion.span>
      </button>

      <AnimatePresence initial={false}>
        {isOpen && (
          <motion.div
            initial={reduceMotion ? { height: "auto" } : { height: 0, opacity: 0 }}
            animate={{ height: "auto", opacity: 1 }}
            exit={reduceMotion ? {} : { height: 0, opacity: 0 }}
            transition={{ duration: reduceMotion ? 0 : 0.3, ease: [0.16, 1, 0.3, 1] }}
            className="overflow-hidden"
          >
            <p className="pb-5 text-[15px] text-[#1a1a1a]/60 font-medium leading-relaxed max-w-2xl">
              {item.answer}
            </p>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
}

export default function PricingFAQ() {
  const [openIndex, setOpenIndex] = useState(null);
  const reduceMotion = useReducedMotion();

  return (
    <section className="py-20 md:py-28 px-6 bg-[#f7f6f3] border-y border-black/[0.04]">
      <motion.div
        initial={reduceMotion ? {} : { opacity: 0, y: 20 }}
        whileInView={{ opacity: 1, y: 0 }}
        viewport={{ once: true }}
        transition={{ duration: 0.6 }}
        className="mx-auto max-w-[740px]"
      >
        <div className="text-center mb-12">
          <h2
            className="font-medium tracking-tight mb-3"
            style={{ fontFamily: "'Instrument Serif', serif", fontSize: "clamp(28px, 4vw, 44px)", color: NAVY }}
          >
            Frequently asked questions
          </h2>
        </div>

        <div className="bg-white rounded-2xl border border-black/[0.06] shadow-sm px-6 md:px-8">
          {FAQ.map((item, index) => (
            <FAQItem
              key={index}
              item={item}
              isOpen={openIndex === index}
              onToggle={() => setOpenIndex(openIndex === index ? null : index)}
            />
          ))}
        </div>
      </motion.div>
    </section>
  );
}