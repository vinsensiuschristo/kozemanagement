<?php

namespace App\Filament\Widgets;

use App\Models\Penghuni;
use App\Models\LogPenghuni;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PenghuniStatsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected static ?string $pollingInterval = '60s';

    public static function canView(): bool
    {
        $user = Auth::user();
        return $user && !$user->hasRole('Owner');
    }

    protected function getStats(): array
    {
        // Total penghuni
        $totalPenghuni = Penghuni::count();

        // Penghuni aktif (yang sedang menghuni berdasarkan log terakhir)
        $penghuniAktif = LogPenghuni::where('status', 'checkin')
            ->whereDoesntHave('penghuni.logs', function ($query) {
                $query->where('status', 'checkout')
                    ->where('tanggal', '>', now()->subDays(30));
            })
            ->distinct('penghuni_id')
            ->count();

        // Check-in bulan ini
        $checkinBulanIni = LogPenghuni::where('status', 'checkin')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        // Check-out bulan ini
        $checkoutBulanIni = LogPenghuni::where('status', 'checkout')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        return [
            Stat::make('Total Penghuni', $totalPenghuni)
                ->description('Penghuni terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Penghuni Aktif', $penghuniAktif)
                ->description('Sedang menghuni')
                ->descriptionIcon('heroicon-m-home')
                ->color('success'),

            Stat::make('Check-in Bulan Ini', $checkinBulanIni)
                ->description('Penghuni baru')
                ->descriptionIcon('heroicon-m-arrow-right-on-rectangle')
                ->color('info'),

            Stat::make('Check-out Bulan Ini', $checkoutBulanIni)
                ->description('Penghuni keluar')
                ->descriptionIcon('heroicon-m-arrow-left-on-rectangle')
                ->color('warning'),
        ];
    }
}
