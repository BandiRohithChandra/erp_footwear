<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    /**
     * Mark the onboarding card as seen for the logged-in user
     */
    public function seen(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->seen_onboarding = true;
            $user->save();
        }

        return response()->json(['status' => 'ok']);
    }
}
