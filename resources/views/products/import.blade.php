@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-xl p-8 bg-gray-100 rounded-2xl shadow-lg mt-12">
    <!-- Header -->
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-semibold text-gray-800 mb-2">Import Articles</h1>
        <p class="text-gray-500 text-sm">Upload a CSV file to bulk import your articles</p>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Import Form -->
    <form action="{{ route('products.import.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <label class="block text-gray-700 font-medium">Select CSV File</label>
        <input type="file" name="file" accept=".csv"
               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition duration-200">

        <div class="flex justify-between items-center">
            <a href="{{ route('products.index') }}"
               class="px-5 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
               Cancel
            </a>
            <button type="submit"
                    class="px-5 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-md">
                Import CSV
            </button>
        </div>
    </form>
</div>
@endsection
