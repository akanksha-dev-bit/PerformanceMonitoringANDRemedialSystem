<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Extend the action_type enum to include quiz_test and practice_session
        DB::statement("ALTER TABLE remedial_actions MODIFY COLUMN action_type ENUM('extra_class','counseling','peer_tutoring','assignment','parent_meeting','other','quiz_test','practice_session') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE remedial_actions MODIFY COLUMN action_type ENUM('extra_class','counseling','peer_tutoring','assignment','parent_meeting','other') NOT NULL");
    }
};
