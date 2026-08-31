<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * M3 — Add capacity column to classrooms.
 *
 * Optional: allows headcount validation against section capacity
 * and enables future "room is full" guard on student enrollment.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->unsignedSmallInteger('capacity')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropColumn('capacity');
        });
    }
};
