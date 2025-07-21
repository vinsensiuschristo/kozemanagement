<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitVoucherRule extends Model
{
    protected $table = 'unit_voucher_rules';
    protected $fillable = [
        'unit_id',
        'voucher_id',
        'kuota_per_bulan',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }
}
