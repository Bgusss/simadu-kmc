<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\FacebookCommentMention;
use App\Models\FacebookPostMention;
use App\Models\AIClassification;
use App\Services\TicketingService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $this->buildNotificationQuery($request);

        return view(
            'notifications',
            compact(
                'notifications'
            )
        );
    }

    public function partial(Request $request)
    {
        $notifications = $this->buildNotificationQuery($request);

        return view(
            'notifications.partials.list',
            compact('notifications')
        );
    }

    private function buildNotificationQuery(Request $request)
    {
        $query = Notification::with('duplicateOf');

        /*
         * Search
         */
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'title',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'sender',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'message',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'comment_message',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        /*
         * Filter jenis notifikasi
         */
        if ($request->filled('type')) {

            $query->where(
                'title',
                $request->type
            );
        }

        /*
         * Filter status baca
         */
        if ($request->status === '0' || $request->status === '1') {
            $query->where('is_read', $request->status);
        }

        /*
         * Filter status duplikasi
         */
        if ($request->filled('duplicate')) {
            $query->where('duplicate_status', $request->duplicate);
        }

        /*
         * Pagination
         */
        $notifications = $query
            ->latest()
            ->paginate(5)
            ->withQueryString();

        /*
         * Lengkapi data notifikasi
         */
        $notifications->getCollection()
            ->transform(function ($notif) {

                /*
                 * Facebook Mention Postingan
                 */
                if ($notif->title === 'Facebook Mention') {

                    $postMention = FacebookPostMention::where(
                            'post_link',
                            $notif->permalink
                        )
                        ->latest()
                        ->first();

                    if ($postMention) {
                        $notif->sender = $postMention->sender;
                    }
                }

                /*
                 * Facebook Mention Komentar
                 */
                if ($notif->title === 'Facebook Comment Mention') {

                    $commentMention = FacebookCommentMention::where(
                            'comment_link',
                            $notif->permalink
                        )
                        ->latest()
                        ->first();

                    if ($commentMention) {

                        $notif->sender = $notif->sender
                            ?? explode(' mentioned', $commentMention->notification_text)[0];

                        $notif->comment_message = $commentMention->comment_message;
                    }
                }

                $notif->ai = AIClassification::where(
                        'notification_id',
                        $notif->id
                    )->first();

                return $notif;
            });

        return $notifications;
    }

    /**
     * Admin memverifikasi: BUKAN duplikat → Buat tiket otomatis.
     */
    public function confirmNotDuplicate($id)
    {
        $notification = Notification::findOrFail($id);

        if ($notification->duplicate_status !== 'terdeteksi') {
            return back()->with('error', 'Notifikasi ini tidak dalam status menunggu verifikasi duplikasi.');
        }

        // Tandai sebagai bukan duplikat
        $notification->update([
            'duplicate_status' => 'bukan_duplikat',
        ]);

        // Buat tiket otomatis dari hasil AI yang sudah ada
        $aiClassification = AIClassification::where('notification_id', $notification->id)->first();

        if ($aiClassification) {
            try {
                $ticket = app(TicketingService::class)->createTicketFromClassification($notification, $aiClassification);
                return back()->with('success', "Diverifikasi bukan duplikat. Tiket {$ticket->tracking_number} berhasil dibuat.");
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal membuat tiket: ' . $e->getMessage());
            }
        }

        return back()->with('error', 'Data klasifikasi AI tidak ditemukan untuk notifikasi ini.');
    }

    /**
     * Admin memverifikasi: MEMANG duplikat → Arsipkan, jangan buat tiket.
     */
    public function confirmDuplicate($id)
    {
        $notification = Notification::findOrFail($id);

        if ($notification->duplicate_status !== 'terdeteksi') {
            return back()->with('error', 'Notifikasi ini tidak dalam status menunggu verifikasi duplikasi.');
        }

        $notification->update([
            'duplicate_status' => 'dikonfirmasi_duplikat',
            'is_read' => true,
        ]);

        return back()->with('success', 'Notifikasi dikonfirmasi sebagai duplikat dan telah diarsipkan.');
    }
}
