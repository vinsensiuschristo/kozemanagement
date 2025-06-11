<?php

namespace App\Filament\Widgets;

use App\Models\Unit;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TopPerformingUnitsWidget extends BaseWidget
{
    protected static ?string $heading = 'Kos dengan Hunian Tertinggi';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $pollingInterval = '60s';

    public function table(Table $table): Table
    {
        $user = Auth::user();
        $isOwner = $user->hasRole('Owner');

        $query = Unit::query()
            ->with(['kamars.ketersediaan', 'alamat', 'owner'])
            ->withCount([
                'kamars',
                'kamars as kamar_terisi_count' => function (Builder $query) {
                    $query->whereHas('ketersediaan', function (Builder $query) {
                        $query->where('status', 'terisi');
                    });
                },
            ])
            ->having('kamars_count', '>', 0)
            ->orderByRaw('(kamar_terisi_count / kamars_count) DESC')
            ->limit(10);

        // Filter berdasarkan role
        if ($isOwner) {
            $query->where('id_owner', $user->owner?->id);
        }

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('nama_cluster')
                    ->label('Nama Kos')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-m-home-modern')
                    ->iconColor('primary'),

                Tables\Columns\TextColumn::make('owner.nama')
                    ->label('Pemilik')
                    ->searchable()
                    ->badge()
                    ->color('info')
                    ->visible(!$isOwner), // Hide untuk owner

                Tables\Columns\TextColumn::make('alamat.kecamatan')
                    ->label('Lokasi')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('kamars_count')
                    ->label('Total Kamar')
                    ->alignCenter()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('kamar_terisi_count')
                    ->label('Kamar Terisi')
                    ->alignCenter()
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('tingkat_hunian')
                    ->label('Tingkat Hunian')
                    ->getStateUsing(function (Unit $record): string {
                        if ($record->kamars_count == 0) return '0%';
                        $percentage = round(($record->kamar_terisi_count / $record->kamars_count) * 100, 1);
                        return $percentage . '%';
                    })
                    ->badge()
                    ->color(function (string $state): string {
                        $percentage = (float) str_replace('%', '', $state);
                        return match (true) {
                            $percentage >= 90 => 'success',
                            $percentage >= 70 => 'warning',
                            default => 'danger',
                        };
                    })
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('disewakan_untuk')
                    ->label('Target')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'putra' => 'blue',
                        'putri' => 'pink',
                        'campur' => 'green',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('kamar_terisi_count', 'desc')
            ->striped()
            ->paginated(false);
    }
}
