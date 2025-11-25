@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('Warning Letters') }}</h1>

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

        <div class="mb-4">
            <a href="{{ route('warning-letters.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">{{ __('Issue Warning Letter') }}</a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Employee') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Reason') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Issue Date') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($warningLetters as $letter)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $letter->employee && $letter->employee->user ? $letter->employee->user->name : 'Employee Not Found' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $letter->reason }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $letter->issue_date ?? $letter->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $letter->status }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
    @if ($letter->status === 'issued')
        <form action="{{ route('warning-letters.upload', $letter) }}" method="POST" enctype="multipart/form-data" class="inline">
            @csrf
            <input type="file" name="signed_letter" class="inline-block" required>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">{{ __('Upload Signed Letter') }}</button>
        </form>
    @elseif ($letter->status === 'uploaded' && $letter->file_path && Storage::exists($letter->file_path))
        <a href="{{ Storage::url($letter->file_path) }}" target="_blank" class="text-blue-500 hover:underline">{{ __('View Letter') }}</a>
    @elseif ($letter->status === 'uploaded')
        <span class="text-gray-500">{{ __('Letter Not Available') }}</span>
    @endif
    <a href="{{ route('warning-letters.print', $letter) }}" target="_blank" class="ml-2 text-blue-500 hover:underline">{{ __('Print') }}</a>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">{{ __('No warning letters found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $warningLetters->links() }}
        </div>
    </div>
@endsection