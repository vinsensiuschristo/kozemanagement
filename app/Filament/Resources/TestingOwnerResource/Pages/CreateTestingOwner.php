<?php

namespace App\Filament\Resources\TestingOwnerResource\Pages;

use App\Filament\Resources\TestingOwnerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTestingOwner extends CreateRecord
{
    protected static string $resource = TestingOwnerResource::class;
}
