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
        Schema::create('log_penghunis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('penghuni_id');
            $table->uuid('kamar_id');
            $table->date('tanggal');
            $table->enum('status', ['checkin', 'checkout']);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            // admin atau super admin yang melakukan perubahan
            $table->timestamps();

            $table->foreign('penghuni_id')->references('id')->on('penghunis')->onDelete('cascade');
            $table->foreign('kamar_id')->references('id')->on('kamars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_penghunis');
    }
};
