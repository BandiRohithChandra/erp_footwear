<?php

namespace App\Observers;

use App\Models\Employee;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class UserObserver
{
    public function updated(User $user)
    {
        // Check if the user has the 'access employee portal' permission
        if ($user->hasPermissionTo('access employee portal') && !$user->employee) {
            // Create an Employee record for the user
            Employee::create([
                'user_id' => $user->id,
                'department_id' => 1, // Default department; adjust as needed
                'position' => 'Employee', // Default position; adjust as needed
            ]);
        }
    }
}