<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUnit extends CreateRecord
{
    protected static string $resource = UnitResource::class;

    protected array $fasilitasTerpilih = [];
    protected array $kamarsData = [];
    protected array $fasilitasKamarsData = [];
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
            ->mapWithKeys(fn($item, $index) => [$index => $item['nama_tipe'] ?? 'Tipe ' . ($index + 1)])
            ->toArray();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Gabungkan semua fasilitas umum dan fasilitas kamar yang dipilih
        $this->fasilitasTerpilih = array_merge(
            $data['fasilitas_umum'] ?? [],
            $data['fasilitas_kamar'] ?? [],
            $data['fasilitas_kamar_mandi'] ?? [],
            $data['fasilitas_parkir'] ?? [],
        );

        // Data kamar untuk nanti disimpan
        $this->kamarsData = $data['kamars'] ?? [];

        // Fasilitas per tipe kamar
        $this->fasilitasKamarsData = $data['fasilitas_per_tipe'] ?? [];

        // Hapus key yang tidak disimpan langsung di tabel utama unit
        unset(
            $data['fasilitas_umum'],
            $data['fasilitas_kamar'],
            $data['fasilitas_kamar_mandi'],
            $data['fasilitas_parkir'],
            $data['kamars'],
            $data['fasilitas_per_tipe'],
        );

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        $data = $this->data;

        // Simpan Alamat Unit
        $record->alamat()->create($data['alamatUnit']);

        // Simpan Fasilitas Unit
        foreach ($this->fasilitasTerpilih as $fasilitasId) {
            $record->fasilitasUnit()->create(['fasilitas_id' => $fasilitasId]);
        }

        // Simpan Foto Unit
        foreach (['foto_kos_depan' => 'depan', 'foto_kos_dalam' => 'dalam', 'foto_kos_jalan' => 'jalan'] as $field => $kategori) {
            foreach ($data[$field] ?? [] as $path) {
                $record->fotoUnit()->create([
                    'kategori' => $kategori,
                    'path' => $path,
                ]);
            }
        }

        // Simpan Tipe Kamar
        // $tipeKamars = [];
        // if ($data['multi_tipe']) {
        //     foreach ($data['tipe_kamars'] as $tipeData) {
        //         $tipe = $record->tipeKamars()->create([
        //             'nama_tipe' => $tipeData['nama_tipe'],
        //         ]);
        //         $tipeKamars[$tipe->id] = $tipe;
        //     }
        // } else {
        //     $tipe = $record->tipeKamars()->create([
        //         'nama_tipe' => $data['nama_tipe'] ?? 'Tipe Default',
        //     ]);
        //     $tipeKamars[$tipe->id] = $tipe;
        // }

        // // Simpan Fasilitas tiap Tipe Kamar
        // foreach ($this->fasilitasKamarsData ?? [] as $item) {
        //     $tipeId = $item['tipe_kamar_id'] ?? null;
        //     if ($tipeId && isset($tipeKamars[$tipeId])) {
        //         $tipeModel = $tipeKamars[$tipeId];
        //         $allFasilitas = array_merge(
        //             $item['fasilitas_umum'] ?? [],
        //             $item['fasilitas_kamar'] ?? [],
        //             $item['fasilitas_kamar_mandi'] ?? [],
        //             $item['fasilitas_parkir'] ?? [],
        //         );
        //         $tipeModel->fasilitas()->sync($allFasilitas);
        //     }
        // }

        // // Simpan Harga Kamar
        // foreach ($data['harga_kamars'] ?? [] as $harga) {
        //     $tipeIndex = $harga['tipe_kamar_id'];
        //     $tipeModel = $tipeKamars[$tipeIndex] ?? null;
        //     if ($tipeModel) {
        //         $tipeModel->hargaKamars()->create([
        //             'harga_perbulan' => $harga['harga_perbulan'],
        //             'minimal_deposit' => $harga['minimal_deposit'] ?? null,
        //         ]);
        //     }
        // }

        // // Simpan Ketersediaan Kamar
        // foreach ($this->kamarsData ?? [] as $kamarData) {
        //     $tipeIndex = $kamarData['tipe_kamar_id'] ?? null;
        //     $tipeModel = $tipeKamars[$tipeIndex] ?? null;
        //     if ($tipeModel) {
        //         $tipeModel->ketersediaanKamars()->create([
        //             'nama' => $kamarData['nama'],
        //             'lantai' => $kamarData['lantai'] ?? null,
        //             'ukuran' => $kamarData['ukuran'] ?? null,
        //             'terisi' => $kamarData['terisi'] ?? false,
        //             'unit_id' => $record->id,
        //         ]);
        //     }
        // }
        // Simpan tipe kamar dan relasi lainnya


        // if (!empty($data['tipe_kamars'])) {
        //     foreach ($data['tipe_kamars'] as $tipeKamarData) {
        //         $tipeKamar = $record->tipeKamars()->create([
        //             'nama_tipe' => $tipeKamarData['nama_tipe'],
        //         ]);

        //         // Simpan fasilitas per tipe kamar
        //         foreach ($this->fasilitasKamarsData as $fasilitasPerTipe) {
        //             if ($fasilitasPerTipe['tipe_kamar_id'] == $tipeKamarData['id']) { // bandingkan dengan id dari data input
        //                 $allFasilitas = array_merge(
        //                     $fasilitasPerTipe['fasilitas_umum'] ?? [],
        //                     $fasilitasPerTipe['fasilitas_kamar'] ?? [],
        //                     $fasilitasPerTipe['fasilitas_kamar_mandi'] ?? [],
        //                     $fasilitasPerTipe['fasilitas_parkir'] ?? [],
        //                 );
        //                 $tipeKamar->fasilitas()->sync($allFasilitas);
        //             }
        //         }

        //         // Simpan harga kamar
        //         foreach ($data['harga_kamars'] ?? [] as $hargaKamar) {
        //             if ($hargaKamar['tipe_kamar_id'] == $tipeKamarData['id']) {
        //                 $tipeKamar->hargaKamars()->create([
        //                     'harga_perbulan' => $hargaKamar['harga_perbulan'],
        //                     'minimal_deposit' => $hargaKamar['minimal_deposit'] ?? 0,
        //                 ]);
        //             }
        //         }

        //         // Simpan ketersediaan kamar
        //         foreach ($this->kamarsData ?? [] as $kamarData) {
        //             if ($kamarData['tipe_kamar_id'] == $tipeKamarData['id']) {
        //                 $tipeKamar->kamars()->create([
        //                     'nama' => $kamarData['nama'],
        //                     'lantai' => $kamarData['lantai'] ?? null,
        //                     'ukuran' => $kamarData['ukuran'] ?? null,
        //                     'terisi' => $kamarData['terisi'] ?? false,
        //                 ]);
        //             }
        //         }
        //     }
        // }

        // Simpan tipe kamar dan buat mapping index ke UUID
        $tipeKamarMap = []; // index => UUID tipe kamar
        if (!empty($data['tipe_kamars'])) {
            foreach ($data['tipe_kamars'] as $index => $tipeKamarData) {
                $tipeKamar = $record->tipeKamars()->create([
                    'nama_tipe' => $tipeKamarData['nama_tipe'],
                ]);
                $tipeKamarMap[$index] = $tipeKamar->id;

                // Simpan fasilitas per tipe kamar dari $this->fasilitasKamarsData jika ada
                foreach ($this->fasilitasKamarsData ?? [] as $fasilitasPerTipe) {
                    if ($fasilitasPerTipe['tipe_kamar_id'] == $tipeKamarData['id']) {
                        $allFasilitas = array_merge(
                            $fasilitasPerTipe['fasilitas_umum'] ?? [],
                            $fasilitasPerTipe['fasilitas_kamar'] ?? [],
                            $fasilitasPerTipe['fasilitas_kamar_mandi'] ?? [],
                            $fasilitasPerTipe['fasilitas_parkir'] ?? [],
                        );
                        $tipeKamar->fasilitas()->sync($allFasilitas);
                    }
                }
            }
        }

        // Simpan harga kamar
        if (!empty($data['harga_per_tipe'])) {
            foreach ($data['harga_per_tipe'] as $hargaData) {
                $tipeIndex = $hargaData['tipe_kamar_index'];
                if (isset($tipeKamarMap[$tipeIndex])) {
                    $tipeKamarId = $tipeKamarMap[$tipeIndex];
                    $tipeKamar = $record->tipeKamars()->find($tipeKamarId);
                    if ($tipeKamar) {
                        $tipeKamar->hargaKamars()->create([
                            'harga_perbulan' => $hargaData['harga_bulanan'],
                            'harga_mingguan' => $hargaData['harga_mingguan'] ?? null,
                            'harga_harian' => $hargaData['harga_harian'] ?? null,
                            'minimal_deposit' => 0, // jika ada dari form, bisa ditambahkan di sini
                        ]);
                    }
                }
            }
        } else if (!empty($data['harga_bulanan'])) {
            // Jika multi_tipe = false, simpan harga langsung ke tipe kamar default
            // Asumsi ada tipe kamar default 1
            $tipeKamar = $record->tipeKamars()->first();
            if ($tipeKamar) {
                $tipeKamar->hargaKamars()->create([
                    'harga_perbulan' => $data['harga_bulanan'],
                    'harga_mingguan' => $data['harga_mingguan'] ?? null,
                    'harga_harian' => $data['harga_harian'] ?? null,
                    'minimal_deposit' => 0,
                ]);
            }
        }

        // Simpan kamar dan ketersediaannya
        if (!empty($data['kamars'])) {
            foreach ($data['kamars'] as $kamarData) {
                $tipeIndex = $kamarData['tipe_kamar_index'];
                if (isset($tipeKamarMap[$tipeIndex])) {
                    $tipeKamarId = $tipeKamarMap[$tipeIndex];

                    // Simpan ke tabel kamars
                    $kamar = $record->kamars()->create([
                        'tipe_kamar_id' => $tipeKamarId,
                        'nama'          => $kamarData['nama'],
                        'lantai'        => $kamarData['lantai'] ?? null,
                        'ukuran'        => $kamarData['ukuran'] ?? null,
                    ]);

                    // Simpan ke tabel ketersediaan_kamars
                    $kamar->ketersediaan()->create([
                        'status' => !empty($kamarData['terisi']) ? 'terisi' : 'kosong',
                    ]);
                }
            }
        }
    }
}
