<?php

namespace Database\Seeders;

use App\Models\LogPenghuni;
use Illuminate\Container\Attributes\Log;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LogPenghuniSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LogPenghuni::factory()->count(20)->create();
    }
}
