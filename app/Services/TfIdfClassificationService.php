<?php

namespace App\Services;

/**
 * Klasifikasi laporan lokal menggunakan TF-IDF dan cosine similarity.
 * Korpus disusun dari pola aduan non-Monitoring Berita KMC.
 */
class TfIdfClassificationService
{
    private const MIN_CONFIDENCE = 0.18;

    private const CORPUS = [
        ['Layanan PDAM', 'Air Bersih', 'PDAM Ketapang', 'pdam air tidak mengalir air tidak jalan air asin air keruh abonemen idpel tirta pawan perumdam pipa pecah sanyo pemasangan baru'],
        ['Infrastruktur dan Pekerjaan Umum', 'Lampu Jalan', 'Dinas Perhubungan', 'lampu jalan lampu penerangan pju lpju lampu gang lampu jembatan solar cell lampu mati gelap'],
        ['Layanan PLN', 'Listrik', 'PLN', 'pln listrik tiang listrik kabel listrik arus listrik kwh gardu trafo nyentrum kabel putus'],
        ['Infrastruktur dan Pekerjaan Umum', 'Lampu Lalu Lintas', 'Dinas Perhubungan', 'lampu merah traffic light lampu lalu lintas lampu lalin'],
        ['Infrastruktur dan Pekerjaan Umum', 'Jalan', 'Dinas PUTR', 'jalan rusak jalan berlubang lubang aspal amblas rambat beton tumpahan minyak jalan putus'],
        ['Infrastruktur dan Pekerjaan Umum', 'Jembatan', 'Dinas PUTR', 'jembatan rusak jembatan goyang jembatan tidak layak tiang jembatan'],
        ['Infrastruktur dan Pekerjaan Umum', 'Drainase', 'Dinas PUTR', 'drainase saluran selokan parit tersumbat gorong gorong normalisasi parit pintu air'],
        ['Lingkungan Hidup dan Kehutanan', 'Sampah', 'Dinas Lingkungan Hidup', 'sampah sarap menumpuk numpuk kebersihan berserakan tps limbah bau menyengat'],
        ['Lingkungan Hidup dan Kehutanan', 'Pohon', 'BPBD', 'pohon dahan pohon miring pohon mati pohon tumbang'],
        ['Bencana dan Penanggulangan Darurat', 'Banjir', 'BPBD', 'banjir terendam air pasang banjir tidak surut meluap'],
        ['Sosial dan Kesejahteraan Masyarakat', 'Bantuan Sosial', 'Dinas Sosial', 'bansos blt pkh bpjs gratis bantuan sosial desil bedah rumah'],
        ['Sosial dan Kesejahteraan Masyarakat', 'Orang Terlantar', 'Dinas Sosial', 'orang terlantar lansia terlantar minta minta gelandangan gangguan jiwa odgj'],
        ['Sosial dan Kesejahteraan Masyarakat', 'KDRT', 'Dinas Sosial', 'kdrt kekerasan dalam rumah tangga perselingkuhan kekerasan'],
        ['Kesehatan', 'Rumah Sakit', 'RSUD Agoesdjam', 'rumah sakit rsud rawat inap pasien pengunjung berisik'],
        ['Kesehatan', 'Fasilitas Kesehatan', 'Dinas Kesehatan', 'puskesmas kesehatan psc 119 ambulans mbg makan bergizi'],
        ['Pendidikan', 'Sekolah', 'Dinas Pendidikan', 'sekolah guru pendidikan asrama mahasiswa buku lks paud'],
        ['Komunikasi dan Informatika', 'Internet', 'Dinas Komunikasi dan Informatika', 'internet sinyal tower kominfo bakti wifi jaringan blank spot email dinas'],
        ['Pertanahan', 'Sengketa Tanah', 'BPN', 'tanah sengketa tanah mafia tanah sertifikat bpn'],
        ['Pertanian, Perikanan, dan Peternakan', 'Pertanian', 'Dinas Pertanian Peternakan dan Perkebunan', 'pertanian pupuk subsidi petani sawit padi irigasi'],
        ['Pertanian, Perikanan, dan Peternakan', 'Nelayan', 'Dinas Ketahanan Pangan, Kelautan, dan Perikanan', 'nelayan perikanan kelautan alat tangkap'],
        ['Keuangan dan Pajak Daerah', 'Pajak', 'BPKAD', 'pajak pbb retribusi pajak bumi bangunan'],
        ['Kepegawaian / SDM Aparatur', 'Kepegawaian', 'BKPSDM', 'kepegawaian pindah tugas mutasi asn pppk honorarium'],
        ['Sosial dan Kesejahteraan Masyarakat', 'Ketenagakerjaan', 'Dinas Tenaga Kerja dan Transmigrasi', 'ketenagakerjaan outsourcing lowongan kerja loker phk tenaga kerja balai latihan kerja'],
        ['Administrasi Kependudukan', 'KTP', 'Disdukcapil', 'ktp kk kartu keluarga akta kelahiran akta kematian skbm disdukcapil'],
    ];

    private const MAP = [
        'aik' => 'air', 'aek' => 'air', 'ayek' => 'air', 'aiq' => 'air', 'dak' => 'tidak', 'idak' => 'tidak', 'ndak' => 'tidak', 'ngk' => 'tidak',
        'ngalir' => 'mengalir', 'pju' => 'lampu_jalan', 'lpju' => 'lampu_jalan', 'jl' => 'jalan', 'jln' => 'jalan', 'gg' => 'gang',
        'belubang' => 'berlubang', 'lobang' => 'berlubang', 'bolong' => 'berlubang', 'sarap' => 'sampah', 'numpuk' => 'menumpuk',
        'parit' => 'drainase', 'selokan' => 'drainase', 'mampet' => 'tersumbat', 'macet' => 'tersumbat', 'odgj' => 'gangguan_jiwa',
    ];

    public function classify(string $message): ?array
    {
        $input = $this->tokenize($message);
        if (count($input) < 2) return null;
        $documents = array_map(fn ($row) => $this->tokenize($row[3]), self::CORPUS);
        $vectors = $this->tfidf([...$documents, $input]);
        $inputVector = array_pop($vectors);
        $best = null; $bestScore = 0.0;
        foreach ($vectors as $index => $vector) {
            $score = $this->cosine($inputVector, $vector);
            if ($score > $bestScore) { $bestScore = $score; $best = self::CORPUS[$index]; }
        }
        if (!$best || $bestScore < self::MIN_CONFIDENCE) return null;
        [$category, $subCategory, $opd] = $best;
        $priority = $this->priority($message);
        return [
            'category' => $category, 'sub_category' => $subCategory, 'opd' => $opd,
            'priority' => $priority, 'confidence' => round($bestScore * 100, 1),
            'reasoning' => "Klasifikasi TF-IDF lokal: {$subCategory} ({$category}).",
        ];
    }

    private function tokenize(string $text): array
    {
        $text = mb_strtolower($text);
        $text = preg_replace('/https?:\/\/\S+/i', '', $text);
        $text = preg_replace('/\b(lampu\s+jalan|lampu\s+penerangan|penerangan\s+jalan)\b/iu', ' lampu_jalan ', $text);
        $text = preg_replace('/\b(dak|tidak|ngk|ndak)\s+(ngalir|jalan)\b/iu', ' gangguan_air ', $text);
        $text = preg_replace('/[^a-z0-9_\s]/u', ' ', $text);
        $tokens = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);
        $stop = ['yang','dan','di','ke','dari','untuk','dengan','ini','itu','saya','kami','min','admin','tolong','mohon','sudah','tidak','ada','akan','lagi','sangat','hari','bulan'];
        $tokens = array_map(fn ($token) => self::MAP[$token] ?? $token, $tokens);
        return array_values(array_filter($tokens, fn ($token) => mb_strlen($token) > 1 && !in_array($token, $stop, true)));
    }

    private function tfidf(array $documents): array
    {
        $count = count($documents); $df = [];
        foreach ($documents as $document) foreach (array_unique($document) as $term) $df[$term] = ($df[$term] ?? 0) + 1;
        return array_map(function ($document) use ($count, $df) {
            $terms = array_count_values($document); $length = max(count($document), 1); $vector = [];
            foreach ($terms as $term => $frequency) $vector[$term] = ($frequency / $length) * (log(($count + 1) / (($df[$term] ?? 0) + 1)) + 1);
            return $vector;
        }, $documents);
    }

    private function cosine(array $left, array $right): float
    {
        $dot = 0.0; foreach ($left as $term => $weight) if (isset($right[$term])) $dot += $weight * $right[$term];
        $a = sqrt(array_sum(array_map(fn ($value) => $value * $value, $left)));
        $b = sqrt(array_sum(array_map(fn ($value) => $value * $value, $right)));
        return ($a && $b) ? $dot / ($a * $b) : 0.0;
    }

    private function priority(string $message): string
    {
        $text = mb_strtolower($message);
        foreach (['kebakaran','banjir','korban jiwa','kecelakaan','nyentrum','konslet','kabel putus','membahayakan','darurat','kdrt'] as $term) if (str_contains($text, $term)) return 'tinggi';
        foreach (['pdam','jalan','lampu','jembatan','drainase','sampah','pohon','listrik'] as $term) if (str_contains($text, $term)) return 'sedang';
        return 'rendah';
    }
}
