<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switchLang($locale)
    {
        if (in_array($locale, ['en', 'ar'])) {
            Session::put('locale', $locale);
            Session::save(); // Ensure the session is saved immediately
            App::setLocale($locale);
            \Log::info("Language switched to: {$locale}, Session locale: " . Session::get('locale'));
        } else {
            \Log::warning("Invalid locale attempted: {$locale}");
        }
        return redirect()->back();
    }
}