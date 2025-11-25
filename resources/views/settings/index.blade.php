@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-lg p-8 mt-6">
        <h1 class="text-3xl font-extrabold text-gray-800 mb-8 flex items-center gap-3">
            ⚙️ <span>Settings Panel</span>
        </h1>

        {{-- ✅ Alerts --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-300 text-green-800 p-4 rounded-lg mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-300 text-red-800 p-4 rounded-lg mb-6">
                ❌ {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-700 p-4 rounded-lg mb-6">
                <strong>Validation Errors:</strong>
                <ul class="list-disc ml-6 mt-2 space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ✅ Reset Data --}}
        <div class="bg-gradient-to-r from-orange-50 to-yellow-50 border border-yellow-200 p-6 rounded-xl mb-10">
            <h2 class="text-lg font-semibold text-yellow-700 mb-2">Data Management</h2>
            <p class="text-sm text-gray-600 mb-4">Use this section to manage or reset your ERP system data. This action is
                irreversible, so proceed with caution.</p>

            <form method="POST" action="{{ route('settings.reset-data') }}"
                onsubmit="return confirm('⚠️ Are you sure? This will permanently erase all ERP data!')"
                class="flex flex-col sm:flex-row sm:items-center gap-3">
                @csrf
                <div>
                    <label class="text-sm text-gray-700">Type <strong>RESET</strong> to confirm:</label>
                    <input type="text" name="confirm" required
                        class="ml-2 px-3 py-2 border rounded-lg border-gray-300 focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                </div>
                <button type="submit"
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-5 py-2 rounded-lg font-semibold shadow transition">
                    Reset ERP Data
                </button>
            </form>
        </div>


        {{-- ✅ Tabs --}}
        <div class="border-b border-gray-200 mb-8">
            <nav class="-mb-px flex space-x-8 text-sm font-medium" aria-label="Tabs">
                <a href="{{ route('settings.index') }}"
                    class="px-3 py-3 border-b-2 {{ request()->routeIs('settings.index') ? 'border-indigo-600 text-indigo-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    ⚙️ General
                </a>
                @role('super_admin')
                <a href="{{ route('settings.roles') }}"
                    class="px-3 py-3 border-b-2 {{ request()->routeIs('settings.roles') ? 'border-indigo-600 text-indigo-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    👥 Roles
                </a>
                @endrole
                @can('manage settings')
                    <a href="{{ route('settings.activity') }}"
                        class="px-3 py-3 border-b-2 {{ request()->routeIs('settings.activity') ? 'border-indigo-600 text-indigo-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        📜 Activity Log
                    </a>
                    <a href="{{ route('settings.backup') }}"
                        class="px-3 py-3 border-b-2 {{ request()->routeIs('settings.backup') ? 'border-indigo-600 text-indigo-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        💾 Backup & Restore
                    </a>
                @endcan
            </nav>
        </div>

        {{-- ✅ Main Settings Section --}}
        <div class="grid md:grid-cols-2 gap-10">

          {{-- 🌍 General Settings --}}
<div class="p-6 bg-gradient-to-br from-indigo-50 to-white border rounded-xl shadow-sm">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">🌍 General Settings</h2>

    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Company Name --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Company Name</label>
            <input type="text" name="company_name"
                value="{{ \App\Models\Settings::get('company_name') }}"
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500">
        </div>

        {{-- Company Address --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Company Address</label>
            <textarea name="company_address" rows="2"
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500">{{ \App\Models\Settings::get('company_address') }}</textarea>
        </div>

        {{-- GST Number --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">GST Number</label>
            <input type="text" name="company_gst"
                value="{{ \App\Models\Settings::get('company_gst') }}"
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500">
        </div>

        {{-- Phone Number --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Phone Number</label>
            <input type="text" name="company_phone"
                value="{{ \App\Models\Settings::get('company_phone') }}"
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500">
        </div>

        {{-- Email ID --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Email ID</label>
            <input type="email" name="company_email"
                value="{{ \App\Models\Settings::get('company_email') }}"
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500">
        </div>

        {{-- Website --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Website</label>
            <input type="text" name="company_website"
                value="{{ \App\Models\Settings::get('company_website') }}"
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500">
        </div>

        {{-- Company Logo --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Company Logo</label>

            @php
                $logo = \App\Models\Settings::get('company_logo');
            @endphp

            @if($logo)
                <div class="mb-3">
                    <p class="text-gray-600 mb-1">Current Logo:</p>
                    <img src="{{ asset($logo) }}" class="h-16 w-auto rounded shadow">
                </div>
            @endif

            <input type="file" name="company_logo" accept="image/*"
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500">
            <small class="text-gray-500">Upload PNG/JPG/SVG (max 2MB)</small>
        </div>

        {{-- Region --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Default Region</label>
            <select name="default_region"
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500" required>
                @foreach ($regions as $code => $region)
                    <option value="{{ $code }}" {{ $defaultRegion === $code ? 'selected' : '' }}>
                        {{ $region['name'] }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Default Currency --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Default Currency</label>
            <div class="mt-1 text-gray-700 font-semibold text-base">{{ $defaultCurrency }}</div>
            <small class="text-gray-500">Currency updates automatically based on region.</small>
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg shadow flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            Update Settings
        </button>
    </form>
</div>


            {{-- 🖼️ Logo Upload --}}
            <div class="p-6 bg-gradient-to-br from-gray-50 to-white border rounded-xl shadow-sm">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">🖼️ Update Logo</h2>

                @if ($logoUrl)
                    <div class="mb-4">
                        <p class="text-gray-600 mb-2">Current Logo:</p>
                        <img src="{{ $logoUrl }}" alt="ERP Logo" class="h-16 w-auto rounded shadow-sm">
                    </div>
                @else
                    <p class="text-gray-500 mb-4">No logo uploaded yet.</p>
                @endif

                <!-- <form method="POST" action="{{ route('settings.update-logo') }}" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf
                    <input type="file" name="logo" accept="image/*"
                        class="block w-full text-sm border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500"
                        required>
                    @error('logo') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium shadow">
                        Upload Logo
                    </button>
                </form> -->
            </div>

            {{-- 🏦 Bank Details --}}
            <div class="md:col-span-2 p-6 bg-gradient-to-br from-green-50 to-white border rounded-xl shadow-sm mt-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">🏦 Bank Details</h2>

                <form method="POST" action="{{ route('settings.update-bank') }}" class="grid md:grid-cols-2 gap-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="bank_name" class="block text-sm font-medium text-gray-700">Bank Name</label>
                        <input type="text" name="bank_name" id="bank_name"
                            value="{{ old('bank_name', $bankDetails->bank_name ?? '') }}"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500"
                            required>
                    </div>

                    <div>
                        <label for="branch_name" class="block text-sm font-medium text-gray-700">Branch Name</label>
                        <input type="text" name="branch_name" id="branch_name"
                            value="{{ old('branch_name', $bankDetails->branch_name ?? '') }}"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500"
                            required>
                    </div>

                    <div>
                        <label for="account_holder" class="block text-sm font-medium text-gray-700">Account Holder
                            Name</label>
                        <input type="text" name="account_holder" id="account_holder"
                            value="{{ old('account_holder', $bankDetails->account_holder ?? '') }}"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500"
                            required>
                    </div>

                    <div>
                        <label for="account_number" class="block text-sm font-medium text-gray-700">Account Number</label>
                        <input type="text" name="account_number" id="account_number"
                            value="{{ old('account_number', $bankDetails->account_number ?? '') }}"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500"
                            required>
                    </div>

                    <div>
                        <label for="ifsc_code" class="block text-sm font-medium text-gray-700">IFSC Code</label>
                        <input type="text" name="ifsc_code" id="ifsc_code"
                            value="{{ old('ifsc_code', $bankDetails->ifsc_code ?? '') }}"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500"
                            required>
                    </div>

                    <div>
                        <label for="upi_id" class="block text-sm font-medium text-gray-700">UPI ID (optional)</label>
                        <input type="text" name="upi_id" id="upi_id" value="{{ old('upi_id', $bankDetails->upi_id ?? '') }}"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500">
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit"
                            class="mt-3 inline-flex items-center px-5 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Update Bank Details
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection