<?php

namespace App\Filament\Resources\TestingSuperAdminResource\Pages;

use App\Filament\Resources\TestingSuperAdminResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTestingSuperAdmin extends CreateRecord
{
    protected static string $resource = TestingSuperAdminResource::class;
}
