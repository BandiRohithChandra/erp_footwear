@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-semibold text-gray-900 mb-8 border-b-2 border-gray-300 pb-2">Suppliers</h1>

    <!-- Add Supplier & CSV Buttons -->
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <!-- Add Supplier -->
        <a href="{{ route('suppliers.create') }}" 
           class="bg-gray-900 text-white px-4 py-2 rounded shadow hover:bg-gray-800 transition">
           + Add New Supplier
        </a>

        <!-- Import CSV -->
        <button type="button"
            onclick="document.getElementById('importModal').classList.remove('hidden')"
            class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700 transition">
            Import CSV
        </button>

        <!-- Export CSV -->
        <a href="{{ route('suppliers.export') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
           Export CSV
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-600 text-green-800 px-4 py-3 rounded mb-6 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Import Modal -->
    <div id="importModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden z-50">
        <div class="bg-white p-6 rounded-xl shadow-lg w-96 relative">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Import Supplier CSV</h2>
            <form action="{{ route('suppliers.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" accept=".csv" required
                    class="block w-full border border-gray-300 rounded-lg p-2 mb-4 text-sm">
                <div class="flex justify-end gap-2">
                    <button type="button"
                        onclick="document.getElementById('importModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Suppliers Table -->
    <!-- Suppliers Table -->
<div class="overflow-x-auto bg-white shadow-lg rounded-lg border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-100 text-gray-700 uppercase text-xs tracking-wider">
            <tr>
                <th class="px-6 py-3 text-left font-medium">Supplier ID</th>
                <th class="px-6 py-3 text-left font-medium">Business Name</th>
                <th class="px-6 py-3 text-left font-medium">Name</th>
                <th class="px-6 py-3 text-left font-medium">Supplied Materials</th>
                <th class="px-6 py-3 text-left font-medium">Email</th>
                <th class="px-6 py-3 text-left font-medium">Phone</th>
                <th class="px-6 py-3 text-left font-medium">GST Number</th>
                <th class="px-6 py-3 text-center font-medium">Actions</th>
            </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($suppliers as $supplier)
            <tr class="hover:bg-gray-50 transition cursor-pointer" 
                onclick="window.location='{{ route('suppliers.show', $supplier->id) }}'">

                <!-- Supplier ID -->
                <td class="px-6 py-3 font-medium text-gray-800">
                    {{ 'SUP-' . str_pad($supplier->id, 5, '0', STR_PAD_LEFT) }}
                </td>

                <!-- Business Name -->
                <td class="px-6 py-3 font-medium text-gray-800">
                    {{ $supplier->business_name ?? '-' }}
                </td>

                <!-- Supplier Name -->
                <td class="px-6 py-3 font-medium text-gray-800">{{ $supplier->name }}</td>

                <!-- Supplied Materials -->
                <td class="px-6 py-3 text-gray-700">{{ $supplier->material_types ?? 'N/A' }}</td>

                <!-- Email -->
                <td class="px-6 py-3">{{ $supplier->email ?? 'N/A' }}</td>

                <!-- Phone -->
                <td class="px-6 py-3">{{ $supplier->phone ?? 'N/A' }}</td>

                <!-- GST -->
                <td class="px-6 py-3">{{ $supplier->gst_number ?? 'N/A' }}</td>

                <!-- ✅ Aligned Actions -->
                <td class="px-6 py-3">
                    <div class="flex justify-center items-center gap-2"
                         onclick="event.stopPropagation()">
                        <a href="{{ route('suppliers.edit', $supplier->id) }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs font-semibold transition">
                            ✏️ Edit
                        </a>

                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" 
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold transition">
                                🗑 Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($suppliers->isEmpty())
        <p class="text-center text-gray-500 py-6">No suppliers available.</p>
    @endif
</div>

</div>
@endsection
