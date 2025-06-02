<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class KetersediaanKamar extends Model
{
    use HasUuids;

    protected $fillable = [
        'tipe_kamar_id',
        'nama',
        'lantai',
        'status',
    ];

    public function tipeKamar()
    {
        return $this->belongsTo(TipeKamar::class, 'tipe_kamar_id');
    }
}
