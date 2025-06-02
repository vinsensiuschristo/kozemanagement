<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FasilitasSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now()->toDateTimeString();

        $data = [
            // fasilitas_umum
            ['nama' => 'Ruang Tamu', 'tipe' => 'umum'],
            ['nama' => 'Dapur Umum', 'tipe' => 'umum'],
            ['nama' => 'Ruang Makan', 'tipe' => 'umum'],
            ['nama' => 'Listrik Gratis', 'tipe' => 'umum'],
            ['nama' => 'Wifi Umum', 'tipe' => 'umum'],
            ['nama' => 'CCTV', 'tipe' => 'umum'],
            ['nama' => 'Penjaga Kos', 'tipe' => 'umum'],
            ['nama' => 'Balcon', 'tipe' => 'umum'],

            // fasilitas_kamar
            ['nama' => 'AC', 'tipe' => 'kamar'],
            ['nama' => 'Kipas Angin', 'tipe' => 'kamar'],
            ['nama' => 'Kasur', 'tipe' => 'kamar'],
            ['nama' => 'Meja Belajar', 'tipe' => 'kamar'],
            ['nama' => 'Lemari', 'tipe' => 'kamar'],
            ['nama' => 'TV', 'tipe' => 'kamar'],
            ['nama' => 'Jendela', 'tipe' => 'kamar'],
            ['nama' => 'Dispenser', 'tipe' => 'kamar'],
            ['nama' => 'Kulkas Mini', 'tipe' => 'kamar'],

            // fasilitas_kamar_mandi
            ['nama' => 'Shower', 'tipe' => 'kamar_mandi'],
            ['nama' => 'Air Panas', 'tipe' => 'kamar_mandi'],
            ['nama' => 'Ember & Gayung', 'tipe' => 'kamar_mandi'],
            ['nama' => 'Kloset Jongkok', 'tipe' => 'kamar_mandi'],
            ['nama' => 'Kloset Duduk', 'tipe' => 'kamar_mandi'],
            ['nama' => 'Wastafel', 'tipe' => 'kamar_mandi'],
            ['nama' => 'Cermin', 'tipe' => 'kamar_mandi'],

            // fasilitas_parkir
            ['nama' => 'Parkir Motor', 'tipe' => 'parkir'],
            ['nama' => 'Parkir Mobil', 'tipe' => 'parkir'],
            ['nama' => 'Parkir Sepeda', 'tipe' => 'parkir'],
            ['nama' => 'Parkir Tamu', 'tipe' => 'parkir'],
            ['nama' => 'Area Parkir Beratap', 'tipe' => 'parkir'],
        ];

        foreach ($data as &$item) {
            $item['created_at'] = $now;
            $item['updated_at'] = $now;
        }

        DB::table('fasilitas')->insert($data);
    }
}
