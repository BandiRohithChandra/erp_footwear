@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 space-y-6">

    {{-- Page Header --}}
   <div class="flex gap-3 items-center">
    <form id="returnForm" onsubmit="return goToReturnCreate()" class="flex gap-2">

    <select id="orderSelect"
            name="order_id"
            class="border rounded px-3 py-2 text-sm">
        <option value="">Select Purchase Order</option>
        @foreach(\App\Models\SupplierOrder::all() as $order)
            <option value="{{ $order->id }}">
                {{ $order->po_number }} — {{ $order->supplier->name }}
            </option>
        @endforeach
    </select>

    <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
        + Create Return Order
    </button>

</form>

<script>
function goToReturnCreate() {
    let orderId = document.getElementById('orderSelect').value;

    if (!orderId) {
        alert("Please select a Purchase Order first.");
        return false;
    }

    // Correct dynamic redirect to the route
    window.location.href = `/supplier-orders/${orderId}/return`;
    return false; // prevent normal form submit
}
</script>
</div>


    {{-- Table --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-gray-200 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">Return ID</th>
                    <th class="px-4 py-3">Supplier</th>
                    <th class="px-4 py-3">PO Number</th>
                    <th class="px-4 py-3">Items Returned</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>

           <tbody class="divide-y">

@forelse ($returns as $return)
<tr class="hover:bg-gray-50 cursor-pointer"
    onclick="window.location='{{ route('supplier-orders.return.show', $return->id) }}'">

    {{-- Return ID --}}
    <td class="px-4 py-3 font-semibold">
        RET-{{ str_pad($return->id, 5, '0', STR_PAD_LEFT) }}
    </td>

    {{-- Supplier --}}
    <td class="px-4 py-3">
        {{ $return->supplier->name ?? 'N/A' }}
    </td>

    {{-- PO Number --}}
    <td class="px-4 py-3">
        {{ $return->order->po_number ?? 'N/A' }}
    </td>

    {{-- Items Returned --}}
    <td class="px-4 py-3">
        @foreach ($return->items as $item)
            <div class="text-gray-700 leading-tight">
                Size: <strong>{{ $item['size'] }}</strong>,
                Qty: <strong>{{ $item['qty'] }}</strong>

                @if(isset($item['reason']) && $item['reason'] !== 'Other')
                    <span class="text-gray-500">({{ $item['reason'] }})</span>
                @endif

                @if(isset($item['other_reason']) && $item['other_reason'])
                    <span class="text-gray-500">(Other: {{ $item['other_reason'] }})</span>
                @endif
            </div>
        @endforeach
    </td>

    {{-- Status --}}
    <td class="px-4 py-3">
        <span class="px-3 py-1 rounded-full text-white text-xs
            @if($return->status === 'pending') bg-yellow-500
            @elseif($return->status === 'returned') bg-blue-600
            @elseif($return->status === 'completed') bg-green-600
            @endif">
            {{ ucfirst($return->status) }}
        </span>
    </td>

    {{-- Date --}}
    <td class="px-4 py-3">
        {{ $return->created_at->format('d M Y') }}
    </td>

    {{-- Actions --}}
   {{-- Actions --}}
<td class="px-4 py-3 text-right space-x-4"
    onclick="event.stopPropagation();">

    {{-- View button --}}
    <a href="{{ route('supplier-orders.return.show', $return->id) }}"
       class="text-blue-600 hover:underline">
        View
    </a>

    {{-- Edit button (only pending) --}}
    @if($return->status === 'pending')
        <a href="{{ route('supplier-orders.return.edit', $return->id) }}"
           onclick="event.stopPropagation();"
           class="text-indigo-600 hover:underline">
            Edit
        </a>
    @endif

    {{-- Complete button --}}
    @if($return->status === 'pending')
        <form action="{{ route('supplier-orders.return.complete', $return->id) }}"
              method="POST" 
              class="inline"
              onclick="event.stopPropagation();">
            @csrf
            
            <button type="submit"
                    class="text-green-600 hover:underline"
                    onclick="return confirm('Mark this return as completed?');">
                Mark Completed
            </button>
        </form>
    @endif

</td>

</tr>
@empty
<tr>
    <td colspan="7" class="text-center py-5 text-gray-500">
        No return orders found.
    </td>
</tr>
@endforelse

</tbody>

        </table>
    </div>

</div>
@endsection
