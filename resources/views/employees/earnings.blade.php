@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen p-6" x-data="{
        selectedEmployee: null,
        open: false,
        showModal(employee) {
            this.selectedEmployee = employee;
            this.open = true;
        },
        closeModal() {
            this.open = false;
            this.selectedEmployee = null;
        }
    }">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-3 md:space-y-0">
        <h1 class="text-3xl font-extrabold text-gray-800">Employees Payroll / Earnings</h1>
       <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-3 md:space-y-0">
  
    <div class="flex space-x-3">
        <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Employees
        </a>
    </div>
</div>

    </div>

    {{-- Table --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow border">
        <table class="w-full min-w-max divide-y divide-gray-200">
            <thead class="bg-gray-100 sticky top-0 z-10">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Employee ID</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Salary</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Total Commission</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Total Earnings</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($employees as $employee)
                <tr class="hover:bg-gray-50 cursor-pointer transition"
                    @click="showModal({
                        id: {{ $employee->id }},
                        name: '{{ addslashes($employee->name) }}',
                        salary: '{{ number_format($employee->salary, 2) }}',
                        total_commission: '{{ number_format($employee->total_commission, 2) }}',
                        total_earnings: '{{ number_format($employee->total_earnings, 2) }}',
                        clients: {!! json_encode($employee->clients ?? []) !!}
                    })">
                    <td class="px-4 py-3">{{ $employee->employee_id }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $employee->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ number_format($employee->salary, 2) }}</td>
                    <td class="px-4 py-3 text-green-600 font-semibold">{{ number_format($employee->total_commission, 2) }}</td>
                    <td class="px-4 py-3 text-blue-600 font-bold">{{ number_format($employee->total_earnings, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-3 text-center text-gray-500">No employees found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if(method_exists($employees, 'links'))
    <div class="mt-4">
        {{ $employees->links() }}
    </div>
    @endif

    {{-- Modal (Outside table!) --}}
    <div x-show="open" x-transition.opacity x-cloak
         @click.away="closeModal()"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2 overflow-hidden animate-fade-in">
            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="text-xl font-bold text-gray-800" x-text="selectedEmployee?.name + ' - Earnings Details'"></h2>
                <button @click="closeModal()" class="text-gray-600 hover:text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-4 space-y-3" x-show="selectedEmployee">
                <p><strong>Employee ID:</strong> <span x-text="selectedEmployee.id"></span></p>
                <p><strong>Salary:</strong> $<span x-text="selectedEmployee.salary"></span></p>
                <p><strong>Total Commission:</strong> $<span x-text="selectedEmployee.total_commission"></span></p>
                <p><strong>Total Earnings:</strong> $<span x-text="selectedEmployee.total_earnings"></span></p>
                <hr class="my-2">
                <h3 class="font-semibold text-gray-700">Clients & Commissions</h3>
                <ul class="list-disc pl-5">
                    <template x-for="client in selectedEmployee.clients" :key="client.id">
                        <li>
                            <strong x-text="client.name"></strong> (<span x-text="client.sales_rep_name ?? 'N/A'"></span>): $<span x-text="client.commission"></span>
                        </li>
                    </template>
                    <template x-if="selectedEmployee.clients.length === 0">
                        <li>No clients assigned</li>
                    </template>
                </ul>
            </div>
        </div>
    </div>

</div>

{{-- Alpine.js v3 --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
@keyframes fade-in {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}
.animate-fade-in { animation: fade-in 0.3s ease-out; }
</style>
@endsection
