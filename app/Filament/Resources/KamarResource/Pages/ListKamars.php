<?php

namespace App\Filament\Resources\KamarResource\Pages;

use App\Filament\Resources\KamarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListKamars extends ListRecords
{
    protected static string $resource = KamarResource::class;

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

            'Kosong' => Tab::make()
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->whereHas('ketersediaan', fn($q) => $q->where('status', 'kosong'))
                ),

            'Terisi' => Tab::make()
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->whereHas('ketersediaan', fn($q) => $q->where('status', 'terisi'))
                ),

            'Booked' => Tab::make()
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->whereHas('ketersediaan', fn($q) => $q->where('status', 'booked'))
                ),
        ];
    }
}
