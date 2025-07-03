<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Ticket extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'uuid';

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
        'tanggal_lapor',
        'tanggal_selesai',
        'response_admin',
        'rating',
    ];

    protected $casts = [
        'tanggal_lapor' => 'datetime',
        'tanggal_selesai' => 'datetime',
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

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'open' => 'danger',
            'in_progress' => 'warning', 
            'resolved' => 'success',
            'closed' => 'gray',
            default => 'gray'
        };
    }

    public function getPrioritasColorAttribute()
    {
        return match($this->prioritas) {
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'danger',
            'urgent' => 'danger',
            default => 'gray'
        };
    }
}
