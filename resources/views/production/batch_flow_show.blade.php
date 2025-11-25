@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" 
     style="font-family: Arial, Helvetica, sans-serif;">

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- 🔹 Top Action Bar --}}
    <div class="mb-4 flex justify-between items-center flex-wrap gap-3">
      {{-- 🔸 Left: Back Button --}}
      <a href="{{ route('batch.flow.index') }}" 
         class="flex items-center gap-1 text-indigo-600 hover:text-indigo-800 font-medium text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Batch List
      </a>

      {{-- 🔸 Right: Action Buttons --}}
      <div class="flex items-center gap-3">
        {{-- Print --}}
        <button onclick="printBatchDetails()" 
                class="flex items-center gap-2 px-4 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-md shadow hover:bg-indigo-700 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 22h12v-4H6v4z"/>
          </svg>
          Print
        </button>

        {{-- Generate Delivery Note --}}
        <button type="button" onclick="openDeliveryModal({{ $batch->id }})" class="btn-delivery generate">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Generate Delivery Note
        </button>

        {{-- View All Delivery Notes --}}
        <a href="{{ route('delivery.notes.byBatch', $batch->id) }}" class="btn-delivery view">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h5l2 2h6a2 2 0 012 2v12a2 2 0 01-2 2z" />
          </svg>
          View All Delivery Notes
        </a>
      </div>
    </div>

    {{-- 🔹 Styles for Buttons --}}
    <style>
      .btn-delivery{display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;text-decoration:none;color:#fff;padding:6px 14px;border-radius:6px;box-shadow:0 2px 5px rgba(0,0,0,0.15);transition:background-color .25s,transform .15s}
      .btn-delivery.generate{background:#7c3aed}.btn-delivery.generate:hover{background:#6d28d9;transform:translateY(-1px)}
      .btn-delivery.view{background:#16a34a}.btn-delivery.view:hover{background:#15803d;transform:translateY(-1px)}
      .btn-delivery .icon{width:16px;height:16px;stroke:white}
    </style>

    {{-- 🔹 Batch Details --}}
    <div id="batch-details-to-print" class="space-y-5">
      @if($employeeAssignments->isNotEmpty())
        @foreach($employeeAssignments as $employeeId => $assignments)
          @php
            $worker = (object)[
              'id' => $employeeId,
              'name' => $assignments->first()->employee_name,
              'labor_type' => $assignments->first()->labor_type,
            ];
            $statusClasses = [
              'pending' => 'bg-yellow-100 text-yellow-800',
              'in progress' => 'bg-blue-100 text-blue-800',
              'completed' => 'bg-green-100 text-green-800',
            ];
            $soles = $batch->product->soles ?? collect();
            $variations = is_string($batch->variations) ? json_decode($batch->variations, true) : ($batch->variations ?? []);
            $allSizes = collect($variations)->flatMap(fn($v) => array_keys($v['sizes'] ?? []))->unique()->sort()->values()->toArray();
           $totalQty = collect($variations)
    ->flatMap(fn($v) => $v['sizes'] ?? [])
    ->sum(function ($info) {
        // Safely handle both numeric and array-based size entries
        if (is_array($info)) {
            // Prefer available > ordered > delivered
            return (int)($info['available'] ?? $info['ordered'] ?? $info['delivered'] ?? 0);
        }
        return (int)$info;
    });

          @endphp

          <div class="worker-section bg-white shadow-sm rounded-lg p-3 border">

            {{-- Product & Worker Info --}}
            <div class="flex flex-row gap-4 items-start">
              <div class="w-32 flex-shrink-0">
                @php
                  $firstImage = $batch->product->variations[0]['images'][0] ?? $batch->product->image ?? null;
                  $imageUrl = $firstImage ? asset('storage/' . $firstImage) : null;
                @endphp
                @if($imageUrl)
                  <img src="{{ $imageUrl }}" alt="{{ $batch->product->name }}" class="w-full h-auto rounded border object-contain shadow-sm">
                @else
                  <div class="w-full h-28 flex items-center justify-center bg-gray-100 text-gray-400 rounded">No Image</div>
                @endif
              </div>

              {{-- Sole + Worker --}}
              <div class="flex-1 grid grid-cols-2 gap-4">
                {{-- Sole Details --}}
                <!-- <div>
                  <h3 class="font-semibold text-gray-800 mb-1 text-sm">Sole Details</h3>
                  @if($soles->isNotEmpty())
                    <table class="w-full border border-gray-300 text-xs">
                      <thead class="bg-gray-100">
                        <tr><th class="border p-1 text-left">Name</th><th class="border p-1 text-left">Color</th><th class="border p-1 text-left">Type</th></tr>
                      </thead>
                      <tbody>
                        @foreach($soles as $sole)
                          <tr><td class="border p-1">{{ $sole->name }}</td><td class="border p-1">{{ ucfirst($sole->color ?? '-') }}</td><td class="border p-1">{{ $sole->sole_type ?? '-' }}</td></tr>
                        @endforeach
                      </tbody>
                    </table>
                  @else
                    <p class="text-gray-500 text-xs">Not available</p>
                  @endif
                </div> -->

                {{-- Worker Details --}}
                <div>
                  <h3 class="font-semibold text-gray-800 mb-1 text-sm">Assigned Worker & Processes</h3>
                  <div class="p-2 border rounded bg-gray-50 text-xs">
                    <p class="font-medium">
                      {{ $worker->name }}
                      @if($worker->labor_type)
                        <span class="text-gray-500 text-[10px]">({{ $worker->labor_type }})</span>
                      @endif
                    </p>
                    <ul class="list-disc pl-4 mt-1 space-y-0.5">
                      @forelse($employeeAssignments[$worker->id] ?? [] as $assignment)
                        <li>
                          <span class="font-semibold">{{ $assignment->process_name ?? 'Unknown' }}</span>
                          — Qty: {{ $assignment->assigned_quantity ?? 0 }}
                          — Status: {{ ucfirst($assignment->labor_status ?? 'pending') }}
                          <div class="text-gray-500 text-[10px]">Start: {{ $assignment->start_date ? \Carbon\Carbon::parse($assignment->start_date)->format('d M, Y') : '-' }}</div>
                        </li>
                      @empty
                        <li class="text-gray-500">No processes</li>
                      @endforelse
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            {{-- Batch Info --}}
           <div class="mt-3">
    <table class="w-full text-xs border border-gray-200 rounded">
        <tbody>

            <tr>
                <td class="font-medium p-1 border">Batch No:</td>
                <td class="p-1 border">{{ $batch->batch_no }}</td>

                <td class="font-medium p-1 border">PO No:</td>
                <td class="p-1 border">{{ $batch->po_no ?? '-' }}</td>
            </tr>

            {{-- ⭐ Added BRAND row --}}
            <tr>
                <td class="font-medium p-1 border">Brand:</td>
                <td class="p-1 border">{{ $quotation->brand_name ?? 'N/A' }}</td>

                <td class="font-medium p-1 border">Article:</td>
                <td class="p-1 border">{{ $batch->product->name ?? '-' }}</td>
            </tr>

            <tr>
                <td class="font-medium p-1 border">Parties:</td>
                <td class="p-1 border">{{ $clients->map(fn($c) => $c->business_name ?: $c->name)->join(', ') }}</td>

                <td class="font-medium p-1 border">Article No:</td>
                <td class="p-1 border">{{ $batch->product->sku ?? '-' }}</td>
            </tr>

            <tr>
                <td class="font-medium p-1 border">Status:</td>
                <td class="p-1 border">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $statusClasses[strtolower($batch->status)] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($batch->status) }}
                    </span>
                </td>

                <td class="font-medium p-1 border">Created:</td>
                <td class="p-1 border">{{ $batch->created_at->format('d M Y') }}</td>
            </tr>

            <tr>
                <td class="font-medium p-1 border">Start:</td>
                <td class="p-1 border">{{ $batch->start_date ? \Carbon\Carbon::parse($batch->start_date)->format('d M Y') : '-' }}</td>

                <td class="font-medium p-1 border"></td>
                <td class="p-1 border"></td>
            </tr>

        </tbody>
    </table>
</div>


            {{-- ✅ Variations Table Restored --}}
            {{-- ✅ Labor Assigned Variations Table (per worker, not total) --}}
<div class="bg-white shadow-sm rounded-lg p-3 border mt-3">
  <h2 class="font-semibold text-gray-800 mb-1 text-sm">👷 Labor Assigned Variations</h2>

  @php
      $allSizes = range(35, 44);
      $laborVariations = [];

      // Build color → size → qty mapping for THIS worker only
      foreach ($assignments as $assignment) {
    foreach ($assignment->variations ?? [] as $v) {
        $color = $v['color'] ?? '-';
        $soleColor = $v['sole_color'] ?? '-';

        $soleName = $v['sole_name'] ?? '-';



        foreach ($v['sizes'] ?? [] as $size => $qty) {
            $laborVariations[$color]['sizes'][$size] =
                ($laborVariations[$color]['sizes'][$size] ?? 0) + (int)$qty;

            $laborVariations[$color]['sole_color'] = $soleColor;
            $laborVariations[$color]['sole_name'] = $soleName;
        }
    }
}

  @endphp

  @if(!empty($laborVariations))
    <table class="w-full border border-gray-200 rounded text-xs text-center">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-1 text-left">Color</th>
          @foreach($allSizes as $size)
            <th class="p-1">{{ $size }}</th>
          @endforeach
          <th class="p-1">Sole Color</th>
          <th class="p-1">Sole Name</th>
        </tr>
      </thead>
      <tbody>
        @php $grandTotal = 0; @endphp
        @foreach($laborVariations as $color => $data)
          @php
            $sizes = $data['sizes'] ?? [];
            $soleColor = $data['sole_color'] ?? '-';
            $totalQty = array_sum($sizes);
            $grandTotal += $totalQty;
          @endphp
          <tr class="border-t">
            <td class="p-1 text-left font-semibold text-gray-700">{{ ucfirst($color) }}</td>
            @foreach($allSizes as $size)
              @php $val = $sizes[$size] ?? 0; @endphp
              <td class="p-1">
                @if($val > 0)
                  {{ $val }}
                @else
                  <span class="text-red-500 text-[10px] italic">0</span>
                @endif
              </td>
            @endforeach
            <td class="p-1">{{ ucfirst($soleColor) }}</td>
            <td class="p-1">{{ ucfirst($data['sole_name'] ?? '-') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <p class="mt-1 text-xs font-medium">
      Total Qty: <span class="text-indigo-700 font-bold">{{ $grandTotal }}</span>
    </p>
  @else
    <p class="text-gray-500 italic text-xs">No labor assignments found yet.</p>
  @endif
</div>

          </div>
        @endforeach
      @endif
    </div>
  </div>
</div>

{{-- DELIVERY NOTE MODAL – FINAL WORKING VERSION (Upper + Bottom Pairing) --}}
<div id="deliveryNoteModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 backdrop-blur-sm">
  <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-4xl relative overflow-y-auto max-h-[90vh] transition-all transform scale-95">

    <!-- Header -->
    <div class="flex items-center justify-between mb-5 border-b pb-3">
      <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
        Generate Delivery Note
      </h2>
      <button onclick="closeDeliveryModal()"
              class="text-gray-400 hover:text-gray-600 text-2xl font-semibold transition">&times;</button>
    </div>

    <form id="deliveryNoteForm" action="{{ route('delivery.note.storePartial') }}"
 method="POST" class="space-y-5">
      @csrf
      <input type="hidden" name="batch_id" id="batch_id">

      <!-- Client Selection -->
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Select Client</label>
        <select name="client_id" id="clientSelect"
                class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-500 outline-none transition" required>
          <option value="">-- Select Client --</option>
          @foreach($clients as $client)
            <option value="{{ $client->id }}">{{ $client->name }}</option>
          @endforeach
        </select>
      </div>

      <!-- Client Variations Container -->
      <div id="clientVariationsContainer">
        @foreach($clients as $client)
    @php
// --------------------------------------------------
// Decode batch variations
// --------------------------------------------------
$batchVar = is_string($batch->variations)
    ? json_decode($batch->variations, true)
    : ($batch->variations ?? []);

// --------------------------------------------------
// Load ordered qty from quotation
// --------------------------------------------------
$orderedMap = [];

$productQuotation = \App\Models\ProductQuotation::where('product_id', $batch->product_id)
    ->orderBy('id', 'desc')
    ->first();

if ($productQuotation) {
    $pqVars = json_decode($productQuotation->variations, true) ?? [];
    foreach ($pqVars as $pqVar) {
        $c = strtolower($pqVar['color'] ?? '');
        foreach ($pqVar['sizes'] as $s => $q) {
            if (!is_numeric($s)) continue;
            $orderedMap["{$c}|{$s}"] = (int)$q;
        }
    }
}

// --------------------------------------------------
// Map process → stage
// --------------------------------------------------
if (!function_exists('get_stage_from_process_id')) {
    function get_stage_from_process_id($pid) {
        return [
            1 => 'upper',
            2 => 'bottom',
            3 => 'finishing'
        ][$pid] ?? null;
    }
}

// --------------------------------------------------
// Load completed labor rows from employee_batch
// --------------------------------------------------
$laborRows = \App\Models\EmployeeBatch::where('batch_id', $batch->id)
    ->where('labor_status', 'completed')
    ->get();

// --------------------------------------------------
// Collect COMPLETED quantities
// --------------------------------------------------
$completed = [];

foreach ($laborRows as $row) {
    $stage = get_stage_from_process_id($row->process_id);
    if (!$stage) continue;

    $vars = json_decode($row->quantities, true) ?? [];

    foreach ($vars as $var) {
        foreach ($var as $size => $qty) {

            if (!is_numeric($size)) continue;

            // batch color/sole used for matching
            $color = strtolower($batchVar[0]['color']);
            $sole  = strtolower($batchVar[0]['sole_color']);

            $key = "{$color}|{$sole}|{$size}";

            $completed[$stage][$key] =
                ($completed[$stage][$key] ?? 0) + (int)$qty;
        }
    }
}

// --------------------------------------------------
// FINAL strict finished qty
// --------------------------------------------------
$totalBuilt = [];

foreach ($batchVar as $var) {
    $color = strtolower($var['color']);
    $sole  = strtolower($var['sole_color']);

    foreach ($var['sizes'] as $size => $info) {

        if (!is_numeric($size)) continue;

        $key = "{$color}|{$sole}|{$size}";

        $fin = (int)($completed['finishing'][$key] ?? 0);
        $upp = (int)($completed['upper'][$key] ?? 0);
        $bot = (int)($completed['bottom'][$key] ?? 0);

        // strict rule: finishing > 0 & upper & bottom must match finishing
        if ($fin > 0 && $upp >= $fin && $bot >= $fin) {
            $totalBuilt[$key] = $fin;
        } else {
            $totalBuilt[$key] = 0;
        }
    }
}

// --------------------------------------------------
// Build UI (Available = built - delivered)
// --------------------------------------------------
$clientVariations = [];

foreach ($batchVar as $var) {

    $color = strtolower($var['color']);
    $sole  = strtolower($var['sole_color']);
    $sizes = [];

    foreach ($var['sizes'] as $size => $info) {

        if (!is_numeric($size)) continue;

        $key = "{$color}|{$sole}|{$size}";

        $built     = $totalBuilt[$key] ?? 0;
        $delivered = is_array($info) ? (int)($info['delivered'] ?? 0) : 0;
        $avail     = max($built - $delivered, 0);

        if ($avail > 0) {
            $ordered = $orderedMap["{$color}|{$size}"] ?? 0;

            $sizes[$size] = [
                'ordered'   => $ordered,
                'delivered' => $delivered,
                'available' => $avail,
            ];
        }
    }

    if (!empty($sizes)) {
        $clientVariations[] = [
            'color'      => ucfirst($var['color']),
            'sole_color' => ucfirst($var['sole_color']),
            'sizes'      => $sizes
        ];
    }
}
@endphp



          <div class="client-section hidden border border-indigo-200 rounded-lg p-4 mb-4 bg-gray-50"
               data-client="{{ $client->id }}">
            <h3 class="font-semibold text-indigo-700 mb-3 text-lg">Client: {{ $client->name }}</h3>

            @if(!empty($clientVariations))
              @foreach($clientVariations as $vIndex => $variation)
                <div class="border border-gray-200 rounded-xl p-5 mb-5 bg-white shadow hover:shadow-md transition">
                  <div class="flex justify-between items-center mb-4">
                    <p class="text-sm font-bold text-gray-700">
                      Color: <span class="text-indigo-600">{{ ucfirst($variation['color']) }}</span> |
                      Sole: <span class="text-gray-800">{{ ucfirst($variation['sole_color']) }}</span>
                    </p>
                  </div>

                  <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                    @foreach($variation['sizes'] as $size => $info)
                      <div class="relative group">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                          Size {{ $size }}
                        </label>
                        <input type="number"
                               name="client_sizes[{{ $client->id }}][{{ $vIndex }}][{{ $size }}]"
                               min="0"
                               max="{{ $info['available'] }}"
                               value="0"
                               data-initial="{{ $info['available'] }}"
                               data-delivered="{{ $info['delivered'] }}"
                               class="w-full border border-gray-300 rounded-lg p-2 text-xs focus:ring-2 focus:ring-indigo-400 outline-none transition font-medium text-center"
                               oninput="updateRemaining(this); updateTotalQty();">

                       <small class="text-[10px] text-gray-500 block mt-1 text-center">
    Ordered: 
    <span class="ordered-count">{{ $info['ordered'] ?? 0 }}</span><br>

    Delivered: 
    <span class="delivered-count">{{ $info['delivered'] }}</span><br>

    Available: 
    <span class="avail-count font-bold text-green-600">{{ $info['available'] }}</span>
</small>

                      </div>
                    @endforeach
                  </div>
                </div>
              @endforeach
            @else
              <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                <p class="text-xl font-bold text-gray-600">No Deliverable Pairs Yet</p>
                <p class="text-sm text-gray-500 mt-2">Upper + Bottom labors must be completed first</p>
              </div>
            @endif
          </div>
        @endforeach
      </div>

      <!-- Total Qty Display -->
      <div class="mt-6 mb-4 flex items-center justify-between bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-5 shadow">
        <span class="text-lg font-bold text-gray-700">Total Delivery Quantity:</span>
        <span id="totalQtyDisplay" class="text-4xl font-bold text-indigo-700">0</span>
        <span class="text-lg text-gray-600">Pairs</span>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end gap-4 mt-8 border-t pt-6">
        <button type="button" onclick="closeDeliveryModal()"
                class="px-8 py-3 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition font-bold text-lg">
          Cancel
        </button>
      <button type="submit"
    class="delivery-btn">
    Generate Delivery Note
</button>

<style>
.delivery-btn {
    background: linear-gradient(to right, #4f46e5, #9333ea);
    color: white !important;
    padding: 12px 32px;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

.delivery-btn:hover {
    background: linear-gradient(to right, #4338ca, #7e22ce);
}
</style>

      </div>
    </form>
  </div>
</div>


<script>
// Unified update function (works for both quotation & manual)
function updateRemaining(input) {
  const max = parseInt(input.dataset.initial) || 0;
  const oldDelivered = parseInt(input.dataset.delivered) || 0;
  let val = parseInt(input.value) || 0;

  if (val > max) {
    val = max;
    input.value = max;
    input.classList.add('border-red-500');
  } else {
    input.classList.remove('border-red-500');
  }

  const container = input.closest('.relative') || input.parentElement;
  const avail = container.querySelector('.avail-count');
  const delivered = container.querySelector('.delivered-count');

  if (avail) avail.textContent = max - val;
  if (delivered) delivered.textContent = oldDelivered + val;
}
</script>


<script>
// 🔹 Recalculate remaining pairs live when user types
// 🔹 Live update: Remaining and Delivered values
// 🔹 For QUOTATION-based batches (original logic)
function updateRemainingQuotation(input) {
  const available = parseInt(input.dataset.initial || 0);  
  const deliveredOld = parseInt(input.dataset.delivered || 0);
  let entered = parseInt(input.value || 0);

  if (entered < 0) entered = 0;
  if (entered > available) {
    entered = available;
    input.value = available;
    input.classList.add("border-red-400");
  } else {
    input.classList.remove("border-red-400");
  }

  const newAvailable = Math.max(available - entered, 0);
  const newDelivered = deliveredOld + entered;

  const container = input.closest('.relative');
  const availSpan = container.querySelector('.avail-count');
  const deliveredSpan = container.querySelector('.delivered-count');

  if (availSpan) availSpan.textContent = newAvailable;
  if (deliveredSpan) deliveredSpan.textContent = newDelivered;
}

// 🔹 For MANUAL batches (fixed logic)
function updateRemainingManual(input) {
  const initialAvailable = parseInt(input.dataset.initial || 0);  
  const initialDelivered = parseInt(input.dataset.delivered || 0);
  let entered = parseInt(input.value || 0);

  if (entered < 0) entered = 0;
  if (entered > initialAvailable) {
    entered = initialAvailable;
    input.value = initialAvailable;
    input.classList.add("border-red-400");
  } else {
    input.classList.remove("border-red-400");
  }

  const newAvailable = Math.max(initialAvailable - entered, 0);
  const newDelivered = initialDelivered + entered;

  const container = input.closest('.relative');
  const availSpan = container.querySelector('.avail-count');
  const deliveredSpan = container.querySelector('.delivered-count');

  if (availSpan) availSpan.textContent = newAvailable;
  if (deliveredSpan) deliveredSpan.textContent = newDelivered;
}




// 🔹 Recalculate total quantity across all clients
function updateTotalQty() {
  let total = 0;
  document.querySelectorAll('#deliveryNoteForm input[type="number"]').forEach(i => {
    total += parseInt(i.value || 0);
  });
  document.getElementById('totalQtyDisplay').innerText = total;
}

</script>


{{-- ✅ Scripts --}}
<script>
// 🔹 Modal Controls
function openDeliveryModal(id) {
  document.getElementById('batch_id').value = id;
  document.getElementById('deliveryNoteModal').classList.remove('hidden');
  document.getElementById('deliveryNoteModal').classList.add('flex');
}

function closeDeliveryModal() {
  document.getElementById('deliveryNoteModal').classList.add('hidden');
}

function updateTotalQty() {
  let total = 0;
  document.querySelectorAll('#deliveryNoteForm input[type="number"]').forEach(i => total += parseInt(i.value || 0));
  document.getElementById('totalQtyDisplay').innerText = total;
}

function printBatchDetails() {
  const content = document.getElementById('batch-details-to-print').innerHTML;
  const printWindow = window.open('', '', 'height=900,width=800');

  printWindow.document.write(`
    <html>
    <head>
      <title>Batch Details</title>
      <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
      <style>
        @page {
          size: A4 portrait;
          margin: 10mm;
        }

        body {
          font-family: Arial, Helvetica, sans-serif;
          font-size: 10px;
          color: #111;
          padding: 10px;
        }

        h2 {
          text-align: center;
          font-size: 14px;
          margin-bottom: 10px;
          font-weight: bold;
        }

        th, td {
          border: 1px solid #999;
          padding: 3px;
          font-size: 9px;
          text-align: left;
        }

        th {
          background: #f3f4f6;
          font-weight: 600;
        }

        .worker-section {
          page-break-inside: avoid;
          margin-bottom: 20px;
          border: 1px solid #ddd;
          padding: 8px;
          border-radius: 6px;
          background: #fff;
        }

        /* ✅ Ensures only 2 worker sections per printed page */
        .worker-section:nth-of-type(2n) {
          page-break-after: always;
        }

        /* Remove unwanted elements during print */
        @media print {
          button, .no-print {
            display: none !important;
          }
        }
      </style>
    </head>
    <body>
      <h2>Batch Details</h2>
      ${content}
    </body>
    </html>
  `);

  printWindow.document.close();
  printWindow.onload = function() {
    printWindow.focus();
    setTimeout(() => {
      printWindow.print();
      printWindow.close();
    }, 500);
  };
}
</script>

<script>
// 🔹 Show correct client section
document.addEventListener('DOMContentLoaded', () => {
  const select = document.getElementById('clientSelect');
  const sections = document.querySelectorAll('.client-section');

  if (!select) return;

  sections.forEach(section => section.classList.add('hidden'));

  select.addEventListener('change', function () {
    const selectedId = String(this.value);

    sections.forEach(section => {
      const sectionId = String(section.dataset.client);
      if (sectionId === selectedId) {
        section.classList.remove('hidden');
        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
      } else {
        section.classList.add('hidden');
      }
    });

    updateTotalQty();
  });
});

</script>



@endsection
