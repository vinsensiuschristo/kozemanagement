<?php

namespace App\Filament\Resources\TestingUserResource\Pages;

use App\Filament\Resources\TestingUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestingUsers extends ListRecords
{
    protected static string $resource = TestingUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
