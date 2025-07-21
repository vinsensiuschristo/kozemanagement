<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasUuids, HasFactory;

    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'id_owner',
        'nomor_kontrak',
        'tanggal_awal_kontrak',
        'tanggal_akhir_kontrak',
        'nama_cluster',
        'multi_tipe',
        'disewakan_untuk',
        'deskripsi',
        'tahun_dibangun',
        'nomor_kontrak',
        'tanggal_awal_kontrak',
        'tanggal_akhir_kontrak',
        'status',
    ];

    public function tipeKamars()
    {
        return $this->hasMany(\App\Models\TipeKamar::class, 'unit_id');
    }

    public function alamat()
    {
        return $this->hasOne(AlamatUnit::class, 'unit_id');
    }

    public function fotoUnit()
    {
        return $this->hasMany(FotoUnit::class, 'unit_id');
    }

    public function fasilitasUnits()
    {
        return $this->hasMany(FasilitasUnit::class, 'unit_id', 'id');
    }

    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'fasilitas_units', 'unit_id', 'fasilitas_id')->withTimestamps();
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class, 'id_owner');
    }


    public function kamars()
    {
        return $this->hasMany(\App\Models\Kamar::class, 'unit_id');
    }

    public function hargaKamars()
    {
        return $this->hasManyThrough(
            HargaKamar::class,
            TipeKamar::class,
            'unit_id',       // FK di tipe_kamars
            'tipe_kamar_id', // FK di harga_kamars
            'id',            // PK di units
            'id'             // PK di tipe_kamars
        );
    }

    public function ketersediaanKamars()
    {
        return $this->hasMany(KetersediaanKamar::class, 'unit_id');
    }

    public function pemasukans()
    {
        return $this->hasMany(Pemasukan::class, 'unit_id');
    }

    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class, 'unit_id');
    }

    public function voucherRules()
    {
        return $this->hasMany(UnitVoucherRule::class, 'unit_id');
    }

}
