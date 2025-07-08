<x-filament::page>
    {{-- Judul --}}
    <div class="mb-6">
        <h2 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
            Balasan Tiket: {{ $record->judul }}
        </h2>
        <p class="text-sm text-gray-500">Status: <span class="font-semibold">{{ $record->status }}</span></p>
    </div>

    {{-- Detail Tiket --}}
    <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-800 rounded shadow">
        <p class="text-sm text-gray-600 mb-2">
            <strong>Pengirim:</strong> {{ $record->user->penghuni->nama ?? $record->user->name ?? 'Pengguna' }}
        </p>
        <p class="text-gray-800 dark:text-white">
            <strong>Deskripsi:</strong> <br>
            {{ $record->deskripsi }}
        </p>
    </div>

    {{-- Riwayat Balasan --}}
    <div class="mb-8">
        <h3 class="text-lg font-semibold mb-4">Riwayat Balasan</h3>

        @forelse ($record->messages()->with('user')->orderBy('created_at')->get() as $message)
            <div class="mb-4 p-4 border border-gray-200 dark:border-gray-700 rounded bg-white dark:bg-gray-900">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ $message->user->name ?? 'Pengguna' }}
                    </span>
                    <span class="text-xs text-gray-500">
                        {{ $message->created_at->diffForHumans() }}
                    </span>
                </div>
                <div class="text-gray-800 dark:text-white">
                    {{ $message->message }}
                </div>
            </div>
        @empty
            <p class="text-gray-500 italic">Belum ada balasan.</p>
        @endforelse
    </div>

    {{-- Form Balasan --}}
    <div class="mb-6">
        <form wire:submit.prevent="submitReply" class="space-y-4">
            <div>
                <label for="replyMessage" class="block text-sm font-medium text-gray-700 dark:text-white">Tulis Balasan</label>
                <textarea id="replyMessage" wire:model.defer="replyMessage"
                          rows="4"
                          class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-800 dark:text-white border-gray-300 dark:border-gray-700 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                          placeholder="Ketik pesan balasan di sini..."></textarea>
                @error('replyMessage') 
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <div class="flex gap-3">
                <x-filament::button type="submit" color="primary">
                    Kirim Balasan
                </x-filament::button>

                <x-filament::button color="success" wire:click="markAsProcessed" type="button">
                    Tandai Diproses
                </x-filament::button>

                <x-filament::button color="danger" wire:click="rejectTicket" type="button">
                    Tolak Tiket
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament::page>
