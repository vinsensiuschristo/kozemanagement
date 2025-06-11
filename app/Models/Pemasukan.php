<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Pemasukan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'unit_id',
        'penghuni_id',
        'kamar_id',
        'checkin_id',
        'tanggal',
        'jumlah',
        'deskripsi',
        'bukti',
        'is_konfirmasi',
        'created_by',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class);
    }
    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }

    // logPenghuni
    public function checkin()
    {
        return $this->belongsTo(LogPenghuni::class, 'checkin_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
