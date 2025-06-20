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
use Filament\Forms\Get;
use Filament\Forms\Set;

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
                Forms\Components\Select::make('unit_id')
                    ->label('Unit')
                    ->options(function () {
                        $query = \App\Models\Unit::query();

                        if (auth()->user()?->hasRole('Owner')) {
                            $query->where('id_owner', auth()->id());
                        }

                        return $query->pluck('nama_cluster', 'id');
                    })
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(fn(Set $set) => $set('tipe_kamar_id', null))
                    ->required(),

                Forms\Components\Select::make('tipe_kamar_id')
                    ->label('Tipe Kamar')
                    ->options(function (Get $get) {
                        $unitId = $get('unit_id');
                        if (!$unitId) return [];

                        return \App\Models\TipeKamar::where('unit_id', $unitId)
                            ->pluck('nama_tipe', 'id');
                    })
                    ->required()
                    ->searchable(),

                Forms\Components\TextInput::make('nama')
                    ->label('Nama / Nomor Kamar')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('lantai')
                    ->numeric()
                    ->minValue(1)
                    ->required(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->label('Nama Kamar')->searchable(),
                Tables\Columns\TextColumn::make('tipeKamar.nama_tipe')->label('Tipe Kamar')->searchable(),
                Tables\Columns\TextColumn::make('tipeKamar.unit.nama_cluster')
                    ->label('Unit')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('ketersediaan.status')
                    ->label('Status')
                    ->colors([
                        'success' => 'kosong',
                        'danger' => 'terisi',
                        'warning' => 'booked',
                    ]),
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
