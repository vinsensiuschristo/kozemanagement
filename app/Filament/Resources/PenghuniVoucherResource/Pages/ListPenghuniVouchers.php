<?php

namespace App\Filament\Resources\PenghuniVoucherResource\Pages;

use App\Filament\Resources\PenghuniVoucherResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenghuniVouchers extends ListRecords
{
    protected static string $resource = PenghuniVoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
