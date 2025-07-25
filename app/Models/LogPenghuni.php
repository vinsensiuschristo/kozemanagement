<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class LogPenghuni extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'log_penghunis';

    protected $fillable = [
        'penghuni_id',
        'kamar_id',
        'tanggal',
        'status',
        'created_by',
    ];

    // Relasi ke Penghuni
    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class);
    }

    // Relasi ke Kamar
    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }

    // Relasi ke User (yang membuat log)
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pemasukan()
    {
        return $this->hasOne(Pemasukan::class, 'log_penghuni_id');
    }
}
