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

<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="bg-white shadow-lg rounded-2xl p-8 space-y-6">

        {{-- Article Details --}}
        <h1 class="text-3xl font-extrabold text-gray-800">{{ __('Add New Article') }}</h1>
         {{-- Basic Article Inputs --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Article Name</label>
                <input list="articleNames" name="name" type="text" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500" required>
                <datalist id="articleNames">
                    @foreach($articleNames as $name)
                        <option value="{{ $name }}">
                    @endforeach
                </datalist>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700">Article No</label>
                <input list="articleNos" name="sku" type="text" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500" required>
                <datalist id="articleNos">
                    @foreach($articleNos as $sku)
                        <option value="{{ $sku }}">
                    @endforeach
                </datalist>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700">Article Type</label>
                <input list="articleTypes" name="category" type="text" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500">
                <datalist id="articleTypes">
                    @foreach($articleTypes as $type)
                        @if($type)
                            <option value="{{ $type }}">
                        @endif
                    @endforeach
                </datalist>
            </div>
        </div>

        <div class="mb-4">
    <label class="block font-medium mb-1">Price</label>
    <input type="number" step="0.01" name="price" class="w-full border-gray-300 rounded-lg px-3 py-2" placeholder="Enter price (optional)">
</div>


        {{-- Description --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mt-3">{{ __('Description') }}</label>
            <textarea name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500" placeholder="Enter article description"></textarea>
        </div>

        

        {{-- Variations Table (Updated - No Unit Price, Price, GST) --}}
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
                    <tr>
                        <td class="border px-4 py-2">1</td>
                        <td class="border px-4 py-2"><input type="text" name="color[]" class="w-full border-gray-300 rounded-lg px-2 py-1" required></td>
                        <td class="border px-4 py-2">
                            <div class="flex flex-wrap gap-2">
                                @for($size = 35; $size <= 44; $size++)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="size[0][]" value="{{ $size }}" checked class="mr-1">
                                        {{ $size }}
                                    </label>
                                @endfor
                            </div>
                        </td>
                        <td class="border px-4 py-2">6402</td>
                        <td class="border px-4 py-2">
                            <div class="image-fields space-y-2">
                                <input type="file" name="images[0][]" accept="image/*" class="w-full border-gray-300 rounded-lg px-2 py-1">
                            </div>
                            <button type="button" onclick="addImageField(this)" class="mt-2 px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600">Add More</button>
                        </td>
                        <td class="border px-4 py-2 text-center">
                            <button type="button" onclick="removeRow(this)" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button type="button" onclick="addRow()" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">Add New Variation</button>
        </div>

        {{-- Sole Section --}}
{{-- Sole Section (Updated to allow Sole Name or Sole Article No in one field) --}}
<div class="mt-6 bg-gray-50 p-6 rounded-lg shadow space-y-4">
    <h3 class="text-lg font-semibold text-gray-700">Sole Details</h3>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700">
                Sole Name / Article No
            </label>
            <input list="soleNames" type="text" id="sole_name_0" name="sole_name_or_article_no[]" 
                   placeholder="Enter Sole Name or Article No" 
                   class="mt-1 block w-full border-gray-300 rounded-lg px-2 py-1">
            <datalist id="soleNames">
                @foreach($soleNamesWithDetails as $sole)
                    <option value="{{ $sole->name }}"> {{-- name or article no --}}
                @endforeach
            </datalist>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Sole Color</label>
            <input type="text" id="sole_color_0" name="sole_color[]" 
                   placeholder="Sole Color" 
                   class="mt-1 block w-full border-gray-300 rounded-lg px-2 py-1">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Sole Sub Type</label>
            <input type="text" id="sole_sub_type_0" name="sole_sub_type[]" 
                   placeholder="Sole Subtype" 
                   class="mt-1 block w-full border-gray-300 rounded-lg px-2 py-1">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Sole Price</label>
            <input type="number" id="sole_price_0" name="sole_price[]" 
                   placeholder="Enter Sole Price" step="0.01" 
                   class="mt-1 block w-full border-gray-300 rounded-lg px-2 py-1">
        </div>
    </div>

    <button type="button" onclick="addSoleRow()" 
            class="mt-3 px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
        Add More Sole
    </button>
    <div id="additionalSoles" class="mt-4 space-y-2"></div>
</div>



        {{-- Process Flow --}}
        <div class="mt-6 bg-gray-50 p-6 rounded-lg shadow space-y-4">
            <h3 class="text-lg font-semibold text-gray-700">Process Flow</h3>
            <div class="overflow-x-auto">
                <table class="w-full table-auto border border-gray-300 rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <!-- <th class="border px-4 py-2">Process ID</th> -->
                            <th class="border px-4 py-2">Process Name</th>
                            <th class="border px-4 py-2">Quantity</th>
                            <th class="border px-4 py-2">Cost (₹)</th>
                            <!-- <th class="border px-4 py-2">Order</th> -->
                            <th class="border px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody id="processContainer">
                        @php $defaultProcesses = ['Upper Part','Bottom Part','Finished Part']; @endphp
                        @foreach($defaultProcesses as $index => $process)
                        <tr>
                            <!-- <td class="border px-4 py-2"><input type="text" name="process_id[]" value="{{ sprintf('%06d', rand(100000, 999999)) }}" class="w-full border-gray-300 rounded-lg px-2 py-1 bg-gray-100 text-center" readonly></td> -->
                            <td class="border px-4 py-2"><input type="text" name="process_flow[]" value="{{ $process }}" class="w-full border-gray-300 rounded-lg px-2 py-1 text-center" readonly></td>
                            <td class="border px-4 py-2"><input type="number" name="process_qty[]" placeholder="Qty" class="w-full border-gray-300 rounded-lg px-2 py-1 text-center" min="0"></td>
                            <td class="border px-4 py-2"><input type="number" name="labor_rate[]" placeholder="₹" class="w-full border-gray-300 rounded-lg px-2 py-1 text-center" step="0.01"></td>
                            <!-- <td class="border px-4 py-2"><input type="number" name="process_order[]" value="{{ $index+1 }}" class="w-full border-gray-300 rounded-lg px-2 py-1 text-center" min="1"></td> -->
                            <td class="border px-4 py-2 text-center"><button type="button" onclick="this.closest('tr').remove(); calculateCosting()" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="button" onclick="addProcess()" class="mt-3 px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">Add More Process</button>
        </div>

        {{-- Materials & Liquid Materials --}}
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Solid Materials --}}
            <div class="bg-gray-50 p-6 rounded-lg shadow space-y-4">
                <h3 class="text-lg font-semibold text-gray-700">Material Details</h3>
                <div class="flex gap-3">
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Material Name</label>
                        <input list="materialNames" 
                            type="text" 
                            id="material_name" 
                            name="material_name_temp" 
                            placeholder="Material Name" 
                            class="w-full border-gray-300 rounded px-2 py-1"
                            oninput="handleDatalistInput(event, 'material')">
                        <datalist id="materialNames">
                            @foreach($materialNamesWithDetails as $material)
                                <option 
                                    data-color="{{ $material->color }}" 
                                    data-unit="{{ $material->unit }}" 
                                    data-quantity="{{ $material->quantity }}" 
                                    data-per_unit_length="{{ $material->per_unit_length }}" 
                                    value="{{ $material->name }}">
                            @endforeach
                            <option value="Add New Material...">
                        </datalist>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Color</label>
                        <input type="text" id="material_color" name="material_color_temp" placeholder="Color" class="w-full border-gray-300 rounded px-2 py-1">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Per Unit</label>
                        <input type="text" id="material_unit" name="material_unit_temp" placeholder="Per Unit" class="w-full border-gray-300 rounded px-2 py-1">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Quantity</label>
                        <input type="number" id="material_quantity" name="material_quantity_temp" placeholder="Quantity" class="w-full border-gray-300 rounded px-2 py-1" min="0">
                    </div>
                    <button type="button" onclick="addMaterialRow()" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-indigo-700">Add</button>
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
                    <tbody id="materialTable"></tbody>
                </table>
            </div>

            {{-- Liquid Materials --}}
            <!-- <div class="bg-gray-50 p-6 rounded-lg shadow space-y-4">
                <h3 class="text-lg font-semibold text-gray-700">Liquid Material</h3>
                <div class="flex gap-3">
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Material Name</label>
                        <input list="liquidMaterialNames" 
                            type="text" 
                            id="liquid_material_name" 
                            name="liquid_material_name_temp" 
                            placeholder="Material Name" 
                            class="w-full border-gray-300 rounded px-2 py-1"
                            oninput="handleDatalistInput(event, 'liquid')">
                        <datalist id="liquidMaterialNames">
                            @foreach($liquidNamesWithDetails as $liquid)
                                <option 
                                    data-unit="{{ $liquid->unit }}" 
                                    data-quantity="{{ $liquid->quantity }}" 
                                    data-per_unit_volume="{{ $liquid->per_unit_volume }}"
                                    value="{{ $liquid->name }}">
                            @endforeach
                            <option value="Add New Liquid Material...">
                        </datalist>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Per Unit</label>
                        <input type="text" id="liquid_material_unit" name="liquid_material_unit_temp" placeholder="Per Unit" class="w-full border-gray-300 rounded px-2 py-1">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Quantity</label>
                        <input type="number" id="liquid_material_quantity" name="liquid_material_quantity_temp" placeholder="Quantity" class="w-full border-gray-300 rounded px-2 py-1" min="0">
                    </div>
                    <button type="button" onclick="addLiquidMaterialRow()" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-indigo-700">Add</button>
                </div>
                <table class="w-full text-sm text-left border mt-3 rounded">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-2 py-1">Sr No</th>
                            <th class="border px-2 py-1">Material Name</th>
                            <th class="border px-2 py-1">Per Unit</th>
                            <th class="border px-2 py-1">Quantity</th>
                            <th class="border px-2 py-1">Action</th>
                        </tr>
                    </thead>
                    <tbody id="liquidMaterialTable"></tbody>
                </table>
            </div> -->
        </div>

        {{-- Costing Summary --}}
        <!-- <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 p-6 rounded-lg shadow space-y-4">
                <h3 class="text-lg font-semibold text-gray-700">Costing Summary</h3>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Production Cost (₹)</label>
                    <input type="text" id="production_cost" class="mt-1 block w-full border-gray-300 rounded-lg px-2 py-1 bg-gray-100" readonly>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Profit (₹)</label>
                    <input type="text" id="profit" class="mt-1 block w-full border-gray-300 rounded-lg px-2 py-1 bg-gray-100" readonly>
                </div>
            </div>
        </div> -->

        {{-- Buttons --}}
        <div class="mt-6 flex gap-4">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save</button>
            <button type="button" onclick="window.history.back()" class="px-6 py-2 bg-green-400 text-black rounded-lg hover:bg-gray-500">Exit</button>
        </div>

    </div>
</form>

<div id="addNewItemModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50" style="display: none;">
    <div class="relative bg-white w-full max-w-2xl mx-4 rounded-2xl shadow-2xl transform transition-all scale-95 animate-fadeIn max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="flex justify-between items-center border-b px-6 py-4 sticky top-0 bg-white z-10">
            <h3 class="text-2xl font-bold text-blue-600" id="modalTitle">Add New Item</h3>
            <button type="button" onclick="closeModal()" class="text-gray-500 hover:text-gray-600 text-2xl font-bold">
                ✕
            </button>
        </div>

        <!-- Body -->
        <form id="addNewItemForm" method="POST" class="px-6 py-4 space-y-4" novalidate>
            @csrf
            <input type="hidden" name="type" id="modal_type_input">
            <input type="hidden" name="product_id" value="">
            <input type="hidden" name="edit_index" id="edit_index" value="">

            <!-- Sole Content -->
<!-- Sole Content -->
<div id="soleContent" style="display: none;" class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Sole Name <span class="text-red-500">*</span></label>
        <input type="text" id="modal_sole_name" name="name" required
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
        <input type="text" id="modal_sole_color" name="color"
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Sole Type (Optional)</label>
        <input type="text" id="modal_sole_type" name="sole_type"
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Sole Price (₹) <span class="text-red-500">*</span></label>
        <input type="number" id="modal_sole_price" name="price" step="0.01" min="0" required
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    <!-- <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity per Unit</label>
        <input type="number" id="modal_sole_qty_per_unit" name="qty_per_unit" step="0.01" min="0"
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div> -->
</div>


            <!-- Material Content -->
<div id="materialContent" style="display: none;" class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Material Name <span class="text-red-500">*</span></label>
        <input type="text" id="modal_material_name" name="material_name" required
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
        <input type="text" id="modal_material_color" name="material_color"
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Unit <span class="text-red-500">*</span></label>
        <select id="modal_material_unit" name="material_unit" required onchange="togglePerUnitLength(this.value, 'material')"
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
        <input type="number" id="modal_material_quantity" name="material_quantity" step="0.01" min="0" required
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>
    <div id="materialPerUnitLength" style="display: none;">
        <label class="block text-sm font-medium text-gray-700 mb-1">Per Unit Length (m)</label>
        <input type="number" 
       id="modal_material_per_unit_length" 
       name="material_per_unit_length" 
       step="0.01" 
       min="0.01" 
       required
       oninput="if(this.value < 0.01) this.value = 0.01;"
       class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

    </div>
    <!-- <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Price per Unit (₹) <span class="text-red-500">*</span></label>
        <input type="number" 
       id="modal_material_price" 
       name="material_price" 
       step="0.01" 
       min="0" 
    
       oninput="this.value = this.value.replace(/[^0-9.]/g, '')"
       class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

    </div> -->
</div>


<!-- Liquid Content -->
<div id="liquidContent" style="display: none;" class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Liquid Material Name <span class="text-red-500">*</span></label>
        <input type="text"
               id="modal_liquid_name"
               name="liquid_name"
               required
               pattern=".*\S.*" 
               title="Liquid Name must be a string"
               class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Unit <span class="text-red-500">*</span></label>
        <select id="modal_liquid_unit"
                name="liquid_unit"
                required
                onchange="togglePerUnitLength(this.value, 'liquid')"
                class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">Select Unit</option>
            <option value="litre">Litre (l)</option>
            <option value="ml">Millilitre (ml)</option>
            <option value="kg">Kilogram (kg)</option>
            <option value="g">Gram (g)</option>
            <option value="piece">Piece</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
        <input type="number" id="modal_liquid_quantity" name="liquid_quantity" step="0.01" min="0" required
               class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    <div id="liquidPerUnitLength" style="display: none;">
        <label class="block text-sm font-medium text-gray-700 mb-1">Per Unit Volume (if piece)</label>
        <input type="number" id="modal_liquid_per_unit_volume" name="liquid_per_unit_volume" step="0.01" min="0.01"
               class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Price per Unit (₹) <span class="text-red-500">*</span></label>
        <input type="number" id="modal_liquid_price" name="liquid_price" step="0.01" min="0" required
               class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>
</div>



            <!-- Footer -->
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


<!-- Tailwind animation -->
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.animate-fadeIn { animation: fadeIn 0.2s ease-out; }

/* Prevent form validation errors for hidden fields */
.hidden-form-fields input:invalid,
.hidden-form-fields select:invalid {
    display: none !important;
}
</style>

</div>

<script>
// Unified toggle function

function togglePerUnitLength(unit, type) {
  // Identify correct content section (for materials or liquids)
  const perUnitWrapper = document.getElementById(`${type}PerUnitLength`);
  const perUnitInput = document.getElementById(`modal_${type}_per_unit_length`) || 
                       document.getElementById(`modal_${type}_per_unit_volume`);
  const quantityLabel = document.querySelector(`#${type}Content label[for="modal_${type}_quantity"]`);
  const perUnitLabel = perUnitWrapper?.querySelector('label');

  // Safety checks
  if (!perUnitWrapper || !perUnitInput) return;

  // Reset state first
  perUnitWrapper.style.display = 'none';
  perUnitInput.required = false;
  perUnitInput.value = '';
  perUnitInput.placeholder = '';

  if (quantityLabel) quantityLabel.textContent = 'Quantity';

  // Handle each unit type
  switch (unit) {
    case 'piece':
      perUnitWrapper.style.display = 'block';
      perUnitInput.required = true;

      if (type === 'material') {
        perUnitLabel.textContent = 'Length per Piece (m)';
        perUnitInput.placeholder = 'Enter length (e.g., 25)';
      } else if (type === 'liquid') {
        perUnitLabel.textContent = 'Volume per Piece (ml or L)';
        perUnitInput.placeholder = 'Enter volume per piece';
      }

      if (quantityLabel) quantityLabel.textContent = 'Quantity (No. of Pieces)';
      break;

    default:
      // For kg, g, litre, ml, metre → no extra field needed
      perUnitWrapper.style.display = 'none';
      perUnitInput.required = false;
      perUnitInput.value = '';
      if (quantityLabel) quantityLabel.textContent = 'Quantity';
      break;
  }
}

// Attach listeners (safe once DOM is loaded)
document.addEventListener('DOMContentLoaded', () => {
  const materialUnit = document.getElementById('modal_material_unit');
  const liquidUnit = document.getElementById('modal_liquid_unit');

  if (materialUnit) {
    materialUnit.addEventListener('change', (e) => togglePerUnitLength(e.target.value, 'material'));
  }
  if (liquidUnit) {
    liquidUnit.addEventListener('change', (e) => togglePerUnitLength(e.target.value, 'liquid'));
  }
});



let processCount = {{ count($defaultProcesses ?? []) }};
let materialCount = 0;
let liquidMaterialCount = 0;
let soleCount = 0;

// Initialize global item options
let soleOptions = {!! $soleNamesWithDetails->map(function($s) { return ['value' => $s->name, 'color' => $s->color, 'subtype' => $s->subtype, 'price' => $s->price]; })->toJson() !!};
let materialOptions = {!! $materialNamesWithDetails->map(function($m) { return [
    'value' => $m->name,
    'color' => $m->color,
    'unit' => $m->unit,
    'quantity' => $m->quantity,
    'qty_per_unit' => $m->per_unit_length,
    'price' => $m->price ?? null,
]; })->toJson() !!};
let liquidOptions = {!! $liquidNamesWithDetails->map(function($l) { return [
    'value' => $l->name,
    'unit' => $l->unit,
    'quantity' => $l->quantity ?? 0,
    'qty_per_unit' => $l->per_unit_volume ?? 0,
    'price' => $l->price ?? 0
]; })->toJson() !!};

// Generate 6-digit random number
function generateProcessId(){ return Math.floor(100000 + Math.random() * 900000).toString(); }

// Function to update datalist dynamically safely
function updateDatalist(datalistId, options, optionAttributes = {}) {
    const datalist = document.getElementById(datalistId);
    if (!datalist) {
        console.error(`Datalist with ID ${datalistId} not found`);
        return;
    }
    
    datalist.innerHTML = '';
    const seenValues = new Set();
    
    options.forEach(function(optionData) {
        const val = optionData.value ?? ''; // <-- safe fallback
        if (!val) return; // skip empty/undefined values
        const valLower = (val + '|' + (optionData.color ?? '')).toLowerCase();


        if (!seenValues.has(valLower)) {
            seenValues.add(valLower);

            const option = document.createElement('option');
            option.value = val;

            // Add data attributes if they exist
            if (optionData.color) option.setAttribute('data-color', optionData.color);
            if (optionData.unit) option.setAttribute('data-unit', optionData.unit);
            if (optionData.quantity) option.setAttribute('data-quantity', optionData.quantity);
            if (optionData.qty_per_unit) option.setAttribute('data-qty_per_unit', optionData.qty_per_unit);
            if (optionData.subtype) option.setAttribute('data-subtype', optionData.subtype);
            // ✅ Always set price attribute, even if 0 or undefined (default to 0)
            option.setAttribute('data-price', optionData.price ?? 0);

            datalist.appendChild(option);
        }
    });

    // "Add New ..." option
    const addNewOption = document.createElement('option');
    addNewOption.value = 'Add New ' + datalistId.replace('Names', '') + '...';
    datalist.appendChild(addNewOption);
}

// Update all dynamic datalists
// Update all dynamic datalists
function updateAllDatalists() {
    const datalists = [
        { id: 'soleNames', options: soleOptions, defaults: { color: null, subtype: null } },
        { id: 'materialNames', options: materialOptions, defaults: { color: null, unit: null, quantity: null, qty_per_unit: null, price: null } }
    ];

    datalists.forEach(({ id, options, defaults }) => {
        const datalist = document.getElementById(id);
        if (datalist) {
            updateDatalist(id, options, defaults);
        } else {
            console.warn(`Datalist with ID ${id} not found`);
        }
    });

    const soleDatalists = document.querySelectorAll('datalist[id^="soleNames_"]');
    soleDatalists.forEach(datalist => {
        updateDatalist(datalist.id, soleOptions, { color: null, subtype: null });
    });
}



// Centralized function to handle all datalist inputs
function setupDatalistHandler(input, type) {
    input.removeEventListener('input', handleDatalistInput);
    input.addEventListener('input', function(e) {
        handleDatalistInput(e, type);
    });
}

function handleDatalistInput(e, type) {
    const input = e.target;
    const value = input.value.trim();
    const addNewText = `Add New ${type.charAt(0).toUpperCase() + type.slice(1)}...`;

    // Handle "Add New"
    if (value === addNewText || value.includes('Add New')) {
        e.preventDefault();
        openModal(type);
        input.value = '';
        return false;
    }

    const datalist = input.list;
    if (!datalist) return;

    const options = datalist.querySelectorAll('option');

    options.forEach(option => {
        if (option.value.trim() === value) {
            switch (type) {
                case 'material':
                    document.getElementById('material_color').value = option.getAttribute('data-color') || '';
                    document.getElementById('material_unit').value = option.getAttribute('data-unit') || '';
                    document.getElementById('material_quantity').value = option.getAttribute('data-quantity') || '';
                    break;

                case 'liquid':
                    document.getElementById('liquid_material_unit').value = option.getAttribute('data-unit') || '';
                    document.getElementById('liquid_material_quantity').value = option.getAttribute('data-quantity') || '';
                    break;

                case 'sole':
                    // ✅ Ensure we get the correct row that this input belongs to
                    const row = input.closest('.grid');
                    if (row) {
                        const colorInput = row.querySelector('input[name="sole_color[]"]');
                        const subtypeInput = row.querySelector('input[name="sole_sub_type[]"]');
                        const priceInput = row.querySelector('input[name="sole_price[]"]');

                        if (colorInput) colorInput.value = option.getAttribute('data-color') || '';
                        if (subtypeInput) subtypeInput.value = option.getAttribute('data-subtype') || '';
                        // ✅ Autofill price—always use data-price (defaults to 0 if missing)
                        if (priceInput) {
                            const price = option.getAttribute('data-price');
                            priceInput.value = (price !== null && price !== undefined && price !== '') ? parseFloat(price) : '';
                        }
                    }
                    break;
            }
        }
    });
}

// Open modal for adding or editing items
// Open modal
function openModal(type, editIndex = null, rowData = null) {
    const modal = document.getElementById('addNewItemModal');
    const title = document.getElementById('modalTitle');
    const form = document.getElementById('addNewItemForm');
    const editIndexInput = document.getElementById('edit_index');
    const typeInput = document.getElementById('modal_type_input');

    // Reset form
    form.reset();
    editIndexInput.value = editIndex ?? '';
    typeInput.value = type;

    // Hide all sections
    ['soleContent', 'materialContent', 'liquidContent', 'materialPerUnitLength', 'liquidPerUnitLength']
        .forEach(id => document.getElementById(id).style.display = 'none');

    switch (type) {
        case 'sole':
            title.textContent = editIndex ? 'Edit Sole' : 'Add New Sole';
            const soleContent = document.getElementById('soleContent');
            soleContent.style.display = 'block';

            if (rowData) {
                const keys = ['name', 'color', 'sole_type', 'price', 'qty_per_unit'];
                keys.forEach(key => {
                    const el = document.getElementById(`modal_sole_${key}`);
                    if (el) el.value = rowData[key] ?? '';
                });
            }
            break;

        case 'material':
            title.textContent = editIndex ? 'Edit Material' : 'Add New Material';
            const materialContent = document.getElementById('materialContent');
            materialContent.style.display = 'block';

            if (rowData) {
                document.getElementById('modal_material_name').value = rowData.material_name ?? '';
                document.getElementById('modal_material_color').value = rowData.material_color ?? '';
                document.getElementById('modal_material_unit').value = rowData.material_unit ?? '';
                document.getElementById('modal_material_quantity').value = rowData.material_quantity ?? '';
                document.getElementById('modal_material_price').value = rowData.material_price ?? '';
                document.getElementById('modal_material_per_unit_length').value = rowData.material_per_unit_length ?? '';
                togglePerUnitLength(rowData.material_unit, 'material');
            }
            document.getElementById('modal_material_unit').onchange = e => togglePerUnitLength(e.target.value, 'material');
            break;

        case 'liquid':
            title.textContent = editIndex ? 'Edit Liquid Material' : 'Add New Liquid Material';
            const liquidContent = document.getElementById('liquidContent');
            liquidContent.style.display = 'block';

            if (rowData) {
                document.getElementById('modal_liquid_name').value = rowData.liquid_name ?? '';
                document.getElementById('modal_liquid_unit').value = rowData.liquid_unit ?? '';
                document.getElementById('modal_liquid_quantity').value = rowData.liquid_quantity ?? '';
                document.getElementById('modal_liquid_price').value = rowData.liquid_price ?? '';
                document.getElementById('modal_liquid_per_unit_volume').value = rowData.liquid_per_unit_volume ?? '';
                togglePerUnitLength(rowData.liquid_unit, 'liquid');
            }
            document.getElementById('modal_liquid_unit').onchange = e => togglePerUnitLength(e.target.value, 'liquid');
            break;
    }

    // Show modal
    modal.style.display = 'flex';
    setTimeout(() => {
        const firstInput = document.querySelector(`#${type}Content input[required]:first-of-type`);
        if (firstInput) firstInput.focus();
    }, 100);
}


// Handle material unit change
function handleMaterialUnitChange(e) {
    const unit = e.target.value;
    const perUnitField = document.getElementById('materialPerUnitLength');
    if (unit === 'piece') {
        perUnitField.style.display = 'block';
        document.getElementById('modal_material_per_unit_length').required = true;
    } else {
        perUnitField.style.display = 'none';
        document.getElementById('modal_material_per_unit_length').required = false;
    }
}

// Handle liquid unit change
function handleLiquidUnitChange(e) {
    const unit = e.target.value;
    const perUnitField = document.getElementById('liquidPerUnitLength');
    if (unit === 'piece') {
        perUnitField.style.display = 'block';
        document.getElementById('modal_liquid_per_unit_length').required = true;
    } else {
        perUnitField.style.display = 'none';
        document.getElementById('modal_liquid_per_unit_length').required = false;
    }
}

// Close modal
// Close modal
function closeModal() {
    const modal = document.getElementById('addNewItemModal');
    const form = document.getElementById('addNewItemForm');
    form.reset();
    form.classList.remove('was-validated');
    document.querySelectorAll('#addNewItemModal .is-invalid, #addNewItemModal .is-valid').forEach(el => el.classList.remove('is-invalid', 'is-valid'));
    modal.style.display = 'none';
    modal.classList.add('hidden');
}

// Modal form submission
// Unified Form Submission
// Modal form submission
document.getElementById('addNewItemForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const type = document.getElementById('modal_type_input').value;
    const editIndex = document.getElementById('edit_index').value;
    const submitBtn = document.getElementById('modalSubmitBtn');

    const getElValue = id => {
        const el = document.getElementById(id);
        return el ? el.value.trim() : '';
    };

    // Validate sole
    if (type === 'sole') {
        let isValid = true;
        ['modal_sole_name', 'modal_sole_price'].forEach(id => {
            const el = document.getElementById(id);
            if (!el.value.trim()) {
                el.classList.add('is-invalid');
                isValid = false;
            } else {
                el.classList.remove('is-invalid');
            }
        });
        if (!isValid) {
            alert('Please fill all required sole fields (Name and Price).');
            return;
        }
    }

    // Validate material
    if (type === 'material') {
        const name = getElValue('modal_material_name');
        const unit = getElValue('modal_material_unit');
        const quantity = getElValue('modal_material_quantity');
        const perUnitLength = getElValue('modal_material_per_unit_length');

        if (!name || !unit || !quantity) {
            ['modal_material_name', 'modal_material_unit', 'modal_material_quantity'].forEach(id => {
                const el = document.getElementById(id);
                if (!el.value.trim()) el.classList.add('is-invalid');
                else el.classList.remove('is-invalid');
            });
            alert('Please fill all required material fields (Name, Unit, Quantity).');
            return;
        }

        if (unit === 'piece' && (!perUnitLength || Number(perUnitLength) <= 0)) {
            const perUnitInput = document.getElementById('modal_material_per_unit_length');
            perUnitInput.classList.add('is-invalid');
            alert('Per Unit Length must be a positive number when Unit is "Piece".');
            return;
        } else {
            document.getElementById('modal_material_per_unit_length').classList.remove('is-invalid');
        }
    }

    // Prepare FormData
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('type', type);

    if (type === 'sole') {
        formData.set('name', getElValue('modal_sole_name'));
        formData.set('color', getElValue('modal_sole_color'));
        formData.set('price', Number(getElValue('modal_sole_price')) || 0);
        const soleType = getElValue('modal_sole_type');
        if (soleType) formData.set('sole_type', soleType);
    }

    if (type === 'material') {
        formData.set('name', getElValue('modal_material_name'));
        formData.set('unit', getElValue('modal_material_unit'));
        formData.set('quantity', getElValue('modal_material_quantity'));
        formData.set('color', getElValue('modal_material_color'));
        formData.set('price', Number(getElValue('modal_material_price')) || 0);
        const perUnitLength = getElValue('modal_material_per_unit_length');
        if (perUnitLength && Number(perUnitLength) > 0) {
            formData.set('per_unit_length', perUnitLength);
        }
    }

    submitBtn.disabled = true;
    submitBtn.textContent = editIndex ? 'Updating...' : 'Adding...';

    try {
        const response = await fetch('{{ route("products.add-item") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const data = await response.json();
        console.log('Server response:', data);

        if (response.ok && data.success && data.data) {
            alert(`${type.charAt(0).toUpperCase() + type.slice(1)} ${editIndex ? 'updated' : 'added'} successfully!`);

            const item = data.data;

            if (type === 'sole') {
                const newSole = {
                    value: item.name || '',
                    color: item.color || null,
                    subtype: item.sole_type || null,
                    price: Number(item.price) || null
                };
                if (editIndex !== '') {
                    const index = soleOptions.findIndex(opt => opt.value === newSole.value);
                    if (index !== -1) soleOptions[index] = newSole;
                    else soleOptions.push(newSole);
                } else {
                    soleOptions.push(newSole);
                }
                updateAllDatalists();
            } else if (type === 'material') {
                if (!item.name || !item.unit || !item.quantity) {
                    throw new Error('Invalid server response: Missing required material fields (name, unit, quantity).');
                }
                const newMaterial = {
                    id: item.id || null,
                    name: item.name,
                    value: item.name,
                    unit: item.unit,
                    quantity: Number(item.quantity) || 0,
                    color: item.color || null,
                    qty_per_unit: Number(item.per_unit_length) || null,
                    price: Number(item.price) || null
                };
                console.log('newMaterial:', newMaterial);
                if (editIndex !== '') {
                    const index = materialOptions.findIndex(opt => opt.id === newMaterial.id);
                    if (index !== -1) materialOptions[index] = newMaterial;
                    else materialOptions.push(newMaterial);
                } else {
                    materialOptions.push(newMaterial);
                }
                updateAllDatalists();
                try {
                    console.log('Calling addMaterialRow with:', newMaterial);
                    addMaterialRow(true, newMaterial);
                } catch (err) {
                    console.error('Error adding material row:', err);
                    throw err;
                }
            }

            closeModal();
        } else {
            console.error('Server error:', data.errors || data.message);
            alert(`Failed to ${editIndex ? 'update' : 'add'} ${type}: ${data.message || data.errors?.join(', ') || 'Check console for details.'}`);
        }
    } catch (err) {
        console.error('Request failed:', err);
        alert('Something went wrong while submitting. Please try again.');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = editIndex ? 'Update Item' : 'Add Item';
    }
});




// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const initialSoleInput = document.getElementById('sole_name_0');
    if (initialSoleInput) {
        setupDatalistHandler(initialSoleInput, 'sole');
    }
    
    const materialInput = document.getElementById('material_name');
    if (materialInput) {
        setupDatalistHandler(materialInput, 'material');
    }
    
    const liquidInput = document.getElementById('liquid_material_name');
    if (liquidInput) {
        setupDatalistHandler(liquidInput, 'liquid');
    }
    
    updateAllDatalists();
    calculateCosting();
});

// Add Product Row
function addRow(){
    const tbody = document.getElementById('productRows');
    const rowIndex = tbody.children.length;

    let sizesHTML = '<div class="grid grid-cols-5 gap-2">';
    for(let size = 35; size <= 44; size++){
        sizesHTML += `
            <label class="flex items-center space-x-1 text-sm bg-gray-100 px-2 py-1 rounded">
                <input type="checkbox" name="size[${rowIndex}][]" value="${size}" checked class="rounded">
                <span>${size}</span>
            </label>
        `;
    }
    sizesHTML += '</div>';

    const tr = document.createElement('tr');
    tr.className = "hover:bg-gray-50";
    tr.innerHTML = `
        <td class="border px-4 py-3 text-center font-medium">${rowIndex + 1}</td>
        <td class="border px-4 py-3">
            <input type="text" name="color[]" 
                class="w-full border-gray-300 rounded-lg px-2 py-1 focus:ring-2 focus:ring-indigo-500" 
                placeholder="Color" required>
        </td>
        <td class="border px-4 py-3">${sizesHTML}</td>
        
        
        <td class="border px-4 py-3">
            6402
        </td>
        <td class="border px-4 py-3">
            <div class="image-fields space-y-2">
                <input type="file" name="images[${rowIndex}][]" 
                    accept="image/*" 
                    class="w-full border-gray-300 rounded-lg px-2 py-1">
            </div>
            <button type="button" onclick="addImageField(this)" 
                class="mt-2 px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm">
                + Add Image
            </button>
        </td>
        <td class="border px-4 py-3 text-center">
            <button type="button" onclick="removeRow(this)" 
                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                Delete
            </button>
        </td>
    `;
    tbody.appendChild(tr);
}

// Remove Row
function removeRow(button){ 
    button.closest('tr').remove(); 
    calculateCosting(); 
}

// Add Sole Row
function addSoleRow() {
    const container = document.getElementById('additionalSoles');
    const div = document.createElement('div');
    div.classList.add('grid', 'grid-cols-1', 'md:grid-cols-4', 'gap-4');
    const index = container.children.length;

    const datalistId = 'soleNames_' + index;

    div.innerHTML = `
        <div>
            <label class="block text-sm font-semibold text-gray-700">Sole Name / Article No</label>
            <input list="${datalistId}" type="text" id="sole_name_${index}" name="sole_name_or_article_no[]" 
                   placeholder="Enter Sole Name or Article No" 
                   class="mt-1 block w-full border-gray-300 rounded-lg px-2 py-1">
            <datalist id="${datalistId}">
                <!-- Options will be populated dynamically -->
            </datalist>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700">Sole Color</label>
            <input type="text" name="sole_color[]" id="sole_color_${index}" 
                   placeholder="Sole Color" 
                   class="mt-1 block w-full border-gray-300 rounded-lg px-2 py-1">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700">Sole Sub Type</label>
            <input type="text" name="sole_sub_type[]" id="sole_sub_type_${index}" 
                   placeholder="Sole Subtype" 
                   class="mt-1 block w-full border-gray-300 rounded-lg px-2 py-1">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700">Sole Price</label>
            <input type="number" name="sole_price[]" id="sole_price_${index}" 
                   placeholder="Sole Price" min="0" step="0.01"
                   class="mt-1 block w-full border-gray-300 rounded-lg px-2 py-1">
        </div>
        <button type="button" 
                onclick="this.parentElement.remove()" 
                class="col-span-4 mt-2 px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
            Delete
        </button>
    `;

    container.appendChild(div);

    // Populate datalist dynamically
    updateDatalist(datalistId, soleOptions, { color: null, subtype: null });

    // Enable auto-fill for new row
    const newSoleInput = div.querySelector('#sole_name_' + index);
    if (newSoleInput) {
        setupDatalistHandler(newSoleInput, 'sole');
    }
}


// Add Image Field
function addImageField(button){
    const container = button.parentElement.querySelector('.image-fields');
    const index = Array.from(document.getElementById('productRows').children).indexOf(button.closest('tr'));
    const input = document.createElement('input');
    input.type = 'file';
    input.name = 'images[' + index + '][]';
    input.accept = 'image/*';
    input.className = 'w-full border-gray-300 rounded-lg px-2 py-1';
    container.appendChild(input);
}

// Add Process
function addProcess(){
    const tbody = document.getElementById('processContainer');
    const index = tbody.children.length + 1;
    const processId = generateProcessId();
    const tr = document.createElement('tr');
    tr.innerHTML = `
      
        <td class="border px-4 py-2"><input type="text" name="process_flow[]" placeholder="Process Name" class="w-full border-gray-300 rounded-lg px-2 py-1 text-center"></td>
        <td class="border px-4 py-2"><input type="number" name="process_qty[]" placeholder="Qty" class="w-full border-gray-300 rounded-lg px-2 py-1 text-center" min="0"></td>
        <td class="border px-4 py-2"><input type="number" name="labor_rate[]" placeholder="₹" class="w-full border-gray-300 rounded-lg px-2 py-1 text-center" step="0.01"></td>
        
        <td class="border px-4 py-2 text-center"><button type="button" onclick="this.closest('tr').remove(); calculateCosting()" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete</button></td>`;
    tbody.appendChild(tr);
    calculateCosting();
}

// Edit Material Row
function editMaterialRow(index) {
    const tbody = document.getElementById('materialTable');
    const row = tbody.children[index];

    // Skip if already in edit mode
    if (row.classList.contains('editing')) return;

    row.classList.add('editing');

    // Get existing data
    const nameCell = row.children[1];
    const colorCell = row.children[2];
    const unitCell = row.children[3];
    const qtyCell = row.children[4];
    const actionCell = row.children[5];

    const name = nameCell.querySelector('input')?.value || '';
    const color = colorCell.querySelector('input')?.value || '';
    const unit = unitCell.querySelector('input')?.value || '';
    const quantity = qtyCell.querySelector('input')?.value || '';

    // Convert cells to editable inputs
    nameCell.innerHTML = `<input type="text" class="w-full border border-gray-300 rounded px-1" value="${name}" name="material_name[]">`;
    colorCell.innerHTML = `<input type="text" class="w-full border border-gray-300 rounded px-1" value="${color}" name="material_color[]">`;
    unitCell.innerHTML = `<input type="text" class="w-full border border-gray-300 rounded px-1" value="${unit}" name="material_unit[]">`;
    qtyCell.innerHTML = `<input type="number" step="0.01" min="0" class="w-full border border-gray-300 rounded px-1" value="${quantity}" name="material_quantity[]">`;

    // Replace Edit/Delete with Save/Cancel
    actionCell.innerHTML = `
        <button type="button" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 mr-1"
                onclick="saveMaterialRow(${index})">Save</button>
        <button type="button" class="bg-gray-400 text-white px-2 py-1 rounded hover:bg-gray-500"
                onclick="cancelEditMaterialRow(${index})">Cancel</button>
    `;
}

// Edit Liquid Material Row
function editLiquidMaterialRow(index) {
    const tbody = document.getElementById('liquidMaterialTable');
    const row = tbody.children[index];
    const rowData = {
        name: row.querySelector('input[name="liquid_material_name[]"]').value,
        unit: row.querySelector('input[name="liquid_material_unit[]"]').value,
        quantity: row.querySelector('input[name="liquid_material_quantity[]"]').value
    };
    openModal('liquid', index, rowData);
}


function saveMaterialRow(index) {
    const tbody = document.getElementById('materialTable');
    const row = tbody.children[index];
    const inputs = row.querySelectorAll('input[name^="material_"]');

    const name = inputs[0].value.trim();
    const color = inputs[1].value.trim();
    const unit = inputs[2].value.trim();
    const quantity = inputs[3].value.trim();

    if (!name || !unit || !quantity) {
        alert('Please fill required fields (Name, Unit, Quantity)');
        return;
    }

    // Update data
    row.innerHTML = `
        <td class="border px-2 py-1">${index + 1}</td>
        <td class="border px-2 py-1">${name}<input type="hidden" name="material_name[]" value="${name}"></td>
        <td class="border px-2 py-1">${color}<input type="hidden" name="material_color[]" value="${color}"></td>
        <td class="border px-2 py-1">${unit}<input type="hidden" name="material_unit[]" value="${unit}"></td>
        <td class="border px-2 py-1">${quantity}<input type="hidden" name="material_quantity[]" value="${quantity}"></td>
        <td class="border px-2 py-1 text-center">
            <button type="button" onclick="editMaterialRow(${index})" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">Edit</button>
            <button type="button" onclick="this.parentElement.parentElement.remove(); calculateCosting()" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</button>
        </td>
    `;
    calculateCosting();
}

function cancelEditMaterialRow(index) {
    const tbody = document.getElementById('materialTable');
    const row = tbody.children[index];
    const hiddenInputs = row.querySelectorAll('input[type="hidden"]');
    const name = hiddenInputs[0]?.value || '';
    const color = hiddenInputs[1]?.value || '';
    const unit = hiddenInputs[2]?.value || '';
    const quantity = hiddenInputs[3]?.value || '';

    row.innerHTML = `
        <td class="border px-2 py-1">${index + 1}</td>
        <td class="border px-2 py-1">${name}<input type="hidden" name="material_name[]" value="${name}"></td>
        <td class="border px-2 py-1">${color}<input type="hidden" name="material_color[]" value="${color}"></td>
        <td class="border px-2 py-1">${unit}<input type="hidden" name="material_unit[]" value="${unit}"></td>
        <td class="border px-2 py-1">${quantity}<input type="hidden" name="material_quantity[]" value="${quantity}"></td>
        <td class="border px-2 py-1 text-center">
            <button type="button" onclick="editMaterialRow(${index})" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">Edit</button>
            <button type="button" onclick="this.parentElement.parentElement.remove(); calculateCosting()" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</button>
        </td>
    `;
}





// Add Material Row
// Add Material Row
// Add Material Row
function addMaterialRow(fromModal = false, newItem = null) {
    let name, color, unit, quantity;

    const escapeHTML = str => {
        if (!str) return '';
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    };

    if (fromModal && newItem) {
        console.log('newItem received:', newItem);
        if (!newItem.name || !newItem.unit || !newItem.quantity) {
            console.error('Invalid material data:', newItem);
            throw new Error('Invalid material data: Missing required fields (name, unit, quantity).');
        }
        name = escapeHTML(newItem.name);
        color = escapeHTML(newItem.color || '');
        unit = escapeHTML(newItem.unit);
        quantity = escapeHTML(String(newItem.quantity));
    } else {
        name = document.getElementById('material_name')?.value.trim() || '';
        color = document.getElementById('material_color')?.value.trim() || '';
        unit = document.getElementById('material_unit')?.value.trim() || '';
        quantity = document.getElementById('material_quantity')?.value.trim() || '';
        
        if (!name || !unit || !quantity) {
            console.error('Non-modal material input validation failed:', { name, unit, quantity });
            alert('Please fill all material fields including quantity');
            return;
        }
        name = escapeHTML(name);
        color = escapeHTML(color);
        unit = escapeHTML(unit);
        quantity = escapeHTML(quantity);
    }
    
    console.log('Adding material row with:', { name, color, unit, quantity });
    
    materialCount++;
    const tbody = document.getElementById('materialTable');
    if (!tbody) {
        console.error('Material table not found');
        throw new Error('Material table element not found');
    }
    
    const tr = document.createElement('tr');
    try {
        tr.innerHTML = `
            <td class="border px-2 py-1">${materialCount}</td>
            <td class="border px-2 py-1">${name}<input type="hidden" name="material_name[]" value="${name}"></td>
            <td class="border px-2 py-1">${color}<input type="hidden" name="material_color[]" value="${color}"></td>
            <td class="border px-2 py-1">${unit}<input type="hidden" name="material_unit[]" value="${unit}"></td>
            <td class="border px-2 py-1">${quantity}<input type="hidden" name="material_quantity[]" value="${quantity}"></td>
            <td class="border px-2 py-1 text-center">
                <button type="button" onclick="editMaterialRow(${materialCount - 1})" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">Edit</button>
                <button type="button" onclick="this.parentElement.parentElement.remove(); calculateCosting()" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</button>
            </td>`;
        tbody.appendChild(tr);
    } catch (err) {
        console.error('Error creating table row:', err);
        throw err;
    }
    
    if (!fromModal) {
        const materialInput = document.getElementById('material_name');
        if (materialInput) {
            setupDatalistHandler(materialInput, 'material');
        }
        
        ['material_name', 'material_color', 'material_unit', 'material_quantity'].forEach(id => {
            const input = document.getElementById(id);
            if (input) input.value = '';
        });
    }
    
    try {
        calculateCosting();
    } catch (err) {
        console.error('Error in calculateCosting:', err);
        throw new Error('Failed to calculate costing: ' + err.message);
    }
}

// Add Liquid Material Row
function addLiquidMaterialRow(fromModal = false, newItem = null) {
    let name, unit, quantity;
    
    if (fromModal && newItem) {
        name = newItem.name;
        unit = newItem.unit || '';
        quantity = newItem.quantity || '';
    } else {
        name = document.getElementById('liquid_material_name').value.trim();
        unit = document.getElementById('liquid_material_unit').value.trim();
        quantity = document.getElementById('liquid_material_quantity').value.trim();
        
        if (!name || !unit || !quantity) {
            alert('Please fill all liquid material fields including quantity');
            return;
        }
    }
    
    liquidMaterialCount++;
    const tbody = document.getElementById('liquidMaterialTable');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td class="border px-2 py-1">${liquidMaterialCount}</td>
        <td class="border px-2 py-1">${name}<input type="hidden" name="liquid_material_name[]" value="${name}"></td>
        <td class="border px-2 py-1">${unit}<input type="hidden" name="liquid_material_unit[]" value="${unit}"></td>
        <td class="border px-2 py-1">${quantity}<input type="hidden" name="liquid_material_quantity[]" value="${quantity}"></td>
        <td class="border px-2 py-1 text-center">
            <button type="button" onclick="editLiquidMaterialRow(${liquidMaterialCount - 1})" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">Edit</button>
            <button type="button" onclick="this.parentElement.parentElement.remove(); calculateCosting()" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</button>
        </td>`;
    tbody.appendChild(tr);
    
    if (!fromModal) {
        const liquidInput = document.getElementById('liquid_material_name');
        if (liquidInput) {
            setupDatalistHandler(liquidInput, 'liquid');
        }
        
        document.getElementById('liquid_material_name').value = '';
        document.getElementById('liquid_material_unit').value = '';
        document.getElementById('liquid_material_quantity').value = '';
    }
    
    calculateCosting();
}

// Calculate Costing
function calculateCosting() {
    let totalProductPrice = 0;
    document.querySelectorAll('#productRows tr').forEach(function(row) {
        const priceInput = row.querySelector('input[name="price[]"]');
        const price = priceInput ? parseFloat(priceInput.value) || 0 : 0;
        totalProductPrice += price;
    });

    let totalMaterialCost = 0;
    document.querySelectorAll('#materialTable tr').forEach(function(row, index) {
        const quantityInput = row.querySelector('input[name="material_quantity[]"]');
        const nameInput = row.querySelector('input[name="material_name[]"]');
        if (!quantityInput || !nameInput) {
            console.warn(`Missing inputs in material row ${index}:`, { quantityInput, nameInput });
            return;
        }
        const quantity = parseFloat(quantityInput.value) || 0;
        const material = materialOptions.find(opt => opt.name === nameInput.value);
        const unitPrice = material && material.price ? parseFloat(material.price) : 0;
        totalMaterialCost += unitPrice * quantity;
    });

    let totalProcessCost = 0;
    document.querySelectorAll('#processContainer tr').forEach(function(row) {
        const qtyInput = row.querySelector('input[name="process_qty[]"]');
        const priceInput = row.querySelector('input[name="labor_rate[]"]');
        const qty = qtyInput ? parseFloat(qtyInput.value) || 0 : 0;
        const price = priceInput ? parseFloat(priceInput.value) || 0 : 0;
        totalProcessCost += qty * price;
    });

    const productionCost = totalMaterialCost + totalProcessCost + (totalProductPrice * 0.7);
    const profit = totalProductPrice - productionCost;

    const productionCostElement = document.getElementById('production_cost');
    const profitElement = document.getElementById('profit');
    if (productionCostElement) {
        productionCostElement.value = productionCost.toFixed(2);
    } else {
        console.warn('Production cost element not found');
    }
    if (profitElement) {
        profitElement.value = profit.toFixed(2);
    } else {
        console.warn('Profit element not found');
    }
}
// Recalculate on input
document.addEventListener('input', function(e){
    const watchedInputs = [
        'price[]', 
        'material_unit[]', 
        'material_quantity[]', 
        'liquid_material_unit[]', 
        'liquid_material_quantity[]',
        'process_qty[]', 
        'labor_rate[]'
    ];
    if(watchedInputs.includes(e.target.name)) {
        calculateCosting();
    }
});

// Event delegation for datalist "Add New" options
document.addEventListener('input', function(e) {
    if (e.target.matches('input[list]')) {
        const datalist = e.target.list;
        if (datalist) {
            const options = datalist.querySelectorAll('option');
            const value = e.target.value.trim();
            
            options.forEach(option => {
                if (option.value === value && option.value.includes('Add New')) {
                    e.preventDefault();
                    const type = option.value.includes('Sole') ? 'sole' : 
                                option.value.includes('Material') ? 'material' : 'liquid';
                    openModal(type);
                    e.target.value = '';
                }
            });
        }
    }
});
</script>

@if(config('broadcasting.connections.pusher.key') && config('broadcasting.connections.pusher.key') !== null)
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    if (typeof Pusher !== 'undefined' && '{{ config("broadcasting.connections.pusher.key") }}') {
        window.Pusher = new Pusher('{{ config("broadcasting.connections.pusher.key") }}', {
            cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
            encrypted: true
        });
    }
</script>
@endif
@endsection