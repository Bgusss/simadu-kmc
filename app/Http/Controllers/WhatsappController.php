<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Ticket;
use App\Models\Opd;
use App\Models\TicketStatusLog;
use App\Services\WhatsAppKeywordClassificationService;
use App\Services\FonnteService;
use App\Services\TicketingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WhatsappController extends Controller
{
    /**
     * Daftar sapaan yang memicu menu utama.
     * Diambil sama persis dari mharisfr/kmc + tambahan dialek Ketapang.
     */
    private array $sapaan = [
        // Salam umum
        'halo', 'hai', 'hello', 'hi', 'hey', 'hei', 'hallo', 'haloo', 'halooo',
        'haii', 'haiii', 'helo', 'heloo', 'helloo',

        // Salam Islam
        'assalamualaikum', "assalamu'alaikum", 'assalamualaikumwr', 'assalamualaikumwrwb',
        'assalamu alaikum', "assalamu'alaikum warahmatullahi wabarakatuh",
        'waalaikumsalam', "wa'alaikumsalam", 'waalaikum salam',
        'salam', 'salamualaikum',

        // Sapaan waktu — lengkap semua variasi
        'selamat pagi', 'selamat siang', 'selamat sore', 'selamat malam',
        'selamat pagi min', 'selamat siang min', 'selamat sore min', 'selamat malam min',
        'selamat pagi kak', 'selamat siang kak', 'selamat sore kak', 'selamat malam kak',
        'selamat pagi admin', 'selamat siang admin', 'selamat sore admin', 'selamat malam admin',
        'selamat pagi kmc', 'selamat siang kmc', 'selamat sore kmc', 'selamat malam kmc',
        'pagi', 'siang', 'sore', 'malam',
        'pagi min', 'siang min', 'sore min', 'malam min',
        'pagi kak', 'siang kak', 'sore kak', 'malam kak',
        'pagi admin', 'siang admin', 'sore admin', 'malam admin',
        'pagi kmc', 'siang kmc', 'sore kmc', 'malam kmc',
        'met pagi', 'met siang', 'met sore', 'met malam',
        'met pagi min', 'met siang min', 'met sore min', 'met malam min',
        'met pagi kak', 'met siang kak', 'met sore kak', 'met malam kak',
        'slmt pagi', 'slmt siang', 'slmt sore', 'slmt malam',
        'slmt pagi min', 'slmt siang min', 'slmt sore min', 'slmt malam min',
        'semat pagi', 'semat siang', 'semat sore', 'semat malam',
        'semat pagi min', 'semat siang min', 'semat sore min', 'semat malam min',
        'slamat pagi', 'slamat siang', 'slamat sore', 'slamat malam',
        'slamat pagi min', 'slamat siang min', 'slamat sore min', 'slamat malam min',
        'slmat pagi', 'slmat siang', 'slmat sore', 'slmat malam',
        'selamat pg', 'selamat sg', 'selamat mlm',
        'good morning', 'good afternoon', 'good evening', 'good night',

        // Navigasi menu
        'menu', 'main menu', 'menu utama', 'kembali', 'kembali ke menu',
        'back', 'home', 'mulai', 'start', 'p', 'help', 'bantuan',

        // Pembuka percakapan
        'permisi', 'permisi kak', 'permisi min', 'misi', 'misi kak',
        'selamat datang', 'hola', 'yo', 'yoo', 'yooo',
        'ada', 'ada?', 'ada gak', 'ada tidak', 'aktif', 'aktif?',
        'ping', 'test', 'tes', 'coba', 'hitung',

        // Sapaan ke admin/bot
        'kmc', 'halo kmc', 'hai kmc', 'hello kmc', 'hei kmc',
        'min', 'halo min', 'hai min', 'hei min', 'hello min',
        'admin', 'halo admin', 'hai admin', 'cs', 'halo cs',
        'bot', 'halo bot', 'hai bot',

        // Typo umum
        'hlo', 'hloo', 'hy', 'hye', 'hla', 'aslm', 'askum', 'ass',
        'assamu alaikum', 'asalamualaikum', 'asalamu alaikum',

        // Sapaan tambahan umum
        'woy', 'woi', 'oi', 'oy', 'kak', 'ka', 'bang', 'bg', 'pak', 'bu',
        'mimin', 'halo mimin', 'hai mimin', 'min kmc', 'kak kmc',
        'assalamualaikum kak', 'assalamualaikum min', 'assalamualaikum admin',
        'apa kabar', 'gan', 'sis', 'bro', 'ces',
        'permisi admin', 'permisi ya', 'numpang tanya', 'mau tanya',
        'mau tanya dong', 'boleh tanya', 'boleh tanya ga', 'boleh tanya gak',
        'excuse me', 'anyone', 'anyone there', 'is anyone there',
        'ada orang', 'ada orang gak', 'ada orang ga', 'ada admin',
        'ada admin gak', 'ada admin ga', 'ada yang jaga',
        'halo halo', 'hai hai', 'pp', 'test bot', 'tes bot',
        'cek bot', 'bot aktif', 'bot ada', 'sistem', 'sistem aktif',

        // Bahasa Melayu Ketapang / Kalimantan Barat
        'amacam', 'amacam kabar', 'amacam kabo', 'ape kabar', 'ape kaba',
        'apo kaba kak', 'ape kaba min', 'macam mane', 'macam mano',
        'salam kenal', 'oi', 'oi kak', 'oi min', 'oi kome',
        'kome', 'kamek', 'kamek nak tanya', 'nak tanya', 'nak tanye',
        'kamek nak lapor', 'nak lapor', 'kamek galak lapor', 'galak lapor',
        'ngape', 'ngape ni', 'ade orang idak', 'ade orang dak',
        'ade admin idak', 'ade admin dak', 'ade ke idak', 'ade ke dak',
        'assalamualaikum kami nak tanya', 'salam kamek', 'hai kamek',
        'malam min ', 'pagi min amacam', 'siang min amacam',
        'sore min ', 'dak ade orang ke', 'idak ade orang ke',
        'halo kome', 'hai kome min', 'permisi kami nak tanya',
    ];

    /**
     * Daftar kata kunci bertanya soal status laporan (bahasa natural).
     * Sama persis dari mharisfr/kmc.
     */
    private array $tanyaLaporan = [
        'laporan saya gimana', 'gimana laporan saya', 'laporan saya bagaimana',
        'bagaimana laporan saya', 'laporan saya sudah diproses belum',
        'laporan saya udah diproses belum', 'sudah diproses belum',
        'udah diproses belum', 'laporan saya sampe mana',
        'laporan saya sampai mana', 'sampe mana laporan saya',
        'sampai mana laporan saya', 'progress laporan saya',
        'progres laporan saya', 'perkembangan laporan saya',
        'perkembangan laporan', 'update laporan saya', 'update laporan',
        'status laporan saya', 'status laporan', 'cek laporan saya',
        'cek laporan', 'cek status laporan', 'mau cek laporan',
        'mau cek status', 'gimana status laporan', 'kapan laporan saya selesai',
        'laporan saya kapan selesai', 'laporan saya belum ditanggapi',
        'laporan saya belum direspon', 'laporan saya belum ada tanggapan',
        'kok laporan saya belum diproses', 'kenapa laporan saya belum diproses',
        'laporan saya udah sampai mana', 'tiket saya gimana',
        'nomor tiket saya gimana', 'laporan saya ditindaklanjuti gak',
        'laporan saya ditindaklanjuti tidak', 'sudah ditanggapi belum',
        'sudah ada jawaban belum', 'ada jawaban belum', 'ada balasan belum',

        // Bahasa Melayu Ketapang / Kalimantan Barat
        'macam mane laporan kami', 'laporan kami macam mane',
        'macam mano laporan kamek', 'laporan kamek amacam',
        'amacam laporan kamek', 'laporan kamek dah diproses ke belum',
        'laporan kamek dah diproses ke idak', 'dah diproses ke belum',
        'dah diproses ke idak', 'laporan kamek sampai mane',
        'laporan kamek sampai mano', 'sampai mane laporan kamek',
        'ngape laporan kamek belum diproses', 'ngape laporan kamek idak diproses',
        'ngape laporan kamek belum ditanggapi', 'laporan kamek ade jawaban ke idak',
        'ade jawaban ke idak', 'ade jawaban ke belum', 'ade balasan ke idak',
        'ade balasan ke belum', 'gik diproses ke idak', 'gik diproses ke belum',
        'laporan kamek gik diproses ke idak', 'tiket kamek macam mane',
        'nomor tiket kamek macam mane', 'kamek nak cek laporan',
        'kamek nak cek status', 'nak cek laporan', 'nak cek status laporan',

        // Variasi ejaan 'saye' & 'belom' (umum di Ketapang)
        'macam mane laporan saye', 'macam mano laporan saye',
        'laporan saye macam mane', 'laporan saye macam mano',
        'laporan saye gimane', 'gimane laporan saye',
        'laporan saye bagaimane', 'bagaimane laporan saye',
        'sudah diproses atau belom', 'udah diproses atau belom',
        'sudah diproses apa belom', 'udah diproses apa belom',
        'dah diproses atau belom', 'dah diproses apa belom',
        'sudah diproses atau belum', 'sudah diproses apa belum',
        'laporan saye sudah diproses atau belom',
        'laporan saye udah diproses atau belom',
        'laporan saye dah diproses atau belom',
        'laporan saye sudah diproses apa belom',
        'laporan saye sampe mane', 'laporan saye sampai mane',
        'sampe mane laporan saye', 'sampai mane laporan saye',
        'laporan saye kapan selesai', 'kapan laporan saye selesai',
        'laporan saye belom ditanggapi', 'laporan saye belom direspon',
        'laporan saye belom ade jawaban', 'laporan saye belom ade balasan',
        'status laporan saye', 'progress laporan saye', 'update laporan saye',
        'tiket saye macam mane', 'nomor tiket saye macam mane',
        'saye nak cek laporan', 'saye nak cek status',
    ];

    /**
     * Webhook endpoint — menerima pesan masuk dari Fonnte.
     */
    public function webhook(Request $request)
    {
        try {
            Log::info('WhatsApp Webhook Masuk', $request->all());

            // Ambil nomor pengirim (Fonnte mengirim field 'sender')
            $number = $request->sender
                ?? $request->number
                ?? $request->from
                ?? null;

            $message = $request->message
                ?? $request->text
                ?? $request->body
                ?? '';

            if (empty($number)) {
                Log::error('WhatsApp Webhook: Nomor pengirim tidak ditemukan');
                return response()->json(['success' => false, 'message' => 'Nomor tidak ditemukan']);
            }

            // Bersihkan nomor
            $number  = preg_replace('/@.+$/', '', $number);
            $message = strtolower(trim($message));

            Log::info('WhatsApp Data Pesan', ['number' => $number, 'message' => $message]);

            /*
            |------------------------------------------------------------------
            | CEK STATUS TIKET — format: CEK#KMC-XXXXXXXX-XXXX
            |------------------------------------------------------------------
            */
            if (preg_match('/^cek#(.+)$/i', $message, $matches)) {
                return $this->handleCekStatus($number, trim($matches[1]));
            }

            /*
            |------------------------------------------------------------------
            | LAPOR — format dimulai dengan "LAPOR#"
            | Contoh: LAPOR#Nama#Isi laporan
            |------------------------------------------------------------------
            */
            if (preg_match('/^lapor#(.+)$/i', $message, $matches)) {
                return $this->handleLapor($number, trim($matches[1]));
            }

            /*
            |------------------------------------------------------------------
            | PERTANYAAN SEPUTAR PERKEMBANGAN LAPORAN
            | (tidak pakai format CEK#, tapi bermaksud sama)
            |------------------------------------------------------------------
            */
            foreach ($this->tanyaLaporan as $kw) {
                if (str_contains($message, $kw)) {
                    return $this->handleTanyaLaporan($number);
                }
            }

            /*
            |------------------------------------------------------------------
            | MENU UTAMA — sapaan / halo / menu
            |------------------------------------------------------------------
            */
            if (in_array($message, $this->sapaan) || $message === '0') {
                return $this->handleMenuUtama($number);
            }

            /*
            |------------------------------------------------------------------
            | PILIHAN MENU ANGKA
            |------------------------------------------------------------------
            */
            if ($message === '1') {
                return $this->handleMenuPengaduan($number);
            }

            if ($message === '2') {
                return $this->handleMenuDarurat($number);
            }

            if ($message === '3') {
                return $this->handleMenuCekStatus($number);
            }

            /*
            |------------------------------------------------------------------
            | PESAN TIDAK DIKENALI
            |------------------------------------------------------------------
            */
            return $this->handleTidakDikenali($number);

        } catch (\Exception $e) {
            Log::error('WhatsApp Webhook Error', [
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return response()->json(['success' => false, 'message' => 'Internal error']);
        }
    }

    /**
     * Menu Utama — sama persis dengan mharisfr/kmc
     */
    private function handleMenuUtama(string $number)
    {
        $pesan =
            "🏛 *Selamat Datang di Ketapang Media Center (KMC)*\n\n" .

            "Halo! Kami hadir untuk membantu Anda menyampaikan aspirasi dan pengaduan kepada Pemerintah Kabupaten Ketapang.\n\n" .

            "📋 *Pilih Layanan:*\n\n" .

            "1️⃣ *Pengaduan Masyarakat*\n" .
            "   Sampaikan keluhan atau laporan pelayanan publik\n\n" .

            "2️⃣ *Layanan Darurat 112*\n" .
            "   Kebakaran, kecelakaan, kriminal & darurat lainnya\n\n" .

            "3️⃣ *Cek Status Laporan*\n" .
            "   Pantau perkembangan laporan yang telah dikirim\n\n" .

            "━━━━━━━━━━━━━━━━━━━━\n" .
            "Balas dengan angka *1*, *2*, atau *3*.\n\n" .

            "_Ketik *menu* kapan saja untuk kembali ke halaman ini._\n\n" .

            "*Ketapang Media Center (KMC)*\n" .
            "_Melayani dengan Hati_ 🤝";

        FonnteService::send($number, $pesan);

        return response()->json(['success' => true, 'action' => 'menu_utama']);
    }

    /**
     * Menu 1 — Pengaduan Masyarakat (sama persis mharisfr/kmc)
     */
    private function handleMenuPengaduan(string $number)
    {
        $linkForm = config('app.url') . '/lapor';

        $pesan =
            "📝 *LAYANAN PENGADUAN MASYARAKAT*\n\n" .

            "Kami siap menerima pengaduan, aspirasi, dan laporan Anda terkait pelayanan publik di Kabupaten Ketapang.\n\n" .

            "🔌 *Cara Membuat Laporan:*\n" .
            "1. Klik link formulir di bawah ini\n" .
            "2. Isi nama lengkap dan nomor HP aktif\n" .
            "3. Pilih kategori laporan yang sesuai\n" .
            "4. Tulis detail permasalahan secara jelas\n" .
            "5. Lampirkan foto pendukung (jika ada)\n" .
            "6. Klik *Kirim Laporan*\n\n" .

            "🔗 *Formulir Laporan:*\n" .
            "{$linkForm}\n\n" .

            "📋 Setelah laporan dikirim, Anda akan menerima *nomor tiket* sebagai bukti laporan.\n" .
            "Simpan nomor tiket tersebut untuk memantau status laporan Anda.\n\n" .

            "Atau langsung lapor via WA dengan format:\n" .
            "LAPOR#Nama Lengkap#Isi laporan\n\n" .

            "📌 *Contoh:*\n" .
            "LAPOR#Budi Santoso#Jalan berlubang di Jl. Merdeka depan kantor pos\n\n" .

            "Balas *3* untuk mengetahui cara cek status laporan.\n\n" .

            "🙏 Terima kasih telah berpartisipasi dalam meningkatkan pelayanan publik.\n\n" .

            "*Ketapang Media Center (KMC)*";

        FonnteService::send($number, $pesan);

        return response()->json(['success' => true, 'action' => 'menu_pengaduan']);
    }

    /**
     * Menu 2 — Layanan Darurat 112 (sama persis mharisfr/kmc)
     */
    private function handleMenuDarurat(string $number)
    {
        $pesan =
            "🚨 *LAYANAN DARURAT 112*\n\n" .

            "Layanan ini digunakan untuk kondisi darurat yang memerlukan penanganan *segera dan cepat*.\n\n" .

            "🔴 *Kondisi yang dapat dilaporkan:*\n" .
            "🔥 Kebakaran\n" .
            "🚑 Membutuhkan Ambulans\n" .
            "🚔 Tindak Kriminal / Kemalingan\n" .
            "🚨 Kecelakaan Lalu Lintas\n" .
            "🌊 Bencana Alam (banjir, tanah longsor)\n" .
            "⚡ Kondisi Darurat Lainnya\n\n" .

            "📞 *Hubungi Segera:*\n" .
            "┌─────────────────┐\n" .
            "│   📲  *112*       │\n" .
            "└─────────────────┘\n\n" .

            "✅ Layanan *24 jam* — *Bebas Pulsa*\n\n" .

            "⚠️ *Penting:*\n" .
            "Gunakan nomor 112 *hanya* untuk keadaan darurat.\n" .
            "Penyalahgunaan layanan darurat dapat dikenakan sanksi hukum.\n\n" .

            "Untuk pengaduan non-darurat, balas *1*.\n\n" .

            "*Ketapang Media Center (KMC)*";

        FonnteService::send($number, $pesan);

        return response()->json(['success' => true, 'action' => 'menu_darurat']);
    }

    /**
     * Menu 3 — Panduan Cek Status Laporan (sama persis mharisfr/kmc)
     */
    private function handleMenuCekStatus(string $number)
    {
        $pesan =
            "🔍 *CEK STATUS LAPORAN*\n\n" .

            "Untuk mengetahui perkembangan laporan Anda, kirimkan nomor tiket dengan format:\n\n" .

            "📩 *CEK#NomorTiket*\n\n" .

            "Contoh:\n" .
            "*CEK#KMC-20260805-0001*\n\n" .

            "Pastikan nomor tiket ditulis dengan benar sesuai yang diterima saat laporan dikirim.\n\n" .

            "ketik *menu* untuk kembali ke menu utama.\n\n" .

            "*Ketapang Media Center (KMC)*";

        FonnteService::send($number, $pesan);

        return response()->json(['success' => true, 'action' => 'menu_cek_status']);
    }

    /**
     * Handle pertanyaan natural tentang status laporan
     * (fitur dari mharisfr/kmc — redirect ke panduan cek)
     */
    private function handleTanyaLaporan(string $number)
    {
        $pesan =
            "🔍 *INFO PERKEMBANGAN LAPORAN*\n\n" .

            "Untuk mengecek perkembangan/status laporan Anda, silakan kirim nomor tiket dengan format berikut:\n\n" .

            "📩 *CEK#NomorTiket*\n\n" .

            "Contoh:\n" .
            "*CEK#KMC-20260805-0001*\n\n" .

            "Nomor tiket dapat Anda temukan pada pesan konfirmasi saat pertama kali mengirim laporan.\n\n" .

            "ketik *menu* untuk kembali ke menu utama.\n\n" .

            "*Ketapang Media Center (KMC)*";

        FonnteService::send($number, $pesan);

        return response()->json(['success' => true, 'action' => 'tanya_laporan']);
    }

    /**
     * Handle laporan masuk via WhatsApp.
     * Format: LAPOR#Nama#Isi Laporan
     */
    private function handleLapor(string $number, string $data)
    {
        // Parse: Nama#Isi Laporan
        $parts = explode('#', $data, 2);

        if (count($parts) < 2 || empty(trim($parts[0])) || empty(trim($parts[1]))) {
            $pesan = "⚠️ *Format tidak lengkap!*\n\n" .
                "Format yang benar:\n" .
                "LAPOR#Nama Lengkap#Isi laporan\n\n" .
                "📌 *Contoh:*\n" .
                "LAPOR#Budi Santoso#Jalan berlubang di Jl. Merdeka\n\n" .
                "Balas *0* untuk kembali ke Menu Utama.";

            FonnteService::send($number, $pesan);
            return response()->json(['success' => false, 'action' => 'format_salah']);
        }

        $namaLengkap = trim($parts[0]);
        $isiLaporan  = trim($parts[1]);

        try {
            // 1. Create Notification (agar masuk di feed admin)
            $notification = Notification::create([
                'title'   => 'WhatsApp',
                'sender'  => $namaLengkap,
                'message' => $isiLaporan,
            ]);

            // 2. Generate tracking number
            $today      = now()->format('Ymd');
            $countToday = Ticket::whereDate('created_at', now()->toDateString())->count();
            $sequence   = str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);
            $trackingNumber = "KMC-{$today}-{$sequence}";

            // 3. Klasifikasi keyword khusus laporan WhatsApp (tanpa AI/API)
            $classification = app(WhatsAppKeywordClassificationService::class)->classify($isiLaporan);
            $category = $classification['category'];
            $subCategory = $classification['sub_category'];
            $opdName = $classification['opd'];
            $priority = $classification['priority'];
            $aiConfidence = null;
            $aiReasoning = $classification['reasoning'];

            // Resolve OPD keyword ke data OPD sistem
            $opd = Opd::where('name', $opdName)->first();
            if (!$opd) {
                foreach (Opd::all() as $candidate) {
                    similar_text(mb_strtolower($opdName), mb_strtolower($candidate->name), $percent);
                    if ($percent > 70) {
                        $opd = $candidate;
                        $opdName = $candidate->name;
                        break;
                    }
                }
            }
            $opdId = $opd?->id;

            // 4. Create Ticket
            $ticket = DB::transaction(function () use (
                $notification, $trackingNumber, $namaLengkap, $number,
                $category, $subCategory, $opdName, $opdId, $priority,
                $isiLaporan, $aiConfidence, $aiReasoning
            ) {
                $ticket = Ticket::create([
                    'notification_id'  => $notification->id,
                    'ticket_number'    => $trackingNumber,
                    'tracking_number'  => $trackingNumber,
                    'ticket_time'      => now(),
                    'platform'         => 'WhatsApp',
                    'reporter_name'    => $namaLengkap,
                    'reporter_link'    => "wa.me/{$number}",
                    'category'         => $category,
                    'sub_category'     => $subCategory,
                    'opd_related'      => $opdName,
                    'assigned_opd_id'  => $opdId,
                    'priority'         => $priority,
                    'complaint'        => $isiLaporan,
                    'status'           => 'diterima',
                    'sla_deadline'     => now()->addHours(24),
                    'ai_confidence'    => $aiConfidence,
                    'ai_reasoning'     => $aiReasoning,
                ]);

                TicketStatusLog::create([
                    'ticket_id'   => $ticket->id,
                    'from_status' => null,
                    'to_status'   => 'diterima',
                    'note'        => 'Tiket otomatis dibuat dari laporan WhatsApp',
                ]);

                // Langsung teruskan ke OPD
                if ($opdName) {
                    $ticket->updateStatus(
                        'diteruskan',
                        null,
                        'Diteruskan ke OPD: ' . $opdName
                    );
                }

                return $ticket;
            });

            // 5. Kirim konfirmasi ke pelapor
            $linkCek    = config('app.url') . "/ticketing/{$trackingNumber}";
            $nomorBot   = config('services.fonnte.bot_number', '');
            $linkWaCek  = !empty($nomorBot)
                ? "https://wa.me/{$nomorBot}?text=" . urlencode("CEK#{$trackingNumber}")
                : '';

            $pesan = "✅ *LAPORAN ANDA TELAH DITERIMA*\n\n" .
                "Halo *{$namaLengkap}*, terima kasih telah melapor ke Ketapang Media Center.\n\n" .
                "📋 *Detail Laporan:*\n" .
                "• Nomor Tiket: *{$trackingNumber}*\n" .
                "• Kategori: {$category}\n" .
                "• Status: Diterima\n" .
                "• Prioritas: " . ucfirst($priority) . "\n" .
                ($opdName ? "• OPD Tujuan: {$opdName}\n" : '') .
                "\n" .
                "🔍 *Cek Status Aduan:*\n" .
                "Ketik: CEK#{$trackingNumber}\n" .
                "\n" .
                "🌐 *Atau lacak online:*\n" .
                "{$linkCek}\n" .
                ($linkWaCek ? "\n📱 *Klik untuk cek:*\n{$linkWaCek}\n" : '') .
                "\nLaporan Anda akan segera ditindaklanjuti. Mohon kesabarannya. 🙏\n\n" .
                "*Ketapang Media Center (KMC)*";

            FonnteService::send($number, $pesan);

            // 6. Notifikasi WA ke OPD (jika ada nomor WA OPD)
            $this->notifyOpd($ticket);

            return response()->json([
                'success'  => true,
                'action'   => 'laporan_dibuat',
                'ticket'   => $trackingNumber,
            ]);

        } catch (\Exception $e) {
            Log::error('WhatsApp: Gagal membuat laporan', [
                'error'  => $e->getMessage(),
                'number' => $number,
            ]);

            $pesan = "❌ *Maaf, terjadi kesalahan saat memproses laporan Anda.*\n\n" .
                "Silakan coba lagi nanti atau hubungi admin KMC.\n\n" .
                "Balas *0* untuk kembali ke Menu Utama.";

            FonnteService::send($number, $pesan);

            return response()->json(['success' => false, 'action' => 'error']);
        }
    }

    /**
     * Handle cek status tiket via WhatsApp.
     * Tampilan status sama persis dengan mharisfr/kmc.
     */
    private function handleCekStatus(string $number, string $trackingNumber)
    {
        $trackingNumber = strtoupper(trim($trackingNumber));

        if (empty($trackingNumber)) {
            $pesan = "⚠️ Format tidak valid.\n\n" .
                "Gunakan format: *CEK#NomorTiket*\n" .
                "Contoh: *CEK#KMC-20260805-0001*\n\n" .
                "Balas *menu* untuk kembali.";

            FonnteService::send($number, $pesan);
            return response()->json(['success' => false, 'action' => 'format_invalid']);
        }

        $ticket = Ticket::where('tracking_number', $trackingNumber)
            ->orWhere('ticket_number', $trackingNumber)
            ->first();

        if (!$ticket) {
            $pesan = "❌ *Laporan Tidak Ditemukan*\n\n" .
                "Nomor tiket *{$trackingNumber}* tidak ditemukan dalam sistem kami.\n\n" .
                "Kemungkinan penyebab:\n" .
                "• Nomor tiket salah atau tidak lengkap\n" .
                "• Laporan belum selesai diproses\n\n" .
                "Silakan periksa kembali nomor tiket Anda.\n\n" .
                "ketik *menu* untuk kembali ke menu utama.\n\n" .
                "*Ketapang Media Center (KMC)*";

            FonnteService::send($number, $pesan);
            return response()->json(['success' => false, 'action' => 'tiket_not_found']);
        }

        // Format status — sama persis dengan mharisfr/kmc
        $statusEmoji = match ($ticket->status) {
            'baru'             => '🆕',
            'diterima'         => '📥',
            'proses_disposisi' => '📋',
            'diteruskan'       => '📨',
            'dibaca'           => '👁️',
            'diproses'         => '⏳',
            'dijawab'          => '💬',
            'selesai'          => '✅',
            'eskalasi'         => '🔺',
            'ditolak'          => '❌',
            default            => '⏳',
        };

        $statusLabel = match ($ticket->status) {
            'baru'             => 'Menunggu Tindak Lanjut',
            'diterima'         => 'Diterima',
            'proses_disposisi' => 'Proses Disposisi',
            'diteruskan'       => 'Diteruskan ke OPD',
            'dibaca'           => 'Dibaca OPD',
            'diproses'         => 'Sedang Diproses',
            'dijawab'          => 'Sudah Dijawab',
            'selesai'          => 'Selesai',
            'eskalasi'         => 'Eskalasi',
            'ditolak'          => 'Ditolak',
            default            => ucfirst($ticket->status),
        };

        $tanggal = $ticket->created_at
            ->timezone('Asia/Jakarta')
            ->translatedFormat('d F Y H:i');

        // Cek jawaban terbaru
        $latestResponse = $ticket->responses()->latest()->first();
        $jawaban = $latestResponse
            ? $latestResponse->message
            : '_Belum ada jawaban dari instansi terkait._';

        $pesan =
            "📋 *INFORMASI STATUS LAPORAN*\n" .
            "━━━━━━━━━━━━━━━━━━━━\n\n" .

            " *No. Tiket* : {$ticket->tracking_number}\n" .
            "*Pelapor*   : {$ticket->reporter_name}\n" .
            "*Tanggal*   : {$tanggal} WIB\n" .
            "*OPD*       : " . ($ticket->opd_related ?? '-') . "\n\n" .

            "📌 *Detail Laporan:*\n" .
            "{$ticket->complaint}\n\n" .

            "━━━━━━━━━━━━━━━━━━━━\n" .
            "{$statusEmoji} *Status : {$statusLabel}*\n" .
            "━━━━━━━━━━━━━━━━━━━━\n\n" .

            "💬 *Jawaban / Tindak Lanjut:*\n" .
            "{$jawaban}\n\n" .

            "Balas *menu* untuk kembali ke menu utama.\n\n" .

            "*Ketapang Media Center (KMC)*";

        FonnteService::send($number, $pesan);

        return response()->json(['success' => true, 'action' => 'cek_status', 'ticket' => $ticket->tracking_number]);
    }

    /**
     * Pesan tidak dikenali
     */
    private function handleTidakDikenali(string $number)
    {
        $rawMessage = $_REQUEST['message'] ?? '';
        $pesan = "⚠️ Maaf, perintah *\"{$rawMessage}\"* tidak dikenali.\n\n";
        $pesan .= $this->menuUtamaText();

        FonnteService::send($number, $pesan);

        return response()->json(['success' => true, 'action' => 'tidak_dikenali']);
    }

    /**
     * Teks menu utama (reusable)
     */
    private function menuUtamaText(): string
    {
        return
            "🏛 *Selamat Datang di Ketapang Media Center (KMC)*\n\n" .

            "Halo! Kami hadir untuk membantu Anda menyampaikan aspirasi dan pengaduan kepada Pemerintah Kabupaten Ketapang.\n\n" .

            "📋 *Pilih Layanan:*\n\n" .

            "1️⃣ *Pengaduan Masyarakat*\n" .
            "   Sampaikan keluhan atau laporan pelayanan publik\n\n" .

            "2️⃣ *Layanan Darurat 112*\n" .
            "   Kebakaran, kecelakaan, kriminal & darurat lainnya\n\n" .

            "3️⃣ *Cek Status Laporan*\n" .
            "   Pantau perkembangan laporan yang telah dikirim\n\n" .

            "━━━━━━━━━━━━━━━━━━━━\n" .
            "Balas dengan angka *1*, *2*, atau *3*.\n\n" .

            "_Ketik *menu* kapan saja untuk kembali ke halaman ini._\n\n" .

            "*Ketapang Media Center (KMC)*\n" .
            "_Melayani dengan Hati_ 🤝";
    }

    /**
     * Kirim notifikasi WA ke OPD terkait saat ada tiket baru.
     */
    private function notifyOpd(Ticket $ticket): void
    {
        if (!$ticket->assigned_opd_id) {
            return;
        }

        $opd = Opd::find($ticket->assigned_opd_id);
        if (!$opd) {
            return;
        }

        // Cari user OPD yang terkait
        $opdUser = $opd->user;
        if (!$opdUser || empty($opdUser->phone)) {
            return;
        }

        $pesan = "🔔 *DISPOSISI ADUAN BARU*\n\n" .
            "Kepada *{$opd->name}*,\n\n" .
            "Terdapat aduan baru yang telah didisposisikan kepada instansi Anda:\n\n" .
            "📌 Tiket: *{$ticket->tracking_number}*\n" .
            "📂 Kategori: {$ticket->category} — {$ticket->sub_category}\n" .
            "👤 Pelapor: {$ticket->reporter_name}\n" .
            "📝 Isi: " . \Illuminate\Support\Str::limit($ticket->complaint, 150) . "\n" .
            "⏰ Batas SLA: " . ($ticket->sla_deadline ? $ticket->sla_deadline->format('d/m/Y H:i') : '-') . "\n\n" .
            "Mohon segera ditindaklanjuti melalui portal OPD:\n" .
            config('app.url') . "/opd/tickets/{$ticket->id}\n\n" .
            "Terima kasih. 🙏";

        $phone = FonnteService::formatPhone($opdUser->phone);
        FonnteService::send($phone, $pesan);

        Log::info('WhatsApp: Notifikasi OPD terkirim', [
            'opd'    => $opd->name,
            'ticket' => $ticket->tracking_number,
        ]);
    }

    /**
     * Endpoint testing — kirim pesan test via WA (admin only).
     */
    public function sendTest(Request $request)
    {
        $phone   = $request->query('phone', config('services.fonnte.bot_number'));
        $message = $request->query('message', '🧪 Test dari SIMADU-KMC WhatsApp Integration');

        if (empty($phone)) {
            return response()->json(['error' => 'Nomor tujuan tidak dikonfigurasi']);
        }

        $result = FonnteService::send(
            FonnteService::formatPhone($phone),
            $message
        );

        return response()->json([
            'success' => true,
            'phone'   => $phone,
            'result'  => $result,
        ]);
    }
}
