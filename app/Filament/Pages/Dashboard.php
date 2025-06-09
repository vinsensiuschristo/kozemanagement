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
            \App\Filament\Widgets\StatsOverviewWidget::class,
            \App\Filament\Widgets\KamarStatusChart::class,
            \App\Filament\Widgets\HunianPerTipeChart::class,
            \App\Filament\Widgets\RevenueTrendChart::class,
            \App\Filament\Widgets\TopPerformingUnitsWidget::class,
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
}
