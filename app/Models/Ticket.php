<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'kategori',
        'prioritas',
        'judul',
        'deskripsi',
        'foto',
        'user_id',
        'kamar_id',
        'unit_id',
        'tanggal_lapor',
        'tanggal_selesai',
        'status',
        'respon_admin',
    ];

    protected $casts = [
        'tanggal_lapor' => 'date',
        'tanggal_selesai' => 'date',
    ];

    protected static function booted()
    {
        static::updated(function ($ticket) {
            // Kirim notifikasi ketika status berubah
            if ($ticket->isDirty('status')) {
                $ticket->sendStatusChangeNotification();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kamar(): BelongsTo
    {
        return $this->belongsTo(Kamar::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function canBeReplied(): bool
    {
        return !in_array($this->status, ['Selesai', 'Ditolak']);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Baru' => 'danger',
            'Diproses' => 'warning',
            'Selesai' => 'success',
            'Ditolak' => 'gray',
            default => 'gray'
        };
    }

    public function getPrioritasColorAttribute(): string
    {
        return match ($this->prioritas) {
            'Rendah' => 'gray',
            'Sedang' => 'info',
            'Tinggi' => 'warning',
            'Mendesak' => 'danger',
            default => 'gray'
        };
    }

    public function sendStatusChangeNotification()
    {
        $user = $this->user;

        if (!$user)
            return;

        $title = match ($this->status) {
            'Selesai' => 'Ticket Selesai',
            'Ditolak' => 'Ticket Ditolak',
            'Diproses' => 'Ticket Sedang Diproses',
            default => 'Status Ticket Berubah'
        };

        $body = match ($this->status) {
            'Selesai' => "Ticket '{$this->judul}' telah diselesaikan oleh admin.",
            'Ditolak' => "Ticket '{$this->judul}' ditolak oleh admin.",
            'Diproses' => "Ticket '{$this->judul}' sedang diproses oleh admin.",
            default => "Status ticket '{$this->judul}' berubah menjadi {$this->status}."
        };

        $color = match ($this->status) {
            'Selesai' => 'success',
            'Ditolak' => 'danger',
            'Diproses' => 'warning',
            default => 'info'
        };

        $icon = match ($this->status) {
            'Selesai' => 'heroicon-o-check-circle',
            'Ditolak' => 'heroicon-o-x-circle',
            'Diproses' => 'heroicon-o-clock',
            default => 'heroicon-o-bell'
        };

        \Filament\Notifications\Notification::make()
            ->title($title)
            ->body($body)
            ->icon($icon)
            ->color($color)
            ->actions([
                \Filament\Notifications\Actions\Action::make('view')
                    ->label('Lihat Ticket')
                    ->url(route('filament.admin.resources.tickets.conversation', $this))
                    ->button(),
            ])
            ->sendToDatabase($user);
    }

    public function getUnreadMessagesCountForUser($userId): int
    {
        return $this->messages()
            ->where('user_id', '!=', $userId)
            ->whereNull('read_at')
            ->count();
    }
}
