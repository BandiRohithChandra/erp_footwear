<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = Session::get('locale', config('app.locale', 'en'));
        App::setLocale($locale);
        \Log::info("SetLocale Middleware - Locale applied: {$locale}, Session locale: " . Session::get('locale'));
        return $next($request);
    }
}