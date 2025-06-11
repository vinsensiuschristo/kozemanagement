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
        Schema::create('pemasukans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('unit_id');
            $table->uuid('penghuni_id')->nullable();
            $table->uuid('kamar_id')->nullable();
            $table->uuid('checkin_id')->nullable();
            $table->date('tanggal');
            $table->integer('jumlah');
            $table->string('deskripsi')->nullable();
            $table->string('bukti')->nullable();
            $table->boolean('is_konfirmasi')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('penghuni_id')->references('id')->on('penghunis')->nullOnDelete();
            $table->foreign('kamar_id')->references('id')->on('kamars')->nullOnDelete();
            $table->foreign('checkin_id')->references('id')->on('log_penghunis')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukans');
    }
};
