<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MigrateKamarDataSeeder extends Seeder
{
    public function run()
    {
        try {
            Log::info('Mengecek koneksi database mysql_lama');

            DB::connection('mysql_lama')->getPdo();

            Log::info('Koneksi mysql_lama berhasil');

            // Step 1: Mapping unit
            $this->prosesMappingUnit();

            // Step 2: Migrasi kamar
            $this->prosesMigrasiKamar();

            Log::info('Migrasi data kamar selesai');
        } catch (\Exception $e) {
            Log::error('ERROR: ' . $e->getMessage());
            throw $e;
        }
    }


    private function prosesMappingUnit()
    {
        Log::info('Memulai mapping unit lama ke baru');

        // ⛔ TRUNCATE tidak boleh dalam transaksi
        // DB::table('unit_mappings')->truncate();

        DB::transaction(function () {
            $unitsLama = DB::connection('mysql_lama')
                ->table('unit')
                ->get();

            $mappedCount = 0;

            foreach ($unitsLama as $unit) {
                $unitBaru = DB::table('units')
                    ->where('nama_cluster', 'like', "%{$unit->Alamat}%")
                    ->where('nama_cluster', 'like', "%{$unit->NoUnit}%")
                    ->first();

                if ($unitBaru) {
                    DB::table('unit_mappings')->insert([
                        'old_unit_id' => $unit->ID_Unit,
                        'new_unit_id' => $unitBaru->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $mappedCount++;
                } else {
                    Log::warning("Unit tidak termapping: {$unit->Alamat} - {$unit->NoUnit}");
                }
            }

            Log::info("Selesai mapping {$mappedCount} unit");
        });
    }



    private function prosesBuatTipeKamar()
    {
        try {
            DB::transaction(function () {
                Log::info('Memeriksa unit tanpa tipe kamar');

                $unitsWithoutType = DB::table('units')
                    ->leftJoin('tipe_kamars', 'units.id', '=', 'tipe_kamars.unit_id')
                    ->whereNull('tipe_kamars.id')
                    ->select('units.id', 'units.nama_cluster')
                    ->get();

                Log::info("Menemukan {$unitsWithoutType->count()} unit tanpa tipe kamar");

                foreach ($unitsWithoutType as $unit) {
                    DB::table('tipe_kamars')->insert([
                        'id' => Str::uuid(),
                        'unit_id' => $unit->id,
                        'nama_tipe' => 'Standard',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                Log::info('Selesai membuat tipe kamar default');
            });
        } catch (\Exception $e) {
            Log::error('Gagal membuat tipe kamar: ' . $e->getMessage());
            throw $e;
        }
    }

    private function prosesMigrasiKamar()
    {
        Log::info('Memulai migrasi data kamar');

        // DB::table('kamars')->truncate();
        try {
            $totalMigrated = 0;
            $totalSkipped = 0;

            // Ambil semua data lalu chunk manual
            $allKamars = DB::connection('mysql_lama')
                ->table('kamar')
                ->orderBy('ID_Unit')
                ->get()
                ->chunk(500);

            foreach ($allKamars as $kamarsChunk) {
                foreach ($kamarsChunk as $kamar) {
                    try {
                        DB::beginTransaction();

                        $mapping = DB::table('unit_mappings')
                            ->where('old_unit_id', $kamar->ID_Unit)
                            ->first();

                        if (!$mapping) {
                            $totalSkipped++;
                            Log::warning("Unit lama ID {$kamar->ID_Unit} tidak ditemukan mapping-nya");
                            DB::rollBack();
                            continue;
                        }

                        $tipeKamar = DB::table('tipe_kamars')
                            ->where('unit_id', $mapping->new_unit_id)
                            ->first();

                        if (!$tipeKamar) {
                            $totalSkipped++;
                            Log::warning("Unit {$mapping->new_unit_id} tidak memiliki tipe kamar");
                            DB::rollBack();
                            continue;
                        }

                        DB::table('kamars')->insert([
                            'id' => Str::uuid(),
                            'unit_id' => $mapping->new_unit_id,
                            'tipe_kamar_id' => $tipeKamar->id,
                            'nama' => $kamar->NoKamar,
                            'ukuran' => null,
                            'lantai' => $this->extractLantai($kamar->NoKamar),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        DB::commit();
                        $totalMigrated++;
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error("Gagal migrasi kamar {$kamar->ID_Kamar}: " . $e->getMessage());
                        $totalSkipped++;
                    }
                }

                Log::info("Progress sementara: {$totalMigrated} kamar berhasil, {$totalSkipped} gagal");
            }

            Log::info("Migrasi kamar selesai: {$totalMigrated} berhasil, {$totalSkipped} gagal");
        } catch (\Exception $e) {
            Log::error('Gagal migrasi kamar: ' . $e->getMessage());
            throw $e;
        }
    }


    private function extractLantai($noKamar)
    {
        if (is_numeric($noKamar)) {
            return (int)substr($noKamar, 0, 1);
        }

        if (preg_match('/(l|floor|lt|lantai)(\d+)/i', $noKamar, $matches)) {
            return (int)$matches[2];
        }

        return null;
    }
}
