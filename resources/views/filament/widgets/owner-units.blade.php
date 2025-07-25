<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-building-office-2 class="h-5 w-5 text-gray-500" />
                Unit Kos Saya
            </div>
        </x-slot>

        <div class="space-y-4">
            @forelse($units as $unit)
                <x-filament::card class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <div class="space-y-4">
                        <!-- Unit Info dan Room Status -->
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <!-- Unit Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="font-semibold text-gray-900 dark:text-white truncate cursor-pointer hover:text-primary-600 transition-colors text-sm sm:text-base"
                                        onclick="window.location.href='/admin/units/{{ $unit['id'] }}/room-layout'">
                                        {{ $unit['nama'] }}
                                    </h3>
                                </div>
                                
                                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-2 truncate">
                                    {{ $unit['alamat'] }}
                                </p>
                                
                                <div class="flex flex-wrap items-center gap-2 text-xs">
                                    <x-filament::badge color="gray" size="sm">
                                        Total: {{ $unit['total_rooms'] }}
                                    </x-filament::badge>
                                    
                                    <x-filament::badge color="success" size="sm">
                                        Tersedia: {{ $unit['available_rooms'] }}
                                    </x-filament::badge>
                                    
                                    <x-filament::badge color="danger" size="sm">
                                        Terisi: {{ $unit['occupied_rooms'] }}
                                    </x-filament::badge>

                                    @if($unit['booked_rooms'] > 0)
                                        <x-filament::badge color="warning" size="sm">
                                            Booked: {{ $unit['booked_rooms'] }}
                                        </x-filament::badge>
                                    @endif
                                </div>
                            </div>

                            <!-- Room Status Grid -->
                            <div class="flex-shrink-0 w-full lg:w-auto">
                                <div class="grid grid-cols-4 sm:grid-cols-6 lg:flex lg:flex-wrap gap-1 sm:gap-2 justify-end">
                                    @forelse($unit['rooms'] as $room)
                                        <div x-data="{ 
                                            tooltip: false,
                                            tooltipText: 'Kamar {{ $room['nama'] }} - {{ ucfirst($room['status']) }}{{ ($room['status'] === 'terisi' || $room['status'] === 'booked') && $room['penghuni'] ? ' - ' . $room['penghuni']['nama'] . ' (' . ($room['penghuni']['no_telp'] ?? 'No telp') . ')' : '' }}'
                                        }" 
                                        class="relative">
                                            <x-filament::badge 
                                                :color="$room['status'] === 'terisi' ? 'danger' : ($room['status'] === 'booked' ? 'warning' : 'success')"
                                                size="sm"
                                                class="w-full px-2 py-1 cursor-pointer hover:scale-105 transition-transform text-xs font-medium min-w-0 truncate"
                                                @mouseenter="tooltip = true"
                                                @mouseleave="tooltip = false"
                                                wire:click.stop="showRoomDetail('{{ $room['nama'] }}')">
                                                {{ $room['nama'] }}
                                            </x-filament::badge>
                                            
                                            <!-- Custom Tooltip -->
                                            <div x-show="tooltip" 
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 transform scale-95"
                                                 x-transition:enter-end="opacity-100 transform scale-100"
                                                 x-transition:leave="transition ease-in duration-75"
                                                 x-transition:leave-start="opacity-100 transform scale-100"
                                                 x-transition:leave-end="opacity-0 transform scale-95"
                                                 class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-lg whitespace-nowrap z-50 max-w-xs"
                                                 x-text="tooltipText"
                                                 style="display: none;">
                                            </div>
                                        </div>
                                    @empty
                                        <span class="text-xs text-gray-500 dark:text-gray-400 col-span-full text-center">Tidak ada kamar</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Legend -->
                        <div class="border-t pt-3">
                            <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-xs text-gray-500 dark:text-gray-400">
                                <div class="flex items-center gap-1">
                                    <div class="w-3 h-3 bg-green-500 rounded"></div>
                                    <span>Tersedia</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <div class="w-3 h-3 bg-yellow-500 rounded"></div>
                                    <span>Booked</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <div class="w-3 h-3 bg-red-500 rounded"></div>
                                    <span>Terisi</span>
                                </div>
                                <div class="text-gray-400 dark:text-gray-500 hidden sm:block">
                                    Klik kamar untuk detail
                                </div>
                            </div>
                        </div>
                    </div>
                </x-filament::card>
            @empty
                <x-filament::card>
                    <div class="text-center py-8">
                        <x-heroicon-o-building-office-2 class="mx-auto h-12 w-12 text-gray-400" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
                            Tidak ada unit
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Anda belum memiliki unit kos yang terdaftar.
                        </p>
                    </div>
                </x-filament::card>
            @endforelse
        </div>
    </x-filament::section>

    <!-- Modal Detail Kamar -->
    <x-filament::modal id="room-detail-modal" width="md">
        <x-slot name="heading">
            Detail Kamar {{ $selectedRoom }}
        </x-slot>

        @if($roomDetail)
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-filament::badge :color="$roomDetail['status'] === 'terisi' ? 'danger' : ($roomDetail['status'] === 'booked' ? 'warning' : 'success')">
                            {{ ucfirst($roomDetail['status']) }}
                        </x-filament::badge>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $roomDetail['tipe'] ?? 'Standard' }}</span>
                    </div>
                </div>

                <!-- Info Kamar -->
                <x-filament::card>
                    <div class="space-y-2">
                        <h4 class="font-semibold text-gray-900 dark:text-white">Informasi Kamar</h4>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="font-medium text-gray-500 dark:text-gray-400">Lantai:</span>
                                <span class="text-gray-900 dark:text-white">{{ $roomDetail['lantai'] ?? 1 }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-500 dark:text-gray-400">Ukuran:</span>
                                <span class="text-gray-900 dark:text-white">{{ $roomDetail['ukuran'] ?? 'Tidak diketahui' }}</span>
                            </div>
                        </div>
                    </div>
                </x-filament::card>

                @if(($roomDetail['status'] === 'terisi' || $roomDetail['status'] === 'booked') && $roomDetail['penghuni'])
                    <x-filament::card>
                        <div class="space-y-3">
                            <h4 class="font-semibold text-gray-900 dark:text-white">
                                {{ $roomDetail['status'] === 'booked' ? 'Informasi Pemesan' : 'Informasi Penghuni' }}
                            </h4>
                            
                            <div class="grid grid-cols-1 gap-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode:</span>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $roomDetail['penghuni']['kode'] ?? '-' }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama:</span>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $roomDetail['penghuni']['nama'] }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Telepon:</span>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $roomDetail['penghuni']['no_telp'] ?? '-' }}</p>
                                </div>

                                @if($roomDetail['penghuni']['email'] && $roomDetail['penghuni']['email'] !== 'Tidak ada')
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Email:</span>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $roomDetail['penghuni']['email'] }}</p>
                                </div>
                                @endif
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        {{ $roomDetail['status'] === 'booked' ? 'Tanggal Booking:' : 'Check-in:' }}
                                    </span>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $roomDetail['penghuni']['checkin'] }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Deposit:</span>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $roomDetail['penghuni']['deposit'] }}</p>
                                </div>
                            </div>
                        </div>
                    </x-filament::card>
                @else
                    <div class="text-center py-4">
                        <x-heroicon-o-user class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" />
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Kamar ini sedang kosong</p>
                    </div>
                @endif
            </div>
        @endif
    </x-filament::modal>
</x-filament-widgets::widget>
