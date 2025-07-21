<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PenghuniVoucher extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'penghuni_vouchers';
    protected $fillable = [
        'penghuni_id',
        'voucher_id',
        'periode',
        'is_used',
        'digunakan_pada_mitra_id',
    ];

    protected $casts = [
        'periode' => 'date',
        'is_used' => 'boolean',
    ];

    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class, 'penghuni_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    public function digunakanPadaMitra()
    {
        return $this->belongsTo(Mitra::class, 'digunakan_pada_mitra_id');
    }   
}
