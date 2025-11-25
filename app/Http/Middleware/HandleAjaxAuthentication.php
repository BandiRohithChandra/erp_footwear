<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HandleAjaxAuthentication
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->ajax() && !Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        return $next($request);
    }
}