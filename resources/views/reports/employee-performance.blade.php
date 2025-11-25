@extends('layouts.app')

@section('content')
<div class="p-6">

    <h1 class="text-3xl font-bold text-gray-800 mb-6">{{ __('Employee Performance Report') }}</h1>

    {{-- Export Button --}}
    <div class="flex justify-end mb-5">
        <a href="{{ route('reports.employee-performance.export') }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white shadow rounded-lg hover:bg-blue-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Export Report
        </a>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <div class="bg-white p-5 rounded-xl shadow border border-gray-200">
            <p class="text-sm text-gray-500">Total Employees</p>
            <p class="text-2xl font-bold text-gray-800">{{ $employees->count() }}</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow border border-gray-200">
            <p class="text-sm text-gray-500">Active Employees</p>
            <p class="text-2xl font-bold text-green-600">
                {{ $employees->where('status', 'active')->count() }}
            </p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow border border-gray-200">
            <p class="text-sm text-gray-500">Average Performance Rating</p>
            <p class="text-2xl font-bold text-blue-600">
                {{ number_format($employees->avg('rating'), 1) ?? '0.0' }}
            </p>
        </div>

    </div>

    {{-- Main Table --}}
    <div class="bg-white shadow-xl rounded-xl border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">ID</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Name</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Department</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Role</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Performance</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Attendance</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Joined On</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($employees as $employee)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-6 py-3 font-medium">{{ $employee->id }}</td>
                        <td class="px-6 py-3 font-medium">{{ $employee->name }}</td>
                        <td class="px-6 py-3">{{ $employee->department ?? 'N/A' }}</td>
                        <td class="px-6 py-3">{{ $employee->role ?? '—' }}</td>

                        {{-- Performance Rating --}}
                        <td class="px-6 py-3 font-semibold">
                            <span class="px-2 py-1 rounded-lg 
                                @if($employee->rating >= 4)
                                    bg-green-100 text-green-700
                                @elseif($employee->rating >= 3)
                                    bg-yellow-100 text-yellow-700
                                @else
                                    bg-red-100 text-red-700
                                @endif">
                                {{ $employee->rating ?? '0' }}/5
                            </span>
                        </td>

                        {{-- Attendance --}}
                        <td class="px-6 py-3">
                            {{ $employee->attendance_percentage ?? '0' }}%
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                @if($employee->status === 'active')
                                    bg-green-100 text-green-700
                                @else
                                    bg-gray-200 text-gray-700
                                @endif">
                                {{ ucfirst($employee->status) }}
                            </span>
                        </td>

                        {{-- Join Date --}}
                        <td class="px-6 py-3">
                            {{ $employee->join_date ? \Carbon\Carbon::parse($employee->join_date)->format('d M Y') : '—' }}
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-6 text-center text-gray-500">
                            No employees found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
