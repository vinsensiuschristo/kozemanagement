<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Ticket extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'unit_id',
        'kamar_id',
        'judul',
        'deskripsi',
        'kategori',
        'prioritas',
        'status',
        'foto',
        'response_admin',
        'rating',
        'tanggal_selesai',
        'tanggal_lapor',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }
}
