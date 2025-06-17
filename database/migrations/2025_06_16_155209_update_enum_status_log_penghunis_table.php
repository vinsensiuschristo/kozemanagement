<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE log_penghunis 
            MODIFY COLUMN status ENUM('checkin', 'checkout', 'booking', 'pindah', 'cancel') 
            NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE log_penghunis 
            MODIFY COLUMN status ENUM('checkin', 'checkout') 
            NOT NULL");
    }
};
