<?php

namespace App\Filament\Widgets;

use App\Models\TipeKamar;
use App\Models\Unit;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class HunianPerTipeChart extends ChartWidget
{
    protected static ?string $heading = 'Tingkat Hunian per Tipe Kamar';
    protected static ?string $description = 'Persentase hunian setiap tipe kamar';
    protected static ?int $sort = 4;
    protected static ?string $pollingInterval = '60s';
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
            $tipeKamars = TipeKamar::with(['ketersediaanKamars'])
                ->whereIn('unit_id', $unitIds)
                ->get();
        } else {
            $tipeKamars = TipeKamar::with(['ketersediaanKamars'])->get();
        }

        $labels = [];
        $dataHunian = [];
        $backgroundColors = [];

        foreach ($tipeKamars as $tipe) {
            $totalKamar = $tipe->ketersediaanKamars->count();
            $kamarTerisi = $tipe->ketersediaanKamars->where('status', 'terisi')->count();

            if ($totalKamar > 0) {
                $persentaseHunian = round(($kamarTerisi / $totalKamar) * 100, 1);
                $labels[] = $tipe->nama_tipe . " ({$kamarTerisi}/{$totalKamar})";
                $dataHunian[] = $persentaseHunian;

                // Warna berdasarkan tingkat hunian
                if ($persentaseHunian >= 80) {
                    $backgroundColors[] = '#10b981'; // hijau - bagus
                } elseif ($persentaseHunian >= 50) {
                    $backgroundColors[] = '#f59e0b'; // kuning - sedang
                } else {
                    $backgroundColors[] = '#ef4444'; // merah - rendah
                }
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tingkat Hunian (%)',
                    'data' => $dataHunian,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $backgroundColors,
                    'borderWidth' => 1,
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
            'indexAxis' => 'y', // Horizontal bar chart
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return context.parsed.x + "%"; }',
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'max' => 100,
                    'ticks' => [
                        'callback' => 'function(value) { return value + "%"; }',
                    ],
                ],
                'y' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}
