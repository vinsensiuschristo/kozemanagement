<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListUnits extends ListRecords
{
    protected static string $resource = UnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Semua' => Tab::make(),

            'Aktif' => Tab::make()
                ->label('Aktif')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', true)),

            'Tidak Aktif' => Tab::make()
                ->label('Tidak Aktif')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', false)),
        ];
    }
}
