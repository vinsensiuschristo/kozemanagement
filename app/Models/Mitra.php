<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'mitras';
    protected $fillable = [
        'nama',
        'kategori',
        'telepon',
        'alamat',
        'deskripsi',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'mitra_id');
    }

    public function penggunaanVouchers()
    {
        return $this->hasMany(PenghuniVoucher::class, 'digunakan_pada_mitra_id');
    }
}
