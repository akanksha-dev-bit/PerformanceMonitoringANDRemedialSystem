<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Extend action_type to include written_assignment, essay, file_upload
        DB::statement("ALTER TABLE remedial_actions MODIFY COLUMN action_type ENUM(
            'extra_class','counseling','peer_tutoring','assignment','parent_meeting',
            'other','quiz_test','practice_session','written_assignment','essay','file_upload'
        ) NOT NULL");

        Schema::table('remedial_actions', function (Blueprint $table) {
            $table->date('due_date')->nullable()->after('scheduled_date');
            $table->unsignedSmallInteger('max_score')->nullable()->after('due_date');
            $table->unsignedSmallInteger('min_words')->nullable()->after('max_score');
            $table->unsignedSmallInteger('max_words')->nullable()->after('min_words');
        });
    }

    public function down(): void
    {
        Schema::table('remedial_actions', function (Blueprint $table) {
            $table->dropColumn(['due_date', 'max_score', 'min_words', 'max_words']);
        });

        DB::statement("ALTER TABLE remedial_actions MODIFY COLUMN action_type ENUM(
            'extra_class','counseling','peer_tutoring','assignment','parent_meeting',
            'other','quiz_test','practice_session'
        ) NOT NULL");
    }
};
