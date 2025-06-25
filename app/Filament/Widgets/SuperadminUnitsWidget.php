<?php

namespace App\Filament\Widgets;

use App\Models\Unit;
use App\Models\Kamar;
use App\Models\LogPenghuni;
use App\Models\Penghuni;
use App\Models\Owner;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class SuperadminUnitsWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.superadmin-units';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public $selectedUnitId = null;
    public $searchTerm = '';
    public $selectedRoom = '';
    public $roomDetail = null;

    public static function canView(): bool
    {
        $user = Auth::user();
        return $user && $user->hasRole('Superadmin');
    }

    public function mount(): void
    {
        // Set unit pertama sebagai default jika ada
        $firstUnit = Unit::with('owner')->first();
        if ($firstUnit) {
            $this->selectedUnitId = $firstUnit->id;
        }
    }

    protected function getViewData(): array
    {
        // Ambil semua units untuk dropdown
        $allUnits = Unit::with('owner')
            ->when($this->searchTerm, function ($query) {
                $query->where('nama_cluster', 'like', '%' . $this->searchTerm . '%')
                    ->orWhereHas('owner', function ($q) {
                        $q->where('nama', 'like', '%' . $this->searchTerm . '%');
                    });
            })
            ->get()
            ->map(function ($unit) {
                return [
                    'value' => $unit->id,
                    'label' => $unit->nama_cluster . ' - ' . ($unit->owner->nama ?? 'No Owner'),
                ];
            });

        // Ambil data unit yang dipilih
        $selectedUnit = null;
        if ($this->selectedUnitId) {
            $unit = Unit::with(['alamat', 'kamars.ketersediaan', 'kamars.tipeKamar', 'owner'])
                ->find($this->selectedUnitId);

            if ($unit) {
                $rooms = $this->getRoomsDataForUnit($unit);

                $selectedUnit = [
                    'id' => $unit->id,
                    'nama' => $unit->nama_cluster ?? 'Unit Tanpa Nama',
                    'alamat' => $unit->alamat?->alamat_lengkap ?? 'Alamat tidak tersedia',
                    'owner' => $unit->owner?->nama ?? 'No Owner',
                    'rooms' => $rooms,
                    'total_rooms' => count($rooms),
                    'occupied_rooms' => collect($rooms)->where('status', 'terisi')->count(),
                    'available_rooms' => collect($rooms)->where('status', 'kosong')->count(),
                ];
            }
        }

        return [
            'allUnits' => $allUnits,
            'selectedUnit' => $selectedUnit,
            'searchTerm' => $this->searchTerm,
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

            $penghuni = $activeLog?->penghuni;

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
                    'telepon' => $penghuni->no_telp ?? 'Tidak ada',
                    'email' => $penghuni->email ?? 'Tidak ada',
                    'checkin' => $checkinDate,
                    'deposit' => isset($activeLog->deposit) ? 'Rp ' . number_format($activeLog->deposit, 0, ',', '.') : 'Tidak ada data',
                    'kode' => $penghuni->kode ?? 'Tidak ada',
                ] : null
            ];
        }

        return $rooms;
    }

    public function updatedSearchTerm()
    {
        // Reset selected unit when search changes
        $this->selectedUnitId = null;
    }

    public function selectUnit($unitId)
    {
        $this->selectedUnitId = $unitId;
    }

    public function showRoomDetail($roomName)
    {
        try {
            $viewData = $this->getViewData();
            $unit = $viewData['selectedUnit'];

            if ($unit) {
                $room = collect($unit['rooms'])->firstWhere('nama', $roomName);
                if ($room) {
                    $this->selectedRoom = $roomName;
                    $this->roomDetail = $room;
                    $this->dispatch('open-modal', id: 'room-detail-modal');
                }
            }
        } catch (\Exception $e) {
            \Log::error('SuperadminUnitsWidget showRoomDetail error: ' . $e->getMessage());
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Gagal memuat detail kamar: ' . $e->getMessage()
            ]);
        }
    }

    public function viewUnitLayout()
    {
        if ($this->selectedUnitId) {
            return redirect()->route('filament.admin.resources.units.room-layout', ['record' => $this->selectedUnitId]);
        }
    }
}
