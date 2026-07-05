<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Academic Classes table
        Schema::create('academic_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('name'); // e.g., "Class 10"
            $table->timestamps();

            $table->index(['school_id']);
        });

        // 2. Classrooms/Sections table
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('academic_class_id')->constrained('academic_classes')->onDelete('cascade');
            $table->string('name'); // e.g., "A", "B", "10A"
            $table->timestamps();

            $table->index(['school_id']);
            $table->index(['academic_class_id']);
        });

        // 3. Add classroom_id foreign key to students table
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'classroom_id')) {
                $table->foreignId('classroom_id')->nullable()->after('school_id')->constrained('classrooms')->onDelete('set null');
            }
        });

        // 4. Teacher Subject/Classroom Allocations
        Schema::create('teacher_subject_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade'); // Maps to users.id (role=teacher)
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('classroom_id')->constrained('classrooms')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['teacher_id', 'subject_id', 'classroom_id', 'school_id'], 'tsa_unique_index');
            $table->index(['school_id']);
            $table->index(['teacher_id']);
            $table->index(['classroom_id']);
        });

        // 5. Timetable Schedule table
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('classroom_id')->constrained('classrooms')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->string('day_of_week'); // e.g., "Monday", "Tuesday"
            $table->unsignedInteger('period_number'); // e.g., 1, 2, 3, 4...
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();

            $table->index(['school_id']);
            $table->index(['classroom_id', 'day_of_week']);
            $table->index(['teacher_id', 'day_of_week']);
        });

        // 6. Attendance Sessions (taken by teacher for a class, subject, period, date)
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('classroom_id')->constrained('classrooms')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('timetable_id')->nullable()->constrained('timetables')->onDelete('set null');
            $table->date('date');
            $table->unsignedInteger('period_number');
            $table->unsignedInteger('headcount')->nullable(); // physical headcount
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->unique(['classroom_id', 'subject_id', 'date', 'period_number', 'school_id'], 'att_session_unique');
            $table->index(['school_id']);
            $table->index(['classroom_id', 'date']);
        });

        // 7. Attendance Records (student present/absent logs)
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('attendance_session_id')->constrained('attendance_sessions')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->enum('status', ['present', 'absent'])->default('present');
            $table->timestamps();

            $table->unique(['attendance_session_id', 'student_id']);
            $table->index(['school_id']);
            $table->index(['student_id']);
        });

        // 8. Attendance Activity Logs (Sensitive audit trail)
        Schema::create('attendance_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('attendance_session_id')->constrained('attendance_sessions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Actor (teacher or admin)
            $table->string('action'); // "created", "updated_records", "headcount_mismatch_override"
            $table->text('details')->nullable(); // JSON data of modifications
            $table->timestamps();

            $table->index(['school_id']);
            $table->index(['attendance_session_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_activity_logs');
        Schema::dropIfExists('attendance_records');
        Schema::dropIfExists('attendance_sessions');
        Schema::dropIfExists('timetables');
        Schema::dropIfExists('teacher_subject_assignments');
        
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'classroom_id')) {
                $table->dropForeign(['classroom_id']);
                $table->dropColumn('classroom_id');
            }
        });

        Schema::dropIfExists('classrooms');
        Schema::dropIfExists('academic_classes');
    }
};
