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
        Schema::create('ketersediaan_kamars', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tipe_kamar_id')->constrained('tipe_kamars')->onDelete('cascade');
            $table->string('nama');       // nomor/nama kamar
            $table->string('lantai');
            $table->enum('status', ['kosong', 'booked', 'terisi'])->default('kosong');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ketersediaan_kamars');
    }
};
