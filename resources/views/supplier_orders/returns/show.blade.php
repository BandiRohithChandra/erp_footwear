@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 space-y-6">

    {{-- Header --}}
    {{-- Header --}}
<div class="bg-blue-100 p-5 rounded-lg shadow flex justify-between items-center">
    <h1 class="text-2xl font-bold text-blue-900">
        Return Order Details — RET-{{ str_pad($return->id, 5, '0', STR_PAD_LEFT) }}
    </h1>

    <div class="flex items-center gap-3">

        {{-- Return Bill Button --}}
        <a href="{{ route('supplier-orders.returns.bill', $return->id) }}"
           class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700">
            🧾 View Return Bill
        </a>

        <a href="{{ route('supplier-orders.returns') }}"
           class="text-blue-700 hover:underline">
            ← Back to Return Orders
        </a>

    </div>
</div>


    {{-- Details Card --}}
    <div class="bg-white shadow rounded-lg p-6 space-y-4">

        {{-- Meta Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <p class="text-gray-600 text-sm">Supplier</p>
                <p class="font-semibold">{{ $return->supplier->name ?? 'N/A' }}</p>
            </div>

            <div>
                <p class="text-gray-600 text-sm">PO Number</p>
                <p class="font-semibold">{{ $return->order->po_number ?? 'N/A' }}</p>
            </div>

            <div>
                <p class="text-gray-600 text-sm">Status</p>
                <span class="px-3 py-1 rounded-full text-white text-xs
                    @if($return->status === 'pending') bg-yellow-500
                    @elseif($return->status === 'returned') bg-blue-600
                    @elseif($return->status === 'completed') bg-green-600
                    @endif">
                    {{ ucfirst($return->status) }}
                </span>
            </div>

            <div>
                <p class="text-gray-600 text-sm">Created On</p>
                <p class="font-semibold">{{ $return->created_at->format('d M Y, h:i A') }}</p>
            </div>

        </div>

        {{-- Remarks --}}
        @if($return->remarks)
        <div>
            <p class="text-gray-600 text-sm">Remarks</p>
            <p class="bg-gray-100 p-3 rounded mt-1">{{ $return->remarks }}</p>
        </div>
        @endif

    </div>

    {{-- Items Table --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-gray-200 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">Sole</th>
                    <th class="px-4 py-3">Size</th>
                    <th class="px-4 py-3">Qty Returned</th>
                    <th class="px-4 py-3">Reason</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach ($return->items as $item)
                <tr class="hover:bg-gray-50">

                    {{-- Sole --}}
                    <td class="px-4 py-3">
                        {{ \App\Models\Sole::find($item['sole_id'])->name ?? 'Sole' }}
                    </td>

                    {{-- Size --}}
                    <td class="px-4 py-3 font-semibold">
                        {{ $item['size'] }}
                    </td>

                    {{-- Qty --}}
                    <td class="px-4 py-3">
                        {{ $item['qty'] }}
                    </td>

                    {{-- Reason --}}
                    <td class="px-4 py-3">
                        @if($item['reason'] === 'Other')
                            <strong>Other:</strong> {{ $item['other_reason'] ?? 'N/A' }}
                        @else
                            {{ $item['reason'] }}
                        @endif
                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</div>
@endsection
