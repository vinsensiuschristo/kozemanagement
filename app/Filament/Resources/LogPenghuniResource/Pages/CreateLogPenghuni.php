<?php

namespace App\Filament\Resources\LogPenghuniResource\Pages;

use App\Filament\Resources\LogPenghuniResource;
use App\Models\LogPenghuni;
use App\Models\Pemasukan;
use Filament\Resources\Pages\CreateRecord;

class CreateLogPenghuni extends CreateRecord
{
    protected static string $resource = LogPenghuniResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        // Ambil relasi kamar
        $kamar = $record->kamar;

        // Update status ketersediaan kamar
        $ketersediaan = $kamar->ketersediaan;
        if ($ketersediaan) {
            $ketersediaan->status = $record->status === 'checkin' ? 'terisi' : 'kosong';
            $ketersediaan->save();
        }
    }
}
