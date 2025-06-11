<?php

namespace App\Filament\Widgets;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class KonfirmasiStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        // Pemasukan belum dikonfirmasi
        $pemasukanBelumKonfirmasi = Pemasukan::where('is_konfirmasi', false)->count();
        $totalPemasukanBelumKonfirmasi = Pemasukan::where('is_konfirmasi', false)->sum('jumlah');

        // Pengeluaran belum dikonfirmasi
        $pengeluaranBelumKonfirmasi = Pengeluaran::where('is_konfirmasi', false)->count();
        $totalPengeluaranBelumKonfirmasi = Pengeluaran::where('is_konfirmasi', false)->sum('jumlah');

        // Total pemasukan dan pengeluaran bulan ini
        $pemasukanBulanIni = Pemasukan::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('jumlah');

        $pengeluaranBulanIni = Pengeluaran::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('jumlah');

        $netIncome = $pemasukanBulanIni - $pengeluaranBulanIni;

        return [
            Stat::make('Pemasukan Belum Konfirmasi', $pemasukanBelumKonfirmasi)
                ->description('Rp ' . Number::format($totalPemasukanBelumKonfirmasi, locale: 'id'))
                ->descriptionIcon('heroicon-m-clock')
                ->color($pemasukanBelumKonfirmasi > 0 ? 'warning' : 'success'),

            Stat::make('Pengeluaran Belum Konfirmasi', $pengeluaranBelumKonfirmasi)
                ->description('Rp ' . Number::format($totalPengeluaranBelumKonfirmasi, locale: 'id'))
                ->descriptionIcon('heroicon-m-clock')
                ->color($pengeluaranBelumKonfirmasi > 0 ? 'warning' : 'success'),

            Stat::make('Pemasukan Bulan Ini', 'Rp ' . Number::format($pemasukanBulanIni, locale: 'id'))
                ->description('Total pemasukan ' . now()->format('F Y'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Net Income Bulan Ini', 'Rp ' . Number::format($netIncome, locale: 'id'))
                ->description($netIncome >= 0 ? 'Profit' : 'Loss')
                ->descriptionIcon($netIncome >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($netIncome >= 0 ? 'success' : 'danger'),
        ];
    }
}
