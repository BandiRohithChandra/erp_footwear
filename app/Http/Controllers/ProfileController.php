<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\PasswordReset;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile and company information.
     */
    public function update(Request $request)
{
    $user = auth()->user();

    // Profile fields
    $user->name = $request->name ?? $user->name;
    $user->phone = $request->phone ?? $user->phone;
    $user->address = $request->address ?? $user->address;
    if ($request->hasFile('profile_picture')) {
        $user->profile_picture = $request->file('profile_picture')->store('profiles');
    }

    // Company fields
    $user->business_name = $request->business_name ?? $user->business_name;
    if ($request->hasFile('company_document')) {
        $user->company_document = $request->file('company_document')->store('company_docs');
    }
    $user->gst_no = $request->gst_no ?? $user->gst_no;
    $user->category = $request->category ?? $user->category;
    $user->website = $request->website ?? $user->website;
    $user->contact_person = $request->contact_person ?? $user->contact_person;
    $user->state = $request->state ?? $user->state;

    $user->designation = $request->designation ?? $user->designation;
    $user->alt_email = $request->alt_email ?? $user->alt_email;
    $user->alt_phone = $request->alt_phone ?? $user->alt_phone;

    $user->save();

    // ✅ Flash message
    return redirect()->back()->with('success', 'Profile updated successfully!');
}


    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->save();

        event(new PasswordReset($user));

        return redirect()->route('profile.edit')
            ->with('status', 'password-updated')
            ->with('success', __('Password updated successfully.'));
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', __('Your account has been deleted.'));
    }

    /**
     * Send email verification notification.
     */
    public function sendVerificationNotification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent')
            ->with('success', __('A new verification link has been sent to your email address.'));
    }
}
