<div class="space-y-6">
    {{-- Header Info --}}
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-6 rounded-xl border border-blue-200 dark:border-blue-700">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $record->judul }}</h3>
                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ match($record->status) {
                            'Baru' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                            'Diproses' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                            'Selesai' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                            'Ditolak' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                            default => 'bg-gray-100 text-gray-800'
                        } }}">
                        {{ $record->status }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ match($record->prioritas) {
                            'Rendah' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                            'Sedang' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                            'Tinggi' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                            'Mendesak' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                            default => 'bg-gray-100 text-gray-800'
                        } }}">
                        Prioritas {{ $record->prioritas }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        {{ $record->kategori }}
                    </span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 dark:text-gray-400">Dibuat</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ $record->created_at->format('d M Y H:i') }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $record->created_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>

    {{-- Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Left Column --}}
        <div class="space-y-6">
            {{-- Description --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Deskripsi Masalah
                </h4>
                <div class="prose prose-sm dark:prose-invert max-w-none">
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $record->deskripsi }}</p>
                </div>
            </div>

            {{-- Location Info --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Lokasi
                </h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Unit:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $record->kamar?->unit?->nama_cluster ?? 'Tidak ditentukan' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Kamar:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $record->kamar?->nama ?? 'Tidak ditentukan' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="space-y-6">
            {{-- Reporter Info --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Pelapor
                </h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Nama:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $record->user?->penghuni?->nama ?? $record->user?->name ?? 'Tidak diketahui' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Email:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $record->user?->email ?? 'Tidak diketahui' }}</span>
                    </div>
                </div>
            </div>

            {{-- Photo Evidence --}}
            @if($record->foto)
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Foto Bukti
                    </h4>
                    <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600">
                        <img src="{{ Storage::url($record->foto) }}" 
                             alt="Foto bukti masalah" 
                             class="w-full h-48 object-cover hover:scale-105 transition-transform duration-300 cursor-pointer"
                             onclick="window.open('{{ Storage::url($record->foto) }}', '_blank')">
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">Klik untuk memperbesar</p>
                </div>
            @endif

            {{-- Timeline --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Timeline
                </h4>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Ticket dibuat</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $record->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($record->status !== 'Baru')
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Status diubah ke {{ $record->status }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $record->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($record->messages->count() > 0)
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $record->messages->count() }} pesan percakapan</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Terakhir: {{ $record->messages->last()?->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Messages Preview --}}
    @if($record->messages->count() > 0)
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                Percakapan Terbaru
            </h4>
            <div class="space-y-3 max-h-60 overflow-y-auto">
                @foreach($record->messages->take(3) as $message)
                    <div class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                            {{ substr($message->user->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $message->user->name ?? 'User' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at->format('d M H:i') }}</p>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ Str::limit($message->message, 100) }}</p>
                        </div>
                    </div>
                @endforeach
                
                @if($record->messages->count() > 3)
                    <div class="text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Dan {{ $record->messages->count() - 3 }} pesan lainnya...</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
