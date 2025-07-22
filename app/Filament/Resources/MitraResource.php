<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MitraResource\Pages;
use App\Filament\Resources\MitraResource\RelationManagers;
use App\Models\Mitra;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class MitraResource extends Resource
{
    protected static ?string $model = Mitra::class;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('Superadmin');
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('Superadmin');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->hasRole('Superadmin');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->hasRole('Superadmin');
    }

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationLabel = 'Mitra';
    protected static ?string $navigationGroup = 'Manajemen Voucher';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Mitra')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->label('Nama Mitra')
                            ->maxLength(255),
                        Forms\Components\Select::make('kategori')
                            ->required()
                            ->options([
                                "cafe" => "Cafe",
                                "restoran" => "Restoran",
                                "hotel" => "Hotel",
                                "wisata" => "Wisata",
                                "laundry" => "Laundry",
                                "lainnya" => "Lainnya",
                            ])
                            ->native(false),
                        Forms\Components\TextInput::make('telepon')
                            ->tel()
                            ->required()
                            ->label('Telepon Mitra')
                            ->maxLength(20),
                        Forms\Components\TextInput::make('alamat')
                            ->nullable()
                            ->label('Alamat Mitra')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('deskripsi')
                            ->nullable()
                            ->label('Deskripsi Mitra')
                            ->maxLength(500)
                            ->rows(3),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Akun Login Mitra')
                    ->description('Buat akun login untuk mitra agar dapat mengakses sistem')
                    ->schema([
                        Forms\Components\TextInput::make('user.name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('user.email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(User::class, 'email', ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('user.password')
                            ->label('Password')
                            ->password()
                            ->required(fn(string $context): bool => $context === 'create')
                            ->minLength(8)
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->helperText('Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.'),
                        Forms\Components\TextInput::make('user.password_confirmation')
                            ->label('Konfirmasi Password')
                            ->password()
                            ->required(fn(string $context): bool => $context === 'create')
                            ->same('user.password')
                            ->dehydrated(false),
                    ])
                    ->columns(2),
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
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'laundry' => 'success',
                        'cafe' => 'warning',
                        'restoran' => 'info',
                        'hotel' => 'primary',
                        'wisata' => 'danger',
                        'lainnya' => 'secondary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('telepon')
                    ->label('Telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email Login')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email disalin!'),
                Tables\Columns\IconColumn::make('user.id')
                    ->label('Akun Aktif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori')
                    ->options([
                        "cafe" => "Cafe",
                        "restoran" => "Restoran",
                        "hotel" => "Hotel",
                        "wisata" => "Wisata",
                        "laundry" => "Laundry",
                        "lainnya" => "Lainnya",
                    ])
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Mitra $record) {
                        // Hapus user terkait jika ada
                        if ($record->user) {
                            $record->user->delete();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            // Hapus user terkait untuk setiap record
                            foreach ($records as $record) {
                                if ($record->user) {
                                    $record->user->delete();
                                }
                            }
                        }),
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
