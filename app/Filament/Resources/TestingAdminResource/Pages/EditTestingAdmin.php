<?php

namespace App\Filament\Resources\TestingAdminResource\Pages;

use App\Filament\Resources\TestingAdminResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestingAdmin extends EditRecord
{
    protected static string $resource = TestingAdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
