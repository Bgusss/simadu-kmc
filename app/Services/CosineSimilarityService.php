<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class CosineSimilarityService
{
    /**
     * Threshold cosine similarity untuk menentukan duplikat.
     * 0.70 = cukup ketat, menghindari false positive.
     */
    private const SIMILARITY_THRESHOLD = 0.70;

    /**
     * Stopwords bahasa Indonesia — kata-kata umum yang tidak membawa makna penting.
     */
    private const STOPWORDS = [
        'yang', 'dan', 'di', 'ke', 'dari', 'ini', 'itu', 'dengan', 'untuk',
        'pada', 'adalah', 'sebagai', 'dalam', 'tidak', 'akan', 'juga', 'sudah',
        'saya', 'kami', 'kita', 'mereka', 'anda', 'dia', 'ia', 'nya', 'lah',
        'kan', 'kah', 'pun', 'apa', 'siapa', 'mana', 'kapan', 'mengapa',
        'bagaimana', 'bisa', 'ada', 'atau', 'jika', 'kalau', 'karena', 'agar',
        'supaya', 'tetapi', 'tapi', 'namun', 'walau', 'meski', 'sedang',
        'telah', 'belum', 'masih', 'sangat', 'sekali', 'saat', 'oleh',
        'bagi', 'antara', 'tanpa', 'tentang', 'setelah', 'sebelum',
        'hanya', 'punya', 'lebih', 'kurang', 'lagi', 'baru', 'sama',
        'seperti', 'semua', 'setiap', 'beberapa', 'banyak', 'sedikit',
        'hampir', 'mungkin', 'selalu', 'sering', 'jarang', 'pernah',
        'tolong', 'mohon', 'minta', 'harap', 'dong', 'deh', 'nih', 'sih',
        'ya', 'yah', 'kok', 'lho', 'lo', 'ayo', 'nah', 'wah', 'eh',
        'pak', 'bu', 'mas', 'mbak', 'bang', 'kak', 'min', 'admin',
        'simadu', 'kmc', 'ketapang',
        'the', 'is', 'at', 'of', 'on', 'and', 'a', 'an', 'to', 'in',
    ];

    /**
     * Pemetaan dialek Melayu Ketapang → Bahasa Indonesia baku.
     */
    private const DIALECT_MAP = [
        'aik'    => 'air',
        'aiq'    => 'air',
        'aek'    => 'air',
        'dak'    => 'tidak',
        'ndak'   => 'tidak',
        'dek'    => 'tidak',
        'ngalir' => 'mengalir',
        'galap'  => 'gelap',
        'parit'  => 'drainase',
        'pokok'  => 'pohon',
        'pju'    => 'lampu jalan',
        'odgj'   => 'gangguan jiwa',
        'gg'     => 'gang',
        'jl'     => 'jalan',
        'jln'    => 'jalan',
        'kel'    => 'kelurahan',
        'ds'     => 'desa',
        'kec'    => 'kecamatan',
        'rt'     => 'rt',
        'rw'     => 'rw',
        'mampet' => 'tersumbat',
        'macet'  => 'tersumbat',
        'bocor'  => 'bocor',
        'rusak'  => 'rusak',
        'lobang' => 'lubang',
    ];

    /**
     * Deteksi duplikasi aduan menggunakan TF-IDF + Cosine Similarity.
     * Membandingkan pesan baru dengan aduan-aduan 30 hari terakhir.
     *
     * @param  string   $newMessage   Pesan aduan baru
     * @param  int|null $excludeId    ID notifikasi yang dikecualikan
     * @return array|null  ['notification_id', 'similarity', 'reason', 'original_message'] atau null
     */
    public function checkDuplicate(string $newMessage, ?int $excludeId = null): ?array
    {
        try {
            // 1. Ambil aduan 30 hari terakhir yang sudah punya tiket
            $recentNotifications = Notification::where('created_at', '>=', now()->subDays(30))
                ->whereHas('ticket')
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->whereNull('duplicate_status')
                ->latest()
                ->take(50) // Bisa lebih banyak karena tidak butuh API call
                ->get(['id', 'message', 'comment_message', 'sender']);

            if ($recentNotifications->isEmpty()) {
                return null;
            }

            // 2. Preprocessing pesan baru
            $newTokens = $this->tokenize($newMessage);

            if (count($newTokens) < 2) {
                return null; // Pesan terlalu pendek
            }

            // 3. Kumpulkan semua dokumen (existing + new)
            $documents = [];
            $docIds = [];

            foreach ($recentNotifications as $notif) {
                $msg = $notif->comment_message ?? $notif->message ?? '';
                $tokens = $this->tokenize($msg);

                if (count($tokens) < 2) {
                    continue;
                }

                $documents[] = $tokens;
                $docIds[] = [
                    'id'      => $notif->id,
                    'message' => $msg,
                ];
            }

            if (empty($documents)) {
                return null;
            }

            // 4. Hitung TF-IDF
            $allDocuments = array_merge($documents, [$newTokens]);
            $tfidfVectors = $this->computeTfIdf($allDocuments);

            $newVector = array_pop($tfidfVectors); // Vektor terakhir = pesan baru

            // 5. Hitung cosine similarity dengan setiap dokumen existing
            $bestMatch = null;
            $bestSimilarity = 0;

            foreach ($tfidfVectors as $idx => $docVector) {
                $similarity = $this->cosineSimilarity($newVector, $docVector);

                if ($similarity > $bestSimilarity) {
                    $bestSimilarity = $similarity;
                    $bestMatch = $docIds[$idx];
                }
            }

            // 6. Cek threshold
            if ($bestSimilarity >= self::SIMILARITY_THRESHOLD && $bestMatch) {
                $similarityPercent = round($bestSimilarity * 100, 1);

                Log::info('CosineSimilarity: Duplikat terdeteksi', [
                    'new_message'  => mb_substr($newMessage, 0, 80),
                    'matched_id'   => $bestMatch['id'],
                    'similarity'   => $similarityPercent,
                ]);

                return [
                    'notification_id'  => (int) $bestMatch['id'],
                    'similarity'       => $similarityPercent,
                    'reason'           => "Cosine Similarity {$similarityPercent}% — konten serupa terdeteksi",
                    'original_message' => $bestMatch['message'],
                ];
            }

            return null;

        } catch (\Exception $e) {
            Log::warning('CosineSimilarity: Error — ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Tokenisasi teks: lowercase, normalisasi dialek, hapus stopwords.
     *
     * @param  string $text
     * @return array  Daftar token bersih
     */
    private function tokenize(string $text): array
    {
        // Hapus mention @simadu kmc
        $text = preg_replace('/@?simadu\s*kmc/iu', '', $text);

        // Lowercase
        $text = mb_strtolower($text);

        // Hapus URL
        $text = preg_replace('/https?:\/\/\S+/i', '', $text);

        // Hapus karakter non-alfanumerik (kecuali spasi)
        $text = preg_replace('/[^a-z0-9\s]/u', ' ', $text);

        // Split menjadi token
        $tokens = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);

        // Normalisasi dialek Ketapang
        $tokens = array_map(function ($token) {
            return self::DIALECT_MAP[$token] ?? $token;
        }, $tokens);

        // Hapus stopwords dan token pendek (< 2 karakter)
        $tokens = array_filter($tokens, function ($token) {
            return mb_strlen($token) >= 2 && !in_array($token, self::STOPWORDS);
        });

        return array_values($tokens);
    }

    /**
     * Hitung TF-IDF vectors untuk kumpulan dokumen.
     *
     * @param  array $documents  Array of token arrays
     * @return array Array of TF-IDF vectors (associative arrays: term => weight)
     */
    private function computeTfIdf(array $documents): array
    {
        $totalDocs = count($documents);

        // 1. Hitung Document Frequency (DF) — berapa dokumen mengandung setiap term
        $df = [];
        foreach ($documents as $tokens) {
            $uniqueTokens = array_unique($tokens);
            foreach ($uniqueTokens as $token) {
                $df[$token] = ($df[$token] ?? 0) + 1;
            }
        }

        // 2. Hitung TF-IDF per dokumen
        $vectors = [];
        foreach ($documents as $tokens) {
            $tokenCount = count($tokens);

            if ($tokenCount === 0) {
                $vectors[] = [];
                continue;
            }

            // Hitung Term Frequency (TF)
            $tf = array_count_values($tokens);

            // Hitung TF-IDF
            $vector = [];
            foreach ($tf as $term => $count) {
                $termFrequency = $count / $tokenCount;
                $idf = log(($totalDocs + 1) / (($df[$term] ?? 0) + 1)) + 1; // Smoothed IDF
                $vector[$term] = $termFrequency * $idf;
            }

            $vectors[] = $vector;
        }

        return $vectors;
    }

    /**
     * Hitung cosine similarity antara dua vektor TF-IDF.
     *
     * @param  array $vectorA  Vektor TF-IDF (term => weight)
     * @param  array $vectorB  Vektor TF-IDF (term => weight)
     * @return float Nilai similarity 0.0 - 1.0
     */
    private function cosineSimilarity(array $vectorA, array $vectorB): float
    {
        if (empty($vectorA) || empty($vectorB)) {
            return 0.0;
        }

        // Dot product
        $dotProduct = 0.0;
        foreach ($vectorA as $term => $weightA) {
            if (isset($vectorB[$term])) {
                $dotProduct += $weightA * $vectorB[$term];
            }
        }

        // Magnitudes
        $magnitudeA = sqrt(array_sum(array_map(fn($v) => $v * $v, $vectorA)));
        $magnitudeB = sqrt(array_sum(array_map(fn($v) => $v * $v, $vectorB)));

        if ($magnitudeA == 0 || $magnitudeB == 0) {
            return 0.0;
        }

        return $dotProduct / ($magnitudeA * $magnitudeB);
    }
}
