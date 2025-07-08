<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Actions\Action;
use Illuminate\Support\Str;

class TicketConversation extends Page
{
    protected static string $resource = TicketResource::class;
    protected static string $view = 'filament.resources.ticket-resource.pages.ticket-conversation';

    public ?Ticket $record = null;
    public string $replyMessage = '';

    public function mount(Ticket $record): void
    {
        $this->record = $record;

        // Tandai pesan dari admin sebagai sudah dibaca
        $this->record->messages()
            ->where('user_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
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

        if (!in_array($this->record->status, ['Selesai', 'Ditolak'])) {
            $this->record->update(['status' => 'Diproses']);
        }

        // Kirim notifikasi ke lawan bicara (user/admin)
        $recipients = User::role(auth()->user()->hasRole('Admin') ? 'User' : 'Admin')->get();

        Notification::make()
        ->title('Pesan Baru di Tiket: ' . $this->record->judul)
        ->body(Str::limit($this->replyMessage, 100))
        ->icon('heroicon-m-chat-bubble-left')
        ->color('info')
        ->actions([
            Action::make('Lihat')
                ->url(TicketResource::getUrl('conversation', ['record' => $this->record]))
                ->button(),
        ])
        ->sendToDatabase($recipients);

        $this->replyMessage = '';

        Notification::make()
        ->title('Balasan berhasil dikirim')
        ->success()
        ->send();

    }
}
