<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-300">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Quick Actions
            </div>
        </x-slot>

        <div class="space-y-4">
            {{-- Aksi Cepat --}}
            <div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    {{-- Tambah Unit --}}
                    <a href="{{ \App\Filament\Resources\UnitResource::getUrl('create') }}" 
                       class="group relative p-3 border rounded-lg hover:shadow-md transition-all duration-200 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-8 h-8 mb-2 flex items-center justify-center rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-500 dark:text-blue-400 group-hover:bg-blue-100 dark:group-hover:bg-blue-800/50 transition-colors">
                                🏢
                            </div>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Tambah Unit</span>
                        </div>
                    </a>

                    {{-- Tambah Owner --}}
                    @if(!$isOwner)
                    <a href="{{ \App\Filament\Resources\OwnerResource::getUrl('create') }}" 
                       class="group relative p-3 border rounded-lg hover:shadow-md transition-all duration-200 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-8 h-8 mb-2 flex items-center justify-center rounded-full bg-purple-50 dark:bg-purple-900/30 text-purple-500 dark:text-purple-400 group-hover:bg-purple-100 dark:group-hover:bg-purple-800/50 transition-colors">
                                👤
                            </div>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Tambah Owner</span>
                        </div>
                    </a>
                    @endif

                    {{-- Catat Pemasukan --}}
                    <a href="{{ \App\Filament\Resources\PemasukanResource::getUrl('create') }}" 
                       class="group relative p-3 border rounded-lg hover:shadow-md transition-all duration-200 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-8 h-8 mb-2 flex items-center justify-center rounded-full bg-green-50 dark:bg-green-900/30 text-green-500 dark:text-green-400 group-hover:bg-green-100 dark:group-hover:bg-green-800/50 transition-colors">
                                💰
                            </div>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Pemasukan</span>
                        </div>
                    </a>

                    {{-- Catat Pengeluaran --}}
                    <a href="{{ \App\Filament\Resources\PengeluaranResource::getUrl('create') }}" 
                       class="group relative p-3 border rounded-lg hover:shadow-md transition-all duration-200 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-8 h-8 mb-2 flex items-center justify-center rounded-full bg-red-50 dark:bg-red-900/30 text-red-500 dark:text-red-400 group-hover:bg-red-100 dark:group-hover:bg-red-800/50 transition-colors">
                                📊
                            </div>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Pengeluaran</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>