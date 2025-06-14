<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kamar;
use App\Models\FasilitasTipeKamar;
use App\Models\TipeKamar;
use App\Models\HargaKamar;
use App\Models\Fasilitas;
use Illuminate\Support\Str;
use App\Models\Unit;

class KamarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kamar::factory()->count(20)->create();
        $dataKamar = [
            // Alesha Blue
            [
                'alamat' => 'Alesha Blue 1-1',
                'tipe_kamar' => 'Standard',
                'harga' => 1800000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Jendela', 'Kolam renang depan', 'Laundry 10Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Blue 1-2',
                'tipe_kamar' => 'WC Kecil',
                'harga' => 1800000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'TV', 'Water Heater', 'Jendela', 'Kolam renang depan', 'Laundry 10Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Blue 1-2',
                'tipe_kamar' => 'WC Besar',
                'harga' => 1900000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'TV', 'Water Heater', 'Jendela', 'Kolam renang depan', 'Laundry 10Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Blue 1-3',
                'tipe_kamar' => 'Standard',
                'harga' => 1800000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Water Heater', 'Jendela', 'Kolam renang depan', 'Laundry 10Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Blue 1-9',
                'tipe_kamar' => 'Standard',
                'harga' => 1700000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Water Heater', 'Jendela', 'Kolam renang depan', 'Laundry 10Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Blue 2-7',
                'tipe_kamar' => 'Standard',
                'harga' => 2000000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Water Heater', 'Jendela', 'Kolam renang depan', 'Listrik', 'Laundry 10Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Blue 5-7',
                'tipe_kamar' => 'Standard',
                'harga' => 1800000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Water Heater', 'Jendela', 'Kolam renang depan', 'Laundry 10Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Blue 6-2',
                'tipe_kamar' => 'Standard',
                'harga' => 1750000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Water Heater', 'Jendela', 'Kolam renang depan', 'Laundry 10Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Blue 6-3',
                'tipe_kamar' => 'Standard',
                'harga' => 1600000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'TV', 'Jendela', 'Kolam renang depan', 'Laundry 10Kg'],
                'disewakan_untuk' => 'campur'
            ],

            // Alesha Orange
            [
                'alamat' => 'Alesha Orange 5-3',
                'tipe_kamar' => 'Standard',
                'harga' => 1500000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'TV', 'Water Heater', 'Jendela'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Orange 6-6',
                'tipe_kamar' => 'Standard',
                'harga' => 1500000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Jendela'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Orange 9-12',
                'tipe_kamar' => 'Standard',
                'harga' => 1500000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Jendela'],
                'disewakan_untuk' => 'campur'
            ],

            // Alesha Red
            [
                'alamat' => 'Alesha Red 2-12',
                'tipe_kamar' => 'Standard',
                'harga' => 1500000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Jendela'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Red 2-2',
                'tipe_kamar' => 'Standard',
                'harga' => 1650000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'TV', 'Jendela'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Red 3-6',
                'tipe_kamar' => 'Standard',
                'harga' => 1500000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Jendela'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Red 3-8',
                'tipe_kamar' => 'Standard',
                'harga' => 1500000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Jendela'],
                'disewakan_untuk' => 'campur'
            ],

            // Alesha Purple
            [
                'alamat' => 'Alesha Purple 1-10',
                'tipe_kamar' => 'Lantai 1',
                'harga' => 1800000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Water Heater', 'Jendela'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Purple 1-10',
                'tipe_kamar' => 'Lantai 2',
                'harga' => 1700000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Water Heater', 'Jendela'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Purple 1-10',
                'tipe_kamar' => 'Lantai 3',
                'harga' => 1600000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Water Heater', 'Jendela'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Purple 1-11',
                'tipe_kamar' => 'Standard',
                'harga' => 1700000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Water Heater', 'Jendela'],
                'disewakan_untuk' => 'campur'
            ],

            // Alesha Yellow
            [
                'alamat' => 'Alesha Yellow 2-5',
                'tipe_kamar' => 'Standard',
                'harga' => 1700000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Water Heater', 'Jendela', 'Kolam renang depan', 'Laundry 10Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Yellow 6-2',
                'tipe_kamar' => 'Standard',
                'harga' => 1900000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'TV', 'Jendela', 'Kolam renang depan', 'Listrik', 'Laundry 10Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Yellow 6-3',
                'tipe_kamar' => 'Standard',
                'harga' => 1900000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'TV', 'Jendela', 'Kolam renang depan', 'Listrik', 'Laundry 10Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Yellow 6-5',
                'tipe_kamar' => 'Standard',
                'harga' => 1600000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'TV', 'Jendela', 'Kolam renang depan', 'Laundry 10Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Yellow 6-7',
                'tipe_kamar' => 'Kamar Kecil',
                'harga' => 1710000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'TV', 'Water Heater', 'Jendela', 'Kolam renang depan'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Yellow 6-7',
                'tipe_kamar' => 'Kamar Besar',
                'harga' => 1890000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'TV', 'Water Heater', 'Jendela', 'Kolam renang depan'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Alesha Yellow 7-7',
                'tipe_kamar' => 'Standard',
                'harga' => 2100000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'TV', 'Water Heater', 'Jendela', 'Kolam renang depan'],
                'disewakan_untuk' => 'campur'
            ],

            // Anarta
            [
                'alamat' => 'Anarta H10-6',
                'tipe_kamar' => 'Standard',
                'harga' => 1500000,
                'fasilitas' => ['AC', 'Kasur', 'Meja Belajar', 'Kamar Mandi Luar', 'TV'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Anarta H10-7',
                'tipe_kamar' => 'Standard',
                'harga' => 1500000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Jendela'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Anarta H11-3',
                'tipe_kamar' => 'Standard',
                'harga' => 1600000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Jendela'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Anarta H12-16',
                'tipe_kamar' => 'Standard',
                'harga' => 1500000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Jendela'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Anarta H15-20',
                'tipe_kamar' => 'Standard',
                'harga' => 1550000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Jendela'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Anarta H16-12',
                'tipe_kamar' => 'Standard',
                'harga' => 1500000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Anarta H16-6',
                'tipe_kamar' => 'Standard',
                'harga' => 1600000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Anarta H17-1',
                'tipe_kamar' => 'Standard',
                'harga' => 1400000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Anarta H17-7',
                'tipe_kamar' => 'Standard',
                'harga' => 1500000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Water Heater'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Anarta H17-19',
                'tipe_kamar' => 'Standard',
                'harga' => 1700000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Water Heater', 'Listrik'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Anarta H19-5',
                'tipe_kamar' => 'Standard',
                'harga' => 1500000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'TV'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Anarta H2-9',
                'tipe_kamar' => 'Standard',
                'harga' => 1600000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'TV', 'Water Heater'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Anarta H21-12',
                'tipe_kamar' => 'Standard',
                'harga' => 1500000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'TV'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Anarta H3-16',
                'tipe_kamar' => 'Standard',
                'harga' => 1700000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Listrik'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Anarta H5-9A',
                'tipe_kamar' => 'Standard',
                'harga' => 1600000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Anarta H7-2',
                'tipe_kamar' => 'Standard',
                'harga' => 1600000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Anarta H7-7',
                'tipe_kamar' => 'Standard',
                'harga' => 1400000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Anarta H7-9',
                'tipe_kamar' => 'Standard',
                'harga' => 1600000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Anarta H8-10',
                'tipe_kamar' => 'Standard',
                'harga' => 1600000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Water Heater', 'Jendela'],
                'disewakan_untuk' => 'campur'
            ],
            // Piazza
            [
                'alamat' => 'Piazza F10-2',
                'tipe_kamar' => 'Balkon Depan',
                'harga' => 2600000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Water Heater', 'Jendela', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Piazza F10-2',
                'tipe_kamar' => 'Balkon Belakang',
                'harga' => 2400000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Water Heater', 'Jendela', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Piazza F10-2',
                'tipe_kamar' => 'Tanpa Balkon',
                'harga' => 2200000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Water Heater', 'Jendela', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],

            // Piazza F5-5
            [
                'alamat' => 'Piazza F5-5',
                'tipe_kamar' => 'Balkon Depan',
                'harga' => 2500000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Piazza F5-5',
                'tipe_kamar' => 'Balkon Belakang',
                'harga' => 2250000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Water Heater', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Piazza F5-5',
                'tipe_kamar' => 'Tanpa Balkon',
                'harga' => 2000000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],

            // Piazza F3-5
            [
                'alamat' => 'Piazza F3-5',
                'tipe_kamar' => 'Balkon Depan',
                'harga' => 2500000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'TV', 'Water Heater', 'Jendela', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Piazza F3-5',
                'tipe_kamar' => 'Balkon Belakang',
                'harga' => 2250000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Piazza F3-5',
                'tipe_kamar' => 'Tanpa Balkon',
                'harga' => 2000000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],

            // Piazza F11-1
            [
                'alamat' => 'Piazza F11-1',
                'tipe_kamar' => 'Hanya Jendela',
                'harga' => 1800000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Water Heater', 'Jendela', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],

            // Piazza F7-7
            [
                'alamat' => 'Piazza F7-7',
                'tipe_kamar' => 'Balkon Depan',
                'harga' => 2500000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'TV', 'Water Heater', 'Jendela'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Piazza F7-7',
                'tipe_kamar' => 'Balkon Belakang',
                'harga' => 2250000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'TV', 'Water Heater', 'Jendela'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Piazza F7-7',
                'tipe_kamar' => 'Tanpa Balkon',
                'harga' => 2000000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'TV', 'Water Heater', 'Jendela'],
                'disewakan_untuk' => 'campur'
            ],
            // Tambahkan data Piazza lainnya

            // Zena
            [
                'alamat' => 'Zena G12',
                'tipe_kamar' => 'Standard',
                'harga' => 1900000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Water Heater', 'Laundry 10Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Zena G9',
                'tipe_kamar' => 'Standard',
                'harga' => 1800000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Water Heater', 'Laundry 10Kg'],
                'disewakan_untuk' => 'campur'
            ],

            // Regentown B8
            [
                'alamat' => 'Regentown B8',
                'tipe_kamar' => 'Standard',
                'harga' => 2000000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'TV', 'Water Heater', 'Jendela', 'Listrik', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],

            // Regentown A5-11 (dari data UnitSeeder)
            [
                'alamat' => 'Regentown A5-11',
                'tipe_kamar' => 'Standard',
                'harga' => 2000000, // Harga diasumsikan sama dengan B8
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'TV', 'Water Heater', 'Jendela', 'Listrik', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],

            // Regentown J1-15, J1-16, J1-17 (dari data UnitSeeder)
            [
                'alamat' => 'Regentown J1-15',
                'tipe_kamar' => 'Standard',
                'harga' => 2000000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'TV', 'Water Heater', 'Jendela', 'Listrik', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Regentown J1-16',
                'tipe_kamar' => 'Standard',
                'harga' => 2000000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'TV', 'Water Heater', 'Jendela', 'Listrik', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],
            [
                'alamat' => 'Regentown J1-17',
                'tipe_kamar' => 'Standard',
                'harga' => 2000000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'TV', 'Water Heater', 'Jendela', 'Listrik', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],

            // Studento L18-12 (cowo)
            [
                'alamat' => 'Studento L18-12',
                'tipe_kamar' => 'Standard',
                'harga' => 1800000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Jendela', 'Listrik', 'Laundry 20Kg'],
                'disewakan_untuk' => 'putra'
            ],

            // Studento L19-15-16 (cewe) - Jendela Luar
            [
                'alamat' => 'Studento L19-15-16',
                'tipe_kamar' => 'Jendela Luar',
                'harga' => 1800000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Jendela', 'Listrik', 'Laundry 20Kg'],
                'disewakan_untuk' => 'putri'
            ],

            // Studento L19-15-16 (cewe) - Jendela dalem
            [
                'alamat' => 'Studento L19-15-16',
                'tipe_kamar' => 'Jendela Dalam',
                'harga' => 1700000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Jendela', 'Listrik', 'Laundry 20Kg'],
                'disewakan_untuk' => 'putri'
            ],

            // Studento L22 (cowo) - Jendela luar
            [
                'alamat' => 'Studento L22',
                'tipe_kamar' => 'Jendela Luar',
                'harga' => 1800000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Water Heater', 'Laundry 20Kg'],
                'disewakan_untuk' => 'putra'
            ],

            // Studento L22 (cowo) - Jendela dalem
            [
                'alamat' => 'Studento L22',
                'tipe_kamar' => 'Jendela Dalam',
                'harga' => 1700000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Water Heater', 'Laundry 20Kg'],
                'disewakan_untuk' => 'putra'
            ],

            // Studento L16 no 19 (dari data UnitSeeder)
            [
                'alamat' => 'Studento L16 no 19',
                'tipe_kamar' => 'Standard',
                'harga' => 1800000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Jendela', 'Listrik', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],

            // Studento L22-6 (dari data UnitSeeder)
            [
                'alamat' => 'Studento L22-6',
                'tipe_kamar' => 'Standard',
                'harga' => 1800000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Water Heater', 'Laundry 20Kg'],
                'disewakan_untuk' => 'putra'
            ],

            // Naturale N5
            [
                'alamat' => 'Naturale N5',
                'tipe_kamar' => 'Standard',
                'harga' => 1600000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Water Heater', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],

            // Naturale N5 no 21 (dari data UnitSeeder)
            [
                'alamat' => 'Naturale N5 no 21',
                'tipe_kamar' => 'Standard',
                'harga' => 1600000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Water Heater', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],

            // Anggrek Loka - AC Lt 1 & 2 kmr mandi dalam
            [
                'alamat' => 'Anggrek Loka',
                'tipe_kamar' => 'AC Lantai 1-2 (Kamar Mandi Dalam)',
                'harga' => 1900000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Kamar Mandi Luar', 'Jendela', 'Listrik', 'Cuci Baju'],
                'disewakan_untuk' => 'campur'
            ],

            // Anggrek Loka - AC Lt 1 & 2 kmr mandi luar
            [
                'alamat' => 'Anggrek Loka',
                'tipe_kamar' => 'AC Lantai 1-2 (Kamar Mandi Luar)',
                'harga' => 1750000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Jendela', 'Listrik', 'Cuci Baju'],
                'disewakan_untuk' => 'campur'
            ],

            // Anggrek Loka - AC Lt 3
            [
                'alamat' => 'Anggrek Loka',
                'tipe_kamar' => 'AC Lantai 3',
                'harga' => 1575000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Listrik', 'Cuci Baju'],
                'disewakan_untuk' => 'campur'
            ],

            // Anggrek Loka - NON AC Lt 2
            [
                'alamat' => 'Anggrek Loka',
                'tipe_kamar' => 'Non-AC Lantai 2',
                'harga' => 1300000,
                'fasilitas' => ['Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Listrik', 'Cuci Baju'],
                'disewakan_untuk' => 'campur'
            ],

            // Anggrek Loka - NON AC Lt 3
            [
                'alamat' => 'Anggrek Loka',
                'tipe_kamar' => 'Non-AC Lantai 3',
                'harga' => 1200000,
                'fasilitas' => ['Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Water Heater', 'Listrik', 'Cuci Baju'],
                'disewakan_untuk' => 'campur'
            ],

            // Anggrek loka Jl anggrek pandan 1 (dari UnitSeeder)
            [
                'alamat' => 'Anggrek loka Jl anggrek pandan 1',
                'tipe_kamar' => 'Standard',
                'harga' => 1900000, // Mengikuti harga tertinggi
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Kamar Mandi Luar', 'Jendela', 'Listrik', 'Cuci Baju'],
                'disewakan_untuk' => 'campur'
            ],

            // Ruko Pascal - Kamar mandi luar
            [
                'alamat' => 'Ruko Pascal',
                'tipe_kamar' => 'Kamar Mandi Luar',
                'harga' => 1900000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Token Sendiri'],
                'disewakan_untuk' => 'campur'
            ],

            // Ruko Pascal - Kamar mandi dalam
            [
                'alamat' => 'Ruko Pascal',
                'tipe_kamar' => 'Kamar Mandi Dalam',
                'harga' => 2000000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Token Sendiri'],
                'disewakan_untuk' => 'campur'
            ],

            // Ruko Pascal Timur No 9 (dari UnitSeeder)
            [
                'alamat' => 'Ruko Pascal Timur No 9',
                'tipe_kamar' => 'Standard',
                'harga' => 2000000, // Mengikuti harga tertinggi
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Dalam', 'Token Sendiri'],
                'disewakan_untuk' => 'campur'
            ],

            // Allogio T2
            [
                'alamat' => 'Allogio T2',
                'tipe_kamar' => 'Standard',
                'harga' => 1500000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'TV', 'Water Heater', 'Jendela', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],

            // Allogio B5
            [
                'alamat' => 'Allogio B5',
                'tipe_kamar' => 'Standard',
                'harga' => 1500000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Water Heater', 'Jendela', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],

            // Allogio B6
            [
                'alamat' => 'Allogio B6',
                'tipe_kamar' => 'Standard',
                'harga' => 1600000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Water Heater', 'Jendela', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],

            // Allogio B5-90 (dari UnitSeeder)
            [
                'alamat' => 'Allogio B5-90',
                'tipe_kamar' => 'Standard',
                'harga' => 1500000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Water Heater', 'Jendela', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],

            // Allogio B6-73 (dari UnitSeeder)
            [
                'alamat' => 'Allogio B6-73',
                'tipe_kamar' => 'Standard',
                'harga' => 1600000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Water Heater', 'Jendela', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],

            // Allogio Barat 6 dan 7 (dari UnitSeeder)
            [
                'alamat' => 'Allogio Barat 6 dan 7',
                'tipe_kamar' => 'Standard',
                'harga' => 1600000, // Mengikuti harga B6
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'Water Heater', 'Jendela', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],

            // Allogio T2-3 (dari UnitSeeder)
            [
                'alamat' => 'Allogio T2-3',
                'tipe_kamar' => 'Standard',
                'harga' => 1500000,
                'fasilitas' => ['AC', 'Kasur', 'Lemari', 'Meja Belajar', 'Kamar Mandi Luar', 'TV', 'Water Heater', 'Jendela', 'Laundry 20Kg'],
                'disewakan_untuk' => 'campur'
            ],
        ];

        // Seeder untuk data kamar
        foreach ($dataKamar as $kamar) {
            // Cari unit berdasarkan alamat
            $unit = Unit::where('nama_cluster', 'like', '%' . $kamar['alamat'] . '%')->first();

            if (!$unit) {
                continue; // Skip jika unit tidak ditemukan
            }

            // Buat tipe kamar
            $tipeKamar = TipeKamar::create([
                'id' => Str::uuid(),
                'unit_id' => $unit->id,
                'nama_tipe' => $kamar['tipe_kamar'],
            ]);

            // Buat harga kamar
            HargaKamar::create([
                'id' => Str::uuid(),
                'tipe_kamar_id' => $tipeKamar->id,
                'harga_perbulan' => $kamar['harga'],
                'minimal_deposit' => $kamar['harga'] / 2, // Contoh: deposit 2x harga bulanan
            ]);

            // Update unit untuk menandakan multi_tipe jika ada lebih dari 1 tipe
            if (TipeKamar::where('unit_id', $unit->id)->count() > 1) {
                $unit->update(['multi_tipe' => true]);
            }

            // Update disewakan_untuk jika berbeda dengan default
            if ($kamar['disewakan_untuk'] !== 'campur') {
                $unit->update(['disewakan_untuk' => $kamar['disewakan_untuk']]);
            }

            // Hubungkan fasilitas dengan tipe kamar
            foreach ($kamar['fasilitas'] as $namaFasilitas) {
                $fasilitas = Fasilitas::where('nama', $namaFasilitas)->first();

                if ($fasilitas) {
                    FasilitasTipeKamar::create([
                        'tipe_kamar_id' => $tipeKamar->id,
                        'fasilitas_id' => $fasilitas->id,
                    ]);
                }
            }
        }
    }
}
