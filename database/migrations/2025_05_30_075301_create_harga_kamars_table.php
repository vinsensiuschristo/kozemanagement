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
        Schema::create('harga_kamars', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->uuid('tipe_kamar_id');
            $table->foreign('tipe_kamar_id')->references('id')->on('tipe_kamars')->onDelete('cascade');
            $table->decimal('harga_perbulan', 10, 2);
            $table->decimal('minimal_deposit', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_kamars');
    }
};
