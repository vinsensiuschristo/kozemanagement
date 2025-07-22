<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PenghuniVoucher extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'penghuni_id',
        'voucher_id',
        'is_used',
        'tanggal_digunakan',
        'mitra_id',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'tanggal_digunakan' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function penghuni(): BelongsTo
    {
        return $this->belongsTo(Penghuni::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public function mitra(): BelongsTo
    {
        return $this->belongsTo(Mitra::class);
    }

    // Method untuk menggunakan voucher
    public function gunakan(?int $mitraId = null): bool
    {
        if ($this->is_used) {
            return false;
        }

        $this->update([
            'is_used' => true,
            'tanggal_digunakan' => now(),
            'mitra_id' => $mitraId ?? $this->voucher->mitra_id,
        ]);

        return true;
    }

    // Scope untuk voucher yang belum digunakan
    public function scopeTersedia($query)
    {
        return $query->where('is_used', false);
    }

    // Scope untuk voucher yang sudah digunakan
    public function scopeTerpakai($query)
    {
        return $query->where('is_used', true);
    }
}
