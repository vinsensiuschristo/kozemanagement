<?php

namespace App\Filament\Resources\TestingUserResource\Pages;

use App\Filament\Resources\TestingUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestingUser extends EditRecord
{
    protected static string $resource = TestingUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
