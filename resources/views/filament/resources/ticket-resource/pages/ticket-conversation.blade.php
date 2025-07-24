<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Ticket Info Header -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 text-white">
            <div class="flex justify-between items-start">
                {{-- TAMBAHKAN KELAS PADA DIV INI --}}
                <div class="p-4 rounded-lg bg-gray-800 text-white w-full">

                    {{-- Pastikan teks di sini juga diatur ke putih/terang --}}
                    <h2 class="text-2xl font-bold mb-2 text-white">{{ $record->judul }}</h2>
                    <p class="mb-4 text-gray-950">{{ $record->deskripsi }}</p>

                    <div class="flex flex-wrap gap-2">
                        {{-- Badge untuk Kategori & Prioritas --}}
                        <span
                            class="px-3 py-1 rounded-full text-sm font-medium bg-gray-700 text-gray-950 dark:text-white">
                            Kategori: {{ $record->kategori }}
                        </span>
                        <span
                            class="px-3 py-1 rounded-full text-sm font-medium bg-gray-700 text-gray-950 dark:text-white">
                            Prioritas: {{ $record->prioritas }}
                        </span>

                        {{-- Badge untuk Status dengan warna dinamis --}}
                        <span
                            class="px-3 py-1 rounded-full text-sm font-medium
                {{ match ($record->status) {
                    'Baru' => 'bg-red-500/50 text-gray-950 dark:text-white',
                    'Diproses' => 'bg-yellow-500/50 text-gray-950 dark:text-white',
                    'Selesai' => 'bg-green-500/50 text-gray-950 dark:text-white',
                    'Ditolak' => 'bg-gray-600 text-gray-950 dark:text-white',
                    default => 'bg-gray-600 text-gray-950 dark:text-white',
                } }}
            ">
                            Status: {{ $record->status }}
                        </span>

                        {{-- Badge untuk Kamar (jika ada) --}}
                        @if ($record->kamar)
                            <span
                                class="px-3 py-1 rounded-full text-sm font-medium bg-gray-700 text-gray-950 dark:text-white">
                                Kamar: {{ $record->kamar->nama }}
                            </span>
                        @endif
                    </div>
                </div>
                @if ($record->foto)
                    <div class="ml-4 flex-shrink-0">
                        <img src="{{ Storage::url($record->foto) }}" alt="Foto Ticket"
                            class="w-20 h-20 object-cover rounded-lg border-2 border-white/30 cursor-pointer"
                            onclick="window.open('{{ Storage::url($record->foto) }}', '_blank')">
                    </div>
                @endif
            </div>
        </div>

        <!-- Messages -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-950 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                    Percakapan ({{ $record->messages->count() }} pesan)
                </h3>
            </div>

            <div class="p-6 space-y-4 max-h-96 overflow-y-auto" id="messages-container">
                @forelse($record->messages as $message)
                    <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs lg:max-w-md">
                            <!-- Avatar -->
                            <div
                                class="flex items-center mb-2 {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-gray-950 dark:text-white text-xs font-bold
                        {{ $message->user_id === auth()->id() ? 'bg-blue-500' : 'bg-green-500' }}">
                                    {{ substr($message->user->name, 0, 2) }}
                                </div>
                                <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $message->user->name }} • {{ $message->created_at->format('d M H:i') }}
                                </span>
                            </div>

                            <!-- Message bubble -->
                            <div
                                class="px-4 py-3 rounded-lg 
                    {{ $message->user_id === auth()->id()
                        ? 'bg-blue-500 text-white'
                        : 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-gray-100' }}">
                                <p class="text-sm whitespace-pre-wrap text-gray-950 dark:text-white">
                                    {{ $message->message }}</p>

                                @if ($message->attachment)
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($message->attachment) }}" alt="Attachment"
                                            class="max-w-full h-auto rounded cursor-pointer border-2 border-white/30 dark:border-gray-700"
                                            onclick="window.open('{{ Storage::url($message->attachment) }}', '_blank')">
                                    </div>
                                @endif
                            </div>

                            <!-- Read status -->
                            <div
                                class="flex items-center mt-1 text-xs {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <span
                                    class="text-gray-400 dark:text-gray-500">{{ $message->created_at->diffForHumans() }}</span>
                                @if ($message->user_id === auth()->id() && $message->read_at)
                                    <span class="ml-1 text-green-500">✓ Dibaca</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                        <p>Belum ada percakapan. Mulai dengan mengirim pesan pertama.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Reply Form -->
        @if ($record->canBeReplied())
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <form wire:submit="sendMessage">
                        {{ $this->form }}

                        <div class="mt-4 flex justify-end">
                            <x-filament::button type="submit" icon="heroicon-o-paper-airplane">
                                Kirim Pesan
                            </x-filament::button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 dark:bg-gray-800 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-800 dark:text-yellow-300">
                            Ticket ini sudah {{ strtolower($record->status) }}. Tidak dapat mengirim pesan baru.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Auto scroll to bottom of messages
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });

        // Auto refresh every 30 seconds
        setInterval(function() {
            if (document.visibilityState === 'visible') {
                window.location.reload();
            }
        }, 30000);
    </script>
</x-filament-panels::page>
