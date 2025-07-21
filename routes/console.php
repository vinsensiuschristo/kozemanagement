<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ini buat scheduler CRON JOB
// Email ulang tahun
Schedule::command('email:penghuni-birthday')->dailyAt('08:00')
->timezone('Asia/Jakarta')
->onOneServer();

// generate voucher per bulan
Schedule::command('generate:monthly-vouchers')->monthlyOn(1, '01:00')
->timezone('Asia/Jakarta');