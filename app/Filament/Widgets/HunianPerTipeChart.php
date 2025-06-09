<?php

namespace App\Filament\Widgets;

use App\Models\TipeKamar;
use Filament\Widgets\ChartWidget;

class HunianPerTipeChart extends ChartWidget
{
    protected static ?string $heading = 'Hunian per Tipe Kamar';
    protected static ?string $description = 'Perbandingan tingkat hunian setiap tipe';
    protected static ?int $sort = 3;
    protected static ?string $pollingInterval = '60s';

    protected function getData(): array
    {
        $tipeKamars = TipeKamar::with(['ketersediaanKamars'])->get();

        $labels = [];
        $dataKosong = [];
        $dataTerisi = [];
        $dataBooked = [];

        foreach ($tipeKamars as $tipe) {
            $labels[] = $tipe->nama_tipe;
            $dataKosong[] = $tipe->ketersediaanKamars->where('status', 'kosong')->count();
            $dataTerisi[] = $tipe->ketersediaanKamars->where('status', 'terisi')->count();
            $dataBooked[] = $tipe->ketersediaanKamars->where('status', 'booked')->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tersedia',
                    'data' => $dataKosong,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.8)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 2,
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Terisi',
                    'data' => $dataTerisi,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.8)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'borderWidth' => 2,
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Dipesan',
                    'data' => $dataBooked,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.8)',
                    'borderColor' => 'rgb(245, 158, 11)',
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

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'x' => [
                    'stacked' => true,
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
                    'stacked' => true,
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.1)',
                    ],
                    'ticks' => [
                        'font' => [
                            'size' => 11,
                        ],
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'top',
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
                ],
            ],
            'animation' => [
                'duration' => 1000,
                'easing' => 'easeInOutQuart',
            ],
        ];
    }
}
