<?php

namespace App\Filament\Widgets;

use App\Models\KetersediaanKamar;
use App\Models\Unit;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class KamarStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Status Kamar';
    protected static ?string $description = 'Distribusi ketersediaan kamar';
    protected static ?int $sort = 3;
    protected static ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        $user = Auth::user();
        $isOwner = $user->hasRole('Owner');

        if ($isOwner) {
            $unitIds = Unit::where('id_owner', $user->owner?->id)->pluck('id');
            $kosong = KetersediaanKamar::whereHas('kamar', function ($query) use ($unitIds) {
                $query->whereIn('unit_id', $unitIds);
            })->where('status', 'kosong')->count();

            $terisi = KetersediaanKamar::whereHas('kamar', function ($query) use ($unitIds) {
                $query->whereIn('unit_id', $unitIds);
            })->where('status', 'terisi')->count();

            $booked = KetersediaanKamar::whereHas('kamar', function ($query) use ($unitIds) {
                $query->whereIn('unit_id', $unitIds);
            })->where('status', 'booked')->count();
        } else {
            $kosong = KetersediaanKamar::where('status', 'kosong')->count();
            $terisi = KetersediaanKamar::where('status', 'terisi')->count();
            $booked = KetersediaanKamar::where('status', 'booked')->count();
        }

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
                    'borderWidth' => 0,
                    'hoverOffset' => 4,
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
                        'padding' => 15,
                    ],
                ],
                'tooltip' => [
                    'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                    'titleColor' => 'white',
                    'bodyColor' => 'white',
                ],
            ],
            'cutout' => '60%',
        ];
    }
}
