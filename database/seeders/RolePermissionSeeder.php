<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define Permissions
        $permissions = [
            'manage hr',
            'view hr',
            'manage sales',
            'view sales',
            'manage inventory',
            'view inventory',
            'manage finance',
            'view finance',
            'manage settings',
            'view dashboard',
            'view reports',
            'manage users',
            'view notifications',
            'manage notifications',
            'view production',
            'manage production',
            'view employee portal',
            'access employee portal',
            'access manager portal',
            'view sales dashboard',
            'manage sales dashboard',
            'manage quotations',
            'process production',
            'approve transactions',
            'manage payroll',
        ];

        // Create or update permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define Roles and their Permissions
        $roles = [
            'Admin' => $permissions, // Admin gets all permissions
            'HR Manager' => [
                'manage hr',
                'view hr',
                'view dashboard',
                'view reports',
                'view notifications',
                'manage notifications',
            ],
            'HR Employee' => [
                'view hr',
                'view dashboard',
                'view notifications',
            ],
            'Sales Manager' => [
                'manage sales',
                'view sales',
                'view dashboard',
                'view reports',
                'view notifications',
                'manage notifications',
                'view production',
                'process production',
            ],
            'Sales Employee' => [
                'view sales',
                'view dashboard',
                'view notifications',
            ],
            'Inventory Manager' => [
                'manage inventory',
                'view inventory',
                'view dashboard',
                'view reports',
                'view notifications',
                'manage notifications',
            ],
            'Inventory Employee' => [
                'view inventory',
                'view dashboard',
                'view notifications',
            ],
            'Finance Manager' => [
                'manage finance',
                'view finance',
                'view dashboard',
                'view reports',
                'view notifications',
                'manage notifications',
                'approve transactions', // Added for finance approval
            ],
            'Finance Employee' => [
                'view finance',
                'view dashboard',
                'view notifications',
            ],
            'Employee' => [
                'view production',
                'view employee portal',
                'access employee portal',
                'view notifications',
            ],
            'Manager' => [
                'access manager portal',
                'view hr',
                'manage hr',
                'view notifications',
            ],
            'Accountant' => [
                'view finance',
                'manage finance',
                'approve transactions', // Added for finance approval
                'view notifications',
            ],
        ];

        // Create or update roles and assign permissions
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }

        // Assign roles to users based on email
        $users = [
            'admin@example.com' => 'Admin',
            'offline_admin@example.com' => 'Admin',
            'hr_manager@example.com' => 'HR Manager',
            'hr_employee@example.com' => 'HR Employee',
            'sales_manager@example.com' => 'Sales Manager',
            'sales_employee@example.com' => 'Sales Employee',
            'inventory_manager@example.com' => 'Inventory Manager',
            'inventory_employee@example.com' => 'Inventory Employee',
            'finance_manager@example.com' => 'Finance Manager',
            'finance_employee@example.com' => 'Finance Employee',
            'accountant@example.com' => 'Accountant',
        ];

        foreach ($users as $email => $roleName) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->syncRoles([$roleName]);
            }
        }
    }
}