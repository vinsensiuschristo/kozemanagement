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


class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationGroup = 'Manajemen Kos';

    protected array $fasilitasTerpilih = [];


    public static function form(Form $form): Form
    {
        return $form->schema([
            Wizard::make([
                // STEP 1: DATA KOS
                Step::make('Data Kos')->schema([
                    Select::make('id_owner')
                        ->label('Pemilik Kos')
                        ->relationship('owner', 'nama') // Asumsikan relasi Unit → Owner sudah diset dengan relasi `owner()`
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

                // STEP 4: FASILITAS
                Step::make('Fasilitas Kos')
                    ->schema([
                        Forms\Components\Section::make('Fasilitas Umum')
                            ->schema([
                                Forms\Components\CheckboxList::make('fasilitas_umum')
                                    ->label('Pilih Fasilitas Umum')
                                    ->options(Fasilitas::where('tipe', 'umum')->pluck('nama', 'id'))
                                    ->columnSpanFull()
                            ]),

                        Forms\Components\Section::make('Fasilitas Kamar')
                            ->schema([
                                Forms\Components\CheckboxList::make('fasilitas_kamar')
                                    ->label('Pilih Fasilitas Kamar')
                                    ->options(Fasilitas::where('tipe', 'kamar')->pluck('nama', 'id'))
                                    ->columnSpanFull()
                            ]),

                        Forms\Components\Section::make('Fasilitas Kamar Mandi')
                            ->schema([
                                Forms\Components\CheckboxList::make('fasilitas_kamar_mandi')
                                    ->label('Pilih Fasilitas Kamar Mandi')
                                    ->options(Fasilitas::where('tipe', 'kamar_mandi')->pluck('nama', 'id'))
                                    ->columnSpanFull()
                            ]),

                        Forms\Components\Section::make('Fasilitas Parkir')
                            ->schema([
                                Forms\Components\CheckboxList::make('fasilitas_parkir')
                                    ->label('Pilih Fasilitas Parkir')
                                    ->options(Fasilitas::where('tipe', 'parkir')->pluck('nama', 'id'))
                                    ->columnSpanFull()
                            ]),
                    ]),

                // STEP 5: TIPE KAMAR

                Step::make('Tipe Kamar')
                    ->visible(fn(Get $get) => $get('multi_tipe') === true)
                    ->schema([
                        TextInput::make('tipe_awal')
                            ->label('Nama Tipe')
                            ->required(),
                    ]),

                // STEP 6: KETERSEDIAAN KAMAR
                Step::make('Ketersediaan Kamar')->schema([
                    Repeater::make('kamars')
                        ->label('Detail Kamar')
                        ->relationship('kamars') // Hubungkan langsung ke relasi di model
                        ->schema([
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



                // STEP 7: HARGA KAMAR
                Step::make('Harga Kamar')
                    ->schema([
                        Repeater::make('harga_kamars')
                            ->label('Harga Kamar')
                            ->visible(fn(Get $get) => $get('multi_tipe') === true || $get('multi_tipe') === false)
                            ->schema([
                                TextInput::make('harga_perbulan')
                                    ->label('Harga Perbulan')
                                    ->numeric()
                                    ->required(),

                                TextInput::make('minimal_deposit')
                                    ->label('Minimal Deposit')
                                    ->numeric()
                                    ->nullable(),
                            ])
                            ->defaultItems(1)
                            ->minItems(1),
                    ]),


            ])->columnSpanFull(),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Kos')
                    ->schema([
                        TextEntry::make('nama_cluster')->label('Nama Kos'),
                        TextEntry::make('disewakan_untuk')->label('Disewakan Untuk'),
                        TextEntry::make('tahun_dibangun')->label('Tahun Dibangun'),
                    ]),

                Section::make('Alamat Kos')
                    ->schema([
                        TextEntry::make('alamat.alamat')->label('Alamat'),
                        TextEntry::make('alamat.provinsi')->label('Provinsi'),
                        TextEntry::make('alamat.kabupaten')->label('Kabupaten / Kota'),
                        TextEntry::make('alamat.kecamatan')->label('Kecamatan'),
                    ]),

                Section::make('Data Kontrak')
                    ->schema([
                        TextEntry::make('nomor_kontrak')->label('Nomor Kontrak'),
                        TextEntry::make('tanggal_awal_kontrak')->label('Tanggal Awal Kontrak'),
                        TextEntry::make('tanggal_akhir_kontrak')->label('Tanggal Akhir Kontrak'),
                    ]),

                Section::make('Fasilitas Kos')
                    ->schema([
                        TextEntry::make('fasilitasUnits.fasilitas.nama')->label('Fasilitas Umum')
                            ->getStateUsing(fn(Unit $record) => $record->fasilitasUnits->where('fasilitas.tipe', 'umum')->pluck('fasilitas.nama')->implode(', ')),
                        TextEntry::make('fasilitasUnits.fasilitas.nama')->label('Fasilitas Kamar')
                            ->getStateUsing(fn(Unit $record) => $record->fasilitasUnits->where('fasilitas.tipe', 'kamar')->pluck('fasilitas.nama')->implode(', ')),
                        TextEntry::make('fasilitasUnits.fasilitas.nama')->label('Fasilitas Kamar Mandi')
                            ->getStateUsing(fn(Unit $record) => $record->fasilitasUnits->where('fasilitas.tipe', 'kamar_mandi')->pluck('fasilitas.nama')->implode(', ')),
                        TextEntry::make('fasilitasUnits.fasilitas.nama')->label('Fasilitas Parkir')
                            ->getStateUsing(fn(Unit $record) => $record->fasilitasUnits->where('fasilitas.tipe', 'parkir')->pluck('fasilitas.nama')->implode(', ')),
                    ]),

                Section::make('Foto Kos')
                    ->schema([
                        ImageEntry::make('fotoUnit_depan')
                            ->label('Foto Kos Depan')
                            ->getStateUsing(
                                fn(Unit $record) =>
                                $record->fotoUnit
                                    ->where('kategori', 'depan')
                                    ->pluck('path')
                                    ->map(fn($path) => asset('storage/' . $path))
                                    ->toArray()
                            )
                            ->height('300px')
                            ->columnSpanFull(),

                        ImageEntry::make('fotoUnit_dalam')
                            ->label('Foto Kos Dalam')
                            ->getStateUsing(
                                fn(Unit $record) =>
                                $record->fotoUnit
                                    ->where('kategori', 'dalam')
                                    ->pluck('path')
                                    ->map(fn($path) => asset('storage/' . $path))
                                    ->toArray()
                            )
                            ->height('300px')
                            ->columnSpanFull(),

                        ImageEntry::make('fotoUnit_jalan')
                            ->label('Foto Kos Jalan')
                            ->getStateUsing(
                                fn(Unit $record) =>
                                $record->fotoUnit
                                    ->where('kategori', 'jalan')
                                    ->pluck('path')
                                    ->map(fn($path) => asset('storage/' . $path))
                                    ->toArray()
                            )
                            ->height('300px')
                            ->columnSpanFull(),
                    ]),

                Section::make('Tipe Kamar')
                    ->schema([
                        TextEntry::make('tipeKamars.nama_tipe')->label('Nama Tipe Kamar')
                            ->getStateUsing(fn(Unit $record) => $record->tipeKamars->pluck('nama_tipe')->implode(', ')),
                    ]),

                Section::make('Ketersediaan Kamar')
                    ->schema(
                        fn(Unit $record) =>
                        $record->kamars->map(function ($kamar) {
                            return Fieldset::make('Kamar ' . $kamar->nama)
                                ->schema([
                                    TextEntry::make('lantai')
                                        ->label('Lantai')
                                        ->default($kamar->lantai),
                                    TextEntry::make('ukuran')
                                        ->label('Ukuran Kamar')
                                        ->default($kamar->ukuran),
                                    TextEntry::make('terisi')
                                        ->label('Status Terisi')
                                        ->default($kamar->terisi ? 'Terisi' : 'Kosong'),
                                ])
                                ->columns(2);
                        })->toArray()
                    ),

                Section::make('Harga Kamar')
                    ->schema([
                        TextEntry::make('harga_kamars.harga_perbulan')
                            ->label('Harga Perbulan')
                            ->getStateUsing(function (Unit $record) {
                                return $record->hargaKamars
                                    ->pluck('harga_perbulan')
                                    ->map(fn($harga) => 'Rp ' . number_format($harga, 0, ',', '.'))
                                    ->implode(', ');
                            }),

                        TextEntry::make('harga_kamars.minimal_deposit')
                            ->label('Minimal Deposit')
                            ->getStateUsing(function (Unit $record) {
                                return $record->hargaKamars
                                    ->pluck('minimal_deposit')
                                    ->map(fn($deposit) => 'Rp ' . number_format($deposit, 0, ',', '.'))
                                    ->implode(', ');
                            }),

                    ]),
            ])->columns([
                'sm' => 1,
                'md' => 2,
                'lg' => 3,
                'xl' => 4,
            ]);
        // ]);
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
