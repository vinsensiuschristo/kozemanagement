<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class RoomStatusWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        $user = Auth::user();
        return $user && !$user->hasRole('Owner');
    }

    protected function getStats(): array
    {
        // Data dummy untuk demo
        return [
            Stat::make('Total Kamar', '45')
                ->description('Semua kamar')
                ->descriptionIcon('heroicon-m-home')
                ->color('primary'),

            Stat::make('Kamar Tersedia', '12')
                ->description('Siap dihuni')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Kamar Terisi', '28')
                ->description('Sedang dihuni')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('danger'),

            Stat::make('Tingkat Hunian', '82.2%')
                ->description('Persentase hunian')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning'),
        ];
    }
}
