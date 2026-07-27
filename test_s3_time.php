<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$start = microtime(true);
Storage::disk('s3')->put('test4.txt', 'test');
$time = microtime(true) - $start;
echo "Time: {$time}\n";
