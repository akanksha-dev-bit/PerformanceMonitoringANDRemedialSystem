# Admin Role — Deep Dive & Complete File Explanation

---

## 👑 ROLE OVERVIEW
The **Admin** is the highest role within a specific school tenant. When a school registers on PMRS, the user who registers the school becomes its first Admin. Admins manage the fundamental academic structure: Teachers, Subjects, and global School Performance Analytics.

---

## 📂 ADMIN CONTROLLERS (FILE-BY-FILE)

### `app/Http/Controllers/Auth/RegisteredUserController.php`
*The genesis of an Admin and a School.*
When a new school registers:
1. Validates the school name, admin name, email, and password.
2. Calls `School::generateUniqueCode()` to create a 6-character unique ID (e.g., `PMRS-XY8Z2`).
3. Creates the `School` record in the database.
4. Creates the `User` record with `role = 'admin'` and links it to the new `school_id`.
5. Logs the user in and redirects to the admin dashboard.

### `app/Http/Controllers/AdminDashboardController.php`
*The analytics hub for the entire school.*
- **Dependencies Injected:** `PerformanceService`, `SlowLearnerService`.
- **Data Fetched:** 
  - Total teachers, total students.
  - Number of "At Risk" and "Slow Learner" students across the school.
  - Historical trend data (average marks over the last few academic years).
- **Unique Feature:** Generates the school's invite link (`/join/{school_code}`) which the admin can copy or show as a QR code to invite teachers and students.

### `app/Http/Controllers/TeacherController.php`
*Full CRUD for managing the school's teaching staff.*
- **`store()`:** Creates a `User` (with `role='teacher'`) and a `Teacher` profile record simultaneously. Both are automatically assigned the Admin's `school_id` via the `BelongsToSchool` trait.
- **`destroy()`:** Deletes the teacher's User record. Because of database foreign key cascades, this also deletes their Teacher profile and assignments.

### `app/Http/Controllers/SubjectController.php`
*Full CRUD for managing academic subjects.*
- Manages the `subjects` table.
- Enforces composite uniqueness: a subject code must be unique *within the school*, but the same code can exist in different schools.

---

## 🖥️ ADMIN VIEWS (BLADE TEMPLATES)

### `resources/views/dashboard/admin.blade.php`
The main dashboard UI. Features:
- KPI cards (Total Students, Active Interventions, etc.).
- A Chart.js graph plotting subject averages.
- A "Needs Attention" table showing the top 5 students with the lowest averages.
- A modal displaying the QR code for the school invite link using `qrcode.js`.

### `resources/views/teachers/` and `resources/views/subjects/`
Contains `index.blade.php`, `create.blade.php`, and `edit.blade.php` for standard CRUD operations. Uses the premium Glassmorphism table designs and standard Laravel form validation error displays (`@error`).

---

## 🧠 DEEP DIVE: ADMIN CODE PATTERNS

### 1. Generating Unique School Codes
In `app/Models/School.php`:
```php
public static function generateUniqueCode() {
    do {
        $code = 'PMRS-' . strtoupper(Str::random(6));
    } while (self::where('school_code', $code)->exists());
    return $code;
}
```
**How it works:** This is a `do-while` loop. It generates a random string and queries the database. If it exists, it loops and generates a new one, ensuring 100% uniqueness before returning.

### 2. The Role Middleware Barrier
Admin routes are highly destructive (deleting users, creating subjects). We protect them in `routes/web.php` like this:
```php
Route::middleware(['auth', RoleMiddleware::class.':admin'])->group(function () {
    Route::resource('subjects', SubjectController::class);
    Route::resource('teachers', TeacherController::class);
});
```
If a Teacher or Student tries to access `/teachers/create`, the `RoleMiddleware` intercepts the request, checks `auth()->user()->role === 'admin'`, and immediately throws an `abort(403)` (Forbidden) if it fails.

### 3. Subject Unique Validation Rule
In `SubjectController@update`:
```php
'code' => 'required|string|max:10|unique:subjects,code,' . $subject->id,
```
**Why the concatenation?** When editing a subject, if the admin submits the form without changing the code, Laravel's unique validator would normally fail because the code already exists in the database. Appending `,{$subject->id}` tells Laravel to ignore the current subject's row when checking for uniqueness, allowing the update to proceed.
