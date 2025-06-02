<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table = 'fasilitas';

    protected $fillable = [
        'nama',
        'tipe',
    ];

    public function units()
    {
        return $this->belongsToMany(Unit::class, 'fasilitas_units', 'fasilitas_id', 'unit_id');
    }
}
