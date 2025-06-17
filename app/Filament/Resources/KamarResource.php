<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KamarResource\Pages;
use App\Filament\Resources\KamarResource\RelationManagers;
use App\Models\Kamar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class KamarResource extends Resource
{
    protected static ?string $model = Kamar::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen Kos';
    }

    public static function getNavigationLabel(): string
    {
        return 'Kamar';
    }


    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['Admin', 'Superadmin', 'Owner']);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['tipeKamar.unit.owner'])
            ->when(
                auth()->user()?->hasRole('Owner'),
                fn(Builder $query) => $query->whereHas('tipeKamar.unit', function ($q) {
                    $q->where('id_owner', auth()->id());
                })
            );
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


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tipe_kamar_id')
                    ->relationship('tipeKamar', 'nama_tipe')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->label('Nama Kamar')->searchable(),
                Tables\Columns\TextColumn::make('tipeKamar.nama_tipe')->label('Tipe Kamar')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Dibuat'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKamars::route('/'),
            'create' => Pages\CreateKamar::route('/create'),
            'edit' => Pages\EditKamar::route('/{record}/edit'),
        ];
    }
}
