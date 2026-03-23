<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Investment;
use App\Models\Portfolio;
use App\Models\Transaction;

echo "--- INVESTMENTS ---\n";
foreach (Investment::all() as $i) {
    echo "ID: {$i->id} | Symbol: {$i->symbol} | Qty: " . ($i->lots ?? $i->quantity) . " | Type: {$i->asset_type}\n";
}

echo "\n--- PORTFOLIOS ---\n";
foreach (Portfolio::all() as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | Balance: {$p->balance}\n";
}

echo "\n--- LATEST TRANSACTIONS ---\n";
foreach (Transaction::all()->sortByDesc('created_at')->take(5) as $t) {
    echo "ID: {$t->id} | Type: {$t->type} | Amount: {$t->amount} | Note: {$t->note}\n";
}
