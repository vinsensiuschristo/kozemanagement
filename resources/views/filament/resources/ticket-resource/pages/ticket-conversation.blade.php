<x-filament::page>
    <h2 class="text-xl font-bold mb-4">Percakapan Tiket: {{ $record->judul }}</h2>

    {{-- Riwayat Pesan --}}
    <div class="mb-6 space-y-4">
        @foreach ($record->messages as $message)
            <div class="p-4 rounded shadow 
                {{ $message->user_id === auth()->id() ? 'bg-primary-100 dark:bg-primary-900 ml-auto text-right' : 'bg-gray-100 dark:bg-gray-800' }}">
                <div class="text-sm font-semibold text-black dark:text-white">
                    {{ $message->user->name ?? 'Pengguna' }}
                </div>
                <div class="text-sm text-black dark:text-white mt-1">
                    {{ $message->message }}
                </div>
                <div class="text-xs text-black dark:text-white mt-1">
                    {{ $message->created_at->diffForHumans() }}
                </div>
            </div>
        @endforeach
    </div>

    {{-- Form Balas --}}
    <form wire:submit.prevent="submitReply" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-white">Tulis Balasan</label>
            <textarea wire:model.defer="replyMessage" rows="3"
                class="block w-full mt-1 rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-white"></textarea>
            @error('replyMessage') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
        </div>

        <x-filament::button type="submit" color="primary">Kirim</x-filament::button>
    </form>
</x-filament::page>
