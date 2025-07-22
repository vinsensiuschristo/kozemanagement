<?php

namespace App\Filament\Resources\PenggunaanVoucherResource\Pages;

use App\Filament\Resources\PenggunaanVoucherResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenggunaanVoucher extends EditRecord
{
    protected static string $resource = PenggunaanVoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
