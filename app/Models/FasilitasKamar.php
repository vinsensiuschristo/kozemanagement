<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class FasilitasKamar extends Model
{
    use HasUuids;

    protected $fillable = ['nama', 'deskripsi'];
}
