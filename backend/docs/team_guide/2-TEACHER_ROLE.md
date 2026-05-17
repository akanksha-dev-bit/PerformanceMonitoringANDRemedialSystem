# Teacher Role — Deep Dive & Complete File Explanation

---

## 🎓 ROLE OVERVIEW
The **Teacher** is the operational engine of PMRS. Teachers record academic performance (marks), evaluate students, build quizzes, and assign remedial actions. They only see students they teach, ensuring privacy and focus.

---

## 📂 TEACHER CONTROLLERS (FILE-BY-FILE)

### `app/Http/Controllers/TeacherDashboardController.php`
*The teacher's command center.*
- Analyzes the specific classes the teacher is assigned to.
- Fetches "My Students" by applying a complex dynamic `OR WHERE` query based on their `teacher_assignments`.
- Uses `PerformanceService` to find which of their students are "At Risk" (average < 60%).

### `app/Http/Controllers/MarkController.php`
*Handles academic records.*
- **`store()` / `update()`:** Validates that `marks_obtained <= max_marks`.
- Creates records in the `marks` table which trigger real-time updates to a student's `average_percentage` and `is_slow_learner` computed properties.

### `app/Http/Controllers/RemedialController.php`
*Assigning tasks to students.*
- Allows teachers to assign `extra_class`, `counseling`, `essay`, `file_upload`, etc.
- **`showSubmissions()`:** Loads all student submissions for a specific task so the teacher can review and grade them.

### `app/Http/Controllers/QuizController.php` & `QuizAssignmentController.php`
*The Quiz Engine.*
- `QuizController`: Handles creating the quiz structure and questions.
- `QuizAssignmentController`: Handles assigning a pre-made quiz to a specific student, defining the `start_date`, `end_date`, and `max_attempts`.

---

## 🖥️ TEACHER VIEWS (BLADE TEMPLATES)

### `resources/views/dashboard/teacher.blade.php`
Displays class overviews, quick-action buttons (Add Marks, Assign Task), and a list of struggling students in their specific classes.

### `resources/views/remedial/teacher-review.blade.php`
The UI where teachers grade student work. Features:
- A split view showing the student's essay or file download link.
- A grading form to enter a `teacher_score` and `teacher_feedback`.
- Options to "Mark as Reviewed" or "Request Resubmission".

### `resources/views/quizzes/create.blade.php`
A dynamic form utilizing JavaScript to allow teachers to add multiple questions, options, and correct answers before submitting as a single payload.

---

## 🧠 DEEP DIVE: TEACHER CODE PATTERNS

### 1. Dynamic "My Students" Query
In `TeacherDashboardController.php`, a teacher might teach Class 10 Section A, and Class 11 Section B. How do we fetch ONLY those students?
```php
$myStudents = Student::where(function ($query) use ($assignments) {
    foreach ($assignments as $a) {
        $query->orWhere(function ($sub) use ($a) {
            $sub->where('class', $a->class)
                ->where('section', $a->section);
        });
    }
})->get();
```
**How it works:** It loops over the teacher's assignments and builds a nested query like: `WHERE (class='10' AND section='A') OR (class='11' AND section='B')`.

### 2. Database Transactions for Quizzes
In `QuizController@store`:
```php
DB::transaction(function () use ($validated, $request) {
    $quiz = Quiz::create([...]);
    foreach ($validated['questions'] as $q) {
        $quiz->questions()->create($q);
    }
});
```
**Why?** If creating the quiz succeeds, but saving question #3 fails due to a database error, the transaction automatically rolls back everything. This prevents "orphaned" quizzes with missing questions from cluttering the database.

### 3. Grading and Requesting Resubmission
In `RemedialSubmissionController@grade` (which the teacher calls):
```php
if ($request->action === 'reopen') {
    $submission->update([
        'submission_status' => 'needs_improvement',
        'teacher_feedback'  => $request->teacher_feedback,
    ]);
} else {
    $submission->update([
        'submission_status' => 'reviewed',
        'teacher_score'     => $request->teacher_score,
        'teacher_feedback'  => $request->teacher_feedback,
    ]);
    $submission->student->addXP(20); // Reward the student!
}
```
**Logic:** If the work is poor, resetting to `needs_improvement` makes the workspace editable again for the student. If approved, it locks the workspace, records the grade, and awards gamification XP to encourage the student.
