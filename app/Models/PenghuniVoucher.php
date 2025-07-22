<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PenghuniVoucher extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'penghuni_vouchers';

    protected $fillable = [
        'penghuni_id',
        'voucher_id',
        'is_used',
        'digunakan_pada_mitra_id',
        'tanggal_digunakan',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'tanggal_digunakan' => 'datetime',
    ];

    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class, 'penghuni_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    public function mitraDigunakan()
    {
        return $this->belongsTo(Mitra::class, 'digunakan_pada_mitra_id');
    }

    public function gunakan($mitraId = null)
    {
        $this->update([
            'is_used' => true,
            'digunakan_pada_mitra_id' => $mitraId,
            'tanggal_digunakan' => Carbon::now(),
        ]);
    }

    public function scopeBelumDigunakan($query)
    {
        return $query->where('is_used', false);
    }

    public function scopeSudahDigunakan($query)
    {
        return $query->where('is_used', true);
    }
}
