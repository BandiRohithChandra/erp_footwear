@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 flex flex-col items-center py-10">
    <div class="w-full max-w-6xl">
        <!-- Header -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
            <h2 class="text-2xl font-bold text-gray-800">Create New Batch Flow</h2>
            <div class="flex gap-4">
                <!-- <a href="{{ route('batch.flow.card') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                    + Add Batch Card
                </a> -->
                <a href="{{ route('batch.flow.index') }}" class="text-indigo-600 hover:underline">← Back to Batches</a>
            </div>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any() || session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @if (session('error'))
                        <li>{{ session('error') }}</li>
                    @endif
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white shadow-lg rounded-xl p-8">
            <form method="POST" action="{{ route('batch.flow.store') }}" class="space-y-6" id="batch-form">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column: Batch Details -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold mb-2 text-gray-700">Batch Details</h3>
                        <!-- Batch No + Order No -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="batch_no" class="block text-sm font-medium text-gray-700">Batch No</label>
                                <input type="text" name="batch_no" id="batch_no" 
                                       value="{{ $autoBatchNo }}" readonly
                                       class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 cursor-not-allowed" />
                            </div>
                            <div>
                                <label for="order_no" class="block text-sm font-medium text-gray-700">Order No</label>
                                @if($orders && count($orders) > 0)
                                    <select name="order_no" id="order_no" required
                                            class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Select Order --</option>
                                        @foreach($orders as $order)
                                            <option value="{{ $order['order_no'] }}" {{ old('order_no', $selectedOrderNo) == $order['order_no'] ? 'selected' : '' }}>
                                                {{ $order['order_no'] }} - {{ ucfirst($order['status']) }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <p class="text-gray-500 italic mt-1">No orders available. Please create an order first.</p>
                                @endif
                            </div>
                        </div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Party Selection -->
    <div>
        <label class="block text-sm font-medium text-gray-700">
            Party <span class="text-red-500">*</span>
        </label>

        <div class="mt-2 max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3 space-y-4">

            @foreach($clients as $c)

                <!-- PARTY -->
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input 
                        type="checkbox"
                        name="client_id[]"
                        value="{{ $c->id }}"
                        class="rounded text-indigo-600 focus:ring-indigo-500"
                        {{ in_array($c->id, old('client_id', [])) ? 'checked' : '' }}

                    >
                   <span class="text-sm font-medium">
    {{ $c->business_name }} ({{ ucfirst($c->category) }})
</span>

                </label>

                <hr class="border-gray-300">

            @endforeach

        </div>

        <!-- Add New Party Button -->
        <div class="mt-3 flex items-center justify-between">
            <button 
                type="button"
                id="open-add-client"
                class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                + Add New Party
            </button>

            <p class="text-xs text-gray-500">
                <span class="text-red-500">*</span> At least one party required.
            </p>
        </div>
    </div>

    <!-- Brand (separate field) -->
    <div>
        <label class="block text-sm font-medium text-gray-700">
            Brand(Optional)
        </label>
        <input 
            type="text" 
            name="brand"
            value="{{ old('brand', $quotation->brand_name ?? '') }}"
            placeholder="Brand Name"
            class="mt-1 block w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
        />
    </div>

    <!-- PO Number -->
    <div>
        <label for="po_no" class="block text-sm font-medium text-gray-700">
            PO No <span class="text-red-500">*</span>
        </label>
        <input 
            type="text" 
            name="po_no" 
            id="po_no" 
            required
            value="{{ old('po_no') }}"
            class="mt-1 block w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
        />
    </div>

</div>



                        <!-- Batch Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="batch_start_date" class="block text-sm font-medium text-gray-700">Batch Start Date <span class="text-red-500">*</span></label>
                                <input type="date" name="batch_start_date" id="batch_start_date" required
                                       class="mt-1 block w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                            <div>
                                <label for="batch_end_date" class="block text-sm font-medium text-gray-700">Batch End Date</label>
                                <input type="date" name="batch_end_date" id="batch_end_date"
                                       class="mt-1 block w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                        </div>
                        <!-- Article Selection -->
                        <div>
                            <label for="article_no" class="block text-sm font-medium text-gray-700">Article No <span class="text-red-500">*</span></label>
                            <select name="article_no" id="article_no" required
                                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">-- Select Article --</option>
                                @foreach($articles as $article)
                                    <option value="{{ $article->id }}"
                                            {{ $quotationProducts && $quotationProducts[0]['product_id'] == $article->id ? 'selected' : '' }}>
                                        {{ $article->sku }} - {{ $article->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Right Column: Product Details -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold mb-2 text-gray-700">Product Details</h3>
                        <div class="flex flex-col items-center p-4 bg-gray-50 border rounded-lg shadow-sm" id="product-image-card"
                             style="display: {{ $quotationProducts && count($quotationProducts) > 0 ? 'flex' : 'none' }};">

                            

                          <img 
    id="product-image" 
    src="{{ $quotationProducts[0]['image'] ?? 'https://via.placeholder.com/150?text=No+Image' }}" 
    alt="{{ $quotationProducts[0]['name'] ?? 'Product Image' }}" 
    class="w-48 h-48 object-cover rounded-lg border mb-4"
/>


                            <h3 id="product-name" class="text-lg font-semibold">
                                {{ $quotationProducts && count($quotationProducts) > 0 ? $quotationProducts[0]['name'] : '' }}
                            </h3>
                            <p id="product-desc" class="text-sm text-gray-600 text-center">
                                {{ $quotationProducts && count($quotationProducts) > 0 ? ($quotationProducts[0]['description'] ?? 'No description available') : '' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Full-width Tables Section -->
                <div class="space-y-6 mt-6">
                    @foreach (['variations', 'sole', 'material', 'process', 'liquid_material'] as $section)
                        <div class="overflow-x-auto border rounded-lg shadow-sm bg-white w-full">
                            <h4 class="text-md font-semibold mb-2 p-3 bg-gray-50 border-b">
                                {{ ucwords(str_replace('_', ' ', $section)) }} Details
                            </h4>
                            <table class="min-w-full table-fixed border-collapse text-sm">
                                <thead class="bg-gray-100 sticky top-0">
                                    @if($section == 'variations')
                                        <tr>
                                            <th class="border px-3 py-2 text-left">Color</th>
                                            @foreach(range(35, 44) as $size)
                                                <th class="border px-3 py-2 text-center">{{ $size }}</th>
                                            @endforeach
                                            <th class="border px-3 py-2 text-left">Sole Name</th>
                                            <th class="border px-3 py-2 text-center">Sole Color</th>
                                        </tr>
                                    @elseif($section == 'sole')
                                        <tr>
                                            <th class="border px-3 py-2 text-left">Name</th>
                                            <th class="border px-3 py-2 text-left">Color</th>
                                            <th class="border px-3 py-2 text-left">Sub Type</th>
                                        </tr>
                                    @elseif($section == 'material')
                                        <tr>
                                            <th class="border px-3 py-2 text-left">Name</th>
                                            <th class="border px-3 py-2 text-left">Color</th>
                                            <th class="border px-3 py-2 text-left">Unit</th>
                                        </tr>
                                    @elseif($section == 'process')
                                        <tr>
                                            <th class="border px-3 py-2 text-left">Name</th>
                                            <th class="border px-3 py-2 text-left">Stage</th>
                                            <th class="border px-3 py-2 text-left">Status</th>
                                            <th class="border px-3 py-2 text-left">Assigned Qty</th>
                                            <th class="border px-3 py-2 text-left">Completed Qty</th>
                                            <th class="border px-3 py-2 text-left">Labor Rate</th>
                                        </tr>
                                     @elseif($section == 'liquid_material')
                                        <tr>
                                            <th class="border px-3 py-2 text-left">Name</th>
                                            <th class="border px-3 py-2 text-left">Unit</th>
                                        </tr>
                                    @endif 
                                </thead>
                               <tbody id="{{ $section }}-details">
@if($section == 'variations' && $quotationProducts && count($quotationProducts) > 0 && count($quotationProducts[0]['variations']) > 0)
    @foreach($quotationProducts[0]['variations'] as $index => $variation)
        <tr>
            <!-- Color -->
            <td class="border px-3 py-2">
                <input type="text" class="variations-input"
                       value="{{ $variation['color'] ?? '' }}" placeholder="Color" data-color="color">
            </td>

            <!-- Sizes 35-44 -->
            @foreach(range(35, 44) as $size)
                <td class="border px-3 py-2 text-center">
                    <input type="text" class="variations-input text-center"
                           value="{{ $variation['sizes'][$size] ?? 0 }}" placeholder="0" data-size="{{ $size }}"
                           style="width: 40px;">
                </td>
            @endforeach

            <!-- Sole Name -->
           <td class="border px-3 py-2">
    <!-- Dummy field to stop Chrome autofill -->
    <input type="text" style="display:none" autocomplete="username">

    <!-- Real field -->
    <input type="text"
        class="variations-input sole-name-input"
        value="{{ $variation['sole_name'] ?? '' }}"
        placeholder="Sole Name"
        data-sole="sole_name"
        name="sole_name_{{ uniqid() }}"
        autocomplete="off"
        data-random="{{ uniqid() }}">
</td>


         <td class="border px-3 py-2">
    <input type="text" 
           class="variations-input sole-color-input"
           list="colorSuggestions-{{ $loop->index }}" 
           value="{{ $variation['sole_color'] ?? '' }}" 
           placeholder="Sole Color" 
           data-sole="sole_color" 
-          data-product-id="{{ $variation['product_id'] ?? '' }}">
+          data-product-id="{{ $quotationProducts[0]['product_id'] ?? '' }}">
    <datalist id="colorSuggestions-{{ $loop->index }}"></datalist>
</td>

            <!-- Action -->
            <td class="border px-3 py-2 text-center">
                <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded transition"
                        onclick="this.parentElement.parentElement.remove()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6.293 6.293a1 1 0 011.414 0L10 8.586l2.293-2.293a1 1 0 111.414 1.414L11.414 10l2.293 2.293a1 1 0 01-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 01-1.414-1.414L8.586 10 6.293 7.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </td>
        </tr>
    @endforeach
@else

        <tr>
            <td class="border px-3 py-2 text-center" colspan="12">
                No {{ str_replace('_', ' ', $section) }} available for this product.
            </td>
        </tr>
    @endif
</tbody>

                            </table>
                            @if($section == 'variations')
                                <div class="mt-2">
                                    <button type="button" id="add-variation" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">
                                        + Add More Variation
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

               

                 <!-- Hidden input for JSON variations -->
                <input type="hidden" name="variations" id="variations-hidden">

                <!-- Next Button -->
                <div class="mt-4">
                    <button type="submit" id="next-btn" class="submit-btn">Next</button>


                </div>
            </form>
            <!-- Add New Client Modal -->
<div id="add-client-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden" style="overflow: scroll;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] flex flex-col">
        <!-- Header -->
        <div class="flex justify-between items-center sticky top-0 bg-white z-10 border-b border-gray-200 py-4 px-6">
            <h2 class="text-2xl font-bold text-blue-600">Add New Party</h2>
            <button type="button" id="close-client-modal" class="text-gray-400 hover:text-gray-600 text-3xl font-bold transition-all">&times;</button>
        </div>

        <!-- Form -->
        <div class="flex-1 overflow-y-auto p-6">
            <form id="add-client-form" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" style="padding-top: 20px;">
                @csrf

                <!-- Required Fields -->
                <div class="col-span-1">
                    <label for="business_name" class="block text-sm font-medium text-gray-700">
                        Business/Company Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="business_name" name="business_name" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>

                <div class="col-span-1">
                    <label for="gst_no" class="block text-sm font-medium text-gray-700">
                        GST No <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="gst_no" name="gst_no" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select id="category" name="category" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        <option value="">-- Select Category --</option>
                        <option value="wholesale">Wholesale</option>
                        <option value="retail">Retail</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        GST Certificate <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="gst_certificate" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" accept="image/*,application/pdf" required>
                </div>

                <!-- Optional Fields -->
                <div class="col-span-1">
                    <label for="name" class="block text-sm font-medium text-gray-700">Contact Person Name</label>
                    <input type="text" id="name" name="name" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div class="col-span-1 md:col-span-2 lg:col-span-3">
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea id="address" name="address" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label for="sales_rep_id" class="block text-sm font-medium text-gray-700">Assign Sales Rep</label>
                    <select id="sales_rep_id" name="sales_rep_id" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">-- None --</option>
                        @foreach($salesReps as $rep)
                            <option value="{{ $rep->id }}">{{ $rep->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" id="phone" name="phone" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <!-- Optional Documents -->
                <div class="col-span-1 md:col-span-2 lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Company Document</label>
                        <input type="file" name="company_document" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" accept="image/*,application/pdf">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Aadhar Certificate</label>
                        <input type="file" name="aadhar_certificate" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" accept="image/*,application/pdf">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Electricity Bill</label>
                        <input type="file" name="electricity_certificate" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" accept="image/*,application/pdf">
                    </div>
                </div>

                <!-- Optional Password Fields -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <!-- Buttons -->
                <div class="col-span-1 md:col-span-2 lg:col-span-3 flex justify-end gap-3 mt-6">
                    <button type="button" id="cancel-client-btn" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Cancel</button>
                    <button type="submit" id="save-client-btn" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Save Client</button>
                </div>
            </form>
        </div>
    </div>
</div>


        </div>
    </div>
</div>

<style>
.submit-btn {
    width: 100%;
    background-color: #4f46e5;
    color: white;
    font-weight: 600;
    padding: 12px;
    border: none;
    border-radius: 8px;
    box-shadow: 0px 2px 6px rgba(0,0,0,0.2);
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}
.submit-btn:hover { background-color: #4338ca; transform: scale(1.02); }
.submit-btn:active { background-color: #3730a3; transform: scale(0.98); }
table th, table td { text-align: left; }
table tbody tr:hover { background-color: #f3f4f6; }
input.variations-input { 
    width: 100%; 
    text-align: center; 
    border: 1px solid #d1d5db; 
    border-radius: 4px; 
    padding: 2px 4px;
    font-size: 12px;
    height: 28px;
}
input.variations-input[data-size] { 
    width: 40px !important; 
    padding: 4px 2px;
}
.variations-input:focus {
    outline: none;
    border-color: #4f46e5;
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
}
</style>


<script>

document.querySelectorAll('input[data-sole="sole_name"]').forEach(input => {
    input.setAttribute('autocomplete', 'off');
    input.setAttribute('name', 'sole_name_' + Math.random().toString(36).substring(2,8));
});

</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Event delegation for all sole name inputs (now and future)
    document.addEventListener('input', handleSoleNameInput);
    document.addEventListener('change', handleSoleNameChange);

    function handleSoleNameInput(e) {
        if (!e.target.matches('input[data-sole="sole_name"]')) return;
        const input = e.target;
        const query = input.value.trim();
        if (query.length < 2) return;

        fetchAndAttachDatalist(input, query);
    }

    function handleSoleNameChange(e) {
        if (!e.target.matches('input[data-sole="sole_name"]')) return;
        autoFillSoleColor(e.target);
    }

    // Fetch suggestions + create datalist
    async function fetchAndAttachDatalist(input, query) {
        let datalist = input.list;
        if (!datalist) {
            const randomId = 'sole-datalist-' + Math.random().toString(36).substr(2, 9);
            datalist = document.createElement('datalist');
            datalist.id = randomId;
            input.setAttribute('list', randomId);
            input.parentNode.appendChild(datalist);
        }

        try {
            const res = await fetch(`/soles/suggestions?q=${encodeURIComponent(query)}`);
            const soles = await res.json();

            datalist.innerHTML = '';
            soles.forEach(sole => {
                const option = document.createElement('option');
                option.value = sole.name;
                option.dataset.color = sole.color || '';
                datalist.appendChild(option);
            });
        } catch (err) {
            console.error('Failed to fetch soles:', err);
        }
    }

    // Auto-fill sole color when sole name is selected
    function autoFillSoleColor(input) {
        const value = input.value.trim();
        if (!value) return;

        const datalist = input.list;
        if (!datalist) return;

        const selectedOption = Array.from(datalist.options).find(opt => opt.value === value);
        if (selectedOption && selectedOption.dataset.color) {
            const row = input.closest('tr');
            const colorInput = row.querySelector('input[data-sole="sole_color"]');
            if (colorInput) {
                colorInput.value = selectedOption.dataset.color;
                // Optional: Visual feedback
                colorInput.style.backgroundColor = '#ecfdf5';
                setTimeout(() => colorInput.style.backgroundColor = '', 800);
            }
        }
    }

    // === CRITICAL: Fix dynamic rows (Add Variation button) ===
    const observer = new MutationObserver((mutations) => {
        mutations.forEach(mutation => {
            mutation.addedNodes.forEach(node => {
                if (node.nodeType !== 1 || !node.matches('tr')) return;

                const soleNameInput = node.querySelector('input[data-sole="sole_name"]');
                if (soleNameInput) {
                    // Ensure it has autocomplete off + unique name
                    soleNameInput.autocomplete = 'off';
                    soleNameInput.name = `variations[${Date.now()}][sole_name]`;

                    // Trigger datalist on next input
                    soleNameInput.addEventListener('input', () => {
                        if (soleNameInput.value.length >= 2) {
                            fetchAndAttachDatalist(soleNameInput, soleNameInput.value);
                        }
                    });
                }
            });
        });
    });

    const tbody = document.getElementById('variations-details');
    if (tbody) observer.observe(tbody, { childList: true });
});
</script>



<script>
    const batchFlowStoreUrl = "{{ route('batch.flow.store') }}";
</script>
<script src="{{ asset('js/batch-flow.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Listen for click/focus on any sole color input
    document.querySelectorAll('.sole-color-input').forEach(input => {
        input.addEventListener('focus', async function() {
            const productId = this.dataset.productId;
            const datalistId = this.getAttribute('list');
            const datalist = document.getElementById(datalistId);

            // Clear old suggestions
            datalist.innerHTML = '';

            if (!productId) return; // Skip if product not selected

            try {
                const response = await fetch(`/soles/colors/${productId}`);
                if (!response.ok) throw new Error('Failed to fetch colors');
                const colors = await response.json();

                if (colors.length === 0) {
                    console.log('No colors found for this product');
                    return;
                }

                colors.forEach(color => {
                    const option = document.createElement('option');
                    option.value = color;
                    datalist.appendChild(option);
                });
            } catch (err) {
                console.error('Error fetching colors:', err);
            }
        });
    });
});

</script>



<script>
document.addEventListener("DOMContentLoaded", function () {
    // -----------------------------------------------------------------
    // 1. Session & DOM Elements
    // -----------------------------------------------------------------
    const quotationProducts = @json($quotationProducts ?? []);
    const articleSelect     = document.getElementById('article_no');
    const productCard       = document.getElementById('product-image-card');
    const productImage      = document.getElementById('product-image');
    const productName       = document.getElementById('product-name');
    const productDesc       = document.getElementById('product-desc');
    const addClientModal    = document.getElementById('add-client-modal');
    const closeClientModalBtn = document.getElementById('close-client-modal');
    const cancelClientBtn   = document.getElementById('cancel-client-btn');
    const addClientForm     = document.getElementById('add-client-form');
    const addClientBtn      = document.getElementById('open-add-client'); // from checkbox UI
    let manuallyClosed = false;

    // -----------------------------------------------------------------
    // 2. Populate Tables (variations, sole, material, process)
    // -----------------------------------------------------------------
    const populateSections = (data) => {
        const sections = {
            'variations-details': data.variations ?? [],
            'sole-details'      : data.soles ?? [],
            'material-details'  : data.materials ?? [],
            'process-details'   : data.processes ?? [],
            // liquid_material-details REMOVED
        };

        Object.entries(sections).forEach(([id, items]) => {
            const tbody = document.getElementById(id);
            if (!tbody) return;

            tbody.innerHTML = '';

            const colSpanMap = {
                'variations-details': 14,
                'sole-details'      : 3,
                'material-details'  : 3,
                'process-details'   : 6,
            };

            if (!items || items.length === 0) {
                tbody.innerHTML = `<tr>
                    <td colspan="${colSpanMap[id]}" class="border px-3 py-2 text-center text-gray-500">
                        No ${id.replace('-details', '').replace('_', ' ')} data available
                    </td>
                </tr>`;
                return;
            }

            if (id === 'variations-details') {
                items.forEach((v, index) => {
                    tbody.appendChild(createVariationRow(index, v));
                });
            } else {
                items.forEach((item) => {
                    const tr = document.createElement('tr');
                    if (id === 'sole-details') {
                        tr.innerHTML = `<td class="border px-3 py-2">${item.name ?? 'N/A'}</td>
                                        <td class="border px-3 py-2">${item.color ?? 'N/A'}</td>
                                        <td class="border px-3 py-2">${item.sub_type ?? 'N/A'}</td>`;
                    } else if (id === 'material-details') {
                        tr.innerHTML = `<td class="border px-3 py-2">${item.name ?? 'N/A'}</td>
                                        <td class="border px-3 py-2">${item.color ?? 'N/A'}</td>
                                        <td class="border px-3 py-2">${item.unit ?? 'N/A'}</td>`;
                    } else if (id === 'process-details') {
                        tr.innerHTML = `<td class="border px-3 py-2">${item.name ?? 'N/A'}</td>
                                        <td class="border px-3 py-2">${item.stage ?? 'N/A'}</td>
                                        <td class="border px-3 py-2">${item.status ?? 'N/A'}</td>
                                        <td class="border px-3 py-2">${item.assigned_quantity ?? '0'}</td>
                                        <td class="border px-3 py-2">${item.completed_quantity ?? '0'}</td>
                                        <td class="border px-3 py-2">${item.labor_rate ?? '0'}</td>`;
                    }
                    tbody.appendChild(tr);
                });
            }
        });
    };

    // -----------------------------------------------------------------
    // 3. Create Variation Row (with sole_name & sole_color)
    // -----------------------------------------------------------------
    const createVariationRow = (index, variation = {}) => {
        const tr = document.createElement('tr');

        // Color
        const tdColor = document.createElement('td');
        tdColor.className = 'border px-3 py-2';
        const colorInput = document.createElement('input');
        colorInput.type = 'text';
        colorInput.name = `variations[${index}][color]`;
        colorInput.className = 'variations-input';
        colorInput.dataset.color = 'color';
        colorInput.value = variation.color ?? '';
        colorInput.placeholder = 'Color';
        tdColor.appendChild(colorInput);
        tr.appendChild(tdColor);

        // Sizes 35–44
        for (let size = 35; size <= 44; size++) {
            const tdSize = document.createElement('td');
            tdSize.className = 'border px-3 py-2 text-center';
            const sizeInput = document.createElement('input');
            sizeInput.type = 'text';
            sizeInput.name = `variations[${index}][${size}]`;
            sizeInput.className = 'variations-input text-center';
            sizeInput.dataset.size = size;
            sizeInput.value = variation.sizes?.[size] ?? '';
            sizeInput.placeholder = '0';
            sizeInput.style.width = '40px';

            sizeInput.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
            });
            sizeInput.addEventListener('blur', (e) => {
                let val = e.target.value;
                if (val === '') val = '0';
                else val = parseInt(val) || 0;
                e.target.value = val;
            });

            tdSize.appendChild(sizeInput);
            tr.appendChild(tdSize);
        }

        // Sole Name
       // Sole Name
const tdSoleName = document.createElement('td');
tdSoleName.className = 'border px-3 py-2';

const soleNameInput = document.createElement('input');
soleNameInput.type = 'text';
soleNameInput.name = `variations[${index}][sole_name]`;
soleNameInput.className = 'variations-input sole-name-input';
soleNameInput.dataset.sole = 'sole_name';
soleNameInput.placeholder = 'Sole Name';
soleNameInput.autocomplete = 'off';

// Add this: unique ID for datalist
const datalistId = 'sole-datalist-' + Math.random().toString(36).substr(2, 9);
soleNameInput.dataset.datalistId = datalistId;

tdSoleName.appendChild(soleNameInput);
tr.appendChild(tdSoleName);

        // Sole Color
        const tdSoleColor = document.createElement('td');
        tdSoleColor.className = 'border px-3 py-2';
        const soleColorInput = document.createElement('input');
        soleColorInput.type = 'text';
        soleColorInput.name = `variations[${index}][sole_color]`;
        soleColorInput.className = 'variations-input';
        soleColorInput.dataset.sole = 'sole_color';
        soleColorInput.value = variation.sole_color ?? '';
        soleColorInput.placeholder = 'Sole Color';
        tdSoleColor.appendChild(soleColorInput);
        tr.appendChild(tdSoleColor);

        // Delete Button
        const tdDelete = document.createElement('td');
        tdDelete.className = 'border px-3 py-2 text-center';
        const delBtn = document.createElement('button');
        delBtn.type = 'button';
        delBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M6.293 6.293a1 1 0 011.414 0L10 8.586l2.293-2.293a1 1 0 111.414 1.414L11.414 10l2.293 2.293a1 1 0 01-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 01-1.414-1.414L8.586 10 6.293 7.707a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>`;
        delBtn.className = 'bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded transition';
        delBtn.onclick = () => tr.remove();
        tdDelete.appendChild(delBtn);
        tr.appendChild(tdDelete);

        return tr;
    };

    // -----------------------------------------------------------------
    // 4. Show Product (from session or AJAX)
    // -----------------------------------------------------------------
    const showProduct = (product) => {
        if (!product || !product.product_id) {
            productCard.style.display = 'none';
            productImage.src = 'https://via.placeholder.com/150?text=No+Image';
            productName.textContent = '';
            productDesc.textContent = '';
            populateSections({});
            return;
        }

        articleSelect.value = product.product_id;

        const data = {
            variations: product.variations || [],
            soles: product.soles || [],
            materials: product.materials || [],
            processes: product.processes || [],
        };

        populateSections(data);
        productCard.style.display = 'flex';

        const imageSrc = product.image && product.image !== 'https://via.placeholder.com/150?text=No+Image'
            ? product.image
            : 'https://via.placeholder.com/150?text=No+Image';

        if (productImage.src !== imageSrc) productImage.src = imageSrc;
        productName.textContent = product.name ?? 'N/A';
        productDesc.textContent = product.description ?? 'No description available';
    };

    // -----------------------------------------------------------------
    // 5. Load Product via AJAX
    // -----------------------------------------------------------------
    const loadProductDetails = async (articleId) => {
        if (!articleId || manuallyClosed) return;

        const sessionProduct = quotationProducts.find(p => p.product_id == articleId);
        if (sessionProduct && sessionProduct.image) {
            showProduct(sessionProduct);
            return;
        }

        try {
            const res = await fetch(`/products/${articleId}/details`);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const data = await res.json();

            productCard.style.display = 'flex';

            const imageSrc = data.product?.image || 'https://via.placeholder.com/150?text=No+Image';
            if (productImage.src !== imageSrc) productImage.src = imageSrc;
            productName.textContent = data.product?.name ?? 'N/A';
            productDesc.textContent = data.product?.description ?? 'No description';

            const variations = quotationProducts.find(p => p.product_id == articleId)?.variations || data.variations || [];
            populateSections({ ...data, variations });

        } catch (err) {
            console.error(err);
            alert('Failed to load product details.');
        }
    };

    // -----------------------------------------------------------------
    // 6. Initial Load
    // -----------------------------------------------------------------
    if (quotationProducts.length > 0 && quotationProducts[0].product_id) {
        showProduct(quotationProducts[0]);
        articleSelect.value = quotationProducts[0].product_id;
    } else {
        showProduct(null);
    }

    articleSelect.addEventListener('change', () => {
        manuallyClosed = false;
        loadProductDetails(articleSelect.value);
    });

    // -----------------------------------------------------------------
    // 7. Add Variation Button
    // -----------------------------------------------------------------
    const addVariationBtn = document.getElementById('add-variation');
    if (addVariationBtn) {
        addVariationBtn.addEventListener('click', () => {
            const tbody = document.getElementById('variations-details');
            const index = tbody.querySelectorAll('tr').length;
            tbody.appendChild(createVariationRow(index));
        });
    }

    // -----------------------------------------------------------------
    // 8. Multi-Party: Open Modal
    // -----------------------------------------------------------------
    if (addClientBtn) {
        addClientBtn.addEventListener('click', () => {
            addClientModal.classList.remove('hidden');
            document.body.classList.add('modal-open');
            addClientForm.reset();
            document.querySelectorAll('#add-client-form .text-red-500')
                .forEach(el => { el.textContent = ''; el.classList.add('hidden'); });
        });
    }

    // -----------------------------------------------------------------
    // 9. Close Modal
    // -----------------------------------------------------------------
    const closeModal = () => {
        addClientModal.classList.add('hidden');
        document.body.classList.remove('modal-open');
    };
    closeClientModalBtn?.addEventListener('click', closeModal);
    cancelClientBtn?.addEventListener('click', closeModal);

    // -----------------------------------------------------------------
    // 10. Save New Client → Add Checkbox (checked)
    // -----------------------------------------------------------------
    addClientForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(addClientForm);

        document.querySelectorAll('#add-client-form .text-red-500')
            .forEach(el => { el.textContent = ''; el.classList.add('hidden'); });

        try {
            const res = await fetch("{{ route('clients.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await res.json();

            if (!res.ok) {
                if (res.status === 422 && data.errors) {
                    const msgs = [];
                    for (const [field, messages] of Object.entries(data.errors)) {
                        const el = document.getElementById(`${field}_error`);
                        if (el) { el.textContent = messages.join(' '); el.classList.remove('hidden'); }
                        msgs.push(...messages);
                    }
                    alert(msgs.join('\n'));
                    return;
                }
                alert(data.message || 'Error');
                return;
            }

            alert('Client added successfully!');
            addClientForm.reset();
            closeModal();

            // Add new checkbox (checked)
            if (data.client) {
                const container = document.querySelector('.max-h-48');
                const label = document.createElement('label');
                label.className = 'flex items-center space-x-2 cursor-pointer';

                const input = document.createElement('input');
                input.type = 'checkbox';
                input.name = 'client_id[]';
                input.value = data.client.id;
                input.checked = true;
                input.className = 'rounded text-indigo-600 focus:ring-indigo-500';

                const span = document.createElement('span');
                span.textContent = `${data.client.business_name} (${data.client.category})`;

                label.appendChild(input);
                label.appendChild(span);
                container.appendChild(label);
            }

        } catch (err) {
            console.error(err);
            alert('Unexpected error');
        }
    });

    // -----------------------------------------------------------------
    // 11. Form Submit: Collect Variations + Validate
    // -----------------------------------------------------------------
    const form = document.getElementById('batch-form');
    const nextBtn = document.getElementById('next-btn');

    if (form && nextBtn) {
        nextBtn.addEventListener('click', (e) => {
            e.preventDefault();

            const variations = [];
            let hasError = false;

            document.querySelectorAll('#variations-details tr').forEach((row, index) => {
    const colorInput = row.querySelector('[data-color="color"]');
    const soleColorInput = row.querySelector('[data-sole="sole_color"]');
    const soleNameInput = row.querySelector('[data-sole="sole_name"]');

    if (!colorInput) return; // skip "no data" row

    const color = colorInput.value.trim();
    const soleColor = soleColorInput?.value.trim() || '';
    const soleName = soleNameInput?.value.trim() || '';

    if (!color) {
        alert(`Variation ${index + 1}: Color is required.`);
        hasError = true;
        return;
    }
    if (!soleColor) {
        alert(`Variation ${index + 1}: Sole Color is required.`);
        hasError = true;
        return;
    }
    if (!soleName) {
        alert(`Variation ${index + 1}: Sole Name is required.`);
        hasError = true;
        return;
    }

    const variation = { color, sole_name: soleName, sole_color: soleColor, sizes: {} };
    row.querySelectorAll('[data-size]').forEach(input => {
        const size = input.dataset.size;
        variation.sizes[size] = input.value || '0';
    });

    variations.push(variation);
});

            if (hasError) return;

            document.getElementById('variations-hidden').value = JSON.stringify(variations);
            console.log('Submitting variations:', variations);
            form.submit();
        });
    }
});
</script>

@endsection