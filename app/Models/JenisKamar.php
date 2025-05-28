<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class JenisKamar extends Model
{
    use HasUuids;

    protected $fillable = [
        'nama',
        'harga',
        'deskripsi',
    ];

    public function fotoKamar()
    {
        return $this->hasMany(DetailFotoKamar::class, 'id_jenis_kamar');
    }

    public function fasilitasKamar()
    {
        return $this->belongsToMany(FasilitasKamar::class, 'fasilitas_jenis_kamar', 'jenis_kamar_id', 'fasilitas_kamar_id');
    }

    public function kamars()
    {
        return $this->hasMany(Kamar::class, 'id_jenis_kamar');
    }
}
