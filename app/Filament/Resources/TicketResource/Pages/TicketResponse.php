<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Pages\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;

class TicketResponse extends Page
{
    protected static string $resource = TicketResource::class;
    protected static string $view = 'filament.resources.ticket-resource.pages.ticket-response';

    public Ticket $record;
    public string $replyMessage = '';

    // Livewirev3
    #[Computed]
    public function getMessageHistory()
    {
        return $this->record
            ->messages()
            ->with('user')
            ->orderBy('created_at')
            ->get();
    }

    public function submitReply(): void
    {
        $this->validate([
            'replyMessage' => 'required|string|min:3',
        ]);

        TicketMessage::create([
            'ticket_id' => $this->record->id,
            'user_id'   => Auth::id(),
            'message'   => $this->replyMessage,
        ]);

        // Ubah status jika masih "Baru"
        if ($this->record->status === 'Baru') {
            $this->record->update(['status' => 'Diproses']);
        }

        $this->replyMessage = '';

        Notification::make()
            ->title('Balasan berhasil dikirim')
            ->success()
            ->send();
    }

    public function markAsProcessed(): void
    {
        $this->record->update(['status' => 'Diproses']);

        Notification::make()
            ->title('Tiket ditandai sebagai Diproses')
            ->success()
            ->send();
    }

    public function rejectTicket(): void
    {
        $this->record->update(['status' => 'Ditolak']);

        Notification::make()
            ->title('Tiket berhasil ditolak')
            ->danger()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Tolak')
                ->color('danger')
                ->action(fn () => $this->rejectTicket())
                ->visible(fn () => $this->record->status !== 'Ditolak'),
        ];
    }
}
