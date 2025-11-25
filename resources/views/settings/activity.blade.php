@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">{{ __('Settings') }}</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="{{ route('settings.index') }}" class="border-b-2 px-1 py-4 text-sm font-medium {{ request()->routeIs('settings.index') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ __('General') }}
                </a>
                @can('manage settings')
                    <a href="{{ route('settings.roles') }}" class="border-b-2 px-1 py-4 text-sm font-medium {{ request()->routeIs('settings.roles') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        {{ __('Roles') }}
                    </a>
                    <a href="{{ route('settings.activity') }}" class="border-b-2 px-1 py-4 text-sm font-medium {{ request()->routeIs('settings.activity') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        {{ __('Activity Log') }}
                    </a>
                    <a href="{{ route('settings.backup') }}" class="border-b-2 px-1 py-4 text-sm font-medium {{ request()->routeIs('settings.backup') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        {{ __('Backup & Restore') }}
                    </a>
                @endcan
            </nav>
        </div>

        <!-- Activity Log -->
        <h2 class="text-lg font-medium text-gray-700 mb-4">{{ __('Activity Log') }}</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Log Name') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Description') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('User') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($activities as $activity)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $activity->log_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $activity->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $activity->causer ? $activity->causer->name : 'System' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center">{{ __('No activities found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $activities->links() }}
        </div>
    </div>
@endsection