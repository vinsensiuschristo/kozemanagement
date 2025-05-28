<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DetailFotoKamar extends Model
{
    use HasUuids;

    protected $table = 'detail_foto_kamars';

    protected $fillable = [
        'jenis_kamar_id',
        'path',
    ];

    public function jenisKamar()
    {
        return $this->belongsTo(JenisKamar::class);
    }
}
