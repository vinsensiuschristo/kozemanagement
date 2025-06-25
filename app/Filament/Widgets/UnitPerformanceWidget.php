<?php

namespace App\Filament\Widgets;

use App\Models\Unit;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Number;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UnitPerformanceWidget extends BaseWidget
{
    protected static ?string $heading = '🏢 Performance Unit Kos';
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = Auth::user();
        return !$user->hasRole('Owner');
    }

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
                'kamars as kamar_kosong_count' => function (Builder $query) {
                    $query->whereHas('ketersediaan', function (Builder $query) {
                        $query->where('status', 'kosong');
                    });
                },
            ]);

        // Filter berdasarkan role
        if ($isOwner) {
            $query->where('id_owner', $user->owner?->id);
        }

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('nama_cluster')
                    ->label('🏠 Nama Unit')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->extraAttributes(['class' => 'text-lg']),

                Tables\Columns\TextColumn::make('owner.nama')
                    ->label('👤 Pemilik')
                    ->searchable()
                    ->badge()
                    ->color('info')
                    ->visible(!$isOwner), // Hide untuk owner karena sudah pasti milik mereka

                Tables\Columns\TextColumn::make('alamat.kecamatan')
                    ->label('📍 Lokasi')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('kamars_count')
                    ->label('🏠 Total Kamar')
                    ->alignCenter()
                    ->badge()
                    ->color('primary')
                    ->size('lg'),

                Tables\Columns\TextColumn::make('kamar_terisi_count')
                    ->label('✅ Terisi')
                    ->alignCenter()
                    ->badge()
                    ->color('success')
                    ->size('lg'),

                Tables\Columns\TextColumn::make('kamar_kosong_count')
                    ->label('⭕ Kosong')
                    ->alignCenter()
                    ->badge()
                    ->color('warning')
                    ->size('lg'),

                Tables\Columns\TextColumn::make('tingkat_hunian')
                    ->label('📊 Hunian')
                    ->getStateUsing(function (Unit $record): string {
                        if ($record->kamars_count == 0) return '0%';
                        $percentage = round(($record->kamar_terisi_count / $record->kamars_count) * 100, 1);
                        return $percentage . '%';
                    })
                    ->badge()
                    ->size('lg')
                    ->color(function (string $state): string {
                        $percentage = (float) str_replace('%', '', $state);
                        return match (true) {
                            $percentage >= 90 => 'success',
                            $percentage >= 70 => 'info',
                            $percentage >= 50 => 'warning',
                            default => 'danger',
                        };
                    })
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('pemasukan_bulan_ini')
                    ->label('💰 Pemasukan')
                    ->getStateUsing(function (Unit $record) {
                        return $record->pemasukans()
                            ->whereMonth('tanggal', now()->month)
                            ->whereYear('tanggal', now()->year)
                            ->sum('jumlah');
                    })
                    ->formatStateUsing(fn($state) => 'Rp ' . Number::format($state ?? 0, locale: 'id'))
                    ->badge()
                    ->color('success')
                    ->size('lg'),

                Tables\Columns\TextColumn::make('net_income')
                    ->label('📈 Net Income')
                    ->getStateUsing(function (Unit $record) {
                        $pemasukan = $record->pemasukans()
                            ->whereMonth('tanggal', now()->month)
                            ->whereYear('tanggal', now()->year)
                            ->sum('jumlah');

                        $pengeluaran = $record->pengeluarans()
                            ->whereMonth('tanggal', now()->month)
                            ->whereYear('tanggal', now()->year)
                            ->sum('jumlah');

                        $net = $pemasukan - $pengeluaran;
                        return 'Rp ' . Number::format($net, locale: 'id');
                    })
                    ->badge()
                    ->size('lg')
                    ->color(function (Unit $record): string {
                        $pemasukan = $record->pemasukans()
                            ->whereMonth('tanggal', now()->month)
                            ->whereYear('tanggal', now()->year)
                            ->sum('jumlah');

                        $pengeluaran = $record->pengeluarans()
                            ->whereMonth('tanggal', now()->month)
                            ->whereYear('tanggal', now()->year)
                            ->sum('jumlah');

                        $net = $pemasukan - $pengeluaran;
                        return $net >= 0 ? 'success' : 'danger';
                    }),
            ])
            ->defaultSort('kamar_terisi_count', 'desc')
            ->striped()
            ->paginated([10, 25, 50])
            ->extremePaginationLinks()
            ->poll('30s');
    }
}
