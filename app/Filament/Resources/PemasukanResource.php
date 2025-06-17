<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PemasukanResource\Pages;
use App\Filament\Resources\PemasukanResource\RelationManagers;
use App\Models\Pemasukan;
use App\Models\Kamar;
use App\Models\LogPenghuni;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;
use Carbon\Carbon;

class PemasukanResource extends Resource
{
    protected static ?string $model = Pemasukan::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Keuangan';
    protected static ?string $modelLabel = 'Pemasukan';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['Owner', 'Superadmin']);
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('is_checkin')
                    ->label('Apakah ini check-in?')
                    ->reactive()
                    ->default(false),

                // Jika BUKAN check-in → tampilkan tanggal manual
                DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->hidden(fn(Get $get) => $get('is_checkin'))
                    ->required(fn(Get $get) => ! $get('is_checkin')),

                // Jika check-in → tampilkan unit
                Select::make('unit_id')
                    ->label('Unit')
                    ->relationship('unit', 'nama_cluster')
                    ->searchable()
                    ->required(fn(Get $get) => ! $get('is_checkin') || $get('is_checkin')),


                // Jika check-in → tampilkan kamar
                Select::make('kamar_id')
                    ->label('Kamar')
                    ->options(
                        fn(Get $get) =>
                        Kamar::where('unit_id', $get('unit_id'))->pluck('nama', 'id')
                    )
                    ->relationship('kamar', 'nama')
                    ->hidden(fn(Get $get) => ! $get('is_checkin')),

                // Jika check-in → pilih log check-in
                Select::make('checkin_id')
                    ->label('Check-In')
                    ->options(
                        fn(Get $get) =>
                        LogPenghuni::with(['penghuni', 'kamar'])
                            ->where('kamar_id', $get('kamar_id'))
                            ->where('status', 'checkin')
                            ->get()
                            ->mapWithKeys(fn($log) => [
                                $log->id => $log->penghuni->nama . ' - ' . $log->kamar->nama
                            ])
                    )
                    ->searchable()
                    ->hidden(fn(Get $get) => ! $get('is_checkin'))
                    ->required(fn(Get $get) => $get('is_checkin'))
                    ->reactive()
                    ->afterStateUpdated(function ($state, Set $set) {
                        $log = LogPenghuni::with('kamar.hargaKamar')->find($state);

                        if ($log) {
                            $harga = $log->kamar?->hargaKamar?->harga_perbulan;
                            $tanggalMasuk = Carbon::parse($log->tanggal_masuk)->format('Y-m-d');

                            $set('jumlah', $harga);
                            $set('jumlah_asli', $harga);
                            $set('tanggal', $tanggalMasuk);
                        } else {
                            $set('jumlah', null);
                            $set('jumlah_asli', null);
                            $set('tanggal', null);
                        }
                    }),

                TextInput::make('jumlah')
                    ->label('Jumlah (Rp)')
                    ->numeric()
                    ->reactive()
                    ->disabled(fn(Get $get) => $get('is_checkin'))
                    ->dehydrated()
                    ->formatStateUsing(fn($state) => $state ? 'Rp ' . number_format((float) $state, 0, ',', '.') : null),

                Hidden::make('jumlah_asli')
                    ->dehydrated()
                    ->default(fn(Get $get) => static::getHargaFromCheckin($get('checkin_id'))),

                // Tanggal Masuk untuk check-in
                DatePicker::make('tanggal')
                    ->label('Tanggal Masuk')
                    ->reactive()
                    ->disabled()
                    ->dehydrated()
                    ->visible(fn(Get $get) => $get('is_checkin')),

                Textarea::make('deskripsi')
                    ->label('Deskripsi'),

                FileUpload::make('bukti')
                    ->label('Upload Bukti Pembayaran')
                    ->image()
                    ->directory('bukti-pemasukan')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('unit.nama_cluster')
                    ->label('Unit')
                    ->sortable()
                    ->searchable()
                    ->default('-'),

                Tables\Columns\TextColumn::make('penghuni.nama')
                    ->label('Penghuni')
                    ->sortable()
                    ->formatStateUsing(fn($state) => $state ?? 'Pemasukan bukan checkin'),

                Tables\Columns\TextColumn::make('jumlah')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_konfirmasi')
                    ->boolean()
                    ->label('Terkonfirmasi'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_konfirmasi')
                    ->label('Status Konfirmasi'),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPemasukans::route('/'),
            'create' => Pages\CreatePemasukan::route('/create'),
            'edit' => Pages\EditPemasukan::route('/{record}/edit'),
        ];
    }

    protected static function getHargaFromCheckin(?string $checkinId): ?float
    {
        if (!$checkinId) return null;

        $log = LogPenghuni::with('kamar.hargaKamar')->find($checkinId);

        return $log?->kamar?->hargaKamar?->harga_perbulan;
    }


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['checkin_id']) {
            $data['jumlah'] = static::getHargaFromCheckin($data['checkin_id']);
        }

        return $data;
    }
}
