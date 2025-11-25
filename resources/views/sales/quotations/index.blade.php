@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen p-6 md:p-8">

    {{-- Flash Messages --}}
    @foreach (['success', 'error'] as $msg)
        @if(session($msg))
            <div class="mb-4 p-4 rounded border 
                        {{ $msg === 'success' ? 'bg-green-100 text-green-800 border-green-200' : 'bg-red-100 text-red-800 border-red-200' }}">
                {{ session($msg) }}
            </div>
        @endif
    @endforeach

    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Quotations</h1>
        <a href="{{ route('quotations.create') }}"
           class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">
            ➕ Create Quotation
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('quotations.index') }}" class="mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status"
                    class="rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">All</option>
                @foreach (['pending', 'sent', 'accepted', 'rejected', 'expired'] as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit"
                class="px-4 py-2 rounded-lg bg-gray-200 text-sm font-medium hover:bg-gray-300 transition">
            🔍 Filter
        </button>
    </form>

    {{-- BULK ACCEPT BUTTON FORM ONLY --}}
    <form id="bulkAcceptForm" action="{{ route('quotations.bulkAccept') }}" method="POST" class="mb-4">
        @csrf
        <button type="submit"
            class="px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-medium hover:bg-green-700 transition">
            ✔ Accept Selected
        </button>
    </form>

    {{-- Quotations Table (NOT INSIDE FORM ANYMORE) --}}
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full border-collapse">
    <thead class="bg-gray-100">
        <tr>
            <th class="px-4 py-3">
                <input type="checkbox" id="selectAll" onclick="toggleAll(this)">
            </th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Quotation No</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Party</th>
            <th class="px-6 py-3 text-right text-sm font-semibold text-gray-600">Subtotal</th>
            <th class="px-6 py-3 text-right text-sm font-semibold text-gray-600">Tax</th>
            <th class="px-6 py-3 text-right text-sm font-semibold text-gray-600">Grand Total</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Created At</th>
            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-600">Actions</th>
        </tr>
    </thead>

    <tbody class="divide-y divide-gray-200">
        @forelse($quotations as $quotation)
            <tr class="hover:bg-gray-50 cursor-pointer"
                onclick="window.location='{{ route('quotations.show', $quotation) }}'">

                {{-- Checkbox --}}
                <td class="px-4 py-4" onclick="event.stopPropagation()">
                    @if(in_array($quotation->status, ['pending', 'sent']))
                        <input type="checkbox"
                               name="quotation_ids[]"
                               value="{{ $quotation->id }}"
                               form="bulkAcceptForm"
                               class="row-check">
                    @endif
                </td>

                <td class="px-6 py-4 text-sm text-gray-700 font-medium">{{ $quotation->quotation_no }}</td>
                <td class="px-6 py-4 text-sm text-gray-700">{{ $quotation->client->name ?? 'N/A' }}</td>
                <td class="px-6 py-4 text-sm text-gray-700 text-right">₹{{ number_format($quotation->subtotal, 2) }}</td>
                <td class="px-6 py-4 text-sm text-gray-700 text-right">₹{{ number_format($quotation->tax, 2) }}</td>
                <td class="px-6 py-4 text-sm text-gray-700 font-semibold text-right">₹{{ number_format($quotation->grand_total, 2) }}</td>

                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @switch($quotation->status)
                            @case('pending') bg-yellow-100 text-yellow-700 @break
                            @case('sent') bg-blue-100 text-blue-700 @break
                            @case('accepted') bg-green-100 text-green-700 @break
                            @case('rejected') bg-red-100 text-red-700 @break
                            @default bg-gray-100 text-gray-700
                        @endswitch">
                        {{ ucfirst($quotation->status) }}
                    </span>
                </td>

                <td class="px-6 py-4 text-sm text-gray-600">{{ $quotation->created_at->format('d M Y') }}</td>

                {{-- ACTION BUTTONS --}}
                <td class="px-6 py-4 text-sm text-center" onclick="event.stopPropagation()">
                    <div class="flex flex-wrap gap-2 justify-center items-center">

                        {{-- View --}}
                        <a href="{{ route('quotations.show', $quotation) }}"
                           class="px-3 py-1 rounded bg-blue-100 text-blue-700 text-sm hover:bg-blue-200 transition">
                           View
                        </a>

                        {{-- Edit --}}
                        @if($quotation->status === 'pending')
                            <a href="{{ route('quotations.edit', $quotation) }}"
                               class="px-3 py-1 rounded bg-green-100 text-green-700 text-sm hover:bg-green-200 transition">
                               Edit
                            </a>
                        @endif

                        {{-- Accept --}}
                        @if($quotation->status === 'pending' || $quotation->status === 'sent')
                            <form action="{{ route('quotations.accept', $quotation->id) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('PATCH')
                                <button class="px-3 py-1 rounded bg-green-600 text-white text-sm hover:bg-green-700 transition">
                                    Accept
                                </button>
                            </form>
                        @endif

                        {{-- Delete --}}
                        <form action="{{ route('quotations.destroy', $quotation) }}" method="POST" class="inline"
                              onsubmit="return confirm('Are you sure?');">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 rounded bg-red-100 text-red-700 text-sm hover:bg-red-200 transition">
                                Delete
                            </button>
                        </form>

                        {{-- -------------------- CREATE INVOICE BUTTON -------------------- --}}
                        @php
                            $totalQty = $quotation->products->sum('pivot.quantity');
                            $invoicedQty = 0;

                            foreach ($quotation->invoices as $invoice) {
                                $items = json_decode($invoice->items, true) ?? [];
                                foreach ($items as $item) {
                                    $invoicedQty += $item['quantity'];
                                }
                            }

                            $remainingQty = $totalQty - $invoicedQty;
                        @endphp

                        @if($quotation->status === 'accepted' && $quotation->orders->isNotEmpty() && $remainingQty > 0)
                            <button 
                                type="button"
                                onclick="event.stopPropagation(); openPOModal({{ $quotation->orders->first()->id }})"
                                class="px-3 py-1 rounded bg-yellow-100 text-yellow-700 text-sm hover:bg-yellow-200 transition">
                                🧾 Create Invoice
                            </button>
                        @endif

                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                    No quotations found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $quotations->links() }}
    </div>

</div>


<!-- 🌟 PO Number Modal -->
<div id="poModal"
     class="fixed inset-0 hidden items-center justify-center z-50 
            backdrop-blur-md bg-white/20 transition-all duration-300 ease-in-out">

  <div id="poModalContent"
       class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md relative transform scale-95 opacity-0 
              transition-all duration-300 border border-gray-100">

      <!-- Close -->
      <button onclick="closePOModal()" 
              class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 transition text-xl">
          &times;
      </button>

      <div class="text-center mb-6">
          <h2 class="text-2xl font-bold text-gray-800">Create Invoice</h2>
          <p class="text-sm text-gray-500 mt-1">Enter a Purchase Order Number (Optional)</p>
      </div>

      <!-- Input -->
      <div class="mb-6">
          <label for="poNumberInput" class="block text-sm font-semibold text-gray-700 mb-2">PO Number</label>
          <input type="text" id="poNumberInput"
                 placeholder="Enter PO Number (Optional)"
                 class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-gray-700 
                        focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
      </div>

      <!-- Buttons -->
      <div class="flex justify-end gap-3">
          <button onclick="closePOModal()"
                  class="px-5 py-2.5 rounded-lg border border-gray-300 bg-gray-100 
                         text-gray-700 font-medium hover:bg-gray-200">
              Cancel
          </button>

          <button id="confirmPOBtn"
                  class="px-5 py-2.5 rounded-lg bg-blue-600 text-white font-semibold shadow-sm 
                         hover:bg-blue-700 focus:ring-2 focus:ring-blue-300">
              Create Invoice
          </button>
      </div>
  </div>
</div>

<script>
let selectedOrderId = null;

function openPOModal(orderId) {
    selectedOrderId = orderId;

    const modal = document.getElementById('poModal');
    const content = document.getElementById('poModalContent');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    setTimeout(() => {
        content.classList.remove('opacity-0', 'scale-95');
        content.classList.add('opacity-100', 'scale-100');
    }, 50);
}

function closePOModal() {
    const modal = document.getElementById('poModal');
    const content = document.getElementById('poModalContent');

    content.classList.add('opacity-0', 'scale-95');
    content.classList.remove('opacity-100', 'scale-100');

    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.getElementById('poNumberInput').value = '';
        selectedOrderId = null;
    }, 200);
}

document.getElementById('confirmPOBtn').addEventListener('click', function() {
    const poNumber = document.getElementById('poNumberInput').value.trim();

    if (!selectedOrderId) return;

    let url = `/sales/invoices/create/${selectedOrderId}`;
    if (poNumber !== '') {
        url += `?po_no=${encodeURIComponent(poNumber)}`;
    }

    window.location.href = url;
});
</script>


<script>
function toggleAll(source) {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = source.checked);
}
</script>

@endsection
