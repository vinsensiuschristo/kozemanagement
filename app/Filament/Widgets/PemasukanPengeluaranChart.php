<?php

namespace App\Filament\Widgets;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Unit;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class PemasukanPengeluaranChart extends ChartWidget
{
    protected static ?string $heading = 'Pemasukan vs Pengeluaran';
    protected static ?string $description = 'Perbandingan pemasukan dan pengeluaran per periode';
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = '60s';

    public ?string $filter = '3_months';

    protected function getFilters(): ?array
    {
        return [
            '1_month' => '1 Bulan',
            '3_months' => '3 Bulan',
            '6_months' => '6 Bulan',
            '1_year' => '1 Tahun',
        ];
    }

    protected function getData(): array
    {
        $months = $this->getMonthsRange();
        $pemasukanData = [];
        $pengeluaranData = [];
        $labels = [];

        foreach ($months as $month) {
            $labels[] = $month->format('M Y');

            $pemasukan = Pemasukan::whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->sum('jumlah');

            $pengeluaran = Pengeluaran::whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->sum('jumlah');

            $pemasukanData[] = $pemasukan;
            $pengeluaranData[] = $pengeluaran;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => $pemasukanData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.8)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => $pengeluaranData,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.8)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    private function getMonthsRange()
    {
        $months = collect();
        $range = match ($this->filter) {
            '1_month' => 1,
            '3_months' => 3,
            '6_months' => 6,
            '1_year' => 12,
            default => 3,
        };

        for ($i = $range - 1; $i >= 0; $i--) {
            $months->push(Carbon::now()->subMonths($i));
        }

        return $months;
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "Rp " + value.toLocaleString("id-ID"); }',
                    ],
                ],
            ],
            'plugins' => [
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return context.dataset.label + ": Rp " + context.parsed.y.toLocaleString("id-ID"); }',
                    ],
                ],
            ],
        ];
    }
}
