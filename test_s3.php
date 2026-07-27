<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Testing S3 Connection...\n";
    Storage::disk('s3')->put('test.txt', 'Hello World');
    echo "Uploaded successfully!\n";
    echo Storage::disk('s3')->url('test.txt') . "\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
