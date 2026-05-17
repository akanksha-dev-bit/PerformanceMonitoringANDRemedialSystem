# Shared Components & Database Architecture Guide

This guide explains the shared architecture that **all three team members** must understand. It covers how the database isolates data between different schools, how users log in, and how the frontend is structured.

## 🏗️ 1. Multi-Tenancy (The `school_id` Concept)
This application is designed to support multiple schools on one database. This means **EVERY** major table (`users`, `students`, `subjects`, `remedial_actions`, `quizzes`) has a `school_id` column.

### The Golden Rule:
**Never write a query without scoping it to the current user's school.**
If you forget this, a teacher in "School A" will see the students from "School B".

### How we enforce this:
We use a trait called `BelongsToSchool`.
```php
namespace App\Traits;

trait BelongsToSchool
{
    protected static function bootBelongsToSchool()
    {
        // Automatically adds WHERE school_id = X to all queries!
        static::addGlobalScope('school', function ($builder) {
            if (auth()->check()) {
                $builder->where('school_id', auth()->user()->school_id);
            }
        });
    }
}
```
If you make a new Model, always add `use BelongsToSchool;` to it.

---

## 👥 2. User Architecture
We use a single `users` table for logging in.
```php
// Table: users
id | name | email | password | role | school_id
```
The `role` column is a string: `'admin'`, `'teacher'`, or `'student'`.

### The Student Profile
For students, the `User` record isn't enough. They need a class, roll number, XP, etc. So we have a `students` table that connects back to the `user`.
```php
// Table: students
id | user_id | school_id | roll_no | class | section | xp_points
```
To get a student's profile: `$student = Student::where('user_id', auth()->id())->first();`

---

## 🎨 3. Shared Frontend Components
We use Laravel Blade components for the UI to avoid rewriting the same HTML.

### Layout File (`resources/views/layouts/app.blade.php`)
This is the master HTML file. It contains the `<head>`, CSS imports, the sidebar navigation, and the top navbar. 
If you want to add a link to the sidebar, you edit this file or the `navigation.blade.php` file it includes.

### Using the Layout
Every view you build must be wrapped in this layout component:
```html
<x-app-layout>
    <x-slot name="title">Page Title Goes Here</x-slot>

    <!-- Your HTML goes here -->
    <div class="my-content">
        <h1>Hello World</h1>
    </div>

    <!-- Inject JS at the bottom -->
    @push('scripts')
        <script>console.log("Loaded");</script>
    @endpush
</x-app-layout>
```

### Styling Guidelines
We do NOT use a heavy CSS framework like Tailwind. We use custom Vanilla CSS with modern "Glassmorphism" aesthetics.
If you need to make a card, use the global CSS classes we've established:
- `.form-card` or `.premium-card` for white boxes with rounded corners and soft shadows.
- `.btn-submit` or `.btn-primary` for beautiful gradient buttons.

---

## 💾 4. File Storage
When students upload assignment files, we MUST keep them separated by school.
Files are stored locally in the `storage/app/public` folder.
**Path Structure:** `storage/app/public/schools/{school_id}/submissions/`
To make these files visible to the browser, you must run:
`php artisan storage:link` (this creates a shortcut in the `public/` folder).
