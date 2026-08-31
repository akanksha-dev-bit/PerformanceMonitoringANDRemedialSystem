/**
 * ============================================================================
 * config/pricing.js - Central Pricing Configuration
 * ============================================================================
 * Single source of truth for ALL pricing data displayed on the PMRS website.
 *
 * Every pricing card, comparison table, FAQ, and CTA reads from here.
 * To update pricing, change ONLY this file.
 *
 * IMPORTANT:
 *   - These are display-only values. No backend enforcement yet.
 *   - Do not claim features that are not implemented.
 *   - AI features are marked "Coming Soon" - not currently available.
 * ============================================================================
 */

export const PLANS = [
  {
    id: "starter",
    name: "Starter",
    price: 49000,
    priceDisplay: "\u20B949,000",
    billingPeriod: "year",
    monthlyEquivalent: "\u20B94,083",
    studentLimit: 200,
    teacherLimit: 20,
    adminLimit: 1,
    description: "Essential academic management for growing schools.",
    highlighted: false,
    badge: null,
    cta: "Get Started",
    ctaType: "secondary",
    features: [
      "Student Management",
      "Teacher Management",
      "Admin Dashboard",
      "Student & Teacher Accounts",
      "Classes & Sections",
      "Attendance Management",
      "Marks Management",
      "Basic Performance Monitoring",
      "Remedial Activity Management",
      "Academic Year Management",
      "Timetable Management",
      "Basic Academic Reports",
      "Email Support",
    ],
  },
  {
    id: "professional",
    name: "Professional",
    price: 79000,
    priceDisplay: "\u20B979,000",
    billingPeriod: "year",
    monthlyEquivalent: "\u20B96,583",
    studentLimit: 500,
    teacherLimit: 50,
    adminLimit: 3,
    description: "Advanced performance monitoring and academic management.",
    highlighted: true,
    badge: "Most Popular",
    cta: "Choose Professional",
    ctaType: "primary",
    features: [
      "Everything in Starter",
      "Advanced Performance Analytics",
      "Performance Trends",
      "Advanced Reports",
      "Teacher Allocations",
      "Advanced Timetable Management",
      "Data Export",
      "Advanced Remedial Monitoring",
      "Priority Support",
      "Enhanced Administrative Controls",
    ],
  },
  {
    id: "enterprise",
    name: "Enterprise",
    price: 129000,
    priceDisplay: "\u20B91,29,000",
    billingPeriod: "year",
    monthlyEquivalent: "\u20B910,750",
    studentLimit: 1000,
    teacherLimit: 100,
    adminLimit: 5,
    description: "Complete institutional platform for larger schools.",
    highlighted: false,
    badge: null,
    cta: "Contact Us",
    ctaType: "outline",
    features: [
      "Everything in Professional",
      "Advanced Analytics",
      "Custom Reports",
      "Dedicated Onboarding",
      "Priority Support",
      "Institution-Specific Configuration",
      "Higher Usage Limits",
      "Custom Integrations",
      "Future AI Personalized Learning",
      "Future RAG-Based Institutional Knowledge Assistant",
    ],
  },
];

export const USER_LIMITS = [
  { plan: "Starter",      students: "Up to 200",   teachers: "Up to 20",  admins: "1"        },
  { plan: "Professional", students: "Up to 500",   teachers: "Up to 50",  admins: "Up to 3"  },
  { plan: "Enterprise",   students: "Up to 1,000", teachers: "Up to 100", admins: "Up to 5"  },
];

export const FEATURE_COMPARISON = [
  {
    category: "Academic Management",
    features: [
      { name: "Student Management",   starter: true,  professional: true,  enterprise: true  },
      { name: "Teacher Management",   starter: true,  professional: true,  enterprise: true  },
      { name: "Classes & Sections",   starter: true,  professional: true,  enterprise: true  },
      { name: "Subjects",             starter: true,  professional: true,  enterprise: true  },
      { name: "Academic Years",       starter: true,  professional: true,  enterprise: true  },
      { name: "Teacher Allocations",  starter: false, professional: true,  enterprise: true  },
      { name: "Timetable",            starter: true,  professional: true,  enterprise: true  },
    ],
  },
  {
    category: "Attendance & Performance",
    features: [
      { name: "Attendance Management",        starter: true,  professional: true,  enterprise: true  },
      { name: "Marks Management",             starter: true,  professional: true,  enterprise: true  },
      { name: "Performance Monitoring",       starter: true,  professional: true,  enterprise: true  },
      { name: "Performance Trends",           starter: false, professional: true,  enterprise: true  },
      { name: "Remedial Activities",          starter: true,  professional: true,  enterprise: true  },
      { name: "Advanced Remedial Monitoring", starter: false, professional: true,  enterprise: true  },
    ],
  },
  {
    category: "Reports & Administration",
    features: [
      { name: "Basic Reports",            starter: true,  professional: true,  enterprise: true  },
      { name: "Advanced Reports",          starter: false, professional: true,  enterprise: true  },
      { name: "Data Export",               starter: false, professional: true,  enterprise: true  },
      { name: "Custom Reports",            starter: false, professional: false, enterprise: true  },
      { name: "Admin Dashboard",           starter: true,  professional: true,  enterprise: true  },
      { name: "Institution Configuration", starter: false, professional: false, enterprise: true  },
    ],
  },
  {
    category: "Support",
    features: [
      { name: "Email Support",        starter: true,  professional: true,  enterprise: true  },
      { name: "Priority Support",     starter: false, professional: true,  enterprise: true  },
      { name: "Dedicated Onboarding", starter: false, professional: false, enterprise: true  },
    ],
  },
  {
    category: "Future AI",
    features: [
      { name: "AI Personalized Learning",                    starter: false, professional: false, enterprise: "Coming Soon" },
      { name: "AI Study Assistant",                           starter: false, professional: false, enterprise: "Coming Soon" },
      { name: "RAG-based Institutional Knowledge Assistant",  starter: false, professional: false, enterprise: "Coming Soon" },
    ],
  },
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
  { question: "Is PMRS billed monthly or annually?", answer: "PMRS plans are currently offered as annual SaaS subscriptions." },
  { question: "How many students can I add?", answer: "Each plan includes a defined active-student limit. Schools can upgrade as their student population grows." },
  { question: "Can we upgrade later?", answer: "Yes. Institutions can move to a higher plan when their requirements increase." },
  { question: "Is hosting included?", answer: "The PMRS SaaS subscription includes the platform infrastructure required to operate the service." },
  { question: "Do you provide onboarding?", answer: "Professional and Enterprise customers can receive onboarding assistance based on the implementation requirements." },
  { question: "Can PMRS be customized?", answer: "Enterprise institutions can discuss custom reports, integrations and institution-specific requirements." },
  { question: "Is AI currently available?", answer: "AI-powered personalized learning and RAG-based institutional assistance are planned capabilities and will be introduced separately. Features marked Coming Soon are not currently part of the production platform." },
];

export const CONTACT_EMAIL = "pmrsteam.official@gmail.com";