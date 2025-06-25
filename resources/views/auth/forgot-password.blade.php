<x-guest-layout>
    <x-auth-card>
        <div class="mb-4 text-sm text-gray-600">
            Masukkan email untuk mengirimkan link reset password.
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button>
                    Kirim Link Reset
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
