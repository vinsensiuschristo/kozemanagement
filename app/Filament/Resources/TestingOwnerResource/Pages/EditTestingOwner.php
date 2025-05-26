<?php

namespace App\Filament\Resources\TestingOwnerResource\Pages;

use App\Filament\Resources\TestingOwnerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestingOwner extends EditRecord
{
    protected static string $resource = TestingOwnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
