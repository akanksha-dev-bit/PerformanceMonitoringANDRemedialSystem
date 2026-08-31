import React, { useEffect, useState } from "react";
import { motion, useReducedMotion } from "framer-motion";
import { ChevronRight, ArrowRight, Linkedin, Instagram, Mail } from "lucide-react";
import { PRICING_NOTES, CONTACT_EMAIL } from "@/config/pricing";
import PricingHero from "@/components/pricing/PricingHero";
import PricingCards from "@/components/pricing/PricingCards";
import FeatureComparison from "@/components/pricing/FeatureComparison";
import AddonsSection from "@/components/pricing/AddonsSection";
import PricingProcess from "@/components/pricing/PricingProcess";
import PricingFAQ from "@/components/pricing/PricingFAQ";
import PricingCTA from "@/components/pricing/PricingCTA";

const NAVY = "#152238";
const MARIGOLD = "#F2A93B";
const BASE_URL = import.meta.env.VITE_BACKEND_URL || "http://localhost:8000";

function NavButton({ children }) {
  return (
    <span
      className="group flex items-center gap-1 px-5 py-2.5 rounded-full text-[14px] font-semibold text-white shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-[1px]"
      style={{ background: `linear-gradient(135deg, ${NAVY}, #1e3654)` }}
    >
      {children}
    </span>
  );
}

function FooterLink({ children, href = "#" }) {
  const isExternal = href.startsWith("http") || href.startsWith("mailto:");
  return (
    <a
      href={href}
      target={isExternal ? "_blank" : undefined}
      rel={isExternal ? "noopener noreferrer" : undefined}
      className="relative inline-block w-fit text-[14px] font-semibold text-[#19181a]/60 hover:text-[#19181a] transition-colors group"
    >
      {children}
      <span
        className="absolute left-0 -bottom-0.5 w-full h-[2px] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"
        style={{ backgroundColor: MARIGOLD }}
        aria-hidden="true"
      />
    </a>
  );
}

function SEOHead() {
  useEffect(() => {
    document.title = "PMRS Pricing | School Management & Performance Monitoring";

    const setMeta = (name, content, attr = "name") => {
      let el = document.querySelector(`meta[${attr}="${name}"]`);
      if (!el) {
        el = document.createElement("meta");
        el.setAttribute(attr, name);
        document.head.appendChild(el);
      }
      el.setAttribute("content", content);
    };

    setMeta("description", "Explore PMRS pricing plans for school management, attendance, marks, performance monitoring, remedial activities and academic management.");
    setMeta("og:title", "PMRS Pricing | School Management & Performance Monitoring", "property");
    setMeta("og:description", "Explore PMRS plans for modern school academic and performance management.", "property");
    setMeta("og:url", "https://pmrs.live/pricing", "property");
    setMeta("og:type", "website", "property");

    let canonical = document.querySelector('link[rel="canonical"]');
    if (!canonical) {
      canonical = document.createElement("link");
      canonical.setAttribute("rel", "canonical");
      document.head.appendChild(canonical);
    }
    canonical.setAttribute("href", "https://pmrs.live/pricing");

    return () => {
      document.title = "PMRS";
    };
  }, []);

  return null;
}

export default function PricingPage() {
  const reduceMotion = useReducedMotion();
  const [showTop, setShowTop] = useState(false);

  useEffect(() => {
    const onScroll = () => setShowTop(window.scrollY > 600);
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  return (
    <div className="relative min-h-screen w-full overflow-x-clip bg-[#faf9f6] selection:bg-[#152238] selection:text-white">
      <SEOHead />

      {/* Navigation */}
      <header>
        <nav className="fixed top-6 left-1/2 -translate-x-1/2 z-[100] w-full max-w-[1300px] px-4 md:px-6" aria-label="Main Navigation">
          <div className="flex items-center justify-between bg-white/70 backdrop-blur-2xl border border-black/5 rounded-full px-4 md:px-8 py-3 shadow-[0_8px_32px_0_rgba(21,34,56,0.06)]">
            <div className="flex items-center gap-2">
              <a href="/" className="flex items-center gap-2.5 font-bold text-xl tracking-tight" style={{ color: NAVY }} aria-label="PMRS Homepage">
                <img src="/logo.png" alt="PMRS Logo" className="h-8 md:h-9 w-auto object-contain" />
              </a>
            </div>
            <div className="flex items-center gap-3 md:gap-6">
              <div className="hidden md:flex items-center gap-6 lg:gap-8">
                {[
                  { label: "Features", href: "/#features" },
                  { label: "Pricing", href: "/pricing" },
                  { label: "Founders", href: "/#founders" },
                  { label: "For schools", href: "/#for-schools" },
                ].map((item) => (
                  <a key={item.label} href={item.href} className={`text-[14px] font-semibold transition-colors ${item.label === "Pricing" ? "text-black" : "text-[#1a1a1a]/70 hover:text-black"}`}>
                    {item.label}
                  </a>
                ))}
              </div>
              <a href={`${BASE_URL}/login`} target="_blank" rel="noopener noreferrer" aria-label="Sign in to PMRS portal">
                <NavButton>
                  Sign In
                  <ChevronRight className="w-4 h-4 text-white/70 group-hover:text-white transition-colors" aria-hidden="true" />
                </NavButton>
              </a>
            </div>
          </div>
        </nav>
      </header>

      <main>
        <PricingHero />

        <section className="px-6 pb-20 md:pb-28" id="plans">
          <PricingCards />
          <div className="text-center mt-10">
            <p className="text-[#1a1a1a]/50 text-[15px] font-medium mb-1">Need a larger plan?</p>
            <p className="text-[#1a1a1a]/40 text-[14px] font-medium">
              Custom plans are available for institutions with more than 1,000 students.{" "}
              <a href={`mailto:${CONTACT_EMAIL}`} className="font-bold hover:opacity-80 transition-opacity" style={{ color: NAVY }}>
                Contact us &rarr;
              </a>
            </p>
          </div>
        </section>

        <FeatureComparison />
        <AddonsSection />
        <PricingProcess />

        {/* Pricing Notes */}
        <section className="py-16 px-6">
          <motion.div
            initial={reduceMotion ? {} : { opacity: 0, y: 16 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="mx-auto max-w-[740px]"
          >
            <h3 className="text-[14px] font-bold uppercase tracking-widest mb-6" style={{ color: MARIGOLD }}>
              Pricing information
            </h3>
            <ul className="flex flex-col gap-3" role="list">
              {PRICING_NOTES.map((note, i) => (
                <li key={i} className="text-[14px] text-[#1a1a1a]/55 font-medium leading-relaxed pl-4 border-l-2 border-black/[0.06]">
                  {note}
                </li>
              ))}
            </ul>
          </motion.div>
        </section>

        <PricingFAQ />
        <PricingCTA />
      </main>

      {/* Footer */}
      <footer className="relative w-full bg-white pt-24 pb-12 overflow-hidden border-t border-black/5">
        <div className="absolute bottom-0 left-0 right-0 w-full flex justify-center items-end pointer-events-none z-0 overflow-hidden" aria-hidden="true">
          <h2
            className="text-[32vw] font-bold leading-[0.7] tracking-[-0.05em] text-center whitespace-nowrap uppercase"
            style={{
              backgroundImage: "radial-gradient(circle at 50% 50%, #06b6d4 0%, #3b82f6 50%, #1e3a8a 100%)",
              WebkitBackgroundClip: "text",
              backgroundClip: "text",
              color: "transparent",
              filter: "blur(24px)",
              opacity: 0.4,
              transform: "translateY(15%)",
            }}
          >
            PMRS
          </h2>
        </div>

        <div className="relative z-10 mx-auto max-w-[1200px] px-6 flex flex-col gap-16">
          <div className="grid grid-cols-1 md:grid-cols-12 gap-12 md:gap-8">
            <div className="md:col-span-4 flex flex-col gap-5">
              <div className="flex items-center gap-2">
                <img src="/logo.png" alt="PMRS Logo" className="h-8 md:h-9 w-auto object-contain" />
              </div>
              <p className="text-[#1a1a1a]/60 text-[15px] font-medium leading-relaxed max-w-sm">
                Performance Monitoring &amp; Remedial System &mdash; built for CBSE schools, one real classroom at a time.
              </p>
            </div>

            <div className="md:col-span-6 flex flex-wrap gap-x-16 gap-y-10 md:justify-center">
              {[
                { title: "Product", links: [{ l: "Features", h: "/#features" }, { l: "Pricing", h: "/pricing" }, { l: "For schools", h: "/#for-schools" }] },
                { title: "Company", links: [{ l: "Founders", h: "/#founders" }, { l: "Contact Us", h: `mailto:${CONTACT_EMAIL}` }] },
                { title: "Legal", links: [{ l: "Privacy Policy", h: "#" }, { l: "Terms of Service", h: "#" }] },
              ].map((col) => (
                <div key={col.title} className="flex flex-col gap-5">
                  <span className="text-[13px] font-bold uppercase tracking-widest text-[#19181a]/40">{col.title}</span>
                  <div className="flex flex-col gap-3">
                    {col.links.map((link) => (
                      <FooterLink key={link.l} href={link.h}>{link.l}</FooterLink>
                    ))}
                  </div>
                </div>
              ))}
            </div>

            <div className="md:col-span-2 flex flex-row md:flex-col gap-4 justify-start md:items-end">
              {[
                { Icon: Linkedin, label: "Visit PMRS on LinkedIn", href: "https://linkedin.com" },
                { Icon: Instagram, label: "Visit PMRS on Instagram", href: "https://instagram.com" },
                { Icon: Mail, label: "Contact PMRS via Email", href: `mailto:${CONTACT_EMAIL}` },
              ].map(({ Icon, label, href }) => (
                <a
                  key={label}
                  href={href}
                  target="_blank"
                  rel="noopener noreferrer"
                  aria-label={label}
                  className="w-11 h-11 rounded-xl flex items-center justify-center text-white shadow-sm hover:opacity-90 transition-opacity"
                  style={{ backgroundColor: NAVY }}
                >
                  <Icon className="w-4 h-4" aria-hidden="true" />
                </a>
              ))}
            </div>
          </div>

          <div className="flex flex-col sm:flex-row justify-between items-center gap-4 border-t border-black/10 pt-8 mt-4">
            <span className="text-[13px] font-medium text-[#19181a]/50 text-center sm:text-left">
              &copy; {new Date().getFullYear()} DevMax Educational Solutions. All rights reserved.
            </span>
            <span className="text-[12px] text-[#19181a]/40 font-bold uppercase tracking-widest">Built by students, for schools</span>
          </div>
        </div>

        <motion.button
          onClick={() => window.scrollTo({ top: 0, behavior: reduceMotion ? "auto" : "smooth" })}
          initial={false}
          animate={{ opacity: showTop ? 1 : 0, y: showTop ? 0 : 20, pointerEvents: showTop ? "auto" : "none" }}
          transition={{ duration: 0.3 }}
          aria-label="Scroll back to top of page"
          className="fixed bottom-8 right-8 z-[100] w-12 h-12 rounded-full flex items-center justify-center text-white shadow-xl hover:scale-110 transition-transform cursor-pointer"
          style={{ backgroundColor: NAVY }}
        >
          <ArrowRight className="w-5 h-5 -rotate-90" aria-hidden="true" />
        </motion.button>
      </footer>
    </div>
  );
}