<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule withdrawal processing twice monthly (1st and 16th)
Schedule::command('withdrawals:process')
    ->monthlyOn(1, '09:00')
    ->description('Process scheduled withdrawals on 1st of month');

Schedule::command('withdrawals:process')
    ->monthlyOn(16, '09:00')
    ->description('Process scheduled withdrawals on 16th of month');

// Schedule daily commission generation and returns processing
Schedule::command('commissions:generate')
    ->daily()
    ->at('02:00')
    ->description('Generate daily commissions');

Schedule::command('returns:process-daily')
    ->daily()
    ->at('03:00')
    ->description('Process daily investment returns');
