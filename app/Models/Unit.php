<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Unit extends Model
{
    use HasUuids;

    protected $fillable = [
        'id_owner',
        'nomor_kontrak',
        'tanggal_awal_kontrak',
        'tanggal_akhir_kontrak',
        'nama_cluster',
        'alamat',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class, 'id_owner', 'id');
    }
}
