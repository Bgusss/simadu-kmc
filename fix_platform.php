<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Ticket;
use App\Models\Notification;

$tickets = Ticket::whereHas('notification', function($q) {
    $q->where('title', 'Instagram DM');
})->get();

foreach($tickets as $t) {
    $t->platform = 'Instagram';
    $t->save();
    echo 'Updated ticket: ' . $t->ticket_number . "\n";
}
echo "Done.\n";
