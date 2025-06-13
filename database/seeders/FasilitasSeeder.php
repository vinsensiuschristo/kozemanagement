<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FasilitasSeeder extends Seeder
{
    public function run(): void
    {
        $timestamp = now();

        DB::table('fasilitas')->insert([
            ['nama' => 'Ruang Tamu', 'tipe' => 'umum', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Dapur Umum', 'tipe' => 'umum', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Ruang Makan', 'tipe' => 'umum', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Listrik Gratis', 'tipe' => 'umum', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Wifi Umum', 'tipe' => 'umum', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'CCTV', 'tipe' => 'umum', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Penjaga Kos', 'tipe' => 'umum', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Balcon', 'tipe' => 'umum', 'created_at' => $timestamp, 'updated_at' => $timestamp],

            ['nama' => 'AC', 'tipe' => 'kamar', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Kipas Angin', 'tipe' => 'kamar', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Kasur', 'tipe' => 'kamar', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Meja Belajar', 'tipe' => 'kamar', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Lemari', 'tipe' => 'kamar', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'TV', 'tipe' => 'kamar', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Jendela', 'tipe' => 'kamar', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Dispenser', 'tipe' => 'kamar', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Kulkas Mini', 'tipe' => 'kamar', 'created_at' => $timestamp, 'updated_at' => $timestamp],

            ['nama' => 'Shower', 'tipe' => 'kamar_mandi', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Air Panas', 'tipe' => 'kamar_mandi', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Ember & Gayung', 'tipe' => 'kamar_mandi', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Kloset Jongkok', 'tipe' => 'kamar_mandi', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Kloset Duduk', 'tipe' => 'kamar_mandi', 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ]);
    }
}
