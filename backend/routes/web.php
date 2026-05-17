<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Academic\MarkController;
use App\Http\Controllers\Academic\PerformanceController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Remedial\RemedialController;
use App\Http\Controllers\Remedial\RemedialSubmissionController;
use App\Http\Controllers\Academic\ReportController;
use App\Http\Controllers\User\StudentController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\JoinController;
use App\Http\Controllers\User\StudentProfileController;
use App\Http\Controllers\User\TeacherController;

// Redirect root to dashboard (or login if unauthenticated)
Route::get('/', fn() => redirect()->route('dashboard'));

// Auth routes (provided by Breeze)
require __DIR__ . '/auth.php';

// Public Invite Routes
Route::middleware('guest')->group(function () {
    Route::get('/join/{school_code}', [JoinController::class, 'create'])->name('join.create');
    Route::post('/join/{school_code}', [JoinController::class, 'store'])->name('join.store')->middleware('throttle:10,1');
});

// Protected routes — require authentication and email verification
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Complete Profile (for Students)
    Route::get('/complete-profile', [StudentProfileController::class, 'create'])->name('complete-profile');
    Route::post('/complete-profile', [StudentProfileController::class, 'store'])->name('complete-profile.store');

    // Dashboard Router & Specific Endpoints
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/admin', [\App\Http\Controllers\Dashboard\AdminDashboardController::class, 'index'])->name('dashboard.admin');
    Route::get('/dashboard/teacher', [\App\Http\Controllers\Dashboard\TeacherDashboardController::class, 'index'])->name('dashboard.teacher');
    Route::get('/dashboard/student', [\App\Http\Controllers\Dashboard\StudentDashboardController::class, 'index'])->name('dashboard.student');
    Route::get('/my-progress', [\App\Http\Controllers\Dashboard\StudentDashboardController::class, 'progress'])->name('student.progress');
    Route::get('/my-tasks', [\App\Http\Controllers\Dashboard\StudentDashboardController::class, 'tasks'])->name('student.tasks');

    Route::middleware(\App\Http\Middleware\EnsureProfileCompleted::class)->group(function () {

        // Profile (Breeze default)
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Students
        Route::resource('students', StudentController::class);

        // Marks
        Route::resource('marks', MarkController::class)->only(['index', 'create', 'store', 'destroy']);

        // Performance
        Route::prefix('performance')->name('performance.')->group(function () {
            Route::get('/',                     [PerformanceController::class, 'index'])       ->name('index');
            Route::get('/student/{student}',   [PerformanceController::class, 'show'])        ->name('show');
            Route::get('/slow-learners',       [PerformanceController::class, 'slowLearners'])->name('slow-learners');
        });

        // Remedial actions
        Route::resource('remedial', RemedialController::class);
        Route::get('/remedial/{remedial}/submissions', [RemedialController::class, 'showSubmissions'])->name('remedial.submissions');

        // Remedial Submissions — Teacher review routes
        Route::get('/remedial-submissions/{submission}/review', [RemedialSubmissionController::class, 'teacherShow'])->name('remedial.review');
        Route::post('/remedial-submissions/{submission}/grade', [RemedialSubmissionController::class, 'grade'])->name('remedial.grade');
        Route::post('/remedial-submissions/{submission}/reopen', [RemedialSubmissionController::class, 'reopen'])->name('remedial.reopen');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

        // Admin Only Routes
        Route::middleware([\App\Http\Middleware\RoleMiddleware::class.':admin'])->group(function () {
            Route::resource('subjects', \App\Http\Controllers\Academic\SubjectController::class)->except(['show']);
            Route::resource('teachers', TeacherController::class);
        });

        // Quiz Management (Teachers + Admin)
        Route::resource('quizzes', \App\Http\Controllers\Quiz\QuizController::class);
        Route::get('quizzes/{quiz}/assign', [\App\Http\Controllers\Quiz\QuizAssignmentController::class, 'create'])->name('quizzes.assign');
        Route::post('quizzes/{quiz}/assign', [\App\Http\Controllers\Quiz\QuizAssignmentController::class, 'store'])->name('quizzes.assign.store');
        Route::get('quiz-assignments/{assignment}/analytics', [\App\Http\Controllers\Quiz\QuizAssignmentController::class, 'analytics'])->name('quizzes.analytics');
    });

    // Student Quiz Attempt Routes
    Route::get('quiz/{assignment}/start', [\App\Http\Controllers\Quiz\QuizAttemptController::class, 'start'])->name('quiz.start');
    Route::get('quiz/attempt/{attempt}', [\App\Http\Controllers\Quiz\QuizAttemptController::class, 'show'])->name('quiz.attempt');
    Route::post('quiz/attempt/{attempt}/submit', [\App\Http\Controllers\Quiz\QuizAttemptController::class, 'submit'])->name('quiz.submit');
    Route::get('quiz/attempt/{attempt}/results', [\App\Http\Controllers\Quiz\QuizAttemptController::class, 'results'])->name('quiz.results');

    // Student Remedial Submission Routes (outside EnsureProfileCompleted)
    Route::get('/remedial/{remedial}/workspace', [RemedialSubmissionController::class, 'show'])->name('remedial.submit.show');
    Route::post('/remedial/{remedial}/draft', [RemedialSubmissionController::class, 'saveDraft'])->name('remedial.submit.draft');
    Route::post('/remedial/{remedial}/submit', [RemedialSubmissionController::class, 'submit'])->name('remedial.submit.store');
    Route::post('/remedial/{remedial}/upload', [RemedialSubmissionController::class, 'uploadFile'])->name('remedial.submit.upload');
});
