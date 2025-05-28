<?php

namespace App\Filament\Resources\JenisKamarResource\Pages;

use App\Filament\Resources\JenisKamarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJenisKamars extends ListRecords
{
    protected static string $resource = JenisKamarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
