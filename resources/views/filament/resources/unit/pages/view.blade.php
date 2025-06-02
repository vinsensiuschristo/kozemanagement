@extends('filament::layouts.app')

@section('content')
    <div class="space-y-6 p-6">
        {{-- Informasi Kos --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Informasi Kos</h2>
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-700 dark:text-gray-200">
                <div><strong>Nama Kos:</strong> {{ $record->nama_cluster }}</div>
                <div><strong>Pemilik:</strong> {{ $record->owner->nama ?? '-' }}</div>
                <div><strong>Disewakan Untuk:</strong> {{ ucfirst($record->disewakan_untuk) }}</div>
                <div><strong>Multi Tipe?</strong> {{ $record->multi_tipe ? 'Ya' : 'Tidak' }}</div>
                <div><strong>Tahun Dibangun:</strong> {{ $record->tahun_dibangun }}</div>
                <div><strong>Nomor Kontrak:</strong> {{ $record->nomor_kontrak }}</div>
                <div><strong>Periode Kontrak:</strong> {{ $record->tanggal_awal_kontrak }} - {{ $record->tanggal_akhir_kontrak }}</div>
            </div>
        </div>

        {{-- Alamat Kos --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Alamat Kos</h2>
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-700 dark:text-gray-200">
                <div><strong>Alamat:</strong> {{ $record->alamat->alamat ?? '-' }}</div>
                <div><strong>Provinsi:</strong> {{ $record->alamat->provinsi ?? '-' }}</div>
                <div><strong>Kabupaten:</strong> {{ $record->alamat->kabupaten ?? '-' }}</div>
                <div><strong>Kecamatan:</strong> {{ $record->alamat->kecamatan ?? '-' }}</div>
                <div class="col-span-2"><strong>Deskripsi:</strong> {{ $record->alamat->deskripsi ?? '-' }}</div>
            </div>
        </div>

        {{-- Fasilitas Kos --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Fasilitas Kos</h2>
            <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-200">
                @forelse ($record->fasilitasUnits as $fasilitas)
                    <li>{{ $fasilitas->fasilitas->nama ?? '-' }}</li>
                @empty
                    <li><em>Tidak ada fasilitas.</em></li>
                @endforelse
            </ul>
        </div>

        {{-- Tipe Kamar & Harga --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Tipe Kamar & Harga</h2>
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-700 dark:text-gray-200">
                @foreach ($record->tipeKamars as $tipe)
                    <div class="border rounded p-4 bg-gray-50 dark:bg-gray-900">
                        <strong>{{ $tipe->nama_tipe }}</strong><br>
                        @if ($tipe->hargaKamars)
                            <div>Harga: Rp {{ number_format($tipe->hargaKamars->harga_perbulan, 0, ',', '.') }}</div>
                            <div>Deposit: 
                                {{ $tipe->hargaKamars->minimal_deposit ? 'Rp ' . number_format($tipe->hargaKamars->minimal_deposit, 0, ',', '.') : '-' }}
                            </div>
                        @else
                            <div><em>Tidak ada data harga.</em></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Foto Kos --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Foto Kos</h2>
            <div class="grid grid-cols-3 gap-4">
                @forelse ($record->fotoUnit as $foto)
                    <div class="space-y-1">
                        <img src="{{ Storage::url($foto->path) }}" alt="Foto" class="rounded-md border w-full object-cover" />
                        <div class="text-xs text-gray-600 dark:text-gray-300">Kategori: {{ ucfirst($foto->kategori) }}</div>
                    </div>
                @empty
                    <div class="col-span-3"><em class="text-gray-500">Tidak ada foto tersedia.</em></div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
