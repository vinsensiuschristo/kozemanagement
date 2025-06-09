<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUnit extends EditRecord
{
    protected static string $resource = UnitResource::class;

    // public function canEdit(): bool
    // {
    //     return auth()->user()->hasAnyRole([
    //         'Superadmin',
    //         'Admin'
    //     ]);
    // }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // Method untuk afterUpdate - DIPERBAIKI
    protected function afterUpdate(): void
    {
        $record = $this->record;
        $data = $this->data;

        // Update Alamat Unit
        if ($record->alamat) {
            $record->alamat->update($data['alamatUnit'] ?? []);
        } else {
            $record->alamat()->create($data['alamatUnit'] ?? []);
        }

        // Update Fasilitas Unit (untuk single type)
        if (!($data['multi_tipe'] ?? false)) {
            $record->fasilitasUnit()->delete();
            foreach ($data['fasilitas_terpilih'] ?? [] as $fasilitasId) {
                $record->fasilitasUnit()->create(['fasilitas_id' => $fasilitasId]);
            }
        }

        // Update Foto Unit - DIPERBAIKI UNTUK EDIT
        $record->fotoUnit()->delete();
        foreach (['foto_kos_depan' => 'depan', 'foto_kos_dalam' => 'dalam', 'foto_kos_jalan' => 'jalan'] as $field => $kategori) {
            foreach ($data[$field] ?? [] as $path) {
                // Bersihkan path dari prefix storage/ jika ada
                $cleanPath = str_replace('storage/', '', $path);

                $record->fotoUnit()->create([
                    'kategori' => $kategori,
                    'path' => $cleanPath,
                ]);
            }
        }

        // Update Tipe Kamar dan relasi lainnya
        $record->tipeKamars()->delete();
        $record->kamars()->delete();

        // Simpan ulang dengan logic yang sama seperti afterCreate
        $this->saveRelatedData($record, $data);

        // REDIRECT KE INDEX SETELAH UPDATE
        $this->redirect(static::getResource()::getUrl('index'));
    }

    // Method helper untuk save data
    protected function saveRelatedData($record, $data): void
    {
        // Simpan tipe kamar dan buat mapping index ke UUID
        $tipeKamarMap = [];

        if (!empty($data['tipe_kamars'])) {
            foreach ($data['tipe_kamars'] as $index => $tipeKamarData) {
                $tipeKamar = $record->tipeKamars()->create([
                    'nama_tipe' => $tipeKamarData['nama_tipe'] ?? '',
                ]);
                $tipeKamarMap[$index] = $tipeKamar->id;
            }
        } else if (!empty($data['nama_tipe_single'])) {
            $tipeKamar = $record->tipeKamars()->create([
                'nama_tipe' => $data['nama_tipe_single'],
            ]);
            $tipeKamarMap[0] = $tipeKamar->id;
        }

        // Simpan harga kamar
        if (!empty($data['harga_per_tipe'])) {
            foreach ($data['harga_per_tipe'] as $hargaData) {
                $tipeIndex = $hargaData['tipe_kamar_index'] ?? 0;
                if (isset($tipeKamarMap[$tipeIndex])) {
                    $tipeKamarId = $tipeKamarMap[$tipeIndex];
                    $tipeKamar = $record->tipeKamars()->find($tipeKamarId);
                    if ($tipeKamar) {
                        $tipeKamar->hargaKamars()->create([
                            'harga_perbulan' => $hargaData['harga_bulanan'] ?? 0,
                            'harga_mingguan' => $hargaData['harga_mingguan'] ?? null,
                            'harga_harian' => $hargaData['harga_harian'] ?? null,
                            'minimal_deposit' => $hargaData['minimal_deposit'] ?? 0,
                        ]);
                    }
                }
            }
        } else if (!empty($data['harga_bulanan'])) {
            $tipeKamar = $record->tipeKamars()->first();
            if ($tipeKamar) {
                $tipeKamar->hargaKamars()->create([
                    'harga_perbulan' => $data['harga_bulanan'],
                    'harga_mingguan' => $data['harga_mingguan'] ?? null,
                    'harga_harian' => $data['harga_harian'] ?? null,
                    'minimal_deposit' => $data['minimal_deposit'] ?? 0,
                ]);
            }
        }

        // Simpan kamar dan ketersediaannya
        if (!empty($data['kamars'])) {
            foreach ($data['kamars'] as $kamarData) {
                $tipeIndex = $kamarData['tipe_kamar_index'] ?? 0;
                if (isset($tipeKamarMap[$tipeIndex])) {
                    $tipeKamarId = $tipeKamarMap[$tipeIndex];

                    $kamar = $record->kamars()->create([
                        'tipe_kamar_id' => $tipeKamarId,
                        'nama'          => $kamarData['nama'] ?? '',
                        'lantai'        => $kamarData['lantai'] ?? null,
                        'ukuran'        => $kamarData['ukuran'] ?? null,
                    ]);

                    $kamar->ketersediaan()->create([
                        'status' => !empty($kamarData['terisi']) ? 'terisi' : 'kosong',
                    ]);
                }
            }
        }
    }
}
