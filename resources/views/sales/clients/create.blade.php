<!-- resources/views/sales/clients/create.blade.php -->
@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">{{ __('Add Client') }}</h1>

<!-- Back Button -->
<a href="{{ url()->previous() }}" class="back-btn">
    <span class="back-icon">←</span> Back
</a>

<style>
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(90deg, #9747FF 0%, #92D3F5 100%);
    color: #fff;
    font-weight: 600;
    font-size: 16px;
    border-radius: 30px;
    text-decoration: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    margin-bottom: 25px;
}

.back-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
    background: linear-gradient(90deg, #92D3F5 0%, #9747FF 100%);
}

.back-icon {
    font-size: 18px;
}
</style>

@if (session('success'))
    <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
        {{ session('success') }}
    </div>
@endif

@php
    $isOfflineAdmin = auth()->user()->is_remote === 0;
@endphp

<form method="POST" action="{{ route('clients.store') }}" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow space-y-4">
    @csrf

    <!-- Business/Company Name -->
    <div>
        <label for="business_name" class="block text-gray-700 font-medium mb-2">Business/Company Name</label>
        <input type="text" id="business_name" name="business_name" value="{{ old('business_name') }}" class="w-full p-2 border rounded-lg" {{ $isOfflineAdmin ? '' : 'required' }}>
        @error('business_name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Contact Person Name -->
    <div>
        <label for="name" class="block text-gray-700 font-medium mb-2">Contact Person Name</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full p-2 border rounded-lg">
        @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Email -->
<div>
    <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>

    <!-- Dummy hidden inputs prevent Chrome autofill -->
    <input type="text" name="fakeemail" style="display:none">
    <input type="password" name="fakepassword" style="display:none">

    <input 
        type="email" 
        id="email"  
        name="email" 
        value="{{ old('email') }}" 
        autocomplete="off" 
        autocapitalize="none" 
        spellcheck="false"
        class="w-full p-2 border rounded-lg">

    @error('email')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

    <!-- Assign Sales Rep -->
    <div>
        <label for="sales_rep_id" class="block text-gray-700 font-medium mb-2">Assign Sales Rep</label>
        <select id="sales_rep_id" name="sales_rep_id" class="w-full p-2 border rounded-lg" >
            <option value="">-- Select Sales Rep --</option>
            @foreach($salesReps as $rep)
                <option value="{{ $rep->id }}" {{ old('sales_rep_id') == $rep->id ? 'selected' : '' }}>{{ $rep->name }}</option>
            @endforeach
        </select>
        @error('sales_rep_id')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Phone -->
    <div>
        <label for="phone" class="block text-gray-700 font-medium mb-2">Phone</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="w-full p-2 border rounded-lg">
        @error('phone')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Address -->
    <div>
        <label for="address" class="block text-gray-700 font-medium mb-2">Address</label>
        <textarea id="address" name="address" class="w-full p-2 border rounded-lg" {{ $isOfflineAdmin ? '' : '' }}>{{ old('address') }}</textarea>
        @error('address')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Company Document -->
    <div>
        <label class="block text-gray-700 font-medium mb-2">Upload Company Board/Image or Electricity Bill</label>
        <input type="file" name="company_document" class="w-full p-2 border rounded-lg" accept="image/*,.pdf" {{ $isOfflineAdmin ? '' : '' }}>
        @error('company_document')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Aadhar Number -->
    <div>
        <label for="aadhar_number" class="block text-gray-700 font-medium mb-2">Aadhar Number</label>
        <input type="text" id="aadhar_number" name="aadhar_number" value="{{ old('aadhar_number') }}" class="w-full p-2 border rounded-lg" {{ $isOfflineAdmin ? '' : '' }}>
        @error('aadhar_number')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Aadhar Certificate -->
    <div>
        <label class="block text-gray-700 font-medium mb-2">Upload Aadhar Certificate</label>
        <input type="file" name="aadhar_certificate" class="w-full p-2 border rounded-lg" accept="image/*,.pdf" {{ $isOfflineAdmin ? '' : '' }}>
        @error('aadhar_certificate')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- GST No -->
    <div>
        <label for="gst_no" class="block text-gray-700 font-medium mb-2">GST No</label>
        <input type="text" id="gst_no" name="gst_no" value="{{ old('gst_no') }}" class="w-full p-2 border rounded-lg" {{ $isOfflineAdmin ? '' : 'required' }}>
        @error('gst_no')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- GST Certificate -->
    <div>
        <label class="block text-gray-700 font-medium mb-2">Upload GST Certificate</label>
        <input type="file" name="gst_certificate" class="w-full p-2 border rounded-lg" accept="image/*,.pdf" {{ $isOfflineAdmin ? '' : 'required' }}>
        @error('gst_certificate')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Electricity Certificate -->
    <div>
        <label class="block text-gray-700 font-medium mb-2">Upload Electricity Bill</label>
        <input type="file" name="electricity_certificate" class="w-full p-2 border rounded-lg" accept="image/*,.pdf" {{ $isOfflineAdmin ? '' : '' }}>
        @error('electricity_certificate')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Category -->
    <div>
        <label for="category" class="block text-gray-700 font-medium mb-2">Category</label>
        <select id="category" name="category" class="w-full p-2 border rounded-lg" {{ $isOfflineAdmin ? '' : 'required' }}>
            <option value="">-- Select Category --</option>
            <option value="wholesale" {{ old('category') == 'wholesale' ? 'selected' : '' }}>Wholesale</option>
            <option value="retail" {{ old('category') == 'retail' ? 'selected' : '' }}>Retail</option>
        </select>
        @error('category')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

   <!-- Password -->
<div>
    <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
    <input 
        type="password" 
        id="password" 
        name="password" 
        autocomplete="new-password"
        class="w-full p-2 border rounded-lg">
    @error('password')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>



    <div>
        <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full p-2 border rounded-lg" {{ $isOfflineAdmin ? '' : '' }}>
        @error('password_confirmation')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Submit Buttons -->
    <div class="flex space-x-4">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Add Client</button>
        <a href="{{ route('clients.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Cancel</a>
    </div>
</form>
@endsection
