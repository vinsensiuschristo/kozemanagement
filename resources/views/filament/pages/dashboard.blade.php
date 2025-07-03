<x-filament-panels::page>
    @php
        $user = auth()->user();
        $isOwner = $user && $user->hasRole('Owner');
        $isSuperadmin = $user && $user->hasRole('Superadmin');
        $isAdmin = $user && ($user->hasRole('Superadmin') || $user->hasRole('Admin'));
        $isUser = $user && $user->hasRole('User');
    @endphp

    <div class="space-y-6">
        @if($isUser)
            {{-- User Dashboard --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Dashboard Penghuni</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Welcome back, {{ auth()->user()->name ?? 'User' }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                        {{ now()->format('l, d F Y') }} • {{ now()->format('H:i') }} WIB
                    </div>
                </div>
            </div>

            {{-- User Ticketing Widget --}}
            @livewire(\App\Filament\Widgets\UserTicketingWidget::class)

        @elseif($isOwner)
            {{-- Owner Dashboard --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Dashboard Owner</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Welcome back, {{ $ownerData?->nama ?? auth()->user()->name ?? 'Owner' }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                        {{ now()->format('l, d F Y') }} • {{ now()->format('H:i') }} WIB
                    </div>
                </div>
            </div>

            {{-- Owner Dashboard Overview --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @livewire(\App\Filament\Widgets\OwnerDashboardOverview::class)
            </div>

            {{-- Owner Units Widget --}}
            @livewire(\App\Filament\Widgets\OwnerUnitsWidget::class)

            {{-- Konfirmasi Stats Widget --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @livewire(\App\Filament\Widgets\KonfirmasiStatsWidget::class)
            </div>

        @elseif($isAdmin && !$isUser)
            {{-- Admin/Superadmin Dashboard - HANYA untuk Admin dan Superadmin, BUKAN User --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">
                        Dashboard {{ $isSuperadmin ? 'Superadmin' : 'Admin' }}
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Welcome back, {{ auth()->user()->name ?? 'Admin' }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                        {{ now()->format('l, d F Y') }} • {{ now()->format('H:i') }} WIB
                    </div>
                </div>
            </div>

            {{-- Stats Overview - HANYA untuk Admin dan Superadmin --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @livewire(\App\Filament\Widgets\StatsOverviewWidget::class)
            </div>

            {{-- Superadmin Units Widget --}}
            @if($isSuperadmin)
                @livewire(\App\Filament\Widgets\SuperadminUnitsWidget::class)
            @endif

            @livewire(\App\Filament\Widgets\UnitPerformanceWidget::class)

        @else
            {{-- No Role or Unknown Role --}}
            <div class="text-center py-12">
                <div class="text-6xl mb-4">🔒</div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Akses Terbatas</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-4">
                    Anda belum memiliki role yang sesuai untuk mengakses dashboard ini.
                </p>
            </div>
        @endif
    </div>
</x-filament-panels::page>
