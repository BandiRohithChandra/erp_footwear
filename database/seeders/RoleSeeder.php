<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // List of roles to create
        $roles = [
            'client',
            // Add other roles if needed, e.g., 'admin', 'employee', etc.
        ];

        foreach ($roles as $roleName) {
            // Check if the role already exists for the 'web' guard
            if (!Role::findByName($roleName, 'web')) {
                Role::create(['name' => $roleName]);
            }
        }
    }
}