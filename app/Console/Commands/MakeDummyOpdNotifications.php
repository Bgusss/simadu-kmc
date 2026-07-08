<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeDummyOpdNotifications extends Command
{
    /**
     * The name and signature of the console command.
     * 
     * Anda bisa memanggilnya dengan:
     * php artisan notifopd:dummy
     * Atau untuk OPD ID spesifik:
     * php artisan notifopd:dummy 3
     *
     * @var string
     */
    protected $signature = 'notifopd:dummy {opd_id=1 : ID OPD yang akan dikirimkan notif dummy}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate dummy ticket notifications for a specific OPD UI testing via Cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $opdId = $this->argument('opd_id');

        // ==========================================
        // CUSTOMIZE KONTEN DUMMY DI BAWAH INI
        // ==========================================
        $jumlahNotif = 5;
        $subKategori = 'Jalan Berlubang';
        // ==========================================

        $this->info("Membuat $jumlahNotif notifikasi tiket dummy untuk OPD ID $opdId di Cache...");

        $dummyNotifs = [];
        // Gunakan ID yang sangat tinggi agar terbaca sebagai notif terbaru oleh javascript frontend
        $baseId = \App\Models\Ticket::max('id') ?? 0;

        $platforms = ['WhatsApp', 'Instagram', 'Facebook', 'Web SIMADU'];

        for ($i = 1; $i <= $jumlahNotif; $i++) {
            $trackingNumber = 'KMC-TEST-' . rand(1000, 9999);
            $platform = $platforms[array_rand($platforms)];
            
            $dummyNotifs[] = [
                'id' => $baseId + 5000 + $i, 
                'title' => $platform,
                'sender' => "Warga Dummy $i",
                'message' => "Terdapat disposisi tiket laporan baru dengan nomor resi {$trackingNumber} terkait {$subKategori}. Mohon agar dapat segera ditindaklanjuti.",
                'permalink' => url("/opd/tickets"), 
                'is_read' => false,
                'created_at' => 'baru saja',
                'created_at_raw' => now()->toISOString(),
            ];
        }

        // Simpan ke cache selama 60 detik dengan key unik milik OPD bersangkutan
        \Illuminate\Support\Facades\Cache::put("dummy_opd_notifications_{$opdId}", $dummyNotifs, 60);

        $this->info("Berhasil! Silakan login sebagai OPD (dengan ID $opdId) dan periksa halaman dashboard dalam 60 detik.");
    }
}
