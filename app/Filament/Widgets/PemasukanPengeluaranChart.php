<?php

namespace App\Filament\Widgets;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Unit;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PemasukanPengeluaranChart extends ChartWidget
{
    protected static ?string $heading = 'Analisis Keuangan';
    protected static ?string $description = 'Perbandingan pemasukan dan pengeluaran';
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = '120s';
    protected int | string | array $columnSpan = 'full';

    public ?string $filter = '6_months';

    public static function canView(): bool
    {
        $user = Auth::user();
        return !$user->hasRole('Owner');
    }

    protected function getFilters(): ?array
    {
        return [
            '3_months' => '3 Bulan',
            '6_months' => '6 Bulan',
            '1_year' => '1 Tahun',
        ];
    }

    protected function getData(): array
    {
        $user = Auth::user();
        $isOwner = $user->hasRole('Owner');

        $unitIds = $isOwner ?
            Unit::where('id_owner', $user->owner?->id)->pluck('id') :
            Unit::pluck('id');

        $months = $this->getMonthsRange();
        $pemasukanData = [];
        $pengeluaranData = [];
        $labels = [];

        foreach ($months as $month) {
            $labels[] = $month->format('M Y');

            $pemasukan = Pemasukan::whereIn('unit_id', $unitIds)
                ->whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->sum('jumlah');

            $pengeluaran = Pengeluaran::whereIn('unit_id', $unitIds)
                ->whereYear('tanggal', $month->year)
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
                    'backgroundColor' => 'rgba(16, 185, 129, 0.8)',
                    'borderColor' => 'rgb(16, 185, 129)',
                    'borderWidth' => 2,
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => $pengeluaranData,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.8)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'borderWidth' => 2,
                    'borderRadius' => 4,
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
            '3_months' => 3,
            '6_months' => 6,
            '1_year' => 12,
            default => 6,
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
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 20,
                    ],
                ],
                'tooltip' => [
                    'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                    'titleColor' => 'white',
                    'bodyColor' => 'white',
                    'callbacks' => [
                        'label' => 'function(context) { return context.dataset.label + ": Rp " + context.parsed.y.toLocaleString("id-ID"); }',
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.1)',
                    ],
                    'ticks' => [
                        'callback' => 'function(value) { return "Rp " + value.toLocaleString("id-ID"); }',
                    ],
                ],
            ],
        ];
    }
}
