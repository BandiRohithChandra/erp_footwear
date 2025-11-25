@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Role Management -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">{{ __('Role Management') }}</h2>

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

            <!-- List of Roles -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Role') }}</th>
                            <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Permissions') }}</th>
                            <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr class="hover:bg-gray-50">
                                <td class="border p-3">{{ $role->name }}</td>
                                <td class="border p-3">
                                    @if ($role->permissions->isEmpty())
                                        <span class="text-gray-600">{{ __('No permissions assigned') }}</span>
                                    @else
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($role->permissions as $permission)
                                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">{{ $permission->name }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="border p-3">
                                    <button onclick="togglePermissionsForm('permissions-form-{{ $role->id }}')" class="text-blue-600 hover:text-blue-800">
                                        {{ __('Edit Permissions') }}
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="border p-3">
                                    <form id="permissions-form-{{ $role->id }}" method="POST" action="{{ route('settings.update-role-permissions', $role->id) }}" class="space-y-4 hidden">
                                        @csrf
                                        @method('PUT')
                                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                            @foreach ($permissions as $permission)
                                                <label class="flex items-center space-x-2">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-500 focus:ring-blue-500">
                                                    <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500">
                                            {{ __('Update Permissions') }}
                                        </button>
                                        <button type="button" onclick="togglePermissionsForm('permissions-form-{{ $role->id }}')" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 focus:ring-2 focus:ring-gray-500">
                                            {{ __('Cancel') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- User Role Assignment -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">{{ __('Assign Roles to Users') }}</h2>
            <form method="POST" action="{{ route('settings.users.create') }}" class="mb-6 space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-gray-700 font-medium mb-2">{{ __('Name') }}</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-gray-700 font-medium mb-2">{{ __('Email') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror" required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password" class="block text-gray-700 font-medium mb-2">{{ __('Password') }}</label>
                    <input id="password" type="password" name="password" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror" required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">{{ __('Confirm Password') }}</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="country" class="block text-gray-700 font-medium mb-2">{{ __('Country') }}</label>
                    <select id="country" name="country" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('country') border-red-500 @enderror">
                        <option value="">{{ __('Select Country') }}</option>
                        @foreach (config('countries.countries') as $code => $name)
                            <option value="{{ $code }}" {{ old('country') == $code ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('country')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="role" class="block text-gray-700 font-medium mb-2">{{ __('Role') }}</label>
                    <select id="role" name="role" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror" required>
                        <option value="">{{ __('Select a Role') }}</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500">
                    {{ __('Create User') }}
                </button>
            </form>

            <!-- List of Users -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Name') }}</th>
                            <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Email') }}</th>
                            <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Country') }}</th>
                            <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Role') }}</th>
                            <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="border p-3">{{ $user->name }}</td>
                                <td class="border p-3">{{ $user->email }}</td>
                                <td class="border p-3">{{ $user->country ? config('countries.countries')[$user->country] : 'N/A' }}</td>
                                <td class="border p-3">
                                    {{ $user->roles->pluck('name')->implode(', ') ?: __('No role assigned') }}
                                </td>
                                <td class="border p-3 space-x-2">
                                    <a href="{{ route('settings.users.edit', $user) }}" class="text-blue-600 hover:text-blue-800">{{ __('Edit') }}</a>
                                    <form method="POST" action="{{ route('settings.assign-role', $user) }}" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" class="border rounded-lg p-1 focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                                            <option value="">{{ __('Assign Role') }}</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                    <form method="POST" action="{{ route('settings.users.delete', $user) }}" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">{{ __('Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function togglePermissionsForm(formId) {
            const form = document.getElementById(formId);
            form.classList.toggle('hidden');
        }
    </script>
@endsection