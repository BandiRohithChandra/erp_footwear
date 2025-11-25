@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('Performance Reviews') }}</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- For Managers/HR: Schedule a Review -->
        @if (Auth::user()->hasRole('manager') || Auth::user()->hasRole('hr'))
            <div class="mb-6">
                <a href="{{ route('performance-reviews.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    {{ __('Schedule Performance Review') }}
                </a>
            </div>

            <!-- Manager Reviews -->
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h2 class="text-xl font-bold mb-4">{{ __('Reviews to Conduct') }}</h2>
                @if ($managerReviews->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Employee') }}</th>
                                    <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Review Date') }}</th>
                                    <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Status') }}</th>
                                    <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($managerReviews as $review)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border p-3">{{ $review->employee->name }}</td>
                                        <td class="border p-3">{{ $review->review_date }}</td>
                                        <td class="border p-3">{{ $review->status }}</td>
                                        <td class="border p-3">
                                            <a href="{{ route('performance-reviews.show', $review) }}" class="text-blue-500 hover:underline mr-2">{{ __('View') }}</a>
                                            @if ($review->status === 'scheduled')
                                                <form action="{{ route('performance-reviews.cancel', $review) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-800">{{ __('Cancel') }}</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600">{{ __('No performance reviews scheduled.') }}</p>
                @endif
            </div>
        @endif

        <!-- Employee Reviews -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">{{ __('Your Performance Reviews') }}</h2>
            @if ($employeeReviews->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Review Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Reviewer') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Rating') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Status') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employeeReviews as $review)
                                <tr class="hover:bg-gray-50">
                                    <td class="border p-3">{{ $review->review_date }}</td>
                                    <td class="border p-3">{{ $review->reviewer ? $review->reviewer->name : 'N/A' }}</td>
                                    <td class="border p-3">{{ $review->rating ?? 'N/A' }}</td>
                                    <td class="border p-3">{{ $review->status }}</td>
                                    <td class="border p-3">
                                        <a href="{{ route('performance-reviews.show', $review) }}" class="text-blue-500 hover:underline">{{ __('View Details') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">{{ __('No performance reviews found.') }}</p>
            @endif
        </div>
    </div>
@endsection