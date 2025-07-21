<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'vouchers';
    protected $fillable = [
        'nama',
        'deskripsi',
        'kode_voucher',
        'mitra_id',
    ];

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }

    public function unitVoucherRules()
    {
        return $this->hasMany(UnitVoucherRule::class, 'voucher_id');
    }

    public function penghuniVouchers()
    {
        return $this->hasMany(PenghuniVoucher::class, 'voucher_id');
    }
}
