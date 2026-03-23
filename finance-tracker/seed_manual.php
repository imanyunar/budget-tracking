<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user_id = 1;

// Clear existing wallets and investments for user 1
\App\Models\Portfolio::where('user_id', $user_id)->delete();
\App\Models\Investment::where('user_id', $user_id)->delete();

// Recreate Wallets
\App\Models\Portfolio::create([
    'user_id' => $user_id, 'name' => 'BCA', 'balance' => 31000, 'currency' => 'IDR', 'icon' => 'wallet', 'color' => '#3b82f6'
]);
\App\Models\Portfolio::create([
    'user_id' => $user_id, 'name' => 'Cash', 'balance' => 0, 'currency' => 'IDR', 'icon' => 'banknotes', 'color' => '#10b981'
]);

// Recreate Investments
\App\Models\Investment::create([
    'user_id' => $user_id, 'asset_type' => 'crypto', 'symbol' => 'USDT', 'name' => 'USDT', 'lots' => 0, 'quantity' => 142.6163107, 'average_price' => 16900
]);
\App\Models\Investment::create([
    'user_id' => $user_id, 'asset_type' => 'stock', 'symbol' => 'BUMI.JK', 'name' => 'BUMI.JK', 'lots' => 4, 'quantity' => 0, 'average_price' => 463.69
]);
\App\Models\Investment::create([
    'user_id' => $user_id, 'asset_type' => 'stock', 'symbol' => 'INET.JK', 'name' => 'INET.JK', 'lots' => 1, 'quantity' => 0, 'average_price' => 540.81
]);

echo "Manual data seeded successfully!\n";
