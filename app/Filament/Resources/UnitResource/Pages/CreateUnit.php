<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use App\Models\Unit;
use Filament\Actions;
use App\Models\TipeKamar;
use App\Models\HargaKamar;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateUnit extends CreateRecord
{
    protected static string $resource = UnitResource::class;
    protected array $fasilitasTerpilih = [];
    protected array $kamarsData = [];

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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $fasilitas = array_merge(
            $data['fasilitas_umum'] ?? [],
            $data['fasilitas_kamar'] ?? [],
            $data['fasilitas_kamar_mandi'] ?? [],
            $data['fasilitas_parkir'] ?? [],
        );

        $this->fasilitasTerpilih = $fasilitas;
        $this->kamarsData = $data['kamars'] ?? []; // simpan dulu data kamar sebelum di-unset


        unset(
            $data['fasilitas_umum'],
            $data['fasilitas_kamar'],
            $data['fasilitas_kamar_mandi'],
            $data['fasilitas_parkir'],
            $data['kamars']
        );

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        $data = $this->data;

        // Alamat
        $record->alamat()->create($data['alamatUnit']);

        // 1. Simpan Tipe Kamar dulu, dan simpan mapping index ke ID-nya
        $tipeKamars = [];

        if ($data['multi_tipe']) {
            foreach ($data['tipe_kamars'] as $index => $tipeData) {
                $tipe = $record->tipeKamars()->create([
                    'nama_tipe' => $tipeData['nama_tipe'],
                ]);
                $tipeKamars[$index] = $tipe; // key: index, value: model
            }
        } else {
            $tipe = $record->tipeKamars()->create([
                'nama_tipe' => $data['nama_tipe'] ?? 'Tipe Default',
            ]);
            $tipeKamars[0] = $tipe;
        }



        // 3. Simpan Foto Unit
        foreach (['foto_kos_depan' => 'depan', 'foto_kos_dalam' => 'dalam', 'foto_kos_jalan' => 'jalan'] as $field => $kategori) {
            foreach ($data[$field] ?? [] as $path) {
                $record->fotoUnit()->create([
                    'kategori' => $kategori,
                    'path' => $path,
                ]);
            }
        }

        // 4. Simpan Fasilitas Unit
        // Fasilitas per Tipe Kamar
        // foreach ($data['fasilitas_per_tipe'] ?? [] as $item) {
        //     $tipeIndex = $item['tipe_kamar_index'] ?? null;

        //     if ($tipeIndex !== null && isset($tipeKamars[$tipeIndex])) {
        //         $tipeKamar = $tipeKamars[$tipeIndex];

        //         foreach (['fasilitas_umum', 'fasilitas_kamar', 'fasilitas_kamar_mandi', 'fasilitas_parkir'] as $kategori) {
        //             foreach ($item[$kategori] ?? [] as $fasilitasId) {
        //                 $record->fasilitasUnits()->create([
        //                     'fasilitas_id' => $fasilitasId,
        //                     'tipe_kamar_id' => $tipeKamar->id,
        //                 ]);
        //             }
        //         }
        //     }
        // }

        // Fasilitas berdasarkan tipe kamar
        foreach ($data['fasilitas_per_tipe'] ?? [] as $fasilitasEntry) {
            $tipeIndex = $fasilitasEntry['tipe_kamar_id'];
            $tipeModel = $tipeKamars[$tipeIndex] ?? null;

            if ($tipeModel) {
                foreach ($fasilitasEntry['fasilitas'] ?? [] as $fasilitasId) {
                    $tipeModel->fasilitasKos()->create([
                        'fasilitas_id' => $fasilitasId,
                    ]);
                }
            }
        }

        // 2. Simpan Harga Kamar
        foreach ($data['harga_kamars'] ?? [] as $harga) {
            $tipeIndex = $harga['tipe_kamar_id'];
            $tipeModel = $tipeKamars[$tipeIndex] ?? null;

            if ($tipeModel) {
                $tipeModel->hargaKamars()->create([
                    'harga_perbulan' => $harga['harga_perbulan'],
                    'minimal_deposit' => $harga['minimal_deposit'] ?? null,
                ]);
            }
        }


        // 5. Simpan Ketersediaan Kamar
        foreach ($data['kamars'] ?? [] as $kamarData) {
            $tipeIndex = $kamarData['tipe_kamar_id'] ?? null;
            $tipeModel = $tipeKamars[$tipeIndex] ?? null;

            if ($tipeModel) {
                $tipeModel->ketersediaanKamars()->create([
                    'nama' => $kamarData['nama'],
                    'lantai' => $kamarData['lantai'] ?? null,
                    'ukuran' => $kamarData['ukuran'] ?? null,
                    'terisi' => $kamarData['terisi'] ?? false,
                    'unit_id' => $record->id,
                ]);
            }
        }
    }
}
