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

    public function logPemasukan()
    {
        return $this->hasMany(Pemasukan::class);
    }

    public function hargaKamar()
    {
        return $this->hasOneThrough(
            \App\Models\HargaKamar::class,
            \App\Models\TipeKamar::class,
            'id',             // Foreign key di TipeKamar ke HargaKamar
            'tipe_kamar_id',  // Foreign key di HargaKamar
            'tipe_kamar_id',  // Local key di Kamar
            'id'              // Local key di TipeKamar
        );
    }
}
