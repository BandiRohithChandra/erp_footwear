<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all authenticated users to update their profile
    }

    public function rules()
    {
        return [
            // Profile fields
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $this->user()->id],
            'profile_picture' => ['nullable', 'image', 'max:2048'], // Max 2MB

            // Company fields
            'business_name' => ['nullable', 'string', 'max:255'],
            'company_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // Max 5MB
            'gst_no' => ['nullable', 'string', 'max:15'],
            'category' => ['nullable', 'in:wholesale,retail'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
            'alt_phone' => ['nullable', 'string', 'max:20'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'alt_email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
        ];
    }
}
