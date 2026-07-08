<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use App\Models\FacebookCommentMention;
use App\Models\Notification;
use App\Models\AIClassification;
use App\Services\AIClassificationService;
use App\Services\TicketingService;

class SyncFacebookCommentMentions extends Command
{
    /**
     * Nama command.
     */
    protected $signature =
    'facebook:comment-sync';

    /**
     * Deskripsi command.
     */
    protected $description =
    'Sync Facebook comment mentions from Playwright';

    /**
     * Path direktori Playwright.
     */
    protected string $nodeProjectPath = '';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->nodeProjectPath = config('services.playwright.project_path');
        $nodePath = config('services.playwright.node_path');

        /*
         * Langkah 1: Ekspor comment_id & comment_link
         * yang sudah ada di DB ke file JSON.
         * Playwright akan membaca ini untuk skip notif lama.
         */
        $this->exportKnownIds();

        /*
         * Langkah 2: Jalankan Playwright
         */
        $result = Process::timeout(300)

            ->path($this->nodeProjectPath)

            ->run(
                "\"{$nodePath}\" facebook-comment-final.js"
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

        $output = trim(
            $result->output()
        );

        /*
         * Ambil semua blok JSON array dari output Playwright,
         * lalu pilih yang terpanjang (paling lengkap).
         */
        preg_match_all(
            '/\[\s*(?:\{.*?\}\s*,?\s*)*\]/s',
            $output,
            $matches
        );

        if (empty($matches[0])) {

            $this->warn(
                'Tidak ada data mention.'
            );

            return Command::SUCCESS;
        }

        $jsonCandidates = $matches[0];

        usort($jsonCandidates, fn($a, $b) => strlen($b) - strlen($a));

        $json = $jsonCandidates[0];

        $mentions = json_decode(
            $json,
            true
        );

        if (
            json_last_error() !==
            JSON_ERROR_NONE
        ) {

            $this->warn(
                'JSON tidak valid: ' . json_last_error_msg()
            );

            return Command::SUCCESS;
        }

        if (empty($mentions)) {

            $this->info(
                'Tidak ada mention baru.'
            );

            return Command::SUCCESS;
        }

        $saved   = 0;
        $skipped = 0;

        foreach ($mentions as $mention) {

            try {

                /*
                 * Normalisasi comment_link
                 */
                $commentLink = rtrim(
                    trim($mention['comment_link'] ?? ''),
                    '/'
                );

                if (empty($commentLink)) {
                    $this->warn('Mention dilewati: comment_link kosong.');
                    continue;
                }

                /*
                 * Simpan mention Facebook — skip jika sudah ada.
                 */
                $record =
                    FacebookCommentMention::firstOrCreate(

                        [
                            'comment_link' => $commentLink,
                        ],

                        [
                            'notification_text' =>
                            $mention['notification_text']
                                ?? null,

                            'comment_message' =>
                            $mention['comment_message']
                                ?? null,

                            'comment_id' =>
                            $mention['comment_id']
                                ?? null,

                            'is_read' => false,
                        ]

                    );

                if (!$record->wasRecentlyCreated) {
                    $skipped++;
                    $this->line(
                        'Dilewati (sudah ada): ' . $commentLink
                    );
                    continue;
                }

                /*
                 * Jalankan AI klasifikasi — hanya jika
                 * comment_message tersedia.
                 */
                if (!empty($mention['comment_message'])) {

                    try {

                        $ai = app(
                            AIClassificationService::class
                        );

                        // ── FILTER SPAM ──────────────────────────────────────
                        $spamCheck = $ai->isSpam($mention['comment_message']);

                        if ($spamCheck['is_spam']) {
                            $this->warn(
                                'Komentar dilewati (spam/tidak jelas): "' .
                                mb_substr($mention['comment_message'], 0, 80) .
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
                                    'permalink' => $commentLink,
                                ],

                                [
                                    'title'           => 'Facebook Comment Mention',
                                    'sender'          => $mention['sender'] ?? null,
                                    'message'         => $mention['notification_text'] ?? null,
                                    'comment_message' => $mention['comment_message'] ?? null,
                                    'is_read'         => false,
                                ]

                            );

                        if (!$notification->wasRecentlyCreated) {
                            $skipped++;
                            $this->warn(
                                'Notifikasi sudah ada (race): ' . $commentLink
                            );
                            continue;
                        }

                        // ── KLASIFIKASI AI ───────────────────────────────────
                        $classification =
                            $ai->classify(
                                $mention['comment_message']
                            );

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
                            'AI berhasil: ' . $mention['comment_message']
                        );

                        // ── DETEKSI DUPLIKASI AI ─────────────────────────────
                        $duplicateResult = $ai->checkDuplicate(
                            $mention['comment_message'],
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
                                mb_substr($mention['comment_message'], 0, 60) . '..."'
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

                        $this->warn(
                            'AI gagal: ' . $e->getMessage()
                        );
                    }

                } else {

                    $this->warn(
                        'AI dilewati: comment_message kosong.'
                    );
                }

                $saved++;

                $this->info(
                    'Mention baru: ' . (
                        $mention['comment_message']
                        ?? $mention['notification_text']
                        ?? 'Tanpa teks'
                    )
                );

            } catch (\Exception $e) {

                $this->warn(
                    'Gagal menyimpan mention: ' . $e->getMessage()
                );
            }
        }

        $this->info(
            "Selesai. {$saved} mention baru disimpan, {$skipped} dilewati."
        );

        return Command::SUCCESS;
    }

    /**
     * Ekspor comment_id dan comment_link yang sudah ada di DB
     * ke file JSON agar Playwright bisa skip notifikasi lama.
     */
    protected function exportKnownIds(): void
    {
        $known = FacebookCommentMention::select(
                'comment_id',
                'comment_link'
            )
            ->whereNotNull('comment_id')
            ->get()
            ->map(fn($r) => [
                'comment_id'   => $r->comment_id,
                'comment_link' => $r->comment_link,
            ])
            ->toArray();

        $path = $this->nodeProjectPath
            . '\\known-comment-ids.json';

        file_put_contents(
            $path,
            json_encode($known, JSON_PRETTY_PRINT)
        );

        $this->info(
            'Known IDs diekspor: ' . count($known) . ' record.'
        );
    }
}