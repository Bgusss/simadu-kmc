<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SyncFacebookMentions extends Command
{
    /**
     * Nama command.
     */
    protected $signature =
        'facebook:sync';

    /**
     * Deskripsi command.
     */
    protected $description =
        'Sinkronisasi seluruh mention Facebook';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info(
            '=== Sinkronisasi Facebook Dimulai ==='
        );

        /*
         * Sync komentar
         */
        $this->info(
            'Menjalankan sync komentar...'
        );

        Artisan::call(
            'facebook:comment-sync'
        );

        $this->line(
            Artisan::output()
        );

        /*
         * Sync postingan
         */
        $this->info(
            'Menjalankan sync postingan...'
        );

        Artisan::call(
            'facebook:post-sync'
        );

        $this->line(
            Artisan::output()
        );

        $this->info(
            '=== Sinkronisasi Selesai ==='
        );

        return Command::SUCCESS;
    }
}