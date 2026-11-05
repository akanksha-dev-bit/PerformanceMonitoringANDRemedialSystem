import React, { useState } from "react";
import { AnimatePresence, motion, useReducedMotion } from "framer-motion";
import { ArrowUpRight, Plus } from "lucide-react";
import { CONTACT_EMAIL, FAQ } from "@/config/pricing";

const categories = [
  { id: "all", label: "All questions" },
  { id: "billing", label: "Plans & billing" },
  { id: "onboarding", label: "Getting started" },
  { id: "platform", label: "Platform" },
];

function FAQItem({ item, index, isOpen, onToggle, reduceMotion }) {
  const answerId = `pricing-faq-answer-${index}`;
  const questionId = `pricing-faq-question-${index}`;

  return (
    <motion.article
      layout={!reduceMotion}
      initial={reduceMotion ? false : { opacity: 0, y: 8 }}
      animate={{ opacity: 1, y: 0 }}
      exit={reduceMotion ? undefined : { opacity: 0, y: -8 }}
      transition={{ duration: reduceMotion ? 0 : 0.2 }}
      className={`group overflow-hidden rounded-[22px] border transition-colors duration-300 ${
        isOpen
          ? "border-[#F2A93B]/50 bg-white shadow-[0_18px_45px_-30px_rgba(21,34,56,0.38)]"
          : "border-[#152238]/[0.08] bg-white/75 hover:border-[#152238]/20 hover:bg-white"
      }`}
    >
      <h3>
        <button
          id={questionId}
          type="button"
          onClick={onToggle}
          aria-expanded={isOpen}
          aria-controls={answerId}
          className="flex w-full items-center gap-4 px-5 py-5 text-left outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-[#F2A93B] sm:gap-5 sm:px-7 sm:py-6"
        >
          <span className="shrink-0 text-[12px] font-bold tracking-[0.16em] text-[#152238]/35">
            {String(index + 1).padStart(2, "0")}
          </span>
          <span className="min-w-0 flex-1 text-[16px] font-semibold leading-snug text-[#152238] sm:text-[18px]">
            {item.question}
          </span>
          <span
            className={`flex h-9 w-9 shrink-0 items-center justify-center rounded-full transition-colors duration-300 ${
              isOpen ? "bg-[#F2A93B] text-[#152238]" : "bg-[#152238]/[0.06] text-[#152238] group-hover:bg-[#152238]/10"
            }`}
            aria-hidden="true"
          >
            <motion.span animate={{ rotate: isOpen ? 45 : 0 }} transition={{ duration: reduceMotion ? 0 : 0.25 }}>
              <Plus className="h-5 w-5" strokeWidth={1.8} />
            </motion.span>
          </span>
        </button>
      </h3>
      <motion.div
        id={answerId}
        role="region"
        aria-labelledby={questionId}
        aria-hidden={!isOpen}
        initial={false}
        animate={{ height: isOpen ? "auto" : 0, opacity: isOpen ? 1 : 0 }}
        transition={{ duration: reduceMotion ? 0 : 0.3, ease: [0.16, 1, 0.3, 1] }}
      >
        <p className="ml-[50px] max-w-2xl border-t border-[#152238]/[0.07] pb-6 pt-4 pr-5 text-[15px] leading-[1.75] text-[#152238]/65 sm:ml-[67px] sm:pb-7 sm:pr-10">
          {item.answer}
        </p>
      </motion.div>
    </motion.article>
  );
}

export default function PricingFAQ() {
  const [category, setCategory] = useState("all");
  const [openQuestions, setOpenQuestions] = useState([FAQ[0]?.question]);
  const reduceMotion = useReducedMotion();
  const visibleQuestions = FAQ.filter((item) => category === "all" || item.category === category);
  const allVisibleOpen = visibleQuestions.every((item) => openQuestions.includes(item.question));

  const selectCategory = (nextCategory) => {
    setCategory(nextCategory);
    const firstQuestion = FAQ.find((item) => nextCategory === "all" || item.category === nextCategory);
    setOpenQuestions(firstQuestion ? [firstQuestion.question] : []);
  };

  const toggleQuestion = (question) => {
    setOpenQuestions((current) =>
      current.includes(question) ? current.filter((item) => item !== question) : [...current, question]
    );
  };

  return (
    <section id="faq" aria-labelledby="pricing-faq-title" className="relative overflow-hidden border-y border-[#152238]/[0.06] bg-[#f5f3ee] px-6 py-20 md:py-28">
      <div className="pointer-events-none absolute -right-32 -top-40 h-[420px] w-[420px] rounded-full bg-[#F2A93B]/[0.09] blur-3xl" aria-hidden="true" />
      <div className="relative mx-auto grid max-w-[1200px] gap-12 lg:grid-cols-[0.8fr_1.2fr] lg:gap-20">
        <motion.div
          initial={reduceMotion ? false : { opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true, amount: 0.2 }}
          transition={{ duration: 0.55 }}
          className="lg:pt-3"
        >
          <span className="mb-5 inline-flex items-center gap-2 text-[12px] font-bold uppercase tracking-[0.2em] text-[#9a691c]">
            <span className="h-1.5 w-1.5 rounded-full bg-[#F2A93B]" />
            Good to know
          </span>
          <h2
            id="pricing-faq-title"
            className="max-w-[440px] text-[clamp(38px,4.4vw,62px)] leading-[1.04] tracking-[-0.025em] text-[#152238]"
            style={{ fontFamily: "'Instrument Serif', serif" }}
          >
            Frequently asked questions<span className="text-[#F2A93B]">.</span>
          </h2>
          <p className="mt-6 max-w-[390px] text-[16px] leading-[1.7] text-[#152238]/60">
            Everything you need to know about plans, onboarding, and what PMRS includes.
          </p>

          <div className="mt-9 max-w-[390px] rounded-[24px] bg-[#152238] p-6 text-white shadow-[0_24px_48px_-30px_rgba(21,34,56,0.7)] sm:p-7">
            <span className="text-[11px] font-bold uppercase tracking-[0.18em] text-[#F2A93B]">Still have a question?</span>
            <p className="mt-3 text-[18px] font-medium leading-snug">Let’s talk about what your school needs.</p>
            <a
              href={`mailto:${CONTACT_EMAIL}?subject=PMRS%20pricing%20question`}
              className="mt-6 inline-flex items-center gap-2 border-b border-[#F2A93B] pb-1 text-[14px] font-bold text-[#F2A93B] transition-all hover:gap-3 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#F2A93B]"
            >
              Contact our team <ArrowUpRight className="h-4 w-4" aria-hidden="true" />
            </a>
          </div>
        </motion.div>

        <div className="min-w-0">
          <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div className="flex flex-wrap gap-2" role="group" aria-label="Filter frequently asked questions">
              {categories.map((item) => (
                <button
                  key={item.id}
                  type="button"
                  onClick={() => selectCategory(item.id)}
                  aria-pressed={category === item.id}
                  className={`rounded-full border px-4 py-2 text-[12px] font-semibold transition-all duration-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#F2A93B] sm:text-[13px] ${
                    category === item.id
                      ? "border-[#152238] bg-[#152238] text-white shadow-[0_6px_16px_-8px_rgba(21,34,56,0.7)]"
                      : "border-[#152238]/10 bg-white/75 text-[#152238]/65 hover:border-[#152238]/25 hover:text-[#152238]"
                  }`}
                >
                  {item.label}
                </button>
              ))}
            </div>
            <button
              type="button"
              onClick={() => setOpenQuestions(allVisibleOpen ? [] : visibleQuestions.map((item) => item.question))}
              className="text-[12px] font-bold text-[#152238]/65 underline decoration-[#F2A93B] underline-offset-4 transition-colors hover:text-[#152238] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#F2A93B]"
            >
              {allVisibleOpen ? "Collapse all" : "Expand all"}
            </button>
          </div>

          <motion.div layout={!reduceMotion} className="space-y-3">
            <AnimatePresence initial={false}>
              {visibleQuestions.map((item) => {
                const index = FAQ.indexOf(item);
                return (
                  <FAQItem
                    key={item.question}
                    item={item}
                    index={index}
                    isOpen={openQuestions.includes(item.question)}
                    onToggle={() => toggleQuestion(item.question)}
                    reduceMotion={reduceMotion}
                  />
                );
              })}
            </AnimatePresence>
          </motion.div>
        </div>
      </div>
    </section>
  );
}
