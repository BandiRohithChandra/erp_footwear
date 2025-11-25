<?php

use App\Models\Settings;

Route::get('/settings/region', function () {
    return response()->json(['region' => Settings::get('default_region', 'sa')]);
});


