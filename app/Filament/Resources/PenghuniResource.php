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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
