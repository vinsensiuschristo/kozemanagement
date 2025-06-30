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
            
            <div class="flex items-center space-x-8 gap-4">
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
                                    <button wire:click="toggleDetail({{ $room['id'] }})" 
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-gray-900 dark:text-white 
                                            @if($this->expandedRoomId == $room['id']) bg-indigo-700 hover:bg-indigo-800 @else bg-indigo-600 hover:bg-indigo-700 @endif 
                                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($this->expandedRoomId == $room['id'])
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            @endif
                                        </svg>
                                        {{ $this->expandedRoomId == $room['id'] ? 'Tutup' : 'Detail' }}
                                    </button>

                                    {{-- Check-out Button --}}
                                    {{-- <button wire:click="checkoutPenghuni({{ $room['id'] }})" 
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-gray-900 dark:text-white hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500"
                                            onclick="return confirm('Yakin ingin checkout penghuni ini?')">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Check-out
                                    </button> --}}
                                </div>
                            </td>
                        </tr>
                        
                        {{-- Detail Card Row --}}
                        @if($this->expandedRoomId == $room['id'] && $room['penghuni'])
                        <tr>
                            <td colspan="7" class="px-0 py-0">
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-l-4 border-indigo-500">
                                    <div class="p-6">
                                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                            {{-- Foto dan Info Dasar --}}
                                            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                                                <div class="text-center mb-4">
                                                    <div class="mx-auto h-20 w-20 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                                                        <svg class="h-12 w-12 text-gray-900 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                    </div>
                                                    <h4 class="mt-3 text-lg font-semibold text-gray-900 dark:text-white">
                                                        {{ $room['penghuni']['nama'] }}
                                                    </h4>
                                                    <p class="text-sm text-gray-900 dark:text-white">
                                                        Kamar {{ $room['nama'] }} • {{ $room['tipe'] }}
                                                    </p>
                                                </div>
                                                
                                                <div class="space-y-2 text-sm">
                                                    <div class="flex justify-between">
                                                        <span class="font-medium text-gray-500 dark:text-gray-400">Jenis Kelamin:</span>
                                                        <span class="text-gray-900 dark:text-white">{{ $room['penghuni']['jenis_kelamin'] }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="font-medium text-gray-500 dark:text-gray-400">Umur:</span>
                                                        <span class="text-gray-900 dark:text-white">{{ $room['penghuni']['umur'] }} tahun</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="font-medium text-gray-500 dark:text-gray-400">Pekerjaan:</span>
                                                        <span class="text-gray-900 dark:text-white">{{ $room['penghuni']['pekerjaan'] }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Kontak dan Alamat --}}
                                            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                                                <h5 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                    </svg>
                                                    Informasi Kontak
                                                </h5>
                                                <div class="space-y-3 text-sm">
                                                    <div>
                                                        <span class="font-medium text-gray-500 dark:text-gray-400">Telepon:</span>
                                                        <p class="text-gray-900 dark:text-white">{{ $room['penghuni']['no_telp'] }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="font-medium text-gray-500 dark:text-gray-400">Email:</span>
                                                        <p class="text-gray-900 dark:text-white">{{ $room['penghuni']['email'] }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="font-medium text-gray-500 dark:text-gray-400">Alamat Asal:</span>
                                                        <p class="text-gray-900 dark:text-white">{{ $room['penghuni']['alamat_asal'] }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Info Sewa dan Pembayaran --}}
                                            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                                                <h5 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                    </svg>
                                                    Informasi Sewa
                                                </h5>
                                                <div class="space-y-3 text-sm">
                                                    <div>
                                                        <span class="font-medium text-gray-500 dark:text-gray-400">Tanggal Masuk:</span>
                                                        <p class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($room['penghuni']['tanggal_masuk'])->format('d F Y') }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="font-medium text-gray-500 dark:text-gray-400">Berakhir:</span>
                                                        <p class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($room['penghuni']['tanggal_berakhir'])->format('d F Y') }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="font-medium text-gray-500 dark:text-gray-400">Status Pembayaran:</span>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                            @if($room['penghuni']['status_pembayaran'] === 'Lunas') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 @endif">
                                                            {{ $room['penghuni']['status_pembayaran'] }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <span class="font-medium text-gray-500 dark:text-gray-400">Deposit:</span>
                                                        <p class="text-lg font-bold text-green-600 dark:text-green-400">Rp {{ number_format($room['penghuni']['deposit'], 0, ',', '.') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endif
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
</x-filament-panels::page>
