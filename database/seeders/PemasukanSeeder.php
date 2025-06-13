<?php

namespace Database\Seeders;

use App\Models\Pemasukan;
use Database\Factories\PemasukanFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PemasukanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pemasukan::factory()->count(15)->create();
    }
}
