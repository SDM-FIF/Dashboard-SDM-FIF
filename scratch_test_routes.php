<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "rekrutasi-dosen.index URL: " . route('rekrutasi-dosen.index') . "\n";
} catch (\Exception $e) {
    echo "rekrutasi-dosen.index Error: " . $e->getMessage() . "\n";
}

try {
    echo "rekrutasi-dosen URL: " . route('rekrutasi-dosen') . "\n";
} catch (\Exception $e) {
    echo "rekrutasi-dosen Error: " . $e->getMessage() . "\n";
}
