# Student Role — Deep Dive & Complete File Explanation

---

## 🎒 ROLE OVERVIEW
The **Student** is the end-user consumer of the platform. Their experience focuses on engagement, interactivity, and self-improvement. They view their performance metrics, complete interactive workspaces, take quizzes, and earn gamification XP and study streaks.

---

## 📂 STUDENT CONTROLLERS (FILE-BY-FILE)

### `app/Http/Controllers/StudentDashboardController.php`
*The Data Aggregator for the Student.*
- **`index()`:** Computes current rank, total XP, and streak.
- **`tasks()`:** Calculates which remedial tasks are overdue, sorts them by priority, and implements "Smart Recommendations" (suggesting quizzes based on subjects where the student has an average < 60%).

### `app/Http/Controllers/RemedialSubmissionController.php`
*The Interactive Workspace Engine.*
- **`show()`:** Renders the dual-column interactive workspace.
- **`saveDraft()`:** Receives AJAX requests every 30 seconds to update the `content` field without changing the submission status.
- **`uploadFile()`:** Handles `multipart/form-data`. Validates mimes (PDF, DOCX, ZIP, JPG) and moves the file to `storage/app/public/schools/{school_id}/submissions/`.

### `app/Http/Controllers/QuizAttemptController.php`
*The Quiz Execution Engine.*
- **`start()`:** Validates if the student has attempts remaining and creates a `QuizAttempt` record with `completed_at = null`.
- **`submit()`:** Loops over the student's submitted JSON answers, compares them against the `correct_answer` in the database, calculates the percentage, and updates the attempt record.

### `app/Http/Controllers/StudentProfileController.php`
*The Onboarding Flow.*
- When a student joins via an invite link, they only have a `User` account. This controller forces them to enter their Roll Number, Class, and Section before they can access the dashboard.

---

## 🖥️ STUDENT VIEWS (BLADE TEMPLATES)

### `resources/views/remedial/submission.blade.php`
The crown jewel of the student UI. A premium workspace featuring:
- A live JavaScript word counter that updates a progress bar.
- Background autosave indicators that pulse when syncing.
- An interactive drag-and-drop file upload zone.
- Real-time feedback and grading results from the teacher.

### `resources/views/quiz/attempt.blade.php`
A distraction-free, fullscreen quiz interface. Questions are rendered as cards, and JavaScript handles a countdown timer (if duration is set) that auto-submits the form when time expires.

---

## 🧠 DEEP DIVE: STUDENT CODE PATTERNS

### 1. Gamification Engine
In `app/Models/Student.php`:
```php
public function updateStreak(): void {
    $today = now()->startOfDay();
    $lastActivity = $this->last_activity_date ? Carbon::parse($this->last_activity_date)->startOfDay() : null;

    if (!$lastActivity || $lastActivity->lt($today)) {
        if ($lastActivity && $lastActivity->diffInDays($today) === 1) {
            $this->increment('study_streak'); // Consecutive day
        } else if (!$lastActivity || $lastActivity->diffInDays($today) > 1) {
            $this->update(['study_streak' => 1]); // Streak broken, reset
        }
        $this->update(['last_activity_date' => now()]);
    }
}
```
**How it works:** This is called every time a student completes a task or quiz. It checks their last activity date. If it was exactly yesterday, streak + 1. If it was > 1 day ago, the streak resets to 1.

### 2. Background Autosaving (AJAX)
In `submission.blade.php` (Frontend JS):
```javascript
fetch(DRAFT_URL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
    body: JSON.stringify({ content: text })
});
```
In `RemedialSubmissionController@saveDraft` (Backend):
```php
$submission->update(['content' => $request->content]);
return response()->json(['saved_at' => now()->format('H:i')]);
```
**Why?** Prevents data loss if the student's browser crashes or they accidentally close the tab during a 1000-word essay.

### 3. File Security and Storage Isolation
In `RemedialSubmissionController@uploadFile`:
```php
$path = $request->file('file')->storeAs(
    "schools/{$schoolId}/submissions", 
    $fileName, 
    'public'
);
```
**Security:** Files are physically isolated in folders named after the `school_id`. The route to view the file also validates `auth()->user()->school_id === submission->school_id`, meaning a student from School A can never download a file uploaded by a student in School B.

### 4. Auto-Grading Quizzes
In `QuizAttemptController@submit`:
```php
$score = 0;
foreach ($questions as $q) {
    $studentAnswer = $request->answers[$q->id] ?? null;
    if ($studentAnswer === $q->correct_answer) {
        $score += $q->marks;
    }
}
$attempt->update(['score' => $score]);
```
**Logic:** It iterates over the master question list, checks the JSON array of student answers mapped by `question_id`, and tallies the score instantly without manual teacher intervention.
