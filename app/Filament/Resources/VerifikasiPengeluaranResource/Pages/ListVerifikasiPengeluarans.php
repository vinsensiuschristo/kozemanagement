<?php

namespace App\Filament\Resources\VerifikasiPengeluaranResource\Pages;

use App\Filament\Resources\VerifikasiPengeluaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVerifikasiPengeluarans extends ListRecords
{
    protected static string $resource = VerifikasiPengeluaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
