<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketMessage extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'attachment',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::created(function ($message) {
            // Kirim notifikasi pesan baru
            $message->sendNewMessageNotification();
        });
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sendNewMessageNotification()
    {
        $ticket = $this->ticket;
        $sender = $this->user;

        // Tentukan penerima notifikasi
        if ($sender->hasRole(['Admin', 'Superadmin'])) {
            // Jika pengirim admin, kirim ke user pemilik ticket
            $recipient = $ticket->user;
            $senderRole = 'Admin';
        } else {
            // Jika pengirim user, kirim ke semua admin
            $recipients = \App\Models\User::role(['Admin', 'Superadmin'])->get();
            $senderRole = 'User';
        }

        $title = "Pesan Baru dari {$senderRole}";
        $body = "Pesan baru di ticket '{$ticket->judul}': " . \Illuminate\Support\Str::limit($this->message, 50);

        $notification = \Filament\Notifications\Notification::make()
            ->title($title)
            ->body($body)
            ->icon('heroicon-o-chat-bubble-left')
            ->color('info')
            ->actions([
                \Filament\Notifications\Actions\Action::make('reply')
                    ->label('Balas')
                    ->url(route('filament.admin.resources.tickets.conversation', $ticket))
                    ->button(),
            ]);

        if (isset($recipient)) {
            // Kirim ke user tunggal
            $notification->sendToDatabase($recipient);
        } else {
            // Kirim ke multiple admin
            $notification->sendToDatabase($recipients);
        }
    }
}
