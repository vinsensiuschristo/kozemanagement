<x-filament-panels::page>
    @if ($role === 'Superadmin')
        {{-- Dashboard Superadmin Elegan --}}
        <div class="space-y-6">
            {{-- Header Section --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Welcome back, Superadmin</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        {{ now()->format('l, d F Y') }} • {{ now()->format('H:i') }} WIB
                    </div>
                </div>
            </div>

            {{-- Stats Overview - 4 cards in row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @livewire(\App\Filament\Widgets\StatsOverviewWidget::class)
            </div>

            {{-- Konfirmasi Stats - 4 cards in row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @livewire(\App\Filament\Widgets\KonfirmasiStatsWidget::class)
            </div>

            {{-- Charts Section - 2 columns --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    @livewire(\App\Filament\Widgets\PemasukanPengeluaranChart::class)
                </div>
                <div>
                    @livewire(\App\Filament\Widgets\KamarStatusChart::class)
                </div>
            </div>

            {{-- Secondary Charts - 2 columns --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    @livewire(\App\Filament\Widgets\RevenueTrendChart::class)
                </div>
                <div>
                    @livewire(\App\Filament\Widgets\HunianPerTipeChart::class)
                </div>
            </div>

            {{-- Top Performing Units Table --}}
            @livewire(\App\Filament\Widgets\TopPerformingUnitsWidget::class)

            {{-- Unit Performance Table --}}
            @livewire(\App\Filament\Widgets\UnitPerformanceWidget::class)

            {{-- Quick Actions --}}
            @livewire(\App\Filament\Widgets\QuickActionsWidget::class)
        </div>

    @elseif ($role === 'Owner')
        {{-- Dashboard Owner Elegan --}}
        <div class="space-y-6">
            {{-- Header Section --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Welcome back, {{ $ownerData?->nama ?? 'Owner' }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        {{ now()->format('l, d F Y') }} • {{ now()->format('H:i') }} WIB
                    </div>
                </div>
            </div>

            {{-- Owner Stats Overview - 3 cards in row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @livewire(\App\Filament\Widgets\OwnerDashboardOverview::class)
            </div>

            {{-- Konfirmasi Stats - 4 cards in row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @livewire(\App\Filament\Widgets\KonfirmasiStatsWidget::class)
            </div>

            {{-- Charts Section - 2 columns --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    @livewire(\App\Filament\Widgets\PemasukanPengeluaranChart::class)
                </div>
                <div>
                    @livewire(\App\Filament\Widgets\KamarStatusChart::class)
                </div>
            </div>

            {{-- Hunian per Tipe Chart - Single column --}}
            <div>
                @livewire(\App\Filament\Widgets\HunianPerTipeChart::class)
            </div>

            {{-- Unit Performance Table --}}
            @livewire(\App\Filament\Widgets\UnitPerformanceWidget::class)

            {{-- Quick Actions --}}
            @livewire(\App\Filament\Widgets\QuickActionsWidget::class)
        </div>

    @else
        {{-- Dashboard Default --}}
        <div class="space-y-6">
            <div class="text-center py-12">
                <div class="text-6xl mb-4">🔒</div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Akses Terbatas</h2>
                <p class="text-gray-600 dark:text-gray-300">
                    Anda belum memiliki role yang sesuai untuk mengakses dashboard ini.
                </p>
            </div>
        </div>
    @endif
</x-filament-panels::page>
