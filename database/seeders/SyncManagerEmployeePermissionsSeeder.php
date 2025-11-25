<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SyncManagerEmployeePermissionsSeeder extends Seeder
{
    public function run()
    {
        // Ensure the Employee role exists
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);

        // Ensure the Manager role exists
        $managerRole = Role::firstOrCreate(['name' => 'manager']);

        // Ensure the 'access employee portal' permission exists and is assigned to Employee
        $accessEmployeePortal = Permission::firstOrCreate(['name' => 'access employee portal']);
        $employeeRole->givePermissionTo($accessEmployeePortal);

        // Get all permissions assigned to the Employee role
        $employeePermissions = $employeeRole->permissions()->pluck('name')->toArray();

        // Assign all Employee permissions to the Manager role
        $managerRole->syncPermissions($employeePermissions);

        // Ensure Manager has additional permissions (if not already assigned)
        $managerPermissions = [
            'manage hr',
            'view hr',
            'manage finance',
            'view finance',
            'manage inventory',
            'view inventory',
            'manage sales',
            'view sales',
            'manage users',
            'manage settings',
            'view reports',
            'view dashboard',
            'view notifications',
            'manage sales',
            'manage productions',
        ];

        foreach ($managerPermissions as $permission) {
            $perm = Permission::firstOrCreate(['name' => $permission]);
            $managerRole->givePermissionTo($perm);
        }
    }
}