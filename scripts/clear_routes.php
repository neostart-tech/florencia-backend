<?php
require 'C:/Users/MAWUSSI/florencia-backend/vendor/autoload.php';
$app = require_once 'C:/Users/MAWUSSI/florencia-backend/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
try {
    Artisan::call('route:clear');
    echo "Route cache cleared.\n";
    
    Artisan::call('route:list', ['--path' => 'api/admin/login']);
    echo Artisan::output();
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
