<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Owner extends Model
{
    use HasUuids, HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama',
        'user_id',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'nomor_telepon',
        'email',
        'alamat',
        'bank',
        'nomor_rekening',
        'nomor_ktp',
        'foto_ktp',
    ];

    public function units()
    {
        return $this->hasMany(Unit::class, 'id_owner', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
