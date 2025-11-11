<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('simulacion:sync')->everyMinute();
// si quieres con notas cada 5 min:
// Schedule::command('simulacion:sync --with-notas')->everyFiveMinutes();
