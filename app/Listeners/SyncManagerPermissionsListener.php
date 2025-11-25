<?php

namespace App\Listeners;

use App\Events\RolePermissionsUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Permission\Models\Role;

class SyncManagerPermissionsListener
{
    public function handle(RolePermissionsUpdated $event)
    {
        if ($event->role->name !== 'employee') {
            return;
        }

        $employeeRole = $event->role;
        $managerRole = Role::where('name', 'manager')->first();

        if (!$managerRole) {
            return;
        }

        $employeePermissions = $employeeRole->permissions()->pluck('name')->toArray();
        $managerRole->syncPermissions($employeePermissions);
    }
}