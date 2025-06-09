<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OwnerResource\Pages;
use App\Filament\Resources\OwnerResource\RelationManagers;
use App\Models\Owner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TextFilter;
use Illuminate\Support\Str;

class OwnerResource extends Resource
{
    protected static ?string $model = Owner::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'SuperAdmin';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('Superadmin');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User terkait')
                    ->relationship('user', 'name')
                    ->required(),

                Forms\Components\TextInput::make('nama')
                    ->label('Nama')
                    ->required()
                    ->unique(ignorable: fn(?Owner $record) => $record)
                    ->maxLength(50),
                Forms\Components\TextInput::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->nullable()
                    ->maxLength(50)
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->nullable()
                    ->minDate(now()->subYears(100))
                    ->maxDate(now()),
                Forms\Components\Select::make('agama')
                    ->label('Agama')
                    ->options([
                        'Islam' => 'Islam',
                        'Kristen' => 'Kristen',
                        'Katolik' => 'Katolik',
                        'Hindu' => 'Hindu',
                        'Buddha' => 'Buddha',
                        'Konghucu' => 'Konghucu',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->nullable()
                    ->required(),
                Forms\Components\TextInput::make('nomor_telepon')
                    ->label('Nomor Telepon')
                    ->placeholder('Contoh: +6281234567890')
                    ->helperText('Gunakan angka saja, boleh diawali + untuk format internasional')
                    ->nullable()
                    ->unique(ignorable: fn(?Owner $record) => $record)
                    ->maxLength(15)
                    ->rules(['regex:/^\+?\d{9,15}$/'])
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->nullable()
                    ->email()
                    ->unique(ignorable: fn(?Owner $record) => $record)
                    ->maxLength(50)
                    ->required(),
                Forms\Components\TextInput::make('alamat')
                    ->label('Alamat')
                    ->nullable()
                    ->maxLength(255)
                    ->required(),
                Forms\Components\Select::make('bank')
                    ->label('Bank')
                    ->options([
                        'BCA' => 'BCA',
                        'BNI' => 'BNI',
                        'BRI' => 'BRI',
                        'Mandiri' => 'Mandiri',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->nullable()
                    ->required(),
                Forms\Components\TextInput::make('nomor_rekening')
                    ->label('Nomor Rekening')
                    ->placeholder('Contoh: 1234567890')
                    ->helperText('Masukkan nomor rekening tanpa spasi atau karakter khusus')
                    ->nullable()
                    ->unique(ignorable: fn(?Owner $record) => $record)
                    ->maxLength(20)
                    ->rules(['regex:/^\d{10,20}$/'])
                    ->required(),
                Forms\Components\TextInput::make('nomor_ktp')
                    ->label('Nomor KTP')
                    ->placeholder('Contoh: 327501xxxxxxxxxx')
                    ->helperText('Masukkan 16 digit angka sesuai KTP')
                    ->nullable()
                    ->unique(ignorable: fn(?Owner $record) => $record)
                    ->maxLength(16)
                    ->rules(['regex:/^\d{16}$/'])
                    ->required(),
                Forms\Components\FileUpload::make('foto_ktp')
                    ->label('Foto')
                    ->disk('public') // storage/app/public
                    ->directory('owner/ktp') // => storage/app/public/owner/ktp
                    ->visibility('public')
                    ->maxWidth(400)
                    ->maxSize(2048)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->columnSpanFull()
                    ->required()
                    ->image(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nomor_telepon')
                    ->label('Nomor Telepon')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([])
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
            'index' => Pages\ListOwners::route('/'),
            'create' => Pages\CreateOwner::route('/create'),
            'edit' => Pages\EditOwner::route('/{record}/edit'),
        ];
    }
}
