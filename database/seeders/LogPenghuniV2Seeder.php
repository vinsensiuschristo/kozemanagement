<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LogPenghuniV2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat mapping penghuni
        $penghuniMapping = []; // Format: [ID_Penghuni_lama => id_penghuni_baru]

        // 2. Buat mapping kamar
        $kamarMapping = []; // Format: [ID_Kamar_lama => id_kamar_baru]

        // 3. Proses migrasi log_penghuni
        $logsLama = DB::connection('mysql_lama')->table('logpenghuni')->get();

        foreach ($logsLama as $log) {
            if (!isset($penghuniMapping[$log->ID_Penghuni]) || !isset($kamarMapping[$log->ID_Kamar])) {
                continue; // Skip jika data tidak ditemukan
            }

            DB::connection('mysql')->table('log_penghuni')->insert([
                'id' => Str::uuid(),
                'penghuni_id' => $penghuniMapping[$log->ID_Penghuni],
                'kamar_id' => $kamarMapping[$log->ID_Kamar],
                'tanggal' => Carbon::createFromFormat('d/m/Y', $log->Tanggal)->format('Y-m-d'),
                'status' => $log->Status === 'Check In' ? 'In' : 'Out',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
