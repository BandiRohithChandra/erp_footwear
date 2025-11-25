@extends('layouts.app')
@section('content')
<div class="bg-gray-50 min-h-screen p-8">
    @if ($errors->any())
        <div class="bg-red-100 p-4 rounded mb-4">
            <ul class="list-disc list-inside text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white shadow-lg rounded-2xl p-8 space-y-6">
            {{-- Article Details --}}
            <h1 class="text-3xl font-extrabold text-gray-800">{{ __('Edit Article') }}</h1>

            {{-- Basic Article Inputs --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Article Name</label>
                    <input list="articleNames" name="name" type="text"
                           value="{{ old('name', $product->name) }}"
                           class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500"
                           required>
                    <datalist id="articleNames">
                        @foreach($articleNames as $name)
                            <option value="{{ $name }}">
                        @endforeach
                    </datalist>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Article No</label>
                    <input list="articleNos" name="sku" type="text"
                           value="{{ old('sku', $product->sku) }}"
                           class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500"
                           required>
                    <datalist id="articleNos">
                        @foreach($articleNos as $sku)
                            <option value="{{ $sku }}">
                        @endforeach
                    </datalist>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Article Type</label>
                    <input list="articleTypes" name="category" type="text"
                           value="{{ old('category', $product->category) }}"
                           class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500">
                    <datalist id="articleTypes">
                        @foreach($articleTypes as $type)
                            @if($type)
                                <option value="{{ $type }}">
                            @endif
                        @endforeach
                    </datalist>
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mt-3">{{ __('Description') }}</label>
                <textarea name="description" rows="3"
                          class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500"
                          placeholder="Enter article description">{{ old('description', $product->description) }}</textarea>
            </div>

            {{-- Variations Table --}}
            <div class="overflow-x-auto mt-6">
                <table class="w-full table-auto border border-gray-300 rounded-lg mb-4">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2">S.No</th>
                            <th class="border px-4 py-2">Color</th>
                            <th class="border px-4 py-2">Sizes</th>
                            <th class="border px-4 py-2">HSN Code</th>
                            <th class="border px-4 py-2">Images</th>
                            <th class="border px-4 py-2">Action</th>
                        </tr>
                    </thead>
                   <tbody id="productRows">
    @foreach($product->variations as $idx => $variation)
        @php
            $sizeList = is_array($variation['sizes'] ?? null) ? $variation['sizes'] : [];
            $images = $variation['images'] ?? [];
        @endphp
        <tr>
            <td class="border px-4 py-2">{{ $loop->iteration }}</td>
            <td class="border px-4 py-2">
                <input type="text" name="color[]" value="{{ $variation['color'] ?? '' }}"
                       class="w-full border-gray-300 rounded-lg px-2 py-1" required>
            </td>
            <td class="border px-4 py-2">
                <div class="flex flex-wrap gap-2">
                    @for($size = 35; $size <= 44; $size++)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="size[{{ $idx }}][]"
                                   value="{{ $size }}"
                                   {{ in_array($size, $sizeList) ? 'checked' : '' }}
                                   class="mr-1">
                            {{ $size }}
                        </label>
                    @endfor
                </div>
            </td>
            <td class="border px-4 py-2">6402</td>
            <td class="border px-4 py-2">
                {{-- Existing images --}}
                @if(is_array($images) && count($images))
                    <div class="flex flex-wrap gap-2 mb-2">
                        @foreach($images as $imgIndex => $imgPath)
                            <div class="relative">
                                <img src="{{ asset('storage/'.$imgPath) }}"
                                     class="w-12 h-12 object-cover rounded border">
                                <label class="absolute top-0 right-0 bg-white p-0.5 rounded-full text-xs cursor-pointer">
                                    <input type="checkbox"
                                           name="delete_images[{{ $idx }}][]"
                                           value="{{ $imgIndex }}"> Delete
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endif
                {{-- New uploads --}}
                <div class="image-fields space-y-2">
                    <input type="file" name="images[{{ $idx }}][]"
                           accept="image/*"
                           class="w-full border-gray-300 rounded-lg px-2 py-1">
                </div>
                <button type="button" onclick="addImageField(this)"
                        class="mt-2 px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                    Add More
                </button>
            </td>
            <td class="border px-4 py-2 text-center">
                <button type="button" onclick="removeRow(this)"
                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                    Delete
                </button>
            </td>
        </tr>
    @endforeach
</tbody>
                </table>
                <button type="button" onclick="addRow()"
                        class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                    Add New Variation
                </button>
            </div>

            {{-- Sole Section --}}
            <div class="mt-6 bg-gray-50 p-6 rounded-lg shadow space-y-4">
                <h3 class="text-lg font-semibold text-gray-700">Sole Details</h3>
                <div id="soleContainer">
                    @foreach($product->soles as $i => $sole)
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-3">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Sole Name / Article No</label>
                                <input list="soleNames" type="text"
                                       id="sole_name_{{ $i }}" name="sole_name_or_article_no[]"
                                       value="{{ $sole->pivot->sole_name ?? $sole->name }}"
                                       class="mt-1 block w-full border-gray-300 rounded-lg px-2 py-1">
                                <datalist id="soleNames">
                                    @foreach($soleNamesWithDetails as $s)
                                        <option value="{{ $s->name }}">
                                    @endforeach
                                </datalist>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Sole Color</label>
                                <input type="text" name="sole_color[]"
                                       value="{{ $sole->pivot->sole_color ?? $sole->color }}"
                                       class="mt-1 block w-full border-gray-300 rounded-lg px-2 py-1">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Sole Sub Type</label>
                                <input type="text" name="sole_sub_type[]"
                                       value="{{ $sole->pivot->sole_sub_type ?? $sole->subtype }}"
                                       class="mt-1 block w-full border-gray-300 rounded-lg px-2 py-1">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Sole Price</label>
                                <input type="number" name="sole_price[]"
                                       value="{{ $sole->pivot->sole_price ?? $sole->price }}"
                                       step="0.01"
                                       class="mt-1 block w-full border-gray-300 rounded-lg px-2 py-1">
                            </div>
                            <button type="button"
                                    onclick="this.parentElement.remove()"
                                    class="col-span-4 mt-2 px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                Delete
                            </button>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="addSoleRow()"
                        class="mt-3 px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                    Add More Sole
                </button>
            </div>

            {{-- Process Flow --}}
            <div class="mt-6 bg-gray-50 p-6 rounded-lg shadow space-y-4">
                <h3 class="text-lg font-semibold text-gray-700">Process Flow</h3>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border border-gray-300 rounded-lg">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-4 py-2">Process Name</th>
                                <th class="border px-4 py-2">Quantity</th>
                                <th class="border px-4 py-2">Cost (₹)</th>
                                <th class="border px-4 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody id="processContainer">
                            @foreach($product->processes as $proc)
                                <tr>
                                    <td class="border px-4 py-2">
                                        <input type="text" name="process_flow[]"
                                               value="{{ $proc->name }}" readonly
                                               class="w-full border-gray-300 rounded-lg px-2 py-1 text-center bg-gray-100">
                                    </td>
                                    <td class="border px-4 py-2">
                                        <input type="number" name="process_qty[]"
                                               value="{{ $proc->pivot->quantity ?? '' }}"
                                               placeholder="Qty"
                                               class="w-full border-gray-300 rounded-lg px-2 py-1 text-center" min="0">
                                    </td>
                                    <td class="border px-4 py-2">
                                        <input type="number" name="labor_rate[]"
                                               value="{{ $proc->pivot->labor_rate ?? '' }}"
                                               placeholder="₹"
                                               class="w-full border-gray-300 rounded-lg px-2 py-1 text-center" step="0.01">
                                    </td>
                                    <td class="border px-4 py-2 text-center">
                                        <button type="button"
                                                onclick="this.closest('tr').remove(); calculateCosting()"
                                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" onclick="addProcess()"
                        class="mt-3 px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                    Add More Process
                </button>
            </div>

            {{-- Materials --}}
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 p-6 rounded-lg shadow space-y-4">
                    <h3 class="text-lg font-semibold text-gray-700">Material Details</h3>
                    <div class="flex gap-3">
                        <div class="flex-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Material Name</label>
                            <input list="materialNames" type="text" id="material_name"
                                   name="material_name_temp" placeholder="Material Name"
                                   class="w-full border-gray-300 rounded px-2 py-1"
                                   oninput="handleDatalistInput(event, 'material')">
                            <datalist id="materialNames">
                                @foreach($materialNamesWithDetails as $mat)
                                    <option data-color="{{ $mat->color }}"
                                            data-unit="{{ $mat->unit }}"
                                            data-quantity="{{ $mat->quantity }}"
                                            data-per_unit_length="{{ $mat->per_unit_length }}"
                                            value="{{ $mat->name }}">
                                @endforeach
                                <option value="Add New Material...">
                            </datalist>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Color</label>
                            <input type="text" id="material_color" name="material_color_temp"
                                   placeholder="Color" class="w-full border-gray-300 rounded px-2 py-1">
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Per Unit</label>
                            <input type="text" id="material_unit" name="material_unit_temp"
                                   placeholder="Per Unit" class="w-full border-gray-300 rounded px-2 py-1">
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Quantity</label>
                            <input type="number" id="material_quantity" name="material_quantity_temp"
                                   placeholder="Quantity" class="w-full border-gray-300 rounded px-2 py-1" min="0">
                        </div>
                        <button type="button" onclick="addMaterialRow()"
                                class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-indigo-700">
                            Add
                        </button>
                    </div>

                    <table class="w-full text-sm text-left border mt-3 rounded">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="border px-2 py-1">Sr No</th>
                                <th class="border px-2 py-1">Material Name</th>
                                <th class="border px-2 py-1">Color</th>
                                <th class="border px-2 py-1">Per Unit</th>
                                <th class="border px-2 py-1">Quantity</th>
                                <th class="border px-2 py-1">Action</th>
                            </tr>
                        </thead>
                        <tbody id="materialTable">
                            @foreach($product->materials as $i => $mat)
                                <tr>
                                    <td class="border px-2 py-1">{{ $loop->iteration }}</td>
                                    <td class="border px-2 py-1">
                                        {{ $mat->name }}
                                        <input type="hidden" name="material_name[]" value="{{ $mat->name }}">
                                    </td>
                                    <td class="border px-2 py-1">
                                        {{ $mat->pivot->color ?? $mat->color }}
                                        <input type="hidden" name="material_color[]" value="{{ $mat->pivot->color ?? $mat->color }}">
                                    </td>
                                    <td class="border px-2 py-1">
                                        {{ $mat->unit }}
                                        <input type="hidden" name="material_unit[]" value="{{ $mat->unit }}">
                                    </td>
                                    <td class="border px-2 py-1">
                                        {{ $mat->pivot->quantity ?? $mat->quantity }}
                                        <input type="hidden" name="material_quantity[]"
                                               value="{{ $mat->pivot->quantity ?? $mat->quantity }}">
                                    </td>
                                    <td class="border px-2 py-1 text-center">
                                        <button type="button"
                                                onclick="editMaterialRow({{ $i }})"
                                                class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">
                                            Edit
                                        </button>
                                        <button type="button"
                                                onclick="this.closest('tr').remove(); calculateCosting()"
                                                class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="mt-6 flex gap-4">
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update
                </button>
                <button type="button" onclick="window.history.back()"
                        class="px-6 py-2 bg-green-400 text-black rounded-lg hover:bg-gray-500">
                    Exit
                </button>
            </div>
        </div>
    </form>

    {{-- Add / Edit Item Modal (same as create) --}}
    <div id="addNewItemModal"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="relative bg-white w-full max-w-2xl mx-4 rounded-2xl shadow-2xl transform transition-all scale-95 animate-fadeIn max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b px-6 py-4 sticky top-0 bg-white z-10">
                <h3 class="text-2xl font-bold text-blue-600" id="modalTitle">Add New Item</h3>
                <button type="button" onclick="closeModal()"
                        class="text-gray-500 hover:text-gray-600 text-2xl font-bold">X</button>
            </div>

            <form id="addNewItemForm" method="POST" class="px-6 py-4 space-y-4" novalidate>
                @csrf
                <input type="hidden" name="type" id="modal_type_input">
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="edit_index" id="edit_index" value="">

                {{-- Sole Content --}}
                <div id="soleContent" style="display: none;" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sole Name <span class="text-red-500">*</span></label>
                        <input type="text" id="modal_sole_name" name="name" required
                               class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                        <input type="text" id="modal_sole_color" name="color"
                               class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sole Type (Optional)</label>
                        <input type="text" id="modal_sole_type" name="sole_type"
                               class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sole Price (₹) <span class="text-red-500">*</span></label>
                        <input type="number" id="modal_sole_price" name="price" step="0.01" min="0" required
                               class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                {{-- Material Content --}}
                <div id="materialContent" style="display: none;" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Material Name <span class="text-red-500">*</span></label>
                        <input type="text" id="modal_material_name" name="material_name" required
                               class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                        <input type="text" id="modal_material_color" name="material_color"
                               class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit <span class="text-red-500">*</span></label>
                        <select id="modal_material_unit" name="material_unit" required
                                onchange="togglePerUnitLength(this.value, 'material')"
                                class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Unit</option>
                            <option value="kg">Kilogram (kg)</option>
                            <option value="g">Gram (g)</option>
                            <option value="litre">Litre (l)</option>
                            <option value="ml">Millilitre (ml)</option>
                            <option value="piece">Piece</option>
                            <option value="metre">Metre (m)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                        <input type="number" id="modal_material_quantity" name="material_quantity"
                               step="0.01" min="0" required
                               class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div id="materialPerUnitLength" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Per Unit Length (m)</label>
                        <input type="number" id="modal_material_per_unit_length" name="material_per_unit_length"
                               step="0.01" min="0.01" required
                               oninput="if(this.value < 0.01) this.value = 0.01;"
                               class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t bg-white sticky bottom-0">
                    <button type="button" onclick="closeModal()"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        Cancel
                    </button>
                    <button type="submit" id="modalSubmitBtn"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md transition disabled:bg-gray-400">
                        Add Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tailwind animation --}}
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fadeIn { animation: fadeIn 0.2s ease-out; }
    </style>
</div>

<script>
/* ==== ALL JAVASCRIPT FROM create.blade.php (unchanged) ==== */
let processCount = {{ $product->processes->count() }};
let materialCount = {{ $product->materials->count() }};
let soleCount = {{ $product->soles->count() }};

let soleOptions = {!! $soleNamesWithDetails->map(fn($s) => ['value'=>$s->name,'color'=>$s->color,'subtype'=>$s->subtype,'price'=>$s->price])->toJson() !!};
let materialOptions = {!! $materialNamesWithDetails->map(fn($m) => [
    'value'=>$m->name,'color'=>$m->color,'unit'=>$m->unit,'quantity'=>$m->quantity,
    'qty_per_unit'=>$m->per_unit_length,'price'=>$m->price ?? null
])->toJson() !!};

function generateProcessId(){ return Math.floor(100000 + Math.random() * 900000).toString(); }

function updateDatalist(datalistId, options, optionAttributes = {}) {
    const datalist = document.getElementById(datalistId);
    if (!datalist) return;
    datalist.innerHTML = '';
    const seen = new Set();
    options.forEach(o => {
        const key = (o.value ?? '') + '|' + (o.color ?? '');
        if (!seen.has(key)) {
            seen.add(key);
            const opt = document.createElement('option');
            opt.value = o.value ?? '';
            if (o.color) opt.dataset.color = o.color;
            if (o.unit) opt.dataset.unit = o.unit;
            if (o.quantity) opt.dataset.quantity = o.quantity;
            if (o.qty_per_unit) opt.dataset.qty_per_unit = o.qty_per_unit;
            if (o.subtype) opt.dataset.subtype = o.subtype;
            if (o.price) opt.dataset.price = o.price;
            datalist.appendChild(opt);
        }
    });
    const add = document.createElement('option');
    add.value = 'Add New ' + datalistId.replace('Names','') + '...';
    datalist.appendChild(add);
}

function updateAllDatalists() {
    updateDatalist('soleNames', soleOptions);
    updateDatalist('materialNames', materialOptions);
    document.querySelectorAll('datalist[id^="soleNames_"]').forEach(dl => updateDatalist(dl.id, soleOptions));
}

function setupDatalistHandler(input, type) {
    input.removeEventListener('input', handleDatalistInput);
    input.addEventListener('input', e => handleDatalistInput(e, type));
}
function handleDatalistInput(e, type) {
    const input = e.target;
    const val = input.value.trim();
    const addTxt = `Add New ${type.charAt(0).toUpperCase() + type.slice(1)}...`;
    if (val === addTxt || val.includes('Add New')) {
        openModal(type);
        input.value = '';
        return;
    }
    const datalist = input.list;
    if (!datalist) return;
    const opt = Array.from(datalist.options).find(o => o.value.trim() === val);
    if (!opt) return;

    switch (type) {
        case 'material':
            document.getElementById('material_color').value = opt.dataset.color || '';
            document.getElementById('material_unit').value = opt.dataset.unit || '';
            document.getElementById('material_quantity').value = opt.dataset.quantity || '';
            break;
        case 'sole':
            const row = input.closest('.grid');
            if (row) {
                row.querySelector('input[name="sole_color[]"]').value = opt.dataset.color || '';
                row.querySelector('input[name="sole_sub_type[]"]').value = opt.dataset.subtype || '';
                row.querySelector('input[name="sole_price[]"]').value = opt.dataset.price || '';
            }
            break;
    }
}

/* ==== Modal ==== */
function openModal(type, editIndex = null, rowData = null) {
    const modal = document.getElementById('addNewItemModal');
    const title = document.getElementById('modalTitle');
    const form = document.getElementById('addNewItemForm');
    form.reset();
    document.getElementById('edit_index').value = editIndex ?? '';
    document.getElementById('modal_type_input').value = type;
    ['soleContent','materialContent','materialPerUnitLength'].forEach(id=>document.getElementById(id).style.display='none');

    if (type === 'sole') {
        title.textContent = editIndex ? 'Edit Sole' : 'Add New Sole';
        document.getElementById('soleContent').style.display = 'block';
        if (rowData) {
            ['name','color','sole_type','price'].forEach(k=>document.getElementById(`modal_sole_${k}`).value = rowData[k] ?? '');
        }
    } else if (type === 'material') {
        title.textContent = editIndex ? 'Edit Material' : 'Add New Material';
        document.getElementById('materialContent').style.display = 'block';
        if (rowData) {
            document.getElementById('modal_material_name').value = rowData.material_name ?? '';
            document.getElementById('modal_material_color').value = rowData.material_color ?? '';
            document.getElementById('modal_material_unit').value = rowData.material_unit ?? '';
            document.getElementById('modal_material_quantity').value = rowData.material_quantity ?? '';
            document.getElementById('modal_material_per_unit_length').value = rowData.material_per_unit_length ?? '';
            togglePerUnitLength(rowData.material_unit, 'material');
        }
    }
    modal.style.display = 'flex';
}
function closeModal() {
    document.getElementById('addNewItemModal').style.display = 'none';
}

/* ==== Form submission (same AJAX as create) ==== */
document.getElementById('addNewItemForm').addEventListener('submit', async e => {
    e.preventDefault();
    const type = document.getElementById('modal_type_input').value;
    const editIdx = document.getElementById('edit_index').value;
    const btn = document.getElementById('modalSubmitBtn');
    btn.disabled = true; btn.textContent = editIdx ? 'Updating...' : 'Adding...';

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('type', type);
    if (type === 'sole') {
        formData.append('name', document.getElementById('modal_sole_name').value);
        formData.append('color', document.getElementById('modal_sole_color').value);
        formData.append('sole_type', document.getElementById('modal_sole_type').value);
        formData.append('price', document.getElementById('modal_sole_price').value);
    }
    if (type === 'material') {
        formData.append('name', document.getElementById('modal_material_name').value);
        formData.append('unit', document.getElementById('modal_material_unit').value);
        formData.append('quantity', document.getElementById('modal_material_quantity').value);
        formData.append('color', document.getElementById('modal_material_color').value);
        const per = document.getElementById('modal_material_per_unit_length').value;
        if (per) formData.append('per_unit_length', per);
    }

    const resp = await fetch('{{ route("products.add-item") }}', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    const data = await resp.json();
    btn.disabled = false; btn.textContent = editIdx ? 'Update Item' : 'Add Item';

    if (resp.ok && data.success && data.data) {
        if (type === 'sole') {
            const newS = {value:data.data.name, color:data.data.color, subtype:data.data.sole_type, price:data.data.price};
            if (editIdx) soleOptions[editIdx] = newS; else soleOptions.push(newS);
            updateAllDatalists();
        } else if (type === 'material') {
            const newM = {
                value:data.data.name, color:data.data.color, unit:data.data.unit,
                quantity:data.data.quantity, qty_per_unit:data.data.per_unit_length, price:data.data.price
            };
            if (editIdx) materialOptions[editIdx] = newM; else materialOptions.push(newM);
            updateAllDatalists();
            addMaterialRow(true, newM);
        }
        closeModal();
    } else {
        alert(`Error: ${data.message ?? 'Check console'}`);
    }
});

/* ==== Row helpers (same as create) ==== */
function addRow() {
    const tbody = document.getElementById('productRows');
    const idx = tbody.children.length;
    const sizes = Array.from({length:10},(_,i)=>35+i).map(s=>`
        <label class="inline-flex items-center">
            <input type="checkbox" name="size[${idx}][]" value="${s}" checked class="mr-1">${s}
        </label>`).join('');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td class="border px-4 py-2">${idx+1}</td>
        <td class="border px-4 py-2"><input type="text" name="color[]" class="w-full border-gray-300 rounded-lg px-2 py-1" required></td>
        <td class="border px-4 py-2"><div class="flex flex-wrap gap-2">${sizes}</div></td>
        <td class="border px-4 py-2">6402</td>
        <td class="border px-4 py-2">
            <div class="image-fields space-y-2">
                <input type="file" name="images[${idx}][]" accept="image/*"
                       class="w-full border-gray-300 rounded-lg px-2 py-1">
            </div>
            <button type="button" onclick="addImageField(this)"
                    class="mt-2 px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600">Add More</button>
        </td>
        <td class="border px-4 py-2 text-center">
            <button type="button" onclick="removeRow(this)"
                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete</button>
        </td>`;
    tbody.appendChild(tr);
}
function removeRow(btn){ btn.closest('tr').remove(); }

function addImageField(btn){
    const container = btn.parentElement.querySelector('.image-fields');
    const idx = Array.from(document.getElementById('productRows').children).indexOf(btn.closest('tr'));
    const input = document.createElement('input');
    input.type='file'; input.name=`images[${idx}][]`; input.accept='image/*';
    input.className='w-full border-gray-300 rounded-lg px-2 py-1';
    container.appendChild(input);
}

function addSoleRow() {
    const container = document.getElementById('soleContainer');
    const idx = container.children.length;
    const datalistId = 'soleNames_'+idx;
    const div = document.createElement('div');
    div.className = 'grid grid-cols-1 md:grid-cols-4 gap-4 mb-3';
    div.innerHTML = `
        <div><label class="block text-sm font-semibold text-gray-700">Sole Name / Article No</label>
            <input list="${datalistId}" type="text" id="sole_name_${idx}" name="sole_name_or_article_no[]"
                   class="mt-1 block w-full border-gray-300 rounded-lg px-2 py-1">
            <datalist id="${datalistId}"></datalist>
        </div>
        <div><label class="block text-sm font-semibold text-gray-700">Sole Color</label>
            <input type="text" name="sole_color[]" class="mt-1 block w-full border-gray-300 rounded-lg px-2 py-1">
        </div>
        <div><label class="block text-sm font-semibold text-gray-700">Sole Sub Type</label>
            <input type="text" name="sole_sub_type[]" class="mt-1 block w-full border-gray-300 rounded-lg px-2 py-1">
        </div>
        <div><label class="block text-sm font-semibold text-gray-700">Sole Price</label>
            <input type="number" name="sole_price[]" step="0.01"
                   class="mt-1 block w-full border-gray-300 rounded-lg px-2 py-1">
        </div>
        <button type="button" onclick="this.parentElement.remove()"
                class="col-span-4 mt-2 px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>`;
    container.appendChild(div);
    updateDatalist(datalistId, soleOptions);
    setupDatalistHandler(div.querySelector('#sole_name_'+idx), 'sole');
}

function addProcess(){
    const tbody = document.getElementById('processContainer');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td class="border px-4 py-2"><input type="text" name="process_flow[]" placeholder="Process Name"
                class="w-full border-gray-300 rounded-lg px-2 py-1 text-center"></td>
        <td class="border px-4 py-2"><input type="number" name="process_qty[]" placeholder="Qty"
                class="w-full border-gray-300 rounded-lg px-2 py-1 text-center" min="0"></td>
        <td class="border px-4 py-2"><input type="number" name="labor_rate[]" placeholder="₹"
                class="w-full border-gray-300 rounded-lg px-2 py-1 text-center" step="0.01"></td>
        <td class="border px-4 py-2 text-center"><button type="button"
                onclick="this.closest('tr').remove(); calculateCosting()"
                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete</button></td>`;
    tbody.appendChild(tr);
}

/* ==== Material row (same as create) ==== */
function addMaterialRow(fromModal = false, newItem = null) {
    let name, color, unit, quantity;
    const esc = s => s ? s.replace(/[&<>"']/g, m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m])) : '';
    if (fromModal && newItem) {
        name = esc(newItem.value);
        color = esc(newItem.color);
        unit = esc(newItem.unit);
        quantity = esc(newItem.quantity);
    } else {
        name = esc(document.getElementById('material_name').value.trim());
        color = esc(document.getElementById('material_color').value.trim());
        unit = esc(document.getElementById('material_unit').value.trim());
        quantity = esc(document.getElementById('material_quantity').value.trim());
        if (!name || !unit || !quantity) { alert('Fill all fields'); return; }
    }
    materialCount++;
    const tbody = document.getElementById('materialTable');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td class="border px-2 py-1">${materialCount}</td>
        <td class="border px-2 py-1">${name}<input type="hidden" name="material_name[]" value="${name}"></td>
        <td class="border px-2 py-1">${color}<input type="hidden" name="material_color[]" value="${color}"></td>
        <td class="border px-2 py-1">${unit}<input type="hidden" name="material_unit[]" value="${unit}"></td>
        <td class="border px-2 py-1">${quantity}<input type="hidden" name="material_quantity[]" value="${quantity}"></td>
        <td class="border px-2 py-1 text-center">
            <button type="button" onclick="editMaterialRow(${materialCount-1})"
                    class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">Edit</button>
            <button type="button" onclick="this.closest('tr').remove(); calculateCosting()"
                    class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</button>
        </td>`;
    tbody.appendChild(tr);
    if (!fromModal) {
        ['material_name','material_color','material_unit','material_quantity'].forEach(id=>document.getElementById(id).value='');
    }
}
function editMaterialRow(idx){
    const row = document.getElementById('materialTable').children[idx];
    const data = {
        material_name: row.querySelector('input[name="material_name[]"]').value,
        material_color: row.querySelector('input[name="material_color[]"]').value,
        material_unit: row.querySelector('input[name="material_unit[]"]').value,
        material_quantity: row.querySelector('input[name="material_quantity[]"]').value
    };
    openModal('material', idx, data);
}

/* ==== Costing (optional – same as create) ==== */
function calculateCosting(){ /* unchanged from create */ }

/* ==== Init ==== */
document.addEventListener('DOMContentLoaded', () => {
    updateAllDatalists();
    document.querySelectorAll('input[list]').forEach(i=>setupDatalistHandler(i, i.id.includes('sole')?'sole':'material'));
    calculateCosting();
});
</script>
@endsection