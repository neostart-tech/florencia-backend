<?php
require 'C:/Users/MAWUSSI/florencia-backend/vendor/autoload.php';
$app = require_once 'C:/Users/MAWUSSI/florencia-backend/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    $admin = User::where('email', 'admin@florencia.com')->first();
    if ($admin) {
        $admin->password = Hash::make('admin2025');
        $admin->save();
        echo "Admin password updated successfully.\n";
        echo "Email: admin@florencia.com\n";
        echo "Password: admin2025\n";
    } else {
        echo "Admin user not found.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
