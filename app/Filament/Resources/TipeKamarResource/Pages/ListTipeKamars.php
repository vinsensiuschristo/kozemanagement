<?php

namespace App\Filament\Resources\TipeKamarResource\Pages;

use App\Filament\Resources\TipeKamarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTipeKamars extends ListRecords
{
    protected static string $resource = TipeKamarResource::class;

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
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->whereHas('unit', fn($q) => $q->where('status', true))
                ),

            'Tidak Aktif' => Tab::make()
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->whereHas('unit', fn($q) => $q->where('status', false))
                ),
        ];
    }
}
