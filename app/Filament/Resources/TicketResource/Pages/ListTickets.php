<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(fn() => !Auth::user()->hasRole(['Superadmin', 'Admin'])),
        ];
    }

    public function getTabs(): array
    {
        if (Auth::user()->hasRole(['Superadmin', 'Admin'])) {
            return [
                'semua' => Tab::make('Semua')
                    ->badge(fn() => $this->getModel()::count()),

                'baru' => Tab::make('Baru')
                    ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Baru'))
                    ->badge(fn() => $this->getModel()::where('status', 'Baru')->count())
                    ->badgeColor('danger'),

                'diproses' => Tab::make('Diproses')
                    ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Diproses'))
                    ->badge(fn() => $this->getModel()::where('status', 'Diproses')->count())
                    ->badgeColor('warning'),

                'selesai' => Tab::make('Selesai')
                    ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Selesai'))
                    ->badge(fn() => $this->getModel()::where('status', 'Selesai')->count())
                    ->badgeColor('success'),

                'ditolak' => Tab::make('Ditolak')
                    ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Ditolak'))
                    ->badge(fn() => $this->getModel()::where('status', 'Ditolak')->count())
                    ->badgeColor('gray'),
            ];
        }

        return [
            'aktif' => Tab::make('Aktif')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereIn('status', ['Baru', 'Diproses']))
                ->badge(fn() => $this->getModel()::where('user_id', Auth::id())->whereIn('status', ['Baru', 'Diproses'])->count())
                ->badgeColor('warning'),

            'selesai' => Tab::make('Selesai')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'Selesai'))
                ->badge(fn() => $this->getModel()::where('user_id', Auth::id())->where('status', 'Selesai')->count())
                ->badgeColor('success'),
        ];
    }
}
