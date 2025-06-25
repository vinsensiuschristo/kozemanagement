<?php

namespace App\Filament\Widgets;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Unit;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class LaporanKeuanganWidget extends ChartWidget
{
    protected static ?string $heading = 'Laporan Keuangan Bulanan';
    protected static ?int $sort = 5;
    protected static ?string $pollingInterval = '60s';

    public static function canView(): bool
    {
        $user = Auth::user();
        return $user->hasRole('Superadmin') || $user->hasRole('Admin');
    }

    protected function getData(): array
    {
        $user = Auth::user();
        $isOwner = $user->hasRole('Owner');

        // Get last 6 months
        $months = collect(range(5, 0))->map(function ($monthsBack) {
            return now()->subMonths($monthsBack);
        });

        $pemasukan = [];
        $pengeluaran = [];

        foreach ($months as $month) {
            if ($isOwner) {
                $unitIds = Unit::where('id_owner', $user->owner?->id)->pluck('id');

                $pemasukanBulan = Pemasukan::whereIn('unit_id', $unitIds)
                    ->whereMonth('tanggal', $month->month)
                    ->whereYear('tanggal', $month->year)
                    ->sum('jumlah');

                $pengeluaranBulan = Pengeluaran::whereIn('unit_id', $unitIds)
                    ->whereMonth('tanggal', $month->month)
                    ->whereYear('tanggal', $month->year)
                    ->sum('jumlah');
            } else {
                $pemasukanBulan = Pemasukan::whereMonth('tanggal', $month->month)
                    ->whereYear('tanggal', $month->year)
                    ->sum('jumlah');

                $pengeluaranBulan = Pengeluaran::whereMonth('tanggal', $month->month)
                    ->whereYear('tanggal', $month->year)
                    ->sum('jumlah');
            }

            $pemasukan[] = $pemasukanBulan;
            $pengeluaran[] = $pengeluaranBulan;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => $pemasukan,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => $pengeluaran,
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $months->map(fn($month) => $month->format('M Y'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
