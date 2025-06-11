<?php

namespace App\Filament\Widgets;

use App\Models\Pemasukan;
use App\Models\Unit;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RevenueTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Trend Revenue';
    protected static ?string $description = 'Perkembangan revenue dalam 6 bulan terakhir';
    protected static ?int $sort = 4;
    protected static ?string $pollingInterval = '60s';

    protected function getData(): array
    {
        $user = Auth::user();
        $isOwner = $user->hasRole('owner');

        if ($isOwner) {
            $unitIds = Unit::where('id_owner', $user->owner?->id)->pluck('id');
        } else {
            $unitIds = Unit::pluck('id');
        }

        $months = collect();
        $revenueData = [];
        $labels = [];

        // Ambil data 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months->push($month);
            $labels[] = $month->format('M Y');

            $revenue = Pemasukan::whereIn('unit_id', $unitIds)
                ->whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->sum('jumlah');

            $revenueData[] = $revenue;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $revenueData,
                    'borderColor' => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'borderWidth' => 3,
                    'tension' => 0.4,
                    'fill' => true,
                    'pointBackgroundColor' => 'rgb(16, 185, 129)',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'tooltip' => [
                    'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                    'titleColor' => 'white',
                    'bodyColor' => 'white',
                    'callbacks' => [
                        'label' => 'function(context) { return "Revenue: Rp " + context.parsed.y.toLocaleString("id-ID"); }',
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'font' => [
                            'size' => 12,
                        ],
                    ],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.05)',
                    ],
                    'ticks' => [
                        'callback' => 'function(value) { return "Rp " + value.toLocaleString("id-ID"); }',
                        'font' => [
                            'size' => 11,
                        ],
                    ],
                ],
            ],
            'animation' => [
                'duration' => 1000,
                'easing' => 'easeOutQuart',
            ],
        ];
    }
}
