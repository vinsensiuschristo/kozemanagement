<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;


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

    public function ketersediaanKamars()
    {
        return $this->hasMany(KetersediaanKamar::class, 'tipe_kamar_id', 'id');
    }

    public function hargaKamars()
    {
        return $this->hasOne(\App\Models\HargaKamar::class, 'tipe_kamar_id', 'id');
    }
}
