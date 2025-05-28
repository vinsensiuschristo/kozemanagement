<?php

namespace App\Filament\Resources\JenisKamarResource\Pages;

use App\Filament\Resources\JenisKamarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisKamar extends EditRecord
{
    protected static string $resource = JenisKamarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
