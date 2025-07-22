<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MitraResource\Pages;
use App\Models\Mitra;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class MitraResource extends Resource
{
    protected static ?string $model = Mitra::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationLabel = 'Mitra';
    protected static ?string $pluralLabel = 'Mitra';
    protected static ?int $navigationSort = 6;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole(['Admin', 'Superadmin']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Mitra')
                    ->description('Data dasar mitra bisnis')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nama')
                                    ->label('Nama Mitra')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('kategori')
                                    ->label('Kategori Bisnis')
                                    ->placeholder('Contoh: Restoran, Retail, Jasa')
                                    ->maxLength(100),

                                Forms\Components\TextInput::make('kontak_person')
                                    ->label('Nama Kontak Person')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('nomor_telepon')
                                    ->label('Nomor Telepon')
                                    ->tel()
                                    ->maxLength(20),

                                Forms\Components\TextInput::make('email')
                                    ->label('Email Mitra')
                                    ->email()
                                    ->maxLength(255),

                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'aktif' => 'Aktif',
                                        'nonaktif' => 'Non-aktif',
                                        'pending' => 'Pending',
                                    ])
                                    ->default('aktif')
                                    ->required(),
                            ]),

                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi Bisnis')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Akun Login Mitra')
                    ->description('Buat akun login untuk mitra ini')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('user_email')
                                    ->label('Email Login')
                                    ->email()
                                    ->required()
                                    ->unique(User::class, 'email', ignoreRecord: true)
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('user_password')
                                    ->label('Password')
                                    ->password()
                                    ->required(fn(string $context): bool => $context === 'create')
                                    ->minLength(8)
                                    ->maxLength(255)
                                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                                    ->dehydrated(fn($state) => filled($state))
                                    ->revealable(),
                            ]),
                    ])
                    ->visible(fn(string $context): bool => $context === 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Mitra')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('kontak_person')
                    ->label('Kontak Person')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nomor_telepon')
                    ->label('Telepon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'aktif' => 'success',
                        'nonaktif' => 'danger',
                        'pending' => 'warning',
                    }),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email Login')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Non-aktif',
                        'pending' => 'Pending',
                    ]),
            ])
            ->actions([
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
            'index' => Pages\ListMitras::route('/'),
            'create' => Pages\CreateMitra::route('/create'),
            'edit' => Pages\EditMitra::route('/{record}/edit'),
        ];
    }
}
