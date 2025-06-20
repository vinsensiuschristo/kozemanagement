<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SeederLamaKamarDataV2Seeder extends Seeder
{
    // Daftar mapping khusus tipe kamar
    private $specialTypeMappings = [
        'Alesha Blue 1-2' => [
            'Wc Kecil' => ['202'],
            'Wc Besar' => ['101']
        ],
        'Alesha Yellow 6-7' => [
            'Kamar Besar' => ['101', '201', '301']
        ],
        'Piazza F10-2' => [
            'Balkon depan' => ['201', '301'],
            'Balkon Belakang' => ['202', '302']
        ],
        'Piazza F7-7' => [
            'Tanpa Balkon' => ['101', '102']
        ],
        'Studento L19-15-16 (cewe)' => [
            'Jendela dalem' => ['6', '7']
        ]
    ];

    public function run()
    {
        try {
            Log::info('Memulai migrasi data kamar');

            // Step 1: Mapping unit
            $this->prosesMappingUnit();

            // Step 2: Buat tipe kamar khusus berdasarkan mapping
            $this->prosesBuatTipeKamarKhusus();

            // Step 3: Buat tipe kamar standar untuk unit yang belum memiliki tipe
            $this->prosesBuatTipeKamarStandar();

            // Step 4: Migrasi kamar dengan mapping yang tepat
            $this->prosesMigrasiKamarDenganTipe();

            Log::info('Migrasi data kamar selesai');
        } catch (\Exception $e) {
            Log::error('ERROR: ' . $e->getMessage());
            throw $e;
        }
    }

    private function prosesMappingUnit()
    {
        Log::info('Memulai mapping unit lama ke baru');

        DB::transaction(function () {
            $unitsLama = DB::connection('mysql_lama')
                ->table('unit')
                ->get();

            foreach ($unitsLama as $unit) {
                $namaCluster = $this->generateNamaCluster($unit->Alamat, $unit->NoUnit);

                $unitBaru = DB::table('units')
                    ->where('nama_cluster', $namaCluster)
                    ->first();

                if ($unitBaru) {
                    DB::table('unit_mappings')->updateOrInsert(
                        ['old_unit_id' => $unit->ID_Unit],
                        [
                            'new_unit_id' => $unitBaru->id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]
                    );
                } else {
                    Log::warning("Unit tidak termapping: {$unit->Alamat} - {$unit->NoUnit}");
                }
            }
        });
    }

    private function prosesBuatTipeKamarKhusus()
    {
        Log::info('Membuat tipe kamar khusus berdasarkan mapping');

        DB::transaction(function () {
            foreach ($this->specialTypeMappings as $unitName => $types) {
                $unit = DB::table('units')
                    ->where('nama_cluster', 'like', "%{$unitName}%")
                    ->first();

                if (!$unit) {
                    Log::warning("Unit khusus tidak ditemukan: {$unitName}");
                    continue;
                }

                foreach ($types as $typeName => $kamars) {
                    DB::table('tipe_kamars')->updateOrInsert(
                        [
                            'unit_id' => $unit->id,
                            'nama_tipe' => $typeName
                        ],
                        [
                            'id' => Str::uuid(),
                            'created_at' => now(),
                            'updated_at' => now()
                        ]
                    );
                }
            }
        });
    }

    private function prosesBuatTipeKamarStandar()
    {
        Log::info('Membuat tipe kamar standar untuk unit yang belum memiliki tipe');

        DB::transaction(function () {
            $unitsWithoutType = DB::table('units')
                ->leftJoin('tipe_kamars', 'units.id', '=', 'tipe_kamars.unit_id')
                ->whereNull('tipe_kamars.id')
                ->select('units.id', 'units.nama_cluster')
                ->get();

            foreach ($unitsWithoutType as $unit) {
                DB::table('tipe_kamars')->insert([
                    'id' => Str::uuid(),
                    'unit_id' => $unit->id,
                    'nama_tipe' => 'Standard',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        });
    }

    private function prosesMigrasiKamarDenganTipe()
    {
        Log::info('Memulai migrasi kamar dengan mapping tipe yang tepat');

        $totalMigrated = 0;
        $totalSkipped = 0;

        DB::connection('mysql_lama')
            ->table('kamar')
            ->orderBy('ID_Unit')
            ->chunk(500, function ($kamars) use (&$totalMigrated, &$totalSkipped) {
                foreach ($kamars as $kamar) {
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

                        $unit = DB::table('units')
                            ->where('id', $mapping->new_unit_id)
                            ->first();

                        if (!$unit) {
                            $totalSkipped++;
                            Log::warning("Unit baru ID {$mapping->new_unit_id} tidak ditemukan");
                            DB::rollBack();
                            continue;
                        }

                        // Cari tipe kamar berdasarkan mapping khusus
                        $tipeKamar = $this->cariTipeKamarYangSesuai($unit->nama_cluster, $kamar->NoKamar, $mapping->new_unit_id);

                        if (!$tipeKamar) {
                            $totalSkipped++;
                            Log::warning("Tidak ditemukan tipe kamar untuk {$unit->nama_cluster} kamar {$kamar->NoKamar}");
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
                            'updated_at' => now()
                        ]);

                        DB::commit();
                        $totalMigrated++;
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error("Gagal migrasi kamar {$kamar->ID_Kamar}: " . $e->getMessage());
                        $totalSkipped++;
                    }
                }

                Log::info("Progress: {$totalMigrated} berhasil, {$totalSkipped} gagal");
            });

        Log::info("Migrasi selesai: {$totalMigrated} kamar berhasil, {$totalSkipped} gagal");
    }

    private function cariTipeKamarYangSesuai($unitName, $noKamar, $unitId)
    {
        // Cek mapping khusus terlebih dahulu
        foreach ($this->specialTypeMappings as $pattern => $types) {
            if (str_contains($unitName, $pattern)) {
                foreach ($types as $typeName => $kamars) {
                    if (in_array($noKamar, $kamars)) {
                        return DB::table('tipe_kamars')
                            ->where('unit_id', $unitId)
                            ->where('nama_tipe', $typeName)
                            ->first();
                    }
                }
            }
        }

        // Jika tidak ada mapping khusus, gunakan tipe standar
        return DB::table('tipe_kamars')
            ->where('unit_id', $unitId)
            ->where('nama_tipe', 'Standard')
            ->first();
    }

    private function generateNamaCluster($alamat, $noUnit)
    {
        return trim($alamat) . ' ' . trim($noUnit);
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
