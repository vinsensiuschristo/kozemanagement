<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KetersediaanKamar extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'kamar_id',
        'status',
    ];

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }
}
