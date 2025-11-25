<?php

namespace App\Http\Controllers;

use App\Models\Settings; // Update to Settings
use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function edit(User $user)
    {
        return view('hr.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [];

        $globalRegion = Settings::get('default_region', 'sa'); // Update to Settings and default_region
        if ($globalRegion === 'sa') {
            $rules['iqama_number'] = 'required|string|max:255';
            $rules['iqama_expiry_date'] = 'required|date';
            $rules['health_card_number'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        $user->update([
            'iqama_number' => $validated['iqama_number'] ?? null,
            'iqama_expiry_date' => $validated['iqama_expiry_date'] ?? null,
            'health_card_number' => $validated['health_card_number'] ?? null,
        ]);

        return redirect()->route('users.edit', $user)->with('success', __('Profile updated successfully!'));
    }
}