<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\InvestmentPackage;
use App\Models\Investment;

$package = InvestmentPackage::first();
echo "Package: {$package->name}" . PHP_EOL;
echo "Return Days: {$package->return_days}" . PHP_EOL;
echo "Daily Return %: {$package->daily_return_percentage}" . PHP_EOL;

$investment = Investment::first();
echo "Investment Start Date: {$investment->start_date}" . PHP_EOL;
echo "Investment End Date: {$investment->end_date}" . PHP_EOL;
echo "Investment Amount: {$investment->amount}" . PHP_EOL;
echo "Daily Return Amount: {$investment->daily_return}" . PHP_EOL;

echo PHP_EOL . "Current Date: " . now()->format('Y-m-d') . PHP_EOL;
echo "Expected Return Start: {$investment->start_date}" . PHP_EOL;
echo "Expected Return End: {$investment->end_date}" . PHP_EOL;
