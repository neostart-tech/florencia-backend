<?php
require 'C:/Users/MAWUSSI/florencia-backend/vendor/autoload.php';
$app = require_once 'C:/Users/MAWUSSI/florencia-backend/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
try {
    $roles = ['superadmin', 'admin', 'personnel', 'user'];
    foreach($roles as $r) {
        Role::firstOrCreate(['role' => $r]);
    }
    echo "Roles synchronized successfully.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
