<?php

namespace App\Filament\Resources\ValidasiVoucherResource\Pages;

use App\Filament\Resources\ValidasiVoucherResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListValidasiVouchers extends ListRecords
{
    protected static string $resource = ValidasiVoucherResource::class;

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua Voucher')
                ->badge(fn() => $this->getModel()::query()->count()),

            'belum_digunakan' => Tab::make('Belum Digunakan')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_used', false))
                ->badge(fn() => $this->getModel()::query()->where('is_used', false)->count())
                ->badgeColor('warning'),

            'sudah_digunakan' => Tab::make('Sudah Digunakan')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_used', true))
                ->badge(fn() => $this->getModel()::query()->where('is_used', true)->count())
                ->badgeColor('success'),
        ];
    }
}
