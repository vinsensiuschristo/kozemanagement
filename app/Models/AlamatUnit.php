<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlamatUnit extends Model
{
    protected $table = 'alamat_unit';

    protected $fillable = [
        'unit_id',
        'alamat',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'deskripsi',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
