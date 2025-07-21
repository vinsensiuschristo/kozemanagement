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
        Schema::create('unit_voucher_rules', function (Blueprint $table) {
            $table->id();
            $table->uuid('unit_id');
            $table->uuid('voucher_id');
            $table->integer('kuota_per_bulan')->default(1);
            $table->timestamps();

            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade'); 
            $table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_voucher_rules');
    }
};
