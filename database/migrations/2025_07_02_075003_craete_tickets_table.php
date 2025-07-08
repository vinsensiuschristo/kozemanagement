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
        Schema::create('tickets', function (Blueprint $table) {
            // Gunakan UUID untuk primary key
            $table->uuid('id')->primary();


            $table->unsignedBigInteger('user_id');
            $table->uuid('unit_id');
            $table->uuid('kamar_id');

            // Detail Laporan
            $table->string('judul');
            $table->text('deskripsi');
            $table->enum('kategori', ['Kebocoran', 'Kerusakan', 'Layanan', 'Penghuni', 'Keamanan', 'Lainnya']); 

            // Enum untuk kolom dengan pilihan terbatas
            $table->enum('prioritas', ['Rendah', 'Sedang', 'Tinggi'])->default('Sedang');
            $table->enum('status', ['Baru', 'Diproses', 'Selesai', 'Ditolak'])->default('Baru');

            // Kolom yang bisa NULL
            $table->string('foto')->nullable();
            $table->text('response_admin')->nullable();
            $table->unsignedTinyInteger('rating')->nullable(); // Rating 1-5
            $table->date('tanggal_selesai')->nullable();

            // Tanggal Lapor
            $table->date('tanggal_lapor');

            // Timestamps (created_at dan updated_at)
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('unit_id')->references('id')->on('units')->cascadeOnDelete();
            $table->foreign('kamar_id')->references('id')->on('kamars')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
