<?php

namespace App\Filament\Resources\PenghuniResource\Pages;

use App\Filament\Resources\PenghuniResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPenghunis extends ListRecords
{
    protected static string $resource = PenghuniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // public function getTabs(): array
    // {
    //     return [
    //         'Semua' => Tab::make(),

    //         'Sedang Mengisi Kamar' => Tab::make()
    //             ->modifyQueryUsing(
    //                 fn(Builder $query) =>
    //                 $query->where('status', 'In') // hanya penghuni yang status-nya In
    //             ),
    //     ];
    // }
}
