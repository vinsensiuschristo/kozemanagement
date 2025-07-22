<?php

namespace App\Filament\Resources\ValidasiVoucherResource\Pages;

use App\Filament\Resources\ValidasiVoucherResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditValidasiVoucher extends EditRecord
{
    protected static string $resource = ValidasiVoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
