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
}
