<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Schedule the financial report generation and email daily at 9:00 AM IST
        $schedule->call(function () {
            app(\App\Http\Controllers\FinanceController::class)->generateScheduledReport();
        })->dailyAt('09:00')->timezone('Asia/Kolkata');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}