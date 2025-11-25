<!-- Modal -->
    <div x-cloak x-show="open_modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative max-h-[80vh] overflow-y-auto">
            <button @click="open_modal=false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>

            <h2 class="text-2xl font-bold text-blue-600 mb-4" 
                x-text="modal_type==='Sole' ? 'Add New Sole' : (modal_type==='Material' ? 'Add New Material' : 'Add New Liquid')">
            </h2>

            <form @submit.prevent="saveMaterial" class="space-y-4">
                <!-- Dynamic Fields -->
                <template x-if="modal_type==='Sole'">
                    <div class="space-y-3">
                        <input type="text" x-model="formData.name" placeholder="Sole Name" class="block w-full p-2 border rounded" required>
                        <input type="text" x-model="formData.color" placeholder="Color" class="block w-full p-2 border rounded">
                        <input type="text" x-model="formData.sole_type" placeholder="Sole Type" class="block w-full p-2 border rounded">

                        <div>
                            <label class="block text-gray-700 mb-1 font-semibold">Sizes & Quantity</label>
                            <div class="flex flex-wrap gap-3">
                                <template x-for="size in Array.from({length:10},(_,i)=>35+i)" :key="size">
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-medium" x-text="size"></span>
                                        <input type="number" x-model.number="formData.sizes_qty[size]" placeholder="Qty" class="w-16 p-1 border rounded" min="0">
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="mt-2 font-semibold text-gray-700">
                            Total Quantity: <span x-text="Object.values(formData.sizes_qty).reduce((a,b)=>a+b,0)"></span>
                        </div>

                        <input type="number" x-model.number="formData.unit_price" placeholder="Unit Price" class="block w-full p-2 border rounded" min="0" step="0.01">
                        <input type="number" x-model.number="formData.selling_price" placeholder="Selling Price" class="block w-full p-2 border rounded" min="0" step="0.01">
                    </div>
                </template>

                <template x-if="modal_type==='Material' || modal_type==='Liquid Material'">
                    <div class="space-y-3">
                        <input type="text" x-model="formData.name" :placeholder="modal_type==='Material'?'Material Name':'Liquid Name'" class="block w-full p-2 border rounded" required>
                        <input type="text" x-model="formData.color" placeholder="Color" class="block w-full p-2 border rounded" x-show="modal_type==='Material'">

                        <select x-model="formData.unit" class="block w-full p-2 border rounded" required>
    <option value="" disabled>Select Unit</option>

    <!-- Material Units -->
    <option value="kg" x-show="modal_type==='Material'">Kilogram (kg)</option>
    <option value="g" x-show="modal_type==='Material'">Gram (g)</option>
    <option value="litre" x-show="modal_type==='Material'">Litre (l)</option>
    <option value="ml" x-show="modal_type==='Material'">Millilitre (ml)</option>
    <option value="piece" x-show="modal_type==='Material'">Piece</option>

    <!-- Liquid Material Units -->
    <option value="litre" x-show="modal_type==='Liquid Material'">Litre (l)</option>
    <option value="ml" x-show="modal_type==='Liquid Material'">Millilitre (ml)</option>
    <option value="kg" x-show="modal_type==='Liquid Material'">Kilogram (kg)</option>
    <option value="g" x-show="modal_type==='Liquid Material'">Gram (g)</option>
    <option value="piece" x-show="modal_type==='Liquid Material'">Piece</option>
</select>


                        <div x-show="formData.unit==='piece'">
                            <input type="number" x-model.number="formData.per_unit_length" placeholder="Per Piece Length (meters)" class="block w-full p-2 border rounded" min="0" step="0.01">
                        </div>

                        <input type="number" x-model.number="formData.quantity" placeholder="Quantity" class="block w-full p-2 border rounded" min="0">
                        <input type="number" x-model.number="formData.price" placeholder="Price per Unit" class="block w-full p-2 border rounded" min="0" step="0.01">
                    </div>
                </template>

                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" @click="open_modal=false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
                </div>
            </form>
        </div>
    </div>