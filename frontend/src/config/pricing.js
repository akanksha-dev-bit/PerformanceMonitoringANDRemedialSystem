/** Display copy only. Prices, capacity, and access are supplied by Laravel /api/plans. */
export const PLAN_PRESENTATION = {
  starter: { description: "Essential academic management for growing schools.", highlighted: false, badge: null, cta: "Get Started", ctaType: "secondary" },
  professional: { description: "Performance insight and academic management for larger teams.", highlighted: true, badge: "Most Popular", cta: "Choose Professional", ctaType: "primary" },
  enterprise: { description: "Higher student capacity for larger institutions.", highlighted: false, badge: null, cta: "Choose Enterprise", ctaType: "outline" },
};

export const FEATURE_LABELS = {
  students: "Student management",
  teachers: "Teacher management",
  academic_structure: "Classes, sections, subjects and academic years",
  attendance: "Attendance management",
  marks: "Marks management",
  performance: "Performance monitoring",
  remedial: "Remedial activities",
  basic_reports: "Basic academic reports",
  quizzes: "Quizzes",
  timetable: "Timetable management",
  allocations: "Teacher-subject allocations",
  attendance_analytics: "Attendance analytics",
};

export const FEATURE_GROUPS = [
  { category: "Academic management", features: ["students", "teachers", "academic_structure", "timetable", "allocations"] },
  { category: "Attendance & performance", features: ["attendance", "marks", "performance", "remedial", "attendance_analytics"] },
  { category: "Learning & reports", features: ["quizzes", "basic_reports"] },
];
export const ADDONS = [
  {
    id: "ai-learning",
    title: "AI Personalized Learning",
    description: "Personalized explanations, remedial recommendations, study plans and institution-approved learning assistance.",
    badge: "Coming Soon",
    cta: null,
  },
  {
    id: "custom-reports",
    title: "Custom Reports",
    description: "Reports designed specifically around your institution's reporting requirements.",
    badge: null,
    cta: "Talk to us",
  },
  {
    id: "extra-capacity",
    title: "Additional Student Capacity",
    description: "Expand your plan when your institution grows beyond the included student limit.",
    badge: null,
    cta: "Contact Us",
  },
  {
    id: "integrations",
    title: "Custom Integrations",
    description: "Discuss integrations with existing school systems and third-party services.",
    badge: null,
    cta: "Contact Us",
  },
];

export const PRICING_STEPS = [
  { step: "01", title: "Choose your plan", description: "Select the plan based on your institution's student strength and requirements." },
  { step: "02", title: "Get onboarded", description: "We configure the institution and help administrators get started." },
  { step: "03", title: "Grow with PMRS", description: "Upgrade your plan as your student population and requirements grow." },
];

export const PRICING_NOTES = [
  "Plans are currently offered as annual SaaS subscriptions.",
  "Student limits refer to active students enrolled in the institution.",
  "Schools can upgrade to a higher plan as they grow.",
  "Institutions above 1,000 students can request custom pricing.",
  "Custom integrations and institution-specific requirements may require separate pricing.",
  "AI capabilities marked \"Coming Soon\" are not currently included as active production features.",
  "Final implementation requirements can be discussed during onboarding.",
];

export const FAQ = [
  { category: "billing", question: "Is PMRS billed monthly or annually?", answer: "PMRS plans are currently offered as annual SaaS subscriptions." },
  { category: "billing", question: "How many students can I add?", answer: "Each plan includes a defined active-student limit. Schools can upgrade as their student population grows." },
  { category: "billing", question: "Can we upgrade later?", answer: "Yes. Institutions can move to a higher plan when their requirements increase." },
  { category: "platform", question: "Is hosting included?", answer: "The PMRS SaaS subscription includes the platform infrastructure required to operate the service." },
  { category: "onboarding", question: "Do you provide onboarding?", answer: "Professional and Enterprise customers can receive onboarding assistance based on the implementation requirements." },
  { category: "onboarding", question: "Can PMRS be customized?", answer: "Enterprise institutions can discuss custom reports, integrations and institution-specific requirements." },
  { category: "platform", question: "Is AI currently available?", answer: "AI-powered personalized learning and RAG-based institutional assistance are planned capabilities and will be introduced separately. Features marked Coming Soon are not currently part of the production platform." },
];

export const CONTACT_EMAIL = "pmrsteam.official@gmail.com";
