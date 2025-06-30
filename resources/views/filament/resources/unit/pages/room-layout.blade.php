<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Simple Header Stats --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->record->nama_cluster }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $this->record->alamat->alamat ?? 'Alamat tidak tersedia' }}
                </p>
            </div>
            
            <div class="flex items-center space-x-8">
                @php $stats = $this->getStatistik(); @endphp
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Total Kamar</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['kosong'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Tersedia</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-rose-600 dark:text-rose-400">{{ $stats['terisi'] }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Terisi</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $stats['tingkat_hunian'] }}%</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Hunian</div>
                </div>
            </div>
        </div>

        {{-- Full Width Table - Only Occupied Rooms --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden w-full shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full table-fixed">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Kamar
                            </th>
                            <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tipe
                            </th>
                            <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="w-1/4 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Penghuni
                            </th>
                            <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Telepon
                            </th>
                            <th class="w-1/6 px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($this->rooms->where('status', 'terisi') as $room)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $room['nama'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($room['tipe'] === 'Premium') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                                    @elseif($room['tipe'] === 'Deluxe') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300
                                    @else bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300 @endif">
                                    {{ $room['tipe'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-300">
                                    Terisi
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($room['penghuni'])
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $room['penghuni']['nama'] }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Check-in: {{ \Carbon\Carbon::parse($room['penghuni']['tanggal_masuk'])->format('d/m/Y') }}</div>
                                @else
                                <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $room['penghuni']['telepon'] ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    {{-- Detail Button --}}
                                    <button wire:click="viewDetail({{ $room['id'] }})" 
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Detail
                                    </button>

                                    {{-- Check-out Button --}}
                                    {{-- <button wire:click="checkoutPenghuni({{ $room['id'] }})" 
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500"
                                            onclick="return confirm('Yakin ingin checkout penghuni ini?')">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Check-out
                                    </button> --}}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        
                        @if($this->rooms->where('status', 'terisi')->isEmpty())
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada penghuni</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Belum ada kamar yang terisi di unit ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Detail Penghuni --}}
    <div x-data="{ open: @entangle('showDetailModal') }" 
         x-show="open" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity" 
                 aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                
                @if($this->selectedRoomDetail)
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Detail Penghuni - Kamar {{ $this->selectedRoomDetail['nama'] }}
                        </h3>
                        <button @click="open = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    @if($this->selectedRoomDetail['penghuni'])
                    <div class="space-y-4">
                        {{-- Foto Profil --}}
                        <div class="text-center">
                            <div class="mx-auto h-20 w-20 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                <svg class="h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h4 class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">
                                {{ $this->selectedRoomDetail['penghuni']['nama'] }}
                            </h4>
                        </div>

                        {{-- Data Diri --}}
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h5 class="font-semibold text-gray-900 dark:text-white mb-3">Data Diri</h5>
                            <div class="grid grid-cols-1 gap-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Jenis Kelamin:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $this->selectedRoomDetail['penghuni']['jenis_kelamin'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Umur:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $this->selectedRoomDetail['penghuni']['umur'] }} tahun</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Pekerjaan:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $this->selectedRoomDetail['penghuni']['pekerjaan'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Alamat Asal:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $this->selectedRoomDetail['penghuni']['alamat_asal'] }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Kontak --}}
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                            <h5 class="font-semibold text-gray-900 dark:text-white mb-3">Informasi Kontak</h5>
                            <div class="grid grid-cols-1 gap-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Telepon:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $this->selectedRoomDetail['penghuni']['telepon'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Email:</span>
                                    <span class="text-gray-900 dark:text-white">{{ $this->selectedRoomDetail['penghuni']['email'] }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Info Sewa --}}
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                            <h5 class="font-semibold text-gray-900 dark:text-white mb-3">Informasi Sewa</h5>
                            <div class="grid grid-cols-1 gap-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Tanggal Masuk:</span>
                                    <span class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($this->selectedRoomDetail['penghuni']['tanggal_masuk'])->format('d F Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Berakhir:</span>
                                    <span class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($this->selectedRoomDetail['penghuni']['tanggal_berakhir'])->format('d F Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Status Pembayaran:</span>
                                    <span class="px-2 py-1 text-xs font-bold rounded-full
                                        @if($this->selectedRoomDetail['penghuni']['status_pembayaran'] === 'Lunas') bg-green-500 text-white
                                        @else bg-yellow-500 text-white @endif">
                                        {{ $this->selectedRoomDetail['penghuni']['status_pembayaran'] }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-500 dark:text-gray-400">Deposit:</span>
                                    <span class="text-gray-900 dark:text-white">Rp {{ number_format($this->selectedRoomDetail['penghuni']['deposit'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
