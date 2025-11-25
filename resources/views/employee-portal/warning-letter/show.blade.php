@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('Warning Letter Details') }}</h1>

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow space-y-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-700">{{ __('Issue Date') }}</h2>
                <p class="text-gray-600">{{ $warningLetter->created_at->format('Y-m-d') }}</p>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-700">{{ __('Reason') }}</h2>
                <p class="text-gray-600">{{ $warningLetter->reason }}</p>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-700">{{ __('Description') }}</h2>
                <p class="text-gray-600">{{ $warningLetter->description ?? 'No additional description provided.' }}</p>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-700">{{ __('Issued By') }}</h2>
                <p class="text-gray-600">{{ $warningLetter->issuer->name ?? 'HR Department' }}</p>
            </div>
            <div>
                <a href="{{ route('employee-portal.index') }}" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    {{ __('Back to Employee Portal') }}
                </a>
            </div>
        </div>
    </div>
@endsection