<?php

namespace App\Filament\Resources\UnitResource\Pages;

use App\Filament\Resources\UnitResource;
use App\Models\Unit;
use App\Models\Kamar;
use App\Models\LogPenghuni;
use App\Models\Penghuni;
use App\Models\HargaKamar;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class RoomLayout extends Page
{
    protected static string $resource = UnitResource::class;
    protected static string $view = 'filament.resources.unit.pages.room-layout';

    public Unit $record;
    public Collection $rooms;
    public $showDetailModal = false;
    public $selectedRoomDetail = null;
    public $expandedRoomId = null; // Untuk card yang di-expand

    public function mount(Unit $record): void
    {
        $this->record = $record;
        $this->rooms = $this->getRoomsData();
    }

    // public function getTitle(): string
    // {
    //     return "Layout Kamar - {$this->record->nama_cluster}";
    // }

    // public function getSubheading(): ?string
    // {
    //     return 'Manajemen kamar dan penghuni';
    // }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url($this->getResource()::getUrl('view', ['record' => $this->record])),
        ];
    }

    // Method untuk mendapatkan data kamar dari database
    public function getRoomsData(): Collection
    {
        $rooms = [];
        
        // Ambil semua kamar dari unit ini
        $kamars = Kamar::where('unit_id', $this->record->id)
            ->with(['ketersediaan', 'tipeKamar.hargaKamars'])
            ->orderBy('nama')
            ->get();

        foreach ($kamars as $kamar) {
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
                    $status = 'kosong'; // Update status jika sudah checkout
                }
            }
            
            $penghuni = $activeLog?->penghuni;
            
            // Format tanggal dengan aman
            $checkinDate = null;
            $checkoutDate = null;
            
            if ($activeLog && $activeLog->tanggal) {
                try {
                    if ($activeLog->tanggal instanceof Carbon) {
                        $checkinDate = $activeLog->tanggal->format('Y-m-d');
                    } else {
                        $checkinDate = Carbon::parse($activeLog->tanggal)->format('Y-m-d');
                    }
                    
                    // Estimasi checkout (1 tahun dari checkin)
                    $checkoutDate = Carbon::parse($checkinDate)->addYear()->format('Y-m-d');
                } catch (\Exception $e) {
                    $checkinDate = $activeLog->tanggal;
                }
            }
            
            $rooms[] = [
                'id' => $kamar->id,
                'nama' => $kamar->nama ?? 'Kamar ' . $kamar->id,
                'tipe' => $kamar->tipeKamar?->nama_tipe ?? 'Standard',
                'status' => $status,
                'penghuni' => $penghuni ? [
                    'nama' => $penghuni->nama ?? 'Tidak ada nama',
                    'telepon' => $penghuni->telepon ?? 'Tidak ada',
                    'email' => $penghuni->email ?? 'Tidak ada',
                    'pekerjaan' => $penghuni->pekerjaan ?? 'Tidak diketahui',
                    'tanggal_masuk' => $checkinDate,
                    'tanggal_berakhir' => $checkoutDate,
                    'status_pembayaran' => $this->getStatusPembayaran($activeLog),
                    'alamat_asal' => $penghuni->alamat ?? 'Tidak diketahui',
                    'umur' => $this->calculateAge($penghuni->tanggal_lahir),
                    'jenis_kelamin' => $penghuni->jenis_kelamin ?? 'Tidak diketahui',
                    'deposit' => $activeLog->deposit ?? 0,
                ] : null
            ];
        }
        
        return collect($rooms);
    }

    // Helper method untuk menghitung umur
    private function calculateAge($tanggalLahir): int
    {
        if (!$tanggalLahir) return 0;
        
        try {
            return Carbon::parse($tanggalLahir)->age;
        } catch (\Exception $e) {
            return 0;
        }
    }

    // Helper method untuk status pembayaran
    private function getStatusPembayaran($activeLog): string
    {
        if (!$activeLog) return 'Tidak ada data';
        
        // Logika sederhana: jika ada deposit, anggap lunas
        // Dalam implementasi nyata, ini harus dihubungkan dengan sistem pembayaran
        return ($activeLog->deposit ?? 0) > 0 ? 'Lunas' : 'Belum Bayar';
    }

    // Method untuk checkout penghuni
    public function checkoutPenghuni($kamarId)
    {
        try {
            // Cari log checkin terakhir untuk kamar ini
            $activeLog = LogPenghuni::where('kamar_id', $kamarId)
                ->where('status', 'checkin')
                ->orderBy('tanggal', 'desc')
                ->first();

            if ($activeLog) {
                // Buat log checkout
                LogPenghuni::create([
                    'penghuni_id' => $activeLog->penghuni_id,
                    'kamar_id' => $kamarId,
                    'tanggal' => now(),
                    'status' => 'checkout',
                    'created_by' => auth()->id(),
                ]);

                // Update status ketersediaan kamar
                $kamar = Kamar::find($kamarId);
                if ($kamar && $kamar->ketersediaan) {
                    $kamar->ketersediaan->update(['status' => 'kosong']);
                }

                Notification::make()
                    ->title('Checkout Berhasil')
                    ->body("Penghuni telah berhasil di-checkout.")
                    ->success()
                    ->send();

                // Refresh data
                $this->rooms = $this->getRoomsData();
                
                // Tutup detail card jika sedang terbuka
                if ($this->expandedRoomId == $kamarId) {
                    $this->expandedRoomId = null;
                }
            } else {
                Notification::make()
                    ->title('Error')
                    ->body("Tidak ada penghuni aktif di kamar ini.")
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body("Gagal melakukan checkout: " . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    // Method untuk toggle detail card
    public function toggleDetail($kamarId)
    {
        if ($this->expandedRoomId == $kamarId) {
            $this->expandedRoomId = null; // Tutup jika sudah terbuka
        } else {
            $this->expandedRoomId = $kamarId; // Buka detail
        }
    }

    // Method untuk mendapatkan statistik kamar
    public function getStatistik(): array
    {
        $allRooms = $this->rooms;
        
        return [
            'total' => $allRooms->count(),
            'kosong' => $allRooms->where('status', 'kosong')->count(),
            'terisi' => $allRooms->where('status', 'terisi')->count(),
            'booked' => $allRooms->where('status', 'booked')->count(),
            'maintenance' => $allRooms->where('status', 'maintenance')->count(),
            'tingkat_hunian' => $allRooms->count() > 0 ? 
                round(($allRooms->where('status', 'terisi')->count() / $allRooms->count()) * 100, 1) : 0,
        ];
    }
}
