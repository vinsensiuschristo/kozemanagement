<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Kamar extends Model
{
    use HasUuids;

    protected $table = 'kamars';

    protected $fillable = [
        'id_unit',
        'id_jenis_kamar',
        'no_kamar',
        'harga',
        'no_kwh',
        'status',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'id_unit');
    }

    public function jenisKamar()
    {
        return $this->belongsTo(JenisKamar::class, 'id_jenis_kamar');
    }

    // Shortcut ke foto (melalui jenis kamar)
    public function detailFotoKamar()
    {
        // Opsi Shortcut:
        // return $this->jenisKamar?->detailFotoKamar();

        // Atau bisa juga dengan cara ini:
        return $this->hasManyThrough(
            DetailFotoKamar::class,
            JenisKamar::class,
            'id', // Foreign key di JenisKamar (local key di Kamar)
            'jenis_kamar_id', // Foreign key di DetailFotoKamar
            'id_jenis_kamar', // Local key di Kamar
            'id' // Local key di JenisKamar
        );
    }

    public static function getStatuses(): array
    {
        return ['tersedia', 'terisi', 'booked'];
    }
}
