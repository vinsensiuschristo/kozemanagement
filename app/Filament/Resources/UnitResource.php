<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitResource\Pages;
use App\Filament\Resources\UnitResource\RelationManagers;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationGroup = 'SuperAdmin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_owner')
                    ->label('Owner')
                    ->required()
                    ->relationship('owner', 'nama')
                    ->searchable()
                    ->preload()
                    ->uuid(),
                Forms\Components\TextInput::make('nomor_kontrak')
                    ->label('Nomor Kontrak')
                    ->required()
                    ->placeholder('Contoh: NK-2025-001')
                    ->maxLength(20)
                    ->rules(['regex:/^NK-\d{4}-\d{3}$/'])
                    ->helperText('Gunakan format: NK-YYYY-XXX (misal: NK-2025-001)')
                    ->unique(ignorable: fn(?Unit $record) => $record),
                Forms\Components\TextInput::make('nama_cluster')
                    ->label('Nama Cluster')
                    ->required()
                    ->unique(ignorable: fn(?Unit $record) => $record)
                    ->maxLength(50),
                Forms\Components\DatePicker::make('tanggal_awal_kontrak')
                    ->label('Tanggal Awal Kontrak')
                    ->native(false)
                    ->minDate(today())
                    ->maxDate(now()->addYears(1))
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_akhir_kontrak')
                    ->label('Tanggal Akhir Kontrak')
                    ->native(false)
                    ->after('tanggal_awal_kontrak')
                    ->required(),
                Forms\Components\TextInput::make('alamat')
                    ->label('Alamat')
                    ->nullable()
                    ->maxLength(255),
            ])->columns([
                'sm' => 2,
                'md' => 3,
                'lg' => 4,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('owner.nama')
                    ->label('Owner')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nomor_kontrak')
                    ->label('Nomor Kontrak')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_cluster')
                    ->label('Nama Cluster')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('owner_nama')
                    ->form([
                        Forms\Components\TextInput::make('owner_nama')
                            ->label('Nama Owner'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (filled($data['owner_nama'] ?? null)) {
                            $query->whereHas('owner', function ($subQuery) use ($data) {
                                $subQuery->where('nama', 'like', '%' . $data['owner_nama'] . '%');
                            });
                        }
                        return $query;
                    })
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
            'index' => Pages\ListUnits::route('/'),
            'create' => Pages\CreateUnit::route('/create'),
            'edit' => Pages\EditUnit::route('/{record}/edit'),
        ];
    }
}
