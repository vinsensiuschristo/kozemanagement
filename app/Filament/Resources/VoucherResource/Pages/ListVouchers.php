<?php

namespace App\Filament\Resources\VoucherResource\Pages;

use App\Filament\Resources\VoucherResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVouchers extends ListRecords
{
    protected static string $resource = VoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Voucher Baru')
                ->icon('heroicon-o-plus'),
            Actions\Action::make('assign')
                ->label('Assign Voucher')
                ->icon('heroicon-o-gift')
                ->color('success')
                ->url(fn(): string => static::$resource::getUrl('assign')),
        ];
    }
}
