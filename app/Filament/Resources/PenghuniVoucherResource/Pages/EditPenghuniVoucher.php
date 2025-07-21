<?php

namespace App\Filament\Resources\PenghuniVoucherResource\Pages;

use App\Filament\Resources\PenghuniVoucherResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenghuniVoucher extends EditRecord
{
    protected static string $resource = PenghuniVoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
