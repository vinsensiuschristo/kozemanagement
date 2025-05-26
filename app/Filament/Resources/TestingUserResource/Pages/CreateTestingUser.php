<?php

namespace App\Filament\Resources\TestingUserResource\Pages;

use App\Filament\Resources\TestingUserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTestingUser extends CreateRecord
{
    protected static string $resource = TestingUserResource::class;
}
