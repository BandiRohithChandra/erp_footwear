@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-8 bg-white rounded-2xl shadow-lg border border-gray-100">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Salary Advances</h2>
            <p class="text-sm text-gray-500 mt-1">Manage employee salary advances and track payment statuses</p>
        </div>

        <a href="{{ route('salary-advance.create') }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Advance
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto border border-gray-200 rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Employee</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Remaining</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($advances as $advance)
                <tr class="hover:bg-gray-50 transition cursor-pointer" 
                    onclick="window.location='{{ route('salary-advance.show', $advance->id) }}'">
                    
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $advance->employee->name }}</td>
                    <td class="px-6 py-4 text-gray-700">₹{{ number_format($advance->amount, 2) }}</td>
                    <td class="px-6 py-4 text-gray-700">₹{{ number_format($advance->remaining_amount, 2) }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ \Carbon\Carbon::parse($advance->date)->format('d M, Y') }}</td>

                    <td class="px-6 py-4">
                        @php
                            $statusClasses = [
                                'Paid' => 'bg-green-100 text-green-800',
                                'Partially Paid' => 'bg-yellow-100 text-yellow-800',
                                'Pending' => 'bg-gray-100 text-gray-800'
                            ];
                        @endphp
                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $statusClasses[$advance->dynamic_status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $advance->dynamic_status }}
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-right flex justify-end gap-2">
                        <a href="{{ route('salary-advance.show', $advance->id) }}" 
                           class="text-blue-600 hover:text-blue-800 px-3 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 transition"
                           onclick="event.stopPropagation();">View</a>

                        <a href="{{ route('salary-advance.edit', $advance->id) }}" 
                           class="text-yellow-600 hover:text-yellow-800 px-3 py-1 rounded-lg bg-yellow-50 hover:bg-yellow-100 transition"
                           onclick="event.stopPropagation();">Edit</a>

                        <form action="{{ route('salary-advance.destroy', $advance->id) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this record?');" 
                              style="display:inline;" onclick="event.stopPropagation();">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-red-600 hover:text-red-800 px-3 py-1 rounded-lg bg-red-50 hover:bg-red-100 transition">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400 text-base">
                        <div class="flex flex-col items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m0 0v-2m0 2h6m0 0v2m0-2v-2M5 10h14M5 6h14M5 14h14"/>
                            </svg>
                            No salary advances found
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-end">
        {{ $advances->links() }}
    </div>
</div>

<style>
    tbody tr { transition: background-color 0.2s ease-in-out; }
    table thead th { font-size: 0.75rem; letter-spacing: 0.05em; }
</style>
@endsection
