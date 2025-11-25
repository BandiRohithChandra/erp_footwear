<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Exceptions\UnauthorizedException;

class DebugPermissionMiddleware
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = $request->user();
        $hasPermission = $user ? $user->hasPermissionTo($permission) : false;
        Log::info('Permission check', [
            'user' => $user ? $user->email : 'Guest',
            'permission' => $permission,
            'has_permission' => $hasPermission,
            'path' => $request->path(),
        ]);

        if ($hasPermission) {
            return $next($request);
        }

        throw UnauthorizedException::forPermissions([$permission]);
    }
}