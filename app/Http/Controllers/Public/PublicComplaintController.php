<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Opd;
use App\Models\Ticket;
use App\Models\TicketStatusLog;
use App\Services\AIClassificationService;
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
        return view('public.complaint');
    }

    /**
     * Simpan pengaduan dari masyarakat.
     */
    public function store(Request $request)
    {
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

            // 2. Create Notification
            $notification = Notification::create([
                'title'       => 'WhatsApp',
                'sender'      => $request->reporter_name,
                'message'     => $request->complaint,
                'attachments' => !empty($attachmentPaths) ? $attachmentPaths : null,
            ]);

            // 3. Generate tracking number
            $today      = now()->format('Ymd');
            $countToday = Ticket::whereDate('created_at', now()->toDateString())->count();
            $sequence   = str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);
            $trackingNumber = "KMC-{$today}-{$sequence}";

            // 4. AI Classification
            $category    = 'Pengaduan Umum';
            $subCategory = 'Lain-lain';
            $opdName     = null;
            $opdId       = null;
            $priority    = 'sedang';
            $aiConfidence = null;
            $aiReasoning  = null;

            try {
                $aiService = app(AIClassificationService::class);
                $aiResult  = $aiService->classify($request->complaint);

                if ($aiResult && is_array($aiResult)) {
                    $category     = $aiResult['suggested_category'] ?? $category;
                    $subCategory  = $aiResult['suggested_sub_category'] ?? $subCategory;
                    $priority     = strtolower($aiResult['priority'] ?? 'Sedang');
                    $aiConfidence = $aiResult['confidence'] ?? null;
                    $aiReasoning  = $aiResult['reasoning'] ?? null;

                    $suggestedOpds = $aiResult['suggested_opds'] ?? [];
                    if (is_array($suggestedOpds) && count($suggestedOpds) > 0) {
                        $opdName = $suggestedOpds[0];
                        $opd = Opd::where('name', $opdName)->first();

                        if (!$opd) {
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
                Log::warning('PublicComplaint: AI Classification gagal', ['error' => $e->getMessage()]);
            }

            // 5. Create Ticket
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

            // 6. Kirim konfirmasi WA (jika Fonnte tersedia) — non-blocking
            try {
                $phone = FonnteService::formatPhone($request->reporter_phone);
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
}
