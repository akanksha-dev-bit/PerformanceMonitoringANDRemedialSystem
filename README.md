# 📊 Performance Monitoring and Remedial System (PMRS)

> A full-stack web application designed to help schools **track student academic performance**, **identify at-risk and slow learners**, **assign targeted remedial actions**, and **conduct adaptive quizzes** — all within a secure, role-based, multi-tenant school environment.

---

## 🌟 Overview

The **Performance Monitoring and Remedial System (PMRS)** is a modern educational management platform built for schools that want to move beyond spreadsheets and manual reporting. It provides a unified dashboard for **Admins**, **Teachers**, and **Students**, each with their own tailored views and actions.

The system automatically evaluates student performance based on marks, categorizes students as **Good**, **At Risk**, or **Slow Learner**, enables teachers to assign structured remedial interventions, and supports a fully interactive **Adaptive Quiz Platform** where students can practice and improve.

---

## 🎯 Key Features

### 🏫 Multi-Tenant School Architecture
- Every school is completely **isolated** — no cross-school data leakage
- Each school gets a unique **school code** (e.g., `PMRS-AB3XY2`) auto-generated on registration
- Teachers and students join a school via a **public invite link** (`/join/{school_code}`)
- Global `SchoolScope` automatically filters every database query to the authenticated user's school
- Cross-school URL tampering returns `404 Not Found` — enforced at SQL level via Route Model Binding

### 👥 Role-Based Access Control
| Role | Capabilities |
|---|---|
| **Admin** | Register a school, manage teachers & subjects, view all analytics, generate invite links |
| **Teacher** | Manage students, enter marks, assign remedial tasks, create & assign quizzes |
| **Student** | View personal marks, progress charts, complete assigned quizzes, track remedial tasks |

### 📈 Performance Analytics & Slow Learner Detection
- Automatic performance grading based on marks:
  - ✅ **Good** — Average ≥ 60%
  - ⚠️ **At Risk** — Average between 40% and 59%
  - 🔴 **Slow Learner** — Average < 40%, **or** failed in 2 or more subjects
- Subject-wise performance breakdown with color-coded charts
- Class rank calculated automatically within same class & section
- Trend chart showing average performance across academic years

### 📝 Mark Management
- Enter marks by subject, exam type (`Unit Test`, `Midterm`, `Final`, `Practical`), and academic year
- Percentage and pass/fail status computed automatically per entry
- Pass threshold: **40%** per subject

### 🛠️ Remedial Action System
Assign targeted interventions to struggling students:
- **Action Types**: Extra Class, Counseling, Peer Tutoring, Assignment, Parent Meeting, Quiz Test, Practice Session
- **Status Tracking**: Pending → In Progress → Completed / Cancelled
- Set scheduled dates, completion dates, and record outcomes
- Students see all their tasks in the "My Workspace" dashboard

### 🧩 Adaptive Quiz Platform
A fully interactive quiz system built inside PMRS:
- **Teachers** create MCQ quizzes with title, subject, difficulty (`Easy`/`Medium`/`Hard`), duration, and per-question marks
- **Teachers** assign quizzes to individual students with a date range and number of allowed attempts (`repeat_days`)
- **Students** take quizzes in a **fullscreen distraction-free interface** with a timer, progress bar, and option selection
- **Auto-grading** — server grades all answers instantly on submission
- **Results page** shows score, percentage, XP earned, and per-question breakdown with correct answers and expert explanations
- **Daily attempt limit** — one attempt per day maximum; total attempts capped by `repeat_days`

### 🎮 Student Gamification
- **XP Points** — earned by completing tasks and recording exam marks
- **Study Streak** — days since last mark was recorded
- **Class Rank** — calculated live against classmates in same class & section
- **Achievement Badges** — "Top Scorer 🏆", "Class Topper 🥇", "5 Exams Done 🔥", "All Subjects Passing ✅"

### 🔍 Global Search
- `Ctrl+K` keyboard shortcut opens the search bar
- Search across students, teachers, and subjects in real time
- All results are automatically scoped to the authenticated user's school

### 🔒 Tenant Audit Logging
- Every authenticated request is logged with: `[School: X, User: Y, Role: Z] METHOD /url`
- Logs stored in `storage/logs/laravel.log` for auditing and debugging

### 📋 Reports
- School-wide academic summary
- Class-wise breakdown (total students, slow learners, performing well)

### 🌐 Public Landing Page
- A modern React-based landing page (`/frontend`) introducing the PMRS platform
- Features hero section, feature highlights, and call-to-action buttons

---

## 🏗️ Project Architecture

```
PerformanceMonitoringANDRemedialSystem/
├── backend/      # Laravel 11 — MVC + Blade Dashboards + Auth
└── frontend/     # React 19 + Vite — Public Landing Page
```

### Backend (Laravel 11)
- Handles all business logic, authentication, role-based routing
- Server-rendered Blade templates for all dashboards
- **Multi-tenant isolation** via Global Eloquent Scopes
- **Laravel Breeze** for authentication scaffolding

### Frontend (React 19 + Vite)
- Standalone React app serving as the public-facing landing page
- Completely separate from the backend — runs on its own dev server

---

## 🛠️ Tech Stack

### Backend
| Technology | Version | Purpose |
|---|---|---|
| **PHP** | ^8.2 | Server-side language |
| **Laravel** | ^11.x | MVC Web Framework |
| **Laravel Breeze** | ^2.x | Authentication scaffolding |
| **MySQL** | — | Production database (via XAMPP) |
| **Blade** | — | Server-side templating engine |
| **Chart.js** | ^4.4 | Performance charts |
| **Vanilla CSS** | — | Custom design system |
| **Vite** | — | Asset bundling |

### Frontend
| Technology | Version | Purpose |
|---|---|---|
| **React** | ^19.0 | UI Library |
| **Vite** | ^6.x | Build tool & dev server |
| **Tailwind CSS** | ^4.x | Utility-first CSS framework |
| **Lucide React** | latest | Icon library |
| **Motion** | ^12.x | Animations |

---

## 📦 Database Schema

| Table | Description |
|---|---|
| `schools` | Registered schools — name, `school_code` (unique), address |
| `users` | All users — name, email, password, `role` (admin/teacher/student), `school_id` |
| `students` | Student profiles — roll_no, class, section, DOB, guardian info, `school_id` |
| `teachers` | Teacher profiles — linked to user, primary subject, `school_id` |
| `teacher_assignments` | Maps teachers to class/section/subject combinations |
| `subjects` | School subjects — name, code (unique per school), class, max_marks, `school_id` |
| `marks` | Exam results — student_id, subject_id, marks_obtained, exam_type, academic_year, `school_id` |
| `remedial_actions` | Remedial tasks — student_id, action_type, title, status, scheduled_date, `school_id` |
| `quizzes` | Quiz definitions — title, subject, difficulty, duration, created_by, `school_id` |
| `quiz_questions` | MCQ questions — quiz_id, options A/B/C/D, correct_answer, explanation, marks |
| `student_quiz_assignments` | Quiz-to-student assignments — start/end date, repeat_days, status |
| `quiz_attempts` | Student attempts — answers (JSON), score, percentage, completed_at |

> **Note:** Every tenant table includes `school_id` to enforce strict data isolation across schools.

---

## 🚀 Getting Started

### Prerequisites
- **PHP** >= 8.2
- **Composer**
- **Node.js** >= 18.x & **npm**
- **XAMPP** (with MySQL running)

---

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/PerformanceMonitoringANDRemedialSystem.git
cd PerformanceMonitoringANDRemedialSystem
```

---

### 2. Set Up the Backend (Laravel)

```bash
cd backend
```

**Install PHP dependencies:**
```bash
composer install
```

**Copy and configure the environment file:**
```bash
cp .env.example .env
```

Edit `.env` for MySQL:
```env
APP_NAME="Performance Monitoring and Remedial System"
APP_ENV=local
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pmrs_db
DB_USERNAME=root
DB_PASSWORD=
```

**Generate the application key:**
```bash
php artisan key:generate
```

**Run database migrations:**
```bash
php artisan migrate
```

**Start the backend server:**
```bash
php artisan serve
```

Backend available at: **http://127.0.0.1:8000**

---

### 3. Set Up the Frontend (React)

Open a **new terminal**, then:

```bash
cd frontend
npm install
npm run dev
```

Frontend landing page available at: **http://localhost:5173**

---

## 🔐 Authentication & User Flow

PMRS uses **Laravel Breeze** for session-based authentication.

### Admin Registration
1. Admin visits `/register`
2. Fills in school name + personal details
3. System auto-creates a **School** record with a unique code (e.g., `PMRS-AB3XY2`)
4. Admin is logged in as the school owner

### Teacher / Student Joining
1. Admin shares the invite link: `http://127.0.0.1:8000/join/{school_code}`
2. User registers via the invite form
3. System sets `school_id` automatically to that school
4. **Students** are redirected to complete their profile (class, section, roll number, DOB)

### Profile Completion (Students)
After first login, students must fill their academic profile before accessing any dashboard features. This is enforced by `EnsureProfileCompleted` middleware.

---

## 🧭 Application Routes

### Public Routes
| Method | URL | Description |
|---|---|---|
| GET | `/join/{school_code}` | View school join/register page |
| POST | `/join/{school_code}` | Register and join a school (rate-limited: 10/min) |

### Authenticated Routes
| URL | Description | Access |
|---|---|---|
| `/dashboard` | Role-based redirect | All |
| `/dashboard/admin` | Admin KPI dashboard | Admin |
| `/dashboard/teacher` | Teacher overview | Teacher |
| `/dashboard/student` | Student performance home | Student |
| `/my-progress` | Student detailed progress | Student |
| `/my-tasks` | Student workspace — tasks + quizzes + XP | Student |
| `/students` | Student CRUD | Teacher / Admin |
| `/marks` | Mark entry & listing | Teacher / Admin |
| `/performance` | Performance overview all students | All |
| `/performance/slow-learners` | Slow learner list | All |
| `/remedial` | Remedial actions CRUD | Teacher / Admin |
| `/quizzes` | Quiz management | Teacher / Admin |
| `/quizzes/{quiz}/assign` | Assign quiz to student | Teacher / Admin |
| `/quiz/{assignment}/start` | Start quiz attempt | Student |
| `/quiz/attempt/{attempt}` | Fullscreen quiz UI | Student |
| `/quiz/attempt/{attempt}/results` | Quiz results page | Student |
| `/reports` | School-wide report | All |
| `/search` | Global search (AJAX) | All |

### Admin-Only Routes
| URL | Description |
|---|---|
| `/subjects` | Subject CRUD |
| `/teachers` | Teacher management |

---

## 📁 Project Structure

### Backend
```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/           # Feature controllers (19 files)
│   │   │   ├── Auth/              # Breeze auth controllers
│   │   │   ├── AdminDashboardController.php
│   │   │   ├── StudentDashboardController.php
│   │   │   ├── QuizController.php
│   │   │   ├── QuizAttemptController.php
│   │   │   └── ...
│   │   └── Middleware/
│   │       ├── EnsureProfileCompleted.php   # Force student profile setup
│   │       ├── RoleMiddleware.php           # Role-based route protection
│   │       └── TenantLoggerMiddleware.php   # Audit logging per request
│   ├── Models/                    # Eloquent models (12 files)
│   │   ├── User.php               # Auth model — eager loads school
│   │   ├── School.php             # Tenant root
│   │   ├── Student.php            # With performance computed attributes
│   │   ├── Quiz.php / QuizAttempt.php / ...
│   ├── Scopes/
│   │   └── SchoolScope.php        # Global tenant query scope
│   ├── Traits/
│   │   └── BelongsToSchool.php    # Auto school_id injection trait
│   └── Services/
│       ├── SlowLearnerService.php # Detect slow learners & at-risk
│       └── PerformanceService.php # Analytics calculations
├── database/
│   ├── migrations/                # 16 migration files
│   └── seeders/
├── resources/views/               # Blade templates
│   ├── layouts/app.blade.php      # Master layout + navbar
│   ├── dashboard/                 # Admin, Teacher, Student dashboards
│   ├── quiz/                      # Attempt UI + Results page
│   ├── students/ marks/ remedial/ subjects/ teachers/ performance/
└── routes/
    ├── web.php                    # All routes
    └── auth.php                   # Breeze auth routes
```

### Frontend
```
frontend/
├── src/
│   ├── App.jsx       # Landing page component
│   ├── main.jsx      # React entry point
│   └── index.css     # Global styles
├── index.html
└── vite.config.js
```

---

## 🎨 Performance Color Coding

Used consistently across all dashboards and views:

| Status | Criteria | Color |
|---|---|---|
| 🟢 **Good** | Average ≥ 60% | `#22c55e` (Green) |
| 🟡 **At Risk** | Average 40–59% | `#f59e0b` (Amber) |
| 🔴 **Slow Learner** | Average < 40% or failed 2+ subjects | `#ef4444` (Red) |
| ⚪ **Not Evaluated** | No marks entered yet | `#9ca3af` (Gray) |

---

## 🔒 Security Features

| Feature | Implementation |
|---|---|
| Authentication | Laravel Breeze (session-based) |
| Role protection | `RoleMiddleware` on admin-only routes |
| Profile enforcement | `EnsureProfileCompleted` middleware |
| Multi-tenant isolation | `SchoolScope` global query scope |
| URL tamper protection | Route Model Binding + SchoolScope → auto 404 |
| CSRF protection | `@csrf` on all forms (Laravel default) |
| Password hashing | `bcrypt` via `Hash::make()` |
| Rate limiting | Join route throttled at 10 req/min |
| Audit logging | `TenantLoggerMiddleware` on every request |

---

## 📌 Key Environment Variables (`backend/.env`)

```env
APP_NAME="Performance Monitoring and Remedial System"
APP_ENV=local
APP_KEY=base64:...           # Auto-generated with php artisan key:generate
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pmrs_db
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=log              # Logs emails locally, no SMTP needed for dev
```

---

## 📄 License

This project is open-sourced software licensed under the [MIT License](https://opensource.org/licenses/MIT).

---

## 👨‍💻 Author

Developed with ❤️ as an academic and real-world educational management tool.  
Built using **Laravel 11**, **React 19**, **MySQL**, and a passion for helping students succeed.

---

> _"Every student deserves a second chance. PMRS makes sure they get one."_
