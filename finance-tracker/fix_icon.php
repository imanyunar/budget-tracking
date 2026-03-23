<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = \App\Models\Portfolio::where('name', 'Cash')->first();
if ($p) {
    echo "Updating Cash from {$p->icon} to banknote\n";
    $p->icon = 'banknote';
    $p->save();
}
