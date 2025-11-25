@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-extrabold mb-8 text-gray-800 flex items-center">
        🔔 {{ __('Notifications') }}
    </h1>

    <!-- Back Button -->
<button type="button" onclick="history.back()" 
        class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
    Back
</button>


    {{-- Success Message --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($notifications->isEmpty())
        {{-- No Notifications --}}
        <div class="text-center py-12 space-y-4">
            <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 
                             0118 14.158V11a6.002 6.002 0 
                             00-4-5.659V5a2 2 0 10-4 
                             0v.341C7.67 6.165 6 8.388 
                             6 11v3.159c0 .538-.214 1.055-.595 
                             1.436L4 17h5m6 0v1a3 3 0 
                             11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <p class="text-gray-500 text-lg">{{ __('No new notifications yet.') }}</p>
        </div>
    @else
        {{-- Mark All as Read --}}
        <div class="mb-6 flex justify-end">
            <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow text-sm">
                    {{ __('Mark All as Read') }}
                </button>
            </form>
        </div>

        {{-- Notifications List --}}
        <div class="space-y-4">
            @foreach ($notifications as $notification)
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-white shadow-md rounded-2xl p-5 hover:shadow-lg transition duration-200">
                    
                    {{-- Left: Icon + Message --}}
                    <div class="flex items-center space-x-4 w-full sm:w-auto">
                        <div class="w-12 h-12 flex items-center justify-center rounded-full 
                            {{ $notification->read_at ? 'bg-gray-100 text-gray-400' : 'bg-blue-100 text-blue-600' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 
                                         0118 14.158V11a6.002 6.002 0 
                                         00-4-5.659V5a2 2 0 10-4 
                                         0v.341C7.67 6.165 6 8.388 
                                         6 11v3.159c0 .538-.214 1.055-.595 
                                         1.436L4 17h5m6 0v1a3 3 0 
                                         11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>

                        <div class="flex-1 mt-2 sm:mt-0">
                            <p class="text-gray-800 font-medium">{{ $notification->data['message'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    {{-- Right: Actions --}}
                    <div class="flex space-x-3 mt-3 sm:mt-0">
                        @if (!$notification->read_at)
                            <form action="{{ route('notifications.mark-as-read', $notification) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-xs shadow">
                                    {{ __('Mark as Read') }}
                                </button>
                            </form>
                        @endif
                        @if (isset($notification->data['url']))
                            <a href="{{ $notification->data['url'] }}"
                               class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-xs shadow">
                                {{ __('View') }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
