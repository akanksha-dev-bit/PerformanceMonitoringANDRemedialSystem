<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $defaultSchool = \App\Models\School::first();
    if (!$defaultSchool) {
        $defaultSchool = \App\Models\School::create([
            'name' => 'Default Grammar School',
            'school_code' => 'DGS',
            'address' => 'Main Campus'
        ]);
        echo "Created Default School." . PHP_EOL;
    }
    $schoolId = $defaultSchool->id;
    echo "Using School ID: {$schoolId} ({$defaultSchool->name})" . PHP_EOL;

    // Backfill Users
    $updatedUsers = \App\Models\User::withoutGlobalScopes()
        ->whereNull('school_id')
        ->update(['school_id' => $schoolId]);
    echo "Updated Users: {$updatedUsers}" . PHP_EOL;

    // Backfill Students
    $updatedStudents = \App\Models\Student::withoutGlobalScopes()
        ->whereNull('school_id')
        ->update(['school_id' => $schoolId]);
    echo "Updated Students: {$updatedStudents}" . PHP_EOL;

    // Backfill Subjects
    $updatedSubjects = \App\Models\Subject::withoutGlobalScopes()
        ->whereNull('school_id')
        ->update(['school_id' => $schoolId]);
    echo "Updated Subjects: {$updatedSubjects}" . PHP_EOL;

    // Backfill Quizzes
    $updatedQuizzes = \App\Models\Quiz::withoutGlobalScopes()
        ->whereNull('school_id')
        ->update(['school_id' => $schoolId]);
    echo "Updated Quizzes: {$updatedQuizzes}" . PHP_EOL;

    // Backfill QuizQuestions
    $updatedQuestions = \App\Models\QuizQuestion::withoutGlobalScopes()
        ->whereNull('school_id')
        ->update(['school_id' => $schoolId]);
    echo "Updated QuizQuestions: {$updatedQuestions}" . PHP_EOL;

    // Backfill StudentQuizAssignments
    $updatedAssignments = \App\Models\StudentQuizAssignment::withoutGlobalScopes()
        ->whereNull('school_id')
        ->update(['school_id' => $schoolId]);
    echo "Updated StudentQuizAssignments: {$updatedAssignments}" . PHP_EOL;

    // Backfill QuizAttempts
    $updatedAttempts = \App\Models\QuizAttempt::withoutGlobalScopes()
        ->whereNull('school_id')
        ->update(['school_id' => $schoolId]);
    echo "Updated QuizAttempts: {$updatedAttempts}" . PHP_EOL;

    // Backfill Marks
    $updatedMarks = \App\Models\Mark::withoutGlobalScopes()
        ->whereNull('school_id')
        ->update(['school_id' => $schoolId]);
    echo "Updated Marks: {$updatedMarks}" . PHP_EOL;

    // Backfill RemedialActions
    $updatedRemedials = \App\Models\RemedialAction::withoutGlobalScopes()
        ->whereNull('school_id')
        ->update(['school_id' => $schoolId]);
    echo "Updated RemedialActions: {$updatedRemedials}" . PHP_EOL;

    echo "Successfully backfilled all existing tenant records!" . PHP_EOL;

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
