<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Console\Command;

class CreateEmployeeRecords extends Command
{
    protected $signature = 'employees:fix-records';
    protected $description = 'Create Employee records for users with access employee portal permission';

    public function handle()
    {
        $users = User::permission('access employee portal')->get();

        foreach ($users as $user) {
            if (!$user->employee) {
                Employee::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email, // Use the user's email from the users table
                    'department' => 'Default Department', // Adjust as needed
                    'position' => 'Employee',
                    'salary' => 0.00, // Default salary; adjust as needed
                    'hire_date' => now()->toDateString(), // Use today's date (2025-06-02)
                ]);
                $this->info("Created Employee record for user: {$user->name} (ID: {$user->id})");
            }
        }

        $this->info('Employee records creation completed.');
    }
}