<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            // Stats Overview
            \App\Filament\Widgets\KonfirmasiStatsWidget::class,
            \App\Filament\Widgets\PenghuniStatsWidget::class,

            // Charts
            \App\Filament\Widgets\PemasukanPengeluaranChart::class,
            \App\Filament\Widgets\KamarStatusChart::class,
            \App\Filament\Widgets\HunianPerTipeChart::class,

            // Tables
            \App\Filament\Widgets\UnitPerformanceWidget::class,
            \App\Filament\Widgets\PengeluaranPerUnitWidget::class,
            \App\Filament\Widgets\TopPerformingUnitsWidget::class,

            // Quick Actions
            \App\Filament\Widgets\QuickActionsWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 3,
            'xl' => 4,
        ];
    }

    public function getTitle(): string
    {
        return 'Dashboard Koze Management';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola unit kos Anda dengan mudah dan efisien';
    }
}
