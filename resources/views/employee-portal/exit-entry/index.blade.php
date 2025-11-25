@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('Exit/Entry Requests') }}</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Employee') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Exit Date') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Re-Entry Date') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Reason') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($requests as $request)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $request->employee->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $request->exit_date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $request->re_entry_date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $request->reason }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $request->status }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($request->status === 'pending')
                                    <form action="{{ route('exit-entry-requests.approve', $request) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">{{ __('Approve') }}</button>
                                    </form>
                                    <form action="{{ route('exit-entry-requests.reject', $request) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">{{ __('Reject') }}</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $requests->links() }}
        </div>
    </div>
@endsection