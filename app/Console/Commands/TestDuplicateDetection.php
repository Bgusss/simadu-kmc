<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\AIClassification;
use App\Services\CosineSimilarityService;
use App\Services\TicketingService;

class TestDuplicateDetection extends Command
{
    protected $signature = 'test:duplicate';
    protected $description = 'Buat 2 notifikasi mirip untuk menguji fitur deteksi duplikasi cosine similarity';

    public function handle()
    {
        $message1 = 'Jalan rusak berlubang di Jl. Sudirman depan pasar, sangat membahayakan pengendara motor dan sudah lama tidak diperbaiki';
        $message2 = 'Jalan berlubang dan rusak di Jl. Sudirman dekat pasar, bahaya untuk pengendara motor, mohon segera diperbaiki';

        $this->info('=== Test Deteksi Duplikasi ===');
        $this->info('');

        // 1. Buat notifikasi pertama + tiket (agar jadi pembanding)
        $notif1 = Notification::create([
            'title'   => 'Test Duplikasi - Asli',
            'sender'  => 'Test User A',
            'message' => $message1,
        ]);

        AIClassification::create([
            'notification_id'        => $notif1->id,
            'suggested_category'     => 'Infrastruktur',
            'suggested_sub_category' => 'Jalan',
            'suggested_opds'         => json_encode(['Dinas PUPR']),
            'priority'               => 'Tinggi',
            'confidence'             => 95,
            'reasoning'              => 'Test duplikasi - notifikasi asli',
        ]);

        // Buat tiket agar notifikasi 1 menjadi pembanding valid
        $aiResult = AIClassification::where('notification_id', $notif1->id)->first();
        try {
            $ticket = app(TicketingService::class)->createTicketFromClassification($notif1, $aiResult);
            $this->info("Notifikasi #1 (ID: {$notif1->id}) dibuat dengan tiket: {$ticket->tracking_number}");
        } catch (\Exception $e) {
            $this->error("Gagal buat tiket notif 1: " . $e->getMessage());
            return;
        }

        // 2. Buat notifikasi kedua (mirip)
        $notif2 = Notification::create([
            'title'   => 'Test Duplikasi - Mirip',
            'sender'  => 'Test User B',
            'message' => $message2,
        ]);

        AIClassification::create([
            'notification_id'        => $notif2->id,
            'suggested_category'     => 'Infrastruktur',
            'suggested_sub_category' => 'Jalan',
            'suggested_opds'         => json_encode(['Dinas PUPR']),
            'priority'               => 'Tinggi',
            'confidence'             => 94,
            'reasoning'              => 'Test duplikasi - notifikasi mirip',
        ]);

        $this->info("Notifikasi #2 (ID: {$notif2->id}) dibuat");
        $this->info('');

        // 3. Jalankan cosine similarity
        $this->info('Menjalankan CosineSimilarityService...');
        $result = app(CosineSimilarityService::class)->checkDuplicate($message2, $notif2->id);

        if ($result) {
            $this->warn("DUPLIKAT TERDETEKSI!");
            $this->warn("  Mirip dengan Notifikasi #{$result['notification_id']}");
            $this->warn("  Similarity: {$result['similarity']}%");
            $this->warn("  Alasan: {$result['reason']}");

            // Tandai sebagai terdeteksi duplikat
            $notif2->update([
                'duplicate_of_id'      => $result['notification_id'],
                'duplicate_similarity' => $result['similarity'],
                'duplicate_status'     => 'terdeteksi',
            ]);

            $this->info('');
            $this->info("Notifikasi #{$notif2->id} ditandai sebagai kandidat duplikat.");
            $this->info("Silakan buka halaman Notifikasi Admin dan filter 'Kandidat Duplikat' untuk memverifikasi.");
        } else {
            $this->error('Duplikasi TIDAK terdeteksi. Periksa pesan atau threshold.');
        }

        $this->info('');
        $this->info('Selesai.');
    }
}
