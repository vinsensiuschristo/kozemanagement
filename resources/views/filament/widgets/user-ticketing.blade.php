<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg">
                        <svg class="w-5 h-5 text-black dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            Pengaduan & Ticketing
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Laporkan masalah dan keluhan Anda</p>
                    </div>
                </div>
                <button wire:click="toggleCreateForm" 
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r text-black from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 dark:text-white text-sm font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($showCreateForm)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        @endif
                    </svg>
                    {{ $showCreateForm ? 'Batal' : 'Buat Pengaduan' }}
                </button>
            </div>
        </x-slot>

        <div class="space-y-6">
            {{-- Modern Stats Cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                <div class="relative overflow-hidden bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 p-4 rounded-2xl border border-blue-200 dark:border-blue-700 hover:shadow-lg transition-all duration-300">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-blue-500/10 rounded-full -mr-8 -mt-8"></div>
                    <div class="relative">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total'] }}</div>
                        <div class="text-xs font-medium text-blue-700 dark:text-blue-300">Total Ticket</div>
                    </div>
                </div>

                <div class="relative overflow-hidden bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/30 dark:to-red-800/30 p-4 rounded-2xl border border-red-200 dark:border-red-700 hover:shadow-lg transition-all duration-300">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-red-500/10 rounded-full -mr-8 -mt-8"></div>
                    <div class="relative">
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['open'] }}</div>
                        <div class="text-xs font-medium text-red-700 dark:text-red-300">Terbuka</div>
                    </div>
                </div>

                <div class="relative overflow-hidden bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/30 dark:to-yellow-800/30 p-4 rounded-2xl border border-yellow-200 dark:border-yellow-700 hover:shadow-lg transition-all duration-300">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-yellow-500/10 rounded-full -mr-8 -mt-8"></div>
                    <div class="relative">
                        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['in_progress'] }}</div>
                        <div class="text-xs font-medium text-yellow-700 dark:text-yellow-300">Proses</div>
                    </div>
                </div>

                <div class="relative overflow-hidden bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 p-4 rounded-2xl border border-green-200 dark:border-green-700 hover:shadow-lg transition-all duration-300">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-green-500/10 rounded-full -mr-8 -mt-8"></div>
                    <div class="relative">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['resolved'] }}</div>
                        <div class="text-xs font-medium text-green-700 dark:text-green-300">Selesai</div>
                    </div>
                </div>
            </div>

            {{-- Modern Create Form --}}
            @if($showCreateForm)
                <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
                        <h3 class="text-lg font-bold text-black dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Buat Pengaduan Baru
                        </h3>
                        <p class="text-blue-100 text-sm mt-1">Isi form di bawah untuk melaporkan masalah</p>
                    </div>

                    <form wire:submit="create" class="p-6 space-y-6">
                        {{ $this->form }}
                        
                        <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <button type="submit" 
                                    class="flex-1 sm:flex-none inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-black dark:text-white text-sm font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Kirim Pengaduan
                            </button>
                            <button type="button" wire:click="toggleCreateForm"
                                    class="flex-1 sm:flex-none inline-flex justify-center items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-black dark:text-gray-300 text-sm font-medium rounded-xl transition-all duration-300">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- Modern Tickets List --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Riwayat Pengaduan
                    </h3>
                    @if($tickets->count() > 0)
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $tickets->count() }} ticket terbaru</span>
                    @endif
                </div>
                
                @forelse($tickets as $ticket)
                    <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 transition-all duration-300 overflow-hidden">
                        <div class="p-6 space-y-4">
                            {{-- Header --}}
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-gray-900 dark:text-white text-lg group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                        {{ $ticket->judul }}
                                    </h4>
                                    <div class="flex items-center gap-2 mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h6m-6 4h6m-2 4h2M7 7h2v6H7z"></path>
                                        </svg>
                                        {{ $ticket->unit->nama_cluster ?? 'Unit tidak diketahui' }}
                                        @if($ticket->kamar)
                                            <span class="text-gray-400">•</span>
                                            <span>{{ $ticket->kamar->nama }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <x-filament::badge :color="$ticket->status_color" size="sm" class="font-medium">
                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                    </x-filament::badge>
                                    <x-filament::badge :color="$ticket->prioritas_color" size="sm" class="font-medium">
                                        {{ ucfirst($ticket->prioritas) }}
                                    </x-filament::badge>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        {{ ucfirst($ticket->kategori) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                    {{ $ticket->deskripsi }}
                                </p>
                            </div>

                            {{-- Footer --}}
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                                <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ \Illuminate\Support\Carbon::parse($ticket->tanggal_lapor)->format('d M Y, H:i') }}

                                    </span>
                                </div>
                                
                                @if($ticket->response_admin)
                                    <div class="inline-flex items-center gap-1 text-xs text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30 px-2 py-1 rounded-full">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        Ada respon admin
                                    </div>
                                @endif
                            </div>

                            {{-- Admin Response --}}
                            @if($ticket->response_admin)
                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 p-4 rounded-xl border-l-4 border-green-400">
                                    <div class="flex items-start gap-3">
                                        <div class="p-1 bg-green-500 rounded-full">
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-sm font-semibold text-green-800 dark:text-green-300 mb-1">Respon Admin:</div>
                                            <div class="text-sm text-green-700 dark:text-green-400">{{ $ticket->response_admin }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-black dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Belum ada pengaduan</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">Klik "Buat Pengaduan" untuk melaporkan masalah atau keluhan Anda.</p>
                        <button wire:click="toggleCreateForm" 
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white text-sm font-medium rounded-xl transition-all duration-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Buat Pengaduan Pertama
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
