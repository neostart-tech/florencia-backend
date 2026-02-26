<?php
require 'C:/Users/MAWUSSI/florencia-backend/vendor/autoload.php';
$app = require_once 'C:/Users/MAWUSSI/florencia-backend/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use Illuminate\Support\Str;

try {
    $r = new Role();
    $r->id = (string) Str::uuid();
    $r->role = 'personnel';
    $r->save();
    echo "Role personnel created.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
