<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use App\Models\Unit;

class QuickActionsWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-actions';
    protected static ?int $sort = 10;
    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $user = Auth::user();
        $isSuperadmin = $user->hasRole('Superadmin');
        $isOwner = $user->hasRole('Owner');

        $actions = [];

        if ($isSuperadmin) {
            $actions = [
                [
                    'label' => 'Kelola Unit',
                    'icon' => 'heroicon-o-home-modern',
                    'url' => '/admin/units',
                    'gradient' => 'from-blue-500 to-blue-600',
                ],
                [
                    'label' => 'Kelola Kamar',
                    'icon' => 'heroicon-o-squares-2x2',
                    'url' => '/admin/kamars',
                    'gradient' => 'from-cyan-500 to-cyan-600',
                ],
                [
                    'label' => 'Data Penghuni',
                    'icon' => 'heroicon-o-users',
                    'url' => '/admin/penghunis',
                    'gradient' => 'from-green-500 to-green-600',
                ],
                [
                    'label' => 'Laporan Keuangan',
                    'icon' => 'heroicon-o-chart-bar',
                    'url' => '/admin/pemasukans',
                    'gradient' => 'from-orange-500 to-orange-600',
                ],
                [
                    'label' => 'Konfirmasi Bayar',
                    'icon' => 'heroicon-o-check-circle',
                    'url' => '/admin/pemasukans?tableFilters[is_konfirmasi][value]=0',
                    'gradient' => 'from-red-500 to-red-600',
                ],
                [
                    'label' => 'Kelola Owners',
                    'icon' => 'heroicon-o-user-group',
                    'url' => '/admin/owners',
                    'gradient' => 'from-purple-500 to-purple-600',
                ],
            ];
        } elseif ($isOwner) {
            $actions = [
                [
                    'label' => 'Tambah Unit',
                    'icon' => 'heroicon-o-plus-circle',
                    'url' => '/admin/units/create',
                    'gradient' => 'from-blue-500 to-blue-600',
                ],
                [
                    'label' => 'Kelola Kamar',
                    'icon' => 'heroicon-o-squares-2x2',
                    'url' => '/admin/kamars',
                    'gradient' => 'from-cyan-500 to-cyan-600',
                ],
                [
                    'label' => 'Data Penghuni',
                    'icon' => 'heroicon-o-users',
                    'url' => '/admin/penghunis',
                    'gradient' => 'from-green-500 to-green-600',
                ],
                [
                    'label' => 'Laporan Keuangan',
                    'icon' => 'heroicon-o-chart-bar',
                    'url' => '/admin/pemasukans',
                    'gradient' => 'from-orange-500 to-orange-600',
                ],
                [
                    'label' => 'Konfirmasi Bayar',
                    'icon' => 'heroicon-o-check-circle',
                    'url' => '/admin/pemasukans?tableFilters[is_konfirmasi][value]=0',
                    'gradient' => 'from-red-500 to-red-600',
                ],
                [
                    'label' => 'Pengaturan',
                    'icon' => 'heroicon-o-cog-6-tooth',
                    'url' => '/admin/owners/' . Auth::user()->owner?->id . '/edit',
                    'gradient' => 'from-gray-500 to-gray-600',
                ],
            ];
        }

        return [
            'actions' => $actions,
            'isOwner' => $isOwner,
        ];
    }
}
