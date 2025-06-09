<?php

namespace App\Filament\Widgets;

use App\Models\HargaKamar;
use App\Models\KetersediaanKamar;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class RevenueTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Trend Revenue Bulanan';
    protected static ?string $description = 'Proyeksi pendapatan 6 bulan terakhir';
    protected static ?int $sort = 6;
    protected static ?string $pollingInterval = '120s';

    protected function getData(): array
    {
        $months = collect();
        $revenues = collect();

        // Generate data untuk 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push($date->format('M Y'));

            // Simulasi revenue berdasarkan kamar terisi
            $kamarTerisi = KetersediaanKamar::where('status', 'terisi')->count();
            $avgHarga = HargaKamar::avg('harga_perbulan') ?? 0;

            // Tambahkan variasi untuk simulasi trend
            $variation = 1 + (sin($i * 0.5) * 0.1); // Variasi ±10%
            $revenue = $kamarTerisi * $avgHarga * $variation;

            $revenues->push($revenue);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (Rp)',
                    'data' => $revenues->toArray(),
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointBackgroundColor' => 'rgb(59, 130, 246)',
                    'pointBorderColor' => 'white',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 6,
                    'pointHoverRadius' => 8,
                ],
            ],
            'labels' => $months->toArray(),
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
            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'font' => [
                            'size' => 11,
                            'weight' => 'bold',
                        ],
                    ],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.1)',
                    ],
                    'ticks' => [
                        'callback' => 'function(value) { return "Rp " + value.toLocaleString("id-ID"); }',
                        'font' => [
                            'size' => 11,
                        ],
                    ],
                ],
            ],
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
            'animation' => [
                'duration' => 2000,
                'easing' => 'easeInOutQuart',
            ],
        ];
    }
}
