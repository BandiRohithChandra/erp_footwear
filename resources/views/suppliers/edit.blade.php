@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Supplier</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" class="bg-white shadow rounded-lg p-6 space-y-4">
        @csrf
        @method('PUT')

        <!-- Business Name -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Business Name</label>
            <input type="text" name="business_name" value="{{ old('business_name', $supplier->business_name) }}"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Supplier Name -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- What They Supply -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Supplied Materials <span class="text-red-500">*</span></label>
            <input type="text" name="material_types" 
                   value="{{ old('material_types', $supplier->material_types) }}" 
                   placeholder="e.g., Leather, Rubber Soles, Glue, Thread"
                   required
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="text-sm text-gray-500 mt-1">Specify the type(s) of materials this supplier provides.</p>
        </div>

        <!-- Email -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $supplier->email) }}"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Phone -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Address -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Address</label>
            <textarea name="address" 
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('address', $supplier->address) }}</textarea>
        </div>

        <!-- GST Number -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">GST Number</label>
            <input type="text" name="gst_number" value="{{ old('gst_number', $supplier->gst_number) }}"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400">
            Update Supplier
        </button>
    </form>
</div>
@endsection
