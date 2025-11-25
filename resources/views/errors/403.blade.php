<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Unauthorized') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
            <h1 class="text-2xl font-bold text-red-600 mb-4">{{ __('Unauthorized Access') }}</h1>
            <p class="text-gray-700 mb-6">{{ __('You do not have permission to access this page.') }}</p>
            <a href="{{ route('dashboard') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">{{ __('Back to Dashboard') }}</a>
        </div>
    </div>
</body>
</html>