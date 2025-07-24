<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Penghuni;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SyncPenghuniUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data yang cocok berdasarkan nama (tanpa spasi ganda dan case-insensitive)
        $matched = DB::select("
            SELECT p.id AS penghuni_id, u.id AS user_id
            FROM penghunis p
            JOIN users u ON
                LOWER(TRIM(REGEXP_REPLACE(p.nama, '\\s+', ' '))) =
                LOWER(TRIM(REGEXP_REPLACE(u.name, '\\s+', ' ')))
        ");

        $total = 0;

        foreach ($matched as $match) {
            DB::table('penghunis')
                ->where('id', $match->penghuni_id)
                ->update(['user_id' => $match->user_id]);

            $total++;
        }

        $this->command->info("Seeder selesai. Total updated: {$total}");
    }
}
