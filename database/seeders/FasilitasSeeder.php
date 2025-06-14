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

            // Fasilitas Kamar
            ['nama' => 'Water Heater', 'tipe' => 'kamar', 'created_at' => $timestamp, 'updated_at' => $timestamp],

            // Fasilitas Umum
            ['nama' => 'Kolam renang depan', 'tipe' => 'umum', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Laundry 10Kg', 'tipe' => 'umum', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Laundry 20Kg', 'tipe' => 'umum', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Cuci Baju', 'tipe' => 'umum', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Token Sendiri', 'tipe' => 'umum', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Laundry 13KG', 'tipe' => 'umum', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Laundry 2 Stell Per Hari', 'tipe' => 'umum', 'created_at' => $timestamp, 'updated_at' => $timestamp],

            // Fasilitas Kamar Mandi
            ['nama' => 'Kamar Mandi Dalam', 'tipe' => 'kamar_mandi', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Kamar Mandi Luar', 'tipe' => 'kamar_mandi', 'created_at' => $timestamp, 'updated_at' => $timestamp],

            // Kategori Kamar
            ['nama' => 'Kamar Kecil', 'tipe' => 'kamar', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'Kamar Besar', 'tipe' => 'kamar', 'created_at' => $timestamp, 'updated_at' => $timestamp],

            // Fasilitas WC
            ['nama' => 'WC Kecil', 'tipe' => 'kamar_mandi', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['nama' => 'WC Besar', 'tipe' => 'kamar_mandi', 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ]);
    }
}
