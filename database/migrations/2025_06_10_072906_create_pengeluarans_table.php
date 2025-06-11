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
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('unit_id');
            $table->date('tanggal');
            $table->integer('jumlah'); // rupiah
            $table->string('kategori'); // contoh: listrik, air, maintenance
            $table->string('deskripsi')->nullable(); // misal: “Perbaikan AC kamar A1”
            $table->string('bukti')->nullable(); // path file foto bukti pengeluaran
            $table->boolean('is_konfirmasi')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};
