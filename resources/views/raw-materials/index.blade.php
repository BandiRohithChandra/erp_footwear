@extends('layouts.app')

@section('content')
    <div x-data="rawMaterials()" class="max-w-7xl mx-auto p-6 bg-white shadow-lg rounded-xl">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Raw Materials</h2>

        <!-- Tabs -->
        <ul class="flex border-b mb-6 justify-center space-x-2">
            <li><button class="tab-btn bg-gray-200 py-2 px-6 rounded-t-lg font-semibold text-gray-700"
                    @click="openTab($event,'sole')">Sole Details</button></li>
            <li><button class="tab-btn bg-gray-200 py-2 px-6 rounded-t-lg font-semibold text-gray-700"
                    @click="openTab($event,'materials')">Material Details</button></li>
            <!-- <li><button class="tab-btn bg-gray-200 py-2 px-6 rounded-t-lg font-semibold text-gray-700" @click="openTab($event,'liquids')">Liquid Materials</button></li> -->
        </ul>

        <!-- Sole Details Tab -->
        <div id="sole" class="tab-content p-4">

            <!-- Subtabs -->
            <ul class="flex border-b mb-4 space-x-2">
                <li>
                    <button class="tab-btn px-6 py-2 font-semibold rounded-t-lg"
                        :class="activeSoleSubTab==='details' ? 'bg-white border-t border-l border-r' : 'bg-gray-200'"
                        @click="activeSoleSubTab='details'">Sole Details</button>
                </li>
                <li>
                    <button class="tab-btn px-6 py-2 font-semibold rounded-t-lg"
                        :class="activeSoleSubTab==='stockArrival' ? 'bg-white border-t border-l border-r' : 'bg-gray-200'"
                        @click="activeSoleSubTab='stockArrival'">Stock Arrival</button>
                </li>
            </ul>

            <!-- Sole Details Tab -->
            <div x-show="activeSoleSubTab==='details'" class="bg-white p-4 rounded shadow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold">Sole Details</h3>
                    <button @click="openModal('Sole', null)"
                        class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                        Add New Sole
                    </button>
                </div>

                <div class="overflow-x-auto">





                    <table class="min-w-full border border-gray-300 rounded-lg text-left">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-4 py-2 border">Sole Name</th>
                                <th class="px-4 py-2 border">Color</th>
                                <th class="px-4 py-2 border">Type</th>
                                <th class="px-4 py-2 border">Sizes</th>
                                <th class="px-4 py-2 border">Total Qty</th>
                                <th class="px-4 py-2 border">Total Amount</th>
                                <th class="px-4 py-2 border">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="product in products" :key="product.id">
                                <template x-for="sole in product.soles_list" :key="sole.id">
                                    <tr class="hover:bg-gray-50">
                                        <td x-text="sole.name || 'N/A'" class="px-4 py-2 border"></td>
                                        <td x-text="sole.color || 'N/A'" class="px-4 py-2 border"></td>
                                        <td x-text="sole.sole_type || 'N/A'" class="px-4 py-2 border"></td>

                                        <td class="px-4 py-2 border">
                                            <button @click="sole.showSizes = !sole.showSizes"
                                                class="px-2 py-1 bg-blue-100 rounded text-sm font-medium mb-1 hover:bg-blue-200">
                                                View Sizes <span x-text="sole.showSizes ? '▲' : '▼'"></span>
                                            </button>



                                            <table x-show="sole.showSizes"
                                                class="min-w-full border border-gray-200 text-sm mt-1">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-2 py-1 border">Size</th>
                                                        <th class="px-2 py-1 border">Qty Available</th>
                                                        <th class="px-2 py-1 border">In-Transit</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template x-for="[size, obj] in Object.entries(sole.sizes_qty || {})"
                                                        :key="size">
                                                        <tr>
                                                            <td class="px-2 py-1 border" x-text="size"></td>
                                                            <td class="px-2 py-1 border"
                                                                :class="obj.qty_available < 0 ? 'text-red-600 font-bold' : ''"
                                                                x-text="obj.qty_available"></td>
                                                            <td class="px-2 py-1 border" x-text="obj.in_transit || 0"></td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </td>


                                        <td class="px-4 py-2 font-medium"
                                            x-text="Object.values(sole.sizes_qty || {}).reduce((a,b)=>a+((b.qty_available||0)+(b.in_transit||0)),0)">
                                        </td>
                                        <td class="px-4 py-2" x-text="sole.total_price.toFixed(2)"></td>



                                        <td class="px-4 py-2">
                                            <div class="flex gap-1">
                                                <button @click="editItem(sole, 'Sole', product.id)"
                                                    class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">Edit</button>
                                                <button @click="deleteItem(sole.id, 'Sole', product.id)"
                                                    class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="product.soles_list.length===0">
                                    <td colspan="7" class="px-4 py-2 text-center text-gray-500">No soles available</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Stock Arrival Tab -->
            <div x-show="activeSoleSubTab==='stockArrival'" class="bg-white p-4 rounded shadow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold">Stock Arrival / Received</h3>
                    <a href="{{ route('supplier-orders.create') }}"
    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
    Add Stock Arrival
</a>

                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300 rounded-lg text-left text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border">Sole Name</th>
                                <th class="px-4 py-2 border">Color</th>
                                <th class="px-4 py-2 border">Size</th>
                                <th class="px-4 py-2 border">Qty Received</th>
                                <th class="px-4 py-2 border">Supplier</th>
                                <th class="px-4 py-2 border">Reason</th>
                                <th class="px-4 py-2 border">Received On</th>
                                <th class="px-4 py-2 border">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="record in stockArrivalRecords" :key="record.id">
                                <tr>
                                    <td x-text="record.name"></td>
                                    <td x-text="record.color"></td>
                                    <td x-text="record.size"></td>
                                    <td x-text="record.qty"></td>
                                    <!-- Updated to show supplier name instead of party -->
                                    <td x-text="record.supplier?.name || 'N/A'"></td>
                                    <td x-text="record.reason || 'N/A'"></td>
                                    <td x-text="record.received_at || 'Pending'"
                                        :class="record.received_at ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'">
                                    </td>
                                    <td>
                                        <button @click="markAsReceived(record)"
                                            class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm"
                                            x-show="!record.received_at">
                                            Received
                                        </button>
                                        <span x-show="record.received_at"
                                            class="text-green-600 font-semibold text-sm">Received</span>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="stockArrivalRecords.length===0">
                                <td colspan="8" class="px-4 py-2 text-center text-gray-500">No stock arrivals yet</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>



        <!-- Material Details Tab -->
        <div id="materials" class="tab-content hidden">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Material Details</h3>
                <button type="button" @click="openModal('Material')"
                    class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">Add New Material</button>
            </div>

            <!-- Debug: show products object -->
            <!-- <pre x-text="JSON.stringify(products, null, 2)" class="mb-4 p-2 bg-gray-100 rounded text-sm overflow-x-auto"></pre> -->

            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse border border-gray-300 text-left">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-3 border-b">Material Name</th>
                            <th class="px-6 py-3 border-b">Color</th>
                            <th class="px-6 py-3 border-b">Unit</th>
                            <th class="px-6 py-3 border-b">Qty</th>
                            <th class="px-6 py-3 border-b">Available Qty</th>
                            <th class="px-6 py-3 border-b">Measurement</th>
                            <th class="px-6 py-3 border-b">Price/Unit</th>
                            <th class="px-6 py-3 border-b">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="product in products" :key="product.id ?? 'independent'">
                            <template x-for="material in product.materials_list" :key="material.id ?? material.type">
                                <tr class="hover:bg-gray-50" x-show="material.type === 'Material'">
                                    <td class="px-6 py-3 border-t" x-text="material.name || 'N/A'"></td>
                                    <td class="px-6 py-3 border-t" x-text="material.color || 'N/A'"></td>
                                    <td class="px-6 py-3 border-t" x-text="material.unit || 'unit'"></td>
                                    <td class="px-6 py-3 border-t"
                                        x-text="formatQuantity(material.quantity ?? material.total_quantity ?? material.available_qty, material.unit)">
                                    </td>
                                    <td class="px-6 py-3 border-t"
                                        x-text="formatQuantity(material.available_qty ?? material.total_quantity ?? material.quantity, material.unit)">
                                    </td>
                                    <td class="px-6 py-3 border-t"
                                        x-text="calculateMeasurement(material.quantity ?? material.total_quantity ?? material.available_qty, material.unit, material.per_unit_length)">
                                    </td>
                                    <td class="px-6 py-3 border-t"
                                        x-text="material.per_unit_price_text || calculatePricePerUnit(material.quantity ?? material.total_quantity ?? material.available_qty, material.price ?? 0, material.unit, material.per_unit_length)">
                                    </td>
                                    <td class="px-6 py-3 border-t">
                                        <div class="flex gap-1">
                                            <button @click="editItem(material, 'Material', product.id)"
                                                class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">Edit</button>
                                            <button @click="openRestockModal(material, 'Material', product.id)"
                                                class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm">Restock</button>
                                            <button @click="deleteItem(material.id, 'Material', product.id)"
                                                class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="product.materials_list.filter(m => m.type === 'Material').length === 0">
                                <td class="px-6 py-3 border-t" colspan="8">No materials available</td>
                            </tr>
                        </template>
                    </tbody>

                </table>
            </div>
        </div>


        <!-- Liquid Materials Tab -->
        <!-- <div id="liquids" class="tab-content hidden">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold">Liquid Materials</h3>
                    <button type="button" @click="step=1;openModal('Liquid Material')" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">Add New Liquid</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse border border-gray-300 text-left">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-6 py-3 border-b">Liquid Name</th>
                                <th class="px-6 py-3 border-b">Unit</th>
                                <th class="px-6 py-3 border-b">Qty</th>
                                <th class="px-6 py-3 border-b">Available Qty</th>
                                <th class="px-6 py-3 border-b">Measurement</th>
                                <th class="px-6 py-3 border-b">Price/Unit</th>
                                <th class="px-6 py-3 border-b">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="product in products" :key="product.id ?? 'independent'">
                                <template x-for="liquid in product.liquids_list" :key="liquid.id">
                                    <tr class="hover:bg-gray-50" x-show="liquid.type === 'Liquid Material'">
                                        <td class="px-6 py-3 border-t" x-text="liquid.name || 'N/A'"></td>
                                        <td class="px-6 py-3 border-t" x-text="liquid.unit || 'N/A'"></td>
                                        <td class="px-6 py-3 border-t" x-text="formatQuantity(liquid.quantity, liquid.unit)"></td>
                                        <td class="px-6 py-3 border-t" x-text="formatQuantity(liquid.available_qty ?? liquid.quantity, liquid.unit)"></td>
                                        <td class="px-6 py-3 border-t" x-text="calculateMeasurement(liquid.quantity, liquid.unit, null, liquid.per_unit_volume)"></td>
                                        <td class="px-6 py-3 border-t" x-text="calculatePricePerUnit(liquid.quantity, liquid.price, liquid.unit, null, liquid.per_unit_volume)"></td>
                                        <td class="px-6 py-3 border-t">
                                            <div class="flex gap-1">
                                                <button @click="editItem(liquid, 'Liquid Material', product.id)" class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">Edit</button>
                                                <button @click="openRestockModal(liquid, 'Liquid Material', product.id)" class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm">Restock</button>
                                                <button @click="deleteItem(liquid.id, 'Liquid Material', product.id)" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="product.liquids_list.filter(l => l.type === 'Liquid Material').length === 0">
                                    <td class="px-6 py-3 border-t" colspan="7">No liquid materials available</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div> -->

        <!-- Add/Edit Modal -->
        <div x-cloak x-show="open_modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-6 relative max-h-[90vh] overflow-y-auto">
                <button @click="closeModal()"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>

                <h2 class="text-2xl font-bold text-blue-600 mb-4"
                    x-text="isEditing ? ('Edit ' + modal_type) : (modal_type==='Sole' ? 'Add New Sole' : (modal_type==='Material' ? 'Add New Material' : 'Add New Liquid Material'))">
                </h2>

                <form @submit.prevent="saveMaterial" class="space-y-4">
                    <input type="hidden" x-model="formData.id">
                    <input type="hidden" x-model="formData.product_id">

                    <!-- Sole Section -->
                    <template x-if="modal_type==='Sole'">
                        <div class="space-y-3">
                            <input type="text" x-model="formData.name" placeholder="Sole Name"
                                class="block w-full p-2 border rounded" required>
                            <input type="text" x-model="formData.color" placeholder="Color"
                                class="block w-full p-2 border rounded" required>
                            <input type="text" x-model="formData.sole_type" placeholder="Sole Type"
                                class="block w-full p-2 border rounded">

                            <div>
                                <label class="block text-gray-700 mb-1 font-semibold">Sizes & Quantity</label>

                                <!-- Size Category Selection -->
                                <div class="mb-3">
                                    <label class="block text-gray-600 text-sm mb-1">Quick Size Category Selection:</label>
                                    <select @change="selectSizeCategory($event)" class="w-full p-2 border rounded mb-2">
                                        <option value="">Individual Sizes</option>
                                        <option value="large">Large (40-44)</option>
                                        <option value="medium">Medium (38-39)</option>
                                        <option value="small">Small (34-37)</option>
                                    </select>

                                    <div class="flex gap-2 mb-2">
                                        <input type="number" placeholder="Quantity for all selected sizes"
                                            class="w-48 p-2 border rounded" @input="setBulkQuantity($event)">
                                        <button type="button" @click="applyBulkQuantity()"
                                            class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                            Apply
                                        </button>
                                    </div>
                                </div>

                                <!-- Individual Size Inputs -->
                                <div class="flex flex-wrap gap-3 overflow-x-auto p-2 bg-gray-50 rounded">
                                    <template x-for="size in Array.from({length:11},(_,i)=>34+i)" :key="size">
                                        <div class="flex flex-col items-center min-w-[60px]" :class="{
                                                     'bg-red-50': size >= 40, // Large
                                                     'bg-yellow-50': size >= 38 && size <= 39, // Medium
                                                     'bg-green-50': size <= 37, // Small
                                                 }">
                                            <span class="text-sm font-medium" x-text="size"></span>
                                            <input type="number" x-model="formData.sizes_qty[size]"
                                                @input="handleNumberInput($event, 'sizes_qty', size)" placeholder="Qty"
                                                class="w-16 p-1 border rounded mt-1 text-center" min="0">
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="mt-2 font-semibold text-gray-700 bg-blue-50 p-2 rounded">
                                Total Quantity:
                                <span class="text-blue-600"
                                    x-text="Object.values(formData.sizes_qty).reduce((a,b)=>Number(a||0)+Number(b||0),0)"></span>
                            </div>

                            <input type="number" x-model="formData.price" @input="handleNumberInput($event, 'price')"
                                placeholder="Price" class="block w-full p-2 border rounded" min="0" step="0.01" required>
                        </div>
                    </template>

                    <!-- Material Section -->
                    <template x-if="modal_type==='Material'">
                        <div class="space-y-3">
                            <input type="text" x-model="formData.name" placeholder="Material Name (e.g., Lace)"
                                class="block w-full p-2 border rounded" required>
                            <input type="text" x-model="formData.color" placeholder="Color"
                                class="block w-full p-2 border rounded">

                            <select x-model="formData.unit" @change="handleUnitChange($event)"
                                class="block w-full p-2 border rounded" required>
                                <option value="" disabled>Select Unit</option>
                                <option value="kg">Kilogram (kg)</option>
                                <option value="g">Gram (g)</option>
                                <option value="metre">Metre (m)</option>
                                <option value="piece">Piece (e.g., Roll)</option>
                            </select>

                            <input type="number" x-model="formData.quantity" @input="handleNumberInput($event, 'quantity')"
                                :placeholder="formData.unit === 'piece' ? 'Quantity (No. of Rolls)' : 'Quantity'"
                                class="block w-full p-2 border rounded" :min="formData.unit === 'g' ? '0.01' : '0'"
                                :step="formData.unit === 'g' ? '0.01' : '0.1'" required>

                            <input type="number" x-model="formData.per_unit_length"
                                @input="handleNumberInput($event, 'per_unit_length')"
                                placeholder="Length per Piece (meters)" class="block w-full p-2 border rounded"
                                x-show="formData.unit === 'piece'" :required="formData.unit === 'piece'" min="0.01"
                                step="0.01">

                            <input type="number" x-model="formData.price" @input="handleNumberInput($event, 'price')"
                                :placeholder="formData.unit === 'piece' ? 'Price per Piece' : 'Price per Unit'"
                                class="block w-full p-2 border rounded" min="0" step="0.01" required>

                            <div class="font-semibold text-gray-700 bg-blue-50 p-2 rounded"
                                x-show="formData.unit === 'piece' && formData.quantity && formData.per_unit_length">
                                Total Length:
                                <span class="text-blue-600"
                                    x-text="calculateMeasurement(formData.quantity, formData.unit, formData.per_unit_length)"></span>
                            </div>

                            <div class="font-semibold text-gray-700 bg-blue-50 p-2 rounded"
                                x-show="formData.quantity && formData.price">
                                Price per Base Unit:
                                <span class="text-blue-600"
                                    x-text="calculatePricePerUnit(formData.quantity, formData.price, formData.unit, formData.per_unit_length)"></span>
                            </div>
                        </div>
                    </template>

                    <!-- Liquid Material Section -->
                    <template x-if="modal_type === 'Liquid Material'">
                        <div class="space-y-3">
                            <input type="text" x-model="formData.name" placeholder="Liquid Name (e.g., Adhesive)"
                                class="block w-full p-2 border rounded" required>

                            <select x-model="formData.unit" @change="handleUnitChange($event)"
                                class="block w-full p-2 border rounded" required>
                                <option value="" disabled>Select Unit</option>
                                <option value="litre">Litre (l)</option>
                                <option value="ml">Millilitre (ml)</option>
                                <option value="kg">Kilogram (kg)</option>
                                <option value="g">Gram (g)</option>
                                <option value="piece">Piece (e.g., Can)</option>
                            </select>

                            <input type="number" x-model="formData.quantity" @input="handleNumberInput($event, 'quantity')"
                                :placeholder="formData.unit === 'piece' ? 'Quantity (No. of Cans)' : 'Quantity'"
                                class="block w-full p-2 border rounded"
                                :min="formData.unit === 'ml' || formData.unit === 'g' ? '0.01' : '0'"
                                :step="formData.unit === 'ml' || formData.unit === 'g' ? '0.01' : '0.1'" required>

                            <input type="number" x-model="formData.per_unit_volume"
                                @input="handleNumberInput($event, 'per_unit_volume')"
                                placeholder="Volume per Piece (liters)" class="block w-full p-2 border rounded"
                                x-show="formData.unit === 'piece'" :required="formData.unit === 'piece'" min="0.01"
                                step="0.01">

                            <input type="number" x-model="formData.price" @input="handleNumberInput($event, 'price')"
                                :placeholder="formData.unit === 'piece' ? 'Price per Piece' : 'Price per Unit'"
                                class="block w-full p-2 border rounded" min="0" step="0.01" required>

                            <div class="font-semibold text-gray-700 bg-blue-50 p-2 rounded"
                                x-show="formData.unit === 'piece' && formData.quantity && formData.per_unit_volume">
                                Total Volume:
                                <span class="text-blue-600"
                                    x-text="calculateMeasurement(formData.quantity, formData.unit, null, formData.per_unit_volume)"></span>
                            </div>

                            <div class="font-semibold text-gray-700 bg-blue-50 p-2 rounded"
                                x-show="formData.quantity && formData.price">
                                Price per Base Unit:
                                <span class="text-blue-600"
                                    x-text="calculatePricePerUnit(formData.quantity, formData.price, formData.unit, null, formData.per_unit_volume)"></span>
                            </div>
                        </div>
                    </template>

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="closeModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:bg-gray-400"
                            x-text="isEditing ? 'Update' : 'Save'" :disabled="!isFormValid()">
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Restock Modal -->
        <div x-cloak x-show="open_restock_modal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-6 relative max-h-[90vh] overflow-y-auto">
                <button @click="closeRestockModal()"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>

                <h2 class="text-2xl font-bold text-green-600 mb-4" x-text="'Restock ' + restockData.type"></h2>

                <form @submit.prevent="restockItem" class="space-y-4">
                    <input type="hidden" x-model="restockData.id">
                    <input type="hidden" x-model="restockData.type">
                    <input type="hidden" x-model="restockData.product_id">

                    <div class="space-y-3">
                        <div>
                            <label class="block text-gray-700 mb-1 font-semibold">Item Name</label>
                            <input type="text" x-model="restockData.name" class="block w-full p-2 border rounded" readonly>
                        </div>

                        <template x-if="restockData.type === 'Sole'">
                            <div>
                                <div>
                                    <label class="block text-gray-700 mb-1 font-semibold">Article No</label>
                                    <input type="text" x-model="restockData.article_no" placeholder="Article No"
                                        class="block w-full p-2 border rounded">
                                </div>
                                <div>
                                    <label class="block text-gray-700 mb-1 font-semibold">Party</label>
                                    <input type="text" x-model="restockData.party" placeholder="Party"
                                        class="block w-full p-2 border rounded">
                                </div>
                                <label class="block text-gray-700 mb-1 font-semibold">Sizes & Quantity to Restock</label>
                                <div class="flex flex-wrap gap-3 overflow-x-auto p-2 bg-gray-50 rounded">
                                    <template x-for="size in Array.from({length:10},(_,i)=>35+i)" :key="size">
                                        <div class="flex flex-col items-center min-w-[60px]">
                                            <span class="text-sm font-medium" x-text="size"></span>
                                            <input type="number" x-model="restockData.sizes_qty[size]"
                                                @input="handleNumberInput($event, 'sizes_qty', size)" placeholder="Qty"
                                                class="w-16 p-1 border rounded mt-1 text-center" min="0">
                                        </div>
                                    </template>
                                </div>
                                <div class="mt-2 font-semibold text-gray-700 bg-blue-50 p-2 rounded">
                                    Total Quantity to Restock: <span class="text-blue-600"
                                        x-text="Object.values(restockData.sizes_qty).reduce((a,b)=>Number(a||0)+Number(b||0),0)"></span>
                                </div>
                            </div>
                        </template>

                        <template x-if="restockData.type !== 'Sole'">
                            <div>
                                <label class="block text-gray-700 mb-1 font-semibold">Quantity to Restock</label>
                                <input type="number" x-model="restockData.quantity"
                                    @input="handleNumberInput($event, 'quantity')" placeholder="Quantity"
                                    class="block w-full p-2 border rounded"
                                    :min="restockData.type === 'Liquid Material' && (restockData.unit === 'ml' || restockData.unit === 'g') ? '0.01' : '0'"
                                    :step="restockData.type === 'Liquid Material' && (restockData.unit === 'ml' || restockData.unit === 'g') ? '0.01' : '0.1'"
                                    required>
                                <div class="mt-2 font-semibold text-gray-700 bg-blue-50 p-2 rounded"
                                    x-show="restockData.type === 'Liquid Material'">
                                    Total Measurement: <span class="text-blue-600"
                                        x-text="calculateMeasurement(restockData.quantity, restockData.unit)"></span>
                                </div>
                            </div>
                        </template>

                        <div>
                            <label class="block text-gray-700 mb-1 font-semibold">Reason for Restock</label>
                            <select x-model="restockData.reason" class="block w-full p-2 border rounded" required>
                                <option value="" disabled>Select Reason</option>
                                <option value="Purchase Order">Purchase Order</option>
                                <option value="Return">Return</option>
                                <option value="Adjustment">Adjustment</option>
                                <option value="Production">Production</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-1 font-semibold">Reference (Optional)</label>
                            <input type="text" x-model="restockData.reference" placeholder="e.g., PO Number"
                                class="block w-full p-2 border rounded">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="closeRestockModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 disabled:bg-gray-400"
                            :disabled="!isRestockFormValid()">Restock</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- New Stock Arrival Modal -->
        <div x-cloak x-show="open_stock_arrival_modal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-6 relative max-h-[90vh] overflow-y-auto">
                <button @click="closeStockArrivalModal()"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>

                <h2 class="text-2xl font-bold text-green-600 mb-4">Add Stock Arrival</h2>

                <form @submit.prevent="addStockArrivalItem" class="space-y-4">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-gray-700 mb-1 font-semibold">Select Sole</label>
                            <select x-model="stockArrivalData.id" @change="updateStockArrivalFields()"
                                class="block w-full p-2 border rounded" required>
                                <option value="">Select Sole</option>
                                <template x-for="product in products" :key="product.id">
                                    <template x-for="sole in product.soles_list" :key="sole.id">
                                        <option :value="sole.id"
                                            x-text="sole.name + (sole.color ? ' - ' + sole.color : '')">
                                        </option>
                                    </template>
                                </template>

                            </select>
                        </div>

                        <!-- Article No Selection -->
                        <!-- <div>
            <label class="block text-gray-700 mb-1 font-semibold">Article No</label>
            <select x-model="stockArrivalData.article_no" class="block w-full p-2 border rounded" required>
                <option value="">Select Article No</option>
                <template x-for="product in products" :key="product.id">
                    <option :value="product.name" x-text="product.name + (product.sku ? ' - ' + product.sku : '')"></option>
                </template>
                                        </select>
    </div> -->



                       <div>
        <label class="block text-gray-700 mb-1 font-semibold">Supplier</label>
        <select x-model="stockArrivalData.supplier_id" class="block w-full p-2 border rounded" required>
            <option value="">Select Supplier</option>
            <template x-for="supplier in supplierList" :key="supplier.id">
                <option :value="supplier.id" x-text="supplier.name + (supplier.business_name ? ' - ' + supplier.business_name : '')"></option>
            </template>
        </select>
    </div>

                        <div>
                            <label class="block text-gray-700 mb-1 font-semibold">Sizes & Quantity to Add</label>
                            <div class="flex flex-wrap gap-3 overflow-x-auto p-2 bg-gray-50 rounded">
                                <template x-for="size in Array.from({length:10},(_,i)=>35+i)" :key="size">
                                    <div class="flex flex-col items-center min-w-[60px]">
                                        <span class="text-sm font-medium" x-text="size"></span>
                                        <input type="number" 
                                               x-model="stockArrivalData.sizes_qty[size]" 
                                               @input="handleNumberInput($event, 'sizes_qty', size)"
                                               placeholder="Qty" 
                                               class="w-16 p-1 border rounded mt-1 text-center" 
                                               min="0">
                                    </div>
                                </template>
                            </div>
                            <div class="mt-2 font-semibold text-gray-700 bg-blue-50 p-2 rounded">
                                Total Quantity to Add: <span class="text-blue-600" x-text="Object.values(stockArrivalData.sizes_qty).reduce((a,b)=>Number(a||0)+Number(b||0),0)"></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-1 font-semibold">Reason for Arrival</label>
                            <select x-model="stockArrivalData.reason" class="block w-full p-2 border rounded" required>
                                <option value="" disabled>Select Reason</option>
                                <option value="Purchase Order">Purchase Order</option>
                                <option value="Return">Return</option>
                                <option value="Adjustment">Adjustment</option>
                                <option value="Production">Production</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <!-- <div>
                            <label class="block text-gray-700 mb-1 font-semibold">Reference (Optional)</label>
                            <input type="text" x-model="stockArrivalData.reference" placeholder="e.g., PO Number" class="block w-full p-2 border rounded">
                        </div> -->
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="closeStockArrivalModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 disabled:bg-gray-400" 
                                :disabled="!isStockArrivalFormValid()">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Ensure x-cloak hides elements until Alpine.js loads */
        [x-cloak] {
            display: none !important;
        }

        /* Container and header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .header h3 {
            font-size: 24px;
            font-weight: 600;
        }
        .header button {
            padding: 6px 12px;
            background-color: #22c55e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .header button:hover {
            background-color: #16a34a;
        }

        /* Outer table */
        .table-container {
            overflow-x: auto;
        }
        .outer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .outer-table th, .outer-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        .outer-table th {
            background-color: #4f46e5;
            color: white;
        }

        /* Inner table */
        .inner-table {
            width: 100%;
            border-collapse: collapse;
        }
        .inner-table th, .inner-table td {
            border: 1px solid #ccc;
            padding: 4px;
            text-align: left;
            font-size: 14px;
        }
        .inner-table th {
            background-color: #000000;
        }

        /* Action buttons */
        .outer-table td button {
            margin-right: 4px;
            padding: 4px 8px;
            font-size: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .outer-table td button:nth-child(1) { background-color: #3b82f6; color: white; }
        .outer-table td button:nth-child(1):hover { background-color: #2563eb; }
        .outer-table td button:nth-child(2) { background-color: #22c55e; color: white; }
        .outer-table td button:nth-child(2):hover { background-color: #16a34a; }
        .outer-table td button:nth-child(3) { background-color: #ef4444; color: white; }
        .outer-table td button:nth-child(3):hover { background-color: #dc2626; }

        /* Hover effect for outer rows */
        .outer-table tbody tr:hover {
            background-color: #f9fafb;
        }
    </style>


    <!-- <script>
    function markAsReceived(record) {
        if (!confirm(`Mark ${record.qty} of ${record.name} (Size ${record.size}) as received?`)) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/stock-arrival/${record.id}/receive`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(res => {
            if (!res.success) {
                alert('Failed to mark as received: ' + (res.message || 'Unknown error'));
                return;
            }

            // ✅ Remove from stockArrivalRecords (since it's now received)
            window.rawMaterials.stockArrivalRecords = 
                window.rawMaterials.stockArrivalRecords.filter(r => r.id !== record.id);

            // ✅ Update sole's available quantity in frontend
            const products = window.rawMaterials.products;
            let updated = false;

            for (let product of products) {
                if (product.soles_list) {
                    for (let sole of product.soles_list) {
                        if (sole.id == record.item_id) { // Note: loose comparison for string IDs
                            // Ensure sizes_qty is properly structured
                            if (!sole.sizes_qty || typeof sole.sizes_qty !== 'object') {
                                sole.sizes_qty = {};
                            }

                            // Normalize all size entries
                            Object.keys(sole.sizes_qty).forEach(size => {
                                let sizeData = sole.sizes_qty[size];
                                if (typeof sizeData !== 'object') {
                                    sole.sizes_qty[size] = {
                                        qty_available: parseFloat(sizeData) || 0,
                                        in_transit: 0
                                    };
                                } else {
                                    sole.sizes_qty[size].qty_available = parseFloat(sizeData.qty_available) || 0;
                                    sole.sizes_qty[size].in_transit = parseFloat(sizeData.in_transit) || 0;
                                }
                            });

                            // Add received quantity to available
                            if (!sole.sizes_qty[record.size]) {
                                sole.sizes_qty[record.size] = { qty_available: 0, in_transit: 0 };
                            }

                            const sizeData = sole.sizes_qty[record.size];
                            const qty = parseFloat(record.qty);

                            // ✅ Add to available qty
                            sizeData.qty_available += qty;

                            // ✅ Recalculate totals
                            sole.available_qty = Object.values(sole.sizes_qty)
                                .reduce((sum, s) => sum + (s.qty_available || 0), 0);
                            sole.total_quantity = sole.available_qty;
                            sole.total_price = (sole.price || 0) * sole.available_qty;

                            updated = true;
                            break;
                        }
                    }
                    if (updated) break;
                }
            }

            // Trigger reactivity
            window.rawMaterials.products = [...products];

            alert('✅ Stock received and added to available inventory!');
        })
        .catch(err => {
            console.error('Stock receive error:', err);
            alert('Failed to mark as received: ' + err.message);
        });
    }
    </script> -->










    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
    function rawMaterials() {
        console.log('rawMaterials component initializing');
        try {
            return {
                open_modal: false,
                open_restock_modal: false,
                open_stock_arrival_modal: false,
                modal_type: '',
                isEditing: false,
                currentProductId: null,
                currentItemId: null,
                activeTab: 'sole',
                activeSoleSubTab: 'details',
                step: 1,
                supplierList: @json($suppliers ?? []) || [], // <-- new suppliers list

                stockArrivalRecords: @json($stockArrivals ?? []) || [],

                formData: {
                    id: '',
                    product_id: '',
                    name: '',
                    color: '',
                    unit: '',
                    price: null,
                    sole_type: '',
                    quantity: null,
                    per_unit_length: null,
                    per_unit_volume: null,
                    sizes_qty: Object.fromEntries(Array.from({length:10},(_,i)=>[35+i, null]))
                },
                restockData: {
                    id: '',
                    type: '',
                    product_id: '',
                    name: '',
                    unit: '',
                    quantity: null,
                    sizes_qty: Object.fromEntries(Array.from({length:10},(_,i)=>[35+i, null])),
                    reason: '',
                    reference: '',
                    article_no: '',
                    supplier_id: '' // <-- replaced party with supplier_id
                },
                stockArrivalData: {
                    id: '',
                    sizes_qty: Object.fromEntries(Array.from({length:10},(_,i)=>[35+i, null])),
                    reason: '',
                    reference: '',
                    article_no: '',
                    supplier_id: '' // <-- replaced party with supplier_id
                },
                products: @json($products) || [],

                init() {
                    console.log('Initializing rawMaterials component');
                    console.log('Raw products:', JSON.stringify(this.products, null, 2));
                    try {
                        this.products = this.products.map(product => ({
                            ...product,
                            id: product.id ?? 'independent',
                            soles_list: (product.soles_list || []).map(sole => ({
                                ...sole,
                                showSizes: false
                            })),
                            materials_list: product.materials_list || [],
                            liquids_list: product.liquids_list || []
                        }));
                        const savedTab = localStorage.getItem('activeRawMaterialsTab') || 'sole';
                        this.activeTab = ['sole', 'materials', 'liquids'].includes(savedTab) ? savedTab : 'sole';
                        this.openTab(null, this.activeTab);
                    } catch (error) {
                        console.error('Init error:', error);
                        alert('An error occurred while initializing the page');
                    }
                },

                closeModal() {
                    this.open_modal = false;
                    this.resetFormData();
                },

                openSoleSubTab(tab) {
                    this.activeSoleSubTab = tab;
                },


                openModal(type, productId = null) {
                    console.log('Opening modal for type:', type, 'with productId:', productId);
                    this.modal_type = type;
                    this.currentProductId = productId ?? (this.products.length > 0 ? this.products[0].id : null);
                    this.isEditing = false;
                    this.currentItemId = null;
                    this.resetFormData();
                    this.open_modal = true;
                },

                resetFormData() {
                    this.formData = {
                        id: '',
                        product_id: '',
                        name: '',
                        color: '',
                        unit: '',
                        price: null,
                        sole_type: '',
                        quantity: null,
                        per_unit_length: null,
                        per_unit_volume: null,
                        sizes_qty: Object.fromEntries(Array.from({length:10},(_,i)=>[35+i, null]))
                    };
                },

                openRestockModal(item, type, productId) {
                    this.restockData = {
                        id: item.id,
                        type: type,
                        product_id: productId,
                        name: item.name || '',
                        unit: item.unit || '',
                        quantity: null,
                        sizes_qty: Object.fromEntries(Array.from({length:10},(_,i)=>[35+i, null])),
                        reason: '',
                        reference: '',
                        article_no: item.article_no || '',
                        party: item.party || ''
                    };
                    this.open_restock_modal = true;
                },

                restockItem() {
                    if (!this.isRestockFormValid()) {
                        alert('Please fill in all required fields with valid values');
                        return;
                    }

                    const payload = {
                        type: this.restockData.type,
                        quantity: this.restockData.type === 'Sole' ? undefined : Number(this.restockData.quantity),
                        sizes_qty: this.restockData.type === 'Sole' ? Object.fromEntries(
                            Object.entries(this.restockData.sizes_qty).filter(([_, qty]) => Number(qty) > 0).map(([size, qty]) => [size, Number(qty)])
                        ) : undefined,
                        reason: this.restockData.reason,
                        reference: this.restockData.reference || undefined,
                        article_no: this.restockData.article_no || undefined,
                        party: this.restockData.party || undefined
                    };

                    fetch(`/raw-materials/${this.restockData.id}/restock`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (!res.success) {
                            console.error('Restock response:', res);
                            alert('Failed to restock: ' + (res.message || 'Unknown error'));
                            return;
                        }

                        const product = this.products.find(p => p.id === this.restockData.product_id);
                        if (product) {
                            const item = [...product.soles_list, ...product.materials_list, ...product.liquids_list].find(i => i.id === this.restockData.id);
                            if (item) {
                                if (this.restockData.type === 'Sole') {
                                    Object.entries(this.restockData.sizes_qty).forEach(([size, qty]) => {
                                        if (qty > 0) {
                                            item.available_qty_per_size = item.available_qty_per_size || {};
                                            item.available_qty_per_size[size] = (item.available_qty_per_size[size] || 0) + Number(qty);
                                        }
                                    });
                                    item.available_qty = Object.values(item.available_qty_per_size).reduce((sum, val) => sum + val, 0);
                                } else {
                                    item.available_qty = (item.available_qty || 0) + Number(this.restockData.quantity);
                                }
                            }
                        }

                        if (res.movements && this.restockData.type === 'Sole') {
                            res.movements.forEach(movement => {
                                this.stockArrivalRecords.push(movement);
                            });
                        }

                        this.products = [...this.products];
                        this.closeRestockModal();
                        alert('Item restocked successfully');
                    })
                    .catch(err => {
                        console.error('Restock error:', err);
                        alert('Failed to restock: ' + err.message);
                    });
                },

                closeRestockModal() {
                    this.open_restock_modal = false;
                    this.resetRestockData();
                },

                resetRestockData() {
                    this.restockData = {
                        id: '',
                        type: '',
                        product_id: '',
                        name: '',
                        unit: '',
                        quantity: null,
                        sizes_qty: Object.fromEntries(Array.from({length:10},(_,i)=>[35+i, null])),
                        reason: '',
                        reference: '',
                        article_no: '',
                        supplier_id: '' // <-- reset supplier_id
                    };
                },

                isRestockFormValid() {
                    if (this.restockData.type === 'Sole') {
                        return this.restockData.reason &&
                               Object.values(this.restockData.sizes_qty).some(qty => qty !== null && qty !== undefined && Number(qty) > 0);
                    }
                    return this.restockData.quantity !== null && this.restockData.quantity > 0 && this.restockData.reason;
                },

                openStockArrivalModal() {
                    this.resetStockArrivalData();
                    this.open_stock_arrival_modal = true;
                },

                closeStockArrivalModal() {
                    this.open_stock_arrival_modal = false;
                    this.resetStockArrivalData();
                },

                resetStockArrivalData() {
                    this.stockArrivalData = {
                        id: '',
                        sizes_qty: Object.fromEntries(Array.from({length:10},(_,i)=>[35+i, null])),
                        reason: '',
                        reference: '',
                        article_no: '',
                        supplier_id: '' // <-- reset supplier_id
                    };
                },

                isStockArrivalFormValid() {
                    return this.stockArrivalData.id &&
                           this.stockArrivalData.reason &&
                           this.stockArrivalData.supplier_id &&
                           Object.values(this.stockArrivalData.sizes_qty).some(qty => qty !== null && qty !== undefined && Number(qty) > 0);
                },

                 updateStockArrivalFields() {
                    const selectedSole = this.products
                        .flatMap(product => product.soles_list.map(sole => ({ ...sole, productId: product.id })))
                        .find(sole => sole.id === this.stockArrivalData.id);
                    if (selectedSole) {
                        this.stockArrivalData.article_no = selectedSole.article_no || '';
                        this.stockArrivalData.supplier_id = selectedSole.supplier_id || '';
                    } else {
                        this.stockArrivalData.article_no = '';
                        this.stockArrivalData.supplier_id = '';
                    }
                },

           addStockArrivalItem() {
                    if (!this.isStockArrivalFormValid()) {
                        alert('Please fill in all required fields with valid values');
                        return;
                    }

                    const product = this.products.find(p => p.soles_list.some(s => s.id === this.stockArrivalData.id));
                    const sole = product ? product.soles_list.find(s => s.id === this.stockArrivalData.id) : null;

                    const payload = {
                        type: 'Sole',
                        sizes_qty: Object.fromEntries(
                            Object.entries(this.stockArrivalData.sizes_qty)
                                  .filter(([_, qty]) => Number(qty) > 0)
                                  .map(([size, qty]) => [size, Number(qty)])
                        ),
                        reason: this.stockArrivalData.reason,
                        reference: this.stockArrivalData.reference || undefined,
                        article_no: this.stockArrivalData.article_no || (sole?.article_no ?? undefined),
                        supplier_id: this.stockArrivalData.supplier_id || (product?.default_supplier_id ?? undefined),
                    };

                    console.log('Sending stock arrival payload:', JSON.stringify(payload, null, 2));

                    this.$dispatch('loading', true);

                    fetch(`/raw-materials/${this.stockArrivalData.id}/restock`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (!res.success) {
                            alert('Failed to add stock arrival: ' + (res.message || 'Unknown error'));
                            return;
                        }

                        if (res.movements && Array.isArray(res.movements)) {
                            this.stockArrivalRecords = [...(Array.isArray(this.stockArrivalRecords) ? this.stockArrivalRecords : []), ...res.movements];
                        }

                        if (product && sole) {
                            const updatedSizesQty = {...sole.sizes_qty};
                            Object.entries(this.stockArrivalData.sizes_qty).forEach(([size, qty]) => {
                                if (qty > 0) {
                                    updatedSizesQty[size] = updatedSizesQty[size] || { qty_available: 0, in_transit: 0 };
                                    updatedSizesQty[size].in_transit += Number(qty);
                                }
                            });
                            const updatedSole = {
                                ...sole,
                                sizes_qty: updatedSizesQty,
                                available_qty: Object.values(updatedSizesQty)
                                    .reduce((sum, val) => sum + (val.qty_available || 0) + (val.in_transit || 0), 0)
                            };
                            product.soles_list = product.soles_list.map(s => s.id === updatedSole.id ? updatedSole : s);
                            this.products = [...this.products];
                        }

                        this.resetStockArrivalData();
                        this.closeStockArrivalModal();
                        alert('Stock arrival added successfully');
                    })
                    .catch(err => {
                        console.error('Stock arrival error:', err);
                        alert('Failed to add stock arrival: ' + err.message);
                    })
                    .finally(() => {
                        this.$dispatch('loading', false);
                    });
                },

                // ✅ ADD THIS METHOD INSIDE THE RETURN OBJECT
        markAsReceived(record) {
        // Prompt user for quantity to receive
        let maxQty = parseFloat(record.qty) || 0;
        let qtyToReceive = parseFloat(prompt(`Enter quantity to receive for ${record.name} (Max: ${maxQty})`, maxQty));

        if (!qtyToReceive || qtyToReceive <= 0 || qtyToReceive > maxQty) {
            alert(`Invalid quantity! Please enter a number between 1 and ${maxQty}`);
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/stock-arrival/${record.id}/receive`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ qty_to_receive: qtyToReceive })
        })
        .then(res => res.json())
        .then(res => {
            if (!res.success) {
                alert('Failed to mark as received: ' + (res.message || 'Unknown error'));
                return;
            }

            // Update arrival record quantity
            record.qty -= qtyToReceive;
            if (record.qty <= 0) {
                // Remove fully received record
                this.stockArrivalRecords = this.stockArrivalRecords.filter(r => r.id !== record.id);
            }

            // Update soles in products
            this.products.forEach(product => {
                if (!product.soles_list) return;

                product.soles_list.forEach(sole => {
                    if (sole.id != record.item_id) return;

                    if (!sole.sizes_qty || typeof sole.sizes_qty !== 'object') {
                        sole.sizes_qty = {};
                    }

                    let sizeKey = record.size;
                    if (!sole.sizes_qty[sizeKey]) {
                        sole.sizes_qty[sizeKey] = { qty_available: 0, in_transit: 0 };
                    }

                    // Move partial quantity from in_transit to qty_available
                    sole.sizes_qty[sizeKey].qty_available += qtyToReceive;
                    sole.sizes_qty[sizeKey].in_transit = Math.max(0, (sole.sizes_qty[sizeKey].in_transit || 0) - qtyToReceive);

                    // Recalculate totals
                    sole.available_qty = Object.values(sole.sizes_qty)
                        .reduce((sum, s) => sum + (parseFloat(s.qty_available) || 0), 0);
                    sole.in_transit_qty = Object.values(sole.sizes_qty)
                        .reduce((sum, s) => sum + (parseFloat(s.in_transit) || 0), 0);
                    sole.total_quantity = sole.available_qty + sole.in_transit_qty;
                    sole.total_price = (parseFloat(sole.price) || 0) * sole.available_qty;
                });
            });

            // Trigger reactivity
            this.products = [...this.products];
            this.stockArrivalRecords = [...this.stockArrivalRecords];

            alert(`✅ ${qtyToReceive} units received and added to inventory!`);
        })
        .catch(err => {
            console.error('Stock receive error:', err);
            alert('Failed to mark as received: ' + err.message);
        });
    },


       handleNumberInput(event, field, size = null) {
                    const value = event.target.value;
                    if (size !== null) {
                        this.formData.sizes_qty[size] = value === '' ? null : Number(value);
                        this.restockData.sizes_qty[size] = value === '' ? null : Number(value);
                        this.stockArrivalData.sizes_qty[size] = value === '' ? null : Number(value);
                    } else {
                        this.formData[field] = value === '' ? null : Number(value);
                        this.restockData[field] = value === '' ? null : Number(value);
                    }
                },

                openTab(evt, tabName) {
                    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
                    const tabContent = document.getElementById(tabName);
                    if (tabContent) tabContent.classList.remove('hidden');
                    this.activeTab = tabName;
                    localStorage.setItem('activeRawMaterialsTab', tabName);
                },

                formatQuantity(quantity, unit) {
                    if (quantity === null || quantity === undefined) return 'N/A';
                    return unit === 'g' || unit === 'ml' ? quantity.toFixed(2) : Math.floor(quantity);
                },

                calculateMeasurement(quantity, unit, perUnitLength = null, perUnitVolume = null) {
                    if (!quantity || !unit) return 'N/A';
                    if (unit === 'piece') {
                        if (perUnitLength) return (quantity * perUnitLength).toFixed(2) + ' m';
                        if (perUnitVolume) return (quantity * perUnitVolume).toFixed(2) + ' l';
                    }
                    return this.formatQuantity(quantity, unit) + ' ' + unit;
                },

                calculatePricePerUnit(quantity, price, unit, perUnitLength = null, perUnitVolume = null) {
                    if (!quantity || !price || !unit) return 'N/A';
                    let perUnitPrice;
                    if (unit === 'piece') {
                        if (perUnitLength) {
                            perUnitPrice = price / (quantity * perUnitLength);
                            return perUnitPrice.toFixed(2) + ' /m';
                        }
                        if (perUnitVolume) {
                            perUnitPrice = price / (quantity * perUnitVolume);
                            return perUnitPrice.toFixed(2) + (perUnitVolume >= 1 ? ' /l' : ' /ml');
                        }
                        perUnitPrice = price / quantity;
                        return perUnitPrice.toFixed(2) + ' /piece';
                    }
                    perUnitPrice = price / quantity;
                    return perUnitPrice.toFixed(2) + ' /' + unit;
                },

                editItem(item, type, productId) {
                    this.modal_type = type;
                    this.isEditing = true;
                    this.currentProductId = productId;
                    this.currentItemId = item.id;
                    this.formData = {
                        id: item.id,
                        product_id: productId,
                        name: item.name || '',
                        color: item.color || '',
                        unit: item.unit || '',
                        price: Number(item.price) || null,
                        sole_type: item.sole_type || '',
                        quantity: item.quantity || null,
                        per_unit_length: item.per_unit_length || null,
                        per_unit_volume: item.per_unit_volume || null,
                        sizes_qty: item.sizes_qty ? Object.fromEntries(
                            Object.entries(item.sizes_qty).map(([size, qty]) => [size, qty])
                        ) : Object.fromEntries(Array.from({length:10},(_,i)=>[35+i, null]))
                    };
                    this.open_modal = true;
                },

             saveMaterial() {
        if (!this.isFormValid()) {
            alert('Please fill in all required fields with valid values');
            return;
        }

        const productId = this.currentProductId ? Number(this.currentProductId) : null;

        // Prepare sizes payload carefully: when editing, don't send sizes if user didn't provide any
        const sizesProcessed = this.modal_type === 'Sole'
            ? Object.fromEntries(
                  Object.entries(this.formData.sizes_qty || {})
                        .filter(([_, qty]) => qty !== null && qty !== undefined && Number(qty) > 0)
                        .map(([size, qty]) => [size, Number(qty)])
              )
            : null;

        const payload = {
            type: this.modal_type,
            product_id: productId,
            name: this.formData.name,
            color: this.formData.color || null,
            unit: this.formData.unit || null,
            price: this.modal_type === 'Sole' ? Number(this.formData.price) : Number(this.formData.price ?? 0),
            sole_type: this.formData.sole_type || null,
            quantity: this.formData.quantity != null ? Number(this.formData.quantity) : null,
            per_unit_length: this.formData.per_unit_length != null ? Number(this.formData.per_unit_length) : null,
            per_unit_volume: this.formData.per_unit_volume != null ? Number(this.formData.per_unit_volume) : null,
            // default - may be removed for edits below
            sizes_qty: null
        };

        if (this.modal_type === 'Sole') {
            if (!this.isEditing) {
                // Creating a new sole — include processed sizes (validation ensures some exist)
                payload.sizes_qty = sizesProcessed || {};
            } else if (sizesProcessed && Object.keys(sizesProcessed).length > 0) {
                // Editing and user provided some sizes — include only those to update
                payload.sizes_qty = sizesProcessed;
            } else {
                // Editing but no sizes provided — omit sizes_qty so backend won't overwrite existing sizes
                delete payload.sizes_qty;
            }
        } else {
            payload.sizes_qty = null;
        }

        const url = this.isEditing 
            ? `/raw-materials/${this.currentItemId}` 
            : '/raw-materials/store';
        const method = this.isEditing ? 'PUT' : 'POST';

        fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(res => {
            console.log('Save response:', res);
            if (!res.success) {
                alert('Failed to save: ' + (res.message || 'Unknown error'));
                return;
            }

            // Find or create product
            let product = this.products.find(p => p.id === productId);
            if (!product) {
                product = { 
                    id: productId ?? 'independent', 
                    soles_list: [], 
                    materials_list: [], 
                    liquids_list: [] 
                };
                this.products.push(product);
            }

            const listKey = this.modal_type === 'Sole' ? 'soles_list' :
                            this.modal_type === 'Material' ? 'materials_list' : 'liquids_list';

            if (!Array.isArray(product[listKey])) product[listKey] = [];

           // Create proper item object for UI
const item = res.data;  // backend returns "data"

const itemToAdd = {
    id: item.id,
    name: item.name || '',
    color: item.color || '',
    unit: item.unit || '',
    quantity: Number(item.quantity ?? 0),
    available_qty: Number(item.available_qty ?? item.quantity ?? 0),
    per_unit_length: Number(item.per_unit_length ?? 0),
    per_unit_volume: Number(item.per_unit_volume ?? 0),
    price: Number(item.price ?? 0),

    total_measurement: this.modal_type === 'Material'
        ? (item.per_unit_length ? item.quantity * item.per_unit_length : item.quantity)
        : (item.per_unit_volume ? item.quantity * item.per_unit_volume : item.quantity),

    per_unit_price_text: this.modal_type === 'Material'
        ? (item.per_unit_length 
            ? `${(item.price / (item.quantity * item.per_unit_length)).toFixed(2)} /m`
            : `${(item.price / item.quantity).toFixed(2)} /${item.unit}`)
        : (item.per_unit_volume
            ? `${(item.price / (item.quantity * item.per_unit_volume)).toFixed(2)} /l`
            : `${(item.price / item.quantity).toFixed(2)} /${item.unit}`),

    type: this.modal_type,
    showSizes: false
};


            // Add or update in product list
            if (this.isEditing) {
                const index = product[listKey].findIndex(item => item.id === this.currentItemId);
                if (index !== -1) {
                    product[listKey][index] = { ...itemToAdd, showSizes: product[listKey][index].showSizes || false };
                } else {
                    product[listKey].push(itemToAdd);
                }
            } else {
                product[listKey].push(itemToAdd);
            }

            // Trigger reactivity
            this.products = [...this.products];

            this.closeModal();
            alert(this.isEditing ? 'Item updated successfully' : 'Item added successfully');
        })
        .catch(err => {
            console.error('Save error:', err);
            alert('Failed to save: ' + err.message);
        });
    },

        deleteItem(itemId, type, productId) {
        if (!confirm('Are you sure you want to delete this item?')) return;

        fetch(`/raw-materials/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ type }) // <-- send type here
        })
        .then(res => res.json())
        .then(res => {
            if (!res.success) {
                console.error('Delete response:', res);
                alert('Failed to delete: ' + (res.message || 'Unknown error'));
                return;
            }

            const product = this.products.find(p => p.id === productId);
            const listKey = type === 'Sole' ? 'soles_list' :
                            type === 'Material' ? 'materials_list' : 'liquids_list';

            product[listKey] = product[listKey].filter(item => item.id !== itemId);
            this.products = [...this.products];
            alert('Item deleted successfully');
        })
        .catch(err => {
            console.error('Delete error:', err);
            alert('Failed to delete: ' + err.message);
        });
    },


                isFormValid() {
                    if (this.modal_type === 'Sole') {
                        // ✅ When editing, quantities are optional; only required when creating new
                        const hasQuantities = Object.values(this.formData.sizes_qty).some(qty => qty !== null && qty !== undefined && Number(qty) > 0);
                        const quantityIsRequired = !this.isEditing; // Only mandatory when adding new sole
                        
                        return this.formData.name &&
                               this.formData.color &&
                               (quantityIsRequired ? hasQuantities : true) &&
                               this.formData.price !== null && this.formData.price >= 0;
                    }
                    return this.formData.name &&
                           this.formData.unit &&
                           this.formData.quantity !== null && this.formData.quantity > 0 &&
                           this.formData.price !== null && this.formData.price >= 0;
                },

                handleUnitChange(event) {
                    if (this.formData.unit !== 'piece') {
                        this.formData.per_unit_length = null;
                        this.formData.per_unit_volume = null;
                    }
                },

                // Handle size category selection
                selectSizeCategory(event) {
                    const category = event.target.value;
                    // Reset all sizes first
                    Object.keys(this.formData.sizes_qty).forEach(size => {
                        this.formData.sizes_qty[size] = null;
                    });

                    // Define size ranges for each category
                    const sizeRanges = {
                        'large': Array.from({length: 5}, (_, i) => 40 + i),  // 40-44
                        'medium': Array.from({length: 2}, (_, i) => 38 + i), // 38-39
                        'small': Array.from({length: 4}, (_, i) => 34 + i),  // 34-37
                    };

                    // Highlight the selected category's sizes
                    if (category && sizeRanges[category]) {
                        sizeRanges[category].forEach(size => {
                            this.formData.sizes_qty[size] = 0;
                        });
                    }
                },

                // Store temporary bulk quantity
                bulkQuantity: 0,

                // Handle bulk quantity input
                setBulkQuantity(event) {
                    this.bulkQuantity = parseInt(event.target.value) || 0;
                },

                // Apply bulk quantity to selected sizes
                applyBulkQuantity() {
                    if (this.bulkQuantity <= 0) {
                        alert('Please enter a valid quantity');
                        return;
                    }

                    // Apply to all non-null sizes (selected by category)
                    Object.keys(this.formData.sizes_qty).forEach(size => {
                        if (this.formData.sizes_qty[size] !== null) {
                            this.formData.sizes_qty[size] = this.bulkQuantity;
                        }
                    });
                }
            };
        } catch (error) {
            console.error('rawMaterials initialization error:', error);
            return {};
        }
    }
    </script>

@endsection