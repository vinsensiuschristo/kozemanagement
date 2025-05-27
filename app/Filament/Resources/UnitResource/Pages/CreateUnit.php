<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use App\Models\Unit;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUnit extends CreateRecord
{
    protected static string $resource = UnitResource::class;

    /**
     * Override untuk menyimpan Unit beserta foto-fotonya
     */
    protected function handleRecordCreation(array $data): Unit
    {
        // Ambil array path foto dari form
        $fotoFiles = $data['foto_unit'] ?? [];

        // Simpan data Unit (kecuali 'foto_unit')
        $unit = Unit::create(collect($data)->except('foto_unit')->toArray());

        // Simpan foto ke relasi fotoUnits
        foreach ($fotoFiles as $filePath) {
            $unit->fotoUnits()->create([
                'path' => $filePath,
            ]);
        }

        return $unit;
    }
}
