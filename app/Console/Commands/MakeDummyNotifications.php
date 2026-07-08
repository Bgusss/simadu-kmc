<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;

class MakeDummyNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notif:dummy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate 10 dummy notifications for UI testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $platforms = [
            'Pesan WhatsApp',
            'Instagram DM',
            'Facebook Mention',
            'Laporan Web SIMADU'
        ];

        $this->info('Membuat 10 notifikasi dummy di Cache...');

        $dummyNotifs = [];
        // We'll generate IDs higher than any existing notification to ensure the frontend accepts them as "new"
        $baseId = \App\Models\Notification::max('id') ?? 0;

        for ($i = 1; $i <= 10; $i++) {
            $platform = $platforms[$i % 4];
            
            $dummyNotifs[] = [
                'id' => $baseId + 1000 + $i, // Fake high ID
                'title' => $platform,
                'sender' => "Warga Dummy $i",
                'message' => "Pesan laporan dummy ke-$i dari $platform untuk menguji antrean dan tumpukan notifikasi pada dashboard Admin.",
                'is_read' => false,
                'created_at' => now(),
            ];
        }

        \Illuminate\Support\Facades\Cache::put('dummy_notifications', $dummyNotifs, 60); // Store for 60 seconds

        $this->info('Berhasil membuat 10 notifikasi dummy di Cache! Ini tidak akan masuk ke Database. Silakan periksa halaman panel admin.');
    }
}
