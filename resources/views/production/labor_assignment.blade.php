@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-3xl font-bold text-gray-800">👷 Assign Labors</h1>
            <a href="{{ route('batch.flow.create') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                ← Back to Batch Flow
            </a>
        </div>

        {{-- Batch Info --}}
        <div class="bg-white rounded-2xl shadow-md p-6 mb-8 border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center md:text-left">
                <div>
                    <p class="text-sm text-gray-500">Start Date</p>
                    <p class="text-lg font-semibold">{{ \Carbon\Carbon::parse($batch->start_date)->format('d M, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">End Date</p>
                    <p class="text-lg font-semibold">{{ \Carbon\Carbon::parse($batch->end_date)->format('d M, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Product</p>
                    <p class="text-lg font-semibold">{{ $batch->product->name }} ({{ $batch->product->sku }})</p>
                </div>
            </div>
        </div>

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                <strong>Errors:</strong>
                <ul class="list-disc list-inside mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                {{ session('error') }}
            </div>
        @endif

        {{-- Labor Assignment Form --}}
        <form method="POST" action="{{ route('batch.flow.storeLabor', $batch->id) }}" class="space-y-10">
            @csrf

            <input type="hidden" name="batch_no" value="{{ $batch->batch_no }}">
            <input type="hidden" name="article_id" value="{{ $batch->product_id }}">

            @foreach($batch->product->processes->where('pivot.product_id', $batch->product_id) as $process)

               @php
    $processWorkers = $process->available_labors ?? collect();
    $variations = json_decode($batch->variations ?? '[]', true);
@endphp


                {{-- PROCESS CARD --}}
                <div class="bg-white rounded-3xl shadow-md border border-gray-100 p-6 hover:shadow-lg transition duration-300">
                    <div class="flex flex-wrap justify-between items-center mb-6 border-b pb-3">
                        <h3 class="text-xl font-bold text-indigo-700">{{ $process->name }}</h3>
                        <div class="text-gray-700 font-semibold">
                            💰 Rate: ₹{{ number_format($process->labor_rate ?? 0, 2) }}
                        </div>
                    </div>

                    {{-- LOOP THROUGH VARIATIONS --}}
                    @foreach($variations as $variationIndex => $variation)
                        @php
                            $sizes = array_keys($variation['sizes'] ?? []);
                            $sizeQty = $variation['sizes'] ?? [];
                            $variantName = $variation['name'] ?? 'Variation ' . ($variationIndex + 1);
                        @endphp

                        <div class="bg-gray-50 rounded-2xl p-5 mb-6">
                            @php
    $variantName = $variation['name'] ?? "Variation " . ($variationIndex + 1);
    $upperColor = $variation['color'] ?? null;
@endphp

<h4 class="text-lg font-semibold mb-3 flex items-center gap-3">
    🎨 {{ $variantName }}

    {{-- Only Variation Color --}}
    @if($upperColor)
        <span class="flex items-center gap-2 text-sm">
            <span class="w-5 h-5 rounded-full border" style="background: {{ $upperColor }}"></span>
            <span class="capitalize text-gray-700">{{ $upperColor }}</span>
        </span>
    @endif
</h4>


                            {{-- SIZE-WISE QUANTITY --}}
                            <div class="mb-4">
                                <h5 class="text-sm font-semibold text-gray-700 mb-2">📦 Size-wise Quantity</h5>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full border text-sm text-center rounded-lg overflow-hidden bg-white shadow-sm">
                                        <thead class="bg-indigo-50">
                                            <tr>
                                                @foreach($sizes as $size)
                                                    <th class="border px-3 py-2 text-gray-700 font-semibold">Size {{ $size }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @foreach($sizes as $size)
                                                    <td class="border px-3 py-2 font-medium text-gray-800 size-summary" 
                                                        data-process-id="{{ $process->id }}" 
                                                        data-variation-index="{{ $variationIndex }}"
                                                        data-size="{{ $size }}" 
                                                        data-available="{{ $sizeQty[$size] ?? 0 }}">
                                                        {{ $sizeQty[$size] ?? 0 }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- LABOR ASSIGNMENT TABLE --}}
                            <h5 class="text-sm font-semibold text-gray-700 mb-2">👷 Assign Workers ({{ $variantName }})</h5>
                            <div class="overflow-x-auto">
                                <table class="min-w-full border text-sm text-center bg-white shadow-sm rounded-lg">
                                    <thead class="bg-indigo-50">
                                        <tr>
                                            <th class="border px-3 py-2">Worker</th>
                                            @foreach($sizes as $size)
                                                <th class="border px-3 py-2">Size {{ $size }}</th>
                                            @endforeach
                                            <th class="border px-3 py-2">Start Date</th>
                                            <th class="border px-3 py-2">End Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($processWorkers as $worker)
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="border px-3 py-2 text-left">
                                                    <label class="flex items-center gap-2">
                                                        <input type="checkbox" class="worker-checkbox" 
                                                            data-process-id="{{ $process->id }}" 
                                                            name="labors[{{ $process->id }}][]"
                                                            value="{{ $worker->id }}">
                                                        <span class="font-medium text-gray-800">{{ $worker->name }}</span>
                                                    </label>
                                                </td>

                                                {{-- SIZE INPUTS --}}
                                               @foreach($sizes as $size)
    @php
        $available = $sizeQty[$size] ?? 0;
    @endphp
    <td class="border px-2 py-1">
        <input type="number" 
               min="0" 
               max="{{ $available }}"
               value="0"
               data-available="{{ $available }}"
               data-process-id="{{ $process->id }}"
               data-variation-index="{{ $variationIndex }}"
               data-size="{{ $size }}"
               name="worker_qty[{{ $process->id }}][{{ $worker->id }}][{{ $variationIndex }}][{{ $size }}]"
               class="worker-qty w-16 text-center border border-gray-300 rounded-lg py-1 text-sm focus:ring-2 focus:ring-indigo-400"
               disabled>
        <div class="text-xs text-red-600 mt-1 over-limit hidden">Max: {{ $available }}</div>
    </td>
@endforeach

                                                {{-- Dates --}}
                                                <td class="border px-3 py-1">
                                                    <input type="date" name="start_date[{{ $process->id }}][{{ $worker->id }}]"
                                                        class="start-date w-32 border rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-indigo-400" disabled>
                                                </td>
                                                <td class="border px-3 py-1">
                                                    <input type="date" name="end_date[{{ $process->id }}][{{ $worker->id }}]"
                                                        class="end-date w-32 border rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-indigo-400" disabled>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            {{-- Submit --}}
            <div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-2xl shadow-md text-lg transition transform hover:scale-[1.01]">
                    💾 Save & Continue
                </button>
            </div>
        </form>
    </div>
</div>

{{-- JS --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    /* ==============================================================
       1. Checkbox: Enable/Disable Inputs + Reset Values
       ============================================================== */
    document.querySelectorAll('.worker-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const row = this.closest('tr');
            const isChecked = this.checked;
            const qtyInputs = row.querySelectorAll('.worker-qty');
            const dateInputs = row.querySelectorAll('.start-date, .end-date');

            qtyInputs.forEach(input => {
                input.disabled = !isChecked;
                if (!isChecked) input.value = '0';
            });
            dateInputs.forEach(input => {
                input.disabled = !isChecked;
                if (!isChecked) input.value = '';
            });

            // Trigger input events to update summaries
            if (isChecked) {
                qtyInputs.forEach(i => i.dispatchEvent(new Event('input')));
            }
        });

        // Initialize state
        checkbox.dispatchEvent(new Event('change'));
    });

    /* ==============================================================
       2. Quantity Input: Clamp + Visual Feedback + Summary Update
       ============================================================== */
    document.querySelectorAll('.worker-qty').forEach(input => {
        const updateQty = () => {
            const available = parseInt(input.dataset.available) || 0;
            let value = parseInt(input.value) || 0;

            // Clamp value
            if (value > available) {
                value = available;
                input.value = value;
                input.classList.add('border-red-500', 'bg-red-50');
                const tooltip = input.parentElement.querySelector('.over-limit');
                if (tooltip) tooltip.classList.remove('hidden');
            } else {
                input.classList.remove('border-red-500', 'bg-red-50');
                const tooltip = input.parentElement.querySelector('.over-limit');
                if (tooltip) tooltip.classList.add('hidden');
            }

            // Update remaining summary
            const processId = input.dataset.processId;
            const variationIndex = input.dataset.variationIndex;
            const size = input.dataset.size;

            const allInputs = document.querySelectorAll(
                `.worker-qty[data-process-id="${processId}"][data-variation-index="${variationIndex}"][data-size="${size}"]`
            );

            let totalAssigned = 0;
            allInputs.forEach(i => {
                if (i.closest('tr').querySelector('.worker-checkbox').checked) {
                    totalAssigned += parseInt(i.value) || 0;
                }
            });

            const summary = document.querySelector(
                `.size-summary[data-process-id="${processId}"][data-variation-index="${variationIndex}"][data-size="${size}"]`
            );

            if (summary) {
                const remaining = Math.max(available - totalAssigned, 0);
                summary.textContent = remaining;

                if (totalAssigned > available) {
                    summary.classList.add('text-red-600', 'font-bold');
                } else {
                    summary.classList.remove('text-red-600', 'font-bold');
                }
            }
        };

        // Events
        input.addEventListener('input', updateQty);
        input.addEventListener('blur', updateQty);

        // Focus: clear "0"
        input.addEventListener('focus', () => {
            if (input.value === '0') input.value = '';
        });
        input.addEventListener('blur', () => {
            if (input.value === '') input.value = '0';
            updateQty();
        });
    });
});
</script>

@endsection
