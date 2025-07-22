<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
        'kode_voucher',
        'mitra_id',
    ];

    public function mitra(): BelongsTo
    {
        return $this->belongsTo(Mitra::class);
    }

    public function penghuniVouchers(): HasMany
    {
        return $this->hasMany(PenghuniVoucher::class);
    }

    public function unitVoucherRules(): HasMany
    {
        return $this->hasMany(UnitVoucherRule::class);
    }

    // Accessor untuk nama dengan mitra
    public function getNamaDenganMitraAttribute(): string
    {
        if ($this->mitra) {
            return $this->nama . ' (' . $this->mitra->nama . ')';
        }

        return $this->nama . ' (Tanpa Mitra)';
    }

    // Scope untuk voucher aktif
    public function scopeAktif($query)
    {
        return $query->whereHas('mitra');
    }

    // Scope untuk voucher yang memiliki kuota
    public function scopeMemilikiKuota($query)
    {
        return $query->whereHas('mitra');
    }
}
