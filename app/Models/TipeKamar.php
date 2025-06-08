<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Models\FasilitasUnit;


class TipeKamar extends Model
{
    use HasFactory;

    protected $table = 'tipe_kamars';

    public $incrementing = false;
    // protected $keyType = 'uuid';

    protected $fillable = [
        'id',
        'unit_id',
        'nama_tipe',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid(); // <- auto generate UUID
            }
        });
    }

    public function unit()
    {
        return $this->belongsTo(\App\Models\Unit::class, 'unit_id', 'id');
    }

    public function fotoKamars()
    {
        return $this->hasMany(FotoKamar::class, 'tipe_kamar_id', 'id');
    }

    public function hargaKamars()
    {
        return $this->hasOne(\App\Models\HargaKamar::class, 'tipe_kamar_id', 'id');
    }

    public function fasilitasKos()
    {
        return $this->hasMany(FasilitasUnit::class, 'tipe_kamar_id');
    }

    public function fasilitas()
    {
        return $this->belongsToMany(
            Fasilitas::class,
            'fasilitas_tipe_kamars',
            'tipe_kamar_id',
            'fasilitas_id'
        );
    }

    public function kamars()
    {
        return $this->hasMany(Kamar::class, 'tipe_kamar_id');
    }

    public function ketersediaanKamars()
    {
        return $this->hasManyThrough(
            \App\Models\KetersediaanKamar::class,
            \App\Models\Kamar::class,
            'tipe_kamar_id', // FK di Kamar
            'kamar_id',      // FK di KetersediaanKamar
            'id',            // PK di TipeKamar
            'id'             // PK di Kamar
        );
    }

    // Model TipeKamar.php

    public function harga()
    {
        return $this->hasOne(HargaKamar::class, 'tipe_kamar_id');
    }
}
