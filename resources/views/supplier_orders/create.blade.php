@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 space-y-6">

    {{-- Page Header --}}
    <div class="bg-blue-100 rounded-lg shadow p-5 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-blue-900">Create Purchase Order</h1>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('supplier-orders.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Supplier Info --}}
        <div class="bg-gray-50 rounded-lg shadow p-5 grid grid-cols-1 md:grid-cols-3 gap-6">

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Supplier</label>
                <select name="supplier_id" class="w-full rounded-lg p-2 border-gray-300 focus:ring-blue-400" required>
                    <option value="">-- Select Supplier --</option>
                    @foreach($suppliers as $supplier)
                        {{-- ⭐ Added GST attribute --}}
                        <option value="{{ $supplier->id }}" data-gst="{{ $supplier->gst_number }}">
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">PO Number</label>
                <input type="text" name="po_number" class="w-full rounded-lg p-2 bg-gray-200"
                       value="PO-{{ date('Ymd-His') }}" readonly>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Order Date</label>
                <input type="date" name="order_date" class="w-full rounded-lg p-2 border-gray-300" required>
            </div>
        </div>

        {{-- Items Table --}}
        <div class="bg-gray-50 rounded-lg shadow p-5">
            <label class="block text-gray-700 font-semibold mb-3">Items</label>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded-lg" id="po-items-table">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="py-2 px-4 border-b">Type</th>
                            <th class="py-2 px-4 border-b">Item</th>
                            <th class="py-2 px-4 border-b">Quantity</th>
                            <th class="py-2 px-4 border-b">Price</th>
                            <th class="py-2 px-4 border-b">Total</th>
                            <th class="py-2 px-4 border-b">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr class="item-row bg-white hover:bg-blue-50 transition">

                            <td class="p-2 border">
                                <select class="item-type border-gray-300 rounded-lg p-1 w-full" name="items[0][type]">
                                    <option value="">Select Type</option>
                                    <option value="raw_material">Material</option>
                                    <option value="sole">Sole</option>
                                </select>
                            </td>

                            <td class="p-2 border">
                                <select class="item-name border-gray-300 rounded-lg p-1 w-full"
                                        name="items[0][id]">
                                    <option value="">Select Item</option>
                                </select>
                            </td>

                            <td class="p-2 border">
                                <input type="number" name="items[0][quantity]"
                                       class="quantity border-gray-300 rounded-lg p-1 w-full">
                                <div class="sizes-container mt-1 space-y-1 hidden"></div>
                            </td>

                            <td class="p-2 border">
                                <input type="number" name="items[0][price]"
                                       class="unit-price border-gray-300 rounded-lg p-1 w-full">
                            </td>

                            <td class="p-2 border">
                                <input type="number" class="total border-gray-300 rounded-lg p-1 w-full" readonly>
                            </td>

                            <td class="p-2 border">
                                <button type="button"
                                        class="remove-row bg-red-500 text-white px-3 py-1 rounded">
                                    Remove
                                </button>
                            </td>

                        </tr>
                    </tbody>
                </table>

                <button type="button" id="add-row"
                        class="mt-3 bg-blue-600 text-white px-5 py-2 rounded">
                    + Add Item
                </button>

            </div>
        </div>

        {{-- Amounts --}}
        <div class="bg-gray-50 rounded-lg shadow p-5 grid grid-cols-1 md:grid-cols-3 gap-6">

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Subtotal (₹)</label>
                <input type="number" name="total_amount" class="w-full rounded-lg p-2 bg-gray-200" readonly>
            </div>

            {{-- ⭐ NEW GST --}}
            <div>
                <label class="block text-gray-700 font-semibold mb-2">GST Amount (₹)</label>
                <input type="number" name="gst_amount" class="w-full rounded-lg p-2 bg-gray-200" readonly>
            </div>

            {{-- ⭐ NEW Final Total --}}
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Total With GST (₹)</label>
                <input type="number" name="total_with_gst" class="w-full rounded-lg p-2 bg-gray-200" readonly>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Paid Amount (₹)</label>
                <input type="number" name="paid_amount" class="w-full rounded-lg p-2 border-gray-300" value="0">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Payment Status</label>
                <select name="payment_status" class="w-full rounded-lg p-2 border-gray-300">
                    <option value="pending">Pending</option>
                    <option value="partial">Partial</option>
                    <option value="paid">Paid</option>
                </select>
            </div>
        </div>

        {{-- Remarks --}}
        <div class="bg-gray-50 rounded-lg shadow p-5">
            <label class="block text-gray-700 font-semibold mb-2">Remarks</label>
            <textarea name="remarks" rows="3"
                      class="w-full rounded-lg p-2 border-gray-300"></textarea>
        </div>

        {{-- Submit --}}
        <div class="flex items-center space-x-4">
            <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded shadow">
                Create Purchase Order
            </button>
            <a href="{{ route('supplier-orders.index') }}" class="text-gray-700 hover:underline">
                Cancel
            </a>
        </div>

    </form>
</div>


{{-- ====================== JS ======================= --}}
<script>

let rowIndex = 0;
const rawMaterials = @json($rawMaterials);
const liquidMaterials = @json($liquidMaterials);
const soles = @json($soles);

/* ⭐ NEW GST LOGIC */
const companyGst = "{{ \App\Models\Settings::get('company_gst') }}";
const companyState = companyGst ? companyGst.substring(0, 2) : null;

let paidAmountAutoFilled = false; // ⭐ NEW FLAG

function updateGST() {
    const supplier = document.querySelector("select[name='supplier_id']").selectedOptions[0];
    if (!supplier) return;

    const supplierGst = supplier.dataset.gst || null;
    const supplierState = supplierGst ? supplierGst.substring(0, 2) : null;

    const subtotal = parseFloat(document.querySelector("input[name='total_amount']").value) || 0;

    let gstAmount = subtotal * 0.05; // 5% always for now

    document.querySelector("input[name='gst_amount']").value = gstAmount.toFixed(2);

    const finalTotal = (subtotal + gstAmount).toFixed(2);
    document.querySelector("input[name='total_with_gst']").value = finalTotal;

    const paid = document.querySelector("input[name='paid_amount']");

    // ⭐ Only auto-fill ONCE when user has not touched the field
    if (!paidAmountAutoFilled && (!paid.value || paid.value == "0")) {
        paid.value = finalTotal;
        paidAmountAutoFilled = true; // stop future overwrites
    }
}

// ⭐ When user edits paid amount manually → disable auto-filling
document.querySelector("input[name='paid_amount']").addEventListener("input", function() {
    paidAmountAutoFilled = true;
});


document.querySelector("select[name='supplier_id']").addEventListener('change', updateGST);


/* TOTAL CALCULATION */
function updateTotalAmount() {
    let total = 0;
    document.querySelectorAll('input.total').forEach(input => {
        total += parseFloat(input.value) || 0;
    });

    document.querySelector('input[name="total_amount"]').value = total;

    updateGST();
}


/* ⭐ FIXED ITEM POPULATION WITH SOLES + SIZE LOGIC */
function populateItems(row) {

    const typeSelect = row.querySelector('.item-type');
    const nameSelect = row.querySelector('.item-name');
    const priceInput = row.querySelector('.unit-price');
    const quantityInput = row.querySelector('.quantity');
    const totalInput = row.querySelector('.total');
    const sizesContainer = row.querySelector('.sizes-container');

    // TYPE CHANGE
    typeSelect.addEventListener('change', function () {
        const type = this.value;
        nameSelect.innerHTML = '<option value="">Select Item</option>';
        let items = [];

        if (type === 'raw_material') items = rawMaterials;
        if (type === 'sole') items = soles;

        items.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;
            option.dataset.price = item.price;
            nameSelect.appendChild(option);
        });

        // reset fields
        priceInput.value = "";
        quantityInput.value = "";
        totalInput.value = "0";
        sizesContainer.innerHTML = "";
        quantityInput.classList.remove("hidden");
        sizesContainer.classList.add("hidden");
    });

    // ITEM CHANGE → price + size logic
    nameSelect.addEventListener('change', function () {

        const selected = this.options[this.selectedIndex];
        const price = parseFloat(selected.dataset.price || 0);

        priceInput.value = price;

        // SOLE => enable sizes
        if (typeSelect.value === 'sole') {

            quantityInput.classList.add('hidden');
            sizesContainer.classList.remove('hidden');
            sizesContainer.innerHTML = "";

            for (let size = 35; size <= 44; size++) {

                const wrapper = document.createElement('div');
                wrapper.classList.add('flex', 'items-center', 'space-x-2');

                const label = document.createElement('label');
                label.textContent = size;
                label.classList.add('text-sm', 'w-8');

                const input = document.createElement('input');
                input.type = "number";
                input.min = 0;
                input.value = "";
                input.name = `items[${rowIndex}][sizes_qty][${size}]`;
                input.classList.add('border', 'rounded', 'p-1', 'w-full');

                // SIZE → update total
                input.addEventListener('input', function () {
                    let qtyTotal = 0;
                    sizesContainer.querySelectorAll('input').forEach(i => {
                        qtyTotal += parseInt(i.value) || 0;
                    });

                    totalInput.value = (qtyTotal * price).toFixed(2);
                    updateTotalAmount();
                });

                wrapper.appendChild(label);
                wrapper.appendChild(input);
                sizesContainer.appendChild(wrapper);
            }

            totalInput.value = "0.00";
        }

        // RAW MATERIAL
        else {
            quantityInput.classList.remove('hidden');
            sizesContainer.classList.add('hidden');
            sizesContainer.innerHTML = "";

            const qty = parseFloat(quantityInput.value) || 0;
            totalInput.value = (qty * price).toFixed(2);
        }

        updateTotalAmount();
    });

    // RAW quantity change
    quantityInput.addEventListener('input', function () {
        if (typeSelect.value !== 'sole') {
            const price = parseFloat(priceInput.value) || 0;
            totalInput.value = (price * this.value).toFixed(2);
            updateTotalAmount();
        }
    });

    // price change
    priceInput.addEventListener('input', function () {

        if (typeSelect.value === 'sole') {
            let qtyTotal = 0;
            row.querySelectorAll('.sizes-container input').forEach(i => {
                qtyTotal += parseInt(i.value) || 0;
            });
            totalInput.value = (qtyTotal * this.value).toFixed(2);
        } else {
            const qty = parseFloat(quantityInput.value) || 0;
            totalInput.value = (qty * this.value).toFixed(2);
        }

        updateTotalAmount();
    });
}


/* INIT EXISTING ROW */
document.querySelectorAll('tr.item-row').forEach(populateItems);


/* ADD ROW */
document.getElementById('add-row').addEventListener('click', function () {

    const tbody = document.querySelector('#po-items-table tbody');
    const newRow = tbody.querySelector('tr.item-row').cloneNode(true);

    rowIndex++;

    newRow.querySelectorAll('input, select').forEach(el => {
        if (el.name) el.name = el.name.replace(/\[\d+\]/, `[${rowIndex}]`);
        el.value = "";
    });

    newRow.querySelector('.sizes-container').innerHTML = "";
    newRow.querySelector('.sizes-container').classList.add('hidden');
    newRow.querySelector('.quantity').classList.remove('hidden');

    tbody.appendChild(newRow);

    populateItems(newRow);
});

/* REMOVE ROW */
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
        updateTotalAmount();
    }
});

</script>

@endsection
