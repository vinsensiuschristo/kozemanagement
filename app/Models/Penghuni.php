<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Penghuni extends Model
{
    use HasFactory;

    protected $table = 'penghunis';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'kode',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'no_telp',
        'email',
        'kontak_darurat',
        'hubungan_kontak_darurat',
        'kendaraan',
        'foto_ktp',
        'referensi',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    // Relasi: Penghuni memiliki banyak log checkin/checkout
    public function logs()
    {
        return $this->hasMany(LogPenghuni::class);
    }
}
