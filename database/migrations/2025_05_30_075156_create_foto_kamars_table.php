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
        Schema::create('foto_kamars', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('kamar_id')->constrained('kamars')->onDelete('cascade');
            $table->enum('kategori', ['depan', 'dalam', 'kamar_mandi']);
            $table->string('path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_kamars');
    }
};
