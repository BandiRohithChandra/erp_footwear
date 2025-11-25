<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\RolePermissionsUpdated;
use App\Listeners\SyncManagerPermissionsListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        RolePermissionsUpdated::class => [
            SyncManagerPermissionsListener::class,
        ],
    ];

    public function boot()
    {
        User::observe(UserObserver::class);
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}