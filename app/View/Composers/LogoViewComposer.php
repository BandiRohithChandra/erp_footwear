<?php

namespace App\View\Composers;

use App\Models\Settings;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class LogoViewComposer
{
    public function compose(View $view)
    {
        $logoPath = Settings::get('logo_path');
        // Debug: Log the raw logo_path
        Log::info('LogoViewComposer - Raw logo_path: ' . ($logoPath ?? 'Not set'));
        // Convert the relative logo path to a full URL
        $logoUrl = $logoPath ? asset('storage/' . $logoPath) : null;
        Log::debug('LogoViewComposer - Logo URL: ' . ($logoUrl ?? 'Not set'));
        // Share the logoUrl with the view
        $view->with('logoUrl', $logoUrl);
    }
}