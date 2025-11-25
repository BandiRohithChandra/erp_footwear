@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 space-y-6">

    {{-- Page Title --}}
    <div class="bg-blue-100 p-5 rounded-lg shadow flex justify-between items-center">
        <h1 class="text-2xl font-bold text-blue-900">
            Edit Return Order (PO: {{ $order->po_number }})
        </h1>

        <a href="{{ route('supplier-orders.index') }}"
           class="text-blue-700 hover:underline">
            ← Back
        </a>
    </div>

    {{-- Edit Form --}}
  <form action="{{ route('supplier-orders.return.save', $returnOrder->id) }}" 
      method="POST" 
      class="space-y-6">
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
    @foreach($item['sizes_qty'] as $size => $receivedQty)

        @php
            $rid = $item['id'].'_'.$size;
            $oldReturn = $returnItems[$item['id']][$size] ?? null;

            $oldQty = $oldReturn['qty'] ?? 0;
            $oldReason = $oldReturn['reason'] ?? '';
            $oldOther = $oldReturn['other_reason'] ?? '';
        @endphp

        <tr class="hover:bg-gray-50">

            {{-- Sole --}}
            <td class="px-4 py-3 font-medium">
                {{ \App\Models\Sole::find($item['id'])->name ?? 'Sole' }}
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
                       name="items[{{ $rid }}][qty]"
                       class="qty-input border rounded px-2 py-1 w-20"
                       data-max="{{ $receivedQty }}"
                       value="{{ $oldQty }}"
                       {{ $receivedQty == 0 ? 'disabled' : '' }}>
                
                <span class="text-red-600 text-xs hidden error-msg">Exceeds available qty!</span>

                <input type="hidden" name="items[{{ $rid }}][sole_id]" value="{{ $item['id'] }}">
                <input type="hidden" name="items[{{ $rid }}][size]" value="{{ $size }}">
            </td>

            {{-- Reason --}}
            <td class="px-4 py-3">
                <select class="reason-select border rounded px-2 py-1 w-full"
                        name="items[{{ $rid }}][reason]"
                        {{ $receivedQty == 0 ? 'disabled' : '' }}>
                    <option value="">Select Reason</option>
                    <option value="Damaged" {{ $oldReason == 'Damaged' ? 'selected' : '' }}>Damaged</option>
                    <option value="Defective" {{ $oldReason == 'Defective' ? 'selected' : '' }}>Defective</option>
                    <option value="Wrong Size" {{ $oldReason == 'Wrong Size' ? 'selected' : '' }}>Wrong Size</option>
                    <option value="Wrong Color" {{ $oldReason == 'Wrong Color' ? 'selected' : '' }}>Wrong Color</option>
                    <option value="Other" {{ $oldReason == 'Other' ? 'selected' : '' }}>Other</option>
                </select>

                {{-- Other reason --}}
                <input type="text"
                       class="other-input border rounded px-2 py-1 mt-2 w-full {{ $oldReason == 'Other' ? '' : 'hidden' }}"
                       name="items[{{ $rid }}][other_reason]"
                       placeholder="Enter custom reason"
                       value="{{ $oldOther }}">
            </td>

        </tr>

    @endforeach
@endforeach

                </tbody>
            </table>
        </div>

        {{-- Remarks --}}
        <div>
            <label class="font-medium">Remarks</label>
            <textarea name="remarks" class="w-full border rounded p-3 mt-1" rows="3">
                {{ $returnOrder->remarks }}
            </textarea>
        </div>

        {{-- Submit --}}
        <div class="text-right">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                Update Return Order
            </button>
        </div>

    </form>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    // 1) Prevent > received qty
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('input', function () {
            let max = parseInt(this.dataset.max);
            if (parseInt(this.value) > max) {
                this.value = max;
                this.closest('td').querySelector('.error-msg').classList.remove('hidden');
            } else {
                this.closest('td').querySelector('.error-msg').classList.add('hidden');
            }
        });
    });

    // 2) Toggle OTHER reason input
    document.querySelectorAll('.reason-select').forEach(select => {
        select.addEventListener('change', function () {
            let other = this.closest('td').querySelector('.other-input');
            if (this.value === "Other") {
                other.classList.remove('hidden');
                other.required = true;
            } else {
                other.classList.add('hidden');
                other.required = false;
                other.value = "";
            }
        });
    });

});
</script>

@endsection
