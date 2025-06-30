<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogPenghuniResource\Pages;
use App\Filament\Resources\LogPenghuniResource\RelationManagers;
use App\Models\LogPenghuni;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Kamar;

class LogPenghuniResource extends Resource
{
    protected static ?string $model = LogPenghuni::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('penghuni_id')
                    ->label('Penghuni')
                    ->searchable()
                    ->options(function () {
                        return \App\Models\Penghuni::query()
                            ->get()
                            ->mapWithKeys(fn($penghuni) => [
                                $penghuni->id => "{$penghuni->kode} - {$penghuni->nama}"
                            ]);
                    })
                    ->required(),

                Select::make('kamar_id')
                    ->label('Kamar')
                    ->searchable()
                    ->options(function () {
                        return Kamar::with('unit')
                            ->get()
                            ->mapWithKeys(function ($kamar) {
                                $unitName = $kamar->unit ? $kamar->unit->nama_cluster : 'Tanpa Unit';
                                return [$kamar->id => "{$unitName} - {$kamar->nama}"];
                            });
                    })
                    ->required(),

                DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'checkin' => 'Check-In',
                        'checkout' => 'Check-Out',
                    ])
                    ->required(),

                Hidden::make('created_by')
                    ->default(fn() => auth()->id()),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['penghuni', 'kamar.unit']);

        if (auth()->user()?->hasRole('Owner')) {
            $owner = \App\Models\Owner::where('user_id', auth()->id())->first();

            if ($owner) {
                $query->whereHas('kamar.unit', function ($q) use ($owner) {
                    $q->where('id_owner', $owner->id)
                        ->where('status', true); // unit aktif
                });
            } else {
                $query->whereRaw('0=1');
            }
        }

        return $query;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('penghuni.nama')->label('Penghuni')->searchable(),
                TextColumn::make('kamar.nama')
                    ->label('Kamar')
                    ->formatStateUsing(function ($state, $record) {
                        $unitName = $record->kamar?->unit?->nama_cluster ?? '-';
                        $kamarName = $record->kamar?->nama ?? '-';
                        return "{$unitName} - {$kamarName}";
                    })
                    ->searchable(),
                TextColumn::make('tanggal')->label('Tanggal')->date(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn(string $state) => [
                        'checkin' => 'Check-In',
                        'checkout' => 'Check-Out',
                    ][$state] ?? ucfirst($state))
                    ->colors([
                        'checkin' => 'success',
                        'checkout' => 'danger',
                    ]),
                TextColumn::make('createdBy.name')->label('Dicatat oleh'),
            ])
            ->filters([
                SelectFilter::make('unit_id')
                    ->label('Nama Unit')
                    ->options(function () {
                        if (auth()->user()->hasRole('Owner')) {
                            $owner = \App\Models\Owner::where('user_id', auth()->id())->first();
                            return \App\Models\Unit::where('id_owner', $owner->id)
                                ->where('status', true)
                                ->pluck('nama_cluster', 'id')
                                ->toArray();
                        }

                        return \App\Models\Unit::where('status', true)
                            ->pluck('nama_cluster', 'id')
                            ->toArray();
                    })
                    ->searchable(),

                Filter::make('tanggal')
                    ->form([
                        Forms\Components\Grid::make(2) // Membuat grid 2 kolom
                            ->schema([
                                DatePicker::make('checkin_from')->label('Dari Tanggal'),
                                DatePicker::make('checkin_until')->label('Sampai Tanggal'),
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['checkin_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date),
                            )
                            ->when(
                                $data['checkin_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date),
                            );
                    })
            ], layout: FiltersLayout::AboveContent) // Mengubah ke dropdown di pojok kanan
            ->filtersFormColumns(2) // Jumlah kolom untuk form filter
            ->modifyQueryUsing(function ($query) {
                return $query->orderByDesc('tanggal');
            });
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
            'index' => Pages\ListLogPenghunis::route('/'),
            'create' => Pages\CreateLogPenghuni::route('/create'),
            'edit' => Pages\EditLogPenghuni::route('/{record}/edit'),
        ];
    }
}
