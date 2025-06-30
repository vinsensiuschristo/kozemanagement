<?php

namespace App\Filament\Widgets;

use App\Models\Unit;
use App\Models\Kamar;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\KetersediaanKamar;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Auth;

class OwnerDashboardOverview extends BaseWidget
{
    protected static ?int $sort = 0;
    protected static ?string $pollingInterval = '30s';

    public static function canView(): bool
    {
        $user = Auth::user();
        return $user && $user->hasRole('Owner');
    }

    protected function getStats(): array
    {
        $user = Auth::user();

        // Jika user tidak ada atau bukan owner, return stats kosong
        if (!$user || !$user->hasRole('Owner')) {
            return [
                Stat::make('Error', 'No Access')
                    ->description('Anda tidak memiliki akses')
                    ->color('danger'),
            ];
        }

        // Jika user tidak memiliki relasi owner
        if (!$user->owner) {
            return [
                Stat::make('Error', 'No Owner Data')
                    ->description('Data owner tidak ditemukan')
                    ->color('danger'),
            ];
        }

        $ownerId = $user->owner->id;
        
        try {
            $units = Unit::where('id_owner', $ownerId)->get();
            $unitIds = $units->pluck('id');

            // Total units
            $totalUnits = $units->count();

            // Jika tidak ada unit, return stats dengan nilai 0
            if ($totalUnits === 0) {
                return [
                    Stat::make('Total Unit Kos', 0)
                        ->description('Belum ada unit terdaftar')
                        ->descriptionIcon('heroicon-m-building-office-2')
                        ->color('gray'),

                    Stat::make('Tingkat Hunian', '0%')
                        ->description('0 dari 0 kamar')
                        ->descriptionIcon('heroicon-m-arrow-trending-down')
                        ->color('gray'),

                    Stat::make('Net Profit', 'Rp 0')
                        ->description('Bulan ' . now()->format('F Y'))
                        ->descriptionIcon('heroicon-m-arrow-trending-up')
                        ->color('gray'),
                ];
            }

            // Total kamar - hitung dari semua unit milik owner
            $totalKamars = Kamar::whereIn('unit_id', $unitIds)->count();

            // Kamar terisi - menggunakan pendekatan yang lebih direct
            // Hitung kamar yang memiliki ketersediaan dengan status 'terisi'
            $kamarTerisi = Kamar::whereIn('unit_id', $unitIds)
                ->whereHas('ketersediaan', function ($query) {
                    $query->where('status', 'terisi');
                })
                ->count();

            // Alternative approach - jika yang atas tidak bekerja, gunakan ini:
            // Kamar terisi - hitung berdasarkan ketersediaan TERBARU
            // $kamarTerisi = Kamar::whereIn('unit_id', $unitIds)
            //     ->whereHas('ketersediaan', function($query) {
            //         $query->where('status', 'terisi')
            //             ->latest() // Ambil yang terbaru
            //             ->limit(1); // Hanya ambil 1 record terbaru
            //     })
            //     ->count();

            // Kamar kosong
            $kamarKosong = Kamar::whereIn('unit_id', $unitIds)
                ->whereHas('ketersediaan', function ($query) {
                    $query->where('status', 'kosong')
                    ->latest() // Ambil yang terbaru
                    ->limit(1); // Hanya ambil 1 record terbaru
                })
                ->count();

            // Revenue bulan ini
            $revenueBulanIni = Pemasukan::whereIn('unit_id', $unitIds)
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->sum('jumlah');

            // Pengeluaran bulan ini
            $pengeluaranBulanIni = Pengeluaran::whereIn('unit_id', $unitIds)
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->sum('jumlah');

            // Net profit
            $netProfit = $revenueBulanIni - $pengeluaranBulanIni;

            // Tingkat hunian - perbaiki perhitungan
            $tingkatHunian = $totalKamars > 0 ? round(($kamarTerisi / $totalKamars) * 100, 1) : 0;

            // Debug log untuk memastikan perhitungan benar
            \Log::info('Owner Dashboard Stats Debug:', [
                'owner_id' => $ownerId,
                'total_units' => $totalUnits,
                'unit_ids' => $unitIds->toArray(),
                'total_kamars' => $totalKamars,
                'kamar_terisi' => $kamarTerisi,
                'kamar_kosong' => $kamarKosong,
                'tingkat_hunian' => $tingkatHunian,
                'revenue' => $revenueBulanIni,
                'pengeluaran' => $pengeluaranBulanIni,
                'net_profit' => $netProfit,
            ]);

            // Verifikasi dengan query manual per unit
            $manualCount = 0;
            $unitDetails = [];
            foreach ($units as $unit) {
                $unitKamarTotal = Kamar::where('unit_id', $unit->id)->count();
                $unitKamarTerisi = Kamar::where('unit_id', $unit->id)
                    ->whereHas('ketersediaan', function ($query) {
                        $query->where('status', 'terisi');
                    })
                    ->count();
                $manualCount += $unitKamarTerisi;
                
                $unitDetails[] = [
                    'unit_id' => $unit->id,
                    'unit_name' => $unit->nama_cluster,
                    'total_kamar' => $unitKamarTotal,
                    'kamar_terisi' => $unitKamarTerisi,
                ];
            }

            \Log::info('Manual Count Verification:', [
                'manual_total_terisi' => $manualCount,
                'query_total_terisi' => $kamarTerisi,
                'unit_details' => $unitDetails,
            ]);

            return [
                Stat::make('Total Unit Kos', $totalUnits)
                    ->description($totalKamars . ' kamar total')
                    ->descriptionIcon('heroicon-m-building-office-2')
                    ->color('primary')
                    ->chart([7, 12, 8, 15, 18, 22, $totalUnits]),

                Stat::make('Tingkat Hunian', $tingkatHunian . '%')
                    ->description($kamarTerisi . ' dari ' . $totalKamars . ' kamar')
                    ->descriptionIcon($tingkatHunian >= 80 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                    ->color($tingkatHunian >= 80 ? 'success' : ($tingkatHunian >= 60 ? 'warning' : 'danger'))
                    ->chart([65, 72, 68, 75, 78, 82, $tingkatHunian]),

                // Stat::make($netProfit >= 0 ? 'Net Profit' : 'Net Loss', 'Rp ' . Number::format(abs($netProfit), locale: 'id'))
                //     ->description('Bulan ' . now()->format('F Y'))
                //     ->descriptionIcon($netProfit >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                //     ->color($netProfit >= 0 ? 'success' : 'danger')
                //     ->chart($netProfit >= 0 ? [1200000, 1350000, 1180000, 1420000, 1380000, 1560000] : [1560000, 1380000, 1420000, 1180000, 1350000, 1200000]),
            ];

        } catch (\Exception $e) {
            \Log::error('OwnerDashboardOverview Error: ' . $e->getMessage());
            
            return [
                Stat::make('Error', 'Database Error')
                    ->description('Gagal memuat data: ' . $e->getMessage())
                    ->color('danger'),
            ];
        }
    }
}
