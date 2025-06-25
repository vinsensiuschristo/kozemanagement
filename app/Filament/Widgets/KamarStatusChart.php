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
    protected int | string | array $columnSpan = 1;

    public static function canView(): bool
    {
        $user = Auth::user();
        return !$user->hasRole('Owner');
    }

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
                    'label' => 'Jumlah Kamar',
                    'data' => [$kosong, $terisi, $booked],
                    'backgroundColor' => [
                        '#10b981', // emerald-500 untuk tersedia
                        '#ef4444', // red-500 untuk terisi
                        '#f59e0b', // amber-500 untuk booked
                    ],
                    'borderColor' => [
                        '#059669', // emerald-600
                        '#dc2626', // red-600
                        '#d97706', // amber-600
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Tersedia', 'Terisi', 'Dipesan'],
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
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}
