<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$req = Illuminate\Http\Request::create('http://localhost/SIMADU-KMC/public/notifications/partial?search=&type=Instagram+DM&status=', 'GET');
$app->instance('request', $req);

$controller = new App\Http\Controllers\NotificationController();
$res = $controller->partial($req);

$view = $res->render();
echo "Rendered view length: " . strlen($view) . "\n";
echo "Contains 'Belum ada notifikasi': " . (strpos($view, 'Belum ada notifikasi') !== false ? 'Yes' : 'No') . "\n";
