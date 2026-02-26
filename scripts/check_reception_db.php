<?php
require 'C:/Users/MAWUSSI/florencia-backend/vendor/autoload.php';
$app = require_once 'C:/Users/MAWUSSI/florencia-backend/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;

$user = User::with('role')->where('email', 'reception@florencia.com')->first();
if ($user) {
    echo "User found: " . $user->nom . "\n";
    echo "Role: " . ($user->role ? $user->role->role : 'NULL') . "\n";
    echo "Role ID: " . $user->role_id . "\n";
} else {
    echo "User not found.\n";
}

$receptionnistRole = Role::where('role', 'receptionnist')->first();
if ($receptionnistRole) {
    echo "Role 'receptionnist' ID: " . $receptionnistRole->id . "\n";
} else {
    echo "Role 'receptionnist' NOT found in roles table.\n";
}
