<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">Total Voucher Diredeem</p>
                        <p class="text-2xl font-bold">
                            {{ \App\Models\PenghuniVoucher::whereHas('voucher', function($q) {
                                $q->where('mitra_id', auth()->user()->mitra?->id);
                            })->where('is_used', true)->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-400 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">Hari Ini</p>
                        <p class="text-2xl font-bold">
                            {{ \App\Models\PenghuniVoucher::whereHas('voucher', function($q) {
                                $q->where('mitra_id', auth()->user()->mitra?->id);
                            })->where('is_used', true)->whereDate('tanggal_digunakan', today())->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-green-400 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">Minggu Ini</p>
                        <p class="text-2xl font-bold">
                            {{ \App\Models\PenghuniVoucher::whereHas('voucher', function($q) {
                                $q->where('mitra_id', auth()->user()->mitra?->id);
                            })->where('is_used', true)->whereBetween('tanggal_digunakan', [now()->startOfWeek(), now()->endOfWeek()])->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-purple-400 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Form --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <form wire:submit="searchVoucher">
                    {{ $this->form }}
                </form>
            </div>
        </div>

        {{-- Voucher Detail --}}
        @if($showVoucherDetail && $foundVoucher)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                        Detail Voucher
                    </h3>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Voucher Info --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Voucher</label>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $foundVoucher->voucher->nama }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode Voucher</label>
                                <p class="text-lg font-mono font-bold text-indigo-600 dark:text-indigo-400">{{ $foundVoucher->voucher->kode_voucher }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</label>
                                <p class="text-gray-700 dark:text-gray-300">{{ $foundVoucher->voucher->deskripsi ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Penghuni</label>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $foundVoucher->penghuni->nama }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                                <div class="flex items-center gap-2">
                                    @if($foundVoucher->is_used)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Sudah Digunakan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                                            </svg>
                                            Belum Digunakan
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if($foundVoucher->is_used)
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Digunakan Pada</label>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $foundVoucher->tanggal_digunakan?->format('d M Y H:i') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                        @if(!$foundVoucher->is_used)
                            <button type="button" wire:click="redeemVoucher" 
                                    class="flex-1 sm:flex-none inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Redeem Voucher
                            </button>
                        @endif
                        
                        <button type="button" wire:click="resetForm"
                                class="flex-1 sm:flex-none inline-flex justify-center items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl transition-all duration-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Recent Redeemed Vouchers --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Voucher Terbaru Diredeem
                </h3>
            </div>
            <div class="p-6">
                @php
                    $recentVouchers = \App\Models\PenghuniVoucher::with(['voucher', 'penghuni'])
                        ->whereHas('voucher', function($q) {
                            $q->where('mitra_id', auth()->user()->mitra?->id);
                        })
                        ->where('is_used', true)
                        ->orderBy('tanggal_digunakan', 'desc')
                        ->limit(5)
                        ->get();
                @endphp

                @forelse($recentVouchers as $voucher)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $voucher->voucher->nama }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $voucher->penghuni->nama }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $voucher->voucher->kode_voucher }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $voucher->tanggal_digunakan?->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                        <p class="mt-2 text-gray-500 dark:text-gray-400">Belum ada voucher yang diredeem</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-panels::page>
