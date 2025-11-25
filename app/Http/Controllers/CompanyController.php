<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Company;

class CompanyController extends Controller
{
    // Only authenticated users can update
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show form (optional, if you want separate route)
    public function edit()
    {
        $company = auth()->user()->company; // assuming user has one company
        return view('profile.partials.company-form', compact('company'));
    }

    // Update company
    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'gst_number'   => 'nullable|string|max:50',
            'pan_number'   => 'nullable|string|max:50',
            'address'      => 'nullable|string|max:500',
            'phone'        => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:255',
            'website'      => 'nullable|url|max:255',
            'logo'         => 'nullable|image|max:2048', // max 2MB
        ]);

        // Get user's company or create new
        $company = auth()->user()->company ?? new Company();
        $company->user_id = auth()->id();
        $company->company_name = $request->company_name;
        $company->gst_number = $request->gst_number;
        $company->pan_number = $request->pan_number;
        $company->address = $request->address;
        $company->phone = $request->phone;
        $company->email = $request->email;
        $company->website = $request->website;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($company->logo) {
                Storage::delete($company->logo); // delete old logo
            }
            $company->logo = $request->file('logo')->store('company_logos', 'public');
        }

        $company->save();

        return redirect()->back()->with('status', 'company-updated');
    }
}
