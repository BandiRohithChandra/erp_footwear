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

        <!-- Backup & Restore -->
        <h2 class="text-lg font-medium text-gray-700 mb-4">{{ __('Backup & Restore') }}</h2>
        <div class="space-y-6">
            <!-- Create Backup -->
            <div>
                <form action="{{ route('settings.backup.create') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        {{ __('Create Backup') }}
                    </button>
                </form>
            </div>

            <!-- List Backups -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Backup File') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($backups as $backup)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ basename($backup) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('settings.backup.download', basename($backup)) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        {{ __('Download') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-center">{{ __('No backups found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Restore Backup -->
            <div>
                <form action="{{ route('settings.backup.restore') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="backup" class="block text-sm font-medium text-gray-700">{{ __('Select Backup to Restore') }}</label>
                        <select name="backup" id="backup" class="mt-1 block w-full max-w-xs border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                            @forelse ($backups as $backup)
                                <option value="{{ $backup }}">{{ basename($backup) }}</option>
                            @empty
                                <option value="">{{ __('No backups available') }}</option>
                            @endforelse
                        </select>
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m0 14v1m-8-8h1m14 0h1m-4.586-4.586l-.707.707m-6 6l-.707.707m6-6l.707-.707m-6 6l.707-.707"></path></svg>
                        {{ __('Restore Backup') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection