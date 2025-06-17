<x-filament::page>
    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Layout Kamar - {{ $this->record->nama_cluster }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Manajemen kamar dan penghuni
            </p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid gap-4 mb-6 md:grid-cols-2 lg:grid-cols-4">
        @php $stats = $this->getStatistik(); @endphp
        
        <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-800">
            <h3 class="text-sm font-medium text-gray-500">Total Kamar</h3>
            <p class="text-2xl font-semibold">{{ $stats['total'] }}</p>
        </div>
        
        <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-800">
            <h3 class="text-sm font-medium text-gray-500">Tersedia</h3>
            <p class="text-2xl font-semibold text-green-600 dark:text-green-400">{{ $stats['kosong'] }}</p>
        </div>
        
        <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-800">
            <h3 class="text-sm font-medium text-gray-500">Terisi</h3>
            <p class="text-2xl font-semibold text-red-600 dark:text-red-400">{{ $stats['terisi'] }}</p>
        </div>
        
        <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-800">
            <h3 class="text-sm font-medium text-gray-500">Tingkat Hunian</h3>
            <p class="text-2xl font-semibold text-blue-600 dark:text-blue-400">{{ $stats['tingkat_hunian'] }}%</p>
        </div>
    </div>

    {{-- Revenue Summary --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-4">
            <div class="flex-1">
                <h3 class="text-sm font-medium text-gray-500">Revenue Aktual</h3>
                <p class="text-lg font-semibold">Rp {{ number_format($stats['revenue_aktual'], 0, ',', '.') }}</p>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-medium text-gray-500">Revenue Potensial</h3>
                <p class="text-lg font-semibold">Rp {{ number_format($stats['revenue_potensial'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Rooms Table --}}
    <div class="overflow-hidden bg-white rounded-lg shadow dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kamar</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lantai</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penghuni</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @foreach($this->rooms as $room)
                    <tr>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900 dark:text-white">{{ $room['nama'] }}</div>
                            <div class="text-xs text-gray-500">{{ $room['ukuran'] }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-gray-900 dark:text-white">Lantai {{ $room['lantai'] }}</td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($room['tipe'] === 'Premium') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                {{ $room['tipe'] }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($room['status'] === 'kosong') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                @elseif($room['status'] === 'terisi') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                @elseif($room['status'] === 'booked') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                @if($room['status'] === 'kosong') Tersedia
                                @elseif($room['status'] === 'terisi') Terisi
                                @elseif($room['status'] === 'booked') Dipesan
                                @else Maintenance
                                @endif
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($room['penghuni'])
                                <div class="font-medium text-gray-900 dark:text-white">{{ $room['penghuni']['nama'] }}</div>
                                <div class="text-xs text-gray-500">{{ $room['penghuni']['pekerjaan'] }}</div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-gray-900 dark:text-white">
                            {{ $room['penghuni']['telepon'] ?? '-' }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-gray-900 dark:text-white">
                            Rp {{ number_format($room['harga'], 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                {{-- Detail Button --}}
                                <button wire:click="viewDetail({{ $room['id'] }})" 
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                {{-- Check-in Button --}}
                                @if($room['status'] === 'kosong')
                                <button wire:click="checkinPenghuni({{ $room['id'] }})" 
                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                @endif

                                {{-- Check-out Button --}}
                                @if($room['status'] === 'terisi')
                                <button wire:click="checkoutPenghuni({{ $room['id'] }})" 
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                        onclick="return confirm('Yakin ingin checkout penghuni ini?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                @endif

                                {{-- Confirm Booking Button --}}
                                @if($room['status'] === 'booked')
                                <button wire:click="konfirmasiBooking({{ $room['id'] }})" 
                                        class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-filament::page>