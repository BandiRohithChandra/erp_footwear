<?php
namespace App\Helpers;

use NumberFormatter;
use Illuminate\Support\Facades\App;
use App\Models\Settings;

class FormatMoney
{
    public static function format($amount, $region = null, $currency = null)
    {
        // Fetch default region and currency at runtime
        $defaultRegion = $region ?? Settings::get('default_region', config('taxes.default_region', 'in'));
        $currency = Settings::get('default_currency', config('taxes.regions.' . $defaultRegion . '.currency', 'INR'));
        $locale = App::getLocale();

        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
        return $formatter->formatCurrency($amount, $currency);
    }
}