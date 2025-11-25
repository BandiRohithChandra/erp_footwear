<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;

class SyncEmployeesWithUsersSeeder extends Seeder
{
    public function run()
    {
        // Find employees with no associated user
        $employees = Employee::whereNull('user_id')->get();

        foreach ($employees as $employee) {
            // Check if a user with this email already exists
            $user = User::where('email', $employee->email)->first();

            if (!$user) {
                // Create a new user for the employee
                $user = User::create([
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'password' => bcrypt('password'),  // Default password
                    'is_remote' => false,
                ]);
            }

            // Assign the employee role
            $user->assignRole('employee');

            // Update the employee's user_id
            $employee->update(['user_id' => $user->id]);
        }
    }
}