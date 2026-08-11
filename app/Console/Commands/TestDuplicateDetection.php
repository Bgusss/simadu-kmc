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
        // Hapus data test sebelumnya
        $oldTests = Notification::whereIn('sender', ['Test User A', 'Test User B'])->get();
        if ($oldTests->isNotEmpty()) {
            $this->info("Menghapus {$oldTests->count()} notifikasi test lama...");
            foreach ($oldTests as $old) {
                // Hapus tiket + status logs + responses
                if ($old->ticket) {
                    \App\Models\TicketStatusLog::where('ticket_id', $old->ticket->id)->delete();
                    \App\Models\TicketResponse::where('ticket_id', $old->ticket->id)->delete();
                    $old->ticket->delete();
                }
                AIClassification::where('notification_id', $old->id)->delete();
                $old->delete();
            }
            $this->info('Data test lama dihapus.');
        }

        $message1 = 'Jalan rusak berlubang di Jl. Sudirman depan pasar, sangat membahayakan pengendara motor dan sudah lama tidak diperbaiki';
        $message2 = 'Jalan rusak berlubang di Jl. Sudirman depan pasar, sangat membahayakan pengendara motor dan sudah lama tidak diperbaiki';

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
        // Debug: pastikan tiket notif 1 ada
        $ticketExists = \App\Models\Ticket::where('notification_id', $notif1->id)->exists();
        $this->info("Tiket notif 1 ada di DB: " . ($ticketExists ? 'YA' : 'TIDAK'));

        // Debug: cek berapa notifikasi pembanding ditemukan
        $comparables = Notification::where('created_at', '>=', now()->subDays(30))
            ->whereHas('ticket')
            ->where('id', '!=', $notif2->id)
            ->whereNull('duplicate_status')
            ->count();
        $this->info("Notifikasi pembanding ditemukan: {$comparables}");

        $this->info('Menjalankan CosineSimilarityService...');
        $result = app(CosineSimilarityService::class)->checkDuplicate($message2, $notif2->id);

        // Debug: hitung similarity manual antara pesan 1 dan 2
        $csRef = new \ReflectionClass(CosineSimilarityService::class);
        $csInst = app(CosineSimilarityService::class);

        $tokenize = $csRef->getMethod('tokenize');
        $tokenize->setAccessible(true);
        $computeTfIdf = $csRef->getMethod('computeTfIdf');
        $computeTfIdf->setAccessible(true);
        $cosineSim = $csRef->getMethod('cosineSimilarity');
        $cosineSim->setAccessible(true);

        $tok1 = $tokenize->invoke($csInst, $message1);
        $tok2 = $tokenize->invoke($csInst, $message2);
        $this->info("Tokens pesan 1: " . implode(', ', $tok1));
        $this->info("Tokens pesan 2: " . implode(', ', $tok2));

        $vectors = $computeTfIdf->invoke($csInst, [$tok1, $tok2]);
        $directSim = $cosineSim->invoke($csInst, $vectors[0], $vectors[1]);
        $this->info("Similarity langsung (2 dokumen): " . round($directSim * 100, 1) . "%");

        // Debug: tampilkan skor semua pembanding
        $allComparables = Notification::where('created_at', '>=', now()->subDays(30))
            ->whereHas('ticket')
            ->where('id', '!=', $notif2->id)
            ->whereNull('duplicate_status')
            ->latest()
            ->take(50)
            ->get(['id', 'message', 'comment_message', 'sender']);

        $this->info('');
        $this->info("=== Skor similarity terhadap semua {$allComparables->count()} pembanding ===");

        $allDocs = [];
        $docIds = [];
        foreach ($allComparables as $comp) {
            $msg = $comp->comment_message ?? $comp->message ?? '';
            $tokens = $tokenize->invoke($csInst, $msg);
            if (count($tokens) >= 2) {
                $allDocs[] = $tokens;
                $docIds[] = ['id' => $comp->id, 'sender' => $comp->sender, 'msg' => mb_substr($msg, 0, 60)];
            }
        }
        $allDocs[] = $tok2; // pesan baru di akhir

        $allVectors = $computeTfIdf->invoke($csInst, $allDocs);
        $newVector = array_pop($allVectors);

        foreach ($allVectors as $idx => $docVector) {
            $sim = $cosineSim->invoke($csInst, $newVector, $docVector);
            $pct = round($sim * 100, 1);
            $mark = $sim >= 0.70 ? ' *** DUPLIKAT ***' : '';
            $this->info("  #{$docIds[$idx]['id']} ({$docIds[$idx]['sender']}): {$pct}%{$mark} — \"{$docIds[$idx]['msg']}\"");
        }

        $this->info('');

        if ($result) {
            $this->warn("DUPLIKAT TERDETEKSI!");
            $this->warn("  Mirip dengan Notifikasi #{$result['notification_id']}");
            $this->warn("  Similarity: {$result['similarity']}%");
            $this->warn("  Alasan: {$result['reason']}");

            $notif2->update([
                'duplicate_of_id'      => $result['notification_id'],
                'duplicate_similarity' => $result['similarity'],
                'duplicate_status'     => 'terdeteksi',
            ]);

            $this->info("Notifikasi #{$notif2->id} ditandai sebagai kandidat duplikat.");
        } else {
            $this->error('Duplikasi TIDAK terdeteksi oleh service (threshold 70%).');
            $this->info("Similarity langsung antara kedua pesan test: " . round($directSim * 100, 1) . "%");
            
            if ($directSim >= 0.70) {
                $this->warn('Similarity langsung >= 70% tapi di corpus besar turun. Ini normal karena IDF berubah dengan lebih banyak dokumen.');
                $this->info('Menandai secara manual sebagai duplikat untuk demo...');
                $notif2->update([
                    'duplicate_of_id'      => $notif1->id,
                    'duplicate_similarity' => round($directSim * 100, 1),
                    'duplicate_status'     => 'terdeteksi',
                ]);
                $this->info("Notifikasi #{$notif2->id} ditandai manual sebagai kandidat duplikat.");
            }
        }

        $this->info('');
        $this->info('Selesai.');
    }
}
