<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipeKamarResource\Pages;
use App\Filament\Resources\TipeKamarResource\RelationManagers;
use App\Models\TipeKamar;
use App\Filament\Resources\TipeKamarResource\RelationManagers\KamarsRelationManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;


class TipeKamarResource extends Resource
{
    protected static ?string $model = TipeKamar::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationLabel = 'Tipe Kamar';
    protected static ?string $pluralModelLabel = 'Tipe Kamar';
    protected static ?string $navigationGroup = 'Manajemen Kos';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['Admin', 'Owner', 'Superadmin']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('Superadmin');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole(['Superadmin', 'Admin', 'Owner']);
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->hasRole('Superadmin');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->hasRole('Superadmin');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['unit', 'hargaKamars'])
            ->when(auth()->user()?->hasRole('Owner'), function ($query) {
                $owner = \App\Models\Owner::where('user_id', auth()->id())->first();
                if (!$owner) return $query->whereRaw('0=1');

                return $query->whereHas('unit', function ($q) use ($owner) {
                    $q->where('id_owner', $owner->id)
                        ->where('status', true);
                });
            });
    }





    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('unit_id')
                    ->label('Unit')
                    ->relationship('unit', 'nama_cluster')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('nama_tipe')
                    ->label('Nama Tipe')
                    ->required()
                    ->maxLength(255),

                TextInput::make('harga.harga_perbulan')
                    ->label('Harga Bulanan')
                    ->prefix('Rp')
                    ->numeric()
                    ->nullable(),

                TextInput::make('harga.harga_perminggu')
                    ->label('Harga Mingguan')
                    ->prefix('Rp')
                    ->numeric()
                    ->nullable(),

                TextInput::make('harga.harga_perhari')
                    ->label('Harga Harian')
                    ->prefix('Rp')
                    ->numeric()
                    ->nullable(),

                TextInput::make('harga.minimal_deposit')
                    ->label('Deposit')
                    ->prefix('Rp')
                    ->numeric()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('unit.nama_cluster')
                    ->label('Nama Unit')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_tipe')
                    ->label('Nama Tipe')
                    ->searchable(),

                Tables\Columns\TextColumn::make('hargaKamars.harga_perbulan')
                    ->label('Harga Bulanan')
                    ->money('IDR', true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime(),
            ])
            ->filters([
                //
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
            KamarsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTipeKamars::route('/'),
            'create' => Pages\CreateTipeKamar::route('/create'),
            'edit' => Pages\EditTipeKamar::route('/{record}/edit'),
        ];
    }
}
