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
        Schema::create('kamars', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_unit');
            $table->uuid('id_jenis_kamar');
            $table->string('no_kamar', 10);
            $table->integer('harga')->unsigned()->nullable();
            $table->string('no_kwh', 50)->nullable();
            $table->enum('status', ['tersedia', 'terisi', 'booked'])->default('tersedia');
            $table->timestamps();

            $table->foreign('id_unit')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('id_jenis_kamar')->references('id')->on('jenis_kamars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamars');
    }
};
