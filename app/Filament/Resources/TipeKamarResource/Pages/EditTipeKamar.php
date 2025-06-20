<?php

namespace App\Filament\Resources\TipeKamarResource\Pages;

use App\Filament\Resources\TipeKamarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipeKamar extends EditRecord
{
    protected static string $resource = TipeKamarResource::class;

    protected array $hargaData = [];

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->hargaData = $data['harga'] ?? [];
        unset($data['harga']); // Hapus sebelum simpan ke TipeKamar
        return $data;
    }

    protected function afterSave(): void
    {
        if (!empty($this->hargaData)) {
            $this->record->harga()->updateOrCreate(
                ['tipe_kamar_id' => $this->record->id],
                $this->hargaData
            );
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
