<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mitras', function (Blueprint $table) {
            // Tambah kolom yang mungkin belum ada
            if (!Schema::hasColumn('mitras', 'kontak_person')) {
                $table->string('kontak_person')->nullable()->after('nama');
            }

            if (!Schema::hasColumn('mitras', 'nomor_telepon')) {
                $table->string('nomor_telepon')->nullable()->after('kontak_person');
            }

            if (!Schema::hasColumn('mitras', 'email')) {
                $table->string('email')->nullable()->after('nomor_telepon');
            }

            if (!Schema::hasColumn('mitras', 'alamat')) {
                $table->text('alamat')->nullable()->after('email');
            }

            if (!Schema::hasColumn('mitras', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('alamat');
            }

            if (!Schema::hasColumn('mitras', 'status')) {
                $table->enum('status', ['aktif', 'nonaktif', 'pending'])->default('aktif')->after('deskripsi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mitras', function (Blueprint $table) {
            $table->dropColumn([
                'kontak_person',
                'nomor_telepon',
                'email',
                'alamat',
                'deskripsi',
                'status'
            ]);
        });
    }
};
