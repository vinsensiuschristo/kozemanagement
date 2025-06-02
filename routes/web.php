<?php

use App\Models\Unit;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/debug-kamar', function () {
    $unit = Unit::with('tipeKamars.ketersediaanKamars')->has('tipeKamars.ketersediaanKamars')->first();

    $ketersediaanKamars = $unit->tipeKamars
        ->flatMap(fn($tipe) => $tipe->ketersediaanKamars);

    dd($ketersediaanKamars);
});
