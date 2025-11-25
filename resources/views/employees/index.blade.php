@extends('layouts.app')

@section('content')
<!-- Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div class="bg-white p-6 rounded-lg shadow">
   

    <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">{{ __('Employees') }}</h1>

   <div x-data="{ openImportModal: false }" class="flex gap-3">
    <!-- Import CSV Modal Trigger -->
    <button @click="openImportModal = true" 
        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
        Import CSV
    </button>

    <!-- Export CSV -->
    <a href="{{ route('employees.export') }}" 
       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        Export CSV
    </a>

    <!-- Add Employee -->
    <a href="{{ route('employees.create') }}" 
       class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
        + Add Employee
    </a>

    <!-- Import Modal -->
    <div 
        x-show="openImportModal" 
        x-transition 
        @keydown.escape.window="openImportModal = false"
        class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50"
    >
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Import Employees CSV</h2>

            <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload CSV File</label>
                    <input type="file" name="file" accept=".csv" required 
                        class="block w-full text-sm border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" @click="openImportModal = false"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>


    <!-- Search -->
    <form method="GET" action="{{ route('employees.index') }}" class="mb-6">
        <div class="flex items-center space-x-4">
            <input type="text" name="search" value="{{ request()->query('search') }}" placeholder="Search..." class="border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 w-64">
            <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Search</button>
        </div>
    </form>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">Employee ID</th>
                    <th class="px-4 py-2 border">Name</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Position</th>
                    <th class="px-4 py-2 border">Department</th>
                    <!-- <th class="px-4 py-2 border">Actions</th> -->
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $employee)
                <tr x-data="{ open: false }" x-cloak class="bg-gray-50 hover:bg-gray-100">
                    <!-- Main row with light bg -->
                    <td class="px-4 py-2 border font-medium">{{ $employee->employee_id ?? '-' }}</td>
                    <td class="px-4 py-2 border font-medium">{{ $employee->name }}</td>
                    <td class="px-4 py-2 border font-medium">{{ $employee->email ?? '-' }}</td>
                    <td class="px-4 py-2 border font-medium">{{ $employee->position }}</td>
                    <td class="px-4 py-2 border font-medium">{{ $employee->department }}</td>
                    <!-- <td class="px-4 py-2 border text-center">
                        <button @click="open = !open" class="px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            <span x-text="open ? 'Hide' : 'View More'"></span>
                        </button>
                    </td> -->
                </tr>

                <!-- Detailed row -->
                <tr x-show="open" x-transition x-cloak class="bg-gray-100">
                    <td colspan="6" class="px-4 py-3 border-t">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-700">
                            <p><strong>Role:</strong> {{ ucfirst($employee->role ?? 'Labor') }}</p>
                            <p><strong>Salary:</strong> {{ $employee->salary ? \App\Helpers\FormatMoney::format($employee->salary, 'in', 'INR') : '-' }}</p>
                            <p><strong>Hire Date:</strong> {{ $employee->hire_date ?? '-' }}</p>
                            <p><strong>Phone:</strong> {{ $employee->phone ?? '-' }}</p>
                            <p><strong>Emergency Contact:</strong> {{ $employee->emergency_contact ?? '-' }}</p>
                            <p><strong>DOB:</strong> {{ $employee->date_of_birth ?? '-' }}</p>
                            <p><strong>Age:</strong> {{ $employee->age ?? '-' }}</p>
                            <p><strong>Aadhaar / PAN:</strong> {{ $employee->national_id ?? '-' }}</p>
                            <p><strong>Personal Email:</strong> {{ $employee->personal_email ?? '-' }}</p>
                            <p><strong>Present City:</strong> {{ $employee->present_city ?? '-' }}</p>
                            <p><strong>Permanent State:</strong> {{ $employee->permanent_state ?? '-' }}</p>
                            <p><strong>Payment Method:</strong> {{ $employee->payment_method ? ucfirst($employee->payment_method) : '-' }}</p>
                            <p><strong>Supervisor:</strong> {{ $employee->user && $employee->user->manager ? $employee->user->manager->name : '-' }}</p>
                            <p><strong>Remote:</strong> {{ $employee->user && $employee->user->is_remote ? 'Yes' : 'No' }}</p>
                        </div>

                        <!-- Actions -->
                        <div class="mt-3 flex flex-wrap gap-3 text-sm">
                            <a href="{{ route('employees.edit', $employee) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                            <a href="{{ route('employees.payroll', $employee) }}" class="text-green-600 hover:text-green-800">Payroll</a>
                            <a href="{{ route('employees.attendance', $employee) }}" class="text-purple-600 hover:text-purple-800">Attendance</a>
                            <a href="{{ route('employees.show', $employee) }}" class="text-indigo-600 hover:text-indigo-800">View</a>
                            <form action="{{ route('employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-2 text-center text-gray-600">No employees found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $employees->links() }}
    </div>
</div>
@endsection
