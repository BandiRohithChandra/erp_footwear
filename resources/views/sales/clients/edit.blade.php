@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">{{ __('Edit Client') }}</h1>

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

    <form method="POST" action="{{ route('clients.update', $client) }}" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="business_name" class="block text-gray-700 font-medium mb-2">Business/Company Name</label>
            <input type="text" id="business_name" name="business_name" 
                value="{{ old('business_name', $client->business_name) }}" 
                class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('business_name') border-red-500 @enderror" required>
            @error('business_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="name" class="block text-gray-700 font-medium mb-2">Contact Person Name</label>
            <input type="text" id="name" name="name" 
                value="{{ old('name', $client->name) }}" 
                class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
            <input type="email" id="email" name="email" 
                value="{{ old('email', $client->email) }}" 
                class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror" required>
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="phone" class="block text-gray-700 font-medium mb-2">Phone</label>
            <input type="text" id="phone" name="phone" 
                value="{{ old('phone', $client->phone) }}" 
                class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror" required>
            @error('phone')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="address" class="block text-gray-700 font-medium mb-2">Address</label>
            <textarea id="address" name="address" 
                class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror" required>{{ old('address', $client->address) }}</textarea>
            @error('address')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Company Document -->
        <div>
            <label class="block text-gray-700 font-medium mb-2">Company Board/Image or Electricity Bill</label>
            
            @if($client->company_document)
                <p class="mb-2">
                    Current Document: 
                    <a href="{{ asset('storage/' . $client->company_document) }}" 
                       target="_blank" class="text-blue-600 underline">
                       View / Download
                    </a>
                </p>
            @endif

            <input type="file" name="company_document" 
                   class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('company_document') border-red-500 @enderror" 
                   accept="image/*,.pdf">
            <p class="text-sm text-gray-500">Leave empty if you don't want to replace the file.</p>
            @error('company_document')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="gst_no" class="block text-gray-700 font-medium mb-2">GST No</label>
            <input type="text" id="gst_no" name="gst_no" 
                value="{{ old('gst_no', $client->gst_no) }}" 
                class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('gst_no') border-red-500 @enderror">
            @error('gst_no')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="category" class="block text-gray-700 font-medium mb-2">Category</label>
            <select id="category" name="category" 
                class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('category') border-red-500 @enderror" required>
                <option value="">-- Select Category --</option>
                <option value="wholesale" {{ old('category', $client->category) == 'wholesale' ? 'selected' : '' }}>Wholesale</option>
                <option value="retail" {{ old('category', $client->category) == 'retail' ? 'selected' : '' }}>Retail</option>
            </select>
            @error('category')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
    <label for="password" class="block text-gray-700 font-medium mb-2">New Password (leave blank to keep current)</label>
    <input type="password" id="password" name="password" class="w-full p-2 border rounded-lg">
    @error('password')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">Confirm New Password</label>
    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full p-2 border rounded-lg">
</div>


        <div class="flex space-x-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Update Client</button>
            <a href="{{ route('clients.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Cancel</a>
        </div>
    </form>
@endsection
