<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitResource\Pages;
use App\Models\Unit;
use App\Models\Fasilitas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Placeholder;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Card;


use function Pest\Laravel\options;
use Filament\Forms\Get;
use Filament\Infolists\Components\Fieldset;
use Filament\Forms\Set;
use Closure;
use function Filament\Forms\getLivewire;
use Illuminate\Support\Str;




class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationGroup = 'Manajemen Kos';

    protected array $fasilitasTerpilih = [];
    protected array $fasilitasKamarsData = [];



    public static function form(Form $form): Form
    {
        return $form->schema([
            Wizard::make([
                // STEP 1: DATA KOS
                Step::make('Data Kos')->schema([
                    Select::make('id_owner')
                        ->label('Pemilik Kos')
                        ->relationship('owner', 'nama') // Asumsikan relasi Unit → Owner sudah diset dengan relasi owner()
                        ->required(),

                    TextInput::make('nama_cluster')
                        ->label('Nama Kos')
                        ->required(),

                    Toggle::make('multi_tipe')
                        ->label('Kos Memiliki Banyak Tipe Kamar?')
                        ->reactive(),

                    Select::make('disewakan_untuk')
                        ->label('Disewakan Untuk')
                        ->options([
                            'putra' => 'Putra',
                            'putri' => 'Putri',
                            'campur' => 'Campur',
                        ])
                        ->required(),

                    Textarea::make('deskripsi')->label('Deskripsi Kos'),

                    Select::make('tahun_dibangun')
                        ->label('Tahun Dibangun')
                        ->options(collect(range(date('Y'), 1980))->mapWithKeys(fn($y) => [$y => $y]))
                        ->required(),
                ]),

                // STEP 2: ALAMAT
                Step::make('Alamat Kos')->schema([
                    Textarea::make('alamatUnit.alamat')
                        ->label('Alamat')
                        ->required(),

                    Select::make('alamatUnit.provinsi')
                        ->label('Provinsi')
                        ->options(self::getProvinces())
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (Set $set) {
                            $set('alamatUnit.kabupaten', null);
                            $set('alamatUnit.kecamatan', null);
                        }),

                    Select::make('alamatUnit.kabupaten')
                        ->label('Kabupaten / Kota')
                        ->required()
                        ->reactive()
                        ->options(function (callable $get) {
                            $provinsi = $get('alamatUnit.provinsi');
                            return self::getCitiesByProvince()[$provinsi] ?? [];
                        })
                        ->afterStateUpdated(function (Set $set) {
                            $set('alamatUnit.kecamatan', null);
                        }),

                    Select::make('alamatUnit.kecamatan')
                        ->label('Kecamatan')
                        ->required()
                        ->options(function (callable $get) {
                            $kabupaten = $get('alamatUnit.kabupaten');
                            return self::getKecamatansByCity()[$kabupaten] ?? [];
                        }),

                    Textarea::make('alamatUnit.deskripsi')
                        ->label('Deskripsi Tambahan')
                        ->nullable(),
                ]),

                // STEP 3: DATA KONTRAK
                Step::make('Data Kontrak')
                    ->schema([
                        TextInput::make('nomor_kontrak')
                            ->label('Nomor Kontrak')
                            ->required()
                            ->unique(ignoreRecord: true),

                        DatePicker::make('tanggal_awal_kontrak')
                            ->label('Tanggal Awal Kontrak')
                            ->required(),

                        DatePicker::make('tanggal_akhir_kontrak')
                            ->label('Tanggal Akhir Kontrak')
                            ->required()
                            ->afterOrEqual('tanggal_awal_kontrak'),
                    ]),


                // STEP 3: FOTO KOS
                Step::make('Foto Kos')->schema([
                    FileUpload::make('foto_kos_depan')->label('Foto Tampak Depan')->multiple()->directory('foto_kos/depan')->reorderable(),
                    FileUpload::make('foto_kos_dalam')->label('Foto Dalam Kos')->multiple()->directory('foto_kos/dalam')->reorderable(),
                    FileUpload::make('foto_kos_jalan')->label('Foto Tampak Dari Jalan')->multiple()->directory('foto_kos/jalan')->reorderable(),
                ]),

                // STEP 4: TIPE KAMAR
                Step::make('Tipe Kamar')
                    ->schema([
                        Repeater::make('tipe_kamars')
                            ->label('Daftar Tipe Kamar')
                            ->schema([
                                TextInput::make('id') // UUID disimpan secara eksplisit
                                    ->hidden()
                                    ->default(fn() => (string) Str::uuid()),

                                TextInput::make('nama_tipe')
                                    ->label('Nama Tipe')
                                    ->required(),

                                TextInput::make('ukuran')
                                    ->label('Ukuran')
                                    ->required(),
                            ])
                            ->defaultItems(1)
                            ->minItems(1)
                            ->createItemButtonLabel('Tambah Tipe Kamar')
                            ->visible(fn(Get $get) => $get('multi_tipe') === true)
                            ->reactive(),

                        TextInput::make('nama_tipe')
                            ->label('Nama Tipe Kamar')
                            ->required()
                            ->visible(fn(Get $get) => $get('multi_tipe') === false)
                            ->default('Tipe Kos Umum'),
                    ]),

                // v2 
                Step::make('Fasilitas Kamar')
                    ->schema([
                        // Jika multi_tipe
                        Repeater::make('fasilitas_per_tipe')
                            ->label('Fasilitas per Tipe Kamar')
                            ->visible(fn($livewire) => $livewire->data['multi_tipe'] ?? false)
                            ->schema([
                                Select::make('tipe_kamar_id')
                                    ->label('Tipe Kamar')
                                    ->required()
                                    ->options(function ($livewire) {
                                        return collect($livewire->data['tipe_kamars'] ?? [])
                                            ->mapWithKeys(fn($tipe) => [$tipe['id'] => $tipe['nama_tipe'] ?? 'Tipe'])
                                            ->toArray();
                                    }),

                                CheckboxList::make('fasilitas_umum')
                                    ->label('Fasilitas Umum')
                                    ->options(Fasilitas::where('tipe', 'umum')->pluck('nama', 'id'))
                                    ->columns(2),

                                CheckboxList::make('fasilitas_kamar')
                                    ->label('Fasilitas Kamar')
                                    ->options(Fasilitas::where('tipe', 'kamar')->pluck('nama', 'id'))
                                    ->columns(2),

                                CheckboxList::make('fasilitas_kamar_mandi')
                                    ->label('Fasilitas Kamar Mandi')
                                    ->options(Fasilitas::where('tipe', 'kamar_mandi')->pluck('nama', 'id'))
                                    ->columns(2),

                                CheckboxList::make('fasilitas_parkir')
                                    ->label('Fasilitas Parkir')
                                    ->options(Fasilitas::where('tipe', 'parkir')->pluck('nama', 'id'))
                                    ->columns(2),
                            ])
                            ->columns(1)
                            ->default(function ($livewire) {
                                return collect($livewire->data['tipe_kamars'] ?? [])
                                    ->map(function ($tipe) {
                                        return [
                                            'tipe_kamar_id' => $tipe['id'] ?? null,
                                            'fasilitas_umum' => [],
                                            'fasilitas_kamar' => [],
                                            'fasilitas_kamar_mandi' => [],
                                            'fasilitas_parkir' => [],
                                        ];
                                    })
                                    ->toArray();
                            }),

                        // Jika bukan multi_tipe
                        Group::make()
                            ->visible(fn($livewire) => !($livewire->data['multi_tipe'] ?? false))
                            ->schema([
                                CheckboxList::make('fasilitas_umum')
                                    ->label('Fasilitas Umum')
                                    ->options(Fasilitas::where('tipe', 'umum')->pluck('nama', 'id'))
                                    ->columns(2),

                                CheckboxList::make('fasilitas_kamar')
                                    ->label('Fasilitas Kamar')
                                    ->options(Fasilitas::where('tipe', 'kamar')->pluck('nama', 'id'))
                                    ->columns(2),

                                CheckboxList::make('fasilitas_kamar_mandi')
                                    ->label('Fasilitas Kamar Mandi')
                                    ->options(Fasilitas::where('tipe', 'kamar_mandi')->pluck('nama', 'id'))
                                    ->columns(2),

                                CheckboxList::make('fasilitas_parkir')
                                    ->label('Fasilitas Parkir')
                                    ->options(Fasilitas::where('tipe', 'parkir')->pluck('nama', 'id'))
                                    ->columns(2),
                            ]),
                    ]),

                // STEP 6: HARGA KAMAR
                Step::make('Harga Kamar')
                    ->schema([
                        Repeater::make('harga_per_tipe')
                            ->label('Harga per Tipe Kamar')
                            ->visible(fn($livewire) => $livewire->data['multi_tipe'] ?? false)
                            ->schema([
                                Select::make('tipe_kamar_index')
                                    ->label('Tipe Kamar')
                                    ->required()
                                    ->options(function ($livewire) {
                                        return collect($livewire->data['tipe_kamars'] ?? [])
                                            ->mapWithKeys(fn($tipe, $index) => [$index => $tipe['nama_tipe'] ?? "Tipe #$index"])
                                            ->toArray();
                                    }),

                                TextInput::make('harga_bulanan')
                                    ->label('Harga Bulanan (Rp)')
                                    ->numeric()
                                    ->required(),

                                TextInput::make('harga_mingguan')
                                    ->label('Harga Mingguan (Rp)')
                                    ->numeric()
                                    ->nullable(),

                                TextInput::make('harga_harian')
                                    ->label('Harga Harian (Rp)')
                                    ->numeric()
                                    ->nullable(),
                            ])
                            ->default(function ($livewire) {
                                return collect($livewire->data['tipe_kamars'] ?? [])
                                    ->map(function ($tipe, $index) {
                                        return [
                                            'tipe_kamar_index' => $index,
                                            'harga_bulanan' => null,
                                            'harga_mingguan' => null,
                                            'harga_harian' => null,
                                        ];
                                    })
                                    ->toArray();
                            }),

                        Group::make()
                            ->visible(fn($livewire) => !($livewire->data['multi_tipe'] ?? false))
                            ->schema([
                                TextInput::make('harga_bulanan')
                                    ->label('Harga Bulanan (Rp)')
                                    ->numeric()
                                    ->required(),

                                TextInput::make('harga_mingguan')
                                    ->label('Harga Mingguan (Rp)')
                                    ->numeric()
                                    ->nullable(),

                                TextInput::make('harga_harian')
                                    ->label('Harga Harian (Rp)')
                                    ->numeric()
                                    ->nullable(),
                            ]),
                    ]),


                // STEP 7: KETERSEDIAAN KAMAR
                Step::make('Ketersediaan Kamar')
                    ->schema([
                        Repeater::make('kamars')
                            ->label('Kamar')
                            ->schema([
                                Select::make('tipe_kamar_index') // ganti jadi index, nanti akan di-map di afterCreate
                                    ->label('Tipe Kamar')
                                    ->required()
                                    ->options(
                                        fn($livewire) => collect($livewire->data['tipe_kamars'] ?? [])
                                            ->mapWithKeys(fn($tipe, $index) => [$index => $tipe['nama_tipe'] ?? "Tipe #$index"])
                                    ),
                                TextInput::make('nama')->label('Nama / Nomor Kamar')->required(),
                                TextInput::make('lantai')->label('Lantai')->numeric()->nullable(),
                                TextInput::make('ukuran')->label('Ukuran Kamar')->nullable(),
                                Toggle::make('terisi')->label('Status Terisi')->default(false),
                            ])
                            ->columns(2)
                            ->minItems(1)
                            ->defaultItems(1)
                            ->createItemButtonLabel('Tambah Kamar'),
                    ]),
            ])->columnSpanFull(),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Header Section - Informasi Utama
                Section::make('Informasi Kos')
                    ->description('Data dasar kos dan pemilik')
                    ->icon('heroicon-o-home-modern')
                    ->collapsible()
                    ->schema([
                        TextEntry::make('owner.nama')
                            ->label('Pemilik Kos')
                            ->icon('heroicon-o-user')
                            ->badge()
                            ->color('primary'),

                        TextEntry::make('nama_cluster')
                            ->label('Nama Kos')
                            ->icon('heroicon-o-building-office-2')
                            ->size(TextEntry\TextEntrySize::Large)
                            ->weight('bold'),

                        TextEntry::make('disewakan_untuk')
                            ->label('Disewakan Untuk')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'putra' => 'blue',
                                'putri' => 'pink',
                                'campur' => 'green',
                                default => 'gray',
                            }),

                        TextEntry::make('tahun_dibangun')
                            ->label('Tahun Dibangun')
                            ->icon('heroicon-o-calendar'),

                        TextEntry::make('deskripsi')
                            ->label('Deskripsi')
                            ->columnSpanFull()
                            ->prose()
                            ->placeholder('Tidak ada deskripsi'),
                    ])
                    ->columns(2),

                // Alamat Section
                Section::make('Alamat & Lokasi')
                    ->description('Detail alamat lengkap kos')
                    ->icon('heroicon-o-map-pin')
                    ->collapsible()
                    ->schema([
                        TextEntry::make('alamat.alamat')
                            ->label('Alamat Lengkap')
                            ->icon('heroicon-o-map')
                            ->columnSpanFull(),

                        TextEntry::make('alamat.provinsi')
                            ->label('Provinsi')
                            ->badge(),

                        TextEntry::make('alamat.kabupaten')
                            ->label('Kabupaten/Kota')
                            ->badge(),

                        TextEntry::make('alamat.kecamatan')
                            ->label('Kecamatan')
                            ->badge(),

                        TextEntry::make('alamat.deskripsi')
                            ->label('Deskripsi Lokasi')
                            ->columnSpanFull()
                            ->placeholder('Tidak ada deskripsi tambahan'),
                    ])
                    ->columns(3),

                // Kontrak Section
                Section::make('Informasi Kontrak')
                    ->description('Detail kontrak dan periode sewa')
                    ->icon('heroicon-o-document-text')
                    ->collapsible()
                    ->schema([
                        TextEntry::make('nomor_kontrak')
                            ->label('Nomor Kontrak')
                            ->icon('heroicon-o-hashtag')
                            ->copyable()
                            ->badge()
                            ->color('success'),

                        TextEntry::make('tanggal_awal_kontrak')
                            ->label('Tanggal Mulai')
                            ->date()
                            ->icon('heroicon-o-play'),

                        TextEntry::make('tanggal_akhir_kontrak')
                            ->label('Tanggal Berakhir')
                            ->date()
                            ->icon('heroicon-o-stop'),
                    ])
                    ->columns(3),

                // Galeri Foto Section
                Section::make('Galeri Foto Kos')
                    ->description('Dokumentasi visual kos')
                    ->icon('heroicon-o-photo')
                    ->collapsible()
                    ->schema([
                        ImageEntry::make('fotoUnit_depan')
                            ->label('Foto Tampak Depan')
                            ->getStateUsing(
                                fn(Unit $record) =>
                                $record->fotoUnit
                                    ->where('kategori', 'depan')
                                    ->pluck('path')
                                    ->map(fn($path) => asset('storage/' . $path))
                                    ->toArray()
                            )
                            ->height('200px')
                            ->columnSpanFull(),

                        ImageEntry::make('fotoUnit_dalam')
                            ->label('Foto Interior Kos')
                            ->getStateUsing(
                                fn(Unit $record) =>
                                $record->fotoUnit
                                    ->where('kategori', 'dalam')
                                    ->pluck('path')
                                    ->map(fn($path) => asset('storage/' . $path))
                                    ->toArray()
                            )
                            ->height('200px')
                            ->columnSpanFull(),

                        ImageEntry::make('fotoUnit_jalan')
                            ->label('Foto Tampak dari Jalan')
                            ->getStateUsing(
                                fn(Unit $record) =>
                                $record->fotoUnit
                                    ->where('kategori', 'jalan')
                                    ->pluck('path')
                                    ->map(fn($path) => asset('storage/' . $path))
                                    ->toArray()
                            )
                            ->height('200px')
                            ->columnSpanFull(),
                    ]),

                // Detail Tipe Kamar Section - MENGGUNAKAN KETERSEDIAAN_KAMARS
                Section::make('Detail Tipe Kamar')
                    ->description('Informasi lengkap setiap tipe kamar')
                    ->icon('heroicon-o-squares-2x2')
                    ->schema(
                        fn(Unit $record) =>
                        $record->tipeKamars->map(function ($tipeKamar, $index) {
                            return Section::make("Tipe Kamar: {$tipeKamar->nama_tipe}")
                                ->description("Detail lengkap untuk {$tipeKamar->nama_tipe}")
                                ->icon('heroicon-o-home')
                                ->collapsible()
                                ->collapsed($index > 0)
                                ->schema([
                                    // Informasi Harga
                                    Section::make('Informasi Harga')
                                        ->schema([
                                            TextEntry::make('harga_perbulan')
                                                ->label('Harga per Bulan')
                                                ->getStateUsing(fn() => $tipeKamar->hargaKamars?->harga_perbulan)
                                                ->money('IDR')
                                                ->icon('heroicon-o-banknotes')
                                                ->color('success')
                                                ->size(TextEntry\TextEntrySize::Large)
                                                ->weight('bold')
                                                ->placeholder('Harga belum ditetapkan'),

                                            TextEntry::make('minimal_deposit')
                                                ->label('Minimal Deposit')
                                                ->getStateUsing(fn() => $tipeKamar->hargaKamars?->minimal_deposit)
                                                ->money('IDR')
                                                ->color('warning')
                                                ->placeholder('Deposit belum ditetapkan'),
                                        ])
                                        ->columns(2)
                                        ->columnSpanFull(),

                                    // Fasilitas
                                    TextEntry::make('fasilitas_tipe')
                                        ->label('Fasilitas Tersedia')
                                        ->getStateUsing(
                                            fn() =>
                                            $tipeKamar->fasilitas && $tipeKamar->fasilitas->count() > 0
                                                ? $tipeKamar->fasilitas->pluck('nama')->map(fn($nama) => "✓ {$nama}")->implode(', ')
                                                : 'Belum ada fasilitas'
                                        )
                                        ->prose()
                                        ->columnSpanFull(),

                                    // Daftar Kamar - MENGGUNAKAN KETERSEDIAAN_KAMARS
                                    Section::make('Daftar Kamar')
                                        ->description('Status ketersediaan setiap kamar')
                                        ->schema([
                                            ...$tipeKamar->ketersediaanKamars?->map(function ($ketersediaanKamar) use ($tipeKamar) {
                                                // Ambil data kamar melalui relasi
                                                $kamar = $ketersediaanKamar->kamar;

                                                return Section::make("Kamar: {$kamar->nama}")
                                                    ->schema([
                                                        // Ukuran Kamar - dari model Kamar
                                                        TextEntry::make('ukuran_kamar')
                                                            ->label('Ukuran')
                                                            ->getStateUsing(fn() => $kamar->ukuran ?? 'Tidak ditentukan')
                                                            ->icon('heroicon-o-arrows-pointing-out')
                                                            ->badge()
                                                            ->color('info'),

                                                        // Lantai Kamar - dari model Kamar
                                                        TextEntry::make('lantai_kamar')
                                                            ->label('Lantai')
                                                            ->getStateUsing(fn() => $kamar->lantai ? "Lantai {$kamar->lantai}" : 'Tidak ditentukan')
                                                            ->icon('heroicon-o-building-office')
                                                            ->badge(),

                                                        // STATUS KAMAR - dari model KetersediaanKamar
                                                        TextEntry::make('status_ketersediaan_individual')
                                                            ->label('Status Ketersediaan')
                                                            ->getStateUsing(function () use ($ketersediaanKamar) {
                                                                $status = $ketersediaanKamar->status ?? null;

                                                                return match ($status) {
                                                                    'kosong' => 'Tersedia',
                                                                    'terisi' => 'Terisi',
                                                                    'booked' => 'Dipesan',
                                                                    default => 'Tidak diketahui',
                                                                };
                                                            })
                                                            ->badge()
                                                            ->color(function () use ($ketersediaanKamar) {
                                                                return match ($ketersediaanKamar->status ?? null) {
                                                                    'kosong' => 'success',
                                                                    'terisi' => 'danger',
                                                                    'booked' => 'warning',
                                                                    default => 'gray',
                                                                };
                                                            })
                                                            ->icon(function () use ($ketersediaanKamar) {
                                                                return match ($ketersediaanKamar->status ?? null) {
                                                                    'kosong' => 'heroicon-o-check-circle',
                                                                    'terisi' => 'heroicon-o-x-circle',
                                                                    'booked' => 'heroicon-o-clock',
                                                                    default => 'heroicon-o-question-mark-circle',
                                                                };
                                                            }),
                                                    ])
                                                    ->columns(3)
                                                    ->compact();
                                            })?->toArray() ?? [
                                                TextEntry::make('no_rooms_available')
                                                    ->label('Status')
                                                    ->getStateUsing(fn() => 'Belum ada kamar yang terdaftar untuk tipe ini')
                                                    ->color('warning')
                                                    ->columnSpanFull()
                                            ]
                                        ])
                                        ->columnSpanFull(),

                                    // RINGKASAN PER TIPE KAMAR
                                    Section::make('Ringkasan Tipe Kamar')
                                        ->schema([
                                            TextEntry::make('total_kamar_tipe')
                                                ->label('Total Kamar')
                                                ->getStateUsing(function () use ($tipeKamar) {
                                                    $total = $tipeKamar->ketersediaanKamars?->count() ?? 0;
                                                    return $total . ' kamar';
                                                })
                                                ->badge()
                                                ->color('primary')
                                                ->icon('heroicon-o-home'),

                                            TextEntry::make('kamar_tersedia_tipe')
                                                ->label('Tersedia')
                                                ->getStateUsing(function () use ($tipeKamar) {
                                                    $tersedia = $tipeKamar->ketersediaanKamars?->where('status', 'kosong')->count() ?? 0;
                                                    return $tersedia . ' kamar';
                                                })
                                                ->badge()
                                                ->color('success')
                                                ->icon('heroicon-o-check-circle'),

                                            TextEntry::make('kamar_dipesan_tipe')
                                                ->label('Dipesan')
                                                ->getStateUsing(function () use ($tipeKamar) {
                                                    $dipesan = $tipeKamar->ketersediaanKamars?->where('status', 'booked')->count() ?? 0;
                                                    return $dipesan . ' kamar';
                                                })
                                                ->badge()
                                                ->color('warning')
                                                ->icon('heroicon-o-clock'),

                                            TextEntry::make('kamar_terisi_tipe')
                                                ->label('Terisi')
                                                ->getStateUsing(function () use ($tipeKamar) {
                                                    $terisi = $tipeKamar->ketersediaanKamars?->where('status', 'terisi')->count() ?? 0;
                                                    return $terisi . ' kamar';
                                                })
                                                ->badge()
                                                ->color('danger')
                                                ->icon('heroicon-o-x-circle'),

                                            TextEntry::make('tingkat_hunian_tipe')
                                                ->label('Tingkat Hunian')
                                                ->getStateUsing(function () use ($tipeKamar) {
                                                    $total = $tipeKamar->ketersediaanKamars?->count() ?? 0;
                                                    $terisi = $tipeKamar->ketersediaanKamars?->where('status', 'terisi')->count() ?? 0;

                                                    if ($total === 0) return '0%';

                                                    $persentase = round(($terisi / $total) * 100, 1);
                                                    return "{$persentase}%";
                                                })
                                                ->badge()
                                                ->color(function () use ($tipeKamar) {
                                                    $total = $tipeKamar->ketersediaanKamars?->count() ?? 0;
                                                    $terisi = $tipeKamar->ketersediaanKamars?->where('status', 'terisi')->count() ?? 0;

                                                    if ($total === 0) return 'gray';

                                                    $persentase = ($terisi / $total) * 100;

                                                    if ($persentase >= 80) return 'success';
                                                    if ($persentase >= 50) return 'warning';
                                                    return 'danger';
                                                })
                                                ->icon('heroicon-o-chart-bar'),
                                        ])
                                        ->columns(5)
                                        ->columnSpanFull(),
                                ])
                                ->columns(1);
                        })->toArray()
                    ),

                // RINGKASAN KOS KESELURUHAN
                Section::make('Ringkasan Kos')
                    ->description('Statistik keseluruhan kos')
                    ->icon('heroicon-o-chart-bar')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        TextEntry::make('total_tipe_kamar')
                            ->label('Total Tipe Kamar')
                            ->getStateUsing(fn(Unit $record) => $record->tipeKamars->count() . ' tipe')
                            ->badge()
                            ->color('info')
                            ->icon('heroicon-o-squares-2x2'),

                        TextEntry::make('total_kamar_kos')
                            ->label('Total Kamar')
                            ->getStateUsing(function (Unit $record) {
                                $total = 0;
                                foreach ($record->tipeKamars as $tipe) {
                                    $total += $tipe->ketersediaanKamars?->count() ?? 0;
                                }
                                return $total . ' kamar';
                            })
                            ->badge()
                            ->color('primary')
                            ->icon('heroicon-o-home'),

                        TextEntry::make('kamar_tersedia_kos')
                            ->label('Kamar Tersedia')
                            ->getStateUsing(function (Unit $record) {
                                $tersedia = 0;
                                foreach ($record->tipeKamars as $tipe) {
                                    $tersedia += $tipe->ketersediaanKamars?->where('status', 'kosong')->count() ?? 0;
                                }
                                return $tersedia . ' kamar';
                            })
                            ->badge()
                            ->color('success')
                            ->icon('heroicon-o-check-circle'),

                        TextEntry::make('kamar_dipesan_kos')
                            ->label('Kamar Dipesan')
                            ->getStateUsing(function (Unit $record) {
                                $dipesan = 0;
                                foreach ($record->tipeKamars as $tipe) {
                                    $dipesan += $tipe->ketersediaanKamars?->where('status', 'booked')->count() ?? 0;
                                }
                                return $dipesan . ' kamar';
                            })
                            ->badge()
                            ->color('warning')
                            ->icon('heroicon-o-clock'),

                        TextEntry::make('kamar_terisi_kos')
                            ->label('Kamar Terisi')
                            ->getStateUsing(function (Unit $record) {
                                $terisi = 0;
                                foreach ($record->tipeKamars as $tipe) {
                                    $terisi += $tipe->ketersediaanKamars?->where('status', 'terisi')->count() ?? 0;
                                }
                                return $terisi . ' kamar';
                            })
                            ->badge()
                            ->color('danger')
                            ->icon('heroicon-o-x-circle'),

                        TextEntry::make('tingkat_hunian_kos')
                            ->label('Tingkat Hunian')
                            ->getStateUsing(function (Unit $record) {
                                $total = 0;
                                $terisi = 0;

                                foreach ($record->tipeKamars as $tipe) {
                                    $total += $tipe->ketersediaanKamars?->count() ?? 0;
                                    $terisi += $tipe->ketersediaanKamars?->where('status', 'terisi')->count() ?? 0;
                                }

                                if ($total === 0) return '0%';

                                $persentase = round(($terisi / $total) * 100, 1);
                                return "{$persentase}%";
                            })
                            ->badge()
                            ->color(function (Unit $record) {
                                $total = 0;
                                $terisi = 0;

                                foreach ($record->tipeKamars as $tipe) {
                                    $total += $tipe->ketersediaanKamars?->count() ?? 0;
                                    $terisi += $tipe->ketersediaanKamars?->where('status', 'terisi')->count() ?? 0;
                                }

                                if ($total === 0) return 'gray';

                                $persentase = ($terisi / $total) * 100;

                                if ($persentase >= 80) return 'success';
                                if ($persentase >= 50) return 'warning';
                                return 'danger';
                            })
                            ->icon('heroicon-o-chart-bar'),

                        TextEntry::make('tingkat_ketersediaan_kos')
                            ->label('Tingkat Ketersediaan')
                            ->getStateUsing(function (Unit $record) {
                                $total = 0;
                                $tersedia = 0;

                                foreach ($record->tipeKamars as $tipe) {
                                    $total += $tipe->ketersediaanKamars?->count() ?? 0;
                                    $tersedia += $tipe->ketersediaanKamars?->where('status', 'kosong')->count() ?? 0;
                                }

                                if ($total === 0) return '0%';

                                $persentase = round(($tersedia / $total) * 100, 1);
                                return "{$persentase}%";
                            })
                            ->badge()
                            ->color(function (Unit $record) {
                                $total = 0;
                                $tersedia = 0;

                                foreach ($record->tipeKamars as $tipe) {
                                    $total += $tipe->ketersediaanKamars?->count() ?? 0;
                                    $tersedia += $tipe->ketersediaanKamars?->where('status', 'kosong')->count() ?? 0;
                                }

                                if ($total === 0) return 'gray';

                                $persentase = ($tersedia / $total) * 100;

                                if ($persentase >= 50) return 'success';
                                if ($persentase >= 20) return 'warning';
                                return 'danger';
                            })
                            ->icon('heroicon-o-home-modern'),
                    ])
                    ->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_cluster')->label('Nama Kos'),
                Tables\Columns\BadgeColumn::make('disewakan_untuk')->colors([
                    'primary' => 'putra',
                    'warning' => 'putri',
                    'success' => 'campur',
                ]),
                Tables\Columns\IconColumn::make('multi_tipe')->boolean(),
                Tables\Columns\TextColumn::make('tahun_dibangun'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUnits::route('/'),
            'create' => Pages\CreateUnit::route('/create'),
            'edit' => Pages\EditUnit::route('/{record}/edit'),
            'view' => Pages\ViewUnit::route('/{record}/view'),
        ];
    }

    protected static function getProvinces(): array
    {
        return [
            'dki_jakarta' => 'DKI Jakarta',
            'banten' => 'Banten',
        ];
    }


    protected static function getCitiesByProvince(): array
    {
        return [
            'dki_jakarta' => [
                'jakarta_pusat'   => 'Jakarta Pusat',
                'jakarta_utara'   => 'Jakarta Utara',
                'jakarta_selatan' => 'Jakarta Selatan',
                'jakarta_timur'   => 'Jakarta Timur',
                'jakarta_barat'   => 'Jakarta Barat',
            ],
            'banten' => [
                'kota_tangerang'       => 'Kota Tangerang',
                'tangerang_selatan'    => 'Tangerang Selatan',
                'kabupaten_tangerang'  => 'Kabupaten Tangerang',
            ],
        ];
    }


    protected static function getKecamatansByCity(): array
    {
        return [
            // Jakarta Utara
            'jakarta_utara' => [
                'cilincing' => 'Cilincing',
                'koja' => 'Koja',
                'kelapa_gading' => 'Kelapa Gading',
                'penjaringan' => 'Penjaringan',
                'pademangan' => 'Pademangan',
                'tanjung_priok' => 'Tanjung Priok',
            ],
            // Jakarta Timur
            'jakarta_timur' => [
                'cakung' => 'Cakung',
                'cililitan' => 'Cililitan',
                'cipayung' => 'Cipayung',
                'ciracas' => 'Ciracas',
                'duren_sawit' => 'Duren Sawit',
                'jatinegara' => 'Jatinegara',
                'kramat_jati' => 'Kramat Jati',
                'makasar' => 'Makasar',
                'pasar_rebo' => 'Pasar Rebo',
                'pulo_gadung' => 'Pulo Gadung',
            ],
            // Jakarta Pusat
            'jakarta_pusat' => [
                'cempaka_putih' => 'Cempaka Putih',
                'gambir' => 'Gambir',
                'kemayoran' => 'Kemayoran',
                'menteng' => 'Menteng',
                'sawah_besar' => 'Sawah Besar',
                'senen' => 'Senen',
                'tanah_abang' => 'Tanah Abang',
                'johar_baru' => 'Johar Baru',
            ],
            // Jakarta Selatan
            'jakarta_selatan' => [
                'kebayoran_baru' => 'Kebayoran Baru',
                'tebet'           => 'Tebet',
                'pasar_minggu'    => 'Pasar Minggu',
                'cilandak'        => 'Cilandak',
            ],
            // Jakarta Barat
            'jakarta_barat' => [
                'cengkareng' => 'Cengkareng',
                'kalideres'  => 'Kalideres',
                'kebon_jeruk' => 'Kebon Jeruk',
            ],
            // Tangerang Selatan
            'tangerang_selatan' => [
                'ciputat'   => 'Ciputat',
                'serpong'   => 'Serpong',
                'pamulang'  => 'Pamulang',
                'pondok_aren' => 'Pondok Aren',
            ],
            // Kota Tangerang
            'kota_tangerang' => [
                'cipondoh' => 'Cipondoh',
                'karawaci' => 'Karawaci',
                'ciledug'  => 'Ciledug',
            ],
            // Kabupaten Tangerang
            'kabupaten_tangerang' => [
                'cisauk'    => 'Cisauk',
                'curug'     => 'Curug',
                'legok'     => 'Legok',
            ]
        ];
    }
}
