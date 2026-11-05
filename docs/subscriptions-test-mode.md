# PMRS subscription rollout (mocked Razorpay tests)

## Current application audit

- React on Vercel serves the public site and migrated admin dashboards, students, teachers, academic structure, attendance, and marks. Performance, remedial, quizzes, reports, and profile still have React placeholder routes backed by Laravel Blade pages.
- Laravel on Render provides authentication, Blade routes, and JSON endpoints in `routes/web.php`. School data is stored in PostgreSQL on Neon. There was no subscription or Razorpay implementation before this change.
- Existing schools have no subscription row. The new catalog treats them as `legacy` so their access and records remain intact. Production Neon data was not inspected; no migration or backfill was run against it.

## Plan catalog

`backend/config/plans.php` is authoritative for prices, one-time setup fees, active-student limits, and feature IDs. Starter costs ₹59,000/year for 200 active students. Professional costs ₹89,000/year for 500. Enterprise costs ₹1,39,000/year for 1,000. Setup fees default to ₹0 for all plans. The public pricing UI reads `GET /api/plans`; React does not calculate the payable amount. Laravel calculates the order total in paise.

All three plans include currently implemented student, teacher, academic-structure, timetable, attendance, marks, performance, remedial, quiz, and basic-report features. Professional and Enterprise add teacher allocations and attendance analytics. Enterprise currently differs by student capacity. AI personalization, data export, custom reports, custom integrations, and support levels are not represented as active entitlements because they have not been verified as implemented or contracted.

## Lifecycle and routes

- Public pricing CTA → Laravel `/register?plan=<slug>` → school and administrator. When checkout and enforcement are both enabled, the subscription starts `pending`. If either rollout switch is disabled, it starts `provisional`: explicitly unpaid Starter-level access capped at 200 active students, regardless of the selected future paid plan. It never becomes `active` without a verified captured payment. The status persists when the rollout switches change, so these schools do not suddenly lose access.
- `GET /api/subscription` and authenticated `GET /api/user` expose status, validity dates, student usage, and entitlement IDs.
- React `/billing` shows the selected plan, current status, usage, payment history, and annual plan choices. Only administrators can create and verify orders. Mid-term changes to a different active plan require PMRS review; same-plan renewal extends the period by one year.
- `POST /api/payments/order` commits a uniquely receipted local `creating` payment before calling Razorpay **Test Mode**. Duplicate requests reuse the current matching pending order. A different choice supersedes older outstanding orders; a same-plan order still being created returns a retry response. Same-plan active renewals are allowed, while mid-term changes require PMRS review.
- `POST /api/payments/verify` checks the checkout HMAC and independently fetches the captured payment and paid order. It validates IDs, amount, INR currency, receipt, and school note before transactional activation.
- `POST /api/payments/webhook` verifies the raw-body HMAC, records the event ID for replay protection, and applies captured or failed events. If an external order was created but its local order ID was not saved, the signed webhook can fetch that order and reattach it using the committed receipt, school note, amount, and currency. A captured superseded order is recorded as `paid_review` and cannot replace the active plan or extend its term; it requires manual investigation. `GET /api/payments` is school-scoped.
- Protected academic routes return `SUBSCRIPTION_REQUIRED` when enforcement is enabled and a school is pending, expired, or cancelled. Legacy schools with no row keep existing access. Provisional schools have explicit Starter-level access and no premium allocations or attendance analytics. Premium Blade and JSON routes are checked by Laravel, independent of React. Student creation and reactivation check capacity while holding the school row lock. Inactive student accounts are denied at login and on every protected request, including existing sessions; invited students must complete their profile before academic access.

## Safe configuration

Render backend: set `RAZORPAY_MODE=test`. Leave `PMRS_TEST_CHECKOUT_ENABLED=false` and `PMRS_SUBSCRIPTION_ENFORCEMENT_ENABLED=false` until the end-to-end test and existing-school review are complete. Keep `PMRS_STARTER_SETUP_FEE_INR=0`, `PMRS_PROFESSIONAL_SETUP_FEE_INR=0`, and `PMRS_ENTERPRISE_SETUP_FEE_INR=0` until fees are approved. `RAZORPAY_KEY_ID`, `RAZORPAY_KEY_SECRET`, and `RAZORPAY_WEBHOOK_SECRET` are backend-only and currently unset; do not add live keys. Configure `FRONTEND_URL=https://www.pmrs.live`, `APP_URL=https://api.pmrs.live`, `CORS_ALLOWED_ORIGINS` for the actual frontend origins, `SESSION_DOMAIN=.pmrs.live`, and secure cookies before any cross-domain acceptance check.

Vercel frontend: `VITE_BACKEND_URL=https://api.pmrs.live`. Do not put secret keys in a `VITE_` variable. The public Razorpay test key comes from the order response only when test checkout is enabled.

The two added migrations create only new subscription, payment, and webhook-event tables. No existing school, user, or student rows are changed. The test-only SQLite migration compatibility fixes leave PostgreSQL logic unchanged. Deploy the new tables before code that reads subscriptions. Confirm whether the older student migration is pending and its classroom backfill is complete before any production migration. Do not run `migrate:fresh` or reset the Neon database.

## Verification status and remaining work

Automated tests use mocked Razorpay responses only; no Razorpay network request or transaction has been made. A real Test Mode payment, webhook delivery, Render/Vercel cookie flow, and Neon schema/backfill review remain unverified. If no webhook arrives after a provider order was created but local persistence failed, an operator must compare the local receipt against Razorpay Test Mode records and resolve the `creating` payment manually. Any `paid_review` record also requires manual reconciliation; it never grants access automatically. True simultaneous webhook and student-activation tests need a PostgreSQL integration environment because SQLite in-memory tests cannot validate PostgreSQL row-lock behaviour. Payment code should remain local and production charging disabled until these checks and the final setup fees are approved. The remaining Blade feature pages can migrate to React in later phases; billing is the first React page in this change.

Local verification on 18 October 2026: the complete Laravel suite passed (62 tests, 309 assertions) using SQLite in memory and mocked Razorpay HTTP responses. The Vite production build passed; it reported a non-failing large-chunk warning. No production database operation was performed during this remediation.
