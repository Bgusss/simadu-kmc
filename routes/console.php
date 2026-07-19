<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 * ═══════════════════════════════════════════════════════════════════════
 * SINKRONISASI NOTIFIKASI DARI SOSIAL MEDIA
 * ═══════════════════════════════════════════════════════════════════════
 * 
 * PERINGATAN: Scraping sosial media berisiko terkena rate limit atau ban
 * jika terlalu sering. Jadwal di bawah adalah KONSERVATIF untuk keamanan.
 * 
 * Command: notifications:sync-all
 * - Menjalankan 3 scraper secara BERURUTAN (bukan paralel)
 * - Jeda acak 30-90 detik antar scraper
 * - Pengaman overlap via cache lock
 * - Total durasi: ~5-10 menit per run
 * 
 * Jadwal default: Setiap 2 jam sekali
 * Alternatif aman: ->twiceDaily(9, 21) untuk 2x sehari (pagi & malam)
 * 
 * JANGAN ubah ke everyMinute() atau terlalu sering!
 */

// Sinkronisasi semua notifikasi setiap 2 jam
Schedule::command('notifications:sync-all')
    ->everyTwoHours()
    ->withoutOverlapping(15) // Maksimal 15 menit, setelah itu lock expired
    ->runInBackground()
    ->onSuccess(function () {
        \Log::info('✅ Sinkronisasi notifikasi berhasil');
    })
    ->onFailure(function () {
        \Log::error('❌ Sinkronisasi notifikasi gagal');
    });

// Eskalasi tiket tetap jalan setiap 30 menit
Schedule::command('ticket:check-escalation')->everyThirtyMinutes();