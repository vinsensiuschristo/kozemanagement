<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kamar extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'kamars';

    protected $fillable = [
        'unit_id',
        'tipe_kamar_id',
        'nama',
        'lantai',
        'terisi',
        'ukuran',
    ];

    public function unit()
    {
        return $this->belongsTo(\App\Models\Unit::class, 'unit_id');
    }

    public function tipeKamar()
    {
        return $this->belongsTo(TipeKamar::class, 'tipe_kamar_id', 'id');
    }

    public function fotoKamar()
    {
        return $this->hasMany(FotoKamar::class);
    }

    public function ketersediaan()
    {
        return $this->hasOne(KetersediaanKamar::class, 'kamar_id');
    }

    public function logs()
    {
        return $this->hasMany(LogPenghuni::class);
    }
}
