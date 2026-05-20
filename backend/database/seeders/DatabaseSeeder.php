<?php

namespace Database\Seeders;

use App\Models\Mark;
use App\Models\RemedialAction;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use App\Models\School;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\StudentQuizAssignment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign keys to cleanly truncate existing tables
        Schema::disableForeignKeyConstraints();
        Mark::truncate();
        RemedialAction::truncate();
        StudentQuizAssignment::truncate();
        \App\Models\QuizAttempt::truncate();
        QuizQuestion::truncate();
        Quiz::truncate();
        Student::truncate();
        TeacherAssignment::truncate();
        Teacher::truncate();
        Subject::truncate();
        User::truncate();
        School::truncate();
        Schema::enableForeignKeyConstraints();

        // 1. Create School (Tenant)
        $school = School::create([
            'name' => 'Oakwood Academy',
            'school_code' => 'OAK123',
        ]);

        // 2. Create School Admin User
        $admin = User::create([
            'name' => 'Principal Smith',
            'email' => 'admin@oakwood.edu',
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
            'school_id' => $school->id,
            'profile_completed' => true,
            'email_verified_at' => now(),
        ]);

        // 3. Create Teacher User
        $teacherUser = User::create([
            'name' => 'Clara Watson',
            'email' => 'teacher@oakwood.edu',
            'password' => Hash::make('Password123!'),
            'role' => 'teacher',
            'school_id' => $school->id,
            'profile_completed' => true,
            'email_verified_at' => now(),
        ]);

        // 4. Create Subjects
        $math = Subject::create([
            'name' => 'Mathematics',
            'code' => 'MATH10',
            'class' => '10',
            'max_marks' => 100,
            'school_id' => $school->id,
        ]);

        $science = Subject::create([
            'name' => 'Science',
            'code' => 'SCI10',
            'class' => '10',
            'max_marks' => 100,
            'school_id' => $school->id,
        ]);

        $english = Subject::create([
            'name' => 'English',
            'code' => 'ENG10',
            'class' => '10',
            'max_marks' => 100,
            'school_id' => $school->id,
        ]);

        $social = Subject::create([
            'name' => 'Social Studies',
            'code' => 'SOC10',
            'class' => '10',
            'max_marks' => 100,
            'school_id' => $school->id,
        ]);

        // 5. Create Teacher Profile mapping Clara to Mathematics
        Teacher::create([
            'user_id' => $teacherUser->id,
            'school_id' => $school->id,
            'subject_id' => $math->id,
        ]);

        // 6. Create Teacher Assignments for Clara
        TeacherAssignment::create([
            'teacher_id' => $teacherUser->id,
            'school_id' => $school->id,
            'class' => '10',
            'section' => 'A',
        ]);

        TeacherAssignment::create([
            'teacher_id' => $teacherUser->id,
            'school_id' => $school->id,
            'class' => '10',
            'section' => 'B',
        ]);

        // 7. Students (and User records) — mix of high achievers, average, and slow learners
        $studentsData = [
            [
                'name' => 'Aditya Sharma',
                'email' => 'aditya@oakwood.edu',
                'roll_no' => '2024-001',
                'class' => '10',
                'section' => 'A',
                'marks' => [
                    ['subject_id' => $math->id, 'score' => 88],
                    ['subject_id' => $science->id, 'score' => 92],
                    ['subject_id' => $english->id, 'score' => 85],
                    ['subject_id' => $social->id, 'score' => 90],
                ]
            ],
            [
                'name' => 'Meena Kumari',
                'email' => 'meena@oakwood.edu',
                'roll_no' => '2024-002',
                'class' => '10',
                'section' => 'A',
                'marks' => [
                    ['subject_id' => $math->id, 'score' => 32],
                    ['subject_id' => $science->id, 'score' => 28],
                    ['subject_id' => $english->id, 'score' => 35],
                    ['subject_id' => $social->id, 'score' => 30],
                ]
            ],
            [
                'name' => 'Rahul Joshi',
                'email' => 'rahul@oakwood.edu',
                'roll_no' => '2024-003',
                'class' => '10',
                'section' => 'B',
                'marks' => [
                    ['subject_id' => $math->id, 'score' => 65],
                    ['subject_id' => $science->id, 'score' => 70],
                    ['subject_id' => $english->id, 'score' => 68],
                    ['subject_id' => $social->id, 'score' => 72],
                ]
            ],
            [
                'name' => 'Priya Lata',
                'email' => 'priya@oakwood.edu',
                'roll_no' => '2024-004',
                'class' => '10',
                'section' => 'B',
                'marks' => [
                    ['subject_id' => $math->id, 'score' => 25],
                    ['subject_id' => $science->id, 'score' => 30],
                    ['subject_id' => $english->id, 'score' => 22],
                    ['subject_id' => $social->id, 'score' => 28],
                ]
            ],
            [
                'name' => 'Sonia Gupta',
                'email' => 'sonia@oakwood.edu',
                'roll_no' => '2024-005',
                'class' => '10',
                'section' => 'A',
                'marks' => [
                    ['subject_id' => $math->id, 'score' => 45],
                    ['subject_id' => $science->id, 'score' => 48],
                    ['subject_id' => $english->id, 'score' => 52],
                    ['subject_id' => $social->id, 'score' => 50],
                ]
            ],
            [
                'name' => 'Kavya Nair',
                'email' => 'kavya@oakwood.edu',
                'roll_no' => '2024-006',
                'class' => '10',
                'section' => 'B',
                'marks' => [
                    ['subject_id' => $math->id, 'score' => 95],
                    ['subject_id' => $science->id, 'score' => 96],
                    ['subject_id' => $english->id, 'score' => 92],
                    ['subject_id' => $social->id, 'score' => 94],
                ]
            ]
        ];

        foreach ($studentsData as $sd) {
            $user = User::create([
                'name' => $sd['name'],
                'email' => $sd['email'],
                'password' => Hash::make('Password123!'),
                'role' => 'student',
                'school_id' => $school->id,
                'profile_completed' => true,
                'email_verified_at' => now(),
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'school_id' => $school->id,
                'roll_no' => $sd['roll_no'],
                'class' => $sd['class'],
                'section' => $sd['section'],
                'gender' => rand(0, 1) ? 'male' : 'female',
                'is_active' => true,
            ]);

            foreach ($sd['marks'] as $m) {
                Mark::create([
                    'student_id' => $student->id,
                    'subject_id' => $m['subject_id'],
                    'marks_obtained' => $m['score'],
                    'max_marks' => 100,
                    'exam_type' => 'final',
                    'academic_year' => '2024-25',
                    'school_id' => $school->id,
                ]);
            }
        }

        // 8. Create Remedial Actions for slow learners
        $slowStudents = Student::with('marks')->get()->filter(fn($s) => $s->is_slow_learner);

        foreach ($slowStudents as $student) {
            RemedialAction::create([
                'student_id' => $student->id,
                'school_id' => $school->id,
                'action_type' => 'extra_class',
                'title' => 'Weekly Algebra Remedial Class — ' . $student->name,
                'description' => 'Targeted revision sessions to reinforce algebraic rules and expressions.',
                'status' => 'in_progress',
                'scheduled_date' => Carbon::now()->addDays(2),
                'assigned_by' => $teacherUser->id,
            ]);

            RemedialAction::create([
                'student_id' => $student->id,
                'school_id' => $school->id,
                'action_type' => 'assignment',
                'title' => 'Practice Homework Sheet 1',
                'description' => 'Solve the fundamentals work page covering primary math concepts.',
                'status' => 'pending',
                'due_date' => Carbon::now()->addDays(5),
                'assigned_by' => $teacherUser->id,
            ]);
        }

        // 9. Create a Quiz
        $quiz = Quiz::create([
            'title' => 'Quadratic Equations Mini Quiz',
            'description' => 'A basic diagnostic quiz covering linear and quadratic equations.',
            'subject_id' => $math->id,
            'created_by' => $teacherUser->id,
            'difficulty_level' => 'easy',
            'duration_minutes' => 15,
            'is_adaptive' => false,
            'school_id' => $school->id,
        ]);

        // 10. Create Quiz Questions
        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Solve for x: 2x - 5 = 11',
            'option_a' => 'x = 3',
            'option_b' => 'x = 8',
            'option_c' => 'x = 6',
            'option_d' => 'x = 4',
            'correct_answer' => 'B',
            'explanation' => 'Add 5 to both sides to get 2x = 16, then divide by 2 to get x = 8.',
            'marks' => 5,
            'school_id' => $school->id,
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Which of the following is a quadratic equation?',
            'option_a' => 'y = 2x + 1',
            'option_b' => 'y = x^3 - 4',
            'option_c' => 'y = x^2 + 5x + 6',
            'option_d' => 'y = 3/x',
            'correct_answer' => 'C',
            'explanation' => 'A quadratic equation always has 2 as its highest degree (x^2).',
            'marks' => 5,
            'school_id' => $school->id,
        ]);

        // 11. Assign Quiz to Slow Learners
        foreach ($slowStudents as $student) {
            StudentQuizAssignment::create([
                'quiz_id' => $quiz->id,
                'student_id' => $student->id,
                'assigned_by' => $teacherUser->id,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(7),
                'max_attempts' => 3,
                'status' => 'active',
                'school_id' => $school->id,
            ]);
        }
    }
}
