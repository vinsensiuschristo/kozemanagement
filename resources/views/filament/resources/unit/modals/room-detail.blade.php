<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header Info --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $this->record->nama_cluster }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $this->record->alamat->alamat ?? 'Alamat tidak tersedia' }}
                    </p>
                </div>
                
                {{-- Statistics --}}
                <div class="flex items-center space-x-6">
                    @php $stats = $this->getStatistik(); @endphp
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
                        <div class="text-xs text-gray-500">Total Kamar</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $stats['kosong'] }}</div>
                        <div class="text-xs text-gray-500">Tersedia</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600">{{ $stats['terisi'] }}</div>
                        <div class="text-xs text-gray-500">Terisi</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $stats['tingkat_hunian'] }}%</div>
                        <div class="text-xs text-gray-500">Hunian</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Manual --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                No. Kamar
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Lantai
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tipe
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Penghuni
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Telepon
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Harga/Bulan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($this->rooms as $room)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $room['nama'] }}
                                </div>
                                <div class="text-sm text-gray-500">{{ $room['ukuran'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Lantai {{ $room['lantai'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($room['tipe'] === 'Premium') bg-yellow-100 text-yellow-800 
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ $room['tipe'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($room['status'] === 'kosong') bg-green-100 text-green-800
                                    @elseif($room['status'] === 'terisi') bg-red-100 text-red-800
                                    @elseif($room['status'] === 'booked') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $this->getStatusLabel($room['status']) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $room['penghuni']['nama'] ?? '-' }}
                                </div>
                                @if($room['penghuni'])
                                <div class="text-sm text-gray-500">{{ $room['penghuni']['pekerjaan'] }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $room['penghuni']['telepon'] ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                Rp {{ number_format($room['harga'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                {{-- Detail Button --}}
                                <button wire:click="viewDetail({{ $room['id'] }})" 
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    Detail
                                </button>

                                {{-- Check-in Button --}}
                                @if($room['status'] === 'kosong')
                                <button wire:click="checkinPenghuni({{ $room['id'] }})" 
                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                    Check-in
                                </button>
                                @endif

                                {{-- Check-out Button --}}
                                @if($room['status'] === 'terisi')
                                <button wire:click="checkoutPenghuni({{ $room['id'] }})" 
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                        onclick="return confirm('Yakin ingin checkout penghuni ini?')">
                                    Check-out
                                </button>
                                @endif

                                {{-- Confirm Booking Button --}}
                                @if($room['status'] === 'booked')
                                <button wire:click="konfirmasiBooking({{ $room['id'] }})" 
                                        class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                    Konfirmasi
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Summary --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Ringkasan</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">
                        Rp {{ number_format($stats['revenue_aktual'], 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-green-600">Revenue Aktual</div>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">
                        Rp {{ number_format($stats['revenue_potensial'], 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-blue-600">Revenue Potensial</div>
                </div>
                <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">
                        {{ $stats['revenue_potensial'] > 0 ? round(($stats['revenue_aktual'] / $stats['revenue_potensial']) * 100, 1) : 0 }}%
                    </div>
                    <div class="text-sm text-purple-600">Efisiensi</div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
