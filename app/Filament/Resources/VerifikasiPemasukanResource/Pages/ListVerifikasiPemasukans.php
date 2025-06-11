<?php

namespace App\Filament\Resources\VerifikasiPemasukanResource\Pages;

use App\Filament\Resources\VerifikasiPemasukanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVerifikasiPemasukans extends ListRecords
{
    protected static string $resource = VerifikasiPemasukanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
