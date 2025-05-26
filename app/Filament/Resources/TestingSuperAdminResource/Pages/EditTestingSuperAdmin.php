<?php

namespace App\Filament\Resources\TestingSuperAdminResource\Pages;

use App\Filament\Resources\TestingSuperAdminResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestingSuperAdmin extends EditRecord
{
    protected static string $resource = TestingSuperAdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
