<x-filament-panels::page>
    @php
        $user = auth()->user();
        $isOwner = $user && $user->hasRole('Owner');
        $isAdmin = $user && ($user->hasRole('Superadmin') || $user->hasRole('Admin'));
    @endphp

    <div class="space-y-6">
        @if($isOwner)
            {{-- Owner Dashboard --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Owner</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Welcome back, {{ $ownerData?->nama ?? auth()->user()->name ?? 'Owner' }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        {{ now()->format('l, d F Y') }} • {{ now()->format('H:i') }} WIB
                    </div>
                </div>
            </div>

            {{-- Owner Dashboard Overview --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @livewire('app.filament.widgets.owner-dashboard-overview')
            </div>

            {{-- Owner Units Widget --}}
            @livewire('app.filament.widgets.owner-units-widget')

            {{-- Trend Jumlah Penghuni - Full Width --}}
            <div class="w-full">
                @livewire('app.filament.widgets.revenue-trend-chart')
            </div>

            {{-- Konfirmasi Stats Widget --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @livewire('app.filament.widgets.konfirmasi-stats-widget')
            </div>

            {{-- Owner Quick Actions Widget --}}
            @livewire('app.filament.widgets.owner-quick-actions-widget')

        @elseif($isAdmin)
            {{-- Admin/Superadmin Dashboard --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Admin</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Welcome back, {{ auth()->user()->name ?? 'Admin' }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        {{ now()->format('l, d F Y') }} • {{ now()->format('H:i') }} WIB
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @livewire('app.filament.widgets.stats-overview-widget')
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    @livewire('app.filament.widgets.pemasukan-pengeluaran-chart')
                </div>
                <div>
                    @livewire('app.filament.widgets.kamar-status-chart')
                </div>
            </div>

            <div>
                @livewire('app.filament.widgets.hunian-per-tipe-chart')
            </div>

            @livewire('app.filament.widgets.top-performing-units-widget')

            @livewire('app.filament.widgets.unit-performance-widget')

            @livewire('app.filament.widgets.quick-actions-widget')

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
