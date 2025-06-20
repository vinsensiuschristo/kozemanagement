<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MigrateKamarDataV2Seeder extends Seeder
{
    // Daftar mapping khusus tipe kamar
    private $specialTypeMappings = [
        "00d15533-2c4a-435d-a56e-5703d2eb21d8" => [
            'Wc Kecil' => ['202'],
            'Wc Besar' => ['101'],
        ],
        "d2093cd4-72b0-4560-80b5-0f2ab66f8ece" => [
            'Kamar Kecil' => ['202', '302'],
            'Kamar Besar' => ['101', '201', '301'],
        ],
        "68cc416f-8d4f-428f-9b46-b3e6889df519" => [
            'Balkon depan' => ['201', '301'],
            'Balkon Belakang' => ['202', '302'],
            'Tanpa Balkon' => [],
        ],
        // Piaza the Mozia F11-1
        "6727fd56-fa32-43fb-bd43-5fa592411574" => [
            'Hanya Jendela' => ['101', '102', '201', '202', '301', '302'],
        ],
        // Piaza the Mozia F3-5
        "adec7989-092b-4314-bfd0-a4507e9c26ed" => [
            'Balkon Depan' => ['201', '301'],
            'Balkon Belakang' => ['202', '302'],
            'Tanpa Balkon' => ['101', '102'],
        ],
        // Piaza the Mozia F5-5
        "0a5b4dc9-32bf-432c-ac23-771c6eb9b944" => [
            'Balkon Depan' => ['201', '301'],
            'Balkon Belakang' => ['202', '302'],
            'Tanpa Balkon' => ['101', '102'],
        ],
        "5de3cd8e-bb98-4b3a-b297-8a8d2582f94f" => [
            'Balkon Depan' => ['201', '301'],
            'Balkon Belakang' => ['202', '302'],
            'Tanpa Balkon' => ['101', '102'],
        ],
        // Studento L19-15-16 (cewe)
        "592ff0d9-60ec-47a6-8f67-ac07494d23aa" => [
            'Jendela Luar' => ['1', '2'],
            'Jendela Dalam' => ['6', '7'],
        ],
        "e54d3124-5f3d-4612-ba7b-29916a90d31f" => [
            'Jendela Luar' => ['2.3', '2.1'],
            'Jendela Dalam' => ['2.2', '2.5', '2.6'],
        ],
        // Anggrek Loka
        "4f8e093e-ada0-4b55-b637-25cdedc99dcc" => [
            'AC Lt 1 & 2 Kamar mandi dalam' => [],
            'AC Lt 1 & 2 Kamar mandi luar' => [],
            'AC Lt 3' => [],
            'Non AC Lt 2' => [],
            'Non AC Lt 3' => [],
        ],
        // Pascal
        "b994caf8-6bc0-463d-8a65-beab0dc293cd" => [
            'Kamar mandi luar' => ['201', '202', '203', '301', '302', '303'], // Kamar mandi luar = Tipe kamar private wc
            'Kamar mandi dalam' => ['204', '205', '304', '305'],
        ],
        "0b2c1811-0832-43b1-8f07-a85361108ccf" => [
            'Khusus Cewe' => ['101', '102', '103', '105', '106', '201', '202', '203', '205', '206', '207', '208', '301', '302', '303', '305', '306', '307', '308'],
        ],
    ];

    public function run()
    {
        try {
            Log::info('Memulai migrasi data kamar');

            $this->prosesMappingUnit();
            $this->prosesBuatTipeKamarKhusus();
            $this->prosesBuatTipeKamarStandar();
            $this->prosesMigrasiKamarDenganTipe();

            $this->updateKamarDenganTipeBaru();

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
            $unitsLama = DB::connection('mysql_lama')->table('unit')->get();

            foreach ($unitsLama as $unit) {
                $namaCluster = $this->generateNamaCluster($unit->Alamat, $unit->NoUnit);

                $unitBaru = DB::table('units')
                    ->whereRaw('LOWER(nama_cluster) = ?', [strtolower($namaCluster)])
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
        Log::info('Membuat tipe kamar khusus berdasarkan mapping UUID');

        DB::transaction(function () {
            foreach ($this->specialTypeMappings as $unitId => $types) {
                foreach ($types as $typeName => $kamars) {
                    // Cek apakah tipe kamar sudah ada untuk unit ini
                    $existing = DB::table('tipe_kamars')
                        ->where('unit_id', $unitId)
                        ->where('nama_tipe', $typeName)
                        ->first();

                    if (!$existing) {
                        DB::table('tipe_kamars')->insert([
                            'id' => Str::uuid(),
                            'unit_id' => $unitId,
                            'nama_tipe' => $typeName,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        Log::info("Tipe kamar '{$typeName}' berhasil dibuat untuk unit {$unitId}");
                    } else {
                        Log::info("Tipe kamar '{$typeName}' sudah ada untuk unit {$unitId}, dilewati");
                    }
                }
            }
        });
    }

    private function prosesBuatTipeKamarStandar()
    {
        Log::info('Membuat tipe kamar standar untuk unit yang belum memiliki tipe');

        DB::transaction(function () {
            $unitsWithoutType = DB::table('units')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('tipe_kamars')
                        ->whereColumn('tipe_kamars.unit_id', 'units.id');
                })
                ->select('id')
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
                            DB::rollBack();
                            continue;
                        }

                        $tipeKamar = $this->cariTipeKamarByUnitId($mapping->new_unit_id, $kamar->NoKamar);

                        if (!$tipeKamar) {
                            $tipeKamar = DB::table('tipe_kamars')
                                ->where('unit_id', $mapping->new_unit_id)
                                ->where('nama_tipe', 'Standard')
                                ->first();

                            if (!$tipeKamar) {
                                $totalSkipped++;
                                DB::rollBack();
                                continue;
                            }
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
    }

    private function cariTipeKamarByUnitId($unitId, $noKamar)
    {
        if (!isset($this->specialTypeMappings[$unitId])) return null;

        foreach ($this->specialTypeMappings[$unitId] as $tipe => $kamars) {
            if (in_array($noKamar, $kamars)) {
                return DB::table('tipe_kamars')
                    ->where('unit_id', $unitId)
                    ->where('nama_tipe', $tipe)
                    ->first();
            }
        }

        return null;
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

    private function updateKamarDenganTipeBaru()
    {
        Log::info('Memulai update kamar berdasarkan specialTypeMappings terbaru');

        foreach ($this->specialTypeMappings as $unitId => $tipeKamars) {
            foreach ($tipeKamars as $tipe => $listNoKamar) {
                if (empty($listNoKamar)) {
                    continue;
                }

                $tipeKamar = DB::table('tipe_kamars')
                    ->where('unit_id', $unitId)
                    ->where('nama_tipe', $tipe)
                    ->first();

                if (!$tipeKamar) {
                    Log::warning("Tipe kamar '$tipe' tidak ditemukan untuk unit $unitId");
                    continue;
                }

                foreach ($listNoKamar as $noKamar) {
                    $affected = DB::table('kamars')
                        ->where('unit_id', $unitId)
                        ->where('nama', $noKamar)
                        ->update([
                            'tipe_kamar_id' => $tipeKamar->id,
                            'updated_at' => now(),
                        ]);

                    if ($affected === 0) {
                        Log::warning("Kamar $noKamar tidak ditemukan di unit $unitId untuk update ke tipe $tipe");
                    }
                }
            }
        }

        Log::info('Update kamar selesai');
    }
}
