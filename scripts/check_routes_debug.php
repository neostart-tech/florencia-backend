<?php
require 'C:/Users/MAWUSSI/florencia-backend/vendor/autoload.php';
$app = require_once 'C:/Users/MAWUSSI/florencia-backend/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Route;

echo "Listing API routes:\n";
$routes = Route::getRoutes();

foreach ($routes as $route) {
    if (str_contains($route->uri(), 'login')) {
        echo "Method: " . implode('|', $route->methods()) . " | URI: " . $route->uri() . "\n";
    }
}
