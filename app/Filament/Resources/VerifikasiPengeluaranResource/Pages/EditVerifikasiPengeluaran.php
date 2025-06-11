<?php

namespace App\Filament\Resources\VerifikasiPengeluaranResource\Pages;

use App\Filament\Resources\VerifikasiPengeluaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVerifikasiPengeluaran extends EditRecord
{
    protected static string $resource = VerifikasiPengeluaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
