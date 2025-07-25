<?php

namespace App\Filament\Widgets;

use App\Models\Unit;
use App\Models\Kamar;
use App\Models\LogPenghuni;
use App\Models\Penghuni;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OwnerUnitsWidget extends Widget
{
    protected static string $view = 'filament.widgets.owner-units';
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    public $selectedRoom = '';
    public $roomDetail = null;
    public $showDetailModal = false;

    public static function canView(): bool
    {
        $user = Auth::user();
        $canView = $user && $user->hasRole('Owner');
        
        \Log::info('OwnerUnitsWidget canView - User: ' . ($user?->id ?? 'null') . ', Can View: ' . ($canView ? 'yes' : 'no'));
        
        return $canView;
    }

    public function mount(): void
    {
        \Log::info('OwnerUnitsWidget mount called');
    }

    protected function getViewData(): array
    {
        $user = Auth::user();
        
        \Log::info('OwnerUnitsWidget getViewData - User: ' . ($user?->id ?? 'null'));
        
        if (!$user) {
            \Log::warning('OwnerUnitsWidget getViewData - No user found');
            return ['units' => collect()];
        }

        if (!$user->owner) {
            \Log::warning('OwnerUnitsWidget getViewData - User has no owner relation');
            return ['units' => collect()];
        }

        \Log::info('OwnerUnitsWidget getViewData - Owner ID: ' . $user->owner->id);

        // Ambil units milik owner dengan relasi yang diperlukan
        $units = Unit::where('id_owner', $user->owner->id)
            ->with([
                'alamat',
                'kamars.ketersediaan',
                'kamars.tipeKamar',
                'owner'
            ])
            ->get();

        \Log::info('OwnerUnitsWidget getViewData - Units found: ' . $units->count());

        $unitsData = $units->map(function ($unit) {
            $rooms = $this->getRoomsDataForUnit($unit);
            
            // Sort rooms by nama (ascending)
            $sortedRooms = collect($rooms)->sortBy('nama')->values()->all();
            
            return [
                'id' => $unit->id,
                'nama' => $unit->nama_cluster ?? 'Unit Tanpa Nama',
                'alamat' => $unit->alamat?->alamat_lengkap ?? 'Alamat tidak tersedia',
                'owner' => $unit->owner?->nama ?? 'No Owner',
                'rooms' => $sortedRooms,
                'total_rooms' => count($sortedRooms),
                'occupied_rooms' => collect($sortedRooms)->where('status', 'terisi')->count(),
                'available_rooms' => collect($sortedRooms)->where('status', 'kosong')->count(),
                'booked_rooms' => collect($sortedRooms)->where('status', 'booked')->count(),
            ];
        });

        return [
            'units' => $unitsData,
        ];
    }

    private function getRoomsDataForUnit($unit): array
    {
        $rooms = [];
        
        foreach ($unit->kamars as $kamar) {
            // Cek status ketersediaan kamar
            $status = $kamar->ketersediaan?->status ?? 'kosong';
            
            // Ambil penghuni aktif berdasarkan log terakhir dengan status checkin
            $activeLog = LogPenghuni::where('kamar_id', $kamar->id)
                ->where('status', 'checkin')
                ->with('penghuni')
                ->orderBy('tanggal', 'desc')
                ->first();

            // Cek apakah ada log checkout setelah checkin terakhir
            if ($activeLog) {
                $checkoutLog = LogPenghuni::where('kamar_id', $kamar->id)
                    ->where('penghuni_id', $activeLog->penghuni_id)
                    ->where('status', 'checkout')
                    ->where('tanggal', '>', $activeLog->tanggal)
                    ->first();
                
                // Jika ada checkout setelah checkin, berarti kamar sudah kosong
                if ($checkoutLog) {
                    $activeLog = null;
                }
            }
            
            // Untuk status booked, cari penghuni yang booking
            $bookedPenghuni = null;
            if ($status === 'booked') {
                // Cari log booking terakhir
                $bookingLog = LogPenghuni::where('kamar_id', $kamar->id)
                    ->where('status', 'booking')
                    ->with('penghuni')
                    ->orderBy('tanggal', 'desc')
                    ->first();
                
                $bookedPenghuni = $bookingLog?->penghuni;
            }
            
            $penghuni = $activeLog?->penghuni ?? $bookedPenghuni;
            
            // Format tanggal dengan aman
            $checkinDate = 'Tidak diketahui';
            if ($activeLog && $activeLog->tanggal) {
                try {
                    if ($activeLog->tanggal instanceof Carbon) {
                        $checkinDate = $activeLog->tanggal->format('d/m/Y');
                    } else {
                        $checkinDate = Carbon::parse($activeLog->tanggal)->format('d/m/Y');
                    }
                } catch (\Exception $e) {
                    $checkinDate = $activeLog->tanggal; // Fallback ke string asli
                }
            }
            
            $rooms[] = [
                'id' => $kamar->id,
                'nama' => $kamar->nama ?? 'Kamar ' . $kamar->id,
                'status' => $status,
                'tipe' => $kamar->tipeKamar?->nama_tipe ?? 'Standard',
                'lantai' => $kamar->lantai ?? 1,
                'ukuran' => $kamar->ukuran ?? 'Tidak diketahui',
                'penghuni' => $penghuni ? [
                    'nama' => $penghuni->nama ?? 'Tidak ada nama',
                    'no_telp' => $penghuni->no_telp ?? 'Tidak ada',
                    'telepon' => $penghuni->no_telp ?? 'Tidak ada', // Untuk backward compatibility
                    'email' => $penghuni->email ?? 'Tidak ada',
                    'checkin' => $checkinDate,
                    'deposit' => isset($activeLog->deposit) ? 'Rp ' . number_format($activeLog->deposit, 0, ',', '.') : 'Tidak ada data',
                    'kode' => $penghuni->kode ?? 'Tidak ada',
                ] : null
            ];
        }
        
        return $rooms;
    }

    public function showRoomDetail($roomName)
    {
        try {
            $viewData = $this->getViewData();
            $units = $viewData['units'];
            
            // Cari room di semua units milik owner
            foreach ($units as $unit) {
                $room = collect($unit['rooms'])->firstWhere('nama', $roomName);
                if ($room) {
                    $this->selectedRoom = $roomName;
                    $this->roomDetail = $room;
                    $this->showDetailModal = true;
                    $this->dispatch('open-modal', id: 'room-detail-modal');
                    return;
                }
            }
            
            // Jika tidak ditemukan
            $this->dispatch('notify', [
                'type' => 'warning',
                'message' => 'Kamar tidak ditemukan'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('OwnerUnitsWidget showRoomDetail error: ' . $e->getMessage());
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Gagal memuat detail kamar: ' . $e->getMessage()
            ]);
        }
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedRoom = '';
        $this->roomDetail = null;
    }

    // Method untuk redirect ke room layout
    public function viewUnitDetail($unitId)
    {
        try {
            return redirect()->route('filament.admin.resources.units.room-layout', ['record' => $unitId]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error', 
                'message' => 'Gagal membuka detail unit'
            ]);
        }
    }
}
