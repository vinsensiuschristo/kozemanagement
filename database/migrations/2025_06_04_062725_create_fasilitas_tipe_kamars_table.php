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
        Schema::create('fasilitas_tipe_kamars', function (Blueprint $table) {
            $table->uuid('tipe_kamar_id');
            $table->foreignId('fasilitas_id');
            $table->timestamps();

            $table->primary(['tipe_kamar_id', 'fasilitas_id']);

            $table->foreign('tipe_kamar_id')->references('id')->on('tipe_kamars')->onDelete('cascade');
            $table->foreign('fasilitas_id')->references('id')->on('fasilitas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasilitas_tipe_kamars');
    }
};
