<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Form Pencarian -->
        <x-filament::section>
            <x-slot name="heading">
                Redeem Voucher
            </x-slot>
            <x-slot name="description">
                Masukkan kode voucher untuk memvalidasi dan redeem voucher penghuni
            </x-slot>

            {{ $this->form }}
        </x-filament::section>

        <!-- Detail Voucher -->
        @if($showVoucherDetail && $voucherFound)
        <x-filament::section>
            <x-slot name="heading">
                Detail Voucher
            </x-slot>

            <div class="space-y-6">
                <!-- Voucher Info Card -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                {{ $voucherFound->voucher->nama }}
                            </h3>
                            <p class="text-sm text-gray-600 mb-4">
                                {{ $voucherFound->voucher->deskripsi ?: 'Tidak ada deskripsi' }}
                            </p>
                            <div class="flex items-center space-x-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $voucherFound->voucher->kode_voucher }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $voucherFound->is_used ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $voucherFound->is_used ? 'Sudah Digunakan' : 'Belum Digunakan' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Penghuni Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Informasi Penghuni
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Nama</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $voucherFound->penghuni->nama }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $voucherFound->penghuni->email ?: 'Tidak ada email' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">No. Telepon</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $voucherFound->penghuni->no_telepon ?: 'Tidak ada telepon' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Status Voucher
                        </h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Diberikan</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $voucherFound->created_at->format('d M Y H:i') }}</p>
                            </div>
                            @if($voucherFound->is_used)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Digunakan</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $voucherFound->tanggal_digunakan?->format('d M Y H:i') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                @if(!$voucherFound->is_used)
                <div class="flex justify-center pt-4">
                    <x-filament::button
                        wire:click="redeemVoucher"
                        size="lg"
                        color="success"
                        icon="heroicon-o-check-circle"
                    >
                        Redeem Voucher Sekarang
                    </x-filament::button>
                </div>
                @else
                <div class="flex justify-center pt-4">
                    <div class="text-center">
                        <div class="inline-flex items-center px-4 py-2 rounded-lg bg-red-100 text-red-800">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Voucher Sudah Digunakan
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </x-filament::section>
        @endif

        <!-- Instructions -->
        <x-filament::section>
            <x-slot name="heading">
                Cara Menggunakan
            </x-slot>

            <div class="prose prose-sm max-w-none">
                <ol class="list-decimal list-inside space-y-2 text-gray-600">
                    <li>Minta penghuni untuk memberikan <strong>kode voucher</strong> mereka</li>
                    <li>Masukkan kode voucher pada form di atas</li>
                    <li>Klik tombol pencarian atau tekan Enter</li>
                    <li>Periksa detail voucher dan penghuni</li>
                    <li>Jika valid, klik tombol <strong>"Redeem Voucher Sekarang"</strong></li>
                    <li>Voucher akan otomatis ditandai sebagai sudah digunakan</li>
                </ol>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
