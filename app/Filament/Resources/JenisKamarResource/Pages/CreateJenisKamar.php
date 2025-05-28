<?php

namespace App\Filament\Resources\JenisKamarResource\Pages;

use App\Filament\Resources\JenisKamarResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\DetailFotoKamar;

class CreateJenisKamar extends CreateRecord
{
    protected static string $resource = JenisKamarResource::class;

    protected ?array $fotoKamarPaths = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->fotoKamarPaths = $data['foto_kamars'] ?? [];
        unset($data['foto_kamars']);
        return $data;
    }

    protected function afterCreate(): void
    {
        if (!empty($this->fotoKamarPaths)) {
            foreach ($this->fotoKamarPaths as $path) {
                DetailFotoKamar::create([
                    'jenis_kamar_id' => $this->record->id,
                    'path' => $path,
                ]);
            }
        }
    }
}
