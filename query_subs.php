<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$subs = App\Models\SubCategory::with('category', 'opd')->orderBy('name')->get();
foreach ($subs as $s) {
    echo $s->id . '|' . $s->name . '|' . ($s->category ? $s->category->name : 'NULL') . '|' . ($s->opd ? $s->opd->name : 'NULL') . PHP_EOL;
}

echo "\n--- Categories ---\n";
$cats = App\Models\Category::orderBy('name')->get();
foreach ($cats as $c) {
    echo $c->id . '|' . $c->name . PHP_EOL;
}
