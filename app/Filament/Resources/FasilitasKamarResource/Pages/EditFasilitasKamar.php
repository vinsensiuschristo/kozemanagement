<?php

namespace App\Filament\Resources\FasilitasKamarResource\Pages;

use App\Filament\Resources\FasilitasKamarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFasilitasKamar extends EditRecord
{
    protected static string $resource = FasilitasKamarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
