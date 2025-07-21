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
        Schema::create('penghuni_vouchers', function (Blueprint $table) {
            $table->id();
            $table->uuid('penghuni_id');
            $table->uuid('voucher_id');
            $table->date('periode');
            $table->boolean('is_used')->default(false);
            $table->uuid('digunakan_pada_mitra_id')->nullable();
            $table->timestamps();

            $table->foreign('penghuni_id')->references('id')->on('penghunis')->onDelete('cascade');
            $table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('cascade');
            $table->foreign('digunakan_pada_mitra_id')->references('id')->on('mitras')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penghuni_vouchers');
    }
};
