@extends('layouts.app')

@section('content')
<div class="delivery-note-container">

  <!-- 🔹 Top Action Buttons -->
  <div class="no-print action-buttons">
    <a href="{{ url()->previous() }}" class="btn back">← Back</a>
    <button onclick="printDeliveryNote()" class="btn print">🖨️ Print</button>
    <button onclick="downloadPDF()" class="btn download">⬇️ Download PDF</button>
    <button onclick="sendMail()" class="btn mail">✉️ Mail</button>
    <button onclick="shareWhatsApp()" class="btn whatsapp">📱 WhatsApp</button>

    <button onclick="openEditModal()" class="btn edit">✏️ Edit</button>

  </div>

  <!-- 🔹 Printable Content -->
  <div class="printable-area" id="delivery-note">

    <!-- Company + Delivery Note Header -->
    <div class="company-header">

    {{-- Left Side: Dynamic Company Info --}}
    <div class="company-info">

        {{-- Company Logo --}}
        @php
            $logo = \App\Models\Settings::get('company_logo');
        @endphp

        <img src="{{ $logo ? asset($logo) : 'https://placehold.co/120x60?text=Logo' }}"
             onerror="this.src='https://placehold.co/120x60?text=Logo'"
             alt="Company Logo"
             style="height:60px; width:auto; margin-bottom:8px;">

       
    </div>

    {{-- Right Side: Delivery Note Info --}}
    <div class="note-details">
        <h1>DELIVERY NOTE</h1>
        <p><strong>DN No:</strong> {{ $deliveryNote->delivery_note_no }}</p>
        <p><strong>Date:</strong> {{ $deliveryNote->delivery_date->format('d/m/Y') }}</p>

        @if($deliveryNote->assigned_qty)
            <p><strong>Assigned Qty:</strong> {{ $deliveryNote->assigned_qty }} Pairs</p>
        @endif
    </div>

</div>


    <!-- Client & Company Info -->
    <div class="party-info">
      <div class="to-party">
        <h3>Delivered To:</h3>
       @if(!empty($deliveryNote->client_id) && $deliveryNote->client)
  <p><strong>{{ $deliveryNote->client->name }}</strong></p>
  <p>{{ ucfirst($deliveryNote->client->category ?? '-') }}</p>
  <p>Phone: {{ $deliveryNote->client->phone ?? 'N/A' }}</p>
  <p>Email: {{ $deliveryNote->client->email ?? 'N/A' }}</p>
@elseif($deliveryNote->batch->clients->count() > 1)
  <p><strong>Multiple Clients (Batch Delivery)</strong></p>
@elseif($deliveryNote->batch->clients->count() === 1)
  @php $client = $deliveryNote->batch->clients->first(); @endphp
  <p><strong>{{ $client->name }}</strong></p>
  <p>{{ ucfirst($client->category ?? '-') }}</p>
  <p>Phone: {{ $client->phone ?? 'N/A' }}</p>
  <p>Email: {{ $client->email ?? 'N/A' }}</p>
@endif

      </div>

     <div class="from-party">
    <h3>From:</h3>

    {{-- Company Name --}}
        <h2>{{ \App\Models\Settings::get('company_name', 'Company Name') }}</h2>

        {{-- Company Address --}}
        <p>{{ \App\Models\Settings::get('company_address') }}</p>

        {{-- GST --}}
        <p>GST: {{ \App\Models\Settings::get('company_gst') }}</p>

        {{-- Phone and Email --}}
        <p>
            Phone: {{ \App\Models\Settings::get('company_phone') }} |
            Email: {{ \App\Models\Settings::get('company_email') }}
        </p>

</div>

    </div>

    <!-- 🔹 Article & Product Details -->
    <table class="details-table">
      <tr>
        <th>Article No</th>
        <td>{{ $deliveryNote->batch->product->sku ?? 'N/A' }}</td>
        <th>Article Name</th>
        <td>{{ $deliveryNote->batch->product->name ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th>Batch No</th>
        <td>{{ $deliveryNote->batch->batch_no }}</td>
        <th>PO No</th>
        <td>{{ $deliveryNote->batch->po_no ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th>Delivery Quantity</th>
        <td colspan="3">{{ $deliveryNote->assigned_qty ?? $deliveryNote->batch->quantity ?? 0 }} Pairs</td>
      </tr>
    </table>


    @php
// -----------------------------
// Decode variations
// -----------------------------
$batchVar = is_string($deliveryNote->batch->variations)
    ? json_decode($deliveryNote->batch->variations, true)
    : $deliveryNote->batch->variations;

$labor = is_string($deliveryNote->batch->labor_assignments)
    ? json_decode($deliveryNote->batch->labor_assignments, true)
    : $deliveryNote->batch->labor_assignments;


// -----------------------------
// Detect if QUOTATION batch
// -----------------------------
$isQuotationBatch = !empty($deliveryNote->batch->quotation_id);



// -----------------------------
// Map process_id -> stage
// -----------------------------
function stage_from_pid($assign) {
    $map = [
        1 => 'upper',
        2 => 'bottom',
        3 => 'finishing',
    ];
    return $map[$assign['process_id']] ?? null;
}


// -----------------------------
// Collect quantities per stage
// -----------------------------
$completed = [];

foreach ($labor as $assign) {
    $stage = stage_from_pid($assign);
    if (!$stage) continue;

    foreach (($assign['variations'] ?? []) as $var) {

        if (!isset($var['sizes'])) {
            $color = strtolower($batchVar[0]['color']);
            $sole  = strtolower($batchVar[0]['sole_color']);
            $sizes = $var;
        } else {
            $color = strtolower($var['color']);
            $sole  = strtolower($var['sole_color']);
            $sizes = $var['sizes'];
        }

        foreach ($sizes as $size => $qty) {
            if (!is_numeric($size)) continue;

            $key = "{$color}|{$sole}|{$size}";
            $completed[$stage][$key] = ($completed[$stage][$key] ?? 0) + (int)$qty;
        }
    }
}


// -----------------------------
// FINAL BUILT (finish → output)
// -----------------------------
$finalBuilt = [];

foreach ($batchVar as $var) {
    $color = strtolower($var['color']);
    $sole  = strtolower($var['sole_color']);

    foreach ($var['sizes'] as $size => $info) {
        if (!is_numeric($size)) continue;

        $key = "{$color}|{$sole}|{$size}";

        $finishing = (int)($completed['finishing'][$key] ?? 0);
        $upper     = (int)($completed['upper'][$key] ?? 0);
        $bottom    = (int)($completed['bottom'][$key] ?? 0);

        if ($finishing > 0 && $upper >= $finishing && $bottom >= $finishing) {
            $finalBuilt[$key] = $finishing;
        } else {
            $finalBuilt[$key] = 0;
        }
    }
}


// -----------------------------
// FINAL CALC: available = finalBuilt - delivered
// -----------------------------
$finalCalculated = [];

foreach ($batchVar as $var) {
    $color = strtolower($var['color']);
    $sole  = strtolower($var['sole_color']);

    foreach ($var['sizes'] as $size => $info) {

        $key = "{$color}|{$sole}|{$size}";

        $built     = $finalBuilt[$key] ?? 0;
        $delivered = (int)($info['delivered'] ?? 0);
        $available = max($built - $delivered, 0);

        $finalCalculated[$color][$sole][$size] = [
            'ordered'   => $isQuotationBatch 
                            ? ($info['ordered'] ?? 0)
                            : 0,

            'delivered' => $delivered,
            'available' => $available,
        ];
    }
}
@endphp


    <!-- 🔹 Item Breakdown -->
    <table class="items-table">
      <thead>
        <tr>
          <th>S.No</th>
          <th>Color</th>
          <th>Sole Color</th>
          <th>Sizes & Quantity</th>
        </tr>
      </thead>
      <tbody>
        @foreach($deliveryNote->items as $index => $item)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item['color'] ?? '-' }}</td>
            <td>{{ $item['sole_color'] ?? '-' }}</td>
            <td>
    @php
        $color = $item['color'];
        $sole  = $item['sole_color'];
        $sizes = $finalCalculated[$color][$sole] ?? [];

        // check if item came from quotation
        $isQuotation = ($item['source'] ?? null) === 'quotation';
    @endphp

    @foreach($sizes as $size => $info)
        @php
            $delivered = $info['delivered'] ?? 0;
        @endphp

        {{-- show ONLY delivered sizes (hide sizes with delivered = 0) --}}
        @if($delivered > 0)
            <span class="size-box">
                Size {{ $size }} |

                {{-- show ordered only for quotation --}}
                {{-- show ordered only for QUOTATION batches --}}
@if($isQuotationBatch && !empty($info['ordered']) && $info['ordered'] > 0)
    Ordered: {{ $info['ordered'] }},
@endif


                Delivered: {{ $delivered }},
                <!-- Avail: {{ $info['available'] ?? 0 }} -->
            </span>
        @endif
    @endforeach
</td>


          </tr>
        @endforeach
      </tbody>
    </table>

    <!-- 🔹 Signatures -->
    <div class="signatures">
      <div class="signature-block">
        <p>Receiver’s Signature</p>
        <div class="signature-line"></div>
      </div>
      <div class="signature-block">
        <p>Authorized Signature</p>
        <div class="signature-line"></div>
      </div>
    </div>
  </div>
</div>



<!-- 🔹 Edit Delivery Note Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 backdrop-blur-sm">
  <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-3xl relative overflow-y-auto max-h-[90vh] transition-all transform scale-95">
    
    <!-- Header -->
    <div class="flex items-center justify-between mb-5 border-b pb-3">
      <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
        ✏️ Edit Delivery Note Quantities
      </h2>
      <button onclick="closeEditModal()" 
              class="text-gray-400 hover:text-gray-600 text-2xl transition">
        &times;
      </button>
    </div>

    <form id="editForm" action="{{ route('delivery.note.updatePartial', $deliveryNote->id) }}" method="POST">
      @csrf
      @method('PUT')

      <!-- Loop through items -->
      @foreach($deliveryNote->items as $vIndex => $variation)
        <div class="border border-gray-200 rounded-xl p-4 mb-4 bg-gray-50 hover:shadow-md transition-all duration-200">
          <div class="flex justify-between items-center mb-3">
            <p class="text-sm font-semibold text-gray-700">
              Color: <span class="text-indigo-600">{{ $variation['color'] ?? '-' }}</span> |
              Sole: <span class="text-gray-800">{{ $variation['sole_color'] ?? '-' }}</span>
            </p>
            <span class="text-xs text-gray-400 font-medium">#{{ $loop->iteration }}</span>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
           @foreach(($variation['sizes'] ?? []) as $size => $info)
  @php
      // Safely handle both old (number) and new (array) formats
      $ordered = is_array($info) ? ($info['ordered'] ?? 0) : (int)$info;
      $available = is_array($info) ? ($info['available'] ?? 0) : 0;
      $delivered = is_array($info) ? ($info['delivered'] ?? 0) : (int)$info;
  @endphp

  <div class="group">
    <label class="block text-xs font-semibold text-gray-600 mb-1 group-hover:text-indigo-600 transition">
      Size {{ $size }}
      <small class="text-gray-500">(Avail: {{ $available }}, Ordered: {{ $ordered }})</small>
    </label>

    <input 
      type="number" 
      name="sizes[{{ $vIndex }}][{{ $size }}]" 
      class="w-full border border-gray-300 rounded-lg p-2 text-xs focus:ring-2 focus:ring-indigo-400 focus:border-indigo-500 outline-none transition" 
      min="0" 
      value="{{ $delivered }}" {{-- ✅ FIXED: now only prints integer --}}
      oninput="updateEditTotalQty()">
  </div>
@endforeach

          </div>
        </div>
      @endforeach

      <!-- Total Display -->
      <div class="mt-4 mb-5 flex items-center justify-between bg-gray-100 rounded-lg p-3 text-sm font-medium text-gray-700">
        <span>Total Assigned Quantity:</span>
        <span id="editTotalQtyDisplay" class="text-indigo-700 text-lg font-bold tracking-wide">
          {{ $deliveryNote->assigned_qty ?? 0 }}
        </span>
        <span class="text-gray-500 text-sm ml-1">Pairs</span>
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-end gap-3 mt-6">
        <button type="button" 
                onclick="closeEditModal()" 
                class="px-5 py-2.5 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition font-medium">
          Cancel
        </button>
        <button type="submit" 
  class="px-5 py-2.5 rounded-lg !bg-amber-500 !text-white font-medium hover:!bg-amber-600 shadow-md transition-all duration-200">
  💾 Save Changes
</button>

      </div>
    </form>
  </div>
</div>


<!-- 🔹 JS for Actions -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
  function printDeliveryNote() {
    window.print();
  }

  async function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const el = document.querySelector("#delivery-note");
    const canvas = await html2canvas(el, { scale: 2 });
    const imgData = canvas.toDataURL("image/png");

    const pdf = new jsPDF('p', 'mm', 'a4');
    const width = pdf.internal.pageSize.getWidth();
    const height = (canvas.height * width) / canvas.width;

    pdf.addImage(imgData, 'PNG', 0, 0, width, height);
    pdf.save("DeliveryNote-{{ $deliveryNote->delivery_note_no }}.pdf");
  }

  function shareWhatsApp() {
    const text = encodeURIComponent(
      "Delivery Note {{ $deliveryNote->delivery_note_no }} for {{ $deliveryNote->batch->product->name }} (Batch {{ $deliveryNote->batch->batch_no }}) - Creative Shoes"
    );
    window.open(`https://wa.me/?text=${text}`, "_blank");
  }

  function sendMail() {
    const subject = encodeURIComponent("Delivery Note {{ $deliveryNote->delivery_note_no }} - Creative Shoes");
    const body = encodeURIComponent(
      `Dear Customer,\n\nPlease find the delivery note details below:\n\n` +
      `Delivery Note No: {{ $deliveryNote->delivery_note_no }}\n` +
      `Date: {{ $deliveryNote->delivery_date->format('d/m/Y') }}\n` +
      `Product: {{ $deliveryNote->batch->product->name ?? 'N/A' }}\n` +
      `Batch No: {{ $deliveryNote->batch->batch_no }}\n\n` +
      `Thank you,\nCreative Shoes`
    );

    const email = "{{ $deliveryNote->batch->clients->first()->email ?? '' }}";
    window.location.href = `mailto:${email}?subject=${subject}&body=${body}`;
  }
</script>


<script>
function openEditModal() {
  document.getElementById('editModal').classList.remove('hidden');
  document.getElementById('editModal').classList.add('flex');
}

function closeEditModal() {
  document.getElementById('editModal').classList.add('hidden');
}

function updateEditTotalQty() {
  let total = 0;
  document.querySelectorAll('#editForm input[type="number"]').forEach(input => {
    total += parseInt(input.value || 0);
  });
  document.getElementById('editTotalQtyDisplay').innerText = total;
}

</script>

<!-- 🔹 Styles -->
<style>
  body {
    background: #f3f4f6;
    font-family: 'Segoe UI', sans-serif;
  }

  .delivery-note-container {
    padding: 20px;
  }

  .printable-area {
    background: #fff;
    max-width: 900px;
    margin: 0 auto;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  }

  .btn.edit { background: #f59e0b; } /* Amber color */

  .btn.edit:hover { background: #d97706; }


  /* Header */
  .company-header {
    display: flex;
    justify-content: space-between;
    border-bottom: 2px solid #ddd;
    padding-bottom: 15px;
    margin-bottom: 25px;
  }

  .company-info img {
    width: 100px;
    height: auto;
    margin-bottom: 5px;
  }

  .company-info h2 {
    margin: 0;
    color: #333;
  }

  .company-info p {
    margin: 2px 0;
    font-size: 13px;
    color: #555;
  }

  .note-details {
    text-align: right;
  }

  .note-details h1 {
    margin: 0;
    color: #1e3a8a;
    font-size: 22px;
    font-weight: 700;
  }

  .note-details p {
    font-size: 13px;
    margin: 2px 0;
    color: #444;
  }

  /* Party Info */
  .party-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 25px;
  }

  .to-party,
  .from-party {
    width: 48%;
    background: #f9fafb;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
  }

  .party-info h3 {
    margin-bottom: 8px;
    color: #333;
  }

  .party-info p {
    margin: 2px 0;
    font-size: 13px;
    color: #555;
  }

  /* Details Table */
  .details-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }

  .details-table th,
  .details-table td {
    border: 1px solid #ddd;
    padding: 6px 10px;
    font-size: 13px;
  }

  .details-table th {
    background: #f3f4f6;
    text-align: left;
    color: #111827;
    width: 25%;
  }

  /* Items Table */
  .items-table {
    width: 100%;
    border-collapse: collapse;
  }

  .items-table th,
  .items-table td {
    border: 1px solid #ddd;
    padding: 8px;
    font-size: 13px;
  }

  .items-table th {
    background: #e5e7eb;
    color: #111827;
    font-weight: 600;
  }

  .size-box {
    display: inline-block;
    background: #e0f2fe;
    color: #0369a1;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 12px;
    margin: 2px;
  }

  /* Signatures */
  .signatures {
    display: flex;
    justify-content: space-between;
    margin-top: 50px;
  }

  .signature-block p {
    font-weight: 500;
    color: #444;
    margin-bottom: 10px;
  }

  .signature-line {
    width: 200px;
    height: 1px;
    background: #333;
  }

  /* Buttons */
  .no-print {
    margin-bottom: 15px;
    text-align: center;
  }

  .btn {
    background: #4f46e5;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 8px 14px;
    font-size: 13px;
    font-weight: 600;
    margin: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: 0.25s;
  }

  .btn:hover {
    transform: translateY(-1px);
    opacity: 0.9;
  }

  .btn.back { background: #6b7280; }
  .btn.download { background: #059669; }
  .btn.mail { background: #2563eb; }
  .btn.whatsapp { background: #22c55e; }

  /* Print Styles */
  @media print {
    body * { visibility: hidden; }
    .printable-area, .printable-area * { visibility: visible; }
    .printable-area {
      margin: 0;
      padding: 20px;
      box-shadow: none;
      width: 100%;
    }
    .no-print { display: none !important; }
    @page { size: A4; margin: 1cm; }
  }
</style>
@endsection
