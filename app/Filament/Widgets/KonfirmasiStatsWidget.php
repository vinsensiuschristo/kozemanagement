<?php

namespace App\Filament\Widgets;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Unit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Auth;

class KonfirmasiStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $user = Auth::user();
        $isOwner = $user->hasRole('Owner');

        // Filter berdasarkan role
        $unitIds = $isOwner ?
            Unit::where('id_owner', $user->owner?->id)->pluck('id') :
            Unit::pluck('id');

        // Pemasukan belum dikonfirmasi
        $pemasukanBelumKonfirmasi = Pemasukan::whereIn('unit_id', $unitIds)
            ->where('is_konfirmasi', false)->count();
        $totalPemasukanBelumKonfirmasi = Pemasukan::whereIn('unit_id', $unitIds)
            ->where('is_konfirmasi', false)->sum('jumlah');

        // Pengeluaran belum dikonfirmasi
        $pengeluaranBelumKonfirmasi = Pengeluaran::whereIn('unit_id', $unitIds)
            ->where('is_konfirmasi', false)->count();
        $totalPengeluaranBelumKonfirmasi = Pengeluaran::whereIn('unit_id', $unitIds)
            ->where('is_konfirmasi', false)->sum('jumlah');

        // Total pemasukan dan pengeluaran bulan ini
        $pemasukanBulanIni = Pemasukan::whereIn('unit_id', $unitIds)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('jumlah');

        $pengeluaranBulanIni = Pengeluaran::whereIn('unit_id', $unitIds)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('jumlah');

        $netIncome = $pemasukanBulanIni - $pengeluaranBulanIni;

        // Income Widget
        return [
            // Stat::make('💰 Pemasukan Pending', $pemasukanBelumKonfirmasi)
            //     ->description('Rp ' . Number::format($totalPemasukanBelumKonfirmasi, locale: 'id'))
            //     ->descriptionIcon('heroicon-m-clock')
            //     ->color($pemasukanBelumKonfirmasi > 0 ? 'warning' : 'success')
            //     ->extraAttributes([
            //         'class' => 'bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 border-l-4 border-yellow-400 hover:shadow-lg transition-all duration-300 transform hover:scale-105',
            //     ]),

            // Stat::make('💸 Pengeluaran Pending', $pengeluaranBelumKonfirmasi)
            //     ->description('Rp ' . Number::format($totalPengeluaranBelumKonfirmasi, locale: 'id'))
            //     ->descriptionIcon('heroicon-m-clock')
            //     ->color($pengeluaranBelumKonfirmasi > 0 ? 'warning' : 'success')
            //     ->extraAttributes([
            //         'class' => 'bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border-l-4 border-red-400 hover:shadow-lg transition-all duration-300 transform hover:scale-105',
            //     ]),

            // Stat::make('📈 Pemasukan Bulan Ini', 'Rp ' . Number::format($pemasukanBulanIni, locale: 'id'))
            //     ->description('Total pemasukan ' . now()->format('F Y'))
            //     ->descriptionIcon('heroicon-m-arrow-trending-up')
            //     ->color('success')
            //     ->extraAttributes([
            //         'class' => 'bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border-l-4 border-green-400 hover:shadow-lg transition-all duration-300 transform hover:scale-105',
            //     ]),

            // Stat::make($netIncome >= 0 ? '🚀 Profit' : '📉 Loss', 'Rp ' . Number::format(abs($netIncome), locale: 'id'))
            //     ->description($netIncome >= 0 ? 'Keuntungan bersih' : 'Kerugian bulan ini')
            //     ->descriptionIcon($netIncome >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            //     ->color($netIncome >= 0 ? 'success' : 'danger')
            //     ->extraAttributes([
            //         'class' => $netIncome >= 0 ?
            //             'bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 border-l-4 border-emerald-400 hover:shadow-lg transition-all duration-300 transform hover:scale-105' :
            //             'bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border-l-4 border-red-400 hover:shadow-lg transition-all duration-300 transform hover:scale-105',
            //     ]),
        ];
    }
}
