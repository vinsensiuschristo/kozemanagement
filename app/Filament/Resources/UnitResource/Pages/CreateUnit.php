<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use App\Models\Unit;
use Filament\Actions;
use App\Models\TipeKamar;
use App\Models\HargaKamar;
use Filament\Resources\Pages\CreateRecord;

class CreateUnit extends CreateRecord
{
    protected static string $resource = UnitResource::class;
    protected array $fasilitasTerpilih = [];

    /**
     * Override untuk menyimpan Unit beserta foto-fotonya
     */
    // protected function handleRecordCreation(array $data): Unit
    // {
    //     // Ambil array path foto dari form
    //     $fotoFiles = $data['foto_unit'] ?? [];

    //     // Simpan data Unit (kecuali 'foto_unit')
    //     $unit = Unit::create(collect($data)->except('foto_unit')->toArray());

    //     // Simpan foto ke relasi fotoUnits
    //     foreach ($fotoFiles as $filePath) {
    //         $unit->fotoUnits()->create([
    //             'path' => $filePath,
    //         ]);
    //     }

    //     return $unit;
    // }

    // ✅ Method untuk manipulasi data sebelum create
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $fasilitas = array_merge(
            $data['fasilitas_umum'] ?? [],
            $data['fasilitas_kamar'] ?? [],
            $data['fasilitas_kamar_mandi'] ?? [],
            $data['fasilitas_parkir'] ?? [],
        );

        $this->fasilitasTerpilih = $fasilitas;

        unset(
            $data['fasilitas_umum'],
            $data['fasilitas_kamar'],
            $data['fasilitas_kamar_mandi'],
            $data['fasilitas_parkir'],
        );

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        $data = $this->data;

        // Alamat
        $record->alamat()->create($data['alamatUnit']);

        // Tipe & Harga Kamar (jika tidak multi_tipe)
        if (!$data['multi_tipe']) {
            $tipe = $record->tipeKamars()->create([
                'nama_tipe' => 'Tipe Default',
            ]);

            foreach ($data['harga_kamars'] ?? [] as $harga) {
                $tipe->hargaKamars()->create([
                    'harga_perbulan' => $harga['harga_perbulan'],
                    'minimal_deposit' => $harga['minimal_deposit'] ?? null,
                ]);
            }
        }

        // Foto unit
        foreach (['foto_kos_depan' => 'depan', 'foto_kos_dalam' => 'dalam', 'foto_kos_jalan' => 'jalan'] as $field => $kategori) {
            foreach ($data[$field] ?? [] as $path) {
                $record->fotoUnit()->create([
                    'kategori' => $kategori,
                    'path' => $path,
                ]);
            }
        }

        // Fasilitas
        foreach (['fasilitas_umum', 'fasilitas_kamar', 'fasilitas_kamar_mandi', 'fasilitas_parkir'] as $kategori) {
            foreach ($data[$kategori] ?? [] as $fasilitasId) {
                $record->fasilitasUnits()->create([
                    'fasilitas_id' => $fasilitasId,
                ]);
            }
        }

        // Tipe awal jika multi_tipe (tipe_awal hanya placeholder awal)
        if ($data['multi_tipe'] && !empty($data['tipe_awal'])) {
            $record->tipeKamars()->create([
                'nama_tipe' => $data['tipe_awal'],
            ]);
        }
    }
}
