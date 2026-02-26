<?php
require 'C:/Users/MAWUSSI/florencia-backend/vendor/autoload.php';
$app = require_once 'C:/Users/MAWUSSI/florencia-backend/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
try {
    $users = User::with('role')->get();
    echo "Users found: " . $users->count() . "\n";
    foreach($users as $user) {
        $roleName = $user->role ? $user->role->role : 'no role';
        echo "- Name: {$user->nom}, Email: {$user->email}, Role: {$roleName}\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
