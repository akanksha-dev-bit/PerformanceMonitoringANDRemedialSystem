# 📊 Performance Monitoring and Remedial System (PMRS)

> A full-stack web application designed to help schools **track student academic performance**, **identify at-risk and slow learners**, and **assign targeted remedial actions** — all within a clean, role-based multi-school environment.

---

## 🌟 Overview

The **Performance Monitoring and Remedial System (PMRS)** is a modern educational management platform built for schools that want to move beyond spreadsheets and manual reporting. It provides a unified dashboard for **Admins**, **Teachers**, and **Students**, each with their own tailored views and actions.

The system automatically evaluates student performance based on marks, categorizes students as **Good**, **At Risk**, or **Slow Learner**, and enables teachers and admins to assign structured remedial interventions.

---

## 🎯 Key Features

### 🏫 Multi-School Architecture
- Each school gets a unique **school code** (e.g., `PMRS-ABC123`)
- Students and teachers join a school using an invite link
- All data is fully scoped per school — no cross-school data leakage

### 👥 Role-Based Access Control
| Role | Capabilities |
|---|---|
| **Admin** | Manage teachers, subjects, view all students & performance reports |
| **Teacher** | Manage students, enter marks, assign remedial actions, view dashboards |
| **Student** | View personal performance, progress, and assigned remedial tasks |

### 📈 Performance Analytics
- Automatic performance grading based on marks:
  - ✅ **Good** — Average ≥ 60%
  - ⚠️ **At Risk** — Average between 40% and 59%
  - 🔴 **Slow Learner** — Average < 40%, or failed 2+ subjects
- View per-student breakdown by subject
- Identify and list all slow learners instantly

### 📝 Mark Management
- Enter marks by subject, exam type, and academic year
- Calculate percentage scores automatically
- Pass/fail status computed per subject (pass threshold: 40%)

### 🛠️ Remedial Action System
- Assign remedial actions (tutoring, counselling, extra sessions, etc.) to struggling students
- Track action status: `Pending`, `In Progress`, `Completed`, `Cancelled`
- Set scheduled and completed dates; record outcomes

### 🔍 Global Search
- Search across students, teachers, and subjects from a single search bar

### 📋 Reports
- Generate and view school-wide academic reports

### 🌐 Public Landing Page
- A modern React-based landing page introducing the PMRS platform
- Features hero section, feature highlights, and call-to-action

---

## 🏗️ Project Architecture

This project uses a **decoupled full-stack** approach:

```
PerformanceMonitoringANDRemedialSystem/
├── backend/      # Laravel 12 — API + Server-Rendered Dashboards
└── frontend/     # React 19 + Vite — Public Landing Page
```

### Backend (Laravel 12)
The backend powers all core business logic, authentication, and role-based dashboards. It uses **Blade templating** for server-rendered views and **Breeze** for authentication scaffolding.

### Frontend (React 19 + Vite)
The frontend is a standalone React application that serves as the **public-facing landing page** of PMRS. It is built with React 19, Vite 6, and Tailwind CSS v4.

---

## 🛠️ Tech Stack

### Backend
| Technology | Version | Purpose |
|---|---|---|
| **PHP** | ^8.2 | Server-side language |
| **Laravel** | ^12.0 | MVC Web Framework |
| **Laravel Breeze** | ^2.4 | Authentication scaffolding |
| **SQLite** | — | Default database (easily switchable) |
| **Blade** | — | Server-side templating engine |
| **Tailwind CSS** | ^3.x | Backend UI styling |

### Frontend
| Technology | Version | Purpose |
|---|---|---|
| **React** | ^19.0 | UI Library |
| **Vite** | ^6.2 | Build tool & dev server |
| **Tailwind CSS** | ^4.x | Utility-first CSS framework |
| **Lucide React** | ^0.546 | Icon library |
| **Motion** | ^12.x | Animations |
| **Google GenAI** | ^1.29 | AI integration |

---

## 📦 Database Schema

The application uses the following core tables:

| Table | Description |
|---|---|
| `schools` | Stores registered schools with a unique `school_code` |
| `users` | All users (admin, teacher, student) with role field |
| `students` | Student profiles linked to a user and school |
| `teachers` | Teacher profiles linked to a user |
| `teacher_assignments` | Maps teachers to schools |
| `subjects` | School subjects (name, code, class) |
| `marks` | Student marks per subject per exam type |
| `remedial_actions` | Remedial plans assigned to struggling students |

---

## 🚀 Getting Started

### Prerequisites

Make sure you have the following installed:
- **PHP** >= 8.2
- **Composer**
- **Node.js** >= 18.x & **npm**
- **XAMPP** (or any local server with MySQL/SQLite support)

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

Edit `.env` to set your database connection. The default uses SQLite:
```env
DB_CONNECTION=sqlite
```

**Generate the application key:**
```bash
php artisan key:generate
```

**Run database migrations:**
```bash
php artisan migrate
```

**Start the backend development server:**
```bash
php artisan serve
```

The backend will be available at: **http://127.0.0.1:8000**

---

### 3. Set Up the Frontend (React)

Open a **new terminal**, then:

```bash
cd frontend
```

**Install Node.js dependencies:**
```bash
npm install
```

**Start the frontend development server:**
```bash
npm run dev
```

The frontend landing page will be available at: **http://localhost:3000**

---

## 🔐 Authentication & User Roles

PMRS uses **Laravel Breeze** for authentication. There are three user roles:

- `admin` — Full school management access
- `teacher` — Student and marks management
- `student` — Personal performance view

### Joining a School
Students and teachers join a school via a unique invite URL:
```
http://127.0.0.1:8000/join/{school_code}
```
The school code is generated automatically (e.g., `PMRS-ABC123`) when a school is created by an admin.

### Completing a Profile
After registering, students must complete their profile (class, section, DOB, guardian info, etc.) before accessing the full system.

---

## 🧭 Application Routes

### Public Routes
| Method | URL | Description |
|---|---|---|
| GET | `/join/{school_code}` | View school join page |
| POST | `/join/{school_code}` | Register and join a school |

### Authenticated Routes
| Method | URL | Description |
|---|---|---|
| GET | `/dashboard` | Role-based dashboard redirect |
| GET | `/dashboard/admin` | Admin dashboard |
| GET | `/dashboard/teacher` | Teacher dashboard |
| GET | `/dashboard/student` | Student dashboard |
| GET | `/my-progress` | Student's personal progress page |
| GET | `/my-tasks` | Student's assigned remedial tasks |
| GET | `/search` | Global search across the system |
| — | `/students` | Full CRUD for students |
| — | `/marks` | Mark entry and management |
| GET | `/performance` | Performance overview |
| GET | `/performance/student/{id}` | Individual student performance |
| GET | `/performance/slow-learners` | List of slow learner students |
| — | `/remedial` | Remedial actions CRUD |
| GET | `/reports` | Academic reports |

### Admin-Only Routes
| Method | URL | Description |
|---|---|---|
| — | `/subjects` | Subject management |
| — | `/teachers` | Teacher management |

---

## 📁 Project Structure

### Backend
```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # All feature controllers
│   │   ├── Middleware/        # Auth, Role, ProfileCompleted middleware
│   │   └── Requests/          # Form request validation
│   ├── Models/                # Eloquent models
│   │   ├── User.php
│   │   ├── School.php
│   │   ├── Student.php
│   │   ├── Teacher.php
│   │   ├── Subject.php
│   │   ├── Mark.php
│   │   ├── RemedialAction.php
│   │   └── TeacherAssignment.php
│   └── Services/              # Business logic services
├── database/
│   ├── migrations/            # All DB schema definitions
│   └── seeders/               # Data seeders
├── resources/
│   └── views/                 # Blade templates (dashboards, students, marks, etc.)
└── routes/
    ├── web.php                # All web routes
    └── auth.php               # Auth routes (Breeze)
```

### Frontend
```
frontend/
├── src/
│   ├── App.jsx                # Main app component (landing page)
│   ├── main.jsx               # React entry point
│   └── index.css              # Global styles
├── index.html                 # HTML shell
└── vite.config.js             # Vite configuration
```

---

## 🎨 Performance Labels & Color Coding

The system uses a consistent color scheme for performance statuses throughout the UI:

| Status | Criteria | Color |
|---|---|---|
| 🟢 **Good** | Average ≥ 60% | `#00C48C` (Green) |
| 🟡 **At Risk** | Average 40–59% | `#F59E0B` (Amber) |
| 🔴 **Slow Learner** | Average < 40% or failed 2+ subjects | `#FF5252` (Red) |
| ⚪ **Not Evaluated** | No marks entered yet | `#9CA3AF` (Gray) |

---

## 🧪 Running Tests

```bash
cd backend
php artisan test
```

---

## 📌 Environment Variables

### Backend (`backend/.env`)
```env
APP_NAME="Performance Monitoring and Remedial System"
APP_ENV=local
APP_KEY=base64:...
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite

MAIL_MAILER=log
```

### Frontend (`frontend/.env`)
```env
VITE_API_URL=http://127.0.0.1:8000
```

---

## 🤝 Contributing

1. Fork the repository
2. Create a new feature branch: `git checkout -b feature/your-feature-name`
3. Commit your changes: `git commit -m "Add your feature"`
4. Push to the branch: `git push origin feature/your-feature-name`
5. Open a Pull Request

---

## 📄 License

This project is open-sourced software licensed under the [MIT License](https://opensource.org/licenses/MIT).

---

## 👨‍💻 Author

Developed with ❤️ as an academic and real-world educational management tool.  
Built using **Laravel 12**, **React 19**, and a passion for helping students succeed.

---

> _"Every student deserves a second chance. PMRS makes sure they get one."_
