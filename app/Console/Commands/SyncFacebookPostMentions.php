<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use App\Models\FacebookPostMention;
use App\Models\Notification;
use App\Models\AIClassification;
use App\Services\AIClassificationService;
use App\Services\TicketingService;

class SyncFacebookPostMentions extends Command
{
    /**
     * Nama command.
     */
    protected $signature = 'facebook:post-sync';

    /**
     * Deskripsi command.
     */
    protected $description =
    'Sync Facebook post mentions from Playwright';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $nodeProjectPath = config('services.playwright.project_path');
        $nodePath = config('services.playwright.node_path');

        $result = Process::timeout(120)

            ->path($nodeProjectPath)

            ->run(
                "\"{$nodePath}\" facebook-post-final.js"
            );

        if (!$result->successful()) {

            $this->error(
                'Gagal menjalankan Playwright.'
            );

            $this->line(
                $result->errorOutput()
            );

            return Command::FAILURE;
        }

        $output = trim($result->output());

        $this->line($output);

        /*
         * Ambil JSON dari output Playwright.
         * Pilih blok JSON array yang paling panjang
         * untuk memastikan data paling lengkap.
         */
        preg_match_all(
            '/\[\s*\{.*?\}\s*\]/s',
            $output,
            $matches
        );

        if (empty($matches[0])) {

            $this->warn(
                'Tidak ada mention postingan.'
            );

            return Command::SUCCESS;
        }

        $jsonCandidates = $matches[0];

        usort($jsonCandidates, fn($a, $b) => strlen($b) - strlen($a));

        $mentions = json_decode(
            $jsonCandidates[0],
            true
        );

        if (
            !$mentions ||
            json_last_error() !== JSON_ERROR_NONE
        ) {

            $this->warn(
                'JSON tidak valid: ' . json_last_error_msg()
            );

            return Command::SUCCESS;
        }

        $saved   = 0;
        $skipped = 0;

        foreach ($mentions as $mention) {

            /*
             * Normalisasi post_link: trim spasi dan
             * hapus trailing slash agar tidak dianggap
             * URL baru padahal sama.
             */
            $postLink = rtrim(
                trim($mention['post_link'] ?? ''),
                '/'
            );

            if (empty($mention['post_message'])) {
                $this->warn('Mention dilewati: post_message kosong.');
                continue;
            }

            if (empty($postLink)) {
                $this->warn('Mention dilewati: post_link kosong.');
                continue;
            }

            /*
             * Simpan mention Facebook — skip jika sudah ada.
             */
            $record =
                FacebookPostMention::firstOrCreate(

                    [
                        'post_link' => $postLink,
                    ],

                    [
                        'sender'            => $mention['sender'] ?? null,
                        'notification_text' => $mention['notification_text'] ?? null,
                        'post_message'      => $mention['post_message'],
                        'is_read'           => false,
                    ]

                );

            /*
             * Jika mention sudah ada sebelumnya, lewati.
             */
            if (!$record->wasRecentlyCreated) {
                $skipped++;
                $this->line(
                    'Dilewati (sudah ada): ' . $postLink
                );
                continue;
            }

            /*
             * Jalankan filter SPAM dan AI klasifikasi.
             */
            try {

                $ai = app(
                    AIClassificationService::class
                );

                // ── FILTER SPAM ──────────────────────────────────────
                $spamCheck = $ai->isSpam($mention['post_message']);

                if ($spamCheck['is_spam']) {
                    $this->warn(
                        'Postingan dilewati (spam/tidak jelas): "' .
                        mb_substr($mention['post_message'], 0, 80) .
                        '" — Alasan: ' . $spamCheck['reason']
                    );

                    $skipped++;
                    continue;
                }

                /*
                 * Simpan notifikasi HANYA jika bukan spam.
                 */
                $notification =
                    Notification::firstOrCreate(

                        [
                            'permalink' => $postLink,
                        ],

                        [
                            'title'   => 'Facebook Mention',
                            'sender'  => $mention['sender'] ?? null,
                            'message' => $mention['post_message'],
                            'is_read' => false,
                        ]

                    );

                /*
                 * Jika notifikasi sudah ada (race condition),
                 * lewati proses AI untuk menghindari duplikat.
                 */
                if (!$notification->wasRecentlyCreated) {
                    $skipped++;
                    $this->warn(
                        'Notifikasi sudah ada (race): ' . $postLink
                    );
                    continue;
                }

                // ── KLASIFIKASI AI ───────────────────────────────────
                $classification =
                    $ai->classify(
                        $mention['post_message']
                    );

                /*
                 * Simpan hasil AI.
                 */
                $aiResult = AIClassification::create([

                    'notification_id' =>
                    $notification->id,

                    'suggested_category' =>
                    $classification['suggested_category']
                        ?? null,

                    'suggested_sub_category' =>
                    $classification['suggested_sub_category']
                        ?? null,

                    'suggested_opds' =>
                    $classification['suggested_opds']
                        ?? [],

                    'priority' =>
                    $classification['priority']
                        ?? 'Sedang',

                    'confidence' =>
                    $classification['confidence']
                        ?? 0,

                    'reasoning' =>
                    $classification['reasoning']
                        ?? null,

                ]);

                $this->info(
                    'AI berhasil: ' . $mention['post_message']
                );

                // ── DETEKSI DUPLIKASI AI ─────────────────────────────
                $duplicateResult = $ai->checkDuplicate(
                    $mention['post_message'],
                    $notification->id
                );

                if ($duplicateResult) {
                    // Duplikat terdeteksi → JANGAN buat tiket, tandai notifikasi
                    $notification->update([
                        'duplicate_of_id'    => $duplicateResult['notification_id'],
                        'duplicate_similarity' => $duplicateResult['similarity'],
                        'duplicate_status'   => 'terdeteksi',
                    ]);

                    $this->warn(
                        'Duplikat terdeteksi (' . round($duplicateResult['similarity']) . '% mirip dengan Notifikasi #' .
                        $duplicateResult['notification_id'] . '): "' .
                        mb_substr($mention['post_message'], 0, 60) . '..."' .
                        ' — Alasan: ' . $duplicateResult['reason']
                    );
                    $this->warn('→ Tiket TIDAK dibuat otomatis. Menunggu verifikasi admin.');
                } else {
                    // Tidak ada duplikat → Buat tiket otomatis seperti biasa
                    try {
                        $ticket = app(TicketingService::class)->createTicketFromClassification($notification, $aiResult);
                        $this->info('Tiket otomatis dibuat: ' . $ticket->tracking_number);
                    } catch (\Exception $e) {
                        $this->warn('Gagal membuat tiket otomatis: ' . $e->getMessage());
                    }
                }

            } catch (\Exception $e) {

                /*
                 * Jangan hentikan sync jika AI gagal.
                 */
                $this->warn(
                    'AI gagal: ' . $e->getMessage()
                );
            }

            $saved++;

            $this->info(
                'Mention baru: ' . $mention['post_message']
            );
        }

        $this->info(
            "Selesai. {$saved} mention baru disimpan, {$skipped} dilewati."
        );

        return Command::SUCCESS;
    }
}
