<div class="space-y-6">
    <!-- Voucher Info -->
    <div class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg p-6">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    {{ $record->voucher->nama }}
                </h3>
                <p class="text-sm text-gray-600 mb-4">
                    {{ $record->voucher->deskripsi ?: 'Tidak ada deskripsi' }}
                </p>
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                        {{ $record->voucher->kode_voucher }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $record->is_used ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $record->is_used ? 'Sudah Digunakan' : 'Belum Digunakan' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Mitra Info -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            Informasi Mitra
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-500">Nama Mitra</label>
                <p class="mt-1 text-sm text-gray-900">{{ $record->voucher->mitra->nama }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Jenis Bisnis</label>
                <p class="mt-1 text-sm text-gray-900">{{ $record->voucher->mitra->jenis_bisnis ?: 'Tidak disebutkan' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Alamat</label>
                <p class="mt-1 text-sm text-gray-900">{{ $record->voucher->mitra->alamat ?: 'Tidak ada alamat' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Kontak</label>
                <p class="mt-1 text-sm text-gray-900">{{ $record->voucher->mitra->kontak ?: 'Tidak ada kontak' }}</p>
            </div>
        </div>
    </div>

    <!-- Usage Info -->
    @if($record->is_used)
    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
        <h4 class="text-md font-semibold text-green-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Informasi Penggunaan
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-green-700">Tanggal Digunakan</label>
                <p class="mt-1 text-sm text-green-900">{{ $record->tanggal_digunakan?->format('d M Y H:i') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-green-700">Digunakan di</label>
                <p class="mt-1 text-sm text-green-900">{{ $record->mitraDigunakan->nama ?? $record->voucher->mitra->nama }}</p>
            </div>
        </div>
    </div>
    @else
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <h4 class="text-md font-semibold text-yellow-900 mb-2 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Cara Menggunakan Voucher
        </h4>
        <p class="text-sm text-yellow-800">
            Untuk menggunakan voucher ini, kunjungi mitra <strong>{{ $record->voucher->mitra->nama }}</strong> dan tunjukkan kode voucher <strong>{{ $record->voucher->kode_voucher }}</strong> kepada petugas.
        </p>
    </div>
    @endif

    <!-- Timeline -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Timeline
        </h4>
        <div class="flow-root">
            <ul class="-mb-8">
                <li>
                    <div class="relative pb-8">
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="min-w-0 flex-1 pt-1.5">
                                <div>
                                    <p class="text-sm text-gray-500">Voucher diterima</p>
                                    <p class="text-xs text-gray-400">{{ $record->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @if($record->is_used)
                <li>
                    <div class="relative">
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="min-w-0 flex-1 pt-1.5">
                                <div>
                                    <p class="text-sm text-gray-500">Voucher digunakan</p>
                                    <p class="text-xs text-gray-400">{{ $record->tanggal_digunakan?->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>
