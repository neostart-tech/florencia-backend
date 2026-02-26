<?php
require 'C:/Users/MAWUSSI/florencia-backend/vendor/autoload.php';
$app = require_once 'C:/Users/MAWUSSI/florencia-backend/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
try {
    $admin = User::with('role')->whereHas('role', function($q) {
        $q->whereIn('role', ['admin', 'superadmin']);
    })->first();

    if ($admin) {
        echo "Admin User Found:\n";
        echo "Name: " . $admin->nom . "\n";
        echo "Email: " . $admin->email . "\n";
        echo "Role: " . $admin->role->role . "\n";
    } else {
        echo "No admin or superadmin user found.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
