<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remedial_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('remedial_action_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('school_id');  // for cross-school security checks

            // Written content
            $table->longText('content')->nullable();
            $table->unsignedInteger('word_count')->nullable();

            // File upload
            $table->string('file_path')->nullable();
            $table->string('file_original_name')->nullable();
            $table->string('file_mime_type')->nullable();

            // Timestamps
            $table->timestamp('submitted_at')->nullable();

            // Teacher review
            $table->text('teacher_feedback')->nullable();
            $table->unsignedSmallInteger('teacher_score')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            // Status as plain string — avoids enum lock-in
            // Values: draft | submitted | reviewed | needs_improvement
            $table->string('submission_status', 30)->default('draft');

            $table->timestamps();

            // Unique: one submission per student per remedial action
            $table->unique(['remedial_action_id', 'student_id']);
            $table->index('school_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remedial_submissions');
    }
};
