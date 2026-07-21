<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Opd\OpdController;
use App\Http\Controllers\Public\TicketingController;
use App\Http\Controllers\Admin\AdminOpdController;
use App\Http\Controllers\StorageFileController;

use App\Models\Notification;
use App\Models\FacebookCommentMention;
use App\Models\FacebookPostMention;

// use App\Services\InstagramService;

Route::get('/', function () {
    return redirect()->route('ticketing.index');
});

/*
|--------------------------------------------------------------------------
| Storage File Serve (php artisan serve tidak support symlink)
|--------------------------------------------------------------------------
*/
Route::get('/storage-debug', [StorageFileController::class, 'debug'])->name('storage.debug');
Route::get('/storage/{path}', [StorageFileController::class, 'show'])
    ->where('path', '.*')
    ->name('storage.file');

/*
|--------------------------------------------------------------------------
| Public Tracking & Auth
|--------------------------------------------------------------------------
*/

Route::get('/ticketing', [TicketingController::class, 'index'])->name('ticketing.index');
Route::get('/ticketing/{tracking_number}', [TicketingController::class, 'show'])->name('ticketing.show');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/notifications/poll', [DashboardController::class, 'pollNotifications'])->name('dashboard.poll');
    Route::resource('admin/opd', AdminOpdController::class, ['as' => 'admin']);
    
    Route::get('/admin/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'index'])->name('admin.profile');
    Route::post('/admin/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('admin.profile.update');
    
    // Existing routes that should probably be admin-only
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/partial', [NotificationController::class, 'partial'])->name('notifications.partial');

    // Duplicate verification routes
    Route::post('/notifications/{id}/not-duplicate', [NotificationController::class, 'confirmNotDuplicate'])->name('notifications.not-duplicate');
    Route::post('/notifications/{id}/is-duplicate', [NotificationController::class, 'confirmDuplicate'])->name('notifications.is-duplicate');
    
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');
});

/*
|--------------------------------------------------------------------------
| OPD Portal
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:opd'])->prefix('opd')->name('opd.')->group(function () {
    Route::get('/', [OpdController::class, 'dashboard'])->name('dashboard');
    Route::get('/tickets', [OpdController::class, 'tickets'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [OpdController::class, 'showTicket'])->name('tickets.show');
    Route::get('/tickets/{ticket}/edit', [OpdController::class, 'editTicket'])->name('tickets.edit');
    Route::post('/tickets/{ticket}/respond', [OpdController::class, 'respond'])->name('tickets.respond');
    Route::post('/tickets/{ticket}/status', [OpdController::class, 'updateStatus'])->name('tickets.status');
    Route::get('/profile', [OpdController::class, 'profile'])->name('profile');
    Route::post('/profile', [OpdController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Notifikasi
|--------------------------------------------------------------------------
*/

// Removed old closure-based /notifications

// Notifications detail routes moved to admin group or kept public if needed?
// The user asked to make /notifications and /tickets admin only.
// Let's protect the generic notification closure routes as well.
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get(
        '/notification/{id}',
        function ($id) {
            $notif = Notification::findOrFail($id);
            $notif->update(['is_read' => true]);
            return back();
        }
    );

    Route::get(
        '/notification/{id}/detail',
        function ($id) {
            $notif = Notification::findOrFail($id);
            $notif->update(['is_read' => true]);
            return redirect()->away(request('url'));
        }
    );
});

/*
|--------------------------------------------------------------------------
| AJAX Dashboard & Notifikasi
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin|opd'])->group(function () {

    Route::get(
        '/notifications-data',
        function () {
            $user = auth()->user();
            
            if ($user && $user->role === 'opd') {
                $tickets = \App\Models\Ticket::where('assigned_opd_id', $user->opd_id)
                    ->latest()
                    ->take(20)
                    ->get()
                    ->map(function ($ticket) {
                        $platform = $ticket->platform ?? 'Laporan Web SIMADU';
                        $platformTitle = in_array(strtolower($platform), ['whatsapp', 'facebook', 'instagram']) ? $platform : 'Laporan Web SIMADU';
                        
                        return [
                            'id' => $ticket->id,
                            'title' => $platformTitle,
                            'sender' => $ticket->reporter_name ?? 'Pelapor Anonim',
                            'message' => "Terdapat disposisi tiket laporan baru dengan nomor resi {$ticket->tracking_number} terkait " . ($ticket->sub_category ?? 'keluhan warga') . ". Mohon agar dapat segera ditindaklanjuti.",
                            'permalink' => route('opd.tickets.show', $ticket->id),
                            'is_read' => in_array($ticket->status, ['dibaca', 'diproses', 'dijawab', 'selesai', 'eskalasi']),
                            'created_at' => $ticket->created_at->diffForHumans(),
                            'created_at_raw' => $ticket->created_at->toISOString(),
                        ];
                    });
                
                $dummyOpdNotifs = \Illuminate\Support\Facades\Cache::pull("dummy_opd_notifications_{$user->opd_id}");
                if ($dummyOpdNotifs) {
                    $tickets = collect($dummyOpdNotifs)->merge($tickets);
                }

                return response()->json($tickets);
            }

            $notifications = Notification::latest()
                ->take(20)
                ->get()
                ->map(function ($notif) {

                    $permalink = null;
                    $commentMessage = null;
                    $sender = null;

                    /*
                     * Facebook Mention Postingan
                     */
                    if (
                        $notif->title ===
                        'Facebook Mention'
                    ) {

                        $permalink =
                            $notif->permalink;

                        if ($permalink) {

                            $postMention =
                                FacebookPostMention::where(
                                    'post_link',
                                    $permalink
                                )->latest()->first();

                            $sender =
                                $postMention?->sender;
                        }
                    }

                    /*
                     * Facebook Mention Komentar
                     */
                    if (
                        $notif->title ===
                        'Facebook Comment Mention'
                    ) {

                        $commentMention =
                            FacebookCommentMention::where(
                                'notification_text',
                                $notif->message
                            )->latest()->first();

                        $permalink =
                            $commentMention?->comment_link;

                        $commentMessage =
                            $commentMention?->comment_message;

                        if ($commentMention) {

                            $sender = explode(
                                ' mentioned',
                                $commentMention
                                    ->notification_text
                            )[0];
                        }
                    }

                    return [

                        'id' =>
                            $notif->id,

                        'title' =>
                            $notif->title,

                        'sender' =>
                            $sender,

                        'message' =>
                            $notif->message,

                        'comment_message' =>
                            $commentMessage,

                        'is_read' =>
                            $notif->is_read,

                        'permalink' =>
                            $permalink,

                        'created_at' =>
                            $notif
                                ->created_at
                                ->diffForHumans(),

                        'created_at_raw' =>
                            $notif
                                ->created_at
                                ->toISOString(),
                    ];
                });

            $dummyNotifs = \Illuminate\Support\Facades\Cache::pull('dummy_notifications');
            if ($dummyNotifs) {
                // Merge dummy notifs at the top of the list so they appear newest
                $notifications = collect($dummyNotifs)->merge($notifications);
            }

            return response()->json(
                $notifications
            );
        }
    );

    Route::get(
        '/notification-summary',
        function () {
            $user = auth()->user();
            if ($user && $user->role === 'opd') {
                $q = \App\Models\Ticket::where('assigned_opd_id', $user->opd_id);
                $readStatuses = ['dibaca', 'diproses', 'dijawab', 'selesai', 'eskalasi'];
                return response()->json([
                    'unread' => (clone $q)->whereNotIn('status', $readStatuses)->count(),
                    'total' => (clone $q)->count(),
                    'read' => (clone $q)->whereIn('status', $readStatuses)->count(),
                    'today' => (clone $q)->whereDate('created_at', today())->count(),
                ]);
            }

            return response()->json([

                'unread' =>
                    Notification::where(
                        'is_read',
                        false
                    )->count(),

                'total' =>
                    Notification::count(),

                'read' =>
                    Notification::where(
                        'is_read',
                        true
                    )->count(),

                'today' =>
                    Notification::whereDate(
                        'created_at',
                        today()
                    )->count(),

            ]);
        }
    );

    Route::get(
        '/notification-count',
        function () {
            $user = auth()->user();
            if ($user && $user->role === 'opd') {
                return response()->json([
                    'count' => \App\Models\Ticket::where('assigned_opd_id', $user->opd_id)
                        ->whereNotIn('status', ['dibaca', 'diproses', 'dijawab', 'selesai', 'eskalasi'])
                        ->count()
                ]);
            }

            return response()->json([

                'count' =>
                    Notification::where(
                        'is_read',
                        false
                    )->count()

            ]);
        }
    );

});

/*
|--------------------------------------------------------------------------
| Complaint
|--------------------------------------------------------------------------
*/

Route::post(
    '/complaint',
    [ComplaintController::class, 'store']
)->middleware('throttle:5,1');

/*
|--------------------------------------------------------------------------
| Notification
|--------------------------------------------------------------------------
*/

// Moved to admin group


/*
|--------------------------------------------------------------------------
| Tiket
|--------------------------------------------------------------------------
*/

// Moved to admin group

/*
|--------------------------------------------------------------------------
| Development / Testing
|--------------------------------------------------------------------------
*/

// Route::get('/api-test', function () {
//
//     $response = Http::get(
//         'https://jsonplaceholder.typicode.com/posts'
//     );
//
//     return $response->json();
// });

// Route::get('/posts', function () {
//
//     $response = Http::get(
//         'https://jsonplaceholder.typicode.com/posts'
//     );
//
//     $posts = $response->json();
//
//     return view(
//         'posts',
//         compact('posts')
//     );
// });

// Route::get('/channels', function () {
//
//     $messages = [
//
//         [
//             'channel' => 'Instagram',
//             'sender' => 'user_ig',
//             'message' => 'Lampu jalan mati'
//         ],
//
//         [
//             'channel' => 'Facebook',
//             'sender' => 'user_fb',
//             'message' => 'Jalan berlubang'
//         ],
//
//         [
//             'channel' => 'WhatsApp',
//             'sender' => 'user_wa',
//             'message' => 'Sampah menumpuk'
//         ]
//
//     ];
//
//     return view(
//         'channels',
//         compact('messages')
//     );
// });

// Route::get('/seed-channels', function () {
//
//     Message::create([
//         'channel' => 'Instagram',
//         'sender' => 'user_ig',
//         'message' => 'Lampu jalan mati'
//     ]);
//
//     Message::create([
//         'channel' => 'Facebook',
//         'sender' => 'user_fb',
//         'message' => 'Jalan berlubang'
//     ]);
//
//     Message::create([
//         'channel' => 'WhatsApp',
//         'sender' => 'user_wa',
//         'message' => 'Sampah menumpuk'
//     ]);
//
//     return 'Berhasil';
// });

// Route::get('/channels-db', function () {
//
//     $messages = Message::latest()
//         ->get();
//
//     return view(
//         'channels',
//         compact('messages')
//     );
// });

// Route::get('/collect', function (
//
//     InstagramService $instagram,
//
//     WhatsAppService $whatsapp
//
// ) {
//
//     $messages = array_merge(
//
//         $instagram->getMessages(),
//
//         $whatsapp->getMessages()
//
//     );
//
//     dd($messages);
// });

// Route::get('/collect-save', function (
//
//     InstagramService $instagram,
//
//     WhatsAppService $whatsapp
//
// ) {
//
//     $messages = array_merge(
//
//         $instagram->getMessages(),
//
//         $whatsapp->getMessages()
//
//     );
//
//     foreach ($messages as $msg) {
//
//         Message::create([
//
//             'channel' =>
//                 $msg['channel'],
//
//             'sender' =>
//                 $msg['sender'],
//
//             'message' =>
//                 $msg['message']
//
//         ]);
//     }
//
//     return 'Data berhasil disimpan';
// });