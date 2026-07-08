<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule::command(
//     'facebook:sync'
// )->everyMinute();

Schedule::command(
    'facebook:comment-sync'
)->everyMinute();

Schedule::command(
    'facebook:post-sync'
)->everyMinute();

Schedule::command('ticket:check-escalation')->everyThirtyMinutes();

// Jalankan sinkronisasi Instagram DM setiap 5 menit (karena ini menggunakan Playwright headless, tidak disarankan setiap menit)
Schedule::command('instagram:sync-dm')->everyFiveMinutes();