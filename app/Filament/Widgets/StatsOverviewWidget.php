<?php

namespace App\Filament\Widgets;

use App\Models\Unit;
use App\Models\Kamar;
use App\Models\KetersediaanKamar;
use App\Models\Owner;
use App\Models\HargaKamar;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Auth;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $user = Auth::user();
        $isOwner = $user->hasRole('Owner');

        // Filter berdasarkan role
        if ($isOwner) {
            $unitIds = Unit::where('id_owner', $user->owner?->id)->pluck('id');
            $totalUnits = Unit::where('id_owner', $user->owner?->id)->count();
            $totalKamars = Kamar::whereIn('unit_id', $unitIds)->count();
            $totalOwners = 1; // Owner hanya melihat dirinya sendiri
        } else {
            $unitIds = Unit::pluck('id');
            $totalUnits = Unit::count();
            $totalKamars = Kamar::count();
            $totalOwners = Owner::count();
        }

        // Hitung ketersediaan kamar
        $kamarTersedia = KetersediaanKamar::whereHas('kamar', function ($query) use ($unitIds) {
            $query->whereIn('unit_id', $unitIds);
        })->where('status', 'kosong')->count();

        $kamarTerisi = KetersediaanKamar::whereHas('kamar', function ($query) use ($unitIds) {
            $query->whereIn('unit_id', $unitIds);
        })->where('status', 'terisi')->count();

        $kamarBooked = KetersediaanKamar::whereHas('kamar', function ($query) use ($unitIds) {
            $query->whereIn('unit_id', $unitIds);
        })->where('status', 'booked')->count();

        // Hitung tingkat hunian
        $tingkatHunian = $totalKamars > 0 ? round(($kamarTerisi / $totalKamars) * 100, 1) : 0;

        // Hitung revenue potensial
        $revenuePotensial = HargaKamar::whereHas('tipeKamar', function ($query) use ($unitIds) {
            $query->whereIn('unit_id', $unitIds);
        })->sum('harga_perbulan');

        $revenueAktual = HargaKamar::whereHas('tipeKamar.ketersediaanKamars', function ($query) {
            $query->where('status', 'terisi');
        })->whereHas('tipeKamar', function ($query) use ($unitIds) {
            $query->whereIn('unit_id', $unitIds);
        })->sum('harga_perbulan');

        return [
            // Total Kos
            Stat::make('Total Kos', $totalUnits)
                ->description('Unit kos terdaftar')
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('primary')
                ->chart([7, 12, 8, 15, 18, 22, $totalUnits])
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-transform duration-200',
                ]),

            // Total Kamar
            Stat::make('Total Kamar', Number::format($totalKamars))
                ->description('Kamar tersedia')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('info')
                ->chart([45, 52, 48, 61, 58, 67, $totalKamars])
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-transform duration-200',
                ]),

            // Tingkat Hunian
            Stat::make('Tingkat Hunian', $tingkatHunian . '%')
                ->description($kamarTerisi . ' dari ' . $totalKamars . ' kamar terisi')
                ->descriptionIcon($tingkatHunian >= 80 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($tingkatHunian >= 80 ? 'success' : ($tingkatHunian >= 50 ? 'warning' : 'danger'))
                ->chart([65, 72, 68, 75, 78, 82, $tingkatHunian])
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-transform duration-200',
                ]),

            // Revenue Aktual
            Stat::make('Revenue Aktual', 'Rp ' . Number::format($revenueAktual, locale: 'id'))
                ->description('Dari ' . Number::format($revenuePotensial, locale: 'id') . ' potensial')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([1200000, 1350000, 1180000, 1420000, 1380000, 1560000, $revenueAktual])
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-transform duration-200',
                ]),
        ];
    }
}
