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
        $penghuni = $user->penghuni;

        if (!$penghuni) {
            Notification::make()
                ->title('Error')
                ->body('Data penghuni tidak ditemukan. Silakan hubungi admin.')
                ->danger()
                ->send();

            $this->halt();
        }

        // Ambil kamar aktif dari log penghuni
        $activeLog = LogPenghuni::where('penghuni_id', $penghuni->id)
            ->where('status', 'checkin')
            ->whereNull('tanggal_checkout')
            ->with('kamar.unit')
            ->first();

        if (!$activeLog) {
            Notification::make()
                ->title('Error')
                ->body('Tidak ada kamar aktif ditemukan. Silakan hubungi admin.')
                ->danger()
                ->send();

            $this->halt();
        }

        // Set data otomatis
        $data['user_id'] = $user->id;
        $data['kamar_id'] = $activeLog->kamar_id;
        $data['unit_id'] = $activeLog->kamar->unit_id;
        $data['tanggal_lapor'] = now()->toDateString();
        $data['status'] = 'Baru';

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Ticket berhasil dibuat';
    }

    protected function afterCreate(): void
    {
        // Kirim notifikasi ke admin
        $admins = \App\Models\User::role(['Admin', 'Superadmin'])->get();

        \Filament\Notifications\Notification::make()
            ->title('Ticket Baru')
            ->body("Ticket baru '{$this->record->judul}' telah dibuat oleh {$this->record->user->name}")
            ->icon('heroicon-o-ticket')
            ->color('warning')
            ->actions([
                \Filament\Notifications\Actions\Action::make('view')
                    ->label('Lihat Ticket')
                    ->url(route('filament.admin.resources.tickets.conversation', $this->record))
                    ->button(),
            ])
            ->sendToDatabase($admins);
    }
}
