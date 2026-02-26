<?php
require 'C:/Users/MAWUSSI/florencia-backend/vendor/autoload.php';
$app = require_once 'C:/Users/MAWUSSI/florencia-backend/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    $role = Role::where('role', 'receptionnist')->first();
    
    if (!$role) {
        die("Error: Role 'receptionnist' not found. Please run add_receptionnist_role.php first.\n");
    }

    $email = 'reception@florencia.com';
    $user = User::where('email', $email)->first();

    if ($user) {
        $user->update([
            'password' => Hash::make('florencia2025'),
            'role_id' => $role->id
        ]);
        echo "Receptionist account updated successfully.\n";
    } else {
        User::create([
            'nom' => 'Réception Florencia',
            'email' => $email,
            'password' => Hash::make('florencia2025'),
            'role_id' => $role->id,
            'tel' => '+225 00000000'
        ]);
        echo "Receptionist account created successfully.\n";
    }
    
    echo "Email: $email\n";
    echo "Password: florencia2025\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
