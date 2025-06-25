<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenghuniResource\Pages;
use App\Filament\Resources\PenghuniResource\RelationManagers;
use App\Models\Penghuni;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenghuniResource extends Resource
{
    protected static ?string $model = Penghuni::class;

    protected static ?string $navigationLabel = 'Data Penghuni';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    // QUERY BARU
    // public static function getEloquentQuery(): Builder
    // {
    //     $query = parent::getEloquentQuery()
    //         ->with(['logs.kamar.unit']) // eager load relasi
    //         ->where('status', 'In'); // status dari table penghuni

    //     if (auth()->user()?->hasRole('Owner')) {
    //         $owner = \App\Models\Owner::where('user_id', auth()->id())->first();

    //         if ($owner) {
    //             $query->whereHas('logs', function ($logQuery) use ($owner) {
    //                 $logQuery->where('status', 'checkin')
    //                     ->whereHas('kamar.unit', function ($unitQuery) use ($owner) {
    //                         $unitQuery->where('id_owner', $owner->id)
    //                             ->where('status', true); // hanya unit aktif
    //                     })
    //                     ->whereIn('id', function ($subQuery) {
    //                         // subquery untuk ambil log terbaru per penghuni
    //                         $subQuery->selectRaw('MAX(id)')
    //                             ->from('log_penghunis')
    //                             ->groupBy('penghuni_id');
    //                     });
    //             });
    //         } else {
    //             $query->whereRaw('0=1');
    //         }
    //     }

    //     return $query;
    // }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['logs.kamar.unit'])
            ->where('status', 'In'); // hanya penghuni yang masih menempati kamar

        if (auth()->user()?->hasRole('Owner')) {
            $owner = \App\Models\Owner::where('user_id', auth()->id())->first();

            if ($owner) {
                $query->whereHas('logs', function ($q) use ($owner) {
                    $q->where('status', 'checkin') // log terakhir harus checkin
                        ->whereHas('kamar.unit', function ($q2) use ($owner) {
                            $q2->where('id_owner', $owner->id)
                                ->where('status', true); // unit aktif
                        });
                });
            } else {
                $query->whereRaw('0=1');
            }
        }

        return $query;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode')
                    ->required()
                    ->unique()
                    ->label('ID Penghuni'),
                TextInput::make('nama')
                    ->required()
                    ->label('Nama Lengkap'),
                TextInput::make('tempat_lahir')
                    ->required()
                    ->label('Tempat Lahir'),
                DatePicker::make('tanggal_lahir')
                    ->required()
                    ->label('Tanggal Lahir')
                    ->maxDate(now())
                    ->minDate(now()->subYears(100)),
                Select::make('agama')
                    ->required()
                    ->options([
                        'Islam' => 'Islam',
                        'Kristen' => 'Kristen',
                        'Katolik' => 'Katolik',
                        'Hindu' => 'Hindu',
                        'Buddha' => 'Buddha',
                        'Konghucu' => 'Konghucu',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->label('Agama'),
                TextInput::make('no_telp')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->placeholder('Masukkan nomor telepon')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),
                TextInput::make('kontak_darurat')
                    ->label('Kontak Darurat')
                    ->required(),
                Select::make('hubungan_kontak_darurat')
                    ->options([
                        'Orang Tua' => 'Orang Tua',
                        'Saudara' => 'Saudara',
                        'Teman' => 'Teman',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->label('Hubungan Kontak Darurat'),
                TextInput::make('kendaraan')
                    ->label('Plat Kendaraan')
                    ->placeholder('Masukkan plat kendaraan jika ada'),
                FileUpload::make('foto_ktp')
                    ->label('Foto KTP')
                    ->required()
                    ->disk('public')
                    ->directory('penghuni/ktp')
                    ->image(),
                Hidden::make('status')->default('Out'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')->searchable(),
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('logs.kamar.nama')
                    ->label('Kamar Terakhir')
                    ->formatStateUsing(function ($state, $record) {
                        $logTerakhir = $record->logs()->latest('tanggal')->first();
                        if (!$logTerakhir) return '-';

                        $unitName = $logTerakhir->kamar?->unit?->nama_cluster ?? '-';
                        $kamarName = $logTerakhir->kamar?->nama ?? '-';

                        return "{$unitName} - {$kamarName}";
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn() => auth()->user()->hasRole('Superadmin')),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn() => auth()->user()->hasRole('Superadmin')),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPenghunis::route('/'),
            'create' => Pages\CreatePenghuni::route('/create'),
            'edit' => Pages\EditPenghuni::route('/{record}/edit'),
        ];
    }
}
