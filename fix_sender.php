<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Notification;

$notifications = Notification::all();

foreach($notifications as $notif) {
    // getSenderNameAttribute() fetches the true sender from related tables
    $realSender = $notif->getSenderNameAttribute();
    
    if (!empty($realSender) && $realSender !== 'Facebook') {
        $notif->sender = $realSender;
        $notif->save();
        echo "Updated notification ID {$notif->id} with sender: {$realSender}\n";
    }
}
echo "Done.\n";
