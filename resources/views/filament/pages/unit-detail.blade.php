<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                Detail Kamar {{ $unit['nama'] }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ $unit['alamat'] }}
            </p>
        </div>

        <!-- Penghuni Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                No
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nama Penghuni
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Deposit
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                No Telp
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($penghuni as $index => $p)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $p['kamar'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $p['nama'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    Rp {{ number_format($p['deposit'], 0, ',', '.') }},00
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $p['telepon'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada penghuni
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($penghuni) }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Penghuni</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-emerald-600">{{ $unit['available_rooms'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Kamar Tersedia</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-rose-600">{{ $unit['occupied_rooms'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Kamar Terisi</div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
