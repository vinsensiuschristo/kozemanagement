<?php

namespace App\Filament\Widgets;

use App\Models\Unit;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Carbon\Carbon;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Auth;

class PengeluaranPerUnitWidget extends BaseWidget
{
    protected static ?string $heading = 'Pengeluaran per Unit';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = Auth::user();
        return $user && !$user->hasRole('Owner');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Unit::query()->with(['alamat', 'owner']))
            ->columns([
                Tables\Columns\TextColumn::make('nama_cluster')
                    ->label('Nama Unit')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('owner.nama')
                    ->label('Pemilik')
                    ->searchable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('alamat.kecamatan')
                    ->label('Lokasi')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('pengeluaran_1_bulan')
                    ->label('1 Bulan Terakhir')
                    ->getStateUsing(function (Unit $record) {
                        return $record->pengeluarans()
                            ->where('tanggal', '>=', Carbon::now()->subMonth())
                            ->sum('jumlah');
                    })
                    ->formatStateUsing(fn($state) => 'Rp ' . Number::format($state ?? 0, locale: 'id'))
                    ->badge()
                    ->color('danger'),

                Tables\Columns\TextColumn::make('pengeluaran_2_bulan')
                    ->label('2 Bulan Terakhir')
                    ->getStateUsing(function (Unit $record) {
                        return $record->pengeluarans()
                            ->where('tanggal', '>=', Carbon::now()->subMonths(2))
                            ->sum('jumlah');
                    })
                    ->formatStateUsing(fn($state) => 'Rp ' . Number::format($state ?? 0, locale: 'id'))
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('pengeluaran_3_bulan')
                    ->label('3 Bulan Terakhir')
                    ->getStateUsing(function (Unit $record) {
                        return $record->pengeluarans()
                            ->where('tanggal', '>=', Carbon::now()->subMonths(3))
                            ->sum('jumlah');
                    })
                    ->formatStateUsing(fn($state) => 'Rp ' . Number::format($state ?? 0, locale: 'id'))
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('jumlah_transaksi')
                    ->label('Total Transaksi')
                    ->getStateUsing(function (Unit $record) {
                        return $record->pengeluarans()->count();
                    })
                    ->badge()
                    ->color('primary'),
            ])
            ->defaultSort('nama_cluster')
            ->striped()
            ->paginated([10, 25, 50]);
    }
}
