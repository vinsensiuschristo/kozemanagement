<?php

namespace App\Filament\Widgets;

use App\Models\Unit;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Number;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class UnitPerformanceWidget extends BaseWidget
{
    protected static ?string $heading = 'Performance Unit Kos';
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = auth()->user();

        // Hanya Superadmin dan Admin yang bisa melihat
        return $user && $user->hasAnyRole(['Superadmin', 'Admin']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                // Debug 1: Cek user yang login dan role-nya
                Log::debug('Auth check', [
                    'user_id' => auth()->id(),
                    'is_authenticated' => auth()->check(),
                    'roles' => auth()->check() ? auth()->user()->getRoleNames() : null,
                    'is_owner' => auth()->check() ? auth()->user()->hasRole('Owner') : false
                ]);

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

                // Debug 2: Cek jumlah unit sebelum filter
                Log::debug('Before filter', [
                    'total_units' => $query->count()
                ]);

                // Filter untuk owner
                if (auth()->check() && auth()->user()->hasRole('Owner')) {
                    Log::debug('Applying owner filter', [
                        'owner_id' => auth()->id(),
                        'units_belonging_to_owner' => Unit::where('id_owner', auth()->id())->count()
                    ]);

                    $query->where('id_owner', auth()->id());
                }

                // Debug 3: Cek jumlah unit setelah filter
                Log::debug('After filter', [
                    'filtered_units' => $query->count()
                ]);

                return $query;
            })
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

                Tables\Columns\TextColumn::make('kamars_count')
                    ->label('Total Kamar')
                    ->alignCenter()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('kamar_terisi_count')
                    ->label('Terisi')
                    ->alignCenter()
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('kamar_kosong_count')
                    ->label('Kosong')
                    ->alignCenter()
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('tingkat_hunian')
                    ->label('Hunian')
                    ->getStateUsing(function (Unit $record): string {
                        if ($record->kamars_count == 0) return '0%';
                        $percentage = round(($record->kamar_terisi_count / $record->kamars_count) * 100, 1);
                        return $percentage . '%';
                    })
                    ->badge()
                    ->color(function (string $state): string {
                        $percentage = (float) str_replace('%', '', $state);
                        return match (true) {
                            $percentage >= 80 => 'success',
                            $percentage >= 50 => 'warning',
                            default => 'danger',
                        };
                    })
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('pemasukan_bulan_ini')
                    ->label('Pemasukan Bulan Ini')
                    ->getStateUsing(function (Unit $record) {
                        return $record->pemasukans()
                            ->whereMonth('tanggal', now()->month)
                            ->whereYear('tanggal', now()->year)
                            ->sum('jumlah');
                    })
                    ->formatStateUsing(fn($state) => 'Rp ' . Number::format($state ?? 0, locale: 'id'))
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('net_income')
                    ->label('Net Income')
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
            ->paginated([10, 25, 50]);
    }
}
