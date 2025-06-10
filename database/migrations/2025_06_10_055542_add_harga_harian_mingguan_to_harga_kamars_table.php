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
        Schema::table('harga_kamars', function (Blueprint $table) {
            Schema::table('harga_kamars', function (Blueprint $table) {
                $table->decimal('harga_perhari', 10, 2)->nullable()->after('harga_perbulan');
                $table->decimal('harga_perminggu', 10, 2)->nullable()->after('harga_perhari');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('harga_kamars', function (Blueprint $table) {
            Schema::table('harga_kamars', function (Blueprint $table) {
                $table->dropColumn(['harga_perhari', 'harga_perminggu']);
            });
        });
    }
};
