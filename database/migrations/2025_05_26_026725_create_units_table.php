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
        Schema::create('units', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('id_owner')->constrained('owners')->onDelete('cascade');

            $table->string('nomor_kontrak')->unique();
            $table->date('tanggal_awal_kontrak');
            $table->date('tanggal_akhir_kontrak');

            $table->string('nama_cluster')->nullable()->unique();
            $table->boolean('multi_tipe')->default(false);
            $table->enum('disewakan_untuk', ['putra', 'putri', 'campur']);
            $table->text('deskripsi')->nullable();
            $table->year('tahun_dibangun')->nullable();

            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
