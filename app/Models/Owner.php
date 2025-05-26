<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Owner extends Model
{
    use HasUuids;
    protected $fillable = [
        'nama',
        'nomor_telepon',
        'email',
        'alamat',
        'nomor_ktp',
    ];

    public function units()
    {
        return $this->hasMany(Unit::class, 'id_owner', 'id');
    }
}
