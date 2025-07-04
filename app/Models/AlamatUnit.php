<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlamatUnit extends Model
{
    use HasFactory;
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
