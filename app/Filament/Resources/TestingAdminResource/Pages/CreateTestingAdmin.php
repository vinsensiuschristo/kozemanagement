<?php

namespace App\Filament\Resources\TestingAdminResource\Pages;

use App\Filament\Resources\TestingAdminResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTestingAdmin extends CreateRecord
{
    protected static string $resource = TestingAdminResource::class;
}
