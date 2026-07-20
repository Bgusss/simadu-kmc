<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use App\Models\InstagramMention;
use App\Models\Notification;
use App\Models\AIClassification;
use App\Services\AIClassificationService;
use App\Services\TicketingService;

class SyncInstagramMentions extends Command
{
    /**
     * Nama command.
     */
    protected $signature = 'instagram:sync-dm';

    /**
     * Deskripsi command.
     */
    protected $description = 'Sync Instagram DMs from Playwright';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $nodeProjectPath = config('services.playwright.project_path');
        $nodePath = config('services.playwright.node_path');

        $scriptPath = 'instagram-dm.js';

        $this->info("Menjalankan script Playwright: {$scriptPath} ...");

        $result = Process::timeout(180)
            ->path($nodeProjectPath)
            ->run("\"{$nodePath}\" {$scriptPath}");

        if (!$result->successful()) {
            $this->error('Gagal menjalankan Playwright.');
            $this->line($result->errorOutput());
            return Command::FAILURE;
        }

        $output = trim($result->output());
        $this->line($output);

        /*
         * Ambil JSON dari output Playwright.
         */
        preg_match_all('/\[\s*\{.*?\}\s*\]/s', $output, $matches);

        if (empty($matches[0])) {
            $this->warn('Tidak ada pesan DM baru yang berbentuk JSON.');
            return Command::SUCCESS;
        }

        $jsonCandidates = $matches[0];
        usort($jsonCandidates, fn($a, $b) => strlen($b) - strlen($a));

        $mentions = json_decode($jsonCandidates[0], true);

        if (!$mentions || json_last_error() !== JSON_ERROR_NONE) {
            $this->warn('JSON tidak valid: ' . json_last_error_msg());
            return Command::SUCCESS;
        }

        $saved   = 0;
        $skipped = 0;

        foreach ($mentions as $mention) {
            $postLink = rtrim(trim($mention['post_link'] ?? ''), '/');

            // Hapus SEMUA fragment dari permalink, pertahankan hanya thread URL
            // (direct/t/xxxxx) sebagai basis unik, lalu tambahkan hash pesan.
            // Ini mencegah duplikasi karena fragment #msg-xxxxx berubah setiap run.
            $cleanLink = preg_replace('/#.*$/', '', $postLink);
            $uniqueLink = rtrim($cleanLink, '/') . '#' . md5($mention['post_message']);

            if (empty($mention['post_message'])) {
                $this->warn('Pesan dilewati: post_message kosong.');
                continue;
            }

            if (empty($postLink)) {
                $this->warn('Pesan dilewati: post_link kosong.');
                continue;
            }

            /*
             * Simpan mention Instagram — skip jika sudah ada.
             */
            $record = InstagramMention::firstOrCreate(
                ['post_link' => $uniqueLink],
                [
                    'sender'            => $mention['sender'] ?? null,
                    'notification_text' => $mention['notification_text'] ?? null,
                    'post_message'      => $mention['post_message'],
                    'message_type'      => $mention['message_type'] ?? 'dm',
                    'is_read'           => false,
                ]
            );

            if (!$record->wasRecentlyCreated) {
                $skipped++;
                $this->line('Dilewati (sudah ada): ' . $uniqueLink);
                continue;
            }

            /*
             * Jalankan filter SPAM dan AI klasifikasi.
             */
            try {
                $ai = app(AIClassificationService::class);

                // ── FILTER SPAM ──────────────────────────────────────
                $spamCheck = $ai->isSpam($mention['post_message']);

                if ($spamCheck['is_spam']) {
                    $this->warn('Pesan dilewati (spam/tidak jelas): "' . mb_substr($mention['post_message'], 0, 80) . '" — Alasan: ' . $spamCheck['reason']);
                    $skipped++;
                    continue;
                }

                $notification = Notification::firstOrCreate(
                    ['permalink' => $uniqueLink],
                    [
                        'title'   => 'Instagram DM',
                        'sender'  => $mention['sender'] ?? null,
                        'message' => $mention['post_message'],
                        'is_read' => false,
                    ]
                );

                if (!$notification->wasRecentlyCreated) {
                    $skipped++;
                    $this->warn('Notifikasi sudah ada (race): ' . $uniqueLink);
                    continue;
                }

                // ── KLASIFIKASI AI ───────────────────────────────────
                $classification = $ai->classify($mention['post_message']);

                $aiResult = AIClassification::create([
                    'notification_id' => $notification->id,
                    'suggested_category' => $classification['suggested_category'] ?? null,
                    'suggested_sub_category' => $classification['suggested_sub_category'] ?? null,
                    'suggested_opds' => $classification['suggested_opds'] ?? [],
                    'priority' => $classification['priority'] ?? 'Sedang',
                    'confidence' => $classification['confidence'] ?? 0,
                    'reasoning' => $classification['reasoning'] ?? null,
                ]);

                $this->info('AI berhasil: ' . $mention['post_message']);

                // ── DETEKSI DUPLIKASI AI ─────────────────────────────
                $duplicateResult = $ai->checkDuplicate(
                    $mention['post_message'],
                    $notification->id
                );

                if ($duplicateResult) {
                    $notification->update([
                        'duplicate_of_id'    => $duplicateResult['notification_id'],
                        'duplicate_similarity' => $duplicateResult['similarity'],
                        'duplicate_status'   => 'terdeteksi',
                    ]);

                    $this->warn(
                        'Duplikat terdeteksi (' . round($duplicateResult['similarity']) . '% mirip dengan Notifikasi #' .
                        $duplicateResult['notification_id'] . '): "' .
                        mb_substr($mention['post_message'], 0, 60) . '..."'
                    );
                    $this->warn('→ Tiket TIDAK dibuat otomatis. Menunggu verifikasi admin.');
                } else {
                    try {
                        $ticket = app(TicketingService::class)->createTicketFromClassification($notification, $aiResult);
                        $this->info('Tiket otomatis dibuat: ' . $ticket->tracking_number);
                    } catch (\Exception $e) {
                        $this->warn('Gagal membuat tiket otomatis: ' . $e->getMessage());
                    }
                }

            } catch (\Exception $e) {
                $this->warn('AI gagal: ' . $e->getMessage());
            }

            $saved++;
            $this->info('DM baru: ' . $mention['post_message']);
        }

        $this->info("Selesai. {$saved} DM baru disimpan, {$skipped} dilewati.");
        return Command::SUCCESS;
    }
}
