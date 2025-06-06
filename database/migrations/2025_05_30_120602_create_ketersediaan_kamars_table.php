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
            $table->uuid('kamar_id');
            $table->enum('status', ['kosong', 'booked', 'terisi'])->default('kosong');
            $table->timestamps();

            $table->foreign('kamar_id')->references('id')->on('kamars')->onDelete('cascade');
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
