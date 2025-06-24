<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateUnitOwnerSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua owner dari DB baru, keyBy nomor_ktp untuk pencocokan cepat
        $ownersBaru = DB::table('owners')->get()->keyBy('nomor_ktp');

        // Ambil semua owner dari DB lama
        $ownersLama = DB::connection('mysql_lama')->table('owner')->get();

        // Bangun mapping: ID_Owner lama → UUID owner baru
        $ownerMapping = [];

        foreach ($ownersLama as $ownerLama) {
            $ktp = trim($ownerLama->NoKTP);

            if ($ownersBaru->has($ktp)) {
                $ownerMapping[$ownerLama->ID_Owner] = $ownersBaru[$ktp]->id;
            } else {
                echo "❌ Owner lama dengan KTP {$ktp} tidak ditemukan di database baru\n";
            }
        }

        // Ambil semua unit dari DB lama untuk referensi ID_Owner lama
        $unitsLama = DB::connection('mysql_lama')->table('unit')->get()->keyBy('NoKontrak');

        // Ambil semua unit dari DB baru
        $unitsBaru = DB::table('units')->get();

        $updated = 0;
        $skipped = 0;

        foreach ($unitsBaru as $unitBaru) {
            $noKontrak = trim($unitBaru->nomor_kontrak);
            $unitLama = $unitsLama[$noKontrak] ?? null;

            if (!$unitLama) {
                echo "⚠️ Unit dengan NoKontrak {$noKontrak} tidak ditemukan di DB lama\n";
                $skipped++;
                continue;
            }

            $idOwnerLama = (int)$unitLama->ID_Owner;

            if (!isset($ownerMapping[$idOwnerLama])) {
                echo "⚠️ Tidak ada mapping untuk ID_Owner lama {$idOwnerLama}\n";
                $skipped++;
                continue;
            }

            DB::table('units')->where('id', $unitBaru->id)->update([
                'id_owner' => $ownerMapping[$idOwnerLama],
                'updated_at' => now(),
            ]);

            echo "✅ Unit {$unitBaru->nama_cluster} (NoKontrak: $noKontrak) → Owner UUID: {$ownerMapping[$idOwnerLama]}\n";
            $updated++;
        }

        echo "\n🎯 Total diupdate: {$updated}, dilewati: {$skipped}\n";
    }
}
