import React, { useState, useEffect, useRef } from "react";
import { motion, useScroll, useTransform, useReducedMotion } from "framer-motion";
import PricingCards from "@/components/pricing/PricingCards";
import {
  BarChart3,
  UserCheck,
  Activity,
  CheckCircle2,
  ClipboardCheck,
  BrainCircuit,
  Repeat,
  Gamepad2,
  FileBarChart,
  QrCode,
  ChevronRight,
  ShieldCheck,
  Building2,
  Lock,
  Users,
  Sparkles,
  ArrowRight,
  Linkedin,
  Instagram,
  Mail
} from "lucide-react";

const BASE_URL = import.meta.env.VITE_BACKEND_URL || "http://localhost:8000";
const NAVY = "#152238";
const MARIGOLD = "#F2A93B";

const FEATURES = [
  {
    icon: <ClipboardCheck className="w-6 h-6" aria-hidden="true" />,
    title: "Attendance Intelligence",
    desc: "Daily attendance rolls up into patterns automatically — a student sliding toward chronic absence gets flagged in weeks, not at report-card time.",
  },
  {
    icon: <BrainCircuit className="w-6 h-6" aria-hidden="true" />,
    title: "Slow Learner Detection",
    desc: "Assessment results are compared against curriculum benchmarks to identify students falling behind before it shows up as a failing grade.",
  },
  {
    icon: <Repeat className="w-6 h-6" aria-hidden="true" />,
    title: "Remedial Action Workflows",
    desc: "Once a gap is found, a teacher assigns a remedial plan in one click and tracks it through to completion — no separate spreadsheet to maintain.",
  },
  {
    icon: <Gamepad2 className="w-6 h-6" aria-hidden="true" />,
    title: "Adaptive Quizzes & XP",
    desc: "Students work through adaptive MCQ quizzes that adjust to their level, with a gamified XP system that makes remedial work feel like progress.",
  },
  {
    icon: <FileBarChart className="w-6 h-6" aria-hidden="true" />,
    title: "Reports & Analytics",
    desc: "KPI cards and visualizations give admins a real read on class and school performance, exportable to CSV for the meetings that need it.",
  },
  {
    icon: <QrCode className="w-6 h-6" aria-hidden="true" />,
    title: "Fast School Onboarding",
    desc: "Invite teachers and students with a shareable QR code or link — a school can be up and running without a spreadsheet import.",
  },
];

const EDUCATOR_ITEMS = [
  { title: "Role-based access", desc: "Admins, teachers, and students each see exactly what's relevant to them — nothing more." },
  { title: "One-click remedial plans", desc: "Turn a flagged gap into an assigned task without leaving the dashboard." },
  { title: "Live class insights", desc: "See attendance and performance trends for a class as the term progresses, not after it ends." },
  { title: "Multi-tenant by design", desc: "Every school's data is isolated at the architecture level — built to serve many schools from one secure platform." },
];

const STEPS = [
  { step: "01", title: "Bring in your school's data", desc: "Add classes, sections, teachers, and students — invite everyone with a QR code, no spreadsheet reformatting." },
  { step: "02", title: "PMRS flags what needs attention", desc: "Attendance drops and performance gaps surface automatically as they happen, not at term-end." },
  { step: "03", title: "Teachers act, PMRS tracks it", desc: "Assign a remedial plan or adaptive quiz, and watch the student's progress update in real time." },
  { step: "04", title: "Admins see the whole picture", desc: "KPI dashboards and exportable reports turn individual progress into a school-wide view." },
];

const PILOT_PROGRESS = [
  { title: "Coursework prototype built", status: "done", detail: "Multi-tenant architecture, role-based login, first working version." },
  { title: "Service agreement drafted", status: "done", detail: "A real agreement a school could sign — not a mock document." },
  { title: "CBSE pilot school onboarded", status: "done", detail: "Real classes, teachers, and students using PMRS today." },
  { title: "Attendance & reports rollout", status: "active", detail: "Attendance system, KPI dashboards, and CSV export in active development." },
  { title: "Opening to more schools", status: "next", detail: "Bringing a second and third school on once the pilot feedback lands." },
];

const FOUNDERS = [
  {
    initials: "A",
    name: "Abijith C G",
    role: "Founder",
    focus: "Backend & Infrastructure",
    bio: "Builds PMRS end-to-end — the multi-tenant architecture, data isolation between schools, and the systems that keep every school's data exactly where it belongs.",
  },
  {
    initials: "A",
    name: "Akanksha",
    role: "Founder",
    focus: "Client Relations & Coordination",
    bio: "Leads the conversations with schools that shape what PMRS builds next — the person a school actually talks to, from onboarding through ongoing support.",
  },
];

function SEOManager() {
  useEffect(() => {
    document.title = "PMRS — Student Performance Tracking, Adaptive Quizzes & Early Remedial Plans for CBSE Schools";

    const setMetaTag = (selector, attributeName, attributeValue, contentValue) => {
      let meta = document.querySelector(selector);
      if (!meta) {
        meta = document.createElement("meta");
        meta.setAttribute(attributeName, attributeValue);
        document.head.appendChild(meta);
      }
      meta.setAttribute("content", contentValue);
    };

    setMetaTag('meta[name="description"]', 'name', 'description', 'PMRS empowers CBSE school administrators and teachers with real-time student performance tracking, attendance intelligence, adaptive quizzes, and early remedial plans.');
    setMetaTag('meta[name="keywords"]', 'name', 'keywords', 'student performance tracking, adaptive quizzes, early remedial plans, CBSE school administrators, teachers, attendance intelligence, slow learner detection');
    setMetaTag('meta[name="author"]', 'name', 'author', 'DevMax Educational Solutions');
    setMetaTag('meta[name="robots"]', 'name', 'robots', 'index, follow');

    setMetaTag('meta[property="og:title"]', 'property', 'og:title', 'PMRS — Student Performance Tracking & Early Remedial Plans');
    setMetaTag('meta[property="og:description"]', 'property', 'og:description', 'Comprehensive student performance tracking platform for CBSE schools with adaptive quizzes and automated remedial plans.');
    setMetaTag('meta[property="og:type"]', 'property', 'og:type', 'website');
    setMetaTag('meta[property="og:url"]', 'property', 'og:url', 'https://pmrs.live');
    setMetaTag('meta[property="og:image"]', 'property', 'og:image', 'https://pmrs.live/logo.png');
    setMetaTag('meta[property="og:site_name"]', 'property', 'og:site_name', 'PMRS');

    setMetaTag('meta[name="twitter:card"]', 'name', 'twitter:card', 'summary_large_image');
    setMetaTag('meta[name="twitter:title"]', 'name', 'twitter:title', 'PMRS — Student Performance Tracking for CBSE Schools');
    setMetaTag('meta[name="twitter:description"]', 'name', 'twitter:description', 'Track student attendance, detect slow learners early, and assign adaptive quizzes and remedial plans.');
    setMetaTag('meta[name="twitter:image"]', 'name', 'twitter:image', 'https://pmrs.live/logo.png');

    // JSON-LD: SoftwareApplication schema
    let scriptApp = document.querySelector('script[data-schema="SoftwareApplication"]');
    if (!scriptApp) {
      scriptApp = document.createElement("script");
      scriptApp.setAttribute("type", "application/ld+json");
      scriptApp.setAttribute("data-schema", "SoftwareApplication");
      document.head.appendChild(scriptApp);
    }
    scriptApp.textContent = JSON.stringify({
      "@context": "https://schema.org",
      "@type": "SoftwareApplication",
      "name": "PMRS - Performance Monitoring and Remedial System",
      "operatingSystem": "Web-based",
      "applicationCategory": "EducationalApplication",
      "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "INR"
      },
      "description": "Comprehensive student performance tracking system for CBSE schools featuring attendance analytics, slow learner detection, adaptive quizzes, and automated early remedial plans.",
      "audience": {
        "@type": "EducationalAudience",
        "educationalRole": ["School Administrator", "Teacher", "Educator"]
      },
      "featureList": [
        "Attendance Intelligence & Pattern Detection",
        "Slow Learner Early Detection System",
        "One-Click Automated Remedial Plan Workflows",
        "Adaptive MCQ Quizzes and Gamified XP System",
        "Interactive KPI Dashboards and Analytics"
      ]
    });

    // JSON-LD: Organization schema with founders
    let scriptOrg = document.querySelector('script[data-schema="Organization"]');
    if (!scriptOrg) {
      scriptOrg = document.createElement("script");
      scriptOrg.setAttribute("type", "application/ld+json");
      scriptOrg.setAttribute("data-schema", "Organization");
      document.head.appendChild(scriptOrg);
    }
    scriptOrg.textContent = JSON.stringify({
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "PMRS",
      "url": "https://pmrs.live",
      "description": "PMRS empowers CBSE school administrators and teachers with real-time student performance tracking, attendance intelligence, adaptive quizzes, and early remedial plans.",
      "logo": "https://pmrs.live/logo.png",
      "founder": [
        {
          "@type": "Person",
          "name": "Abijith C G",
          "jobTitle": "Founder",
          "description": "Backend & Infrastructure"
        },
        {
          "@type": "Person",
          "name": "Akanksha",
          "jobTitle": "Co-founder",
          "description": "Client Relations & Coordination"
        }
      ],
      "contactPoint": {
        "@type": "ContactPoint",
        "contactType": "customer support",
        "email": "pmrsteam.official@gmail.com"
      }
    });
  }, []);

  return null;
}

const NavButton = ({ children, className = "", as: Tag = "button", ...props }) => (
  <Tag
    className={`relative overflow-hidden font-geist font-medium text-[14px] tracking-tight px-6 py-2.5 rounded-full text-white shadow-md hover:shadow-xl transition-all duration-300 ease-out active:scale-95 group ${className}`}
    style={{ backgroundColor: NAVY }}
    {...props}
  >
    <div className="absolute inset-0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000 ease-in-out bg-gradient-to-r from-transparent via-white/20 to-transparent z-0" aria-hidden="true" />
    <span className="relative z-10 flex items-center gap-2">{children}</span>
  </Tag>
);

function MagneticButton({ children, onClick, className = "", style = {}, label }) {
  const reduceMotion = useReducedMotion();
  const ref = useRef(null);
  const [pos, setPos] = useState({ x: 0, y: 0 });

  const handleMove = (e) => {
    if (reduceMotion || !ref.current) return;
    const rect = ref.current.getBoundingClientRect();
    const x = e.clientX - (rect.left + rect.width / 2);
    const y = e.clientY - (rect.top + rect.height / 2);
    setPos({ x: x * 0.35, y: y * 0.35 });
  };

  return (
    <motion.button
      ref={ref}
      onMouseMove={handleMove}
      onMouseLeave={() => setPos({ x: 0, y: 0 })}
      onClick={onClick}
      animate={{ x: pos.x, y: pos.y }}
      transition={{ type: "spring", stiffness: 200, damping: 14, mass: 0.5 }}
      className={className}
      style={style}
      aria-label={label || "Request early access for PMRS"}
    >
      <motion.span animate={{ x: pos.x * 0.4, y: pos.y * 0.4 }} transition={{ type: "spring", stiffness: 200, damping: 14 }} className="inline-flex items-center gap-2">
        {children}
      </motion.span>
    </motion.button>
  );
}

function GrainOverlay() {
  return (
    <div
      className="absolute inset-0 pointer-events-none mix-blend-overlay opacity-[0.03] z-50"
      aria-hidden="true"
      style={{
        backgroundImage:
          "url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E\")",
      }}
    />
  );
}

function ScrollProgressBar() {
  const { scrollYProgress } = useScroll();
  const width = useTransform(scrollYProgress, [0, 1], ["0%", "100%"]);
  return (
    <motion.div
      className="fixed top-0 left-0 h-[3px] z-[100] origin-left"
      aria-hidden="true"
      style={{ width, background: `linear-gradient(90deg, ${NAVY}, ${MARIGOLD})` }}
    />
  );
}

const MockupCard = ({ type }) => {
  if (type === "profile") {
    return (
      <div className="w-[300px] bg-white/70 backdrop-blur-xl rounded-3xl p-5 shadow-[0_24px_48px_-12px_rgba(21,34,56,0.12)] border border-white flex flex-col gap-5" role="img" aria-label="Student Performance Profile Preview showing high attendance and active remedial plan">
        <div className="flex items-center gap-4">
          <div className="w-12 h-12 rounded-full bg-gradient-to-br from-[#152238] to-[#2a4066] flex items-center justify-center text-white font-bold text-sm shadow-inner shrink-0" aria-hidden="true">
            AS
          </div>
          <div className="flex flex-col gap-0.5">
            <div className="text-[15px] font-bold text-[#152238]">Aarav Sharma</div>
            <div className="text-[12px] text-[#152238]/60 font-medium">Class 10-B</div>
          </div>
        </div>
        <div className="w-full h-[1px] bg-gray-200/50" aria-hidden="true" />
        <div className="flex flex-col gap-4">
          <div className="flex flex-col gap-1.5">
            <div className="flex justify-between items-center text-xs">
              <span className="text-[#152238]/60 font-medium">Attendance</span>
              <span className="font-bold text-[#152238]">94%</span>
            </div>
            <div className="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden" aria-hidden="true">
              <div className="h-full bg-green-500 rounded-full w-[94%]" />
            </div>
          </div>
          <div className="flex flex-col gap-1.5">
            <div className="flex justify-between items-center text-xs">
              <span className="text-[#152238]/60 font-medium">Performance</span>
              <span className="font-bold text-[#F2A93B]">68%</span>
            </div>
            <div className="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden" aria-hidden="true">
              <div className="h-full bg-[#F2A93B] rounded-full w-[68%]" />
            </div>
          </div>
        </div>
        <div className="mt-1 flex items-center gap-2 p-2.5 rounded-xl bg-[#F2A93B]/10 border border-[#F2A93B]/20">
           <CheckCircle2 className="w-4 h-4 text-[#F2A93B]" aria-hidden="true" />
           <span className="text-xs font-semibold text-[#152238]">Remedial Plan Active</span>
        </div>
      </div>
    );
  }
  
  if (type === "chart") {
    return (
      <div className="w-[300px] bg-white/70 backdrop-blur-xl rounded-3xl p-5 shadow-[0_24px_48px_-12px_rgba(21,34,56,0.12)] border border-white" role="img" aria-label="Adaptive quiz results bar chart mockup">
        <div className="flex items-center justify-between mb-6">
          <div className="text-[14px] font-bold text-[#152238]">Quiz Results</div>
          <BarChart3 className="w-4 h-4 text-[#152238]/40" aria-hidden="true" />
        </div>
        <div className="flex items-end gap-3 h-28" aria-hidden="true">
          {[40, 70, 45, 90, 65, 85].map((h, i) => (
            <div key={i} className="flex-1 rounded-t-md relative group" style={{ height: `${h}%`, backgroundColor: i === 3 ? "#F2A93B" : "#eef1f6" }}>
              <div className="absolute -top-6 left-1/2 -translate-x-1/2 bg-[#152238] text-white text-[10px] py-0.5 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                {h}%
              </div>
            </div>
          ))}
        </div>
      </div>
    );
  }

  if (type === "list") {
    return (
      <div className="w-[300px] bg-white/70 backdrop-blur-xl rounded-3xl p-5 shadow-[0_24px_48px_-12px_rgba(21,34,56,0.12)] border border-white flex flex-col gap-4" role="img" aria-label="Action required list mockup for slow learner intervention">
        <div className="flex items-center gap-2 mb-2">
          <Activity className="w-4 h-4 text-red-500" aria-hidden="true" />
          <div className="text-[14px] font-bold text-[#152238]">Action Required</div>
        </div>
        {[1, 2, 3].map((_, i) => (
          <div key={i} className="flex items-center gap-3 p-3 rounded-2xl bg-white border border-gray-100 shadow-sm" aria-hidden="true">
            <div className="w-8 h-8 rounded-full bg-[#f8f9fa] border border-gray-200 flex items-center justify-center shrink-0">
              <UserCheck className="w-3.5 h-3.5 text-[#152238]/40" />
            </div>
            <div className="flex flex-col gap-1.5 flex-1">
              <div className="w-full h-2 bg-gray-200/70 rounded-full" />
              <div className="w-2/3 h-2 bg-gray-100 rounded-full" />
            </div>
          </div>
        ))}
      </div>
    );
  }
  return null;
};

function DashboardCard() {
  return (
    <div className="relative overflow-hidden rounded-[28px] border border-white/[0.04] bg-[#0c0d10] shadow-[0_40px_100px_-20px_rgba(0,0,0,0.6)]" role="img" aria-label="Interactive PMRS dashboard showcase displaying average attendance, flagged students, active remedial plans, and subject term progress">
      <div className="absolute inset-0 z-0 pointer-events-none opacity-[0.12]" aria-hidden="true">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <pattern id="gridPattern" width="40" height="40" patternUnits="userSpaceOnUse">
              <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#cba273" strokeWidth="0.5" strokeOpacity="0.2" />
            </pattern>
            <radialGradient id="glowGrad" cx="50%" cy="50%" r="50%" fx="50%" fy="50%">
              <stop offset="0%" stopColor="#cba273" stopOpacity="0.1" />
              <stop offset="100%" stopColor="#cba273" stopOpacity="0" />
            </radialGradient>
          </defs>
          <rect width="100%" height="100%" fill="url(#gridPattern)" />
          <rect width="100%" height="100%" fill="url(#glowGrad)" />
        </svg>
      </div>
      
      <div className="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-white/[0.15] to-transparent z-10" aria-hidden="true" />
      <div className="absolute top-0 left-1/4 w-1/2 h-[1px] rounded-full z-10" style={{ background: `linear-gradient(90deg, transparent, #cba27340, transparent)` }} aria-hidden="true" />
      
      <div className="relative z-10 flex items-center justify-between px-6 py-4 border-b border-white/[0.04] bg-[#0c0d10]/40 backdrop-blur-md">
        <div className="flex gap-2.5" aria-hidden="true">
          {[
            { fill: "#ff5f56", shadow: "rgba(255,95,86,0.3)" },
            { fill: "#ffbd2e", shadow: "rgba(255,189,46,0.3)" },
            { fill: "#27c93f", shadow: "rgba(39,201,63,0.3)" }
          ].map((c, i) => (
            <svg key={i} width="12" height="12" viewBox="0 0 12 12">
              <circle cx="6" cy="6" r="6" fill={c.fill} style={{ filter: `drop-shadow(0 0 2px ${c.shadow})` }} />
            </svg>
          ))}
        </div>
        <div className="px-3 py-1 rounded-full bg-white/[0.03] border border-white/[0.05] flex items-center gap-2 backdrop-blur-sm">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#cba273" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{ filter: "drop-shadow(0 0 2px rgba(203,162,115,0.3))" }} aria-hidden="true">
            <circle cx="12" cy="12" r="10" />
            <path d="M12 6v6l4 2" />
          </svg>
          <span className="text-[10px] text-white/50 uppercase tracking-widest font-semibold">Class Dashboard</span>
        </div>
      </div>
      
      <div className="relative z-10 p-6 md:p-10 grid md:grid-cols-12 gap-8">
        <div className="md:col-span-8 flex flex-col gap-6">
          <div className="grid grid-cols-3 gap-4">
            {[
              { label: "Avg. attendance", value: "91%" },
              { label: "Flagged students", value: "6" },
              { label: "Remedial plans active", value: "14" },
            ].map((s) => (
              <div key={s.label} className="bg-white/[0.02] rounded-2xl p-4 border border-white/[0.04] backdrop-blur-sm relative overflow-hidden">
                <div className="absolute top-0 right-0 p-2 opacity-10" aria-hidden="true">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#cba273" strokeWidth="2">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                  </svg>
                </div>
                <div className="text-[11px] text-[#cba273]/70 mb-2 font-semibold uppercase tracking-widest">{s.label}</div>
                <div className="text-2xl font-bold text-white/90 font-mono tracking-tight">{s.value}</div>
              </div>
            ))}
          </div>
          
          <div className="relative min-h-[240px] bg-white/[0.01] rounded-3xl border border-white/[0.04] p-6 flex flex-col overflow-hidden backdrop-blur-sm">
            <div className="text-[11px] text-white/40 mb-4 font-semibold uppercase tracking-widest relative z-10">Term progress by subject</div>
            <motion.div 
              initial={{ opacity: 0, y: 15 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 1, ease: "easeOut" }}
              className="absolute inset-x-0 bottom-0 top-16"
              aria-hidden="true"
            >
              <svg viewBox="0 0 800 200" preserveAspectRatio="none" className="w-full h-full">
                <defs>
                  <linearGradient id="chartFill" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stopColor="#cba273" stopOpacity="0.2" />
                    <stop offset="100%" stopColor="#cba273" stopOpacity="0.0" />
                  </linearGradient>
                  <filter id="glowLine">
                    <feGaussianBlur stdDeviation="2" result="coloredBlur"/>
                    <feMerge>
                      <feMergeNode in="coloredBlur"/>
                      <feMergeNode in="SourceGraphic"/>
                    </feMerge>
                  </filter>
                </defs>
                <path d="M0,130 C150,130 200,60 300,90 C450,130 550,30 650,50 C750,70 780,110 800,100 L800,200 L0,200 Z" fill="url(#chartFill)" />
                <path d="M0,130 C150,130 200,60 300,90 C450,130 550,30 650,50 C750,70 780,110 800,100" fill="none" stroke="#cba273" strokeOpacity="0.8" strokeWidth="2" filter="url(#glowLine)" />
              </svg>
            </motion.div>
          </div>
        </div>
        
        <div className="md:col-span-4 flex flex-col gap-6">
          <div className="bg-white/[0.01] rounded-3xl p-6 border border-white/[0.04] flex flex-col gap-5 backdrop-blur-sm">
            <div className="text-[11px] text-white/40 font-semibold uppercase tracking-widest">Students flagged</div>
            {["Attendance drop — Class 8B", "Math gap — Class 6A", "Reassessment due — 9C"].map((row, i) => (
              <motion.div
                key={row}
                initial={{ opacity: 0, x: 12 }}
                whileInView={{ opacity: 1, x: 0 }}
                viewport={{ once: true }}
                transition={{ delay: 0.3 + i * 0.1 }}
                className="flex items-center gap-3"
              >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#cba273" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" style={{ filter: "drop-shadow(0 0 2px rgba(203,162,115,0.4))" }} aria-hidden="true">
                  <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                  <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                <div className="text-[13px] text-white/80 font-medium">{row}</div>
              </motion.div>
            ))}
          </div>
          <div className="flex-1 rounded-3xl border border-white/[0.04] p-6 flex flex-col justify-center items-center text-center gap-4 relative overflow-hidden backdrop-blur-sm"
            style={{ background: `linear-gradient(135deg, rgba(203,162,115,0.05) 0%, transparent 80%)` }}
          >
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cba273" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" className="relative z-10" style={{ filter: "drop-shadow(0 0 4px rgba(203,162,115,0.3))" }} aria-hidden="true">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
              <path d="M9 12l2 2 4-4" />
            </svg>
            <div className="text-[13px] text-white/60 leading-relaxed font-medium relative z-10">
              Every school's data lives in its own isolated space
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default function LandingPage() {
  const [email, setEmail] = useState("");
  const [emailFocused, setEmailFocused] = useState(false);
  const reduceMotion = useReducedMotion();
  const heroRef = useRef(null);
  const dashboardRef  = useRef(null);

  const { scrollYProgress } = useScroll({ target: heroRef, offset: ["start start", "end start"] });
  const y1 = useTransform(scrollYProgress, [0, 1], [0, reduceMotion ? 0 : -600]);
  const y2 = useTransform(scrollYProgress, [0, 1], [0, reduceMotion ? 0 : 400]);
  const y3 = useTransform(scrollYProgress, [0, 1], [0, reduceMotion ? 0 : -800]);

  const textOpacity = useTransform(scrollYProgress, [0, 0.5], [1, 0]);
  const textScale = useTransform(scrollYProgress, [0, 0.6], [1, 0.88]);
  const textY = useTransform(scrollYProgress, [0, 0.6], [0, 120]);

  const { scrollYProgress: dp } = useScroll({
    target: dashboardRef,
    offset: ["start end", "center center"],
  });
  const dp_labelOp = useTransform(dp, [0.05, 0.35], [0, 1]);
  const dp_labelY  = useTransform(dp, [0.05, 0.35], [20, 0]);
  const dp_cardOp  = useTransform(dp, [0.20, 0.55], [0, 1]);
  const dp_cardY   = useTransform(dp, [0.20, 0.55], [60, 0]);
  const dp_rotateX = useTransform(dp, [0.20, 0.55], [14, 0]);
  const dp_scale   = useTransform(dp, [0.20, 0.55], [0.93, 1]);

  const headlineContainer = {
    hidden: {},
    visible: { transition: { staggerChildren: 0.08, delayChildren: 0.1 } },
  };
  const headlineWord = {
    hidden: { opacity: 0, y: 30, filter: "blur(8px)" },
    visible: { opacity: 1, y: 0, filter: "blur(0px)", transition: { duration: 0.8, ease: [0.16, 1, 0.3, 1] } },
  };

  const handleCreateAccount = () => {
    const registerUrl = new URL(`${BASE_URL}/register`);
    if (email) registerUrl.searchParams.append("email", email);
    window.location.href = registerUrl.toString();
  };

  return (
    <div className="relative min-h-screen w-full overflow-x-clip bg-[#faf9f6] selection:bg-[#152238] selection:text-white">
      <SEOManager />
      <ScrollProgressBar />

      <header>
        <nav className="fixed top-6 left-1/2 -translate-x-1/2 z-[100] w-full max-w-[1300px] px-4 md:px-6" aria-label="Main Navigation">
          <div className="flex items-center justify-between bg-white/70 backdrop-blur-2xl border border-black/5 rounded-full px-4 md:px-8 py-3 shadow-[0_8px_32px_0_rgba(21,34,56,0.06)]">
            <div className="flex items-center gap-2">
              <a href="#" className="flex items-center gap-2.5 font-bold text-xl tracking-tight" style={{ color: NAVY }} aria-label="PMRS Homepage">
                <img src="/logo.png" alt="PMRS Logo" className="h-8 md:h-9 w-auto object-contain" />
            
              </a>
            </div>
            <div className="flex items-center gap-3 md:gap-6">
              <div className="hidden md:flex items-center gap-6 lg:gap-8">
                {[
                  { label: "Features", href: "#features" },
                  { label: "How it works", href: "#how-it-works" },
                  { label: "Pricing", href: "#pricing" },
                  { label: "Founders", href: "#founders" },
                  { label: "For schools", href: "#for-schools" },
                ].map((item) => (
                  <a key={item.label} href={item.href} className="text-[14px] font-semibold text-[#1a1a1a]/70 hover:text-black transition-colors">
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
        <section id="hero" ref={heroRef} className="relative w-full" style={{ height: "220vh" }}>
          <div className="sticky top-0 h-screen w-full overflow-hidden flex flex-col items-center justify-center pt-20">
            <GrainOverlay />

            <div className="absolute inset-0 z-0 flex justify-center items-center pointer-events-none perspective-[1200px] overflow-hidden opacity-90" aria-hidden="true">
              <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[70vw] h-[70vw] bg-[#F2A93B]/5 rounded-full blur-[120px]" />
              <div className="absolute bottom-[-10%] right-[-10%] w-[50vw] h-[50vw] bg-[#152238]/5 rounded-full blur-[100px]" />

              <motion.div
                style={{
                  rotateX: reduceMotion ? 0 : 15,
                  rotateZ: reduceMotion ? 0 : -4,
                  scale: reduceMotion ? 1 : 1.15,
                }}
                className="absolute top-[-10vh] left-1/2 -translate-x-1/2 flex gap-[85vw] sm:gap-[650px] md:gap-[750px] lg:gap-[900px] opacity-60 md:opacity-80"
              >
                <motion.div style={{ y: y1 }} className="flex flex-col gap-8 md:gap-10 pt-[15vh]">
                  <MockupCard type="profile" />
                  <MockupCard type="chart" />
                  <MockupCard type="list" />
                  <MockupCard type="profile" />
                </motion.div>
                <motion.div style={{ y: y3 }} className="flex flex-col gap-8 md:gap-10 pt-[30vh]">
                  <MockupCard type="list" />
                  <MockupCard type="profile" />
                  <MockupCard type="chart" />
                  <MockupCard type="list" />
                </motion.div>
              </motion.div>
            </div>

            <div className="absolute inset-0 z-10 bg-[radial-gradient(ellipse_at_center,rgba(250,249,246,0.98)_25%,rgba(250,249,246,0.7)_60%,rgba(250,249,246,0)_100%)] pointer-events-none" aria-hidden="true" />
            <div className="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-[#faf9f6] to-transparent z-10 pointer-events-none" aria-hidden="true" />

            <motion.div
              style={{ opacity: textOpacity, scale: textScale, y: textY }}
              className="relative z-20 mx-auto flex max-w-[1000px] flex-col items-center gap-7 text-center px-6 mt-[-4vh]"
            >
              <motion.h1
                variants={headlineContainer}
                initial="hidden"
                animate="visible"
                className="font-bold text-[#111113] tracking-[-0.04em] px-2 md:px-0 drop-shadow-sm"
                style={{ fontFamily: "'Geist', sans-serif", fontSize: "clamp(42px, 7vw, 92px)", lineHeight: 1.05 }}
              >
                <span className="inline-flex flex-wrap justify-center gap-x-[0.25em]">
                  {"Know which students need help".split(" ").map((word, i) => (
                    <motion.span key={i} variants={headlineWord} className="inline-block">
                      {word}
                    </motion.span>
                  ))}
                </span>
                <br />
                <motion.span
                  variants={headlineWord}
                  className="italic text-transparent bg-clip-text inline-block pb-2"
                  style={{ fontFamily: "'Instrument Serif', serif", fontWeight: 400, backgroundImage: `linear-gradient(90deg, ${NAVY}, #3a5a8c, ${MARIGOLD})` }}
                >
                  before the term ends
                </motion.span>
              </motion.h1>

              <motion.p
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.7, delay: 0.7, ease: [0.16, 1, 0.3, 1] }}
                className="text-[#1a1a1a]/70 font-medium leading-relaxed text-[17px] md:text-[20px] max-w-[700px]"
              >
                PMRS empowers CBSE school administrators and teachers to perform real-time student performance tracking, analyze attendance intelligence, launch adaptive quizzes, and execute early remedial plans.
              </motion.p>

              <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.7, delay: 0.85, ease: [0.16, 1, 0.3, 1] }}
                className="flex flex-col items-center gap-4 mt-4 w-full"
              >
                <motion.div
                  animate={{
                    boxShadow: emailFocused
                      ? `0px 16px 48px 8px rgba(242,169,59,0.15), 0 0 0 4px ${MARIGOLD}33`
                      : "0px 12px 40px 4px rgba(21,34,56,0.08)",
                  }}
                  transition={{ duration: 0.3 }}
                  className="flex flex-col sm:flex-row items-center gap-2 bg-white/95 backdrop-blur-md border border-black/10 p-2 sm:pl-6 w-full max-w-[480px]"
                  style={{ borderRadius: "40px" }}
                >
                  <input
                    id="school-email"
                    name="email"
                    type="email"
                    placeholder="School / work email"
                    aria-label="School or work email address for early access request"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    onFocus={() => setEmailFocused(true)}
                    onBlur={() => setEmailFocused(false)}
                    className="flex-1 bg-transparent outline-none text-[15px] font-medium text-[#111] placeholder:text-[#9aa0ad] w-full px-4 py-3 sm:px-0 sm:py-0"
                  />
                  <MagneticButton
                    onClick={handleCreateAccount}
                    label="Request early access for PMRS student performance tracking"
                    className="rounded-full px-6 py-3 text-white text-[14px] font-bold w-full sm:w-auto shadow-lg shadow-[#152238]/10"
                    style={{ background: `linear-gradient(180deg, ${NAVY} 0%, #0e1826 100%)` }}
                  >
                    Request early access
                  </MagneticButton>
                </motion.div>
                <p className="text-[12px] text-[#1a1a1a]/45 font-semibold tracking-tight mt-1">
                  Currently onboarding a small group of pilot schools — no cost during pilot.
                </p>
              </motion.div>
            </motion.div>

            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              transition={{ delay: 1.3 }}
              className="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 z-20"
              aria-hidden="true"
            >
              <div className="w-[2px] h-12 bg-black/10 overflow-hidden relative rounded-full">
                {!reduceMotion && (
                  <motion.div
                      className="w-full h-4 absolute top-0 rounded-full"
                      style={{ background: MARIGOLD }}
                      animate={{ y: [0, 48, 0] }}
                      transition={{ repeat: Infinity, duration: 1.8, ease: "easeInOut" }}
                  />
                )}
              </div>
              <span className="text-[10px] uppercase tracking-widest text-black/40 font-bold">Scroll</span>
            </motion.div>
          </div>
        </section>

        <div className="w-full bg-[#faf9f6] leading-[0] overflow-hidden -mb-1" aria-hidden="true">
          <svg viewBox="0 0 1440 120" preserveAspectRatio="none" className="w-full h-[60px] md:h-[100px] block">
            <path d="M0,0 C480,120 960,120 1440,0 L1440,120 L0,120 Z" fill="#0e1826" />
          </svg>
        </div>

        <section
          id="dashboard-showcase"
          ref={dashboardRef}
          className="relative py-24 md:py-32 px-6 overflow-hidden"
          style={{ background: `linear-gradient(180deg, #0e1826 0%, #080f1a 100%)` }}
        >
          <div className="absolute inset-0 pointer-events-none flex justify-center items-center overflow-hidden" style={{ perspective: "1200px" }} aria-hidden="true">
            <div className="absolute w-[120vw] md:w-[80vw] max-w-[1000px] aspect-square rounded-full blur-[120px] top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2" style={{ background: `radial-gradient(circle at center, rgba(203,162,115,0.12) 0%, transparent 60%)` }} />
            
            <div
              className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full flex justify-center items-center"
              style={{ transformStyle: "preserve-3d" }}
            >
              <div className="absolute left-[-10%] md:left-[5%] top-[15%] w-[300px] h-[400px] rounded-[32px] bg-white/5 border border-white/10 backdrop-blur-xl shadow-[0_20px_40px_-10px_rgba(0,0,0,0.8)]" style={{ transform: "translateZ(-300px) rotateY(15deg) rotateX(10deg)" }} />
              <div className="absolute left-[0%] md:left-[12%] bottom-[5%] w-[250px] h-[250px] rounded-[32px] bg-white/[0.08] border border-white/10 backdrop-blur-xl shadow-[0_20px_40px_-10px_rgba(0,0,0,0.8)]" style={{ transform: "translateZ(-150px) rotateY(25deg) rotateX(-5deg)" }} />
              
              <div className="absolute right-[-10%] md:right-[5%] bottom-[15%] w-[350px] h-[250px] rounded-[32px] bg-white/5 border border-white/10 backdrop-blur-xl shadow-[0_20px_40px_-10px_rgba(0,0,0,0.8)]" style={{ transform: "translateZ(-350px) rotateY(-20deg) rotateX(-15deg)" }} />
              <div className="absolute right-[0%] md:right-[15%] top-[10%] w-[220px] h-[320px] rounded-[32px] bg-white/[0.08] border border-white/10 backdrop-blur-xl shadow-[0_20px_40px_-10px_rgba(0,0,0,0.8)]" style={{ transform: "translateZ(-200px) rotateY(-15deg) rotateX(20deg)" }} />
            </div>
          </div>

          <div className="relative mx-auto max-w-[1100px] flex flex-col items-center gap-12 z-10">
            <motion.div
              className="flex flex-col items-center gap-3 text-center"
              style={{ opacity: dp_labelOp, y: dp_labelY }}
            >
              <h2
                className="font-medium tracking-tight text-white"
                style={{ fontFamily: "'Instrument Serif', serif", fontSize: "clamp(30px, 4vw, 52px)" }}
              >
                Everything in one place.
              </h2>
            </motion.div>

            <motion.div
              className="w-full relative z-20"
              style={{
                opacity: dp_cardOp,
                y: dp_cardY,
                rotateX: dp_rotateX,
                scale: dp_scale,
                transformPerspective: 1400,
              }}
            >
              <DashboardCard />
            </motion.div>
          </div>
        </section>

        <div className="w-full bg-[#faf9f6] leading-[0] overflow-hidden -mt-1" aria-hidden="true">
          <svg viewBox="0 0 1440 120" preserveAspectRatio="none" className="w-full h-[60px] md:h-[100px] block">
            <path d="M0,0 L1440,0 C960,120 480,120 0,0 Z" fill="#080f1a" />
          </svg>
        </div>

        <section id="features" className="mx-auto max-w-[1100px] px-6 py-20 md:py-32">
          <motion.div initial={{ opacity: 0, y: 30 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true, amount: 0.2 }} transition={{ duration: 0.7 }} className="flex flex-col gap-14">
            <div className="flex flex-col gap-4 text-center items-center">
              <h2 className="font-medium tracking-tight text-[#1a1a1a]" style={{ fontFamily: "'Instrument Serif', serif", fontSize: "clamp(36px, 5vw, 64px)", lineHeight: 1.1 }}>
                From raw data to a remedial plan
              </h2>
            </div>
            <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
              {FEATURES.map((f, i) => (
                <motion.div
                  key={f.title}
                  initial={{ opacity: 0, y: 24 }}
                  whileInView={{ opacity: 1, y: 0 }}
                  viewport={{ once: true }}
                  transition={{ duration: 0.5, delay: (i % 3) * 0.08 }}
                  className="group p-8 rounded-[32px] border border-black/10 hover:border-black/20 hover:shadow-2xl hover:shadow-black/5 transition-all duration-500 bg-white"
                >
                  <div className="w-12 h-12 rounded-2xl bg-black/5 flex items-center justify-center transition-all duration-500 mb-6 group-hover:scale-110 group-hover:bg-[#152238] group-hover:text-white" style={{ color: NAVY }} aria-hidden="true">
                    {f.icon}
                  </div>
                  <h3 className="font-bold text-xl text-[#1a1a1a] mb-3">{f.title}</h3>
                  <p className="text-[#1a1a1a]/65 leading-relaxed text-[15px] font-medium">{f.desc}</p>
                </motion.div>
              ))}
            </div>
          </motion.div>
        </section>

        <section id="for-schools" className="mx-auto max-w-[1100px] px-6 py-20 md:py-32">
          <motion.div initial={{ opacity: 0, y: 30 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true, amount: 0.2 }} transition={{ duration: 0.7 }} className="grid lg:grid-cols-2 gap-16 items-center">
            <div className="flex flex-col gap-8">
              <h2 className="font-medium tracking-tight text-[#111113] leading-[1.05]" style={{ fontFamily: "'Instrument Serif', serif", fontSize: "clamp(40px, 5.5vw, 64px)" }}>
                Less admin work, more direct student impact
              </h2>
              <p className="text-[19px] text-[#1a1a1a]/65 leading-relaxed font-medium">
                PMRS was built with a real CBSE school as our pilot partner — every feature is
                shaped by what a working school day actually needs.
              </p>
              <div className="grid sm:grid-cols-2 gap-x-10 gap-y-10 mt-4">
                {EDUCATOR_ITEMS.map((item) => (
                  <div key={item.title} className="flex flex-col gap-2.5 border-l-[3px] border-black/10 pl-5 hover:border-[#F2A93B] transition-colors duration-300">
                    <h3 className="font-bold text-[#111113] text-[16px]">{item.title}</h3>
                    <p className="text-[#1a1a1a]/60 text-[14px] leading-relaxed font-medium">{item.desc}</p>
                  </div>
                ))}
              </div>
            </div>
            <div className="relative rounded-[40px] bg-white border border-black/10 shadow-xl shadow-black/5 p-10 md:p-14 flex flex-col gap-8">
              {[
                { icon: <Building2 className="w-5 h-5" aria-hidden="true" />, title: "Multi-school ready", sub: "One platform, many isolated schools" },
                { icon: <Lock className="w-5 h-5" aria-hidden="true" />, title: "Data stays with the school", sub: "Role-based access for admins, teachers, students" },
                { icon: <Users className="w-5 h-5" aria-hidden="true" />, title: "Every role, one login", sub: "Administrators, teachers, and students" },
              ].map((row, i) => (
                <motion.div key={row.title} initial={{ opacity: 0, x: 16 }} whileInView={{ opacity: 1, x: 0 }} transition={{ delay: i * 0.1 }} viewport={{ once: true }} className="flex items-center gap-4">
                  <div className="w-14 h-14 rounded-2xl flex items-center justify-center text-white shrink-0 shadow-inner" style={{ backgroundColor: NAVY }} aria-hidden="true">{row.icon}</div>
                  <div>
                    <h3 className="font-bold text-[#1a1a1a] text-[16px] mb-0.5">{row.title}</h3>
                    <div className="text-[#1a1a1a]/50 text-[14px] font-medium">{row.sub}</div>
                  </div>
                </motion.div>
              ))}
            </div>
          </motion.div>
        </section>

        <section id="how-it-works" className="bg-white py-24 md:py-32 border-y border-black/5">
          <motion.div initial={{ opacity: 0, y: 30 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true, amount: 0.2 }} transition={{ duration: 0.8 }} className="mx-auto max-w-[900px] px-6">
            <div className="flex flex-col gap-4 text-center items-center mb-20">
              <h2 className="font-medium tracking-tight text-[#1a1a1a]" style={{ fontFamily: "'Instrument Serif', serif", fontSize: "clamp(36px, 5vw, 64px)" }}>How PMRS works</h2>
              <p className="text-[19px] font-medium text-[#1a1a1a]/65 max-w-lg">Four steps from onboarding to a remedial plan in a student's hands.</p>
            </div>
            <div className="relative flex flex-col gap-14">
              <div className="absolute left-[24px] top-4 bottom-4 w-[2px] bg-gradient-to-b from-transparent via-black/10 to-transparent hidden sm:block" aria-hidden="true" />
              {STEPS.map((s, i) => (
                <motion.div key={s.step} initial={{ opacity: 0, x: -20 }} whileInView={{ opacity: 1, x: 0 }} viewport={{ once: true }} transition={{ delay: i * 0.1 }} className="relative flex gap-8 items-start">
                  <span className="text-5xl text-black/10 font-bold font-mono relative z-10 bg-white pr-4 py-2" aria-hidden="true">{s.step}</span>
                  <div className="flex flex-col gap-2 pt-4">
                    <h3 className="font-bold text-xl text-[#1a1a1a]">{s.title}</h3>
                    <p className="text-[#1a1a1a]/65 leading-relaxed font-medium text-[16px]">{s.desc}</p>
                  </div>
                </motion.div>
              ))}
            </div>
          </motion.div>
        </section>

        <section id="founders" className="mx-auto max-w-[1100px] px-6 py-24 md:py-32">
          <div className="flex flex-col w-full">
            <div className="flex flex-col items-center text-center pb-16 px-6 border-b border-[#e5e7eb]">
              <svg viewBox="0 0 24 24" fill="currentColor" className="w-5 h-5 mb-6 text-[#3b82f6]" aria-hidden="true">
                <path d="M12 0L13.8 10.2L24 12L13.8 13.8L12 24L10.2 13.8L0 12L10.2 10.2L12 0Z" />
              </svg>
              <h2 className="font-medium tracking-tight text-[#152238] mb-4" style={{ fontFamily: "'Instrument Serif', serif", fontSize: "clamp(40px, 6vw, 64px)", lineHeight: 1.1 }}>
                Meet the Founders
              </h2>
              <p className="text-[#6b7280] font-sans max-w-lg text-[17px] leading-relaxed">
                Two Computer Science students building the tool schools actually need — from a coursework project to a real CBSE pilot.
              </p>
            </div>
            <div className="grid md:grid-cols-2">
              {FOUNDERS.map((f, i) => (
                <FounderCard key={f.name} founder={f} index={i} isLast={i === FOUNDERS.length - 1} />
              ))}
            </div>
          </div>
        </section>

        <section id="project-story" className="bg-[#0c0d10] py-24 md:py-36 text-white">
          <motion.div initial={{ opacity: 0, y: 24 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ duration: 0.7 }} className="mx-auto max-w-[1100px] px-6 flex flex-col gap-20">
            <h2 className="font-medium tracking-tight text-center text-[#f8f9fa]" style={{ fontFamily: "'Instrument Serif', serif", fontSize: "clamp(36px, 5vw, 64px)" }}>
              From class project to a school actually using it
            </h2>
            <div className="grid md:grid-cols-3 gap-16 md:gap-12 relative">
              <div className="hidden md:block absolute top-7 left-[10%] right-[10%] h-px bg-gradient-to-r from-transparent via-[#cba273]/20 to-transparent" aria-hidden="true" />
              {[
                "A software engineering coursework project became a full architecture: multi-tenant, role-based, built in Laravel.",
                "We wrote a real Service Agreement — the terms a school would actually sign, not just a mock document.",
                "A CBSE school agreed to pilot PMRS with real classes, teachers, and students.",
              ].map((text, i) => (
                <motion.div 
                  key={i} 
                  initial={{ opacity: 0, y: 16 }} 
                  whileInView={{ opacity: 1, y: 0 }} 
                  viewport={{ once: true }} 
                  transition={{ delay: i * 0.15, duration: 0.7, ease: "easeOut" }} 
                  className="flex flex-col gap-8 items-center text-center relative z-10"
                >
                  <div className="relative flex items-center justify-center w-14 h-14 bg-[#0c0d10]">
                    <svg className="absolute inset-0 w-full h-full text-[#cba273]/30" viewBox="0 0 56 56" fill="none" stroke="currentColor" strokeWidth="1" aria-hidden="true">
                      <circle cx="28" cy="28" r="27" />
                    </svg>
                    <span className="text-3xl text-[#cba273]" style={{ fontFamily: "'Instrument Serif', serif" }}>
                      0{i + 1}
                    </span>
                  </div>
                  <p className="text-white/50 text-[16px] font-medium leading-relaxed tracking-wide px-4">
                    {text}
                  </p>
                </motion.div>
              ))}
            </div>
          </motion.div>
        </section>

        <section id="pilot-status" className="mx-auto max-w-[900px] px-6 py-24 md:py-32">
          <motion.div initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ duration: 0.7 }} className="flex flex-col gap-6 items-center text-center mb-20">
            <h2 className="font-medium tracking-tight text-[#1a1a1a] max-w-2xl leading-[1.1]" style={{ fontFamily: "'Instrument Serif', serif", fontSize: "clamp(32px, 4.5vw, 56px)" }}>
              We're building PMRS with a real CBSE school, not in a vacuum
            </h2>
            <p className="text-[#1a1a1a]/65 leading-relaxed max-w-lg text-[18px] font-medium">
              No inflated numbers here — this is the actual state of the pilot, updated as it moves.
            </p>
          </motion.div>

          <div className="relative">
            <div className="absolute left-[19px] md:left-1/2 md:-translate-x-1/2 top-0 bottom-0 w-[2px] bg-black/10" aria-hidden="true" />
            <motion.div
              initial={{ scaleY: 0 }}
              whileInView={{ scaleY: 1 }}
              viewport={{ once: true }}
              transition={{ duration: 1.2, ease: [0.22, 1, 0.36, 1] }}
              className="absolute left-[19px] md:left-1/2 md:-translate-x-1/2 top-0 w-[2px] origin-top"
              aria-hidden="true"
              style={{ height: "100%", background: `linear-gradient(180deg, ${NAVY}, ${MARIGOLD})` }}
            />
            <div className="flex flex-col gap-12 md:gap-8">
              {PILOT_PROGRESS.map((item, i) => {
                const isRight = i % 2 === 1;
                const badge =
                  item.status === "done"
                    ? { label: "Done", bg: NAVY, text: "#fff" }
                    : item.status === "active"
                    ? { label: "In progress", bg: MARIGOLD, text: NAVY }
                    : { label: "Next up", bg: "#e8e6df", text: "#555" };
                return (
                  <motion.div
                    key={item.title}
                    initial={{ opacity: 0, x: isRight ? 24 : -24 }}
                    whileInView={{ opacity: 1, x: 0 }}
                    viewport={{ once: true }}
                    transition={{ delay: i * 0.12, duration: 0.5 }}
                    className={`relative flex items-start gap-6 md:gap-0 md:grid md:grid-cols-2 ${isRight ? "" : ""}`}
                  >
                    <motion.div
                      initial={{ scale: 0 }}
                      whileInView={{ scale: 1 }}
                      viewport={{ once: true }}
                      transition={{ delay: i * 0.12 + 0.1, type: "spring", stiffness: 300, damping: 16 }}
                      className="absolute left-[10px] md:left-1/2 md:-translate-x-1/2 top-1 w-5 h-5 rounded-full border-4 border-[#faf9f6] z-10 shadow-sm"
                      style={{ backgroundColor: badge.bg }}
                      aria-hidden="true"
                    />
                    <div className={`pl-14 md:pl-0 ${isRight ? "md:order-2 md:pl-16" : "md:pr-16 md:text-right"}`}>
                      <div className={`flex items-center gap-2 mb-3 ${isRight ? "" : "md:justify-end"}`}>
                        <span className="text-[11px] font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-sm" style={{ backgroundColor: badge.bg, color: badge.text }}>
                          {badge.label}
                        </span>
                      </div>
                      <h3 className="font-bold text-[#1a1a1a] text-[18px] md:text-[20px]">{item.title}</h3>
                      <p className="text-[#1a1a1a]/60 text-[15px] font-medium leading-relaxed mt-2">{item.detail}</p>
                    </div>
                    <div className={isRight ? "md:order-1" : ""} />
                  </motion.div>
                );
              })}
            </div>
          </div>
        </section>

                <section id="pricing" className="py-24 md:py-32 px-6 bg-[#faf9f6]">
          <motion.div initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ duration: 0.7 }} className="flex flex-col items-center text-center mb-16">
            <h2 className="font-medium tracking-tight text-[#1a1a1a] mb-4" style={{ fontFamily: "'Instrument Serif', serif", fontSize: "clamp(32px, 4.5vw, 56px)" }}>
              Plans designed for growing schools
            </h2>
            <p className="text-[#1a1a1a]/65 leading-relaxed max-w-lg text-[18px] font-medium">
              Start with the capabilities you need today, and upgrade as your institution grows.
            </p>
          </motion.div>
          
          <PricingCards compact={true} />
          
          <div className="mt-16 text-center">
            <a href="/pricing" className="inline-flex items-center gap-2 font-bold text-[16px] text-[#152238] hover:opacity-80 transition-opacity">
              View Detailed Pricing
              <ArrowRight className="w-4 h-4" aria-hidden="true" />
            </a>
          </div>
        </section>

        <section id="cta" className="relative w-full bg-[#0c0d10] py-32 md:py-48 overflow-hidden flex flex-col items-center text-center px-6 border-t border-white/[0.04]">
          <div className="absolute inset-0 z-0 pointer-events-none flex justify-center items-center overflow-hidden" style={{ perspective: "1200px" }} aria-hidden="true">
            <div
              className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 flex gap-[80vw] sm:gap-[650px] md:gap-[750px] lg:gap-[900px]"
              style={{ transformStyle: "preserve-3d", transform: "rotateX(20deg) rotateY(-10deg) rotateZ(-5deg) scale(1.15)" }}
            >
              <div className="flex flex-col gap-10 md:gap-14 -translate-y-[15vh]" style={{ transformStyle: "preserve-3d" }}>
                <div className="w-[280px] h-[320px] rounded-[32px] bg-white/10 border border-white/10 backdrop-blur-xl shadow-[0_20px_40px_-10px_rgba(0,0,0,0.8)]" style={{ transform: "translateZ(100px) rotateY(10deg)" }} />
                <div className="w-[280px] h-[220px] rounded-[32px] bg-white/5 border border-white/10 backdrop-blur-xl shadow-[0_20px_40px_-10px_rgba(0,0,0,0.8)]" style={{ transform: "translateZ(40px) rotateY(15deg)" }} />
              </div>
              <div className="flex flex-col gap-10 md:gap-14 translate-y-[5vh]" style={{ transformStyle: "preserve-3d" }}>
                <div className="w-[280px] h-[220px] rounded-[32px] bg-white/5 border border-white/10 backdrop-blur-xl shadow-[0_20px_40px_-10px_rgba(0,0,0,0.8)]" style={{ transform: "translateZ(80px) rotateY(-15deg)" }} />
                <div className="w-[280px] h-[320px] rounded-[32px] bg-white/10 border border-white/10 backdrop-blur-xl shadow-[0_20px_40px_-10px_rgba(0,0,0,0.8)]" style={{ transform: "translateZ(140px) rotateY(-10deg)" }} />
              </div>
            </div>
          </div>

          <div className="absolute inset-0 z-0 pointer-events-none flex items-center justify-center" aria-hidden="true">
            <div className="absolute w-[120vw] md:w-[70vw] max-w-[1000px] aspect-square bg-[radial-gradient(circle_at_center,rgba(203,162,115,0.2)_0%,transparent_65%)] rounded-full blur-[100px]" />
          </div>
          
          <motion.div
            initial={{ opacity: 0, y: 24 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 1, ease: [0.16, 1, 0.3, 1] }}
            className="relative z-10 flex flex-col items-center gap-7 w-full max-w-2xl"
          >
            <h2 className="font-medium tracking-tight text-white leading-[1.05]" style={{ fontFamily: "'Instrument Serif', serif", fontSize: "clamp(52px, 8vw, 88px)" }}>
              Bring PMRS to your school
            </h2>
            <p className="text-white/60 text-[18px] md:text-[20px] font-medium leading-relaxed max-w-md">
              Get in touch and we'll set up your school's workspace during our early access phase.
            </p>
            <div className="mt-8">
              <a href={`${BASE_URL}/register`} target="_blank" rel="noopener noreferrer" aria-label="Request early access for PMRS application" className="group relative inline-flex items-center gap-3 px-10 py-5 rounded-full bg-white text-[#0c0d10] font-bold text-[16px] tracking-wide hover:-translate-y-1 hover:shadow-[0_20px_40px_-10px_rgba(255,255,255,0.15)] transition-all duration-500">
                Request early access 
                <ArrowRight className="w-5 h-5 text-[#0c0d10]/60 group-hover:text-[#0c0d10] group-hover:translate-x-1 transition-all duration-500" aria-hidden="true" />
              </a>
            </div>
          </motion.div>
        </section>
      </main>

      <SiteFooter />
    </div>
  );
}

function FounderCard({ founder, index, isLast }) {
  return (
    <div className={`flex flex-col items-center pb-12 pt-12 ${isLast ? '' : 'border-b md:border-b-0 md:border-r'} border-[#e5e7eb]`}>
      <div className="relative w-full max-w-[320px] aspect-square mb-8">
        <div className="absolute inset-0 bg-gradient-to-b from-[#e0f2fe] to-white rounded-t-3xl" aria-hidden="true" />
        {founder.image ? (
          <img 
            src={founder.image} 
            alt={`Portrait of ${founder.name}, ${founder.role} at PMRS focusing on ${founder.focus}`} 
            className="absolute inset-0 w-full h-full object-cover object-bottom" 
            style={{ WebkitMaskImage: "linear-gradient(to bottom, black 60%, transparent 100%)", maskImage: "linear-gradient(to bottom, black 60%, transparent 100%)" }} 
          />
        ) : (
          <div className="absolute inset-0 flex items-end justify-center" style={{ WebkitMaskImage: "linear-gradient(to bottom, black 60%, transparent 100%)", maskImage: "linear-gradient(to bottom, black 60%, transparent 100%)" }} role="img" aria-label={`Avatar icon for ${founder.name}, ${founder.role}`}>
            <svg viewBox="0 0 24 24" fill="currentColor" className="w-[75%] h-[75%] text-[#0ea5e9]/20 -mb-4" aria-hidden="true">
              <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm0 2c-3.33 0-10 1.67-10 5v3h20v-3c0-3.33-6.67-5-10-5z" />
            </svg>
          </div>
        )}
      </div>

      <h3 className="font-bold text-[28px] text-[#152238] mb-1" style={{ fontFamily: "'Instrument Serif', serif" }}>{founder.name}</h3>
      <p className="text-[14px] font-sans text-[#6b7280] tracking-wide uppercase font-semibold mb-2">{founder.role} — {founder.focus}</p>
      <p className="text-[#6b7280] font-sans max-w-sm text-center text-[15px] leading-relaxed px-4 mb-6">{founder.bio}</p>
      
      <div className="flex w-12 h-[2px]" aria-hidden="true">
        <div className="w-1/2 bg-[#3b82f6]" />
        <div className="w-1/2 bg-[#e5e7eb]" />
      </div>
    </div>
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

function SiteFooter() {
  const reduceMotion = useReducedMotion();
  const [showTop, setShowTop] = useState(false);

  useEffect(() => {
    const onScroll = () => setShowTop(window.scrollY > 900);
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  return (
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
            transform: "translateY(15%)"
          }}
        >
          PMRS
        </h2>
      </div>

      <div className="relative z-10 mx-auto max-w-[1200px] px-6 flex flex-col gap-16">
        <div className="grid grid-cols-1 md:grid-cols-12 gap-12 md:gap-8">
          <motion.div initial={{ opacity: 0 }} whileInView={{ opacity: 1 }} viewport={{ once: true }} className="md:col-span-4 flex flex-col gap-5">
            <div className="flex items-center gap-2">
               <img src="/logo.png" alt="PMRS Logo" className="h-8 md:h-9 w-auto object-contain" />
           
            </div>
            <p className="text-[#1a1a1a]/60 text-[15px] font-medium leading-relaxed max-w-sm">
              Performance Monitoring & Remedial System — built for CBSE schools, one real classroom at a time.
            </p>
          </motion.div>

          <div className="md:col-span-6 flex flex-wrap gap-x-16 gap-y-10 md:justify-center">
            {[
              { title: "Product", links: [{ l: "Features", h: "#features" }, { l: "How it works", h: "#how-it-works" }, { l: "Pricing", h: "/pricing" }, { l: "For schools", h: "#for-schools" }] },
              { title: "Company", links: [{ l: "Founders", h: "#founders" }, { l: "Contact Us", h: "mailto:pmrsteam.official@gmail.com" }] },
              { title: "Legal", links: [{ l: "Privacy Policy", h: "#" }, { l: "Terms of Service", h: "#" }, { l: "Data Processing Agreement", h: "#" }] },
            ].map((col, ci) => (
              <motion.div
                key={col.title}
                initial={{ opacity: 0, y: 12 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ delay: ci * 0.08 }}
                className="flex flex-col gap-5"
              >
                <span className="text-[13px] font-bold uppercase tracking-widest text-[#19181a]/40">{col.title}</span>
                <div className="flex flex-col gap-3">
                  {col.links.map((link) => (
                    <FooterLink key={link.l} href={link.h}>{link.l}</FooterLink>
                  ))}
                </div>
              </motion.div>
            ))}
          </div>

          <motion.div
            initial={{ opacity: 0, x: 12 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            className="md:col-span-2 flex flex-row md:flex-col gap-4 justify-start md:items-end"
          >
            {[
              { Icon: Linkedin, label: "Visit PMRS on LinkedIn", href: "https://linkedin.com" },
              { Icon: Instagram, label: "Visit PMRS on Instagram", href: "https://instagram.com" },
              { Icon: Mail, label: "Contact PMRS via Email", href: "mailto:pmrsteam.official@gmail.com" },
            ].map(({ Icon, label, href }) => (
              <motion.a
                key={label}
                href={href}
                target="_blank"
                rel="noopener noreferrer"
                aria-label={label}
                whileHover={reduceMotion ? {} : { y: -3, backgroundColor: MARIGOLD, color: NAVY }}
                transition={{ duration: 0.2 }}
                className="w-11 h-11 rounded-xl flex items-center justify-center text-white shadow-sm"
                style={{ backgroundColor: NAVY }}
              >
                <Icon className="w-4 h-4" aria-hidden="true" />
              </motion.a>
            ))}
          </motion.div>
        </div>

        <div className="flex flex-col sm:flex-row justify-between items-center gap-4 border-t border-black/10 pt-8 mt-4">
          <span className="text-[13px] font-medium text-[#19181a]/50 text-center sm:text-left">
            Â© {new Date().getFullYear()} DevMax Educational Solutions. All rights reserved.
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
        className="fixed bottom-8 right-8 z-[100] w-12 h-12 rounded-full flex items-center justify-center text-white shadow-xl hover:scale-110 transition-transform"
        style={{ backgroundColor: NAVY }}
      >
        <ArrowRight className="w-5 h-5 -rotate-90" aria-hidden="true" />
      </motion.button>
    </footer>
  );
}

