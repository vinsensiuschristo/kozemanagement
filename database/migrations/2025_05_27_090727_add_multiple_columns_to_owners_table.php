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
        Schema::table('owners', function (Blueprint $table) {
            $table->string('bank')->nullable()->after('alamat');
            $table->string('nomor_rekening')->nullable()->after('bank');
            $table->string('foto_ktp')->nullable()->after('nomor_ktp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('owners', function (Blueprint $table) {
            $table->dropColumn([
                'tempat_lahir',
                'tanggal_lahir',
                'agama',
                'nomor_telepon',
                'email',
                'alamat',
                'bank',
                'nomor_rekening',
                'nomor_ktp',
                'foto_ktp'
            ]);
        });
    }
};
