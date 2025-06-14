<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HargaKamar;

class FixDepositSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hargaKamars = HargaKamar::all();

        foreach ($hargaKamars as $hargaKamar) {
            $hargaKamar->update([
                'minimal_deposit' => $hargaKamar->harga_perbulan / 2
            ]);
        }
    }
}
