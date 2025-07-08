<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Ticket;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

        public function getTabs(): array
    {
        if (! auth()->user()?->hasRole('Admin')) {
            return [];
        }

        return [
            'baru' => Tab::make('Baru')
                ->icon('heroicon-m-bell-alert')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Baru'))
                ->badge(Ticket::where('status', 'Baru')->count())
                ->badgeColor('danger'),

            'diproses' => Tab::make('Diproses')
                ->icon('heroicon-m-arrow-path')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Diproses'))
                ->badge(Ticket::where('status', 'Diproses')->count())
                ->badgeColor('warning'),

            'selesai' => Tab::make('Selesai')
                ->icon('heroicon-m-check-badge')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Selesai'))
                ->badge(Ticket::where('status', 'Selesai')->count())
                ->badgeColor('success'),

            'ditolak' => Tab::make('Ditolak')
                ->icon('heroicon-m-x-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Ditolak'))
                ->badge(Ticket::where('status', 'Ditolak')->count())
                ->badgeColor('gray'),

            'semua' => Tab::make('Semua Tiket')
                ->icon('heroicon-m-inbox'),
        ];
    }

    public function getDefaultActiveTab(): string
    {
        return 'baru';
    }
}
