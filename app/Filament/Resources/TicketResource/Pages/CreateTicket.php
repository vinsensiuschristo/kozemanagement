<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use App\Models\LogPenghuni;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        // Set user_id
        $data['user_id'] = $user->id;

        // Jika user bukan admin, ambil data dari relasi penghuni
        if (!$user->hasRole(['Superadmin', 'Admin'])) {
            $penghuni = $user->penghuni;

            if (!$penghuni) {
                throw new \Exception('Data penghuni tidak ditemukan. Silakan hubungi administrator.');
            }

            // Ambil log penghuni yang aktif
            $logAktif = LogPenghuni::where('penghuni_id', $penghuni->id)
                ->where('status', 'Aktif')
                ->first();

            if (!$logAktif) {
                throw new \Exception('Tidak ada kamar aktif ditemukan. Silakan hubungi administrator.');
            }

            $data['kamar_id'] = $logAktif->kamar_id;
            $data['unit_id'] = $logAktif->kamar->unit_id;
        }

        // Set default values
        $data['status'] = 'Baru';
        $data['tanggal_lapor'] = $data['tanggal_lapor'] ?? now()->toDateString();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Ticket berhasil dibuat')
            ->body('Ticket Anda telah berhasil dibuat dan akan segera ditindaklanjuti.');
    }
}
