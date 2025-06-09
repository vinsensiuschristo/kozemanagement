<?php

namespace App\Filament\Widgets;

use App\Models\KetersediaanKamar;
use Filament\Widgets\ChartWidget;

class KamarStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Status Kamar';
    protected static ?string $description = 'Status ketersediaan kamar real-time';
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        $kosong = KetersediaanKamar::where('status', 'kosong')->count();
        $terisi = KetersediaanKamar::where('status', 'terisi')->count();
        $booked = KetersediaanKamar::where('status', 'booked')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Status Kamar',
                    'data' => [$kosong, $terisi, $booked],
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',   // Green untuk kosong
                        'rgb(239, 68, 68)',   // Red untuk terisi
                        'rgb(245, 158, 11)',  // Yellow untuk booked
                    ],
                    'borderColor' => [
                        'rgb(34, 197, 94)',
                        'rgb(239, 68, 68)',
                        'rgb(245, 158, 11)',
                    ],
                    'borderWidth' => 2,
                    'hoverOffset' => 10,
                ],
            ],
            'labels' => ['Tersedia', 'Terisi', 'Dipesan'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 20,
                        'font' => [
                            'size' => 12,
                            'weight' => 'bold',
                        ],
                    ],
                ],
                'tooltip' => [
                    'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                    'titleColor' => 'white',
                    'bodyColor' => 'white',
                    'borderColor' => 'rgba(255, 255, 255, 0.2)',
                    'borderWidth' => 1,
                ],
            ],
            'cutout' => '60%',
            'animation' => [
                'animateRotate' => true,
                'animateScale' => true,
            ],
        ];
    }
}
