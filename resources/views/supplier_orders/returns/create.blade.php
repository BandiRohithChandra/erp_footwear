@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 space-y-6">

    {{-- Page Title --}}
    <div class="bg-blue-100 p-5 rounded-lg shadow flex justify-between items-center">
        <h1 class="text-2xl font-bold text-blue-900">
            Create Return Order (PO: {{ $order->po_number }})
        </h1>
        <a href="{{ route('supplier-orders.index') }}"
           class="text-blue-700 hover:underline">
            ← Back
        </a>
    </div>

    {{-- Return Form --}}
    <form action="{{ route('supplier-orders.return.store') }}" method="POST" class="space-y-6">
        @csrf

        <input type="hidden" name="supplier_id" value="{{ $order->supplier_id }}">
        <input type="hidden" name="order_id" value="{{ $order->id }}">

        {{-- Items Table --}}
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-gray-200 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Sole</th>
                        <th class="px-4 py-3">Size</th>
                        <th class="px-4 py-3">Received Qty</th>
                        <th class="px-4 py-3">Return Qty</th>
                        <th class="px-4 py-3">Reason</th>
                    </tr>
                </thead>

              <tbody class="divide-y">

@foreach($items as $item)

    @php
        $soleName = \App\Models\Sole::find($item['id'])->name ?? 'Sole';
        $groupId = 'sole_' . $item['id'];
    @endphp

    {{-- GROUP HEADER --}}
    <tr class="bg-gray-100 cursor-pointer"
        onclick="document.getElementById('{{ $groupId }}').classList.toggle('hidden')">
        <td colspan="5" class="px-4 py-3 font-bold text-blue-900 flex justify-between items-center">
            {{ $soleName }}

            <span class="text-sm text-gray-600">Click to Expand</span>
        </td>
    </tr>

    {{-- GROUP BODY --}}
    <tbody id="{{ $groupId }}" class="hidden bg-white">

        @foreach($item['sizes_qty'] as $size => $receivedQty)
        <tr class="hover:bg-gray-50">

            {{-- Sole (hidden; group header already shows name) --}}
            <td class="px-4 py-3">
                <span class="text-gray-400">—</span>
            </td>

            {{-- Size --}}
            <td class="px-4 py-3 font-semibold">
                {{ $size }}
            </td>

            {{-- Received Qty --}}
            <td class="px-4 py-3">
                {{ $receivedQty }}
            </td>

            {{-- Return Qty --}}
            <td class="px-4 py-3 w-32">
                <input type="number"
                       min="0"
                       max="{{ $receivedQty }}"
                       name="items[{{ $item['id'] }}_{{ $size }}][qty]"
                       class="qty-input border rounded px-2 py-1 w-20"
                       data-max="{{ $receivedQty }}"
                       {{ $receivedQty == 0 ? 'disabled' : '' }}>
                
                <span class="text-red-600 text-xs hidden error-msg">Exceeds available qty!</span>

                <input type="hidden"
                       name="items[{{ $item['id'] }}_{{ $size }}][sole_id]"
                       value="{{ $item['id'] }}">
                <input type="hidden"
                       name="items[{{ $item['id'] }}_{{ $size }}][size]"
                       value="{{ $size }}">
            </td>

            {{-- Reason --}}
            <td class="px-4 py-3">
                <select class="reason-select border rounded px-2 py-1 w-full"
                        name="items[{{ $item['id'] }}_{{ $size }}][reason]"
                        {{ $receivedQty == 0 ? 'disabled' : '' }}>
                    <option value="">Select Reason</option>
                    <option value="Damaged">Damaged</option>
                    <option value="Defective">Defective</option>
                    <option value="Wrong Size">Wrong Size</option>
                    <option value="Wrong Color">Wrong Color</option>
                    <option value="Other">Other</option>
                </select>

                <input type="text"
                       class="other-input border rounded px-2 py-1 mt-2 w-full hidden"
                       placeholder="Enter custom reason"
                       name="items[{{ $item['id'] }}_{{ $size }}][other_reason]">
            </td>

        </tr>
        @endforeach

    </tbody>

@endforeach

</tbody>


            </table>
        </div>

        {{-- Remarks --}}
        <div>
            <label class="font-medium">Remarks (optional)</label>
            <textarea name="remarks"
                      class="w-full border rounded p-3 mt-1"
                      rows="3"
                      placeholder="Add any overall remark for this return"></textarea>
        </div>

        {{-- Submit --}}
        <div class="text-right">
           <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
    Submit Return Order
</button>


        </div>

    </form>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    // 1) Prevent return qty > received qty
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('input', function () {
            let max = parseInt(this.dataset.max);
            let value = parseInt(this.value);

            let errorMsg = this.parentNode.querySelector('.error-msg');

            if (value > max) {
                this.value = max;
                errorMsg.classList.remove('hidden');
            } else {
                errorMsg.classList.add('hidden');
            }
        });
    });


    // 2) Reason dropdown show OTHER input
    document.querySelectorAll('.reason-select').forEach(select => {
        select.addEventListener('change', function () {
            let row = this.closest('td');
            let otherInput = row.querySelector('.other-input');

            if (this.value === "Other") {
                otherInput.classList.remove('hidden');
                otherInput.required = true;
            } else {
                otherInput.classList.add('hidden');
                otherInput.required = false;
                otherInput.value = "";
            }
        });
    });

});
</script>
@endsection
