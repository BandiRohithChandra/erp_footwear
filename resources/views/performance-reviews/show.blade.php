@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('Performance Review Details') }}</h1>

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

        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Review Information') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-700"><strong>{{ __('Employee') }}:</strong> {{ $performanceReview->employee->name }}</p>
                    <p class="text-gray-700"><strong>{{ __('Reviewer') }}:</strong> {{ $performanceReview->reviewer ? $performanceReview->reviewer->name : 'N/A' }}</p>
                    <p class="text-gray-700"><strong>{{ __('Review Date') }}:</strong> {{ $performanceReview->review_date }}</p>
                    <p class="text-gray-700"><strong>{{ __('Status') }}:</strong> {{ $performanceReview->status }}</p>
                </div>
                <div>
                    <p class="text-gray-700"><strong>{{ __('Rating') }}:</strong> {{ $performanceReview->rating ?? 'N/A' }}</p>
                    <p class="text-gray-700"><strong>{{ __('Feedback') }}:</strong> {{ $performanceReview->feedback ?? 'N/A' }}</p>
                    <p class="text-gray-700"><strong>{{ __('Goals') }}:</strong> {{ $performanceReview->goals ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Warning Letters Context -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">{{ __('Related Warning Letters') }}</h2>
            @if ($warningLetters->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Issue Date') }}</th>
                                <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Reason') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($warningLetters as $warningLetter)
                                <tr class="hover:bg-gray-50">
                                    <td class="border p-3">{{ $warningLetter->issue_date }}</td>
                                    <td class="border p-3">{{ $warningLetter->reason }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">{{ __('No warning letters found for this employee.') }}</p>
            @endif
        </div>

        <!-- Complete Review Form (for Managers/HR) -->
        @if ((Auth::user()->id === $performanceReview->reviewer_id || Auth::user()->hasRole('hr')) && $performanceReview->status === 'scheduled')
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">{{ __('Complete Performance Review') }}</h2>
                <form method="POST" action="{{ route('performance-reviews.complete', $performanceReview) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="rating" class="block text-gray-700 font-medium mb-2">{{ __('Rating (1-5)') }}</label>
                        <select id="rating" name="rating" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('rating') border-red-500 @enderror" required>
                            <option value="">{{ __('Select Rating') }}</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        @error('rating')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="feedback" class="block text-gray-700 font-medium mb-2">{{ __('Feedback') }}</label>
                        <textarea id="feedback" name="feedback" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('feedback') border-red-500 @enderror" required>{{ old('feedback') }}</textarea>
                        @error('feedback')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="goals" class="block text-gray-700 font-medium mb-2">{{ __('Goals for Next Period') }}</label>
                        <textarea id="goals" name="goals" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('goals') border-red-500 @enderror" required>{{ old('goals') }}</textarea>
                        @error('goals')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500">
                        {{ __('Complete Review') }}
                    </button>
                </form>
            </div>
        @endif
    </div>
@endsection