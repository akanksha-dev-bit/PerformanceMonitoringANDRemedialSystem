<?php

/**
 * ============================================================================
 * 0001_01_01_000000_create_users_table — User Authentication Database Schema
 * ============================================================================
 *
 * PURPOSE:
 *   Defines the core database tables for user authentication and sessions
 *   within the Performance Monitoring and Remedial System (PMRS).
 *
 * TABLES CREATED:
 *
 *   1. users
 *      - Central user authentication table.
 *      - Stores user credentials, role information, school assignments,
 *        profile completion status, and authentication tokens.
 *      - Supports three distinct roles: admin, teacher, and student.
 *
 *   2. password_reset_tokens
 *      - Secure token storage for password reset functionality.
 *      - Stores unique tokens linked to user email addresses.
 *
 *   3. sessions
 *      - Manages user session data for authentication and state management.
 *      - Stores session ID, user information, IP address, user agent,
 *        payload data, and last activity timestamp.
 *
 * RELATED FILES:
 *   - Model:      app/Models/User.php
 *   - Provider:   app/Providers/AppServiceProvider.php (password defaults)
 *   - Config:     config/auth.php (authentication configuration)
 * ============================================================================
 */

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'teacher', 'student'])->default('student');
            $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('cascade');
            $table->boolean('profile_completed')->default(false);
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();

            $table->index(['school_id']);
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
