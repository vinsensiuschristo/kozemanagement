<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class FotoUnit extends Model
{
    use HasUuids;

    protected $table = 'foto_units';

    protected $fillable = [
        'unit_id',
        'kategori',
        'path',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
