<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('email:penghuni-birthday')->dailyAt('08:00')
->timezone('Asia/Jakarta')
->onOneServer();