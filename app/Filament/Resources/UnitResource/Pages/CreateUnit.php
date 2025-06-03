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

    public array $tipeKamarOptions = [];

    public function updated($name, $value): void
    {
        if ($name === 'data.tipe_kamars') {
            $this->updateTipeKamarOptions($value);
        }
    }


    public function updateTipeKamarOptions(array $tipeKamars)
    {
        $this->tipeKamarOptions = collect($tipeKamars)
            ->mapWithKeys(function ($item, $index) {
                return [$index => $item['nama_tipe'] ?? 'Tipe ' . ($index + 1)];
            })
            ->toArray();
    }



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

        // Tipe Kamar
        $tipeKamars = [];
        if ($data['multi_tipe']) {
            foreach ($data['tipe_kamars'] as $tipeData) {
                $tipe = $record->tipeKamars()->create([
                    'nama_tipe' => $tipeData['nama_tipe'],
                ]);
                $tipeKamars[] = $tipe;
            }
        } else {
            $tipe = $record->tipeKamars()->create([
                'nama_tipe' => $data['nama_tipe'] ?? 'Tipe Default',
            ]);
            $tipeKamars[] = $tipe;
        }

        // Harga Kamar
        foreach ($data['harga_kamars'] ?? [] as $index => $harga) {
            if (isset($tipeKamars[$index])) {
                $tipeKamars[$index]->hargaKamars()->create([
                    'harga_perbulan' => $harga['harga_perbulan'],
                    'minimal_deposit' => $harga['minimal_deposit'] ?? null,
                ]);
            }
        }

        // Foto Unit
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

        // Ketersediaan Kamar
        foreach ($data['kamars'] ?? [] as $kamarData) {
            $tipeIndex = $kamarData['tipe_kamar_id'] ?? null;
            if ($tipeIndex !== null && isset($tipeKamars[$tipeIndex])) {
                $tipeKamars[$tipeIndex]->ketersediaanKamars()->create([
                    'nama' => $kamarData['nama'],
                    'lantai' => $kamarData['lantai'] ?? null,
                    'ukuran' => $kamarData['ukuran'] ?? null,
                    'terisi' => $kamarData['terisi'] ?? false,
                ]);
            }
        }
    }
}
