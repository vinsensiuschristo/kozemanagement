<?php

namespace App\Filament\Resources\TestingSuperAdminResource\Pages;

use App\Filament\Resources\TestingSuperAdminResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestingSuperAdmins extends ListRecords
{
    protected static string $resource = TestingSuperAdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
