<?php
putenv('AWS_EC2_METADATA_DISABLED=true');
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $start = microtime(true);
    echo "Testing S3 Connection (Metadata disabled)...\n";
    Storage::disk('s3')->put('test3.txt', 'Hello World');
    $time = microtime(true) - $start;
    echo "Uploaded successfully in {$time} seconds!\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
