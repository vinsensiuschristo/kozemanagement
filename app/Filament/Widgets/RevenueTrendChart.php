<?php

namespace App\Filament\Widgets;

use App\Models\LogPenghuni;
use App\Models\Unit;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RevenueTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Trend Jumlah Penghuni';
    protected static ?string $description = 'Perkembangan jumlah penghuni dalam 6 bulan terakhir';
    protected static ?int $sort = 4;
    protected static ?string $pollingInterval = '60s';
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = Auth::user();
        return $user && $user->hasRole('Owner');
    }

    protected function getData(): array
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole('Owner')) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        // Ambil unit milik owner
        $unitIds = Unit::where('id_owner', $user->owner?->id)->pluck('id');

        if ($unitIds->isEmpty()) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $months = collect();
        $penghuniData = [];
        $labels = [];

        // Ambil data 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months->push($month);
            $labels[] = $month->format('M Y');

            // Hitung jumlah penghuni yang checkin pada bulan tersebut
            // dan belum checkout sampai akhir bulan
            $penghuniCount = LogPenghuni::whereHas('kamar', function ($query) use ($unitIds) {
                $query->whereIn('unit_id', $unitIds);
            })
                ->where('status', 'checkin')
                ->whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->distinct('penghuni_id')
                ->count();

            $penghuniData[] = $penghuniCount;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Penghuni',
                    'data' => $penghuniData,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderWidth' => 3,
                    'tension' => 0.4,
                    'fill' => true,
                    'pointBackgroundColor' => 'rgb(59, 130, 246)',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 6,
                    'pointHoverRadius' => 8,
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
                        'label' => 'function(context) { return "Penghuni: " + context.parsed.y + " orang"; }',
                    ],
                ],
                'datalabels' => [
                    'display' => true,
                    'color' => 'rgb(59, 130, 246)',
                    'font' => [
                        'weight' => 'bold',
                        'size' => 14,
                    ],
                    'anchor' => 'end',
                    'align' => 'top',
                    'offset' => 8,
                    'formatter' => 'function(value) { return value; }',
                    'backgroundColor' => 'rgba(255, 255, 255, 0.9)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderRadius' => 4,
                    'borderWidth' => 1,
                    'padding' => 4,
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
                        'callback' => 'function(value) { return value + " orang"; }',
                        'font' => [
                            'size' => 11,
                        ],
                        'stepSize' => 1,
                    ],
                ],
            ],
            'animation' => [
                'duration' => 1000,
                'easing' => 'easeOutQuart',
            ],
            'layout' => [
                'padding' => [
                    'top' => 30,
                ],
            ],
        ];
    }
}
