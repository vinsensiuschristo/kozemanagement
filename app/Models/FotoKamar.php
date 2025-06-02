<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class FotoKamar extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'foto_kamars';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'kamar_id',
        'kategori',
        'path',
    ];

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }
}
