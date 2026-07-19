<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Opd;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIClassificationService
{
    /**
     * Mapping Sub Kategori → (Kategori default, OPD default)
     * Berdasarkan data lapangan asli KMC Ketapang (100+ laporan nyata).
     * Digunakan sebagai sumber kebenaran ketika relasi DB belum diisi.
     */
    private const SUB_CATEGORY_MAP = [
        // ── LAYANAN PDAM (topik terbanyak ~22.6%) ──
        'Air Bersih'          => ['category' => 'Layanan PDAM',                          'opd' => 'PDAM Ketapang'],

        // ── INFRASTRUKTUR DAN PEKERJAAN UMUM ──
        'Lampu Jalan'         => ['category' => 'Infrastruktur dan Pekerjaan Umum',      'opd' => 'Dinas Perhubungan'],
        'Lampu Lalu Lintas'   => ['category' => 'Infrastruktur dan Pekerjaan Umum',      'opd' => 'Dinas Perhubungan'],
        'Jembatan'            => ['category' => 'Infrastruktur dan Pekerjaan Umum',      'opd' => 'Dinas PUTR'],
        'Jalan'               => ['category' => 'Infrastruktur dan Pekerjaan Umum',      'opd' => 'Dinas PUTR'],
        'Jalan Gang'          => ['category' => 'Infrastruktur dan Pekerjaan Umum',      'opd' => 'Dinas Perkim'],
        'Drainase'            => ['category' => 'Infrastruktur dan Pekerjaan Umum',      'opd' => 'Dinas PUTR'],
        'Transportasi Umum'   => ['category' => 'Infrastruktur dan Pekerjaan Umum',      'opd' => 'Dinas Perhubungan'],
        'Parkir'              => ['category' => 'Infrastruktur dan Pekerjaan Umum',      'opd' => 'Satpol PP'],
        'Perijinan'           => ['category' => 'Infrastruktur dan Pekerjaan Umum',      'opd' => 'Dinas PUTR'],

        // ── LAYANAN PLN ──
        'Listrik'             => ['category' => 'Layanan PLN',                           'opd' => 'PLN'],

        // ── SOSIAL DAN KESEJAHTERAAN MASYARAKAT ──
        'Bantuan Sosial'      => ['category' => 'Sosial dan Kesejahteraan Masyarakat',   'opd' => 'Dinas Sosial'],
        'Orang Terlantar'     => ['category' => 'Sosial dan Kesejahteraan Masyarakat',   'opd' => 'Dinas Sosial'],
        'ODGJ'                => ['category' => 'Sosial dan Kesejahteraan Masyarakat',   'opd' => 'Dinas Sosial'],
        'Ketenagakerjaan'     => ['category' => 'Sosial dan Kesejahteraan Masyarakat',   'opd' => 'Dinas Tenaga Kerja dan Transmigrasi'],
        'KDRT'                => ['category' => 'Sosial dan Kesejahteraan Masyarakat',   'opd' => 'Dinas Sosial'],
        'Kekerasan Anak'      => ['category' => 'Sosial dan Kesejahteraan Masyarakat',   'opd' => 'Dinas Sosial'],
        'Kekerasan Perempuan' => ['category' => 'Sosial dan Kesejahteraan Masyarakat',   'opd' => 'Dinas Pemberdayaan Perempuan dan Perlindungan Anak'],

        // ── LINGKUNGAN HIDUP DAN KEHUTANAN ──
        'Sampah'              => ['category' => 'Lingkungan Hidup dan Kehutanan',        'opd' => 'Dinas Lingkungan Hidup'],
        'Pencemaran Air'      => ['category' => 'Lingkungan Hidup dan Kehutanan',        'opd' => 'Dinas Lingkungan Hidup'],
        'Pencemaran Lingkungan'=> ['category' => 'Lingkungan Hidup dan Kehutanan',       'opd' => 'Dinas Lingkungan Hidup'],
        'Pohon'               => ['category' => 'Lingkungan Hidup dan Kehutanan',        'opd' => 'BPBD'],

        // ── BENCANA DAN PENANGGULANGAN DARURAT ──
        'Banjir'              => ['category' => 'Bencana dan Penanggulangan Darurat',    'opd' => 'BPBD'],
        'Kebakaran'           => ['category' => 'Bencana dan Penanggulangan Darurat',    'opd' => 'BPBD'],
        'Tanah Longsor'       => ['category' => 'Bencana dan Penanggulangan Darurat',    'opd' => 'BPBD'],
        'Kebakaran Hutan'     => ['category' => 'Bencana dan Penanggulangan Darurat',    'opd' => 'BPBD'],

        // ── ADMINISTRASI KEPENDUDUKAN ──
        'KTP'                 => ['category' => 'Administrasi Kependudukan',             'opd' => 'Disdukcapil'],
        'KK'                  => ['category' => 'Administrasi Kependudukan',             'opd' => 'Disdukcapil'],
        'Akta Kelahiran'      => ['category' => 'Administrasi Kependudukan',             'opd' => 'Disdukcapil'],
        'Akta Kematian'       => ['category' => 'Administrasi Kependudukan',             'opd' => 'Disdukcapil'],

        // ── PENDIDIKAN ──
        'Fasilitas Pendidikan'=> ['category' => 'Pendidikan',                            'opd' => 'Dinas Pendidikan'],
        'Guru'                => ['category' => 'Pendidikan',                            'opd' => 'Dinas Pendidikan'],
        'Sekolah'             => ['category' => 'Pendidikan',                            'opd' => 'Dinas Pendidikan'],

        // ── KESEHATAN ──
        'Fasilitas Kesehatan' => ['category' => 'Kesehatan',                             'opd' => 'Dinas Kesehatan'],
        'Puskesmas'           => ['category' => 'Kesehatan',                             'opd' => 'Dinas Kesehatan'],
        'Rumah Sakit'         => ['category' => 'Kesehatan',                             'opd' => 'RSUD Agoesdjam'],
        'BPJS'                => ['category' => 'Kesehatan',                             'opd' => 'Dinas Kesehatan'],

        // ── KOMUNIKASI DAN INFORMATIKA ──
        'Internet'            => ['category' => 'Komunikasi dan Informatika',            'opd' => 'Dinas Komunikasi dan Informatika'],
        'Blank Spot'          => ['category' => 'Komunikasi dan Informatika',            'opd' => 'Dinas Komunikasi dan Informatika'],
        'Aplikasi Pemerintah' => ['category' => 'Komunikasi dan Informatika',            'opd' => 'Dinas Komunikasi dan Informatika'],
        'Website Pemerintah'  => ['category' => 'Komunikasi dan Informatika',            'opd' => 'Dinas Komunikasi dan Informatika'],

        // ── PERIZINAN DAN INVESTASI ──
        'Perizinan Usaha'     => ['category' => 'Perizinan dan Investasi',               'opd' => 'DPMPTSP'],

        // ── KEUANGAN DAN PAJAK DAERAH ──
        'Pajak'               => ['category' => 'Keuangan dan Pajak Daerah',             'opd' => 'BPKAD'],
        'Retribusi'           => ['category' => 'Keuangan dan Pajak Daerah',             'opd' => 'BPKAD'],
        'Pendapatan / Gaji'   => ['category' => 'Keuangan dan Pajak Daerah',             'opd' => 'BPKAD'],

        // ── PERTANIAN, PERIKANAN, DAN PETERNAKAN ──
        'Irigasi'             => ['category' => 'Pertanian, Perikanan, dan Peternakan',  'opd' => 'Dinas Pertanian, Peternakan dan Perkebunan'],
        'Perikanan'           => ['category' => 'Pertanian, Perikanan, dan Peternakan',  'opd' => 'Dinas Ketahanan Pangan, Kelautan, dan Perikanan'],
        'Nelayan'             => ['category' => 'Pertanian, Perikanan, dan Peternakan',  'opd' => 'Dinas Ketahanan Pangan, Kelautan, dan Perikanan'],
        'Peternakan'          => ['category' => 'Pertanian, Perikanan, dan Peternakan',  'opd' => 'Dinas Pertanian, Peternakan dan Perkebunan'],
        'Perkebunan'          => ['category' => 'Pertanian, Perikanan, dan Peternakan',  'opd' => 'Dinas Pertanian, Peternakan dan Perkebunan'],

        // ── PERDAGANGAN, UMKM, DAN KOPERASI ──
        'UMKM'                => ['category' => 'Perdagangan, UMKM, dan Koperasi',      'opd' => 'Dinas Perindustrian, Perdagangan, Koperasi dan UKM'],
        'Koperasi'            => ['category' => 'Perdagangan, UMKM, dan Koperasi',      'opd' => 'Dinas Perindustrian, Perdagangan, Koperasi dan UKM'],
        'Pasar'               => ['category' => 'Perdagangan, UMKM, dan Koperasi',      'opd' => 'Dinas Perindustrian, Perdagangan, Koperasi dan UKM'],

        // ── KETENTRAMAN DAN KETERTIBAN UMUM ──
        'Ketertiban Umum'     => ['category' => 'Ketentraman dan Ketertiban Umum',       'opd' => 'Satpol PP'],
        'Keamanan'            => ['category' => 'Ketentraman dan Ketertiban Umum',       'opd' => 'Satpol PP'],
        'Hewan Liar'          => ['category' => 'Ketentraman dan Ketertiban Umum',       'opd' => 'Satpol PP'],
        'Tempat Ibadah'       => ['category' => 'Ketentraman dan Ketertiban Umum',       'opd' => 'Satpol PP'],
        'Ruang Publik'        => ['category' => 'Fasilitas Umum',                        'opd' => 'Dinas Perkim'],
        'Taman Kota'          => ['category' => 'Fasilitas Umum',                        'opd' => 'Dinas Perkim'],

        // ── KEPEGAWAIAN / SDM APARATUR ──
        'PHK'                 => ['category' => 'Kepegawaian / SDM Aparatur',            'opd' => 'Dinas Tenaga Kerja dan Transmigrasi'],
        'Pelatihan Kerja'     => ['category' => 'Kepegawaian / SDM Aparatur',            'opd' => 'Dinas Tenaga Kerja dan Transmigrasi'],
        'Kepegawaian'         => ['category' => 'Kepegawaian / SDM Aparatur',            'opd' => 'BKPSDM'],

        // ── LAINNYA ──
        'Pertanahan'          => ['category' => 'Pertanahan',                            'opd' => 'BPN'],
        'Sengketa Tanah'      => ['category' => 'Sengketa Tanah',                       'opd' => 'BPN'],
        'Perumahan'           => ['category' => 'Perumahan dan Lingkungan Hidup',        'opd' => 'Dinas Perkim'],
        'Pariwisata'          => ['category' => 'Pariwisata, Kebudayaan, dan Olahraga',  'opd' => 'Dinas Kebudayaan dan Pariwisata'],
        'Kebudayaan'          => ['category' => 'Pariwisata, Kebudayaan, dan Olahraga',  'opd' => 'Dinas Kebudayaan dan Pariwisata'],
        'Olahraga'            => ['category' => 'Pariwisata, Kebudayaan, dan Olahraga',  'opd' => 'Dinas Pemuda dan Olahraga'],
        'Nomor Darurat'       => ['category' => 'Pertanyaan',                            'opd' => 'RSUD Agoesdjam'],
        'ATM'                 => ['category' => 'Bank Kalbar',                           'opd' => 'Bank Kalbar'],
        'Makan Bergizi Gratis'=> ['category' => 'Makan Bergizi Gratis',                  'opd' => 'Dinas Kesehatan'],
        'Monitoring Berita'   => ['category' => 'Lain-lain / Umum',                      'opd' => null],
        'Keluhan Masyarakat'  => ['category' => 'Pengaduan Pelayanan Publik',            'opd' => null],
        'Aduan Masyarakat'    => ['category' => 'Pengaduan Pelayanan Publik',            'opd' => null],
    ];

    public function classify(string $message): array
    {
        $message = $this->normalizeText($message);

        if ($message === '') {
            return $this->fallbackResult(
                'Sedang',
                'Aduan kosong atau tidak valid.'
            );
        }

        // ── Pre-check: Monitoring Berita (hemat API call) ──
        if ($this->isMonitoringBerita($message)) {
            return $this->buildMonitoringBeritaResult($message);
        }

        $master = $this->buildMasterData();

        $result = null;

        try {
            $response = $this->callGemini(
                $this->buildPrompt($master, $message, false)
            );

            $result = $this->parseResult($response);

            if (!$this->isValidResult($result, $master)) {
                $response = $this->callGemini(
                    $this->buildPrompt($master, $message, true)
                );

                $result = $this->parseResult($response);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('AI Classification API Error: ' . $e->getMessage());
        }

        if (!$this->isValidResult($result, $master)) {
            return $this->fallbackResultFromMessage(
                $message,
                'Diklasifikasikan via analisis kata kunci pesan.'
            );
        }

        return $this->sanitizeResult($result, $master, $message);
    }

    // ──────────────────────────────────────────────────────────────────────
    //  MONITORING BERITA PRE-CHECK
    // ──────────────────────────────────────────────────────────────────────

    private function isMonitoringBerita(string $message): bool
    {
        $newsDomains = [
            'hariantribuana.co', 'suaraindo.id', 'mediawartatipikor.com',
            'busernet.co.id', 'lintaskapuas.com', 'suarapemredkalbar.com',
            'faktakalbar.id', 'indometro.id', 'kalbar.kabardaerah.com',
            'suarautamanews.com', 'mediasuaramabes.com',
            'ketapang.suarakalbar.co.id',
        ];

        $domainPattern = implode('|', array_map(fn($d) => preg_quote($d, '/'), $newsDomains));
        $hasNewsUrl = preg_match('/https?:\/\/(?:www\.)?(' . $domainPattern . ')/i', $message);

        if (!$hasNewsUrl) {
            return false;
        }

        $textWithoutUrls = preg_replace('/https?:\/\/\S+/', '', $message);
        $textWithoutUrls = trim($textWithoutUrls);
        $wordCount = str_word_count($textWithoutUrls);

        return $wordCount < 30;
    }

    private function buildMonitoringBeritaResult(string $message): array
    {
        $opds = $this->fallbackOpdsFromMessage($message);

        return [
            'suggested_category'     => 'Lain-lain / Umum',
            'suggested_sub_category' => 'Monitoring Berita',
            'suggested_opds'         => array_slice($opds, 0, 1),
            'priority'               => 'Rendah',
            'confidence'             => 95,
            'reasoning'              => 'Pesan berisi link berita untuk monitoring media.',
        ];
    }

    // ──────────────────────────────────────────────────────────────────────
    //  MASTER DATA & PROMPT
    // ──────────────────────────────────────────────────────────────────────

    private function buildMasterData(): array
    {
        $categories = Category::orderBy('name')->pluck('name')->values()->all();
        $subCategories = SubCategory::orderBy('name')->pluck('name')->values()->all();
        $opds = Opd::orderBy('name')->pluck('name')->values()->all();

        return [
            'categories' => $categories,
            'subCategories' => $subCategories,
            'opds' => $opds,
            'categoryBlock' => implode("\n- ", $categories),
            'subCategoryBlock' => implode("\n- ", $subCategories),
            'opdBlock' => implode("\n- ", $opds),
        ];
    }

    private function buildPrompt(array $master, string $message, bool $retry = false): string
    {
        $examples = <<<'TXT'
CONTOH 1 (PDAM — topik terbanyak ~22.6%):
Aduan: "air PDAM jalur kalinilam perum. green aulia udah seminggu an dak ngalir, udah di laporkan ke Kantor tapi dak ade tindak lanjut, mohon bantuan di teruskan ke Pimpinan nye. No ID PDAM : 1080961"
JSON:
{"suggested_sub_category": "Air Bersih", "priority": "Sedang", "confidence": 97, "reasoning": "Keluhan air PDAM tidak mengalir sudah seminggu, sudah dilaporkan tanpa tindak lanjut."}

CONTOH 2 (Lampu Jalan — topik kedua ~16.7%):
Aduan: "Lampu PJU mati 1 tiang. Alamat: Perumahan BSD Sukaharja, Kabupaten Ketapang, Kalimantan Barat"
JSON:
{"suggested_sub_category": "Lampu Jalan", "priority": "Sedang", "confidence": 96, "reasoning": "Penerangan jalan umum tidak berfungsi di area perumahan."}

CONTOH 3 (Jembatan — bahaya keselamatan):
Aduan: "jembatan di kampung saya itu udah agak goyang tiang nya bisa ndak di ganti jembatan nya Di desa batu lapis"
JSON:
{"suggested_sub_category": "Jembatan", "priority": "Tinggi", "confidence": 95, "reasoning": "Jembatan tidak stabil membahayakan keselamatan pengguna."}

CONTOH 4 (Bantuan Sosial / Bedah Rumah):
Aduan: "Nama: Ahmad syahbandi. Alamat: JL.H.muhammad kumuk. Permasalahan: Belum mendapatkan bantuan bedah rumah."
JSON:
{"suggested_sub_category": "Bantuan Sosial", "priority": "Sedang", "confidence": 94, "reasoning": "Permohonan bantuan sosial bedah rumah yang belum diterima."}

CONTOH 5 (Jalan Rusak — bahaya kecelakaan):
Aduan: "sering terjadi tumpahan minyak CPO di jalan sui awan yg menyebabkan kecelakaan sepeda motor. Dan tumpahan minyak menyebabkan jalan rusak dan bergelombang. Mohon dapat menjadi perhatian pemda karena sangat membahayakan pengendara motor."
JSON:
{"suggested_sub_category": "Jalan", "priority": "Tinggi", "confidence": 96, "reasoning": "Jalan rusak akibat tumpahan CPO membahayakan pengendara motor."}

CONTOH 6 (Orang Terlantar):
Aduan: "Tolong dibantu uruskan seorang ibu ibu yang cacat kakinya sering muncul didaerah sukaharja dekat dunia fashion. Dia sering minta2 di tepi jalan, tolong dinas sosial bisa bantu mengurusnya."
JSON:
{"suggested_sub_category": "Orang Terlantar", "priority": "Tinggi", "confidence": 96, "reasoning": "Warga disabilitas terlantar membutuhkan penanganan Dinas Sosial."}

CONTOH 7 (ODGJ):
Aduan: "izin melaporkan ada lansia dg gaduh gelisah di jalan kalinilam. Ada aksi kekerasan dari cucu. Beliau sering kabur dan sulit dikendalikan, mohon bantuannya"
JSON:
{"suggested_sub_category": "ODGJ", "priority": "Tinggi", "confidence": 95, "reasoning": "Lansia dengan gangguan jiwa memerlukan evakuasi dan penanganan Dinas Sosial."}

CONTOH 8 (KDRT):
Aduan: "kdrt dan perselingkuhan. Cuma bagian badan sm kepala yg benjol2 ngga ke foto ka. Dia kdrt gara2 ketahuan selingkuh dan bela selingkuhan nya ka"
JSON:
{"suggested_sub_category": "KDRT", "priority": "Tinggi", "confidence": 97, "reasoning": "Laporan KDRT dengan bukti kekerasan fisik memerlukan penanganan segera."}

CONTOH 9 (Ketenagakerjaan):
Aduan: "Kami pemuda kecamatan Matan Hilir Selatan sudah seringkali melamar pekerjaan di perusahaan PT BAP dan KBS tapi lamaran tidak ada yang diterima, padahal sudah mediasi. Mohon solusinya"
JSON:
{"suggested_sub_category": "Ketenagakerjaan", "priority": "Sedang", "confidence": 94, "reasoning": "Keluhan ketenagakerjaan terkait akses lapangan kerja bagi masyarakat lokal."}

CONTOH 10 (Nelayan):
Aduan: "tolong bantu kami untuk alat tangkap, sudah beberapa tahun program ke pemerintah tidak pernah lagi mengucurkan bantuan kepada nelayan pesisir dusun sungai tengar kecamatan Kendawangan"
JSON:
{"suggested_sub_category": "Nelayan", "priority": "Sedang", "confidence": 95, "reasoning": "Permohonan bantuan alat tangkap untuk nelayan pesisir."}

CONTOH 11 (Listrik PLN):
Aduan: "memohon bantuan supaya PLN kami bisa nyala, kendala nya kabel belum ada dari simpang kelampai 7 km, tiang sudah ada mohon dibantu"
JSON:
{"suggested_sub_category": "Listrik", "priority": "Sedang", "confidence": 96, "reasoning": "Permohonan pemasangan jaringan listrik PLN ke daerah terpencil."}

CONTOH 12 (Drainase — ancaman pertanian):
Aduan: "tidak berfungsinya pintu air persawahan desa Banjarsari karena tidak ada pemeliharaan dari dinas pengairan menyebabkan masuknya air pasang laut ke persawahan"
JSON:
{"suggested_sub_category": "Drainase", "priority": "Tinggi", "confidence": 95, "reasoning": "Kerusakan infrastruktur pengairan mengancam lahan pertanian warga."}

CONTOH 13 (Kepegawaian):
Aduan: "pak tolong bantu saya mau pindah tempat ngajar, dari kabupaten Kepulauan Mentawai mau pindah ke Ketapang, saya putra daerah asli Ketapang pak, tolong"
JSON:
{"suggested_sub_category": "Kepegawaian", "priority": "Rendah", "confidence": 93, "reasoning": "Permohonan mutasi guru antar kabupaten."}

CONTOH 14 (Internet/Sinyal):
Aduan: "di Desa sp3 sembelangaan ad tower kominfo BAKTI dengn kualitasnya blum bisa aktf, ank2 km yg belajar krna hrus pergi jauh2 untuk mengikuti ANBK"
JSON:
{"suggested_sub_category": "Internet", "priority": "Sedang", "confidence": 95, "reasoning": "Tower internet tidak berfungsi menghambat kegiatan pendidikan."}

CONTOH 15 (Pajak/Retribusi — permintaan informasi):
Aduan: "Info bagai mana cara mendaftar pajak bumi dan bangunan d wilayah ketapang"
JSON:
{"suggested_sub_category": "Pajak", "priority": "Rendah", "confidence": 93, "reasoning": "Pertanyaan prosedur pendaftaran PBB daerah."}

CONTOH 16 (Sampah — lingkungan):
Aduan: "sampah di parit depan rumah kami sudah menumpuk betabur kemana-mana, bau menyengat, tapi truk sampah ndak pernah lewat semenjak bulan lalu"
JSON:
{"suggested_sub_category": "Sampah", "priority": "Sedang", "confidence": 95, "reasoning": "Sampah menumpuk dan tidak diangkut truk sampah, menimbulkan bau."}

CONTOH 17 (Kebakaran — darurat):
Aduan: "ada kebakaran di daerah mulia kerta, api sudah menjalar ke 3 rumah, tolong kirim pemadam"
JSON:
{"suggested_sub_category": "Kebakaran", "priority": "Tinggi", "confidence": 98, "reasoning": "Kebakaran aktif menjalar ke pemukiman, memerlukan penanganan darurat segera."}

CONTOH 18 (Banjir — darurat):
Aduan: "air sudah masuk rumah setinggi lutut di kelurahan sampit, warga sudah mulai mengungsi ke sekolah, mohon bantuannya"
JSON:
{"suggested_sub_category": "Banjir", "priority": "Tinggi", "confidence": 97, "reasoning": "Banjir merendam pemukiman, warga mengungsi, memerlukan evakuasi dan bantuan."}

CONTOH 19 (Dialek Melayu Ketapang):
Aduan: "aik pdam kamek dak jalan udah seminggu, saye udah lapor tapi dak ade tanggapan dri kantor nye"
JSON:
{"suggested_sub_category": "Air Bersih", "priority": "Sedang", "confidence": 96, "reasoning": "Air PDAM tidak mengalir sudah seminggu tanpa tanggapan setelah dilaporkan."}

CONTOH 20 (Kabel/Listrik — bukan jalan):
Aduan: "ada kabel listrik jatuh menjuntai di tengah jalan depan SD 12 Ketapang, bahaya kena biak-biak yang lewat"
JSON:
{"suggested_sub_category": "Listrik", "priority": "Tinggi", "confidence": 97, "reasoning": "Kabel listrik menjuntai di jalan membahayakan keselamatan anak sekolah."}
TXT;

        $retryNote = $retry
            ? "\n\nPERHATIAN:\nSebelumnya keluaran tidak valid.\nSekarang keluarkan HANYA JSON murni.\nJangan gunakan markdown.\nJangan gunakan penjelasan di luar JSON."
            : '';

        return <<<PROMPT
Anda adalah SIMODU KMC — Sistem Informasi Monitoring Aduan Ketapang Media Center, Kabupaten Ketapang, Kalimantan Barat.
Sistem ini menerima aduan masyarakat dari komentar media sosial (Instagram/Facebook) untuk diklasifikasikan secara otomatis.

═══ TUGAS UTAMA ═══
Tentukan SUB KATEGORI yang paling tepat untuk aduan berikut.
Kategori induk dan OPD (Organisasi Perangkat Daerah) tujuan akan ditentukan otomatis oleh sistem berdasarkan sub kategori yang Anda pilih.

═══ KONTEKS BAHASA LOKAL (Dialek Melayu Ketapang/Kalbar) ═══
Masyarakat Ketapang sering menggunakan dialek Melayu lokal dalam aduan mereka.
Anda WAJIB memahami dan menerjemahkan dialek ini sebelum mengklasifikasikan:
- "dak", "ndak", "sik", "sik ada" = tidak / tidak ada
- "aek", "aik", "aiq" = air (biasanya terkait laporan PDAM)
- "dak ngalir", "ngk jalan", "dak pakai jalan" = air tidak mengalir (masalah PDAM)
- "aik teh" = air keruh seperti air teh (masalah kualitas air PDAM)
- "parit" = drainase / saluran air / selokan
- "sumbat", "tepampat" = tersumbat
- "PJU", "lampu jalan" = Penerangan Jalan Umum
- "solar sell", "solar cell" = lampu jalan tenaga surya
- "id pelanggan", "idpel", "no id pdam" = nomor pelanggan PDAM
- "sanyo" = mesin pompa air
- "mati lampu", "padam listrik", "padam" = masalah listrik padam (→ sub kategori Listrik)
- "lampu merah" = lampu lalu lintas (→ sub kategori Lampu Lalu Lintas, BUKAN Lampu Jalan)
- "min", "mimin" = sapaan untuk admin KMC (BUKAN istilah internet/website)
- "biak" = anak-anak / remaja
- "bederai", "ancur", "bapok" = rusak parah / hancur (contoh: jalan bederai = jalan rusak parah)
- "betabur" = berserakan (biasanya terkait sampah)
- "lepak" = nongkrong (biasanya terkait ketertiban umum)
- "sidak" = mereka, "kamek" = saya / kami, "kitak" = kalian
- "pokok" = pohon (contoh: pokok tumbang = pohon tumbang)
- "ngadang" = menghalangi / melintang
- "ade" = ada, "saye" = saya, "dri" = dari
- "turun padang" = kunjungan langsung ke lapangan
- "gaduh gelisah" = perilaku agresif/tidak terkendali (biasanya ODGJ)
- "minta2", "minta-minta" = meminta-minta (biasanya orang terlantar)
- "ngerendap" = terendam air / amblas

═══ ATURAN KLASIFIKASI WAJIB ═══
1. Gunakan HANYA sub kategori yang tersedia dalam daftar di bawah. DILARANG membuat sub kategori baru.
2. Jika tidak ada sub kategori yang cocok sama sekali, gunakan: "Aduan Masyarakat".
3. Fokus pada OBJEK UTAMA masalah, bukan lokasi kejadian:
   - Kabel listrik jatuh di jalan → "Listrik" (bukan "Jalan")
   - Pohon tumbang di jalan → "Pohon" (bukan "Jalan")
   - Lampu PJU mati di jembatan → "Lampu Jalan" (bukan "Jembatan")
   - Sampah menumpuk di parit → "Sampah" (bukan "Drainase")
4. Bedakan dengan cermat:
   - "Lampu Jalan" = PJU/penerangan jalan/solar cell ≠ "Lampu Lalu Lintas" = lampu merah/traffic light
   - "Jalan" = jalan kabupaten/provinsi ≠ "Jalan Gang" = jalan lingkungan/gang/lorong
   - "Drainase" = parit/gorong-gorong ≠ "Irigasi" = saluran persawahan/pintu air sawah
   - "Air Bersih" = masalah PDAM ≠ "Pencemaran Air" = sungai/limbah tercemar
   - "ODGJ" = gangguan jiwa ≠ "Orang Terlantar" = gelandangan/pengemis
5. priority hanya boleh salah satu dari: "Rendah", "Sedang", atau "Tinggi".
6. confidence harus angka bulat 0–100 (seberapa yakin Anda dengan sub kategori yang dipilih).
7. reasoning harus singkat dan spesifik, maksimal 1 kalimat, menjelaskan mengapa sub kategori tersebut dipilih.
8. Output HARUS berupa JSON valid murni. Tanpa markdown, tanpa teks tambahan, tanpa kode block.

═══ ATURAN PENILAIAN PRIORITAS ═══
Prioritas menentukan urgensi penanganan aduan oleh OPD terkait:

▸ TINGGI — Memerlukan penanganan segera/darurat:
  • Ancaman keselamatan jiwa: kebakaran, banjir merendam, longsor, kecelakaan, pohon tumbang di jalan
  • Infrastruktur berbahaya: jembatan goyang/rusak parah, kabel listrik menjuntai, jalan amblas/putus
  • Kekerasan & perlindungan: KDRT, kekerasan anak, kekerasan perempuan
  • Kemanusiaan mendesak: ODGJ berkeliaran, orang terlantar butuh evakuasi
  • Masalah berlarut-larut: sudah berbulan-bulan/bertahun-tahun tanpa tindak lanjut, sudah dilaporkan berkali-kali tapi tidak ditanggapi
  • Bencana aktif: kebakaran hutan, banjir tidak surut, tanah longsor

▸ SEDANG — Gangguan layanan publik yang perlu ditindak dalam waktu dekat:
  • Gangguan utilitas: air PDAM tidak mengalir, listrik padam area kecil
  • Infrastruktur rusak (tidak darurat): jalan berlubang, lampu jalan mati, drainase tersumbat
  • Kebersihan: sampah menumpuk, pencemaran ringan
  • Pelayanan publik: lambatnya pengurusan KTP/KK/akta, BPJS bermasalah
  • Bantuan sosial: permohonan bansos/BLT/bedah rumah
  • Ketenagakerjaan: keluhan akses kerja, outsourcing

▸ RENDAH — Tidak mendesak, bersifat informatif atau umum:
  • Pertanyaan/permintaan informasi: tanya prosedur, info layanan
  • Saran/masukan kebijakan
  • Monitoring berita/media
  • Permohonan administratif (mutasi, pindah tugas)

═══ DAFTAR SUB KATEGORI YANG TERSEDIA ═══
- {$master['subCategoryBlock']}

═══ CONTOH KLASIFIKASI ═══
{$examples}

═══ ADUAN YANG HARUS DIKLASIFIKASIKAN ═══
"{$message}"
{$retryNote}

KELUARKAN HANYA JSON SEPERTI INI (tanpa teks lain):
{"suggested_sub_category": "...", "priority": "...", "confidence": 90, "reasoning": "..."}
PROMPT;
    }

    // ──────────────────────────────────────────────────────────────────────
    //  API CALL & PARSING
    // ──────────────────────────────────────────────────────────────────────

    private function callGemini(string $prompt): string
    {
        $maxRetries = 3;
        $retryDelay = 5; // detik

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $url = rtrim(config('gemini.base_url'), '/') . '/' . config('gemini.model') . ':generateContent?key=' . config('gemini.api_key');

                $response = Http::timeout(config('gemini.timeout', 30))
                    ->post($url, [
                        'contents' => [
                            [
                                'role' => 'user',
                                'parts' => [
                                    ['text' => "Anda adalah SIMODU KMC — sistem AI klasifikasi aduan masyarakat Ketapang Media Center. Ikuti aturan secara ketat dan selalu menghasilkan JSON valid. Fokus pada akurasi sub kategori, prioritas, dan deteksi spam/duplikasi.\n\n" . $prompt]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 0.0,
                            'responseMimeType' => 'application/json'
                        ]
                    ]);

                // Rate limit (429) atau server error (5xx) → tunggu lalu retry
                if (in_array($response->status(), [429, 503, 502]) && $attempt < $maxRetries) {
                    sleep($retryDelay);
                    continue;
                }

                if (!$response->successful()) {
                    $errMsg = $response->json('error.message') ?? $response->body();

                    throw new \Exception(
                        'Gemini API error [' . $response->status() . ']: ' . $errMsg
                    );
                }

                $parts = $response->json('candidates.0.content.parts') ?? [];
                $content = '';
                foreach ($parts as $part) {
                    if (empty($part['thought']) || $part['thought'] !== true) {
                        $content = $part['text'] ?? '';
                    }
                }

                // Output kosong → retry
                if (trim($content) === '' && $attempt < $maxRetries) {
                    sleep($retryDelay);
                    continue;
                }

                return $content;

            } catch (\Exception $e) {
                if ($attempt === $maxRetries) {
                    throw new \Exception('Gagal menghubungi AI: ' . $e->getMessage());
                }
                sleep($retryDelay);
            }
        }

        throw new \Exception('Gagal menghubungi AI : maksimum retry tercapai.');
    }

    private function parseResult(string $content): ?array
    {
        $content = trim($content);

        if ($content === '') {
            return null;
        }

        $content = preg_replace('/```json|```/i', '', $content);
        $content = trim($content);

        $start = strpos($content, '{');
        $end = strrpos($content, '}');

        if ($start !== false && $end !== false && $end > $start) {
            $content = substr($content, $start, $end - $start + 1);
        }

        $result = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($result)) {
            return null;
        }

        return $result;
    }

    private function isValidResult(?array $result, array $master): bool
    {
        if (!$result) {
            return false;
        }

        $requiredKeys = [
            'suggested_sub_category',
            'priority',
            'confidence',
            'reasoning',
        ];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $result)) {
                return false;
            }
        }

        // Pastikan suggested_sub_category adalah sub-kategori yang valid (bukan nama kategori)
        $suggestedSub = (string) ($result['suggested_sub_category'] ?? '');
        if ($suggestedSub === '') {
            return false;
        }

        // Cek apakah ada di daftar sub-kategori DB
        $bestMatch = $this->bestMatch($suggestedSub, $master['subCategories']);
        if ($bestMatch === null) {
            return false;
        }

        return true;
    }

    // ──────────────────────────────────────────────────────────────────────
    //  SANITASI & RESOLUSI DARI DATABASE / MAPPING
    // ──────────────────────────────────────────────────────────────────────

    private function sanitizeResult(array $result, array $master, string $message): array
    {
        $categories = $master['categories'];
        $subCategories = $master['subCategories'];
        $opds = $master['opds'];

        // ── 1. Resolve Sub Kategori ──
        $subCategory = $this->resolveSubCategory(
            (string) ($result['suggested_sub_category'] ?? ''),
            $message,
            $subCategories
        );

        // ── 2. Auto-resolve Kategori & OPD dari relasi DB atau mapping ──
        $resolved = $this->resolveFromSubCategory($subCategory, $categories, $opds);
        $category = $resolved['category'];
        $opdList  = $resolved['opds'];

        // ── 3. Jika kategori masih "Lain-lain", coba infer dari teks ──
        if (
            $category === 'Lain-lain / Umum'
            && $subCategory !== 'Aduan Masyarakat'
            && $subCategory !== 'Monitoring Berita'
        ) {
            $inferred = $this->inferCategoryFromText($subCategory, $categories, $message);
            if ($inferred !== null) {
                $category = $inferred;
            }
        }

        // ── 4. Jika OPD masih kosong, fallback dari pesan ──
        if (empty($opdList)) {
            $opdList = array_slice($this->fallbackOpdsFromMessage($message), 0, 1);
        }

        $opdList = $this->sanitizeOpds($opdList, $opds, $message);

        // ── 5. Priority & Confidence ──
        $priority = $this->sanitizePriority(
            (string) ($result['priority'] ?? ''),
            $message
        );

        $confidence = $this->sanitizeConfidence($result['confidence'] ?? 0);

        // ── 6. Reasoning ──
        $reasoning = trim((string) ($result['reasoning'] ?? ''));

        if ($reasoning === '') {
            $reasoning = $this->fallbackReasoning($category, $subCategory, $priority);
        }

        return [
            'suggested_category' => $category,
            'suggested_sub_category' => $subCategory,
            'suggested_opds' => $opdList,
            'priority' => $priority,
            'confidence' => $confidence,
            'reasoning' => $reasoning,
        ];
    }

    /**
     * Resolve Kategori & OPD dari Sub Kategori menggunakan:
     * 1. Relasi database (SubCategory->Category, SubCategory->OPD)
     * 2. Mapping statis SUB_CATEGORY_MAP (fallback jika relasi DB kosong)
     */
    private function resolveFromSubCategory(string $subCategoryName, array $categories, array $opds): array
    {
        $defaultResult = ['category' => 'Lain-lain / Umum', 'opds' => []];

        // ── Coba 1: Relasi database ──
        $subCatModel = SubCategory::where('name', $subCategoryName)->with(['category', 'opd'])->first();

        if ($subCatModel) {
            $cat = $subCatModel->category ? $subCatModel->category->name : null;
            $opd = $subCatModel->opd ? $subCatModel->opd->name : null;

            if ($cat !== null && $opd !== null) {
                return ['category' => $cat, 'opds' => [$opd]];
            }
        }

        // ── Coba 2: Mapping statis ──
        if (isset(self::SUB_CATEGORY_MAP[$subCategoryName])) {
            $map = self::SUB_CATEGORY_MAP[$subCategoryName];
            $category = $map['category'];
            $opdName  = $map['opd'];

            if (in_array($category, $categories, true)) {
                $result = ['category' => $category, 'opds' => []];

                if ($opdName !== null) {
                    $matchedOpd = $this->bestMatch($opdName, $opds, 70);
                    if ($matchedOpd !== null) {
                        $result['opds'] = [$matchedOpd];
                    }
                }

                return $result;
            }
        }

        // ── Coba 3: Fuzzy match nama ──
        $bestMapMatch = $this->bestMatch($subCategoryName, array_keys(self::SUB_CATEGORY_MAP), 80);
        if ($bestMapMatch !== null && isset(self::SUB_CATEGORY_MAP[$bestMapMatch])) {
            $map = self::SUB_CATEGORY_MAP[$bestMapMatch];
            $category = $map['category'];
            $opdName  = $map['opd'];

            if (in_array($category, $categories, true)) {
                $result = ['category' => $category, 'opds' => []];
                if ($opdName !== null) {
                    $matchedOpd = $this->bestMatch($opdName, $opds, 70);
                    if ($matchedOpd !== null) {
                        $result['opds'] = [$matchedOpd];
                    }
                }
                return $result;
            }
        }

        return $defaultResult;
    }

    private function resolveSubCategory(string $candidate, string $message, array $subCategories): string
    {
        $candidate = $this->normalizeText($candidate);

        if ($candidate !== '') {
            $exact = $this->bestMatch($candidate, $subCategories, 90);
            if ($exact !== null) {
                return $exact;
            }

            $close = $this->bestMatch($candidate, $subCategories, 70);
            if ($close !== null) {
                return $close;
            }
        }

        $inferred = $this->inferSubCategoryFromMessage($message, $subCategories);
        if ($inferred !== null) {
            return $inferred;
        }

        return 'Aduan Masyarakat';
    }

    private function sanitizeOpds(array|string $rawOpds, array $knownOpds, string $message): array
    {
        $opds = is_array($rawOpds) ? $rawOpds : [$rawOpds];

        $cleaned = [];

        foreach ($opds as $opd) {
            $opd = $this->normalizeText((string) $opd);

            if ($opd === '') {
                continue;
            }

            $match = $this->bestMatch($opd, $knownOpds, 70);

            if ($match !== null) {
                $cleaned[] = $match;
                continue;
            }

            $cleaned[] = $opd;
        }

        $cleaned = array_values(array_unique($cleaned));
        $cleaned = array_slice($cleaned, 0, 1);

        if (empty($cleaned)) {
            $fallback = $this->fallbackOpdsFromMessage($message);
            return array_slice($fallback, 0, 1);
        }

        return $cleaned;
    }

    private function sanitizePriority(string $priority, string $message): string
    {
        $priority = $this->normalizeText($priority);

        if (in_array($priority, ['Rendah', 'Sedang', 'Tinggi'], true)) {
            return $priority;
        }

        return $this->heuristicPriority($message);
    }

    private function sanitizeConfidence(mixed $confidence): int
    {
        if (is_string($confidence)) {
            $confidence = preg_replace('/[^\d]/', '', $confidence);
        }

        $confidence = (int) $confidence;

        return max(0, min(100, $confidence));
    }

    // ──────────────────────────────────────────────────────────────────────
    //  KEYWORD INFERENCE (dengan bahasa Melayu Ketapang)
    // ──────────────────────────────────────────────────────────────────────

    private function inferCategoryFromText(string $text, array $categories, string $message = ''): ?string
    {
        $text = $this->normalizeText($text . ' ' . $message);

        $map = [
            'Layanan PDAM' => [
                'pdam', 'air bersih',
                'air dak jalan', 'air ngk jalan', 'dak ngalir', 'dak pakai jalan',
                'air tidak mengalir', 'air tidak jalan', 'abonemen', 'id pelanggan',
                'idpel', 'id pdam', 'perumdam', 'tirta pawan', 'aik teh',
            ],
            'Layanan PLN' => [
                'pln', 'listrik', 'mati lampu', 'padam',
                'tiang listrik', 'kabel listrik', 'arus listrik', 'kwh', 'up3 ketapang',
            ],
            'Infrastruktur dan Pekerjaan Umum' => [
                'jalan', 'lampu jalan', 'jembatan', 'drainase',
                'irigasi', 'fasilitas umum', 'pju', 'lampu pju', 'solar sell',
                'lampu merah', 'lampu lalu lintas', 'traffic light', 'gorong-gorong', 'gorong gorong', 'rambat beton',
                'jalan berlubang', 'jalan rusak', 'jembatan gantung', 'jembatan rusak',
            ],
            'Administrasi Kependudukan' => [
                'ktp', 'kk', 'akta kelahiran', 'akta kematian', 'kependudukan', 'disdukcapil', 'skbm',
            ],
            'Perizinan dan Investasi' => [
                'perizinan usaha', 'izin usaha', 'izin', 'investasi', 'pkkpr', 'perijinan',
            ],
            'Pendidikan' => [
                'sekolah', 'guru', 'pendidikan', 'fasilitas pendidikan',
                'asrama mahasiswa', 'buku lks', 'paud', 'anbk',
            ],
            'Kesehatan' => [
                'puskesmas', 'rumah sakit', 'bpjs', 'kesehatan', 'fasilitas kesehatan',
                'psc 119', 'ambulans', 'rsud', 'mbg', 'makan bergizi',
            ],
            'Sosial dan Kesejahteraan Masyarakat' => [
                'bantuan sosial', 'orang terlantar', 'bansos', 'kesejahteraan',
                'blt', 'pkh', 'kdrt', 'kekerasan', 'terlantar',
                'cacat', 'disabilitas', 'lansia', 'bedah rumah',
            ],
            'Kepegawaian / SDM Aparatur' => [
                'phk', 'pelatihan kerja', 'gaji', 'pendapatan', 'kepegawaian', 'sdm',
                'pppk', 'asn', 'pindah tugas', 'mutasi', 'honor', 'honorarium',
            ],
            'Keuangan dan Pajak Daerah' => ['pajak', 'retribusi', 'keuangan', 'pbb', 'pajak bumi'],
            'Pertanian, Perikanan, dan Peternakan' => [
                'irigasi', 'perikanan', 'peternakan', 'perkebunan', 'pertanian', 'nelayan',
                'pupuk', 'sawit', 'padi', 'alat tangkap', 'pupuk subsidi',
            ],
            'Perdagangan, UMKM, dan Koperasi' => ['umkm', 'koperasi', 'pasar', 'perdagangan'],
            'Komunikasi dan Informatika' => [
                'internet', 'blank spot', 'aplikasi pemerintah', 'website pemerintah',
                'kominfo', 'tower', 'sinyal', 'jaringan', 'email dinas', 'medsos',
            ],
            'Pariwisata, Kebudayaan, dan Olahraga' => ['pariwisata', 'kebudayaan', 'olahraga'],
            'Lingkungan Hidup dan Kehutanan' => [
                'sampah', 'pohon', 'hewan liar', 'lingkungan', 'kehutanan',
                'manggrove', 'mangrove', 'polusi', 'pencemaran',
            ],
            'Ketentraman dan Ketertiban Umum' => [
                'ketertiban umum', 'keamanan', 'tempat ibadah', 'ruang publik',
                'taman kota', 'satpol pp', 'anjing', 'hewan berkeliaran', 'parkir liar',
            ],
            'Bencana dan Penanggulangan Darurat' => [
                'kebakaran', 'tanah longsor', 'kebakaran hutan', 'nomor darurat',
                'bencana', 'banjir besar', 'pohon tumbang', 'karhutla',
            ],
            'Pengaduan Pelayanan Publik' => [
                'pelayanan publik', 'layanan publik', 'dana desa', 'korupsi',
                'menyimpang', 'inspektorat',
            ],
            'Lain-lain / Umum' => [
                'pertanyaan', 'keluhan masyarakat', 'aduan masyarakat',
                'verifikasi', 'informasi umum',
            ],
            'Pertanyaan' => ['tanya', 'pertanyaan', 'info', 'informasi', 'bagaimana cara', 'bagai mana'],
            'Bank Kalbar' => ['atm', 'bank kalbar'],
            'Perumahan' => ['perumahan', 'rumah', 'btn', 'residence', 'komplek'],
            'Pertanahan' => ['pertanahan', 'sengketa tanah', 'tanah', 'mafia tanah', 'bpn', 'sertifikat tanah'],
            'Makan Bergizi Gratis' => ['makan bergizi gratis', 'mbg', 'sppg'],
            'Administrasi' => ['administrasi', 'sip'],
            'Fasilitas Umum' => ['taman kota', 'fasilitas bermain', 'neon box', 'banner rusak'],
        ];

        foreach ($map as $category => $keywords) {
            foreach ($keywords as $keyword) {
                $pattern = '/\b' . preg_quote($this->normalizeText($keyword), '/') . '\b/i';
                if (preg_match($pattern, $text)) {
                    if (in_array($category, $categories, true)) {
                        return $category;
                    }
                }
            }
        }

        return null;
    }

    private function inferSubCategoryFromMessage(string $message, array $subCategories): ?string
    {
        $text = $this->normalizeText($message);

        $map = [
            'Air Bersih' => [
                'air bersih', 'air keruh', 'air macet', 'pdam', 'air pdam',
                'dak ngalir', 'ngk jalan', 'dak pakai jalan',
                'air dak jalan', 'air ngk jalan', 'air tidak jalan',
                'air tidak mengalir', 'air tidak ngalir',
                'aik teh', 'abonemen', 'id pelanggan', 'idpel', 'id pdam',
                'no pelanggan', 'pemasangan baru pdam', 'sanyo',
                'perumdam', 'tirta pawan',
            ],
            'Lampu Jalan' => [
                'lampu jalan', 'penerangan', 'pju', 'lampu mati', 'lampu pju',
                'solar sell', 'solar cell', 'lampu gang', 'lampu jembatan',
                'lpju', 'gelap gulita', 'lampu',
                'lampu tidak hidup', 'lampu tidak menyala', 'lampu padam', 'bola lampu',
            ],
            'Lampu Lalu Lintas' => [
                'lampu merah', 'lampu lalu lintas', 'traffic light', 'lampu pengatur jalan', 'lampu lalin',
            ],
            'Jalan' => [
                'jalan', 'berlubang', 'aspal', 'amblas', 'rusak jalan',
                'jalan rusak', 'jalan berlubang', 'debu di jalanan',
                'tumpahan minyak', 'jalan gang',
            ],
            'Jembatan' => [
                'jembatan', 'jembatan gantung', 'jembatan rusak',
                'goyang tiang', 'ngerendap', 'tidak layak pakai',
            ],
            'Drainase' => [
                'drainase', 'saluran', 'selokan', 'gorong-gorong', 'gorong gorong',
                'parit', 'parit sumbat', 'parit tersumbat', 'air tersumbat',
                'normalisasi parit', 'pintu air',
            ],
            'Sampah' => ['sampah', 'tumpukan sampah', 'kebersihan', 'berserakan', 'bau tidak sedap', 'sampah berserakan', 'mengotori jalan'],
            'Banjir' => ['banjir', 'terendam', 'banjir tidak surut', 'air pasang'],
            'Listrik' => [
                'listrik', 'pln', 'mati lampu', 'padam listrik', 'kabel listrik',
                'tiang listrik', 'arus listrik', 'kabel semrawut', 'kabel jatuh',
                'kabel menjuntai', 'kabel putus', 'konslet', 'nyentrum',
            ],
            'KTP' => ['ktp', 'skbm'],
            'KK' => ['kk', 'kartu keluarga'],
            'Akta Kelahiran' => ['akta kelahiran'],
            'Akta Kematian' => ['akta kematian'],
            'Fasilitas Pendidikan' => ['fasilitas pendidikan', 'ruang kelas', 'kelas rusak', 'asrama mahasiswa'],
            'Guru' => ['guru honorer', 'guru kontrak', 'nota tugas guru'],
            'Sekolah' => ['sekolah', 'lks'],
            'Fasilitas Kesehatan' => ['fasilitas kesehatan', 'klinik', 'psc 119', 'ambulans'],
            'Puskesmas' => ['puskesmas'],
            'Rumah Sakit' => ['rumah sakit', 'rsud', 'rawat inap'],
            'BPJS' => ['bpjs'],
            'Bantuan Sosial' => [
                'bantuan sosial', 'bansos', 'blt', 'pkh', 'bedah rumah',
                'bantuan orang sakit', 'tidak pernah menerima bantuan',
            ],
            'Orang Terlantar' => ['orang terlantar', 'terlantar', 'lansia terlantar', 'gelandangan', 'cacat kaki', 'minta minta', 'minta2'],
            'ODGJ' => ['odgj', 'orang gila', 'gangguan jiwa', 'gaduh gelisah', 'delimensia', 'berontak', 'skizofrenia', 'orang gila sering ganggu', 'sulit dikendalikan', 'kabur dan sulit'],
            'Ketenagakerjaan' => ['ketenagakerjaan', 'outsourcing', 'lowongan kerja', 'loker', 'melamar pekerjaan', 'tenaga kerja lokal', 'ring 1'],
            'Pencemaran Air' => ['pencemaran', 'sungai hitam', 'sungai bau', 'limbah pabrik', 'sungai tercemar', 'ikan mati'],
            'Pencemaran Lingkungan' => ['polusi', 'asap pabrik', 'bau menyengat', 'oli bekas', 'polusi udara'],
            'Jalan Gang' => ['jalan gang', 'jalan depan sd', 'jalan depan sekolah', 'gang rusak'],
            'Perijinan' => ['perijinan', 'pkkpr', 'permohonan pkkpr'],
            'UMKM' => ['umkm'],
            'Koperasi' => ['koperasi'],
            'Pasar' => ['pasar'],
            'Irigasi' => ['irigasi', 'persawahan'],
            'Perikanan' => ['perikanan'],
            'Nelayan' => ['nelayan', 'alat tangkap'],
            'Peternakan' => ['peternakan'],
            'Perkebunan' => ['perkebunan', 'sawit', 'kelapa sawit'],
            'Internet' => ['internet', 'blank spot', 'sinyal', 'tower kominfo', 'tower bakti', 'wifi', 'tower tidak aktif'],
            'Blank Spot' => ['blank spot'],
            'Aplikasi Pemerintah' => ['aplikasi pemerintah', 'aplikasi'],
            'Website Pemerintah' => ['website pemerintah', 'website'],
            'Perizinan Usaha' => ['perizinan usaha', 'izin usaha', 'perijinan', 'pkkpr'],
            'Pajak' => ['pajak', 'pbb'],
            'Retribusi' => ['retribusi'],
            'Pendapatan / Gaji' => ['gaji', 'pendapatan', 'honor', 'honorarium', 'gaji honor', 'tidak dibayarkan'],
            'Transportasi Umum' => ['transportasi umum', 'angkutan'],
            'Parkir' => ['parkir', 'truk nakal', 'antri bahan bakar'],
            'Kebakaran' => ['kebakaran'],
            'Tanah Longsor' => ['longsor'],
            'Kebakaran Hutan' => ['kebakaran hutan', 'karhutla'],
            'Ketertiban Umum' => ['ketertiban umum', 'ugal ugalan'],
            'Keamanan' => ['keamanan', 'dirampas', 'kriminal'],
            'Hewan Liar' => ['hewan liar', 'anjing berkeliaran', 'anjing'],
            'Tempat Ibadah' => ['tempat ibadah', 'masjid', 'gereja', 'surau'],
            'Ruang Publik' => ['ruang publik'],
            'Taman Kota' => ['taman kota', 'fasilitas bermain'],
            'Pertanahan' => ['pertanahan'],
            'Sengketa Tanah' => ['sengketa tanah', 'mafia tanah', 'sertifikat tanah', 'ganti untung'],
            'PHK' => ['phk'],
            'Pelatihan Kerja' => ['pelatihan kerja', 'balai latihan kerja', 'blk'],
            'Kepegawaian' => ['kepegawaian', 'pindah tugas', 'mutasi', 'pindah tempat ngajar'],
            'Perumahan' => ['perumahan', 'bedah rumah perkim', 'rusun'],
            'Nomor Darurat' => ['nomor darurat', 'panggilan darurat'],
            'KDRT' => ['kdrt', 'kekerasan dalam rumah tangga'],
            'Kekerasan Anak' => ['kekerasan anak'],
            'Kekerasan Perempuan' => ['kekerasan perempuan'],
            'ATM' => ['atm', 'bank kalbar'],
            'Pohon' => ['pohon', 'pohon miring', 'pohon tumbang', 'dahan'],
            'Monitoring Berita' => ['monitoring berita'],
            'Makan Bergizi Gratis' => ['makan bergizi gratis', 'mbg', 'sppg'],
            'Keluhan Masyarakat' => ['keluhan masyarakat'],
            'Aduan Masyarakat' => ['aduan masyarakat'],
        ];

        // Score-based matching: hitung jumlah keyword yang cocok per sub-kategori
        // dan pilih yang paling banyak cocok (bukan first-match)
        $scores = [];
        foreach ($map as $subCategory => $keywords) {
            if (!in_array($subCategory, $subCategories, true)) {
                continue;
            }
            $score = 0;
            foreach ($keywords as $keyword) {
                $normalizedKeyword = $this->normalizeText($keyword);
                $pattern = '/\b' . preg_quote($normalizedKeyword, '/') . '\b/i';
                if (preg_match($pattern, $text)) {
                    // Multi-word keywords lebih spesifik → skor lebih tinggi
                    $wordCount = str_word_count($normalizedKeyword);
                    $score += ($wordCount >= 2) ? 3 : 1;
                }
            }
            if ($score > 0) {
                $scores[$subCategory] = $score;
            }
        }

        if (!empty($scores)) {
            arsort($scores);
            return array_key_first($scores);
        }

        return null;
    }

    // ──────────────────────────────────────────────────────────────────────
    //  FALLBACK OPD (nama sesuai database)
    // ──────────────────────────────────────────────────────────────────────

    private function fallbackOpdsFromMessage(string $message): array
    {
        $message = $this->normalizeText($message);
        $fallback = [];

        if (
            str_contains($message, 'pdam') ||
            str_contains($message, 'air bersih') ||
            str_contains($message, 'air tidak') ||
            str_contains($message, 'dak ngalir') ||
            str_contains($message, 'ngk jalan') ||
            str_contains($message, 'abonemen') ||
            str_contains($message, 'id pelanggan') ||
            str_contains($message, 'idpel')
        ) {
            $fallback[] = 'PDAM Ketapang';
        }

        if (
            str_contains($message, 'lampu jalan') ||
            str_contains($message, 'lampu mati') ||
            str_contains($message, 'pju') ||
            str_contains($message, 'lampu pju') ||
            str_contains($message, 'penerangan jalan') ||
            str_contains($message, 'solar sell') ||
            str_contains($message, 'lampu gang')
        ) {
            $fallback[] = 'Dinas Perhubungan';
        }

        if (
            str_contains($message, 'jalan') ||
            str_contains($message, 'jembatan') ||
            str_contains($message, 'drainase') ||
            str_contains($message, 'gorong') ||
            str_contains($message, 'parit')
        ) {
            $fallback[] = 'Dinas PUTR';
        }

        if (str_contains($message, 'sampah') || str_contains($message, 'lingkungan')) {
            $fallback[] = 'Dinas Lingkungan Hidup';
        }

        if (str_contains($message, 'pohon') || str_contains($message, 'dahan')) {
            $fallback[] = 'BPBD';
        }

        if (
            str_contains($message, 'ktp') || str_contains($message, 'kk') ||
            str_contains($message, 'akta') || str_contains($message, 'skbm')
        ) {
            $fallback[] = 'Disdukcapil';
        }

        if (
            str_contains($message, 'listrik') || str_contains($message, 'pln') ||
            str_contains($message, 'mati lampu') || str_contains($message, 'kabel listrik') ||
            str_contains($message, 'tiang listrik')
        ) {
            $fallback[] = 'PLN';
        }

        if (
            str_contains($message, 'bansos') || str_contains($message, 'blt') ||
            str_contains($message, 'terlantar') || str_contains($message, 'kdrt') ||
            str_contains($message, 'orang terlantar')
        ) {
            $fallback[] = 'Dinas Sosial';
        }

        if (
            str_contains($message, 'kebakaran') || str_contains($message, 'banjir') ||
            str_contains($message, 'longsor')
        ) {
            $fallback[] = 'BPBD';
        }

        if (
            str_contains($message, 'ketenagakerjaan') || str_contains($message, 'outsourcing') ||
            str_contains($message, 'phk') || str_contains($message, 'blk')
        ) {
            $fallback[] = 'Dinas Tenaga Kerja dan Transmigrasi';
        }

        if (
            str_contains($message, 'satpol') || str_contains($message, 'ketertiban') ||
            str_contains($message, 'anjing')
        ) {
            $fallback[] = 'Satpol PP';
        }

        return array_values(array_unique($fallback));
    }

    // ──────────────────────────────────────────────────────────────────────
    //  HEURISTIK PRIORITAS (diperkaya dari pola data)
    // ──────────────────────────────────────────────────────────────────────

    private function heuristicPriority(string $message): string
    {
        $message = $this->normalizeText($message);

        $highKeywords = [
            'kebakaran', 'banjir', 'longsor', 'jalan putus', 'pohon tumbang',
            'kecelakaan', 'mati total', 'listrik padam', 'padam total', 'darurat',
            'korban jiwa', 'korban', 'membahayakan', 'bahaya', 'rawan kecelakaan',
            'rawan celaka', 'berbahaya',
            'jembatan rusak', 'jembatan tidak layak', 'goyang tiang', 'ngerendap',
            'kdrt', 'kekerasan', 'orang terlantar', 'terlantar',
            'sudah berbulan', 'sudah bertahun', 'berbulan bulan', 'bertahun tahun',
            'tidak ada tindak lanjut', 'tidak ada tindakan', 'tidak ada perubahan',
            'tidak di gubris', 'mau demo',
            'terendam', 'banjir tidak surut', 'sampai lutut',
        ];

        foreach ($highKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return 'Tinggi';
            }
        }

        $mediumKeywords = [
            'jalan berlubang', 'jalan rusak', 'jalan lubang',
            'lampu jalan mati', 'lampu mati', 'lampu pju', 'penerangan', 'gelap',
            'pdam', 'air tidak jalan', 'air tidak mengalir',
            'ngk jalan', 'dak ngalir', 'dak pakai jalan', 'air dak jalan',
            'sampah', 'drainase', 'parit sumbat', 'parit tersumbat', 'sumbat',
            'jembatan', 'gorong',
            'taman rusak', 'fasilitas rusak', 'pohon miring', 'pohon mati',
            'kabel jatuh', 'kabel semrawut',
            'pelayanan', 'gangguan', 'air bersih', 'irigasi',
        ];

        foreach ($mediumKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return 'Sedang';
            }
        }

        return 'Rendah';
    }

    // ──────────────────────────────────────────────────────────────────────
    //  UTILITY
    // ──────────────────────────────────────────────────────────────────────

    private function bestMatch(string $needle, array $haystack, int $threshold = 80): ?string
    {
        $needleNorm = $this->normalizeText($needle);

        $bestItem = null;
        $bestScore = 0.0;

        foreach ($haystack as $item) {
            $itemNorm = $this->normalizeText((string) $item);

            if ($needleNorm === $itemNorm) {
                return $item;
            }

            similar_text($needleNorm, $itemNorm, $percent);

            if ($percent > $bestScore) {
                $bestScore = $percent;
                $bestItem = $item;
            }
        }

        return $bestScore >= $threshold ? $bestItem : null;
    }

    private function normalizeText(string $value): string
    {
        $value = mb_strtolower($value);
        $value = preg_replace('/\s+/u', ' ', $value);
        $value = trim($value);

        return $value;
    }

    private function sanitizeResultReasoning(string $category, string $subCategory, string $priority): string
    {
        return "Aduan diklasifikasikan ke kategori {$category} dengan subkategori {$subCategory} dan prioritas {$priority}.";
    }

    private function fallbackReasoning(string $category, string $subCategory, string $priority): string
    {
        return $this->sanitizeResultReasoning($category, $subCategory, $priority);
    }

    private function fallbackResult(string $priority, string $reasoning): array
    {
        return [
            'suggested_category' => 'Lain-lain / Umum',
            'suggested_sub_category' => 'Aduan Masyarakat',
            'suggested_opds' => [],
            'priority' => $priority,
            'confidence' => 0,
            'reasoning' => $reasoning,
        ];
    }

    private function fallbackResultFromMessage(string $message, string $reasoning): array
    {
        $subCategories = SubCategory::pluck('name')->values()->all();
        $categories = Category::pluck('name')->values()->all();

        $subCategory = $this->inferSubCategoryFromMessage($message, $subCategories)
            ?? 'Aduan Masyarakat';

        $opds = Opd::pluck('name')->values()->all();
        $resolved = $this->resolveFromSubCategory($subCategory, $categories, $opds);

        // Buat reasoning yang informatif berdasarkan sub-kategori yang terdeteksi
        $autoReasoning = $subCategory !== 'Aduan Masyarakat'
            ? "Diklasifikasikan sebagai '{$subCategory}' berdasarkan analisis kata kunci pesan."
            : $reasoning;

        return [
            'suggested_category'     => $resolved['category'],
            'suggested_sub_category' => $subCategory,
            'suggested_opds'         => !empty($resolved['opds'])
                ? array_slice($resolved['opds'], 0, 1)
                : array_slice($this->fallbackOpdsFromMessage($message), 0, 1),
            'priority'               => $this->heuristicPriority($message),
            'confidence'             => 0,
            'reasoning'              => $autoReasoning,
        ];
    }

    // ──────────────────────────────────────────────────────────────────────
    //  SPAM FILTER
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Deteksi apakah komentar adalah spam / tidak jelas / tidak layak dijadikan tiket.
     * Tahap 1: Filter heuristik lokal (cepat, tanpa panggil API).
     * Tahap 2: Filter AI (panggil API, lebih akurat).
     *
     * Mengembalikan ['is_spam' => bool, 'reason' => string]
     */
    public function isSpam(string $message): array
    {
        $raw = $message;
        $normalized = $this->normalizeText($message);

        // ── TAHAP 1: Filter heuristik lokal ─────────────────────────────────

        // 1a. Pesan kosong
        if ($normalized === '') {
            return ['is_spam' => true, 'reason' => 'Pesan kosong.'];
        }

        // 1b. Terlalu pendek (< 10 karakter bersih — hanya emoji/tanda baca/angka)
        $cleanChars = preg_replace('/[^a-zA-Z0-9\x{00C0}-\x{024F}\x{1E00}-\x{1EFF}]/u', '', $normalized);
        if (mb_strlen($cleanChars) < 8) {
            return ['is_spam' => true, 'reason' => 'Pesan terlalu pendek atau hanya berisi emoji/simbol.'];
        }

        // 1c. Hanya berisi emoji (tidak ada huruf Latin maupun huruf apa pun)
        if (preg_match('/^[\x{1F000}-\x{1FFFF}\x{2600}-\x{27BF}\s]+$/u', $raw)) {
            return ['is_spam' => true, 'reason' => 'Pesan hanya berisi emoji.'];
        }

        // 1d. Kata berulang spam klasik
        $spamPatterns = [
            '/\b(amin+|aminn+|aamiin+|wkwk+|haha+|hihi+|hehe+|xd+|lol+|mantap+|mantul+|okeh+|oke+|ok+|siip+|sip+|jos+|keren+)\b/iu',
        ];
        $wordCount = str_word_count($normalized);
        foreach ($spamPatterns as $pattern) {
            if (preg_match($pattern, $normalized) && $wordCount <= 5) {
                return ['is_spam' => true, 'reason' => 'Komentar terdeteksi sebagai reaksi/ekspresi tanpa isi aduan.'];
            }
        }

        // 1e. Hanya angka / simbol
        if (preg_match('/^[\d\s\W]+$/u', $normalized)) {
            return ['is_spam' => true, 'reason' => 'Pesan hanya berisi angka atau simbol.'];
        }

        // 1f. Kata-kata yang jelas bukan aduan publik
        $nonComplaintKeywords = [
            'selamat ulang tahun', 'happy birthday', 'hbd', 'met ultah',
            'happy new year', 'selamat tahun baru', 'merry christmas',
            'selamat lebaran', 'selamat natal', 'selamat idul fitri',
            'follback', 'follow back', 'f4f', 'like4like',
            'giveaway', 'promo', 'diskon', 'jual', 'beli', 'stok',
            'bisnis', 'investasi bodong', 'klik link', 'wa aja',
        ];
        foreach ($nonComplaintKeywords as $keyword) {
            if (str_contains($normalized, $keyword)) {
                return ['is_spam' => true, 'reason' => "Komentar terdeteksi sebagai ucapan/promosi: \"{$keyword}\"."];
            }
        }

        // 1g. Strip prefix halaman (Simadu KMC / @Simadu KMC) untuk evaluasi isi sebenarnya
        $stripped = preg_replace('/^@?simadu\s*kmc\s*/iu', '', $normalized);
        $stripped = trim($stripped);

        // Jika setelah prefix dihilangkan isinya sangat pendek (< 5 karakter)
        if (mb_strlen($stripped) < 5) {
            return ['is_spam' => true, 'reason' => 'Pesan hanya berisi tag mention tanpa isi aduan.'];
        }

        // 1h. Pola komentar tes / tanpa konteks yang jelas
        $testPatterns = [
            '/^tes\s*$/iu',
            '/^test\s*$/iu',
            '/^tes\s+(komen|komentar|posting|post|aja|doang|dulu|coba|123)/iu',
            '/^test\s+(comment|posting|post|only|just|123)/iu',
            '/^coba\s+(komen|komentar|posting|post|tes|test|aja)/iu',
            '/^komen\s*\d*[.:]\d*\s*$/iu',
            '/^komentar\s*\d*[.:]\d*\s*$/iu',
            '/^comment\s*\d*[.:]\d*\s*$/iu',
            '/^\w+\s+\d{1,2}[.:]\d{2}\s*$/iu',
            '/^(halo+|hai+|hi+|hello+|hey+)\s*$/iu',
            '/^(halo+|hai+|hi+)\s+(min|admin|kak|bang)\s*$/iu',
            '/^(min|admin|kak|bang)\s*$/iu',
        ];
        foreach ($testPatterns as $pattern) {
            if (preg_match($pattern, $stripped)) {
                return ['is_spam' => true, 'reason' => 'Komentar tidak mengandung konteks aduan yang jelas.'];
            }
        }

        // ── TAHAP 2: Filter AI ───────────────────────────────────────────────
        try {
            $prompt = <<<PROMPT
Anda adalah sistem filter spam SIMODU KMC — Sistem Informasi Monitoring Aduan Ketapang Media Center, Kabupaten Ketapang, Kalimantan Barat.
Sistem ini memproses komentar dari media sosial (Instagram/Facebook) dan harus memfilter komentar yang BUKAN aduan masyarakat yang layak diproses.

═══ KONTEKS ═══
Komentar yang masuk berasal dari postingan akun resmi KMC Ketapang di media sosial.
Masyarakat menyampaikan keluhan/aduan lewat komentar, namun banyak juga komentar yang hanya berisi reaksi, ucapan, atau spam.

═══ KOSAKATA DIALEK MELAYU KETAPANG (WAJIB DIPAHAMI) ═══
Masyarakat Ketapang sering menggunakan bahasa daerah. Komentar dalam dialek lokal yang berisi aduan nyata HARUS dianggap VALID:
- "dak/ndak/sik/sik ada" = tidak/tidak ada → "dak ngalir" = tidak mengalir
- "aek/aik/aiq" = air → "aik dak jalan" = air tidak mengalir (aduan PDAM)
- "parit" = drainase/selokan → "parit sumbat" = drainase tersumbat
- "pokok" = pohon → "pokok ngadang jalan" = pohon menghalangi jalan
- "ngadang" = menghalangi → masalah infrastruktur
- "bederai/ancur/bapok" = rusak parah → "jalan bederai" = jalan rusak parah
- "betabur" = berserakan → "sampah betabur" = sampah berserakan
- "ade" = ada, "saye/kamek" = saya/kami, "dri" = dari, "biak" = anak-anak
- "minta2" = meminta-minta, "gaduh gelisah" = agresif (ODGJ)
- "min/mimin" = sapaan admin (BUKAN spam, lanjut baca isi pesan)

═══ ATURAN DETEKSI SPAM ═══

ADUAN VALID (is_spam = false) — layak dijadikan tiket:
✅ Berisi keluhan/masalah nyata tentang layanan publik (air, jalan, listrik, sampah, dll)
✅ Laporan kejadian (kebakaran, banjir, pohon tumbang, ODGJ, KDRT, dll)
✅ Permintaan bantuan spesifik (bansos, bedah rumah, perizinan, dll)
✅ Pertanyaan tentang layanan pemerintah (cara urus KTP, info pajak, dll)
✅ Komentar dalam dialek Ketapang yang setelah diterjemahkan berisi aduan nyata
✅ Pesan yang menyebut lokasi + masalah (meski singkat)
✅ Tag @simodu kmc diikuti isi aduan → VALID (abaikan tag, fokus pada isi)

BUKAN ADUAN / SPAM (is_spam = true) — tidak layak diproses:
❌ Reaksi singkat tanpa isi: "amin", "mantap", "ok", "keren", "sip", "wkwk", "haha"
❌ Hanya emoji tanpa teks bermakna
❌ Ucapan selamat: "selamat ulang tahun", "happy birthday", "selamat lebaran"
❌ Promosi/iklan: jualan, link produk, investasi, follow back
❌ Komentar tes: "tes", "test", "halo min", "coba posting"
❌ Kalimat sangat umum tanpa detail masalah: "kapan ya", "semoga cepat", "setuju"
❌ Dukungan/pujian tanpa aduan: "semangat kerjanya", "lanjutkan program"
❌ Komentar tidak bermakna: angka/simbol acak, huruf berulang

PRINSIP UTAMA:
- Jika RAGU antara spam atau valid → anggap VALID (is_spam = false). Lebih baik meloloskan komentar yang meragukan daripada memblokir aduan masyarakat yang nyata.
- Komentar pendek tapi berisi masalah spesifik tetap VALID (contoh: "lampu jalan mati" → VALID).
- Bahasa tidak baku / typo berat tetap VALID selama ada inti aduan.

═══ KOMENTAR YANG HARUS DINILAI ═══
"{$message}"

KELUARKAN HANYA JSON (tanpa markdown, tanpa teks lain):
{"is_spam": true/false, "reason": "alasan spesifik maksimal 1 kalimat"}
PROMPT;

            $response = $this->callGemini($prompt);
            $parsed = $this->parseResult($response);

            if (isset($parsed['is_spam'])) {
                return [
                    'is_spam' => (bool) $parsed['is_spam'],
                    'reason'  => $parsed['reason'] ?? 'Dinilai oleh AI.',
                ];
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Spam filter AI error: ' . $e->getMessage());
        }

        return ['is_spam' => false, 'reason' => ''];
    }

    /**
     * Deteksi duplikasi aduan menggunakan AI.
     * Membandingkan pesan baru dengan aduan-aduan yang sudah ada dalam 30 hari terakhir.
     *
     * @param  string   $newMessage   Pesan aduan baru
     * @param  int|null $excludeId    ID notifikasi yang dikecualikan dari pengecekan
     * @return array|null  ['notification_id' => int, 'similarity' => float, 'original_message' => string] atau null jika tidak ada duplikat
     */
    public function checkDuplicate(string $newMessage, ?int $excludeId = null): ?array
    {
        try {
            // Ambil aduan 30 hari terakhir yang sudah punya tiket (sudah diproses)
            $recentNotifications = \App\Models\Notification::where('created_at', '>=', now()->subDays(30))
                ->whereHas('ticket')
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->whereNull('duplicate_status')
                ->latest()
                ->take(20) // Batasi 20 aduan terakhir agar prompt tidak terlalu panjang
                ->get(['id', 'message', 'comment_message', 'sender']);

            if ($recentNotifications->isEmpty()) {
                return null;
            }

            // Bangun daftar aduan untuk dibandingkan
            $existingList = '';
            foreach ($recentNotifications as $index => $notif) {
                $msg = $notif->comment_message ?? $notif->message ?? '';
                $cleanMsg = preg_replace('/@?simadu\s*kmc/iu', '', $msg);
                $cleanMsg = trim($cleanMsg, " \t\n\r\0\x0B,.:;");
                if (mb_strlen($cleanMsg) < 10) continue;

                $existingList .= "[ID:{$notif->id}] \"{$cleanMsg}\"\n";
            }

            if (empty(trim($existingList))) {
                return null;
            }

            // Bersihkan pesan baru
            $cleanNew = preg_replace('/@?simadu\s*kmc/iu', '', $newMessage);
            $cleanNew = trim($cleanNew, " \t\n\r\0\x0B,.:;");

            $prompt = <<<PROMPT
Anda adalah sistem deteksi duplikasi aduan SIMODU KMC — Sistem Informasi Monitoring Aduan Ketapang Media Center, Kabupaten Ketapang, Kalimantan Barat.
Tugas Anda adalah menentukan apakah aduan BARU merupakan DUPLIKAT dari aduan yang sudah ada (sudah memiliki tiket yang sedang diproses).

═══ TUJUAN ═══
Mencegah pembuatan tiket ganda untuk aduan yang sama persis, agar OPD tidak menangani masalah yang identik dua kali.
Sistem ini HARUS sangat ketat — hanya menandai duplikat jika benar-benar yakin 100%.

═══ KOSAKATA DIALEK KETAPANG (referensi terjemahan) ═══
Masyarakat Ketapang menggunakan dialek Melayu lokal. Pahami padanan berikut saat membandingkan:
- "aik/aiq/aek" = air → konteks PDAM/air bersih
- "dak/ndak/dek" = tidak/belum → "dak ngalir" = tidak mengalir
- "jalan" (konteks air) = mengalir → "air dak jalan" = air tidak mengalir
- "galap/gelap" = mati lampu / gelap gulita
- "PJU" = Penerangan Jalan Umum (lampu jalan)
- "ODGJ" = Orang Dengan Gangguan Jiwa
- "parit" = drainase/selokan
- "pokok" = pohon → "pokok tumbang" = pohon tumbang
- "jl/jln" = jalan (nama jalan), "gg/gang" = gang/lorong
- "rt/rw" = RT/RW (wilayah administratif terkecil)
- "kel/kelurahan" = kelurahan, "ds/desa" = desa, "kec" = kecamatan

═══ ADUAN BARU YANG AKAN DICEK ═══
"{$cleanNew}"

═══ DAFTAR ADUAN YANG SUDAH ADA (sudah punya tiket aktif) ═══
{$existingList}

═══ INSTRUKSI ANALISIS (LANGKAH DEMI LANGKAH) ═══

LANGKAH 1 — EKSTRAK INFORMASI DARI ADUAN BARU:
- Masalah utama: [apa yang dikeluhkan? air mati? jalan rusak? lampu padam?]
- Lokasi spesifik: [nama jalan, nomor RT/RW, kelurahan, desa, kecamatan, atau landmark tertentu — jika disebutkan]
- Objek terdampak: [lampu, pipa, jembatan, pohon, dll — jika disebutkan]
- Identitas pelapor: [nama, nomor pelanggan — jika disebutkan]

LANGKAH 2 — BANDINGKAN SATU PER SATU DENGAN ADUAN YANG SUDAH ADA:
Untuk setiap aduan [ID:X], periksa TIGA dimensi:
a) MASALAH UTAMA — Apakah SAMA atau SANGAT MIRIP? (bukan hanya kategori yang sama!)
b) LOKASI SPESIFIK — Apakah SAMA PERSIS atau jelas merujuk ke tempat yang sama?
c) OBJEK TERDAMPAK — Apakah sama? (jika disebutkan di kedua aduan)

LANGKAH 3 — PUTUSKAN:
Aduan dinyatakan DUPLIKAT hanya jika SEMUA kondisi ini terpenuhi:
✅ Masalah utama IDENTIK atau SANGAT MIRIP (bukan sekadar topik serupa)
✅ Lokasi SAMA PERSIS atau jelas merujuk ke tempat yang sama
✅ Objek yang terdampak sama (jika disebutkan di kedua aduan)

═══ CONTOH BUKAN DUPLIKAT (wajib ditolak) ═══
❌ "air PDAM mati di Jl. DI Panjaitan" ≠ "air PDAM mati di Gg. Mawar" → BEDA LOKASI
❌ "lampu jalan mati di Delta Pawan" ≠ "lampu jalan mati di Mulia Baru" → BEDA LOKASI
❌ "jalan rusak di RT 05 Kel. Sampit" ≠ "jalan rusak di RT 12 Kel. Sampit" → BEDA RT
❌ "air PDAM tidak mengalir" ≠ "pipa PDAM bocor" → BEDA MASALAH (meski sama-sama PDAM)
❌ Aduan baru TANPA lokasi ≠ aduan lama TANPA lokasi → TIDAK BISA diverifikasi = BUKAN DUPLIKAT
❌ Lokasi hanya sebutan umum ("di ketapang", "di kota") tanpa nama jalan/kelurahan → BUKAN DUPLIKAT

═══ CONTOH DUPLIKAT SEJATI (yang boleh ditandai) ═══
✅ "aik pdam dak jalan di jl sutan syahrir" = "air PDAM mati 2 hari jl sultan syahrir" → SAMA masalah + SAMA lokasi
✅ "lampu PJU padam di depan kantor bupati" = "lampu jalan mati depan kantor bupati" → SAMA masalah + SAMA lokasi
✅ "pohon tumbang halangi jalan di jl a yani km3" = "ada pohon ngadang jalan jl ahmad yani km 3" → SAMA masalah + SAMA lokasi (bahasa berbeda tapi lokasi identik)

═══ PRINSIP UTAMA ═══
- Lebih baik SALAH TIDAK MENDETEKSI daripada SALAH MENDETEKSI duplikat
- Jika tidak yakin 100% → is_duplicate = false
- Kategori/topik yang sama BUKAN berarti duplikat (5 orang bisa melaporkan jalan rusak di 5 lokasi berbeda)

KELUARKAN HANYA JSON (tanpa penjelasan, tanpa markdown, tanpa kode blok):
{"is_duplicate": true/false, "matched_id": ID_ATAU_null, "similarity": ANGKA_0_SAMPAI_100, "reason": "alasan spesifik maks 15 kata"}
PROMPT;

            $response = $this->callGemini($prompt);
            $parsed = $this->parseResult($response);

            if (isset($parsed['is_duplicate']) && $parsed['is_duplicate'] === true && !empty($parsed['matched_id'])) {
                $matchedNotif = \App\Models\Notification::find($parsed['matched_id']);

                if ($matchedNotif) {
                    $originalMsg = $matchedNotif->comment_message ?? $matchedNotif->message ?? '';

                    return [
                        'notification_id' => (int) $parsed['matched_id'],
                        'similarity'      => (float) ($parsed['similarity'] ?? 80),
                        'reason'          => $parsed['reason'] ?? 'Terdeteksi mirip oleh AI',
                        'original_message' => $originalMsg,
                    ];
                }
            }

            return null;

        } catch (\Exception $e) {
            Log::warning('Duplicate detection AI error: ' . $e->getMessage());
            return null;
        }
    }
}
