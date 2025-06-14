<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FasilitasTipeKamar extends Model
{
    use HasFactory;

    protected $table = 'fasilitas_tipe_kamars';
    public $incrementing = false;

    protected $fillable = [
        'tipe_kamar_id',
        'fasilitas_id',
    ];

    public function tipeKamar()
    {
        return $this->belongsTo(TipeKamar::class, 'tipe_kamar_id', 'id');
    }

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class, 'fasilitas_id', 'id');
    }
}
