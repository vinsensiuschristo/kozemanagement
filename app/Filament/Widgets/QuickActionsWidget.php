<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-actions';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'actions' => [
                [
                    'label' => 'Tambah Kos Baru',
                    'icon' => 'heroicon-o-plus-circle',
                    'url' => '/admin/units/create',
                    'color' => 'primary',
                    'description' => 'Daftarkan unit kos baru',
                ],
                [
                    'label' => 'Kelola Kamar',
                    'icon' => 'heroicon-o-squares-2x2',
                    'url' => '/admin/kamars',
                    'color' => 'info',
                    'description' => 'Atur ketersediaan kamar',
                ],
                [
                    'label' => 'Data Pemilik',
                    'icon' => 'heroicon-o-users',
                    'url' => '/admin/owners',
                    'color' => 'success',
                    'description' => 'Kelola data pemilik kos',
                ],
                [
                    'label' => 'Laporan Hunian',
                    'icon' => 'heroicon-o-chart-bar',
                    'url' => '/admin/reports',
                    'color' => 'warning',
                    'description' => 'Lihat laporan dan analitik',
                ],
            ],
        ];
    }
}
