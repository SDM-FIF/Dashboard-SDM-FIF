<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Mahasiswa;
use App\Models\Kompetisi;
use Illuminate\Support\Facades\DB;

echo "Total Mahasiswa: " . Mahasiswa::count() . "\n";
echo "Total Kompetisi: " . Kompetisi::count() . "\n";
echo "Total Mahasiswa Kompetisi (Juara): " . DB::table('mahasiswa_kompetisi')->count() . "\n";
