<?php

namespace App\Filament\Resources\LogPenghuniResource\Pages;

use App\Filament\Resources\LogPenghuniResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLogPenghuni extends EditRecord
{
    protected static string $resource = LogPenghuniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
