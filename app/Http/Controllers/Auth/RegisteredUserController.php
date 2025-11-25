<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password; // <-- Correct import
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

   public function store(Request $request): RedirectResponse
{
    // Validate inputs
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'confirmed', Password::defaults()],

        'phone' => ['required', 'string', 'max:20'],
        'address' => ['required', 'string', 'max:255'],
        'category' => ['required', 'in:retail,wholesale'],

        'business_name' => ['required', 'string', 'max:255'],
        'company_document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png'],
        'gst_no' => ['required', 'string', 'max:255'],
        'gst_certificate' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png'],
        'aadhar_number' => ['required', 'string', 'max:255'],
        'aadhar_certificate' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png'],
        'electricity_certificate' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png'],
    ]);

    // Handle file uploads
    $companyDocPath = $request->file('company_document')->store('documents/company', 'public');
    $gstCertPath = $request->file('gst_certificate')->store('documents/gst', 'public');
    $aadharCertPath = $request->file('aadhar_certificate')->store('documents/aadhar', 'public');
    $electricityCertPath = $request->file('electricity_certificate')->store('documents/electricity', 'public');

    // Create user with status 'pending'
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),

        'phone' => $request->phone,
        'address' => $request->address,
        'category' => $request->category,

        'business_name' => $request->business_name,
        'company_document' => $companyDocPath,
        'gst_no' => $request->gst_no,
        'gst_certificate' => $gstCertPath,
        'aadhar_number' => $request->aadhar_number,
        'aadhar_certificate' => $aadharCertPath,
        'electricity_certificate' => $electricityCertPath,
        'status' => 'pending', // <-- Set pending by default
    ]);

    // Assign "client" role
    $user->assignRole('client');

    // Fire registered event
    event(new Registered($user));

    // Do NOT log in the user yet
    // Auth::login($user); <-- removed

    // Redirect to Thank You page
    return redirect()->route('auth.thankyou');
}


public function approve(User $client)
{
    $client->update(['status' => 'approved']);
    return redirect()->back()->with('success', 'Client approved successfully.');
}

public function reject(User $client)
{
    $client->update(['status' => 'rejected']);
    return redirect()->back()->with('success', 'Client rejected successfully.');
}




}
