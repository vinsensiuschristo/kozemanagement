<?php

namespace App\Filament\Resources\TestingOwnerResource\Pages;

use App\Filament\Resources\TestingOwnerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestingOwners extends ListRecords
{
    protected static string $resource = TestingOwnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
