<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Ticket Info Header -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold mb-2">{{ $record->judul }}</h2>
                    <p class="text-blue-100 mb-4">{{ $record->deskripsi }}</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm">
                            Kategori: {{ $record->kategori }}
                        </span>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm">
                            Prioritas: {{ $record->prioritas }}
                        </span>
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm">
                            Status: {{ $record->status }}
                        </span>
                    </div>
                </div>
                @if($record->foto)
                    <div class="ml-4">
                        <img src="{{ Storage::url($record->foto) }}" 
                             alt="Foto Ticket" 
                             class="w-20 h-20 object-cover rounded-lg border-2 border-white/30">
                    </div>
                @endif
            </div>
        </div>

        <!-- Messages -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Percakapan</h3>
            </div>
            
            <div class="p-6 space-y-4 max-h-96 overflow-y-auto" id="messages-container">
                @forelse($record->messages as $message)
                    <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs lg:max-w-md">
                            <!-- Message bubble -->
                            <div class="px-4 py-2 rounded-lg {{ $message->user_id === auth()->id() 
                                ? 'bg-blue-500 text-white' 
                                : 'bg-gray-100 text-gray-900' }}">
                                <p class="text-sm">{{ $message->message }}</p>
                                
                                @if($message->attachment)
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($message->attachment) }}" 
                                             alt="Attachment" 
                                             class="max-w-full h-auto rounded cursor-pointer"
                                             onclick="window.open('{{ Storage::url($message->attachment) }}', '_blank')">
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Message info -->
                            <div class="flex items-center mt-1 text-xs text-gray-500 {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <span>{{ $message->user->name }}</span>
                                <span class="mx-1">•</span>
                                <span>{{ $message->created_at->diffForHumans() }}</span>
                                @if($message->read_at)
                                    <span class="ml-1 text-green-500">✓</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <p>Belum ada percakapan. Mulai dengan mengirim pesan pertama.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Reply Form -->
        @if($record->canBeReplied())
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
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
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-800">
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
