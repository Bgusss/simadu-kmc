<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Opd;
use App\Models\Ticket;
use App\Models\TicketStatusLog;
use App\Models\AIClassification;
use App\Services\CosineSimilarityService;
use App\Services\TfIdfClassificationService;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PublicComplaintController extends Controller
{
    /**
     * Tampilkan form pengaduan publik.
     */
    public function create()
    {
        return view('public.complaint', ['hideFlashToast' => true]);
    }

    /**
     * Simpan pengaduan dari masyarakat.
     */
    public function store(Request $request)
    {
        foreach ((array) $request->file('attachments', []) as $index => $attachment) {
            if (!$attachment->isValid()) {
                Log::warning('PublicComplaint: Lampiran gagal ditransfer oleh PHP', [
                    'index' => $index,
                    'client_name' => $attachment->getClientOriginalName(),
                    'error_code' => $attachment->getError(),
                    'error_message' => $attachment->getErrorMessage(),
                ]);
            }
        }

        $request->validate([
            'reporter_name' => 'required|string|max:255',
            'reporter_phone' => 'required|string|max:20',
            'complaint' => 'required|string|max:5000',
            'attachments' => 'nullable|array|max:6',
            'attachments.*' => [
                'file',
                'mimes:jpg,jpeg,png,webp,heic,heif,mp4,mov,avi,3gp',
                'max:20480', // 20MB per file
            ],
        ], [
            'reporter_name.required'  => 'Nama lengkap wajib diisi.',
            'reporter_phone.required' => 'Nomor HP/WhatsApp wajib diisi.',
            'complaint.required'      => 'Isi pengaduan wajib diisi.',
            'attachments.max'         => 'Maksimal 6 file (5 gambar + 1 video).',
            'attachments.*.mimes'     => 'Format file tidak didukung. Gunakan JPG, PNG, WEBP, MP4, MOV, atau 3GP.',
            'attachments.*.max'       => 'Ukuran file maksimal 20MB per file.',
        ]);

        try {
            // 1. Simpan lampiran (multiple)
            $attachmentPaths = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $attachmentPaths[] = $file->store('complaints', 'public');
                }
            }

            $phone = FonnteService::formatPhone($request->reporter_phone);

            // 2. Buat notifikasi. Permalink sosial bersifat unik, sehingga tautan
            // WhatsApp ditambahkan setelah ID tersedia agar satu nomor boleh melapor lagi.
            $notification = Notification::create([
                'title'       => 'WhatsApp',
                'sender'      => $request->reporter_name,
                'message'     => $request->complaint,
                'attachments' => !empty($attachmentPaths) ? $attachmentPaths : null,
            ]);

            $notification->update([
                'permalink' => "https://wa.me/{$phone}#laporan-{$notification->id}",
            ]);

            // 3. Generate tracking number yang belum dipakai pada tanggal ini.
            $trackingNumber = $this->generateTrackingNumber();

            // 4. Klasifikasi TF-IDF lokal untuk laporan dari WhatsApp → /lapor.
            $classification = app(TfIdfClassificationService::class)->classify($request->complaint);
            $category = $classification['category'] ?? 'Pengaduan Umum';
            $subCategory = $classification['sub_category'] ?? 'Aduan Masyarakat';
            $opdName = $classification['opd'] ?? null;
            $opdId = Opd::where('name', $opdName)->value('id');
            $priority = $classification['priority'] ?? 'sedang';
            $aiConfidence = $classification['confidence'] ?? 0;
            $aiReasoning = $classification['reasoning'] ?? 'Kategori default laporan publik karena tidak ada kecocokan TF-IDF yang cukup kuat.';

            // Simpan hasil klasifikasi agar verifikasi "Bukan Duplikat" dapat membuat tiket.
            AIClassification::create([
                'notification_id'        => $notification->id,
                'suggested_category'     => $category,
                'suggested_sub_category' => $subCategory,
                'suggested_opds'         => $opdName ? [$opdName] : [],
                'priority'               => ucfirst($priority),
                'confidence'             => $aiConfidence,
                'reasoning'              => $aiReasoning,
            ]);

            // 5. Deteksi duplikasi sebelum tiket dibuat.
            $duplicate = app(CosineSimilarityService::class)->checkDuplicate(
                $request->complaint,
                $notification->id
            );

            if ($duplicate) {
                $notification->update([
                    'duplicate_of_id'      => $duplicate['notification_id'],
                    'duplicate_similarity' => $duplicate['similarity'],
                    'duplicate_status'     => 'terdeteksi',
                ]);

                return redirect()
                    ->route('public.complaint.create')
                    ->with('submitted', true);
            }

            // 6. Create Ticket
            $ticket = DB::transaction(function () use (
                $notification, $trackingNumber, $request,
                $category, $subCategory, $opdName, $opdId, $priority,
                $aiConfidence, $aiReasoning
            ) {
                $ticket = Ticket::create([
                    'notification_id'  => $notification->id,
                    'ticket_number'    => $trackingNumber,
                    'tracking_number'  => $trackingNumber,
                    'ticket_time'      => now(),
                    'platform'         => 'WhatsApp',
                    'reporter_name'    => $request->reporter_name,
                    'reporter_link'    => "wa.me/" . FonnteService::formatPhone($request->reporter_phone),
                    'category'         => $category,
                    'sub_category'     => $subCategory,
                    'opd_related'      => $opdName,
                    'assigned_opd_id'  => $opdId,
                    'priority'         => $priority,
                    'complaint'        => $request->complaint,
                    'status'           => 'diterima',
                    'sla_deadline'     => now()->addHours(24),
                    'ai_confidence'    => $aiConfidence,
                    'ai_reasoning'     => $aiReasoning,
                ]);

                TicketStatusLog::create([
                    'ticket_id'   => $ticket->id,
                    'from_status' => null,
                    'to_status'   => 'diterima',
                    'note'        => 'Tiket dibuat dari form pengaduan publik web SIMADU',
                ]);

                if ($opdName) {
                    $ticket->updateStatus(
                        'diteruskan',
                        null,
                        'Diteruskan ke OPD: ' . $opdName
                    );
                }

                return $ticket;
            });

            // 7. Kirim konfirmasi WA (jika Fonnte tersedia) — non-blocking
            try {
                $linkCek = config('app.url') . "/ticketing/{$trackingNumber}";

                $pesan = "✅ *LAPORAN ANDA TELAH DITERIMA*\n\n"
                    . "Halo *{$request->reporter_name}*, terima kasih telah melapor ke Ketapang Media Center.\n\n"
                    . "📋 *Detail:*\n"
                    . "• Nomor Tiket: *{$trackingNumber}*\n"
                    . "• Kategori: {$category}\n"
                    . "• Status: Diterima\n\n"
                    . "🔍 *Lacak status:*\n{$linkCek}\n\n"
                    . "Atau ketik CEK#{$trackingNumber} di WhatsApp ini.\n\n"
                    . "Laporan Anda akan segera ditindaklanjuti. 🙏";

                FonnteService::send($phone, $pesan);
            } catch (\Exception $e) {
                Log::warning('PublicComplaint: Gagal kirim WA konfirmasi', ['error' => $e->getMessage()]);
            }

            return redirect()
                ->route('public.complaint.create')
                ->with('success', true)
                ->with('tracking_number', $trackingNumber);

        } catch (\Exception $e) {
            Log::error('PublicComplaint: Gagal menyimpan', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('public.complaint.create')
                ->with('error', 'Terjadi kesalahan saat mengirim laporan. Silakan coba lagi.')
                ->withInput($request->except('attachments'));
        }
    }

    /**
     * Buat nomor tiket harian yang belum dipakai.
     */
    private function generateTrackingNumber(): string
    {
        $date = now()->format('Ymd');

        for ($sequence = 1; $sequence <= 9999; $sequence++) {
            $number = sprintf('KMC-%s-%04d', $date, $sequence);
            if (!Ticket::where('ticket_number', $number)->exists()) {
                return $number;
            }
        }

        throw new \RuntimeException('Nomor tiket harian telah mencapai batas maksimum.');
    }
}
