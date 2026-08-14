<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class WhatsAppSpamGuard
{
    public function __construct(private readonly AIClassificationService $ai)
    {
    }

    public function check(string $phone, string $message, ?string $providerMessageId = null): array
    {
        $normalized = $this->normalize($message);
        $identity = $providerMessageId ?: hash('sha256', $phone . '|' . $normalized);
        $dedupeKey = 'wa:spam:dedupe:' . $identity;

        if (!Cache::add($dedupeKey, true, now()->addMinutes(10))) {
            return $this->blocked('duplicate', 'Pesan webhook yang sama sudah diproses.');
        }

        if ($normalized === '') {
            return $this->blocked('heuristic', 'Pesan kosong.');
        }

        $fingerprintKey = 'wa:spam:content:' . hash('sha256', $phone . '|' . $normalized);
        $cooldown = (int) config('services.whatsapp_spam.duplicate_cooldown_seconds', 120);
        if (!Cache::add($fingerprintKey, true, now()->addSeconds($cooldown))) {
            return $this->blocked('duplicate', 'Pesan identik dikirim berulang dalam waktu singkat.');
        }

        $window = (int) config('services.whatsapp_spam.window_seconds', 60);
        $limit = (int) config('services.whatsapp_spam.max_free_messages', 6);
        $rateKey = 'wa:spam:rate:' . hash('sha256', $phone);
        $count = Cache::increment($rateKey);
        if ($count === 1) Cache::put($rateKey, 1, now()->addSeconds($window));
        if ($count > $limit) {
            return $this->blocked('rate_limit', 'Terlalu banyak pesan bebas dalam waktu singkat.');
        }

        if (preg_match('/(.)\\1{7,}/u', $normalized) || preg_match('/(?:https?:\\/\\/\\S+\\s*){2,}/iu', $message)) {
            return $this->blocked('heuristic', 'Pola pengulangan atau promosi tautan terdeteksi.');
        }

        if (!config('services.whatsapp_spam.ai_enabled', true)) {
            return ['allowed' => true, 'layer' => 'local', 'reason' => null];
        }

        $aiResult = $this->ai->isSpam($message);
        if ($aiResult['is_spam']) {
            return $this->blocked('ai', $aiResult['reason']);
        }

        return ['allowed' => true, 'layer' => 'ai', 'reason' => null];
    }

    private function normalize(string $message): string
    {
        return trim(preg_replace('/\\s+/u', ' ', mb_strtolower($message)));
    }

    private function blocked(string $layer, string $reason): array
    {
        return ['allowed' => false, 'layer' => $layer, 'reason' => $reason];
    }
}