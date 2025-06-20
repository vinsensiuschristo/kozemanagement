<?php

namespace App\Filament\Resources\TipeKamarResource\Pages;

use App\Filament\Resources\TipeKamarResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTipeKamar extends CreateRecord
{
    protected static string $resource = TipeKamarResource::class;

    protected array $hargaData = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->hargaData = $data['harga'] ?? [];
        unset($data['harga']);
        return $data;
    }

    protected function afterCreate(): void
    {
        if (!empty($this->hargaData)) {
            $this->record->harga()->create($this->hargaData);
        }
    }
}
