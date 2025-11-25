@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row items-center gap-6 mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4 md:mb-0">Batch List</h2>
            <a href="{{ route('batch.flow.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow hover:bg-indigo-700 transition">
               + Create New Batch
            </a>

            <a href="{{ route('batch.flow.update_status') }}" 
   class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg shadow hover:bg-green-700 transition">
   Update Status
</a>

        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg shadow">
                {{ session('success') }}
            </div>
        @endif

        {{-- Table --}}
       <div class="bg-white shadow rounded-2xl overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 table-fixed">
        <thead class="bg-gray-50 sticky top-0">
    <thead class="bg-gray-50 sticky top-0">
<tr>
    <th class="w-1/12 px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Batch No</th>
    <th class="w-2/12 px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Article</th>
    <th class="w-1/12 px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Image</th> <!-- New column -->
    <th class="w-1/12 px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Start Date</th>
    <th class="w-1/12 px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">End Date</th>
    <th class="w-2/12 px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
    <th class="w-3/12 px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Workers / Labors</th>
    <th class="w-1/12 px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created At</th>
    <th class="w-1/12 px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
</tr>
</thead>

<tbody class="bg-white divide-y divide-gray-200">
@forelse($batches as $batch)
@php
    // 🧠 Check Sole stock using product_id
    $sole = \App\Models\Sole::where('product_id', $batch->product->id)->first();

    $hasLowStock = false;
    $lowStockSizes = [];

    if ($sole) {
        $totalAvailable = (float)($sole->available_qty ?? 0);
        $totalQty = (int)($sole->quantity ?? 0);
        $sizesQty = $sole->sizes_qty ?? [];


        // ⚠️ Trigger low stock when stock <= threshold (e.g. 5)
        $lowStockThreshold = 5;

        if ($totalAvailable <= $lowStockThreshold || $totalQty <= $lowStockThreshold) {
            $hasLowStock = true;

            // Optional: identify low-stock sizes if available
            if (is_array($sizesQty)) {
                foreach ($sizesQty as $size => $qty) {
                    if ((int)$qty <= $lowStockThreshold) {
                        $lowStockSizes[$size] = $qty;
                    }
                }
            }
        }
    }
@endphp



<tr class="hover:bg-gray-50 transition cursor-pointer {{ $hasLowStock ? 'border-l-4 border-yellow-400 bg-yellow-50/30' : '' }}"
    onclick="window.location='{{ route('batch.flow.show', $batch->id) }}'">

    {{-- ✅ Batch No --}}
    <td class="px-6 py-4 font-medium text-gray-800">{{ $batch->batch_no }}</td>

    {{-- ✅ Article Name --}}
    <td class="px-6 py-4 text-gray-700">{{ $batch->product->name ?? '-' }}</td>

    {{-- ✅ Product Image --}}
    <td class="px-6 py-4">
        @php
            $firstImage = $batch->product->variations[0]['images'][0] ?? $batch->product->image ?? null;
            $imageUrl = $firstImage ? asset('storage/' . $firstImage) : null;
        @endphp
        @if($imageUrl)
            <div class="w-16 h-16 flex items-center justify-center border rounded-md bg-gray-50 overflow-hidden">
                <img src="{{ $imageUrl }}" alt="{{ $batch->product->name }}" class="max-w-full max-h-full object-contain">
            </div>
        @else
            <span class="text-gray-400">No Image</span>
        @endif
    </td>

    {{-- ✅ Start / End Dates --}}
    <td class="px-6 py-4 text-gray-700">{{ $batch->start_date ? $batch->start_date->format('d M Y') : '-' }}</td>
    <td class="px-6 py-4 text-gray-700">{{ $batch->end_date ? $batch->end_date->format('d M Y') : '-' }}</td>

    {{-- ✅ Process Status + Low Stock Badge --}}
    <td class="px-6 py-4 text-gray-700 align-top">
        {{-- 🟡 Low Stock Warning --}}
        @if($hasLowStock)
            <div class="mb-2 inline-flex items-center bg-yellow-100 border border-yellow-300 text-yellow-800 text-xs font-semibold px-2 py-1 rounded-full shadow-sm"
                 title="Low stock for sizes: {{ implode(', ', array_keys($lowStockSizes)) }}">
                ⚠️ Low Stock
            </div>
        @endif

        @php
            $processData = DB::table('employee_batch')
                ->join('processes', 'employee_batch.process_id', '=', 'processes.id')
                ->join('employees', 'employee_batch.employee_id', '=', 'employees.id')
                ->select(
                    'processes.name as process_name',
                    'employees.name as worker_name',
                    'employees.labor_type',
                    'employee_batch.labor_status'
                )
                ->where('employee_batch.batch_id', $batch->id)
                ->orderBy('processes.name')
                ->get()
                ->groupBy('process_name');
        @endphp

        @forelse($processData as $processName => $workers)
            <div class="mb-3 p-2 rounded-lg border border-gray-100 bg-gray-50 shadow-sm hover:shadow-md transition">
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-semibold text-indigo-700">⚙️ {{ $processName }}</span>
                </div>

                <div class="space-y-1 pl-2">
                    @foreach($workers as $w)
                        @php
                            $status = strtolower($w->labor_status ?? 'pending');
                            $colorClass = match($status) {
                                'completed' => 'bg-green-100 text-green-800',
                                'in_progress' => 'bg-blue-100 text-blue-800',
                                'paid' => 'bg-purple-100 text-purple-800',
                                default => 'bg-yellow-100 text-yellow-800',
                            };
                            $statusLabel = ucfirst(str_replace('_', ' ', $status));
                        @endphp

                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-800 font-medium">{{ $w->worker_name }}</span>
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $colorClass }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <span class="text-gray-500 italic">No processes found</span>
        @endforelse
    </td>

    {{-- ✅ Workers --}}
    <td class="px-6 py-4 text-gray-700">
        @foreach($batch->workers as $worker)
            @php
                $pivot = $worker->pivot;
                $process = $batch->product->processes->firstWhere('id', $pivot->process_id);
                $rate = $process->pivot->labor_rate ?? 0;
            @endphp
            <div class="mb-1">
                <span class="font-medium text-gray-800">{{ $worker->name }}</span>
                <span class="text-gray-600">
                    - Qty: {{ $pivot->quantity ?? 0 }}, Rate: ₹{{ number_format($rate, 2) }}
                </span>
            </div>
        @endforeach
    </td>

    {{-- ✅ Created Date --}}
    <td class="px-6 py-4 text-gray-700">{{ $batch->created_at->format('d M Y') }}</td>

    {{-- ✅ Actions --}}
    <td class="px-6 py-4 text-gray-700">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('batch.flow.show', $batch->id) }}" class="text-indigo-600 hover:underline">View</a>
            <a href="{{ route('batch.flow.edit', $batch->id) }}" class="text-yellow-600 hover:underline">Edit</a>
            <form action="{{ route('batch.flow.destroy', $batch->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" onclick="event.stopPropagation();">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline">Delete</button>
            </form>
        </div>
    </td>

</tr>
@empty
<tr>
    <td colspan="9" class="px-6 py-4 text-center text-gray-500">No batches found.</td>
</tr>
@endforelse
</tbody>


    </table>
</div>

    </div>
</div>
@endsection
