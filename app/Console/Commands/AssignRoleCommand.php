<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignRoleCommand extends Command
{
    protected $signature = 'user:assign-role {email} {role}';
    protected $description = 'Assign a role to a user by email';

    public function handle()
    {
        $email = $this->argument('email');
        $roleName = $this->argument('role');

        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }

        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            $this->error("Role {$roleName} not found.");
            return 1;
        }

        $user->assignRole($role);
        $this->info("Role {$roleName} assigned to user {$email} successfully!");
        return 0;
    }
}