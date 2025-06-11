<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Pengeluaran extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'unit_id',
        'tanggal',
        'jumlah',
        'kategori',
        'deskripsi',
        'bukti',
        'is_konfirmasi',
        'created_by',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
