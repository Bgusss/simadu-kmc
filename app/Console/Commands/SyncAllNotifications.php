<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SyncAllNotifications extends Command
{
    /**
     * Nama command.
     */
    protected $signature = 'notifications:sync-all 
                            {--force : Paksa jalankan meski ada proses lain}
                            {--min-delay=30 : Delay minimum antar-scraper (detik)}
                            {--max-delay=90 : Delay maksimum antar-scraper (detik)}';

    /**
     * Deskripsi command.
     */
    protected $description = 'Sinkronisasi semua sumber notifikasi (Facebook Post, Facebook Comment, Instagram DM) secara berurutan dengan jeda acak';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lockKey = 'notifications:sync-all';
        $lockDuration = 900; // 15 menit max

        // Cek apakah ada proses sync lain yang sedang berjalan
        if (!$this->option('force') && Cache::has($lockKey)) {
            $this->warn('⚠️  Proses sinkronisasi masih berjalan. Gunakan --force untuk paksa jalankan.');
            $this->info('   Lock key: ' . $lockKey);
            $this->info('   Expired: ' . Cache::get($lockKey));
            return Command::FAILURE;
        }

        // Set lock
        Cache::put($lockKey, now()->addSeconds($lockDuration)->toDateTimeString(), $lockDuration);

        $this->info('🚀 === Sinkronisasi Semua Notifikasi Dimulai ===');
        $this->info('   Waktu mulai: ' . now()->format('Y-m-d H:i:s'));
        $this->newLine();

        $startTime = microtime(true);
        $minDelay = (int) $this->option('min-delay');
        $maxDelay = (int) $this->option('max-delay');

        try {
            // 1. Facebook Post Mentions
            $this->info('📘 [1/3] Menjalankan Facebook Post sync...');
            $postStart = microtime(true);
            
            Artisan::call('facebook:post-sync');
            $this->line(Artisan::output());
            
            $postDuration = round(microtime(true) - $postStart, 2);
            $this->info("   ✓ Selesai dalam {$postDuration}s");
            $this->newLine();

            // Jeda acak sebelum scraper berikutnya
            $delay1 = rand($minDelay, $maxDelay);
            $this->info("⏳ Menunggu {$delay1} detik sebelum scraper berikutnya...");
            sleep($delay1);
            $this->newLine();

            // 2. Facebook Comment Mentions
            $this->info('💬 [2/3] Menjalankan Facebook Comment sync...');
            $commentStart = microtime(true);
            
            Artisan::call('facebook:comment-sync');
            $this->line(Artisan::output());
            
            $commentDuration = round(microtime(true) - $commentStart, 2);
            $this->info("   ✓ Selesai dalam {$commentDuration}s");
            $this->newLine();

            // Jeda acak sebelum Instagram
            $delay2 = rand($minDelay, $maxDelay);
            $this->info("⏳ Menunggu {$delay2} detik sebelum scraper berikutnya...");
            sleep($delay2);
            $this->newLine();

            // 3. Instagram DM
            $this->info('📷 [3/3] Menjalankan Instagram DM sync...');
            $igStart = microtime(true);
            
            Artisan::call('instagram:sync-dm');
            $this->line(Artisan::output());
            
            $igDuration = round(microtime(true) - $igStart, 2);
            $this->info("   ✓ Selesai dalam {$igDuration}s");
            $this->newLine();

            // Summary
            $totalDuration = round(microtime(true) - $startTime, 2);
            $totalDelay = $delay1 + $delay2;
            $scrapingTime = $postDuration + $commentDuration + $igDuration;

            $this->info('✅ === Sinkronisasi Selesai ===');
            $this->info("   Total waktu: {$totalDuration}s");
            $this->info("   - Scraping: {$scrapingTime}s");
            $this->info("   - Delay: {$totalDelay}s");
            $this->info('   Waktu selesai: ' . now()->format('Y-m-d H:i:s'));

            // Release lock
            Cache::forget($lockKey);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Error saat sinkronisasi: ' . $e->getMessage());
            $this->line($e->getTraceAsString());

            // Release lock jika error
            Cache::forget($lockKey);

            return Command::FAILURE;
        }
    }
}
