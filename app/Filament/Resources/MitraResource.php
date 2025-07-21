<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MitraResource\Pages;
use App\Filament\Resources\MitraResource\RelationManagers;
use App\Models\Mitra;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

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
                    ]),
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
                    ->maxLength(500),
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
                    ->color(fn (string $state) => match ($state) {
                        'laundry' => 'success',
                        'cafe' => 'warning',
                        'restoran' => 'info',
                        'hotel' => 'primary',
                        'wisata' => 'danger',
                        'lainnya' => 'secondary',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
