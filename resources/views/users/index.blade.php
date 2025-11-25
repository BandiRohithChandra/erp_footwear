@extends('layouts.app')

@section('content')
<div class="bg-white p-4 md:p-6 rounded-lg shadow max-w-7xl mx-auto">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold">{{ __('Users') }}</h1>
        <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            {{ __('Add New User') }}
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- Desktop Table --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Name') }}</th>
                    <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Email') }}</th>
                    <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Roles') }}</th>
                    <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Manager') }}</th>
                    <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Remote') }}</th>
                    <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="border p-3">{{ $user->name }}</td>
                        <td class="border p-3">{{ $user->email }}</td>
                        <td class="border p-3">{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
                        <td class="border p-3">{{ $user->manager ? $user->manager->name : '-' }}</td>
                        <td class="border p-3 text-center">{{ $user->is_remote ? __('Yes') : __('No') }}</td>
                        <td class="border p-3">
    <div class="flex flex-row items-center justify-center gap-2">
        <a href="{{ route('users.edit', $user) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
            {{ __('Edit') }}
        </a>
        <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                {{ __('Delete') }}
            </button>
        </form>
    </div>
</td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="border p-3 text-center text-gray-600">{{ __('No users found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden flex flex-col gap-4">
        @forelse ($users as $user)
            <div class="border rounded-lg p-4 shadow-sm bg-gray-50 space-y-2">
                <div><span class="font-semibold">{{ __('Name') }}: </span>{{ $user->name }}</div>
                <div><span class="font-semibold">{{ __('Email') }}: </span>{{ $user->email }}</div>
                <div><span class="font-semibold">{{ __('Roles') }}: </span>{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</div>
                <div><span class="font-semibold">{{ __('Manager') }}: </span>{{ $user->manager ? $user->manager->name : '-' }}</div>
                <div><span class="font-semibold">{{ __('Remote') }}: </span>{{ $user->is_remote ? __('Yes') : __('No') }}</div>
                <div class="flex space-x-2 mt-2">
                    <a href="{{ route('users.edit', $user) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs">{{ __('Edit') }}</a>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs">{{ __('Delete') }}</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-600">{{ __('No users found') }}</div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
