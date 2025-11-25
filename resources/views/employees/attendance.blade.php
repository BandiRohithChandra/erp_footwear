@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">{{ __('Attendance for') }} {{ $employee->name }}</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('employees.attendance.store', $employee) }}" class="space-y-6 mb-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">{{ __('Date') }}</label>
                    <input type="date" name="date" id="date" value="{{ old('date') }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required>
                    @error('date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">{{ __('Status') }}</label>
                    <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500" required onchange="toggleCheckTimes(this)">
                        <option value="present" {{ old('status') === 'present' ? 'selected' : '' }}>{{ __('Present') }}</option>
                        <option value="absent" {{ old('status') === 'absent' ? 'selected' : '' }}>{{ __('Absent') }}</option>
                        <option value="leave" {{ old('status') === 'leave' ? 'selected' : '' }}>{{ __('Leave') }}</option>
                    </select>
                    @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div id="check_in_container">
                    <label for="check_in" class="block text-sm font-medium text-gray-700">{{ __('Check In') }}</label>
                    <input type="time" name="check_in" id="check_in" value="{{ old('check_in') }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                    @error('check_in') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div id="check_out_container">
                    <label for="check_out" class="block text-sm font-medium text-gray-700">{{ __('Check Out') }}</label>
                    <input type="time" name="check_out" id="check_out" value="{{ old('check_out') }}" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                    @error('check_out') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">{{ __('Notes') }}</label>
                    <textarea name="notes" id="notes" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                    @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                {{ __('Record Attendance') }}
            </button>
        </form>

        <div class="mt-6">
            <h2 class="text-xl font-semibold mb-4">{{ __('Attendance History') }}</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Date') }}</th>
                            <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Status') }}</th>
                            <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Check In') }}</th>
                            <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Check Out') }}</th>
                            <th class="border p-3 text-left text-sm font-semibold text-gray-700">{{ __('Notes') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="border p-3">{{ $attendance->date }}</td>
                                <td class="border p-3">{{ __(ucfirst($attendance->status)) }}</td>
                                <td class="border p-3">{{ $attendance->check_in ?? 'N/A' }}</td>
                                <td class="border p-3">{{ $attendance->check_out ?? 'N/A' }}</td>
                                <td class="border p-3">{{ $attendance->notes ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border p-3 text-center text-gray-600">{{ __('No attendance records found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function toggleCheckTimes(select) {
            const checkInContainer = document.getElementById('check_in_container');
            const checkOutContainer = document.getElementById('check_out_container');
            const isPresent = select.value === 'present';
            checkInContainer.classList.toggle('hidden', !isPresent);
            checkOutContainer.classList.toggle('hidden', !isPresent);
            document.getElementById('check_in').required = isPresent;
            document.getElementById('check_out').required = isPresent;
        }

        // Initialize visibility based on current status
        document.addEventListener('DOMContentLoaded', () => {
            toggleCheckTimes(document.getElementById('status'));
        });
    </script>
@endsection