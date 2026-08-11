<?php

namespace App\Services;

class WhatsAppKeywordClassificationService
{
    public function classify(string $message): array
    {
        $text = mb_strtolower(trim($message));
        $rules = [
            ['Air Bersih', 'PDAM Ketapang', ['pdam', 'air mati', 'air tidak', 'air keruh', 'pipa', 'aek', 'ayek', 'paip']],
            ['Kelistrikan', 'PLN', ['listrik', 'lampu mati', 'lampu padam', 'pln', 'strum', 'setrum', 'kabel listrik']],
            ['Perhubungan', 'Dinas Perhubungan', ['lampu jalan', 'pju', 'traffic light', 'parkir', 'angkot', 'terminal', 'pelabuhan', 'rambu']],
            ['Infrastruktur', 'Dinas PUPR', ['jalan rusak', 'jalan berlubang', 'jembatan', 'drainase', 'parit', 'selokan', 'aspal', 'irigasi', 'jalan bolong', 'jalan rosak', 'jalatn']],
            ['Perumahan', 'Dinas PUPR', ['rtlh', 'rumah tidak layak', 'perumahan kumuh', 'bedah rumah', 'rumah bocor', 'rumah roboh']],
            ['Sosial', 'Dinas Sosial', ['bansos', 'bantuan sosial', 'warga miskin', 'pengemis', 'disabilitas', 'blt', 'pkh', 'orang susah']],
            ['Ketertiban Umum', 'Satpol PP', ['keributan', 'tawuran', 'pedagang liar', 'miras', 'judi', 'bangunan liar', 'damkar', 'pemadam kebakaran']],
            ['Kesehatan', 'Dinas Kesehatan', ['rumah sakit', 'rsud', 'puskesmas', 'dokter', 'perawat', 'obat', 'posyandu', 'dbd', 'ambulans', 'ubat']],
            ['Bencana', 'BPBD', ['banjir', 'longsor', 'karhutla', 'bencana', 'pohon tumbang', 'gempa', 'evakuasi', 'aek naek']],
            ['Pendidikan', 'Dinas Pendidikan', ['sekolah', 'guru', 'pendidikan', 'siswa', 'murid', 'beasiswa', 'ppdb']],
            ['Administrasi Kependudukan', 'Disdukcapil', ['ktp', 'e-ktp', 'kartu keluarga', 'akta lahir', 'nik', 'dukcapil', 'domisili']],
            ['Lingkungan', 'Dinas Lingkungan Hidup', ['sampah', 'tps', 'tpa', 'limbah', 'pencemaran', 'polusi', 'sarap']],
            ['Pertanian', 'Dinas Pertanian, Peternakan dan Perkebunan', ['sawah', 'padi', 'pupuk', 'ternak', 'perkebunan', 'petani', 'kebon']],
            ['Perikanan', 'Dinas Ketahanan Pangan, Kelautan, dan Perikanan', ['nelayan', 'tambak', 'jaring ikan', 'tangkapan ikan', 'pukat']],
            ['Ketenagakerjaan', 'Dinas Tenaga Kerja dan Transmigrasi', ['phk', 'gaji tidak dibayar', 'upah tidak dibayar', 'buruh', 'pengangguran', 'kerje']],
            ['Perdagangan', 'Dinas Perindustrian, Perdagangan, Koperasi dan UKM', ['pasar', 'kios', 'pedagang', 'elpiji', 'koperasi', 'umkm', 'kedai']],
            ['Pariwisata', 'Dinas Kebudayaan dan Pariwisata', ['wisata', 'pantai', 'air terjun', 'danau', 'museum']],
            ['Perlindungan Perempuan dan Anak', 'Dinas Pemberdayaan Perempuan dan Perlindungan Anak', ['kdrt', 'pelecehan', 'kekerasan anak', 'kekerasan perempuan', 'bullying']],
            ['Pertanahan', 'BPN', ['sertifikat tanah', 'sengketa tanah', 'sengketa lahan', 'tapal batas', 'ptsl']],
            ['Komunikasi dan Informatika', 'Dinas Komunikasi dan Informatika', ['sinyal', 'internet', 'jaringan', 'wifi', 'bts', 'server down', 'sistem error']],
        ];

        foreach ($rules as [$category, $opd, $keywords]) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    return compact('category', 'opd') + ['sub_category' => 'Lain-lain', 'priority' => 'sedang', 'reasoning' => "Klasifikasi kata kunci WhatsApp: {$keyword}"];
                }
            }
        }

        return ['category' => 'Pengaduan', 'opd' => 'Dinas Komunikasi dan Informatika', 'sub_category' => 'Lain-lain', 'priority' => 'sedang', 'reasoning' => 'Tidak ada kata kunci cocok; klasifikasi default WhatsApp.'];
    }
}
