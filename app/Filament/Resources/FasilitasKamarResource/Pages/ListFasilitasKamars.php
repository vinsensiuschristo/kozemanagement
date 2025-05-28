<?php

namespace App\Filament\Resources\FasilitasKamarResource\Pages;

use App\Filament\Resources\FasilitasKamarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFasilitasKamars extends ListRecords
{
    protected static string $resource = FasilitasKamarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
