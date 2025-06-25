<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-building-office-2 class="h-5 w-5 text-gray-500" />
                Manajemen Unit Kos
            </div>
        </x-slot>

        <div class="space-y-6">
            <!-- Search and Select Section -->
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Search Input -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cari Unit Kos
                        </label>
                        <input 
                            type="text" 
                            wire:model.live="searchTerm"
                            placeholder="Cari berdasarkan nama unit atau owner..."
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        />
                    </div>

                    <!-- Unit Selector -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Pilih Unit Kos
                        </label>
                        <select 
                            wire:model.live="selectedUnitId"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">-- Pilih Unit Kos --</option>
                            @foreach($allUnits as $unit)
                                <option value="{{ $unit['value'] }}">{{ $unit['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if($selectedUnit)
                    <div class="mt-4 flex items-center justify-between">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Unit dipilih: <span class="font-semibold">{{ $selectedUnit['nama'] }}</span>
                        </div>
                        <button 
                            wire:click="viewUnitLayout"
                            class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors"
                        >
                            Lihat Layout Lengkap
                        </button>
                    </div>
                @endif
            </div>

            <!-- Selected Unit Display -->
            @if($selectedUnit)
                <x-filament::card class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <div class="space-y-4">
                        <!-- Unit Info dan Room Status dalam satu baris -->
                        <div class="flex items-center justify-between">
                            <!-- Unit Info (Kiri) -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="font-semibold text-gray-900 dark:text-white truncate">
                                        {{ $selectedUnit['nama'] }}
                                    </h3>
                                </div>
                                
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1 truncate">
                                    {{ $selectedUnit['alamat'] }}
                                </p>
                                
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2 truncate">
                                    Owner: {{ $selectedUnit['owner'] }}
                                </p>
                                
                                <div class="flex flex-wrap items-center gap-3 text-xs">
                                    <x-filament::badge color="gray">
                                        Total: {{ $selectedUnit['total_rooms'] }} kamar
                                    </x-filament::badge>
                                    
                                    <x-filament::badge color="success">
                                        Tersedia: {{ $selectedUnit['available_rooms'] }}
                                    </x-filament::badge>
                                    
                                    <x-filament::badge color="danger">
                                        Terisi: {{ $selectedUnit['occupied_rooms'] }}
                                    </x-filament::badge>
                                </div>
                            </div>

                            <!-- Room Status (Kanan) -->
                            <div class="ml-4 flex-shrink-0">
                                <div class="flex flex-wrap gap-2 justify-end">
                                    @forelse($selectedUnit['rooms'] as $room)
                                        <x-filament::badge 
                                            :color="$room['status'] === 'terisi' ? 'danger' : 'success'"
                                            size="sm"
                                            class="px-3 py-2 cursor-pointer hover:scale-105 transition-transform text-xs font-medium"
                                            :tooltip="'Kamar ' . $room['nama'] . ' - ' . ucfirst($room['status']) . ($room['status'] === 'terisi' && $room['penghuni'] ? ' - ' . $room['penghuni']['nama'] : '')"
                                            wire:click.stop="showRoomDetail('{{ $room['nama'] }}')">
                                            {{ $room['nama'] }}
                                        </x-filament::badge>
                                    @empty
                                        <span class="text-xs text-gray-500">Tidak ada kamar</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Legend -->
                        <div class="border-t pt-3">
                            <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                <div class="flex items-center gap-1">
                                    <div class="w-3 h-3 bg-green-500 rounded"></div>
                                    <span>Tersedia</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <div class="w-3 h-3 bg-red-500 rounded"></div>
                                    <span>Terisi</span>
                                </div>
                                <div class="text-gray-400">
                                    Klik nama kamar untuk detail penghuni
                                </div>
                            </div>
                        </div>
                    </div>
                </x-filament::card>
            @else
                <x-filament::card>
                    <div class="text-center py-8">
                        <x-heroicon-o-magnifying-glass class="mx-auto h-12 w-12 text-gray-400" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
                            Pilih Unit Kos
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Gunakan pencarian atau dropdown untuk memilih unit kos yang ingin dilihat.
                        </p>
                    </div>
                </x-filament::card>
            @endif
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
                        <x-filament::badge :color="$roomDetail['status'] === 'terisi' ? 'danger' : 'success'">
                            {{ ucfirst($roomDetail['status']) }}
                        </x-filament::badge>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-medium">{{ $roomDetail['tipe'] ?? 'Standard' }}</span>
                    </div>
                </div>

                <!-- Info Kamar -->
                <x-filament::card>
                    <div class="space-y-2">
                        <h4 class="font-semibold text-gray-900 dark:text-white">Informasi Kamar</h4>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="font-medium text-gray-500">Lantai:</span>
                                <span class="text-gray-900 dark:text-white">{{ $roomDetail['lantai'] ?? 1 }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-500">Ukuran:</span>
                                <span class="text-gray-900 dark:text-white">{{ $roomDetail['ukuran'] ?? 'Tidak diketahui' }}</span>
                            </div>
                        </div>
                    </div>
                </x-filament::card>

                @if($roomDetail['status'] === 'terisi' && $roomDetail['penghuni'])
                    <x-filament::card>
                        <div class="space-y-3">
                            <h4 class="font-semibold text-gray-900 dark:text-white">Informasi Penghuni</h4>
                            
                            <div class="grid grid-cols-1 gap-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Kode:</span>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $roomDetail['penghuni']['kode'] ?? '-' }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Nama:</span>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $roomDetail['penghuni']['nama'] }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Telepon:</span>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $roomDetail['penghuni']['telepon'] ?? '-' }}</p>
                                </div>

                                @if($roomDetail['penghuni']['email'])
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Email:</span>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $roomDetail['penghuni']['email'] }}</p>
                                </div>
                                @endif
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Check-in:</span>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $roomDetail['penghuni']['checkin'] }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Deposit:</span>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $roomDetail['penghuni']['deposit'] }}</p>
                                </div>
                            </div>
                        </div>
                    </x-filament::card>
                @else
                    <div class="text-center py-4">
                        <x-heroicon-o-user class="mx-auto h-12 w-12 text-gray-400" />
                        <p class="mt-2 text-sm text-gray-500">Kamar ini sedang kosong</p>
                    </div>
                @endif
            </div>
        @endif
    </x-filament::modal>
</x-filament-widgets::widget>
