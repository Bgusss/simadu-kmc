<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$count = App\Models\Notification::where('title', 'Instagram DM')->count();
echo "Count for Instagram DM: " . $count . "\n";

$req = request();
$req->merge(['type' => 'Instagram DM']);

$controller = new App\Http\Controllers\NotificationController();
$res = $controller->index($req);

echo "Total items in paginator: " . $res->getData()['notifications']->total() . "\n";
