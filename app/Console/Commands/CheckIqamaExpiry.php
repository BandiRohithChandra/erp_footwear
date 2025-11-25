<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\IqamaExpiryNotification;
use Carbon\Carbon;

class CheckIqamaExpiry extends Command
{
    protected $signature = 'iqama:check-expiry';
    protected $description = 'Check for upcoming Iqama expiries and notify employees and HR';

    public function handle()
    {
        $thresholdDays = 30; // Notify 30 days before expiry
        $today = Carbon::today();

        // Find employees with Iqama expiring soon
        $employees = User::where('region', 'saudi_arabia')
            ->whereNotNull('iqama_expiry_date')
            ->whereBetween('iqama_expiry_date', [$today, $today->copy()->addDays($thresholdDays)])
            ->get();

        foreach ($employees as $employee) {
            $daysUntilExpiry = $employee->iqama_expiry_date->diffInDays($today);
            $employee->notify(new IqamaExpiryNotification($daysUntilExpiry));

            // Notify HR
            $hrUsers = User::role('HR Manager')->get();
            foreach ($hrUsers as $hr) {
                $hr->notify(new IqamaExpiryNotification($daysUntilExpiry));
            }
        }

        $this->info('Iqama expiry notifications sent successfully.');
    }
}