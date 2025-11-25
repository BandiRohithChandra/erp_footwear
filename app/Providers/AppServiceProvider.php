<?php

namespace App\Providers;

use App\Models\Settings;
use App\View\Composers\LogoViewComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    // Set the application locale
    $locale = Session::get('locale', config('app.locale', 'en'));
    App::setLocale($locale);

    // Share the logo path with all views that use layouts.app
    View::composer('layouts.app', LogoViewComposer::class);

    View::composer('layouts.app', function ($view) {
        // Logo path logic
        $logoPath = Settings::get('logo_path');
        if ($logoPath) {
            if (Storage::disk('public')->exists($logoPath)) {
                $url = Storage::disk('public')->url($logoPath);
                $view->with('logoPath', $url);
            } else {
                Log::warning('AppServiceProvider - Logo file does not exist at path: ' . $logoPath);
                $view->with('logoPath', null);
            }
        } else {
            $view->with('logoPath', null);
        }

        // Unread notifications count logic
        $unreadCount = 0;
        if (Auth::check()) {
            $unreadCount = Auth::user()->notifications()->whereNull('read_at')->count();
        }
        $view->with('unreadCount', $unreadCount);
    });
}

}