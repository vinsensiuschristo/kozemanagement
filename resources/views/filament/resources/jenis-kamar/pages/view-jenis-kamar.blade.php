<x-filament-panels::page
    @class([
        'fi-resource-view-record-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
        'fi-resource-record-' . $record->getKey(),
    ])
>

    {{-- ✅ Tombol Back dan Edit --}}
    <div class="flex justify-end gap-3 mb-6">
        <a
            href="{{ route('filament.admin.resources.jenis-kamars.index') }}"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition"
        >
            <x-heroicon-o-arrow-left class="w-6 h-4 mr-4" />
              Kembali
        </a>

        <a
            href="{{ route('filament.admin.resources.jenis-kamars.edit', ['record' => $record]) }}"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700 transition"
        >
            <x-heroicon-o-pencil-square class="w-6 h-4 mr-4" />
            Edit
        </a>
    </div>

    {{-- ✅ Konten Detail --}}
    <x-filament::section>
        <x-slot name="heading">
            Detail Jenis Kamar
        </x-slot>

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Nama</p>
                    <p class="text-base text-gray-900">{{ $record->nama }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">Harga</p>
                    <p class="text-base text-gray-900">Rp{{ number_format($record->harga, 0, ',', '.') }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-500">Deskripsi</p>
                    <p class="text-base text-gray-700">{{ $record->deskripsi }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-500">Fasilitas</p>
                    <div class="flex flex-wrap gap-2 mt-1">
                        @foreach ($record->fasilitasKamar as $fasilitas)
                            <span class="inline-flex items-center rounded-md bg-primary-100 px-2.5 py-0.5 text-sm font-medium text-primary-800">
                                {{ $fasilitas->nama }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ✅ Foto Jenis Kamar --}}
            <div>
                <p class="text-sm font-medium text-gray-500">Foto Jenis Kamar</p>
                <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @forelse ($record->detailFotoKamars as $foto)
                        <div class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden border bg-white">
                            <img
                                src="{{ asset('storage/' . $foto->path) }}"
                                alt="Foto Jenis Kamar"
                                class="object-cover w-full h-full"
                            >
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 italic">Tidak ada foto tersedia.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page>
