<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Ticket;
use App\Models\Opd;
use App\Models\TicketStatusLog;
use App\Services\AIClassificationService;
use App\Services\FonnteService;
use App\Services\TicketingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WhatsappController extends Controller
{
    /**
     * Daftar sapaan yang memicu menu utama.
     */
    private array $sapaan = [
        // Salam umum
        'halo', 'hai', 'hello', 'hi', 'hey', 'hei', 'hallo', 'haloo', 'halooo',
        'haii', 'haiii', 'helo', 'heloo', 'helloo',

        // Salam Islam
        'assalamualaikum', "assalamu'alaikum", 'assalamualaikumwr', 'assalamualaikumwrwb',
        'waalaikumsalam', "wa'alaikumsalam", 'waalaikum salam',
        'salam', 'salamualaikum',

        // Sapaan waktu
        'selamat pagi', 'selamat siang', 'selamat sore', 'selamat malam',
        'pagi', 'siang', 'sore', 'malam',
        'met pagi', 'met siang', 'met sore', 'met malam',

        // Navigasi menu
        'menu', 'main menu', 'menu utama', 'kembali', 'kembali ke menu',
        'back', 'home', 'mulai', 'start', 'help', 'bantuan',

        // Pembuka percakapan
        'permisi', 'misi', 'hola', 'yo', 'yoo',
        'ada', 'ada?', 'aktif', 'aktif?', 'ping', 'test', 'tes',

        // Sapaan ke admin/bot
        'kmc', 'halo kmc', 'hai kmc', 'hello kmc',
        'min', 'halo min', 'hai min',
        'admin', 'halo admin', 'bot', 'halo bot',

        // Typo umum
        'hlo', 'hloo', 'hy', 'hye', 'aslm', 'askum', 'ass',
        'assamu alaikum', 'asalamualaikum', 'asalamu alaikum',

        // Sapaan tambahan
        'woy', 'woi', 'oi', 'oy', 'kak', 'ka', 'bang', 'pak', 'bu',
        'mimin', 'halo mimin', 'apa kabar', 'gan', 'sis', 'bro',
        'numpang tanya', 'mau tanya', 'boleh tanya',
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
                return $this->handlePanduanLapor($number);
            }

            if ($message === '2') {
                return $this->handlePanduanCek($number);
            }

            if ($message === '3') {
                return $this->handleInfoKontak($number);
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
     * Menu Utama
     */
    private function handleMenuUtama(string $number)
    {
        $pesan = "🏛️ *SIMADU - Ketapang Media Center*\n"
            . "Sistem Informasi Manajemen Aduan\n\n"
            . "Selamat datang! Silakan pilih menu:\n\n"
            . "1️⃣ *Lapor Aduan* — Kirim pengaduan baru\n"
            . "2️⃣ *Cek Status* — Lacak status aduan Anda\n"
            . "3️⃣ *Info & Kontak* — Informasi layanan KMC\n\n"
            . "Balas dengan angka *1*, *2*, atau *3*\n"
            . "Atau ketik *MENU* kapan saja untuk kembali.";

        FonnteService::send($number, $pesan);

        return response()->json(['success' => true, 'action' => 'menu_utama']);
    }

    /**
     * Panduan Lapor
     */
    private function handlePanduanLapor(string $number)
    {
        $pesan = "📝 *CARA MELAPOR VIA WHATSAPP*\n\n"
            . "Kirim pesan dengan format:\n\n"
            . "LAPOR#Nama Lengkap#Isi laporan Anda\n\n"
            . "📌 *Contoh:*\n"
            . "LAPOR#Budi Santoso#Jalan berlubang di Jl. Merdeka depan kantor pos, sudah 2 minggu belum diperbaiki\n\n"
            . "Atau Anda juga bisa melapor via web:\n"
            . config('app.url') . "/lapor\n\n"
            . "Balas *0* untuk kembali ke Menu Utama.";

        FonnteService::send($number, $pesan);

        return response()->json(['success' => true, 'action' => 'panduan_lapor']);
    }

    /**
     * Panduan Cek Status
     */
    private function handlePanduanCek(string $number)
    {
        $pesan = "🔍 *CARA CEK STATUS ADUAN*\n\n"
            . "Kirim pesan dengan format:\n\n"
            . "CEK#Nomor_Tiket_Anda\n\n"
            . "📌 *Contoh:*\n"
            . "CEK#KMC-20260805-0001\n\n"
            . "Nomor tiket Anda bisa dilihat pada pesan konfirmasi saat melapor.\n\n"
            . "Atau lacak di web:\n"
            . config('app.url') . "/ticketing\n\n"
            . "Balas *0* untuk kembali ke Menu Utama.";

        FonnteService::send($number, $pesan);

        return response()->json(['success' => true, 'action' => 'panduan_cek']);
    }

    /**
     * Info & Kontak
     */
    private function handleInfoKontak(string $number)
    {
        $pesan = "ℹ️ *INFO LAYANAN KMC*\n\n"
            . "🏛️ *Ketapang Media Center (KMC)*\n"
            . "Dinas Komunikasi dan Informatika\n"
            . "Kabupaten Ketapang\n\n"
            . "📍 *Alamat:*\n"
            . "Jl. R. Suprapto No.3, Ketapang\n\n"
            . "🌐 *Website:*\n"
            . config('app.url') . "\n\n"
            . "📧 *Email:*\n"
            . "kominfo@ketapangkab.go.id\n\n"
            . "⏰ *Jam Layanan:*\n"
            . "Senin - Jumat: 08.00 - 16.00 WIB\n\n"
            . "Balas *0* untuk kembali ke Menu Utama.";

        FonnteService::send($number, $pesan);

        return response()->json(['success' => true, 'action' => 'info_kontak']);
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
            $pesan = "⚠️ *Format tidak lengkap!*\n\n"
                . "Format yang benar:\n"
                . "LAPOR#Nama Lengkap#Isi laporan\n\n"
                . "📌 *Contoh:*\n"
                . "LAPOR#Budi Santoso#Jalan berlubang di Jl. Merdeka\n\n"
                . "Balas *0* untuk kembali ke Menu Utama.";

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

            // 3. Coba AI Classification (jika service tersedia)
            $category    = 'Pengaduan Umum';
            $subCategory = 'Lain-lain';
            $opdName     = null;
            $opdId       = null;
            $priority    = 'sedang';
            $aiConfidence = null;
            $aiReasoning  = null;

            try {
                $aiService = app(AIClassificationService::class);
                $aiResult  = $aiService->classify($isiLaporan);

                if ($aiResult && is_array($aiResult)) {
                    $category     = $aiResult['suggested_category'] ?? $category;
                    $subCategory  = $aiResult['suggested_sub_category'] ?? $subCategory;
                    $priority     = strtolower($aiResult['priority'] ?? 'Sedang');
                    $aiConfidence = $aiResult['confidence'] ?? null;
                    $aiReasoning  = $aiResult['reasoning'] ?? null;

                    // Resolve OPD dari AI suggestion
                    $suggestedOpds = $aiResult['suggested_opds'] ?? [];
                    if (is_array($suggestedOpds) && count($suggestedOpds) > 0) {
                        $opdName = $suggestedOpds[0];
                        $opd = Opd::where('name', $opdName)->first();

                        if (!$opd) {
                            // Fuzzy match
                            $allOpds = Opd::all();
                            foreach ($allOpds as $o) {
                                similar_text(strtolower($opdName), strtolower($o->name), $percent);
                                if ($percent > 70) {
                                    $opd = $o;
                                    $opdName = $o->name;
                                    break;
                                }
                            }
                        }

                        $opdId = $opd?->id;
                    }
                }
            } catch (\Exception $e) {
                Log::warning('WhatsApp: AI Classification gagal, menggunakan default', [
                    'error' => $e->getMessage(),
                ]);
            }

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

            $pesan = "✅ *LAPORAN ANDA TELAH DITERIMA*\n\n"
                . "Halo *{$namaLengkap}*, terima kasih telah melapor ke Ketapang Media Center.\n\n"
                . "📋 *Detail Laporan:*\n"
                . "• Nomor Tiket: *{$trackingNumber}*\n"
                . "• Kategori: {$category}\n"
                . "• Status: Diterima\n"
                . "• Prioritas: " . ucfirst($priority) . "\n"
                . ($opdName ? "• OPD Tujuan: {$opdName}\n" : '')
                . "\n"
                . "🔍 *Cek Status Aduan:*\n"
                . "Ketik: CEK#{$trackingNumber}\n"
                . "\n"
                . "🌐 *Atau lacak online:*\n"
                . "{$linkCek}\n"
                . ($linkWaCek ? "\n📱 *Klik untuk cek:*\n{$linkWaCek}\n" : '')
                . "\nLaporan Anda akan segera ditindaklanjuti. Mohon kesabarannya. 🙏";

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

            $pesan = "❌ *Maaf, terjadi kesalahan saat memproses laporan Anda.*\n\n"
                . "Silakan coba lagi nanti atau hubungi admin KMC.\n\n"
                . "Balas *0* untuk kembali ke Menu Utama.";

            FonnteService::send($number, $pesan);

            return response()->json(['success' => false, 'action' => 'error']);
        }
    }

    /**
     * Handle cek status tiket via WhatsApp.
     */
    private function handleCekStatus(string $number, string $trackingNumber)
    {
        $ticket = Ticket::where('tracking_number', $trackingNumber)
            ->orWhere('ticket_number', $trackingNumber)
            ->first();

        if (!$ticket) {
            $pesan = "❌ *Tiket tidak ditemukan*\n\n"
                . "Nomor tiket *{$trackingNumber}* tidak ditemukan di sistem kami.\n\n"
                . "Pastikan nomor tiket yang Anda masukkan benar.\n"
                . "Format: CEK#KMC-XXXXXXXX-XXXX\n\n"
                . "Balas *0* untuk kembali ke Menu Utama.";

            FonnteService::send($number, $pesan);
            return response()->json(['success' => false, 'action' => 'tiket_not_found']);
        }

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
            default            => '📌',
        };

        $statusLabel = match ($ticket->status) {
            'baru'             => 'Baru',
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

        $linkDetail = config('app.url') . "/ticketing/{$ticket->tracking_number}";

        $pesan = "📋 *STATUS ADUAN ANDA*\n\n"
            . "📌 Nomor Tiket: *{$ticket->tracking_number}*\n"
            . "{$statusEmoji} Status: *{$statusLabel}*\n"
            . "📂 Kategori: {$ticket->category}\n"
            . "🏢 OPD: " . ($ticket->opd_related ?? 'Belum ditentukan') . "\n"
            . "📅 Dilaporkan: " . $ticket->created_at->format('d/m/Y H:i') . "\n";

        // Tampilkan respon terbaru jika ada
        $latestResponse = $ticket->responses()->latest()->first();
        if ($latestResponse) {
            $pesan .= "\n💬 *Respon Terbaru:*\n"
                . $latestResponse->message . "\n"
                . "(" . $latestResponse->created_at->format('d/m/Y H:i') . ")";
        }

        $pesan .= "\n\n🌐 *Detail lengkap:*\n{$linkDetail}\n\n"
            . "Balas *0* untuk kembali ke Menu Utama.";

        FonnteService::send($number, $pesan);

        return response()->json(['success' => true, 'action' => 'cek_status', 'ticket' => $ticket->tracking_number]);
    }

    /**
     * Pesan tidak dikenali
     */
    private function handleTidakDikenali(string $number)
    {
        $pesan = "🤔 *Maaf, pesan Anda tidak dikenali.*\n\n"
            . "Silakan pilih salah satu menu:\n\n"
            . "1️⃣ Lapor Aduan\n"
            . "2️⃣ Cek Status Aduan\n"
            . "3️⃣ Info & Kontak\n\n"
            . "Atau ketik *MENU* untuk kembali ke menu utama.\n\n"
            . "📝 Untuk langsung melapor, ketik:\n"
            . "LAPOR#Nama#Isi laporan";

        FonnteService::send($number, $pesan);

        return response()->json(['success' => true, 'action' => 'tidak_dikenali']);
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

        $pesan = "🔔 *DISPOSISI ADUAN BARU*\n\n"
            . "Kepada *{$opd->name}*,\n\n"
            . "Terdapat aduan baru yang telah didisposisikan kepada instansi Anda:\n\n"
            . "📌 Tiket: *{$ticket->tracking_number}*\n"
            . "📂 Kategori: {$ticket->category} — {$ticket->sub_category}\n"
            . "👤 Pelapor: {$ticket->reporter_name}\n"
            . "📝 Isi: " . \Illuminate\Support\Str::limit($ticket->complaint, 150) . "\n"
            . "⏰ Batas SLA: " . ($ticket->sla_deadline ? $ticket->sla_deadline->format('d/m/Y H:i') : '-') . "\n\n"
            . "Mohon segera ditindaklanjuti melalui portal OPD:\n"
            . config('app.url') . "/opd/tickets/{$ticket->id}\n\n"
            . "Terima kasih. 🙏";

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
