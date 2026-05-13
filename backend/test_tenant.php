<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $schoolB = \App\Models\School::firstOrCreate(['school_code' => 'SPH'], ['name' => 'Springfield High', 'address' => '123 Test St', 'contact_number' => '555-0000', 'email' => 'springfield@example.com']);
    
    $userB = \App\Models\User::firstOrCreate(['email' => 'teacherB@springfield.com'], ['name' => 'Springfield Teacher', 'password' => bcrypt('password'), 'role' => 'teacher', 'school_id' => $schoolB->id, 'is_active' => true]);
    
    $studentUserB = \App\Models\User::firstOrCreate(['email' => 'studentB@springfield.com'], ['name' => 'Springfield Student', 'password' => bcrypt('password'), 'role' => 'student', 'school_id' => $schoolB->id, 'is_active' => true]);
    
    $studentB = \App\Models\Student::firstOrCreate(['user_id' => $studentUserB->id], ['school_id' => $schoolB->id, 'roll_no' => 'B001', 'class' => '10', 'section' => 'A', 'is_active' => true]);

    auth()->login($userB);
    \App\Models\Subject::firstOrCreate(['code' => 'MAT101', 'school_id' => $schoolB->id], ['name' => 'Math B', 'class' => '10']);
    echo 'Logged in as User B (' . $userB->school->name . '). Total Subjects visible: ' . \App\Models\Subject::count() . PHP_EOL;

    $schoolA = \App\Models\School::firstOrCreate(['school_code' => 'FS1'], ['name' => 'First School', 'address' => '123 Test St']);
    $userA = \App\Models\User::firstOrCreate(['email' => 'teachera@first.com'], ['name' => 'First Teacher', 'password' => bcrypt('password'), 'role' => 'teacher', 'school_id' => $schoolA->id, 'is_active' => true]);
    auth()->login($userA);
    \App\Models\Subject::firstOrCreate(['code' => 'MAT101', 'school_id' => $schoolA->id], ['name' => 'Math A', 'class' => '10']);
    echo 'Logged in as User A (' . $userA->school->name . '). Total Subjects visible: ' . \App\Models\Subject::count() . PHP_EOL;

    try {
        $studentLookup = \App\Models\Student::findOrFail($studentB->id);
        echo 'FAIL: User A was able to find Student B!' . PHP_EOL;
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        echo 'SUCCESS: User A could not find Student B (ModelNotFoundException thrown).' . PHP_EOL;
    }
} catch (\Exception $e) {
    echo $e->getMessage();
}
