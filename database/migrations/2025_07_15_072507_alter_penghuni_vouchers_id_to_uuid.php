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
        Schema::table('penghuni_vouchers', function (Blueprint $table) {
            // 1. Tambahkan kolom UUID sementara
            $table->uuid('uuid')->nullable()->after('id');
        });

        // 2. Isi kolom UUID dengan nilai unik
        DB::table('penghuni_vouchers')->get()->each(function ($item) {
            DB::table('penghuni_vouchers')
                ->where('id', $item->id)
                ->update(['uuid' => Str::uuid()]);
        });

        Schema::table('penghuni_vouchers', function (Blueprint $table) {
            // 3. Drop primary key lama (pastikan tidak ada AUTO_INCREMENT)
            $table->dropPrimary();
        });

        Schema::table('penghuni_vouchers', function (Blueprint $table) {
            // 4. Drop kolom id lama dan rename uuid jadi id
            $table->dropColumn('id');
            $table->renameColumn('uuid', 'id');

            // 5. Jadikan kolom UUID sebagai primary key
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penghuni_vouchers', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('id');
            $table->bigIncrements('id')->first();
        });
    }
};
