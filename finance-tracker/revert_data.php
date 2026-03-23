<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Investment;
use App\Models\Portfolio;
use App\Models\Transaction;

// 1. USDT Revert
$inv = Investment::where('symbol', 'USDT-USD')->first();
if ($inv) {
    echo "Restoring Investment {$inv->symbol} quantity to 142.6163\n";
    $inv->quantity = 142.6163;
    $inv->save();
}

// 2. BCA Revert
$port = Portfolio::where('name', 'BCA')->first();
if ($port) {
    echo "Restoring Portfolio {$port->name} balance to 21345899.20\n";
    $port->balance = 21345899.20;
    $port->save();
}

// 3. Delete latest USDT sale transaction
$trans = Transaction::where('note', 'like', '%USDT-USD%')->latest()->first();
if ($trans) {
    echo "Deleting Transaction: {$trans->note}\n";
    $trans->delete();
}

echo "--- Revert Completed ---\n";
