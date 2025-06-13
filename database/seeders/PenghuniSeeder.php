<?php

namespace Database\Seeders;

use App\Models\Penghuni;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenghuniSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Penghuni::factory()->count(10)->create();
    }
}
