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
        Schema::create('fasilitas_jenis_kamar', function (Blueprint $table) {
            $table->uuid('jenis_kamar_id');
            $table->uuid('fasilitas_kamar_id');
            $table->timestamps();

            $table->primary(['jenis_kamar_id', 'fasilitas_kamar_id']);

            $table->foreign('jenis_kamar_id')->references('id')->on('jenis_kamars')->onDelete('cascade');
            $table->foreign('fasilitas_kamar_id')->references('id')->on('fasilitas_kamars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasilitas_jenis_kamar');
    }
};
