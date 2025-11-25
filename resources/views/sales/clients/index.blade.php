@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-4 lg:px-8 py-6">
    <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">

            <!-- Heading -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <h2 class="text-2xl font-bold text-gray-800">{{ __('Party') }}</h2>
            </div>

            <!-- Back Button -->
            <a href="{{ url()->previous() }}" 
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-gray-700 bg-gray-100 
                      hover:bg-gray-200 hover:text-gray-900 transition shadow-sm border border-gray-300 w-fit">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>

            <!-- Success Alert -->
            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif

          <!-- Search / Import / Export / Add Client -->
<div class="flex flex-col md:flex-row justify-between items-center gap-3 mb-6">
    <!-- Search -->
    <form method="GET" action="{{ route('clients.index') }}" class="flex w-full md:w-auto items-center gap-2">
        <input type="text" 
               name="search" 
               value="{{ request('search') }}" 
               placeholder="{{ __('Search Parties...') }}" 
               class="w-full md:w-64 border-gray-300 rounded-lg p-2 text-sm focus:ring-blue-500 focus:border-blue-500">
        <button type="submit" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            {{ __('Search') }}
        </button>
    </form>

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-3 justify-center md:justify-end">
        <!-- Import CSV -->
        <button type="button"
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
            onclick="document.getElementById('importModal').classList.remove('hidden')">
            Import CSV
        </button>

        <!-- Export CSV -->
        <a href="{{ route('clients.export') }}" 
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
           Export CSV
        </a>

        <!-- Add Client -->
        <a href="{{ route('clients.create') }}" 
           class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
           + Add Party
        </a>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="bg-white p-6 rounded-xl shadow-lg w-96 relative">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Import Party CSV</h2>

        <form action="{{ route('clients.import') }}" method="POST" enctype="multipart/form-data">
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

<!-- Auto-close modal after success -->
@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('importModal')?.classList.add('hidden');
    });
</script>
@endif


            <!-- Table for desktop -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-600 uppercase">{{ __('Business Name') }}</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600 uppercase">{{ __('Contact Name') }}</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600 uppercase">{{ __('Email') }}</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600 uppercase">{{ __('Phone') }}</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600 uppercase">{{ __('GST No') }}</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-600 uppercase">{{ __('Category') }}</th>
                            <!-- <th class="px-4 py-2 text-left font-medium text-gray-600 uppercase">{{ __('Sales Rep') }}</th> -->
                            <!-- <th class="px-4 py-2 text-left font-medium text-gray-600 uppercase">{{ __('Base Salary') }}</th> -->
                            <!-- <th class="px-4 py-2 text-left font-medium text-gray-600 uppercase">{{ __('Commission') }}</th> -->
                            <!-- <th class="px-4 py-2 text-left font-medium text-gray-600 uppercase">{{ __('Total Payout') }}</th> -->
                            <th class="px-4 py-2 text-left font-medium text-gray-600 uppercase">{{ __('Status') }}</th>
                            <th class="px-4 py-2 text-right font-medium text-gray-600 uppercase">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                       @forelse ($clients as $client)
<tr onclick="window.location='{{ route('clients.show', $client) }}'"
    class="cursor-pointer hover:bg-gray-100 transition-colors duration-200">

                            <td class="px-4 py-2">{{ $client->business_name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $client->name }}</td>
                            <td class="px-4 py-2">{{ $client->email }}</td>
                            <td class="px-4 py-2">{{ $client->phone ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $client->gst_no ?? '-' }}</td>
                            <td class="px-4 py-2 capitalize">{{ $client->category ?? '-' }}</td>

                           <!-- Sales Rep + Salary + Commission -->
<!-- <td class="px-4 py-2">{{ $client->salesRep?->name ?? '-' }}</td>
<td class="px-4 py-2">{{ number_format($client->salesRep?->salary ?? 0, 2) }}</td>
<td class="px-4 py-2">
    {{ number_format($client->salesRep?->commissions?->where('client_id', $client->id)->sum('commission_amount') ?? 0, 2) }}
</td>
<td class="px-4 py-2 font-bold">
    {{ number_format(
        ($client->salesRep?->salary ?? 0) + ($client->salesRep?->commissions?->where('client_id', $client->id)->sum('commission_amount') ?? 0),
        2
    ) }}
</td> -->


                            <td class="px-4 py-2">
                                @if($client->status === 'pending')
                                    <span class="text-yellow-600 font-semibold">{{ ucfirst($client->status) }}</span>
                                @elseif($client->status === 'approved')
                                    <span class="text-green-600 font-semibold">{{ ucfirst($client->status) }}</span>
                                @elseif($client->status === 'rejected')
                                    <span class="text-red-600 font-semibold">{{ ucfirst($client->status) }}</span>
                                @else
                                    <span class="text-gray-500">{{ ucfirst($client->status) }}</span>
                                @endif
                            </td>

                            <td class="px-4 py-2 text-right">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <a href="{{ route('clients.show', $client) }}" class="text-green-600 hover:text-green-800 font-medium">View</a>
                                    <a href="{{ route('clients.edit', $client) }}" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                    @if($client->status === 'pending')
                                        <form action="{{ route('clients.approve', $client) }}" method="POST" class="inline">@csrf
                                            <button type="submit" class="text-green-600 hover:text-green-800 font-medium">Approve</button>
                                        </form>
                                        <form action="{{ route('clients.reject', $client) }}" method="POST" class="inline">@csrf
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Reject</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium"
                                                onclick="return confirm('{{ __('Are you sure you want to delete this client?') }}')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="px-4 py-4 text-center text-gray-500">{{ __('No Parties found.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Card view for mobile -->
            <div class="space-y-4 md:hidden">
                @forelse ($clients as $client)
                    <div class="border rounded-lg p-4 shadow-sm">
                        <h3 class="font-bold text-lg text-gray-800">{{ $client->business_name ?? '-' }}</h3>
                        <p><span class="font-semibold">Contact:</span> {{ $client->name }}</p>
                        <p><span class="font-semibold">Email:</span> {{ $client->email }}</p>
                        <p><span class="font-semibold">Phone:</span> {{ $client->phone ?? '-' }}</p>
                        <p><span class="font-semibold">GST No:</span> {{ $client->gst_no ?? '-' }}</p>
                        <p><span class="font-semibold">Category:</span> {{ $client->category ?? '-' }}</p>

                        <!-- Sales Rep + Salary + Commission -->
<p><span class="font-semibold">Sales Rep:</span> {{ $client->salesRep?->name ?? '-' }}</p>
<p><span class="font-semibold">Base Salary:</span> {{ number_format($client->salesRep?->salary ?? 0, 2) }}</p>
<p><span class="font-semibold">Commission:</span> 
    {{ number_format($client->salesRep?->commissions?->where('client_id', $client->id)->sum('commission_amount') ?? 0, 2) }}
</p>
<p><span class="font-semibold">Total Payout:</span> 
    {{ number_format(
        ($client->salesRep?->salary ?? 0) + ($client->salesRep?->commissions?->where('client_id', $client->id)->sum('commission_amount') ?? 0),
        2
    ) }}
</p>

                        <!-- Actions -->
                        <div class="flex flex-wrap gap-3 mt-3">
                            <a href="{{ route('clients.show', $client) }}" class="text-green-600 hover:text-green-800 font-medium">View</a>
                            <a href="{{ route('clients.edit', $client) }}" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                            @if($client->status === 'pending')
                                <form action="{{ route('clients.approve', $client) }}" method="POST" class="inline">@csrf
                                    <button type="submit" class="text-green-600 hover:text-green-800 font-medium">Approve</button>
                                </form>
                                <form action="{{ route('clients.reject', $client) }}" method="POST" class="inline">@csrf
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Reject</button>
                                </form>
                            @endif
                            <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium"
                                        onclick="return confirm('{{ __('Are you sure you want to delete this client?') }}')">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center">{{ __('No Parties found.') }}</p>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $clients->links() }}
            </div>

        </div>
    </div>
</div>
@endsection
