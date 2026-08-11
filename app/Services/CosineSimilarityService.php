<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class CosineSimilarityService
{
    private const SIMILARITY_THRESHOLD = 0.70;

    private const STOPWORDS = [
        'yang','dan','di','ke','dari','ini','itu','dengan','untuk','pada','adalah','sebagai','dalam','tidak','akan','juga','sudah','saya','kami','kita','mereka','anda','dia','ia','nya','lah','kan','kah','pun','apa','siapa','mana','kapan','mengapa','bagaimana','bisa','ada','atau','jika','kalau','karena','agar','supaya','tetapi','tapi','namun','walau','meski','sedang','telah','belum','masih','sangat','sekali','saat','oleh','bagi','antara','tanpa','tentang','setelah','sebelum','hanya','punya','lebih','kurang','lagi','baru','sama','seperti','semua','setiap','beberapa','banyak','sedikit','hampir','mungkin','selalu','sering','jarang','pernah','tolong','mohon','minta','harap','dong','deh','nih','sih','ya','yah','kok','lho','lo','ayo','nah','wah','eh','pak','bu','mas','mbak','bang','kak','min','admin','simadu','kmc','ketapang','the','is','at','of','on','and','a','an','to','in','am','jak','dah','brp','n','ade','saye','kami','mau','susah','hari','lama','segera','depan','dekat','sangat','bahaya','membahayakan','pengendara','motor','mohon','benarkan','jalur',
    ];

    /** Pemetaan dialek, typo dan singkatan laporan masyarakat. */
    private const NORMALIZATION_MAP = [
        'aik' => 'air', 'aiq' => 'air', 'aek' => 'air', 'ayek' => 'air', 'ayik' => 'air',
        'dak' => 'tidak', 'ndak' => 'tidak', 'dek' => 'tidak', 'idak' => 'tidak', 'ngk' => 'tidak', 'ngga' => 'tidak', 'nggak' => 'tidak',
        'ngalir' => 'mengalir', 'smpai' => 'sampai', 'sdh' => 'sudah', 'blm' => 'belum', 'dgn' => 'dengan', 'yg' => 'yang', 'sy' => 'saya',
        'galap' => 'gelap', 'parit' => 'drainase', 'pokok' => 'pohon',
        'pju' => 'lampu_jalan', 'lpju' => 'lampu_jalan', 'odgj' => 'gangguan_jiwa', 'gg' => 'gang',
        'jl' => 'jalan', 'jln' => 'jalan', 'pwn' => 'pawan', 'kel' => 'kelurahan', 'ds' => 'desa', 'kec' => 'kecamatan',
        'mampet' => 'tersumbat', 'macet' => 'tersumbat', 'saluran' => 'drainase', 'selokan' => 'drainase', 'lobang' => 'kerusakan_jalan',
        'belubang' => 'kerusakan_jalan', 'berlubang' => 'kerusakan_jalan', 'bolong' => 'kerusakan_jalan', 'rusak' => 'kerusakan_jalan', 'ancur' => 'kerusakan_jalan',
        'sarap' => 'sampah', 'numpuk' => 'menumpuk', 'numpek' => 'menumpuk',
    ];

    /** Kata searti dikonversi menjadi token konsep yang sama. */
    private const CONCEPTS = [
        'masalah_jalan' => ['jalan', 'kerusakan_jalan', 'aspal', 'trotoar', 'rambat', 'beton'],
        'masalah_jembatan' => ['jembatan', 'goyang', 'tiang', 'ngerendap'],
        'masalah_drainase' => ['drainase', 'saluran', 'selokan', 'gorong', 'tersumbat', 'pintu_air'],
        'masalah_air' => ['air', 'pdam', 'asin', 'keruh', 'mengalir', 'pipa', 'ledeng', 'perumdam', 'abonemen', 'idpel'],
        'masalah_lampu_jalan' => ['lampu_jalan', 'penerangan', 'solar', 'gelap'],
        'masalah_listrik' => ['listrik', 'padam', 'mati', 'pln', 'kabel', 'kwh', 'nyentrum'],
        'masalah_sampah' => ['sampah', 'menumpuk', 'limbah', 'tps', 'tpa', 'kebersihan'],
        'masalah_pohon' => ['pohon', 'dahan', 'tumbang', 'miring'],
        'masalah_banjir' => ['banjir', 'meluap', 'terendam', 'genangan', 'pasang'],
        'masalah_sosial' => ['bansos', 'blt', 'pkh', 'terlantar', 'lansia', 'gangguan_jiwa', 'kdrt'],
    ];

    public function checkDuplicate(string $newMessage, ?int $excludeId = null): ?array
    {
        try {
            $recentNotifications = Notification::where('created_at', '>=', now()->subDays(30))
                ->whereHas('ticket')
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->whereNull('duplicate_status')
                ->latest()->take(50)
                ->get(['id', 'message', 'comment_message', 'sender']);

            if ($recentNotifications->isEmpty()) return null;

            $newTokens = $this->tokenize($newMessage);
            if (count($newTokens) < 2) return null;

            $documents = [];
            $matches = [];
            foreach ($recentNotifications as $notification) {
                $message = $notification->comment_message ?? $notification->message ?? '';
                $tokens = $this->tokenize($message);
                if (count($tokens) < 2) continue;
                $documents[] = $tokens;
                $matches[] = ['id' => $notification->id, 'message' => $message, 'tokens' => $tokens];
            }
            if (!$documents) return null;

            $vectors = $this->computeTfIdf([...$documents, $newTokens]);
            $newVector = array_pop($vectors);
            $bestMatch = null;
            $bestScore = 0.0;
            $bestParts = [];

            foreach ($vectors as $index => $vector) {
                $cosine = $this->cosineSimilarity($newVector, $vector);
                $location = $this->locationSimilarity($newTokens, $matches[$index]['tokens']);
                $concept = $this->conceptSimilarity($newTokens, $matches[$index]['tokens']);

                // Cosine tetap utama; lokasi dan konsep menguatkan parafrasa/dialek.
                $score = (0.55 * $cosine) + (0.30 * $location) + (0.15 * $concept);
                if ($score > $bestScore) {
                    $bestScore = $score;
                    $bestMatch = $matches[$index];
                    $bestParts = compact('cosine', 'location', 'concept');
                }
            }

            if ($bestMatch && $bestScore >= self::SIMILARITY_THRESHOLD) {
                $percent = round($bestScore * 100, 1);
                $details = sprintf('Cosine %.1f%%, lokasi %.1f%%, konsep %.1f%%', $bestParts['cosine'] * 100, $bestParts['location'] * 100, $bestParts['concept'] * 100);
                Log::info('CosineSimilarity: kandidat duplikat', ['matched_id' => $bestMatch['id'], 'score' => $percent, 'details' => $details]);
                return [
                    'notification_id' => (int) $bestMatch['id'],
                    'similarity' => $percent,
                    'reason' => "Skor kemiripan {$percent}% ({$details})",
                    'original_message' => $bestMatch['message'],
                ];
            }
            return null;
        } catch (\Exception $e) {
            Log::warning('CosineSimilarity error: ' . $e->getMessage());
            return null;
        }
    }

    private function tokenize(string $text): array
    {
        $text = preg_replace('/@?simadu\s*kmc/iu', '', mb_strtolower($text));
        $text = preg_replace('/https?:\/\/\S+/i', '', $text);
        // Normalisasi frasa masalah yang setara dari data aduan lapangan.
        $text = preg_replace('/\b(lampu\s+jalan|lampu\s+penerangan|penerangan\s+jalan|lampu\s+pju)\b/iu', ' lampu_jalan ', $text);
        $text = preg_replace('/\b(padam|mati\s+total|mati)\b/iu', ' gangguan_lampu ', $text);
        $text = preg_replace('/\b(dak|tidak|ngk|ngga|nggak)\s+(ngalir|jalan)\b/iu', ' gangguan_air ', $text);
        $text = preg_replace('/\b(tertutup\s+tanah|parit\s+sumbat|parit\s+tersumbat|air\s+(nye\s+)?sumbat)\b/iu', ' tersumbat ', $text);
        $text = str_ireplace(['delapan', 'tujuh', 'enam', 'lima', 'empat', 'tiga', 'dua', 'satu'], ['8', '7', '6', '5', '4', '3', '2', '1'], $text);
        // Angka Romawi umum pada nama jalan/lokasi.
        $text = preg_replace('/\b(viii|vii|vi|iv|iii|ii|ix|v)\b/u', ' $0 ', $text);
        $text = str_ireplace(['viii','vii','vi','iv','iii','ii','ix','v'], ['8','7','6','4','3','2','9','5'], $text);
        $text = preg_replace('/\b([a-z]+)(\d+)\b/iu', '$1 $2', $text);
        $text = preg_replace('/[^a-z0-9_\s]/u', ' ', $text);
        $tokens = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);

        $normalized = [];
        foreach ($tokens as $token) {
            $replacement = self::NORMALIZATION_MAP[$token] ?? $token;
            foreach (explode(' ', $replacement) as $part) $normalized[] = $part;
        }
        $tokens = array_values(array_filter($normalized, fn ($token) => mb_strlen($token) >= 2 && !in_array($token, self::STOPWORDS, true)));

        // Sisipkan token konsep: typo/parafrasa yang bermakna sama tetap bertemu di TF-IDF.
        foreach (self::CONCEPTS as $concept => $terms) {
            if (array_intersect($tokens, $terms)) $tokens[] = $concept;
        }
        return $tokens;
    }

    private function locationSimilarity(array $left, array $right): float
    {
        $exclude = array_merge(array_keys(self::CONCEPTS), array_merge(...array_values(self::CONCEPTS)));
        $leftLocations = array_values(array_diff($left, $exclude));
        $rightLocations = array_values(array_diff($right, $exclude));
        if (!$leftLocations || !$rightLocations) return 0.0;
        $intersection = count(array_intersect(array_unique($leftLocations), array_unique($rightLocations)));
        $union = count(array_unique([...$leftLocations, ...$rightLocations]));
        return $union ? $intersection / $union : 0.0;
    }

    private function conceptSimilarity(array $left, array $right): float
    {
        $leftConcepts = array_values(array_intersect($left, array_keys(self::CONCEPTS)));
        $rightConcepts = array_values(array_intersect($right, array_keys(self::CONCEPTS)));
        if (!$leftConcepts || !$rightConcepts) return 0.0;
        return count(array_intersect($leftConcepts, $rightConcepts)) / count(array_unique([...$leftConcepts, ...$rightConcepts]));
    }

    private function computeTfIdf(array $documents): array
    {
        $total = count($documents); $df = [];
        foreach ($documents as $tokens) foreach (array_unique($tokens) as $token) $df[$token] = ($df[$token] ?? 0) + 1;
        return array_map(function ($tokens) use ($df, $total) {
            if (!$tokens) return [];
            $tf = array_count_values($tokens); $count = count($tokens); $vector = [];
            foreach ($tf as $term => $frequency) $vector[$term] = ($frequency / $count) * (log(($total + 1) / (($df[$term] ?? 0) + 1)) + 1);
            return $vector;
        }, $documents);
    }

    private function cosineSimilarity(array $a, array $b): float
    {
        if (!$a || !$b) return 0.0;
        $dot = 0.0; foreach ($a as $term => $weight) if (isset($b[$term])) $dot += $weight * $b[$term];
        $magnitudeA = sqrt(array_sum(array_map(fn ($v) => $v * $v, $a)));
        $magnitudeB = sqrt(array_sum(array_map(fn ($v) => $v * $v, $b)));
        return ($magnitudeA && $magnitudeB) ? $dot / ($magnitudeA * $magnitudeB) : 0.0;
    }
}
