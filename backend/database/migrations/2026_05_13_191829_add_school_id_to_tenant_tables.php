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
        $tables = [
            'marks',
            'remedial_actions',
            'student_quiz_assignments',
            'quiz_questions',
            'quiz_attempts'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    if (!Schema::hasColumn($table->getTable(), 'school_id')) {
                        $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('cascade');
                    }
                });
            }
        }

        if (Schema::hasTable('subjects')) {
            Schema::table('subjects', function (Blueprint $table) {
                if (!Schema::hasColumn('subjects', 'school_id')) {
                    $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('cascade');
                }
                
                // Drop global unique constraint on 'code' and add composite unique constraint
                $table->dropUnique(['code']); // Drops 'subjects_code_unique'
                
                // Adding composite index requires ensuring there's no existing one that conflicts, 
                // but since it's new, it's fine.
                $table->unique(['school_id', 'code']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'marks',
            'remedial_actions',
            'student_quiz_assignments',
            'quiz_questions',
            'quiz_attempts'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    if (Schema::hasColumn($table->getTable(), 'school_id')) {
                        $table->dropForeign(['school_id']);
                        $table->dropColumn('school_id');
                    }
                });
            }
        }

        if (Schema::hasTable('subjects')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->dropUnique(['school_id', 'code']);
                
                if (Schema::hasColumn('subjects', 'school_id')) {
                    $table->dropForeign(['school_id']);
                    $table->dropColumn('school_id');
                }
                
                $table->unique('code');
            });
        }
    }
};
