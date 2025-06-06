<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class KetersediaanKamar extends Model
{
    use HasUuids;

    protected $fillable = [
        'kamar_id',
        'status',
    ];

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }
}
