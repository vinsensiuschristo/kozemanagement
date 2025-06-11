<?php

namespace App\Filament\Widgets;

use App\Models\Unit;
use App\Models\Kamar;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Auth;

class OwnerDashboardOverview extends BaseWidget
{
    protected static ?int $sort = 0;
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $user = Auth::user();

        if (!$user->hasRole('Owner')) {
            return [];
        }

        $ownerId = $user->owner?->id;
        $units = Unit::where('id_owner', $ownerId)->get();
        $unitIds = $units->pluck('id');

        // Total units
        $totalUnits = $units->count();

        // Total kamar
        $totalKamars = Kamar::whereIn('unit_id', $unitIds)->count();

        // Kamar terisi
        $kamarTerisi = Kamar::whereIn('unit_id', $unitIds)
            ->whereHas('ketersediaan', function ($query) {
                $query->where('status', 'terisi');
            })->count();

        // Revenue bulan ini
        $revenueBulanIni = Pemasukan::whereIn('unit_id', $unitIds)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('jumlah');

        // Pengeluaran bulan ini
        $pengeluaranBulanIni = Pengeluaran::whereIn('unit_id', $unitIds)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('jumlah');

        // Net profit
        $netProfit = $revenueBulanIni - $pengeluaranBulanIni;

        // Tingkat hunian
        $tingkatHunian = $totalKamars > 0 ? round(($kamarTerisi / $totalKamars) * 100, 1) : 0;

        return [
            Stat::make('Total Unit Kos', $totalUnits)
                ->description($totalKamars . ' kamar total')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary')
                ->chart([7, 12, 8, 15, 18, 22, $totalUnits]),

            Stat::make('Tingkat Hunian', $tingkatHunian . '%')
                ->description($kamarTerisi . ' dari ' . $totalKamars . ' kamar')
                ->descriptionIcon($tingkatHunian >= 80 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($tingkatHunian >= 80 ? 'success' : ($tingkatHunian >= 60 ? 'warning' : 'danger'))
                ->chart([65, 72, 68, 75, 78, 82, $tingkatHunian]),

            Stat::make($netProfit >= 0 ? 'Net Profit' : 'Net Loss', 'Rp ' . Number::format(abs($netProfit), locale: 'id'))
                ->description('Bulan ' . now()->format('F Y'))
                ->descriptionIcon($netProfit >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($netProfit >= 0 ? 'success' : 'danger')
                ->chart($netProfit >= 0 ? [1200000, 1350000, 1180000, 1420000, 1380000, 1560000] : [1560000, 1380000, 1420000, 1180000, 1350000, 1200000]),
        ];
    }
}
